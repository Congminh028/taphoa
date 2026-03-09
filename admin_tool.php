<?php
// admin_tool.php - File hỗ trợ đổi mật khẩu và tạo tài khoản Admin nhanh
require_once 'config/Database.php';

$database = new Database();
$db = $database->getConnection();

if (!$db) {
    die("Lỗi kết nối Database. Vui lòng kiểm tra lại cấu hình.");
}

echo "<h2>Công cụ Quản lý Tài khoản Admin</h2>";

// ==========================================
// 1. Đổi mật khẩu cho tài khoản 'minh2'
// ==========================================
$account_to_update = "minh2";
$new_password_for_minh2 = "123456"; // <-- Bạn có thể đổi mật khẩu này theo ý muốn
$hash_password = password_hash($new_password_for_minh2, PASSWORD_BCRYPT);

$query_update = "UPDATE users SET password = :password WHERE username = :username";
$stmt_update = $db->prepare($query_update);
$stmt_update->bindParam(':password', $hash_password);
$stmt_update->bindParam(':username', $account_to_update);

if ($stmt_update->execute()) {
    echo "<p>✅ Đã đổi mật khẩu cho tài khoản <b>{$account_to_update}</b> thành công! Mật khẩu mới là: <b>{$new_password_for_minh2}</b></p>";
} else {
    echo "<p>❌ Lỗi khi cập nhật mật khẩu cho {$account_to_update}.</p>";
}

echo "<hr>";

// ==========================================
// 2. Tạo một tài khoản Admin mới hoàn toàn
// ==========================================
$new_admin_username = "admin_super";
$new_admin_email = "admin_super@example.com";
$new_admin_password = "adminpassword123";
$new_admin_hash = password_hash($new_admin_password, PASSWORD_BCRYPT);

// Mảng quyền chứa mã ADMIN và toàn bộ các UC khác 
$permissions = json_encode(["UC1", "UC2", "UC3", "UC4", "UC5", "ADMIN"]);

// Kiểm tra xem tên đăng nhập / email đã tồn tại hay chưa
$query_check = "SELECT id FROM users WHERE username = :username OR email = :email LIMIT 1";
$stmt_check = $db->prepare($query_check);
$stmt_check->bindParam(':username', $new_admin_username);
$stmt_check->bindParam(':email', $new_admin_email);
$stmt_check->execute();

if ($stmt_check->rowCount() > 0) {
    echo "<p>⚠️ Tài khoản <b>{$new_admin_username}</b> hoặc email <b>{$new_admin_email}</b> đã tồn tại. Nếu bạn muốn tạo lại, hãy xoá chúng trong DB thử nhé!</p>";
} else {
    // Nếu chưa tồn tại, ta sẽ thêm mới
    $query_insert = "INSERT INTO users (username, email, password, permissions) VALUES (:username, :email, :password, :permissions)";
    $stmt_insert = $db->prepare($query_insert);
    $stmt_insert->bindParam(':username', $new_admin_username);
    $stmt_insert->bindParam(':email', $new_admin_email);
    $stmt_insert->bindParam(':password', $new_admin_hash);
    $stmt_insert->bindParam(':permissions', $permissions);
    
    if ($stmt_insert->execute()) {
        echo "<p>✅ Đã tạo tài khoản admin mới thành công!</p>";
         echo "<ul>
                 <li>Tên đăng nhập (Username): <b>{$new_admin_username}</b></li>
                 <li>Email: <b>{$new_admin_email}</b></li>
                 <li>Mật khẩu (Password): <b>{$new_admin_password}</b></li>
                 <li>Quyền (Permissions): <b>ADMIN</b> và toàn bộ thao tác hệ thống</li>
               </ul>";
    } else {
        echo "<p>❌ Lỗi khi thêm tài khoản admin mới.</p>";
    }
}

echo "<hr>";
echo "<p><a href='public/index.php'>🔙 Quay lại trang chủ / Đăng nhập</a></p>";
echo "<p style='color:red;'><b>Lưu ý cực kỳ quan trọng:</b> Vì tính bảo mật, bạn MỘT LÀ nên xóa file này hai là dời ra khỏi web sau khi chạy xong để người ngoài không tự ý đổi được mật khẩu hệ thống nhé!</p>";
?>
