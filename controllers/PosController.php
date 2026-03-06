<?php
// controllers/PosController.php

class PosController {
    public function index() {
        // 1. Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }

        // 2. Lấy thông tin user
        $username = $_SESSION['username'];
        $permissions = $_SESSION['permissions'] ?? [];

        // 3. Gọi giao diện Menu con của Thu ngân
        require_once '../views/pos/dashboard.php';
    }
}
?>