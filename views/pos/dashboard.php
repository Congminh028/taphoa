<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thu ngân & Bán hàng - Taphoa Manager</title>
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
                    <h1>THU NGÂN (UC3)</h1>
                    <p>Nghiệp vụ bán hàng & Thanh toán</p>
                </div>
            </header>

            <div class="card-grid">
                
                <a href="index.php?action=cashier_pos" class="card" style="border-left: 5px solid #00e676;">
                    <div class="card-icon" style="color: #000;"><i class="fas fa-cash-register"></i></div>
                    <h3>BÁN HÀNG</h3>
                    <p>Tạo hóa đơn, Quét mã vạch, Tính tiền</p>
                </a>

                <a href="index.php?action=pos_history" class="card">
                    <div class="card-icon"><i class="fas fa-receipt"></i></div>
                    <h3>LỊCH SỬ HÓA ĐƠN</h3>
                    <p>Xem lại hóa đơn cũ, In lại phiếu</p>
                </a>

                <a href="index.php?action=pos_report" class="card">
                    <div class="card-icon"><i class="fas fa-coins"></i></div>
                    <h3>DOANH THU CA</h3>
                    <p>Tổng kết tiền mặt cuối ngày</p>
                </a>

            </div>
        </main>
    </div>
</body>
</html>