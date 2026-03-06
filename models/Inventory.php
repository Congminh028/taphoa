<?php
class Inventory {
    private $conn;
    private $table = 'inventory_checks';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Tạo phiếu kiểm kho mới
    public function createCheck($code, $userId, $note, $details) {
        try {
            $this->conn->beginTransaction(); // Bắt đầu giao dịch an toàn

            // 1. Lưu thông tin phiếu chung
            $query = "INSERT INTO " . $this->table . " (code, user_id, note) VALUES (:code, :user_id, :note)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':code' => $code, ':user_id' => $userId, ':note' => $note]);
            $checkId = $this->conn->lastInsertId();

            // 2. Lưu chi tiết từng sản phẩm & Cập nhật kho
            $queryDetail = "INSERT INTO inventory_details (check_id, product_id, system_stock, actual_stock) VALUES (:check_id, :p_id, :sys, :act)";
            $queryUpdate = "UPDATE products SET stock = :new_stock WHERE id = :p_id";
            
            $stmtDetail = $this->conn->prepare($queryDetail);
            $stmtUpdate = $this->conn->prepare($queryUpdate);

            foreach ($details as $item) {
                // Chỉ lưu những món có thay đổi hoặc được kiểm tra
                $stmtDetail->execute([
                    ':check_id' => $checkId,
                    ':p_id' => $item['id'],
                    ':sys' => $item['system'],
                    ':act' => $item['actual']
                ]);

                // CẬP NHẬT KHO CHÍNH (Quan trọng nhất)
                $stmtUpdate->execute([
                    ':new_stock' => $item['actual'],
                    ':p_id' => $item['id']
                ]);
            }

            $this->conn->commit(); // Xác nhận lưu
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack(); // Nếu lỗi thì hoàn tác hết
            return false;
        }
    }

    // Lấy lịch sử kiểm kho
    // models/Inventory.php

public function getHistory() {
    // Sửa 'u.fullname' thành 'u.username' (hoặc tên cột thực tế trong bảng users của bạn)
    $query = "SELECT i.*, u.username FROM " . $this->table . " i 
              LEFT JOIN users u ON i.user_id = u.id 
              ORDER BY i.check_date DESC";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
}
}
?>