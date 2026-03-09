<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Cấu hình Chương trình Thành viên</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .settings-card { background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); max-width: 600px; margin: 0 auto; }
        .setting-group { margin-bottom: 25px; }
        .setting-label { font-weight: bold; display: block; margin-bottom: 8px; font-size: 14px; color: #333; }
        .setting-input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; }
        .setting-desc { font-size: 12px; color: #777; margin-top: 5px; }
        .suffix-input { position: relative; }
        .suffix-input input { padding-right: 40px; }
        .suffix-input .suffix { position: absolute; right: 15px; top: 12px; color: #888; font-weight: bold; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <?php include '../views/partials/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="list-header" style="max-width: 600px; margin: 0 auto 20px;">
                <div>
                    <a href="javascript:history.back()" class="back-btn" style="display:inline-block; margin-bottom:10px; color:#666; text-decoration:none; font-weight:bold;"><i class="fas fa-arrow-left"></i> Quay lại</a>
                    <h1 style="font-family: 'Oswald', sans-serif;"><i class="fas fa-cogs"></i> CẤU HÌNH HỆ THỐNG</h1>
                    <p>Chương trình Thành viên & Khuyến mãi</p>
                </div>
            </div>

            <?php if(isset($_SESSION['success'])): ?>
                <div class="alert alert-success" style="background: #2ecc71; color: white; padding: 15px; margin: 0 auto 15px; max-width: 600px; border-radius: 5px;"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>
            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger" style="background: #e74c3c; color: white; padding: 15px; margin: 0 auto 15px; max-width: 600px; border-radius: 5px;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <div class="settings-card">
                <form action="index.php?action=update_settings" method="POST">
                    
                    <h3 style="border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 20px; color: #2980b9;">
                        <i class="fas fa-tags"></i> Khuyến Mãi Toàn Hệ Thống (POS)
                    </h3>
                    <div class="setting-group">
                        <label class="setting-label">Mức giảm giá mặc định cho mọi hóa đơn</label>
                        <div class="suffix-input">
                            <input type="number" name="global_discount_percent" class="setting-input" value="<?php echo htmlspecialchars($settings['global_discount_percent'] ?? 0); ?>" min="0" max="100">
                            <span class="suffix">%</span>
                        </div>
                        <div class="setting-desc">Nhập 0 nếu không có chương trình giảm giá.</div>
                    </div>

                    <h3 style="border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 20px; color: #e67e22; margin-top: 40px;">
                        <i class="fas fa-star"></i> Cấu Hình Điểm Thưởng & Hạng
                    </h3>
                    <div class="setting-group">
                        <label class="setting-label">Tỷ lệ quy đổi điểm (Bao nhiêu tiền = 1 điểm?)</label>
                        <div class="suffix-input">
                            <input type="number" name="points_conversion_rate" class="setting-input" value="<?php echo htmlspecialchars($settings['points_conversion_rate'] ?? 100000); ?>" min="1000">
                            <span class="suffix">VNĐ</span>
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 20px;">
                        <div class="setting-group" style="flex: 1;">
                            <label class="setting-label">Điểm lên hạng BẠC</label>
                            <input type="number" name="points_silver" class="setting-input" value="<?php echo htmlspecialchars($settings['points_silver'] ?? 50); ?>" min="1">
                        </div>
                        <div class="setting-group" style="flex: 1;">
                            <label class="setting-label">Điểm lên hạng VÀNG</label>
                            <input type="number" name="points_gold" class="setting-input" value="<?php echo htmlspecialchars($settings['points_gold'] ?? 200); ?>" min="1">
                        </div>
                    </div>

                    <div style="margin-top: 30px; text-align: right;">
                        <button type="submit" class="btn-black" style="padding: 12px 30px; font-size: 16px; background: #27ae60;"><i class="fas fa-save"></i> LƯU CẤU HÌNH</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
