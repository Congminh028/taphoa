<?php
// controllers/CustomerController.php

class CustomerController {
    public function index() {
        // 1. Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }

        // 2. Lấy thông tin user cho Sidebar
        $username = $_SESSION['username'];
        $permissions = $_SESSION['permissions'] ?? [];

        // 3. Gọi giao diện Menu con của Khách hàng
        require_once '../views/customers/dashboard.php';
    }
    public function list() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }

        $db = (new Database())->getConnection();

        // Đảm bảo bảng khách hàng tồn tại (Tự động chạy)
        $db->exec("CREATE TABLE IF NOT EXISTS taphoa_db.customers (
            id int(11) NOT NULL AUTO_INCREMENT,
            phone varchar(20) NOT NULL UNIQUE,
            name varchar(255) NOT NULL,
            address text DEFAULT NULL,
            points int(11) DEFAULT 0,
            tier varchar(50) DEFAULT 'Đồng',
            created_at timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

        // Đảm bảo bảng orders có cột customer_id
        try {
            $db->exec("ALTER TABLE taphoa_db.orders ADD COLUMN customer_id int(11) DEFAULT NULL AFTER id;");
        } catch(PDOException $e) { /* Đã tồn tại */ }

        require_once '../models/Customer.php';
        $customerModel = new Customer($db);

        $customersStmt = $customerModel->readAll();
        $customers = $customersStmt->fetchAll(PDO::FETCH_ASSOC);

        $permissions = $_SESSION['permissions'] ?? [];
        require_once '../views/customers/list.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $db = (new Database())->getConnection();
            require_once '../models/Customer.php';
            $customerModel = new Customer($db);

            $id = $_POST['id'] ?? null;
            $data = [
                'name' => $_POST['name'],
                'phone' => $_POST['phone'],
                'address' => $_POST['address']
            ];

            if ($id) {
                // Update
                $result = $customerModel->update($id, $data);
                if ($result === true) {
                    $_SESSION['success'] = "Cập nhật thành công!";
                } else {
                    $_SESSION['error'] = $result; // Lỗi duplicate phone
                }
            } else {
                // Create
                $result = $customerModel->create($data);
                if ($result === true) {
                    $_SESSION['success'] = "Thêm khách hàng thành công!";
                } else {
                    $_SESSION['error'] = $result;
                }
            }

            header("Location: index.php?action=customer_list");
            exit();
        }
    }

    public function history() {
        if (!isset($_SESSION['user_id']) || !isset($_GET['customer_id'])) {
            echo json_encode(['success' => false, 'message' => 'Lỗi xác thực hoặc thiếu ID khách hàng']);
            exit();
        }

        $customer_id = $_GET['customer_id'];
        $db = (new Database())->getConnection();
        
        // Nhận tham số bộ lọc
        $dateFrom = $_GET['date_from'] ?? '';
        $dateTo = $_GET['date_to'] ?? '';
        $orderCode = $_GET['order_code'] ?? '';

        // Xây dựng câu truy vấn động
        $query = "SELECT * FROM orders WHERE customer_id = :customer_id";
        $params = [':customer_id' => $customer_id];

        if (!empty($dateFrom)) {
            $query .= " AND DATE(created_at) >= :date_from";
            $params[':date_from'] = $dateFrom;
        }
        if (!empty($dateTo)) {
            $query .= " AND DATE(created_at) <= :date_to";
            $params[':date_to'] = $dateTo;
        }
        if (!empty($orderCode)) {
            $query .= " AND order_code LIKE :order_code";
            $params[':order_code'] = "%$orderCode%";
        }

        $query .= " ORDER BY created_at DESC";
        
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Lấy chi tiết cho từng hóa đơn
        $queryDetails = "SELECT od.*, p.name 
                         FROM order_details od 
                         JOIN products p ON od.product_id = p.id 
                         WHERE od.order_id = :order_id";
        $stmtDetails = $db->prepare($queryDetails);

        $results = [];
        $totalSpent = 0;
        $orderCount = count($orders);

        foreach ($orders as $order) {
            $stmtDetails->execute([':order_id' => $order['id']]);
            $details = $stmtDetails->fetchAll(PDO::FETCH_ASSOC);
            
            $totalSpent += $order['total_amount'];
            $earnedPoints = floor($order['total_amount'] / 100000);

            $results[] = [
                'id' => $order['id'],
                'order_code' => $order['order_code'],
                'created_at' => date('d/m/Y H:i', strtotime($order['created_at'])),
                'total_amount' => $order['total_amount'],
                'points_earned' => $earnedPoints,
                'details' => $details
            ];
        }

        // Thống kê cơ bản
        $avgFrequency = "Chưa có dữ liệu";
        if ($orderCount >= 2) {
            $firstOrderDate = strtotime($orders[count($orders)-1]['created_at']);
            $lastOrderDate = strtotime($orders[0]['created_at']);
            $daysDiff = max(1, round(abs($lastOrderDate - $firstOrderDate) / 86400));
            $avgFrequency = round($daysDiff / ($orderCount - 1), 1) . " ngày/lần";
        }

        echo json_encode([
            'success' => true, 
            'data' => $results,
            'stats' => [
                'total_orders' => $orderCount,
                'total_spent' => $totalSpent,
                'avg_frequency' => $avgFrequency
            ]
        ]);
        exit();
    }

    public function loyalty() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }

        $db = (new Database())->getConnection();
        require_once '../models/Customer.php';
        $customerModel = new Customer($db);

        $customersStmt = $customerModel->readAll();
        $customers = $customersStmt->fetchAll(PDO::FETCH_ASSOC);

        $permissions = $_SESSION['permissions'] ?? [];
        require_once '../views/customers/loyalty.php';
    }

    public function vouchers() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }

        $db = (new Database())->getConnection();

        // Đảm bảo bảng cài đặt tồn tại
        $db->exec("CREATE TABLE IF NOT EXISTS system_settings (
            setting_key varchar(50) NOT NULL,
            setting_value varchar(255) NOT NULL,
            description text,
            PRIMARY KEY (setting_key)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

        $db->exec("INSERT IGNORE INTO system_settings (setting_key, setting_value, description) VALUES 
            ('global_discount_percent', '0', 'Phần trăm giảm giá toàn hệ thống tại POS'),
            ('points_silver', '50', 'Số điểm tối thiểu để đạt hạng Bạc'),
            ('points_gold', '200', 'Số điểm tối thiểu để đạt hạng Vàng'),
            ('points_conversion_rate', '100000', 'Số tiền VNĐ tương ứng 1 điểm'),
            ('discount_silver', '0', 'Phần trăm giảm giá cho hạng Bạc'),
            ('discount_gold', '0', 'Phần trăm giảm giá cho hạng Vàng');");

        $stmtSettings = $db->query("SELECT setting_key, setting_value FROM system_settings");
        $results = $stmtSettings->fetchAll(PDO::FETCH_ASSOC);
        $settings = [];
        foreach($results as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }

        $conversionRate = isset($settings['points_conversion_rate']) ? (int)$settings['points_conversion_rate'] : 100000;
        $silverPoints = isset($settings['points_silver']) ? (int)$settings['points_silver'] : 50;
        $goldPoints = isset($settings['points_gold']) ? (int)$settings['points_gold'] : 200;
        $globalDiscount = isset($settings['global_discount_percent']) ? (int)$settings['global_discount_percent'] : 0;
        $discountSilver = isset($settings['discount_silver']) ? (int)$settings['discount_silver'] : 0;
        $discountGold = isset($settings['discount_gold']) ? (int)$settings['discount_gold'] : 0;

        $permissions = $_SESSION['permissions'] ?? [];
        require_once '../views/customers/vouchers.php';
    }

    public function settings() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }

        $db = (new Database())->getConnection();
        
        // Đảm bảo bảng cài đặt tồn tại
        $db->exec("CREATE TABLE IF NOT EXISTS system_settings (
            setting_key varchar(50) NOT NULL,
            setting_value varchar(255) NOT NULL,
            description text,
            PRIMARY KEY (setting_key)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

        $db->exec("INSERT IGNORE INTO system_settings (setting_key, setting_value, description) VALUES 
            ('global_discount_percent', '0', 'Phần trăm giảm giá toàn hệ thống tại POS'),
            ('points_silver', '50', 'Số điểm tối thiểu để đạt hạng Bạc'),
            ('points_gold', '200', 'Số điểm tối thiểu để đạt hạng Vàng'),
            ('points_conversion_rate', '100000', 'Số tiền VNĐ tương ứng 1 điểm'),
            ('discount_silver', '0', 'Phần trăm giảm giá cho hạng Bạc'),
            ('discount_gold', '0', 'Phần trăm giảm giá cho hạng Vàng');");

        // Lấy tất cả cài đặt
        $stmt = $db->query("SELECT setting_key, setting_value FROM system_settings");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $settings = [];
        foreach($results as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }

        $permissions = $_SESSION['permissions'] ?? [];
        require_once '../views/settings/membership.php';
    }

    public function updateSettings() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
            $db = (new Database())->getConnection();
            $stmt = $db->prepare("UPDATE system_settings SET setting_value = :value WHERE setting_key = :key");

            try {
                // Duyệt qua tất cả POST data và cập nhật
                $db->beginTransaction();
                foreach($_POST as $key => $value) {
                    $stmt->execute([
                        ':value' => $value,
                        ':key' => $key
                    ]);
                }
                $db->commit();
                $_SESSION['success'] = "Cập nhật cấu hình thành công!";
            } catch (Exception $e) {
                $db->rollBack();
                $_SESSION['error'] = "Có lỗi xảy ra: " . $e->getMessage();
            }

            header("Location: index.php?action=" . ($_POST['redirect_to'] ?? 'settings'));
            exit();
        }
    }
}
?>