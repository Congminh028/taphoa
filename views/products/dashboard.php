<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Kho Hàng - Taphoa Manager</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include '../views/partials/sidebar.php'; ?>
        
        <main class="main-content">
            <header class="module-header">
                <a href="index.php?action=dashboard" class="back-btn"><i class="fas fa-arrow-left"></i> Quay lại</a>
                <div class="header-text">
                    <h1>KHO HÀNG HÓA (UC1)</h1>
                    <p>Quản lý sản phẩm & Tồn kho</p>
                </div>
            </header>

            <div class="card-grid">
                
                <a href="index.php?action=product_list" class="card" style="border-left: 5px solid #000;">
                    <div class="card-icon" style="color: #000;"><i class="fas fa-boxes"></i></div>
                    <h3>DANH SÁCH SẢN PHẨM</h3>
                    <p>Xem tất cả, Thêm mới, Sửa giá</p>
                </a>

                <a href="index.php?action=category_list" class="card"> <div class="card-icon"><i class="fas fa-tags"></i></div>
    <h3>DANH MỤC</h3>
    <p>Quản lý nhóm hàng, Đơn vị tính</p>
</a>

                <a href="index.php?action=inventory_check" class="card">
    <div class="card-icon"><i class="fas fa-clipboard-check"></i></div>
    <h3>KIỂM KHO</h3>
    <p>Đối soát tồn kho thực tế</p>
</a>

            </div>
        </main>
    </div>
</body>
</html>