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
}
?>