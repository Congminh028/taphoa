<?php
require_once 'config/Database.php';

$log = "Bắt đầu cập nhật cơ sở dữ liệu...\n";

try {
    $db = (new Database())->getConnection();
    $log .= "Kết nối database thành công.\n";
    
    // Tạo bảng customers
    $query1 = "CREATE TABLE IF NOT EXISTS taphoa_db.customers (
        id int(11) NOT NULL AUTO_INCREMENT,
        phone varchar(20) NOT NULL UNIQUE,
        name varchar(255) NOT NULL,
        address text DEFAULT NULL,
        points int(11) DEFAULT 0,
        tier varchar(50) DEFAULT 'Đồng',
        created_at timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
    
    $db->exec($query1);
    $log .= "Tạo bảng customers thành công!\n";
    
    // Thêm cột customer_id vào orders nếu chưa có
    try {
        $query2 = "ALTER TABLE taphoa_db.orders ADD COLUMN customer_id int(11) DEFAULT NULL AFTER id;";
        $db->exec($query2);
        $log .= "Thêm cột customer_id thành công!\n";
    } catch(PDOException $e) {
        $log .= "Cột customer_id có thể đã tồn tại. Lỗi: " . $e->getMessage() . "\n";
    }
    
    $log .= "Hoàn tất!";
} catch(PDOException $e) {
    $log .= "Lỗi chung: " . $e->getMessage();
}

file_put_contents('db_log.txt', $log);
echo "Done script";
?>
