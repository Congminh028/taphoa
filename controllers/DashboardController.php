<?php
// controllers/DashboardController.php

class DashboardController {
    public function index() {
        // Kiểm tra session (đã start ở index.php rồi)
        if (!isset($_SESSION['user_id'])) {
            // Chưa đăng nhập thì đuổi về trang login
            header("Location: index.php?action=login");
            exit();
        }

        // Lấy thông tin user từ session để hiển thị
        $username = $_SESSION['username'];
        $permissions = $_SESSION['permissions'] ?? [];

        // Gọi view dashboard
        require_once '../views/dashboard/index.php';
    }
}
?>