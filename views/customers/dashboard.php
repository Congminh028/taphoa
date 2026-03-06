<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Khách hàng - Taphoa Manager</title>
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
                    <h1>KHÁCH HÀNG & ƯU ĐÃI (UC4)</h1>
                    <p>Chăm sóc hội viên & Marketing</p>
                </div>
            </header>

            <div class="card-grid">
                
                <a href="index.php?action=customer_list" class="card">
                    <div class="card-icon"><i class="fas fa-address-book"></i></div>
                    <h3>DANH SÁCH KHÁCH HÀNG</h3>
                    <p>Thông tin, Lịch sử mua hàng</p>
                </a>

                <a href="index.php?action=loyalty" class="card">
                    <div class="card-icon"><i class="fas fa-star"></i></div>
                    <h3>HẠNG THÀNH VIÊN</h3>
                    <p>Quản lý điểm tích lũy & Nâng hạng</p>
                </a>

                <a href="index.php?action=vouchers" class="card">
                    <div class="card-icon"><i class="fas fa-ticket-alt"></i></div>
                    <h3>KHUYẾN MÃI</h3>
                    <p>Tạo Voucher, Mã giảm giá</p>
                </a>

            </div>
        </main>
    </div>
</body>
</html>