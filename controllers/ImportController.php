<?php
// controllers/ImportController.php
require_once '../models/Import.php';
require_once '../models/Product.php';

// controllers/ImportController.php
class ImportController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection(); // ĐẢM BẢO CÓ DÒNG NÀY
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit(); }
        $username = $_SESSION['username'] ?? 'User';
        $permissions = $_SESSION['permissions'] ?? [];
        require_once '../views/imports/dashboard.php';
    }
    // controllers/ImportController.php

public function store() {
    // 1. Kiểm tra Session để tránh bị văng ra Login
    if (!isset($_SESSION['user_id'])) { 
        header("Location: index.php"); 
        exit(); 
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Nhận thông tin chung của phiếu nhập
        $import_code = $_POST['import_code'] ?? 'PN-' . time();
        $supplier = $_POST['supplier'] ?? 'N/A';
        
        // Nhận danh sách sản phẩm từ bảng nhập liệu
        $p_ids = $_POST['p_id'] ?? [];
        $qtys = $_POST['qty'] ?? [];
        $prices = $_POST['price'] ?? [];

        if (empty($p_ids)) {
            echo "<script>alert('Lỗi: Phiếu nhập chưa có sản phẩm nào!'); window.history.back();</script>";
            exit();
        }

        try {
            $this->db->beginTransaction(); // Bắt đầu giao dịch an toàn

            // BƯỚC A: Lưu thông tin phiếu vào bảng 'imports' để theo dõi lịch sử
            $total_bill = 0;
            for ($i = 0; $i < count($p_ids); $i++) {
                $total_bill += ($qtys[$i] * $prices[$i]);
            }

            $sql_import = "INSERT INTO imports (import_code, supplier, total_amount) VALUES (:code, :sup, :total)";
            $stmt_import = $this->db->prepare($sql_import);
            $stmt_import->execute([
                ':code' => $import_code,
                ':sup' => $supplier,
                ':total' => $total_bill
            ]);

            // BƯỚC B: Đồng bộ sang Kho hàng (UC1) cho từng sản phẩm
            for ($i = 0; $i < count($p_ids); $i++) {
                $id = $p_ids[$i];
                $qty = $qtys[$i];
                $price = $prices[$i];

                // Lệnh quan trọng: Tồn mới = Tồn cũ + Qty | Giá vốn = Giá nhập mới nhất
                $sql_update = "UPDATE products SET 
                               stock = stock + :qty, 
                               cost = :new_cost 
                               WHERE id = :id";
                
                $stmt_update = $this->db->prepare($sql_update);
                $stmt_update->execute([
                    ':qty' => $qty,
                    ':new_cost' => $price,
                    ':id' => $id
                ]);
            }

            $this->db->commit(); // Hoàn tất mọi thay đổi

            // Thành công: Chuyển về trang Lịch sử hoặc Danh sách sản phẩm để xem kết quả
            header("Location: index.php?action=product_list");
            exit();

        } catch (Exception $e) {
            $this->db->rollBack(); // Nếu lỗi thì hủy hết, không để kho bị lệch
            die("Lỗi hệ thống: " . $e->getMessage());
        }
    }
}
// controllers/ImportController.php

// controllers/ImportController.php

public function add() {
    if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit(); }
    
    // KHAI BÁO BIẾN NÀY ĐỂ HẾT LỖI MAIN MENU
    $username = $_SESSION['username'] ?? 'User';
    $permissions = $_SESSION['permissions'] ?? []; 

    $productModel = new Product($this->db);
    $products = $productModel->readAll()->fetchAll(PDO::FETCH_ASSOC);

    // Lấy nhà cung cấp để đổ vào ô chọn (Đồng bộ)
    $query = "SELECT id, name FROM suppliers ORDER BY name ASC";
    $stmt = $this->db->prepare($query);
    $stmt->execute();
    $suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    require_once '../views/imports/add.php';
}
// controllers/ImportController.php

public function history() {
    // 1. Kiểm tra session
    if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit(); }
    $username = $_SESSION['username'] ?? 'User';
    $permissions = $_SESSION['permissions'] ?? []; // Fix lỗi sidebar

    // 2. Lấy dữ liệu từ bảng imports
    $query = "SELECT * FROM imports ORDER BY created_at DESC";
    $stmt = $this->db->prepare($query);
    $stmt->execute();
    $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 3. Gọi View
    require_once '../views/imports/history.php';
}
// controllers/ImportController.php

// 1. Hiển thị danh sách nhà cung cấp
public function supplierList() {
    if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit(); }
    $username = $_SESSION['username'] ?? 'User';
    $permissions = $_SESSION['permissions'] ?? [];

    $query = "SELECT * FROM suppliers ORDER BY id DESC";
    $stmt = $this->db->prepare($query);
    $stmt->execute();
    $suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    require_once '../views/imports/suppliers.php';
}

// 2. Lưu nhà cung cấp mới
public function supplierStore() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];

        $sql = "INSERT INTO suppliers (name, phone, address) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$name, $phone, $address]);

        header("Location: index.php?action=suppliers");
        exit();
    }
}


// controllers/ImportController.php

}