<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chương trình Thành viên - TAPHOA MANAGER PRO</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #1a1a1a 0%, #333333 100%);
            --accent-color: #3498db;
            --silver-color: #bdc3c7;
            --gold-color: #f1c40f;
            --success-color: #2ecc71;
            --danger-color: #e74c3c;
        }

        .premium-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .page-title-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 30px;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
        }

        .page-title-section h1 {
            font-family: 'Oswald', sans-serif;
            font-size: 2.5rem;
            text-transform: uppercase;
            margin: 0;
            letter-spacing: -1px;
        }

        .settings-link {
            background: var(--primary-gradient);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .settings-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
            color: #fff;
        }

        .perma-discount-card {
            background: var(--danger-color);
            color: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 10px 20px rgba(231, 76, 60, 0.2);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }

        .perma-discount-card i {
            font-size: 3rem;
        }

        .perma-discount-info h2 {
            margin: 0;
            font-family: 'Oswald', sans-serif;
            font-size: 1.8rem;
        }

        .rules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .rule-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: 1px solid #f0f0f0;
            transition: transform 0.3s ease;
            text-align: center;
        }

        .rule-card:hover {
            transform: translateY(-10px);
        }

        .rule-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2rem;
        }

        .rule-card.points .rule-icon { background: #e8f4f8; color: var(--accent-color); }
        .rule-card.silver .rule-icon { background: #f0f3f4; color: #7f8c8d; }
        .rule-card.gold .rule-icon { background: #fef9e7; color: var(--gold-color); }

        .rule-card h3 {
            font-family: 'Oswald', sans-serif;
            font-size: 1.4rem;
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .rule-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #000;
            margin-bottom: 15px;
        }

        .rule-desc {
            color: #7f8c8d;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .info-alert {
            background: #f8f9fa;
            border-left: 5px solid #2c3e50;
            padding: 20px;
            border-radius: 8px;
            display: flex;
            align-items: flex-start;
            gap: 15px;
            color: #34495e;
        }

        .info-alert i {
            margin-top: 3px;
            font-size: 1.2rem;
            color: #2c3e50;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <?php include '../views/partials/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="premium-container">
                <header class="page-title-section">
                    <div>
                        <a href="javascript:history.back()" class="back-btn" style="margin-bottom: 10px; display: inline-block;"><i class="fas fa-arrow-left"></i> QUAY LẠI</a>
                        <h1>CHƯƠNG TRÌNH THÀNH VIÊN</h1>
                        <p style="color: #7f8c8d; font-weight: 500;">Quy tắc tích lũy & Đặc quyền hội viên</p>
                    </div>
                </header>

                <?php if($globalDiscount > 0): ?>
                    <div class="perma-discount-card">
                        <i class="fas fa-percent"></i>
                        <div class="perma-discount-info">
                            <h2>KHUYẾN MÃI ĐANG DIỄN RA: GIẢM GIÁ <?php echo $globalDiscount; ?>%</h2>
                            <p>Toàn bộ sản phẩm được tự động giảm giá khi thanh toán tại quầy POS.</p>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="rules-grid">
                    <!-- Tỷ lệ tích điểm -->
                    <div class="rule-card points">
                        <div class="rule-icon"><i class="fas fa-coins"></i></div>
                        <h3>TỶ LỆ TÍCH ĐIỂM</h3>
                        <div class="rule-value"><?php echo number_format($conversionRate); ?>đ = 1đ</div>
                        <p class="rule-desc">Mọi giao dịch thanh toán thành công sẽ được quy đổi điểm thưởng trực tiếp vào tài khoản khách hàng.</p>
                    </div>

                    <!-- Hạng Bạc -->
                    <div class="rule-card silver">
                        <div class="rule-icon"><i class="fas fa-medal"></i></div>
                        <h3>HẠNG BẠC (SILVER)</h3>
                        <div class="rule-value"><?php echo $silverPoints; ?> ĐIỂM</div>
                        <p class="rule-desc">Tương đương mức chi tiêu tích lũy tổng cộng <strong><?php echo number_format($silverPoints * $conversionRate); ?>đ</strong>.</p>
                        <div style="margin-top: 15px; font-weight: bold; color: #2980b9; background: #ebf5fb; padding: 5px 10px; border-radius: 5px; display: inline-block;">
                            <i class="fas fa-tag"></i> Thêm ưu đãi: Giảm <?php echo $discountSilver; ?>%
                        </div>
                    </div>

                    <!-- Hạng Vàng -->
                    <div class="rule-card gold">
                        <div class="rule-icon"><i class="fas fa-crown"></i></div>
                        <h3>HẠNG VÀNG (GOLD)</h3>
                        <div class="rule-value"><?php echo $goldPoints; ?> ĐIỂM</div>
                        <p class="rule-desc">Tương đương mức chi tiêu tích lũy tổng cộng <strong><?php echo number_format($goldPoints * $conversionRate); ?>đ</strong>.</p>
                        <div style="margin-top: 15px; font-weight: bold; color: #d35400; background: #fdf2e9; padding: 5px 10px; border-radius: 5px; display: inline-block;">
                            <i class="fas fa-tag"></i> Thêm ưu đãi: Giảm <?php echo $discountGold; ?>%
                        </div>
                    </div>
                </div>

                <?php if(in_array('ADMIN', $permissions) || in_array('Quản lý', $permissions) || strpos($_SESSION['username'] ?? '', 'admin') !== false): ?>
                <div id="maintenance-section" style="margin-top: 60px; padding-top: 40px; border-top: 2px dashed #eee;">
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 30px;">
                        <i class="fas fa-tools" style="font-size: 2rem; color: #2c3e50;"></i>
                        <h2 style="font-family: 'Oswald', sans-serif; margin: 0; font-size: 1.8rem;">THIẾT LẬP QUY ĐỊNH (HỆ THỐNG)</h2>
                    </div>

                    <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #f0f0f0;">
                         <form action="index.php?action=update_settings" method="POST">
                            <input type="hidden" name="redirect_to" value="vouchers">
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                                <!-- Khối 1: POS -->
                                <div style="padding-right: 30px; border-right: 1px solid #eee;">
                                    <h4 style="color: var(--danger-color); margin-bottom: 20px;"><i class="fas fa-tags"></i> KHUYẾN MÃI POS</h4>
                                    <div style="margin-bottom: 20px;">
                                        <label style="display: block; font-weight: 600; font-size: 0.9rem; margin-bottom: 8px;">Mức giảm giá mặc định (%)</label>
                                        <div style="position: relative;">
                                            <input type="number" name="global_discount_percent" value="<?php echo htmlspecialchars($globalDiscount ?? 0); ?>" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;" min="0" max="100">
                                            <span style="position: absolute; right: 15px; top: 12px; color: #999;">%</span>
                                        </div>
                                    </div>
                                    <p style="font-size: 0.8rem; color: #7f8c8d;">Lưu ý: Giảm giá này áp dụng cho <strong>tất cả</strong> hóa đơn tại máy POS ngay lập tức.</p>
                                </div>

                                <!-- Khối 2: Điểm thưởng -->
                                <div>
                                    <h4 style="color: var(--accent-color); margin-bottom: 20px;"><i class="fas fa-star"></i> QUY TẮC ĐIỂM</h4>
                                    <div style="margin-bottom: 20px;">
                                        <label style="display: block; font-weight: 600; font-size: 0.9rem; margin-bottom: 8px;">Tỷ lệ quy đổi (Bao nhiêu VNĐ = 1 điểm?)</label>
                                        <input type="number" name="points_conversion_rate" value="<?php echo htmlspecialchars($conversionRate ?? 100000); ?>" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;" min="1000">
                                    </div>
                                    
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                        <div>
                                            <label style="display: block; font-weight: 600; font-size: 0.8rem; margin-bottom: 8px;">Mốc lên hạng Bạc</label>
                                            <input type="number" name="points_silver" value="<?php echo htmlspecialchars($silverPoints ?? 50); ?>" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;">
                                        </div>
                                        <div>
                                            <label style="display: block; font-weight: 600; font-size: 0.8rem; margin-bottom: 8px;">Mốc lên hạng Vàng</label>
                                            <input type="number" name="points_gold" value="<?php echo htmlspecialchars($goldPoints ?? 200); ?>" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;">
                                        </div>
                                    </div>

                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 15px;">
                                        <div>
                                            <label style="display: block; font-weight: 600; font-size: 0.8rem; margin-bottom: 8px;">Ưu đãi hạng Bạc (%)</label>
                                            <input type="number" name="discount_silver" value="<?php echo htmlspecialchars($discountSilver ?? 0); ?>" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;" min="0" max="100">
                                        </div>
                                        <div>
                                            <label style="display: block; font-weight: 600; font-size: 0.8rem; margin-bottom: 8px;">Ưu đãi hạng Vàng (%)</label>
                                            <input type="number" name="discount_gold" value="<?php echo htmlspecialchars($discountGold ?? 0); ?>" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;" min="0" max="100">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div style="margin-top: 30px; text-align: right;">
                                <button type="submit" style="background: var(--success-color); color: white; padding: 12px 40px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; transition: 0.3s; box-shadow: 0 4px 10px rgba(46, 204, 113, 0.3);">
                                    <i class="fas fa-save"></i> CẬP NHẬT THIẾT LẬP
                                </button>
                            </div>
                         </form>
                    </div>
                </div>
                <?php endif; ?>

                <div class="info-alert" style="margin-top: 40px;">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <strong style="display: block; margin-bottom: 5px;">Hệ thống tính toán tự động</strong>
                        Điểm thưởng và thứ hạng thành viên sẽ được hệ thống TAPHOA tự động cập nhật ngay lập tức sau khi Thu ngân hoàn tất giao dịch và có chọn thông tin Khách hàng. Các ưu đãi riêng theo hạng sẽ được cập nhật trong các phiên bản tới.
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
