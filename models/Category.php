<?php
class Category {
    private $conn;
    private $table = 'categories';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả danh mục
    public function readAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Thêm danh mục mới
    public function create($name, $desc) {
        $query = "INSERT INTO " . $this->table . " (name, description) VALUES (:name, :desc)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':desc', $desc);
        return $stmt->execute();
    }

    // Xóa danh mục
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>