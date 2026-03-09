<?php
// models/User.php
class User {
    private $conn;
    private $table_name = "users";

    public $username;
    public $email;
    public $password;
    public $permissions; // Mảng các quyền

    public function __construct($db) {
        $this->conn = $db;
    }

    // Đăng ký tài khoản mới
    public function register() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (username, email, password, permissions) 
                  VALUES (:username, :email, :password, :permissions)";

        $stmt = $this->conn->prepare($query);

        // Làm sạch dữ liệu
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        
        // Hash mật khẩu (Bắt buộc bảo mật)
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        
        // Chuyển mảng quyền thành chuỗi JSON để lưu
        $permissions_json = json_encode($this->permissions);

        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $password_hash);
        $stmt->bindParam(":permissions", $permissions_json);

        try {
            if($stmt->execute()) {
                return true;
            }
        } catch (PDOException $e) {
            // 23000 là mã lỗi Integrity constraint violation (UNIQUE)
            if ($e->getCode() == 23000) {
                return "Tên đăng nhập hoặc Email đã tồn tại. Vui lòng chọn tài khoản khác!";
            }
            return false;
        }
        return false;
    }

    // Kiểm tra đăng nhập
    public function login() {
        $query = "SELECT id, username, password, permissions FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // Kiểm tra mật khẩu hash
            if(password_verify($this->password, $row['password'])) {
                // Trả về thông tin user nếu đúng
                return $row;
            }
        }
        return false;
    }
}
?>