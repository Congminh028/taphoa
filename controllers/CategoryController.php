<?php
require_once '../models/Category.php';

class CategoryController {
    private $db;
    private $categoryModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->categoryModel = new Category($this->db);
    }

    // 1. Hiển thị danh sách
    public function list() {
        if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit(); }
        $username = $_SESSION['username']; 
        $permissions = $_SESSION['permissions'] ?? [];

        // Lấy dữ liệu
        $stmt = $this->categoryModel->readAll();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Gọi giao diện
        require_once '../views/categories/list.php';
    }

    // 2. Xử lý thêm mới
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $desc = $_POST['description'];
            $this->categoryModel->create($name, $desc);
            header("Location: index.php?action=category_list");
        }
    }

    // 3. Xử lý xóa
    public function delete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->categoryModel->delete($id);
        }
        header("Location: index.php?action=category_list");
    }
}
?>