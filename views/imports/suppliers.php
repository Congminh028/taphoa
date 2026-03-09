<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Nhà cung cấp</title>
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
                    <h1><i class="fas fa-handshake"></i> QUẢN LÝ ĐỐI TÁC</h1>
                    <p>Danh sách các nhà cung cấp và đại lý hàng hóa</p>
                </div>
            </div>

            <div class="modern-card" style="margin-bottom: 30px;">
                <form action="index.php?action=supplier_store" method="POST">
                    <div class="form-grid-row">
                        <div class="form-group">
                            <label>TÊN NHÀ CUNG CẤP</label>
                            <input type="text" name="name" class="nike-input" required placeholder="Ví dụ: Công ty nước giải khát Pepsi">
                        </div>
                        <div class="form-group">
                            <label>SỐ ĐIỆN THOẠI</label>
                            <input type="text" name="phone" class="nike-input" placeholder="090...">
                        </div>
                        <div class="form-group">
                            <label>ĐỊA CHỈ</label>
                            <input type="text" name="address" class="nike-input" placeholder="Địa chỉ đại lý">
                        </div>
                        <div class="form-group" style="display: flex; align-items: flex-end;">
                            <button type="submit" class="btn-black" style="width: 100%; height: 50px;">
                                <i class="fas fa-plus"></i> THÊM ĐỐI TÁC MỚI
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-container">
                <table class="nike-table">
                    <thead>
                        <tr>
                            <th>TÊN ĐỐI TÁC</th>
                            <th>LIÊN HỆ</th>
                            <th>ĐỊA CHỈ</th>
                            <th style="width: 100px;">THAO TÁC</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($suppliers)): ?>
                            <?php foreach($suppliers as $s): ?>
                            <tr>
                                <td><b style="font-size: 1.1rem;"><?php echo htmlspecialchars($s['name']); ?></b></td>
                                <td><i class="fas fa-phone-alt"></i> <?php echo $s['phone']; ?></td>
                                <td><?php echo htmlspecialchars($s['address']); ?></td>
                                <td>
                                    <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" style="text-align:center; padding: 30px;">Chưa có dữ liệu nhà cung cấp.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
    </main>
</div>