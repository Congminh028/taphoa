<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống kê Tồn kho - Taphoa Manager</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .report-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        
        .kpi-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .kpi-card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border-left: 4px solid #3498db; }
        .kpi-title { font-size: 0.9rem; color: #7f8c8d; font-weight: 500; text-transform: uppercase; }
        .kpi-value { font-size: 2rem; font-weight: bold; color: #2c3e50; margin: 10px 0; }
        
        .alert-card { background: #fdf2e9; border-left-color: #e67e22; }
        .alert-card .kpi-value { color: #d35400; }
        .danger-card { background: #fdedec; border-left-color: #e74c3c; }
        .danger-card .kpi-value { color: #c0392b; }

        .dashboard-grid { display: grid; grid-template-columns: 1fr; gap: 30px; margin-bottom: 20px; }
        
        .data-table-container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); overflow-x: auto; }
        .data-table-container h3 { font-family: 'Oswald', sans-serif; margin-bottom: 15px; color: #2c3e50; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th, .data-table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; }
        .data-table th { background: #f8f9fa; font-weight: 600; color: #333; }
        .data-table tbody tr:hover { background: #fcfcfc; }

        .badge { padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: bold; }
        .badge-danger { background: #e74c3c; color: white; }
        .badge-warning { background: #f1c40f; color: #333; }
        .badge-success { background: #2ecc71; color: white; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <?php include '../views/partials/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="report-header">
                <div>
                    <a href="index.php?action=reports" class="back-btn"><i class="fas fa-arrow-left"></i> Quay lại</a>
                    <h1 style="font-family: 'Oswald', sans-serif; margin-top: 10px;">THỐNG KÊ GIÁ TRỊ HÀNG TỒN KHO</h1>
                    <p style="color: #7f8c8d;">Đo lường vốn lưu động đang nằm trong kho và kiểm soát date</p>
                </div>
                <button class="btn-filter" onclick="window.print()" style="background: #2c3e50; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold;"><i class="fas fa-print"></i> IN BÁO CÁO</button>
            </div>

            <!-- KPI CARDS -->
            <div class="kpi-cards">
                <div class="kpi-card">
                    <div class="kpi-title">TỔNG VỐN TỒN KHO</div>
                    <div class="kpi-value"><?php echo number_format($inventoryStats['total_value'] ?? 0); ?>đ</div>
                    <div class="kpi-trend" style="color: #7f8c8d;"><i class="fas fa-box-open"></i> <?php echo number_format($inventoryStats['total_items'] ?? 0); ?> sản phẩm đang tồn</div>
                </div>
                <div class="kpi-card alert-card">
                    <div class="kpi-title"><i class="fas fa-exclamation-triangle"></i> SẢN PHẨM SẮP HẾT (< 10)</div>
                    <div class="kpi-value"><?php echo count($lowStockProducts); ?></div>
                    <div class="kpi-trend">mã hàng hóa cần nhập thêm</div>
                </div>
                <div class="kpi-card danger-card">
                    <div class="kpi-title"><i class="fas fa-skull-crossbones"></i> HÀNG TỒN NHIỀU (> 3 THÁNG)</div>
                    <div class="kpi-value"><?php echo count($agingProducts); ?></div>
                    <div class="kpi-trend">mã không có giao dịch bán ra</div>
                </div>
            </div>

            <div class="dashboard-grid">
                
                <!-- BẢNG CẢNH BÁO -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <!-- SẮP HẾT -->
                    <div class="data-table-container" style="border-top: 4px solid #e67e22;">
                        <h3>SẢN PHẨM SẮP HẾT / ĐÃ HẾT</h3>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Mã SP</th>
                                    <th>Tên SP</th>
                                    <th style="text-align: right;">Kho</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($lowStockProducts)): ?>
                                    <tr><td colspan="3" style="text-align: center;">Kho hàng rất khỏe (Mọi SP đều > 10)</td></tr>
                                <?php else: ?>
                                    <?php foreach ($lowStockProducts as $low): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($low['code']); ?></td>
                                        <td><?php echo htmlspecialchars($low['name']); ?></td>
                                        <td style="text-align: right; font-weight: bold;">
                                            <span class="badge <?php echo $low['stock'] <= 0 ? 'badge-danger' : 'badge-warning'; ?>"><?php echo $low['stock']; ?></span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- LÂU NGÀY -->
                    <div class="data-table-container" style="border-top: 4px solid #e74c3c;">
                        <h3>HÀNG TỒN LÂU (> 3 THÁNG)</h3>
                        <p style="font-size: 0.85rem; color: #7f8c8d; margin-bottom: 10px;">Nguy cơ giam vốn, cần xả hàng (<?php echo count($agingProducts); ?> mã)</p>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Tên SP</th>
                                    <th style="text-align: right;">Kho</th>
                                    <th style="text-align: right;">Giam vốn</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($agingProducts)): ?>
                                    <tr><td colspan="3" style="text-align: center;">Tất cả sản phẩm đều bán chạy</td></tr>
                                <?php else: ?>
                                    <?php foreach (array_slice($agingProducts, 0, 10) as $aging): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($aging['name']); ?></strong><br>
                                            <small><?php echo htmlspecialchars($aging['code']); ?></small>
                                        </td>
                                        <td style="text-align: right;"><?php echo $aging['stock']; ?></td>
                                        <td style="text-align: right; color: #e74c3c; font-weight: bold;"><?php echo number_format($aging['total_val']); ?>đ</td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- BẢNG TẤT CẢ TỒN KHO -->
                <div class="data-table-container">
                    <h3>DANH SÁCH TOÀN BỘ TỒN KHO ĐỂ KIỂM KÊ</h3>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Danh mục</th>
                                <th>Mã SP</th>
                                <th>Tên Sản Phẩm</th>
                                <th style="text-align: right;">Giá Vốn</th>
                                <th style="text-align: right;">Tồn Kho H.Tống</th>
                                <th style="text-align: right;">Tổng Vốn</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($allProducts as $p): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($p['category']); ?></td>
                                <td><?php echo htmlspecialchars($p['code']); ?></td>
                                <td><strong><?php echo htmlspecialchars($p['name']); ?></strong></td>
                                <td style="text-align: right; color: #7f8c8d;"><?php echo number_format($p['cost']); ?>đ</td>
                                <td style="text-align: right; font-weight: bold;">
                                    <span class="<?php echo $p['stock'] <= 0 ? 'badge badge-danger' : ($p['stock'] <= 10 ? 'badge badge-warning' : ''); ?>">
                                        <?php echo number_format($p['stock']); ?>
                                    </span>
                                </td>
                                <td style="text-align: right; font-weight: bold; color: #2980b9;"><?php echo number_format($p['cost'] * $p['stock']); ?>đ</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
            
        </main>
    </div>
</body>
</html>
