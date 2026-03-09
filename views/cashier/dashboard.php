<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thu ngân - TapHoa Pro</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include '../views/partials/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="list-header">
                <div>
                    <a href="javascript:history.back()" class="back-btn" style="display:inline-block; margin-bottom:10px; color:#666; text-decoration:none; font-weight:bold;"><i class="fas fa-arrow-left"></i> Quay lại</a>
                    <h1 style="font-family: 'Oswald', sans-serif;">THU NGÂN (UC3)</h1>
                    <p>Nghiệp vụ bán hàng & Thanh toán</p>
                </div>
            </div>

            <div class="pos-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; margin-top: 30px;">
                
                <a href="index.php?action=cashier_pos" style="text-decoration: none; color: inherit;">
                    <div class="modern-card" style="border-left: 5px solid #000; padding: 40px; text-align: center; cursor: pointer; transition: 0.3s;">
                        <i class="fas fa-cash-register" style="font-size: 3rem; margin-bottom: 20px;"></i>
                        <h2 style="font-family: 'Oswald', sans-serif;">BÁN HÀNG</h2>
                        <p style="color: #666;">Tạo hóa đơn, Quét mã vạch, Tính tiền</p>
                    </div>
                </a>

                <a href="index.php?action=order_history" style="text-decoration: none; color: inherit;">
                    <div class="modern-card" style="border-left: 5px solid #666; padding: 40px; text-align: center; cursor: pointer;">
                        <i class="fas fa-file-invoice-dollar" style="font-size: 3rem; margin-bottom: 20px;"></i>
                        <h2 style="font-family: 'Oswald', sans-serif;">LỊCH SỬ ĐƠN</h2>
                        <p style="color: #666;">Xem lại hóa đơn cũ, In lại phiếu</p>
                    </div>
                </a>

            </div>
        </main>
    </div>
</body>
</html>