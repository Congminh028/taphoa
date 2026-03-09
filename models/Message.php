<?php
// models/Message.php
class Message {
    private $conn;
    private $table_name = "messages";

    public $id;
    public $sender_id;
    public $sender_name;
    public $role_label;
    public $message;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Tạo bảng nếu chưa có (Helper cho local)
    public function createTable() {
        $query = "CREATE TABLE IF NOT EXISTS " . $this->table_name . " (
            id INT AUTO_INCREMENT PRIMARY KEY,
            sender_id INT NOT NULL,
            sender_name VARCHAR(100),
            role_label VARCHAR(50),
            channel VARCHAR(50) DEFAULT 'GLOBAL',
            message TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        
        try {
            $this->conn->exec($query);
            // Thêm cột channel nếu bảng đã tồn tại nhưng chưa có cột này
            $this->conn->exec("ALTER TABLE " . $this->table_name . " ADD COLUMN IF NOT EXISTS channel VARCHAR(50) DEFAULT 'GLOBAL' AFTER role_label");
        } catch (Exception $e) {
            // Có thể bảng đã có cột hoặc lỗi khác, bỏ qua ở môi trường dev
        }
    }

    public function create($channel = 'GLOBAL') {
        $query = "INSERT INTO " . $this->table_name . " 
                (sender_id, sender_name, role_label, channel, message) 
                VALUES (:sender_id, :sender_name, :role_label, :channel, :message)";
        
        $stmt = $this->conn->prepare($query);

        $this->message = htmlspecialchars(strip_tags($this->message));
        $this->sender_name = htmlspecialchars(strip_tags($this->sender_name));
        $this->role_label = htmlspecialchars(strip_tags($this->role_label));

        $stmt->bindParam(":sender_id", $this->sender_id);
        $stmt->bindParam(":sender_name", $this->sender_name);
        $stmt->bindParam(":role_label", $this->role_label);
        $stmt->bindParam(":channel", $channel);
        $stmt->bindParam(":message", $this->message);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read($channels = ['GLOBAL'], $limit = 50) {
        $placeholders = implode(',', array_fill(0, count($channels), '?'));
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE channel IN ($placeholders) 
                  ORDER BY created_at DESC LIMIT ?";
        
        $stmt = $this->conn->prepare($query);
        
        foreach ($channels as $k => $v) {
            $stmt->bindValue($k + 1, $v);
        }
        $stmt->bindValue(count($channels) + 1, $limit, PDO::PARAM_INT);
        
        $stmt->execute();
        
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_reverse($messages); // Trả về theo thứ tự thời gian tăng dần
    }
}
?>
