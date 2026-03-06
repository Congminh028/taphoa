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
            header("Location: index.php"); 
            exit(); 
        }

        // 2. Khai báo biến cho Sidebar để tránh lỗi Warning/Lệch Menu
        $username = $_SESSION['username'] ?? 'User';
        $permissions = $_SESSION['permissions'] ?? []; 

        // 3. Lấy danh sách sản phẩm còn hàng để bán
        $query = "SELECT * FROM products WHERE stock > 0 ORDER BY name ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 4. Gọi giao diện bán hàng
        require_once '../views/cashier/pos.php';
    }
    
    
}