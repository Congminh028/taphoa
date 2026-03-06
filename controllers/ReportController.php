<?php
// controllers/ReportController.php

class ReportController {
    public function index() {
        // 1. Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }

        // 2. Lấy thông tin user
        $username = $_SESSION['username'];
        $permissions = $_SESSION['permissions'] ?? [];

        // 3. KIỂM TRA QUYỀN (Admin hoặc có quyền Báo cáo)
        if (!in_array('ADMIN', $permissions) && !in_array('UC5', $permissions)) {
            require_once '../views/403.php'; 
            exit(); 
        }

        // 4. GỌI GIAO DIỆN (Đây là dòng quan trọng nhất bạn đang thiếu)
        // Thay vì echo chữ, ta gọi file view dashboard
        require_once '../views/reports/dashboard.php';
    }
}
?>