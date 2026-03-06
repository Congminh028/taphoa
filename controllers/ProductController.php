<?php
// controllers/ProductController.php
require_once '../models/Product.php';
require_once '../models/Category.php'; // <--- 1. THÊM DÒNG NÀY

class ProductController {
    private $db;
    private $productModel;
    private $categoryModel; // <--- 2. KHAI BÁO BIẾN NÀY

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->productModel = new Product($this->db);
        $this->categoryModel = new Category($this->db); // <--- 3. KHỞI TẠO NÓ
    }

    // --- CÁC HÀM CŨ GIỮ NGUYÊN ---

    public function index() {
        if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit(); }
        $username = $_SESSION['username'];
        $permissions = $_SESSION['permissions'] ?? [];
        require_once '../views/products/dashboard.php';
    }

    // HÀM LIST (Sửa để lấy danh mục cho Modal Thêm)
    public function list() {
        if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit(); }
        $username = $_SESSION['username'];
        $permissions = $_SESSION['permissions'] ?? [];

        // Lấy sản phẩm
        $stmt = $this->productModel->readAll();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // --- MỚI: Lấy danh sách Danh mục ---
        $catStmt = $this->categoryModel->readAll();
        $categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);
        // ------------------------------------

        require_once '../views/products/list.php';
    }

    // ... (Hàm add, store giữ nguyên) ...

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // ... (Code cũ giữ nguyên)
            $data = [
                'code' => $_POST['code'],
                'name' => $_POST['name'],
                'category' => $_POST['category'], // Nó sẽ lấy từ select box mới
                'price' => $_POST['price'],
                'cost' => $_POST['cost'],
                'stock' => $_POST['stock'],
                'unit' => $_POST['unit']
            ];
            if ($this->productModel->create($data)) {
                header("Location: index.php?action=product_list");
            } else {
                echo "Lỗi thêm mới!";
            }
        }
    }

    // HÀM EDIT (Sửa để lấy danh mục cho Form Sửa)
    public function edit() {
        if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit(); }
        $username = $_SESSION['username'] ?? 'User';
        $permissions = $_SESSION['permissions'] ?? [];
        
        $id = $_GET['id'] ?? null;
        if (!$id) { header("Location: index.php?action=product_list"); exit(); }

        $product = $this->productModel->getById($id);

        // --- MỚI: Lấy danh sách Danh mục để đổ vào Select ---
        $catStmt = $this->categoryModel->readAll();
        $categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);
        // ----------------------------------------------------

        if (!$product) { echo "Sản phẩm không tồn tại!"; exit(); }

        require_once '../views/products/edit.php';
    }

    // ... (Hàm update, delete giữ nguyên) ...
    
    public function update() {
         if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_GET['id'];
            $data = [
                'name' => $_POST['name'],
                'category' => $_POST['category'],
                'price' => $_POST['price'],
                'cost' => $_POST['cost'],
                'stock' => $_POST['stock'],
                'unit' => $_POST['unit']
            ];

            if ($this->productModel->update($id, $data)) {
                header("Location: index.php?action=product_list");
            } else {
                echo "Lỗi cập nhật!";
            }
        }
    }

    public function delete() {
        if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit(); }
        $id = $_GET['id'] ?? null;
        if ($id) { $this->productModel->delete($id); }
        header("Location: index.php?action=product_list");
    }
}
?>