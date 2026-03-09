<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo cáo Doanh thu - Taphoa Manager</title>
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
                    <h1>BÁO CÁO QUẢN TRỊ</h1>
                    <p>Thống kê hiệu quả kinh doanh của cửa hàng</p>
                </div>
            </header>

            <div class="card-grid">
                
                <a href="index.php?action=report_revenue" class="card" style="border-left: 5px solid #000;">
                    <div class="card-icon"><i class="fas fa-chart-line"></i></div>
                    <h3>BÁO CÁO THỐNG KÊ DOANH THU</h3>
                    <p>Theo thời gian (ngày/tháng/năm)</p>
                </a>

                <a href="index.php?action=report_profit" class="card">
                    <div class="card-icon"><i class="fas fa-hand-holding-usd"></i></div>
                    <h3>PHÂN TÍCH DANH SÁCH MẶT HÀNG BÁN CHẠY</h3>
                    <p>Thống kê số lượng bán ra</p>
                </a>

                <a href="index.php?action=report_products" class="card">
                    <div class="card-icon"><i class="fas fa-boxes"></i></div>
                    <h3>THỐNG KÊ GIÁ TRỊ HÀNG TỒN KHO HIỆN TẠI</h3>
                    <p>Số lượng và Giá trị tồn kho</p>
                </a>

            </div>
        </main>
    </div>
</body>
</html>