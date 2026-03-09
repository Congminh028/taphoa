<?php
// controllers/ChatController.php
require_once '../config/Database.php';
require_once '../models/Message.php';

class ChatController {
    private $db;
    private $messageModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->messageModel = new Message($this->db);
        // Tự động tạo bảng nếu chưa có để user không phải chạy SQL thủ công
        $this->messageModel->createTable();
    }

    public function sendMessage() {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            exit();
        }

        $messageText = $_POST['message'] ?? '';
        $channel = $_POST['channel'] ?? 'GLOBAL';

        if (empty(trim($messageText))) {
            echo json_encode(['status' => 'error', 'message' => 'Empty message']);
            exit();
        }

        $this->messageModel->sender_id = $_SESSION['user_id'];
        $this->messageModel->sender_name = $_SESSION['username'] ?? 'User';
        
        $permissions = $_SESSION['permissions'] ?? [];
        
        $roleLabel = 'Nhân viên';
        if (in_array('ADMIN', $permissions)) $roleLabel = 'Admin';
        else if (in_array('UC1', $permissions)) $roleLabel = 'Kho hàng';
        else if (in_array('UC2', $permissions)) $roleLabel = 'Nhập hàng';
        else if (in_array('UC3', $permissions)) $roleLabel = 'Thu ngân';
        else if (in_array('UC4', $permissions)) $roleLabel = 'Khách hàng';
        else if (in_array('UC5', $permissions)) $roleLabel = 'Báo cáo';

        $this->messageModel->role_label = $roleLabel;
        $this->messageModel->message = $messageText;

        if ($this->messageModel->create($channel)) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
        exit();
    }

    public function getMessages() {
        $requestedChannel = $_GET['channel'] ?? 'GLOBAL';
        $permissions = $_SESSION['permissions'] ?? [];

        // Kiểm tra quyền hạn xem channel
        $allowed = false;
        if ($requestedChannel === 'GLOBAL') {
            $allowed = true;
        } else if (in_array('ADMIN', $permissions)) {
            $allowed = true; // Admin xem được mọi kênh
        } else {
            // User thường chỉ xem được kênh của bộ phận mình
            if ($requestedChannel === 'KHO' && in_array('UC1', $permissions)) $allowed = true;
            else if ($requestedChannel === 'NHAP_HANG' && in_array('UC2', $permissions)) $allowed = true;
            else if ($requestedChannel === 'THU_NGAN' && in_array('UC3', $permissions)) $allowed = true;
            else if ($requestedChannel === 'KHACH_HANG' && in_array('UC4', $permissions)) $allowed = true;
            else if ($requestedChannel === 'BAO_CAO' && in_array('UC5', $permissions)) $allowed = true;
        }

        if (!$allowed) {
            echo json_encode([]);
            exit();
        }

        $messages = $this->messageModel->read([$requestedChannel]);
        
        header('Content-Type: application/json');
        echo json_encode($messages);
        exit();
    }
}
?>
