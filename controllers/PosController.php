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
    public function history() {
        if (!isset($_SESSION['user_id'])) { 
            header("Location: index.php"); 
            exit(); 
        }

        $db = (new Database())->getConnection();
        $query = "SELECT * FROM orders ORDER BY created_at DESC";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $permissions = $_SESSION['permissions'] ?? [];

        require_once '../views/pos/history.php';
    }

    public function report() {
        if (!isset($_SESSION['user_id'])) { 
            header("Location: index.php"); 
            exit(); 
        }

        $db = (new Database())->getConnection();
        
        // Doanh thu hôm nay
        $today = date('Y-m-d');
        $queryToday = "SELECT SUM(total_amount) as total, COUNT(id) as ord_count FROM orders WHERE DATE(created_at) = :today";
        $stmtToday = $db->prepare($queryToday);
        $stmtToday->execute([':today' => $today]);
        $reportToday = $stmtToday->fetch(PDO::FETCH_ASSOC);

        // Doanh thu tuần này
        $queryWeek = "SELECT SUM(total_amount) as total, COUNT(id) as ord_count FROM orders WHERE YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)";
        $stmtWeek = $db->prepare($queryWeek);
        $stmtWeek->execute();
        $reportWeek = $stmtWeek->fetch(PDO::FETCH_ASSOC);

        // Doanh thu tất cả
        $queryAll = "SELECT SUM(total_amount) as total, COUNT(id) as ord_count FROM orders";
        $stmtAll = $db->prepare($queryAll);
        $stmtAll->execute();
        $reportAll = $stmtAll->fetch(PDO::FETCH_ASSOC);

        $permissions = $_SESSION['permissions'] ?? [];

        // Lịch sử giao dịch gần nhất
        $queryRecent = "SELECT * FROM orders ORDER BY created_at DESC LIMIT 5";
        $stmtRecent = $db->prepare($queryRecent);
        $stmtRecent->execute();
        $recentOrders = $stmtRecent->fetchAll(PDO::FETCH_ASSOC);

        require_once '../views/pos/report.php';
    }
}
?>