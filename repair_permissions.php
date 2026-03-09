<?php
require_once 'config/Database.php';
$db = (new Database())->getConnection();

// 1. Cấp quyền cho Admin (Tất cả UC và "Quản lý")
$admin_perms = json_encode(["UC1", "UC2", "UC3", "UC4", "UC5", "Bán hàng", "Danh mục", "Kho hàng", "Quản lý", "ADMIN"]);

// Cập nhật cho user 'minh2' và 'admin_super' và bất kỳ ai có chữ admin trong tên
$db->exec("UPDATE users SET permissions = '$admin_perms' WHERE username LIKE '%admin%' OR role = 'Administrator' OR username = 'minh2'");

// 2. Tìm hoặc tạo user cho Bộ phận khách hàng
// Giả sử bộ phận khách hàng cần quyền UC4 (Khách hàng) và xem lịch sử
$cust_perms = json_encode(["UC4", "Khách hàng"]);

// Cập nhật cho user 'guest' nếu đó là người dùng hiện tại
$db->exec("UPDATE users SET permissions = '$cust_perms' WHERE username = 'Guest' AND permissions IS NULL");

echo "Permissions Updated Successfully!";
?>
