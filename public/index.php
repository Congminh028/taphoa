<?php
session_start();
// public/index.php

// 1. Khai báo các Controller bắt buộc
require_once '../config/Database.php';
require_once '../controllers/AuthController.php';
require_once '../controllers/DashboardController.php';
require_once '../controllers/ProductController.php';
require_once '../controllers/CategoryController.php'; 
require_once '../controllers/InventoryController.php'; // Thêm Controller Kiểm kho
require_once '../controllers/ImportController.php';
require_once '../controllers/PosController.php';
require_once '../controllers/CustomerController.php';
require_once '../controllers/ReportController.php';
require_once '../controllers/CashierController.php';
require_once '../controllers/ChatController.php';

// 2. Khởi tạo các đối tượng
$action = $_GET['action'] ?? 'login';
$cashierController = new CashierController();
$authController = new AuthController();
$dashboardController = new DashboardController();
$productController = new ProductController();
$catController = new CategoryController();
$invController = new InventoryController();
$importController = new ImportController();
$posController = new PosController();
$customerController = new CustomerController();
$reportController = new ReportController();
$chatController = new ChatController();


// 3. Router điều hướng
switch ($action) {
    // --- AUTH ---
    case 'login':
        $authController->login();
        break;
    case 'login_submit':
        $authController->login_submit();
        break;
    case 'register_submit':
        $authController->register_submit();
        break;
    case 'logout':
        $authController->logout();
        break;
    
    // --- DASHBOARD ---
    case 'dashboard':
        $dashboardController->index(); 
        break;

    // --- KHO HÀNG HÓA (UC1) ---
    case 'products':      // Bảng điều khiển UC1
        $productController->index();
        break;
    case 'product_list':  // Danh sách sản phẩm
        $productController->list();
        break;
    case 'product_store': // Lưu sản phẩm
        $productController->store();
        break;
    case 'product_edit':  // Sửa sản phẩm
        $productController->edit();
        break;
    case 'product_update':
        $productController->update();
        break;
    case 'product_delete':
        $productController->delete();
        break;

    // --- DANH MỤC (Chức năng con của UC1) ---
    case 'category_list':
        $catController->list();
        break;
    case 'category_store':
        $catController->store();
        break;
    case 'category_delete':
        $catController->delete();
        break;

    // --- KIỂM KHO (Chức năng con của UC1) ---
    case 'inventory_check':
        $invController->check();
        break;
    case 'inventory_store':
        $invController->store();
        break;

    // --- CÁC UC KHÁC ---
    case 'imports':
        $importController->index();
        break;
    case 'pos':
        $posController->index();
        break;
    case 'pos_history':
        $posController->history();
        break;
    case 'pos_report':
        $posController->report();
        break;
    case 'customers':
        $customerController->index();
        break;
    case 'customer_list':
        $customerController->list();
        break;
    case 'customer_store':
        $customerController->store();
        break;
    case 'customer_history':
        $customerController->history();
        break;
    case 'loyalty':
        $customerController->loyalty();
        break;
    case 'vouchers':
        $customerController->vouchers();
        break;
    case 'settings':
        $customerController->settings();
        break;
    case 'update_settings':
        $customerController->updateSettings();
        break;
    case 'reports':
        $reportController->index();
        break;
    case 'report_revenue':
        $reportController->revenue();
        break;
    case 'report_profit':
        $reportController->bestsellers(); // "Lợi nhuận" đổi thành bestsellers
        break;
    case 'report_products':
        $reportController->inventoryValue(); // "Top sản phẩm" đổi thành tồn kho
        break;
    case 'imports':
        $importController->index(); // Vào Dashboard 3 nút
        break;
    case 'import_add':
        $importController->add();   // <--- THÊM DÒNG NÀY: Dẫn đến trang có bảng nhập hàng
        break;
    case 'import_store':
        $importController->store(); // Phải có dòng này để gọi hàm xử lý lưu
        break;
    case 'import_history':
        $importController->history();
        break;
    case 'suppliers':
    $importController->supplierList();
    break;
case 'supplier_store':
    $importController->supplierStore();
    break;
    case 'cashier_dashboard': // Phải nằm TRÊN default
        $cashierController->index();
        break;

    case 'cashier_pos':      // Phải nằm TRÊN default
        $cashierController->pos();
        break;
    case 'cashier_checkout':
        $cashierController->processPayment();
        break;
    case 'order_history':
        $cashierController->order_history();
        break;
    case 'ask_ai':
        // Gọi đến một Controller mới hoặc xử lý trực tiếp ở đây
        require_once '../controllers/AiController.php';
        $aiController = new AiController();
        $aiController->chat();
        break;
    case 'send_chat_message':
        $chatController->sendMessage();
        break;
    case 'get_chat_messages':
        $chatController->getMessages();
        break;
    // --- MẶC ĐỊNH LUÔN NẰM CUỐI CÙNG ---
    default:
        $authController->login();
        break;

}