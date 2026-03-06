<?php
class Import {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createImport($data, $items) {
        try {
            $this->conn->beginTransaction();

            // 1. Lưu phiếu nhập tổng
            $query = "INSERT INTO imports (import_code, supplier_name, total_amount, user_id) 
                      VALUES (:code, :sup, :total, :u_id)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':code' => $data['code'],
                ':sup' => $data['supplier'],
                ':total' => $data['total'],
                ':u_id' => $data['user_id']
            ]);
            $importId = $this->conn->lastInsertId();

            // 2. Lưu chi tiết và cập nhật kho
            foreach ($items as $item) {
                // Lưu chi tiết
                $qDetail = "INSERT INTO import_details (import_id, product_id, quantity, import_price) 
                            VALUES (?, ?, ?, ?)";
                $this->conn->prepare($qDetail)->execute([$importId, $item['p_id'], $item['qty'], $item['price']]);

                // CẬP NHẬT KHO: Cộng thêm số lượng mới vào số cũ
                $qUpdate = "UPDATE products SET stock = stock + ?, cost = ? WHERE id = ?";
                $this->conn->prepare($qUpdate)->execute([$item['qty'], $item['price'], $item['p_id']]);
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}