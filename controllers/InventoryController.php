<?php
require_once '../models/Inventory.php';
require_once '../models/Product.php';

class InventoryController {
    private $db;
    private $inventoryModel;
    private $productModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->inventoryModel = new Inventory($this->db);
        $this->productModel = new Product($this->db);
    }

    // 1. Hiển thị trang nhập kho (Form kiểm)
    // controllers/InventoryController.php

public function check() {
    if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit(); }
    $username = $_SESSION['username'];
    $permissions = $_SESSION['permissions'] ?? [];

    // 1. Lấy toàn bộ sản phẩm để lập phiếu mới
    $stmtProducts = $this->productModel->readAll();
    $products = $stmtProducts->fetchAll(PDO::FETCH_ASSOC);

    // 2. Lấy lịch sử phiếu kiểm để hiện bảng bên dưới
    $stmtHistory = $this->inventoryModel->getHistory();
    $history = $stmtHistory->fetchAll(PDO::FETCH_ASSOC);

    $newCode = "KK-" . date('ymd-His');

    require_once '../views/inventory/check.php';
}

    // 2. Xử lý lưu phiếu kiểm
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            $code = $_POST['code'];
            $note = $_POST['note'];
            
            // Xử lý mảng dữ liệu gửi lên
            $details = [];
            if (isset($_POST['actual_stock'])) {
                foreach ($_POST['actual_stock'] as $productId => $actualValue) {
                    $systemStock = $_POST['system_stock'][$productId];
                    
                    // Chỉ xử lý nếu số thực tế khác rỗng
                    if ($actualValue !== '') {
                        $details[] = [
                            'id' => $productId,
                            'system' => $systemStock,
                            'actual' => $actualValue
                        ];
                    }
                }
            }

            if ($this->inventoryModel->createCheck($code, $userId, $note, $details)) {
                // Xong thì về trang danh sách sản phẩm để xem kết quả
                echo "<script>alert('Đã cập nhật kho thành công!'); window.location.href='index.php?action=product_list';</script>";
            } else {
                echo "Lỗi khi lưu phiếu kiểm!";
            }
        }
    }
}
?>