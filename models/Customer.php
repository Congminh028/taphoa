<?php
// models/Customer.php

class Customer {
    private $conn;
    private $table = 'customers';

    public function __construct($db) {
        $this->conn = $db;
    }

    // 1. Lấy danh sách khách hàng
    public function readAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // 2. Lấy thông tin 1 khách hàng theo ID
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 3. Thêm khách hàng mới
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (phone, name, address, points, tier) 
                  VALUES (:phone, :name, :address, :points, :tier)";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':phone', $data['phone']);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':address', $data['address']);
        
        $points = $data['points'] ?? 0;
        $tier = $data['tier'] ?? 'Đồng';
        $stmt->bindParam(':points', $points);
        $stmt->bindParam(':tier', $tier);

        try {
            if($stmt->execute()) {
                return true;
            }
        } catch(PDOException $e) {
            // Handle duplicate phone number
            if ($e->getCode() == 23000) {
                return "Số điện thoại đã tồn tại!";
            }
        }
        return false;
    }

    // 4. Cập nhật khách hàng
    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET name = :name, 
                      phone = :phone, 
                      address = :address
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':phone', $data['phone']);
        $stmt->bindParam(':address', $data['address']);
        
        try {
            return $stmt->execute();
        } catch(PDOException $e) {
             // Handle duplicate phone number
             if ($e->getCode() == 23000) {
                return "Số điện thoại đã tồn tại!";
            }
        }
        return false;
    }

    // 5. Tính điểm và cập nhật hạng
    public function updatePointsAndTier($id, $amountSpent) {
        // Lấy khách hàng hiện tại
        $customer = $this->getById($id);
        if (!$customer) return false;

        // Lấy cấu hình từ DB
        $stmtSettings = $this->conn->query("SELECT setting_key, setting_value FROM system_settings");
        $results = $stmtSettings->fetchAll(PDO::FETCH_ASSOC);
        
        $settings = [];
        foreach($results as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }

        $conversionRate = isset($settings['points_conversion_rate']) ? (int)$settings['points_conversion_rate'] : 100000;
        $silverPoints = isset($settings['points_silver']) ? (int)$settings['points_silver'] : 50;
        $goldPoints = isset($settings['points_gold']) ? (int)$settings['points_gold'] : 200;

        // Tính điểm
        $earnedPoints = floor($amountSpent / $conversionRate);
        $newTotalPoints = $customer['points'] + $earnedPoints;

        // Xếp hạng tự động
        $newTier = 'Đồng';
        if ($newTotalPoints >= $goldPoints) {
            $newTier = 'Vàng';
        } elseif ($newTotalPoints >= $silverPoints) {
            $newTier = 'Bạc';
        }

        $query = "UPDATE " . $this->table . " SET points = :points, tier = :tier WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':points' => $newTotalPoints,
            ':tier' => $newTier,
            ':id' => $id
        ]);
    }
}
?>
