<?php
// controllers/AuthController.php
require_once '../models/User.php';

class AuthController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
        
    }

    public function login() {
        require_once '../views/auth/login.php';
    }

    // Xử lý submit đăng nhập
    public function login_submit() {
    // Chỉ xử lý khi là phương thức POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Dùng dấu ?? '' để nếu không tìm thấy email thì gán bằng rỗng, tránh lỗi Warning
        $this->user->email = $_POST['email'] ?? '';
        $this->user->password = $_POST['password'] ?? '';

        $logged_in_user = $this->user->login();

        if ($logged_in_user) {
            $_SESSION['user_id'] = $logged_in_user['id'];
            $_SESSION['username'] = $logged_in_user['username'];
            // Giải mã JSON quyền hạn
            $_SESSION['permissions'] = json_decode($logged_in_user['permissions'], true);
            
            header("Location: index.php?action=dashboard");
            exit(); // Luôn có exit sau header location
        } else {
            $error = "Email hoặc mật khẩu không đúng!";
            require_once '../views/auth/login.php';
        }
    } else {
        // Nếu ai đó cố truy cập link này mà không post form, đẩy về trang login
        $this->login();
    }
}

    // Xử lý submit đăng ký
    public function register_submit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->user->username = $_POST['username'];
            $this->user->email = $_POST['email'];
            $this->user->password = $_POST['password'];
            
            // Lấy danh sách các quyền được chọn (UC1 -> UC5)
            $this->user->permissions = isset($_POST['permissions']) ? $_POST['permissions'] : [];

            $result = $this->user->register();
            if ($result === true) {
                $success = "Đăng ký thành công! Vui lòng đăng nhập.";
                require_once '../views/auth/login.php'; // Quay lại trang login
                exit();
            } else {
                // Nếu lỗi do trùng email/username, $result sẽ là câu thông báo lỗi
                $error = is_string($result) ? $result : "Đăng ký thất bại.";
                require_once '../views/auth/login.php';
                exit();
            }
        }
    }
    
    public function logout() {
        session_destroy();
        header("Location: index.php");
    }
}
?>