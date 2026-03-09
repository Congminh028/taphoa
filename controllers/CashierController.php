<?php
// C:\xampp\htdocs\taphoa\controllers\CashierController.php

class CashierController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection(); // Kết nối cơ sở dữ liệu
    }
    public function index() { // Hàm này để hiện cái Dashboard 2 nút
    if (!isset($_SESSION['user_id'])) { 
        header("Location: index.php"); 
        exit(); 
    }
    $username = $_SESSION['username'] ?? 'User';
    $permissions = $_SESSION['permissions'] ?? []; 

    require_once '../views/cashier/dashboard.php';
}
    public function pos() {
        
        // 1. Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }

        // 1. Phân quyền (Optional)
        if (!in_array('Bán hàng', $_SESSION['permissions'])) {
            // header("Location: index.php?error=access_denied");
            // exit();
        }

        // Lấy cấu hình hệ thống
        // Đảm bảo bảng cài đặt tồn tại
        $this->db->exec("CREATE TABLE IF NOT EXISTS system_settings (
            setting_key varchar(50) NOT NULL,
            setting_value varchar(255) NOT NULL,
            description text,
            PRIMARY KEY (setting_key)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

        $this->db->exec("INSERT IGNORE INTO system_settings (setting_key, setting_value, description) VALUES 
            ('global_discount_percent', '0', 'Phần trăm giảm giá toàn hệ thống tại POS'),
            ('points_silver', '50', 'Số điểm tối thiểu để đạt hạng Bạc'),
            ('points_gold', '200', 'Số điểm tối thiểu để đạt hạng Vàng'),
            ('points_conversion_rate', '100000', 'Số tiền VNĐ tương ứng 1 điểm'),
            ('discount_silver', '0', 'Phần trăm giảm giá cho hạng Bạc'),
            ('discount_gold', '0', 'Phần trăm giảm giá cho hạng Vàng');");

        $stmtSettings = $this->db->query("SELECT setting_key, setting_value FROM system_settings");
        $results = $stmtSettings->fetchAll(PDO::FETCH_ASSOC);
        $settings = [];
        foreach($results as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        $globalDiscount = isset($settings['global_discount_percent']) ? (int)$settings['global_discount_percent'] : 0;
        $discountSilver = isset($settings['discount_silver']) ? (int)$settings['discount_silver'] : 0;
        $discountGold = isset($settings['discount_gold']) ? (int)$settings['discount_gold'] : 0;

        // 2. Load thông tin nhân viên
        $username = $_SESSION['username'] ?? 'User';
        $permissions = $_SESSION['permissions'] ?? []; 

        // Đảm bảo bảng orders có các cột cần thiết (FIX LỖI TRANSACTION)
        try {
            $this->db->exec("ALTER TABLE orders ADD COLUMN IF NOT EXISTS customer_id int(11) DEFAULT NULL AFTER id;");
            $this->db->exec("ALTER TABLE orders ADD COLUMN IF NOT EXISTS payment_method varchar(50) DEFAULT 'Tiền mặt' AFTER total_amount;");
        } catch(Exception $e) {}

        // 3. Lấy danh sách sản phẩm còn hàng để bán
        $query = "SELECT * FROM products WHERE stock > 0 ORDER BY name ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Lấy danh sách khách hàng để chọn
        $queryCust = "SELECT id, name, phone, tier FROM customers ORDER BY name ASC";
        $stmtCust = $this->db->prepare($queryCust);
        $stmtCust->execute();
        $customers = $stmtCust->fetchAll(PDO::FETCH_ASSOC);

        // 4. Gọi giao diện bán hàng
        require_once '../views/cashier/pos.php';
    }
    
    
    public function processPayment() {
        ob_start(); // Start output buffering
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Lỗi xác thực']);
            exit();
        }

        // Lấy dữ liệu gửi lên từ JS
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || empty($data['cart'])) {
            echo json_encode(['success' => false, 'message' => 'Giỏ hàng trống!']);
            exit();
        }

        $cart = $data['cart'];
        $totalAmount = $data['total'];
        $customerId = isset($data['customer_id']) && $data['customer_id'] != '' ? $data['customer_id'] : null;
        $customerName = isset($data['customer_name']) && $data['customer_name'] != '' ? $data['customer_name'] : 'Khách vãng lai';
        $paymentMethod = $data['payment_method'] ?? 'Tiền mặt';
        $orderCode = "HD-" . date('YmdHis');

        // Đảm bảo bảng orders có các cột cần thiết (FIX LỖI TRANSACTION)
        try {
            $this->db->exec("ALTER TABLE orders ADD COLUMN IF NOT EXISTS customer_id int(11) DEFAULT NULL AFTER id;");
            $this->db->exec("ALTER TABLE orders ADD COLUMN IF NOT EXISTS payment_method varchar(50) DEFAULT 'Tiền mặt' AFTER total_amount;");
        } catch(Exception $e) {}

        try {
            // Bắt đầu transaction
            $this->db->beginTransaction();

            // 1. Lưu hóa đơn chính vào bảng orders
            $queryOrder = "INSERT INTO orders (order_code, customer_id, customer_name, total_amount, payment_method) VALUES (:code, :customer_id, :customer, :total, :method)";
            $stmtOrder = $this->db->prepare($queryOrder);
            $stmtOrder->execute([
                ':code' => $orderCode,
                ':customer_id' => $customerId,
                ':customer' => $customerName,
                ':total' => $totalAmount,
                ':method' => $paymentMethod
            ]);
            
            $orderId = $this->db->lastInsertId();

            // 2. Lưu chi tiết sản phẩm & Trừ tồn kho
            $queryDetail = "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)";
            $stmtDetail = $this->db->prepare($queryDetail);

            $queryUpdateStock = "UPDATE products SET stock = stock - :qty WHERE id = :id AND stock >= :qty";
            $stmtUpdateStock = $this->db->prepare($queryUpdateStock);

            foreach ($cart as $item) {
                // Kiểm tra xem kho còn đủ không và trừ đi
                $stmtUpdateStock->execute([
                    ':qty' => $item['quantity'],
                    ':id' => $item['id']
                ]);

                // Nếu số dòng bị ảnh hưởng = 0 -> Tức là stock < qty (Không đủ hàng)
                if ($stmtUpdateStock->rowCount() == 0) {
                    throw new Exception("Sản phẩm '{$item['name']}' không đủ số lượng trong kho hoặc đã hết!");
                }

                // Lưu chi tiết
                $stmtDetail->execute([
                    ':order_id' => $orderId,
                    ':product_id' => $item['id'],
                    ':quantity' => $item['quantity'],
                    ':price' => $item['price']
                ]);
            }

            // 3. Tích điểm nếu có ID khách hàng
            if ($customerId) {
                require_once '../models/Customer.php';
                $customerModel = new Customer($this->db);
                $customerModel->updatePointsAndTier($customerId, $totalAmount);
            }

            // Hoàn tất transaction
            $this->db->commit();
            
            ob_end_clean(); // Clear any previous output (warnings etc)
            // Trả về JSON để client in hóa đơn
            echo json_encode([
                'success' => true, 
                'order_code' => $orderCode, 
                'total' => $totalAmount,
                'message' => 'Thanh toán thành công!'
            ]);

        } catch (Exception $e) {
            // Nếu có lỗi (hết hàng, lỗi DB...), quay vòng lại trạng thái ban đầu
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            $errorLog = date('Y-m-d H:i:s') . " - Error: " . $e->getMessage() . "\n";
            file_put_contents('../logs/checkout_error.log', $errorLog, FILE_APPEND); // Ghi log
            
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function order_history() {
        if (!isset($_SESSION['user_id'])) { 
            header("Location: index.php"); 
            exit(); 
        }

        $query = "SELECT * FROM orders ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $permissions = $_SESSION['permissions'] ?? [];

        require_once '../views/cashier/history.php';
    }
}