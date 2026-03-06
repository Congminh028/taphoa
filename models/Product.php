<?php
// models/Product.php

class Product {
    private $conn;
    private $table = 'products';

    public function __construct($db) {
        $this->conn = $db;
    }

    // 1. Hàm lấy tất cả sản phẩm (Cái bạn đang thiếu đây)
    public function readAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // 2. Hàm lấy thông tin 1 sản phẩm theo ID (Dùng khi ấn nút Sửa)
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 3. Hàm thêm mới sản phẩm
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (code, name, category, price, cost, stock, unit) 
                  VALUES (:code, :name, :category, :price, :cost, :stock, :unit)";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':code', $data['code']);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':category', $data['category']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':cost', $data['cost']);
        $stmt->bindParam(':stock', $data['stock']);
        $stmt->bindParam(':unit', $data['unit']);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // 4. Hàm cập nhật sản phẩm
    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET name = :name, 
                      category = :category, 
                      price = :price, 
                      cost = :cost, 
                      stock = :stock, 
                      unit = :unit 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':category', $data['category']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':cost', $data['cost']);
        $stmt->bindParam(':stock', $data['stock']);
        $stmt->bindParam(':unit', $data['unit']);
        
        return $stmt->execute();
    }

    // 5. Hàm xóa sản phẩm
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>