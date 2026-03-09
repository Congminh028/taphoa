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

    // --- 1. Báo cáo Doanh thu ---
    public function revenue() {
        if (!isset($_SESSION['user_id'])) { header("Location: index.php?action=login"); exit(); }
        $username = $_SESSION['username'];
        $permissions = $_SESSION['permissions'] ?? [];
        if (!in_array('ADMIN', $permissions) && !in_array('UC5', $permissions)) { require_once '../views/403.php'; exit(); }

        $db = (new Database())->getConnection();

        $startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $_GET['end_date'] ?? date('Y-m-d');

        // Logic trả về JSON cho biểu đồ (AJAX)
        if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
            $query = "SELECT DATE(created_at) as date, SUM(total_amount) as revenue, COUNT(id) as orders 
                      FROM orders 
                      WHERE DATE(created_at) BETWEEN :start AND :end 
                      GROUP BY DATE(created_at) 
                      ORDER BY DATE(created_at) ASC";
            $stmt = $db->prepare($query);
            $stmt->execute([':start' => $startDate, ':end' => $endDate]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Tính tăng trưởng (Kỳ này vs Kỳ trước)
            $diff = strtotime($endDate) - strtotime($startDate);
            $prevStart = date('Y-m-d', strtotime($startDate) - $diff);
            $prevEnd = date('Y-m-d', strtotime($endDate) - $diff);

            $stmtPrev = $db->prepare("SELECT SUM(total_amount) as prev_revenue FROM orders WHERE DATE(created_at) BETWEEN :start AND :end");
            $stmtPrev->execute([':start' => $prevStart, ':end' => $prevEnd]);
            $prevRevenue = $stmtPrev->fetchColumn() ?: 0;

            $currentRevenue = array_sum(array_column($data, 'revenue'));
            $growth = $prevRevenue > 0 ? (($currentRevenue - $prevRevenue) / $prevRevenue) * 100 : 100;

            echo json_encode(['success' => true, 'data' => $data, 'growth' => round($growth, 2), 'total' => $currentRevenue]);
            exit;
        }

        // Lấy danh sách hóa đơn chi tiết cho bảng drill-down
        $queryDetails = "SELECT o.*, c.name as customer_name FROM orders o 
                         LEFT JOIN customers c ON o.customer_id = c.id
                         WHERE DATE(o.created_at) BETWEEN :start AND :end 
                         ORDER BY o.created_at DESC";
        $stmtDetails = $db->prepare($queryDetails);
        $stmtDetails->execute([':start' => $startDate, ':end' => $endDate]);
        $orders = $stmtDetails->fetchAll(PDO::FETCH_ASSOC);

        require_once '../views/reports/revenue.php';
    }

    // --- 2. Danh sách bán chạy (Best Sellers) ---
    public function bestsellers() {
        if (!isset($_SESSION['user_id'])) { header("Location: index.php?action=login"); exit(); }
        $username = $_SESSION['username'];
        $permissions = $_SESSION['permissions'] ?? [];
        if (!in_array('ADMIN', $permissions) && !in_array('UC5', $permissions)) { require_once '../views/403.php'; exit(); }

        $db = (new Database())->getConnection();
        
        $startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        $category = $_GET['category'] ?? '';
        $sort = $_GET['sort'] ?? 'qty_desc';

        // Xây dựng câu query
        $whereClause = "WHERE DATE(o.created_at) BETWEEN :start AND :end";
        $params = [':start' => $startDate, ':end' => $endDate];

        if (!empty($category)) {
            $whereClause .= " AND p.category = :category";
            $params[':category'] = $category;
        }

        $orderBy = "total_qty DESC";
        if ($sort == 'rev_desc') $orderBy = "total_revenue DESC";
        // Tính tạm margin dựa trên cost và price hiện tại (Lưu ý: Thực tế nên lưu cost lúc bán vào order_details)
        if ($sort == 'profit_desc') $orderBy = "total_profit DESC";

        $query = "
            SELECT 
                p.code, p.name, p.category, 
                SUM(od.quantity) as total_qty, 
                SUM(od.quantity * od.price) as total_revenue,
                SUM(od.quantity * (od.price - p.cost)) as total_profit
            FROM order_details od
            JOIN orders o ON od.order_id = o.id
            JOIN products p ON od.product_id = p.id
            $whereClause
            GROUP BY p.id
            ORDER BY $orderBy
            LIMIT 50
        ";

        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $bestsellers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Lấy danh mục để lọc
        $catStmt = $db->query("SELECT * FROM categories ORDER BY name ASC");
        $categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

        // AJAX lấy data cho Pie Chart (Top 5 đóng góp doanh thu)
        if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
            $chartData = array_slice($bestsellers, 0, 5); // Lấy top 5
            echo json_encode(['success' => true, 'data' => $chartData]);
            exit;
        }

        require_once '../views/reports/bestsellers.php';
    }

    // --- 3. Thống kê Hàng tồn kho ---
    public function inventoryValue() {
        if (!isset($_SESSION['user_id'])) { header("Location: index.php?action=login"); exit(); }
        $username = $_SESSION['username'];
        $permissions = $_SESSION['permissions'] ?? [];
        if (!in_array('ADMIN', $permissions) && !in_array('UC5', $permissions)) { require_once '../views/403.php'; exit(); }

        $db = (new Database())->getConnection();

        // 1. Tổng giá trị tồn kho hiện tại (Stock * Cost)
        $queryVal = "SELECT SUM(stock * cost) as total_value, SUM(stock) as total_items FROM products WHERE stock > 0";
        $stmtVal = $db->query($queryVal);
        $inventoryStats = $stmtVal->fetch(PDO::FETCH_ASSOC);

        // 2. Danh sách sản phẩm cảnh báo sắp hết (stock < 10)
        $queryLow = "SELECT code, name, stock, cost FROM products WHERE stock <= 10 AND stock > 0 ORDER BY stock ASC";
        $stmtLow = $db->query($queryLow);
        $lowStockProducts = $stmtLow->fetchAll(PDO::FETCH_ASSOC);

        // 3. Hàng tồn kho chết (Aging Report giả lập: không bán được mục nào trong 3 tháng qua)
        $threeMonthsAgo = date('Y-m-d', strtotime('-3 months'));
        $queryAging = "
            SELECT p.code, p.name, p.stock, (p.stock * p.cost) as total_val 
            FROM products p
            WHERE p.stock > 0 
            AND p.id NOT IN (
                SELECT DISTINCT od.product_id 
                FROM order_details od 
                JOIN orders o ON od.order_id = o.id 
                WHERE o.created_at >= :date
            )
            ORDER BY total_val DESC
        ";
        $stmtAging = $db->prepare($queryAging);
        $stmtAging->execute([':date' => $threeMonthsAgo]);
        $agingProducts = $stmtAging->fetchAll(PDO::FETCH_ASSOC);

        // Lấy full danh sách để hiển thị bảng tổng quát
        $stmtAll = $db->query("SELECT * FROM products ORDER BY category ASC, name ASC");
        $allProducts = $stmtAll->fetchAll(PDO::FETCH_ASSOC);

        require_once '../views/reports/inventory.php';
    }
}
?>