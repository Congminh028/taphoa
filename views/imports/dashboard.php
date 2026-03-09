<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Nhập hàng</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include '../views/partials/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="list-header">
                <div>
                    <a href="javascript:history.back()" class="back-btn" style="display:inline-block; margin-bottom:10px; color:#666; text-decoration:none; font-weight:bold;"><i class="fas fa-arrow-left"></i> Quay lại</a>
                    <h1>QUẢN LÝ NHẬP HÀNG (UC2)</h1>
                    <p>kim ji-won</p>
                </div>
            </div>

            <div class="card-grid">
                <a href="index.php?action=import_add" class="card">
                    <div class="card-icon"><i class="fas fa-plus-circle"></i></div>
                    <h3>TẠO PHIẾU NHẬP MỚI</h3>
                    <p>Nhập thêm hàng hóa vào kho</p>
                </a>

                <a href="index.php?action=import_history" class="card">
                    <div class="card-icon"><i class="fas fa-history"></i></div>
                    <h3>LỊCH SỬ NHẬP HÀNG</h3>
                    <p>Xem lại các hóa đơn đã nhập</p>
                </a>

                <a href="index.php?action=suppliers" class="card">
                    <div class="card-icon"><i class="fas fa-handshake"></i></div>
                    <h3>NHÀ CUNG CẤP</h3>
                    <p>Quản lý thông tin đối tác</p>
                </a>
            </div>
        </main>
    </div>
</body>
</html>