<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phân tích Bán chạy - Taphoa Manager</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .report-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .filter-section { background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px; display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap; }
        .filter-group { display: flex; flex-direction: column; min-width: 150px; }
        .filter-group label { font-size: 0.9rem; font-weight: 500; margin-bottom: 5px; color: #555; }
        .filter-group input, .filter-group select { padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .btn-filter { background: #3498db; color: white; border: none; padding: 9px 15px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        
        .dashboard-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 20px; margin-bottom: 20px; }
        .chart-container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); text-align: center; }
        .chart-container h3 { font-family: 'Oswald', sans-serif; margin-bottom: 15px; color: #2c3e50; }
        
        .data-table-container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); overflow-x: auto; }
        .data-table-container h3 { font-family: 'Oswald', sans-serif; margin-bottom: 15px; color: #2c3e50; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th, .data-table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; }
        .data-table th { background: #f8f9fa; font-weight: 600; color: #333; }
        .data-table tbody tr:hover { background: #fdfdfd; }
        .rank-badge { display: inline-block; width: 24px; height: 24px; line-height: 24px; text-align: center; border-radius: 50%; background: #95a5a6; color: white; font-weight: bold; font-size: 0.8rem; }
        .rank-1 { background: #f1c40f; } /* Vàng */
        .rank-2 { background: #bdc3c7; } /* Bạc */
        .rank-3 { background: #d35400; } /* Đồng */
    </style>
</head>
<body>
    <div class="dashboard-container">
        <?php include '../views/partials/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="report-header">
                <div>
                    <a href="index.php?action=reports" class="back-btn"><i class="fas fa-arrow-left"></i> Quay lại</a>
                    <h1 style="font-family: 'Oswald', sans-serif; margin-top: 10px;">TOP SẢN PHẨM BÁN CHẠY</h1>
                    <p style="color: #7f8c8d;">Phân tích tỷ trọng và hiệu quả kinh doanh từng mặt hàng</p>
                </div>
            </div>

            <!-- BỘ LỌC -->
            <form class="filter-section" method="GET" action="index.php" id="filterForm">
                <input type="hidden" name="action" value="report_profit">
                <div class="filter-group">
                    <label>Từ ngày</label>
                    <input type="date" name="start_date" id="start_date" value="<?php echo htmlspecialchars($startDate); ?>">
                </div>
                <div class="filter-group">
                    <label>Đến ngày</label>
                    <input type="date" name="end_date" id="end_date" value="<?php echo htmlspecialchars($endDate); ?>">
                </div>
                <div class="filter-group">
                    <label>Danh mục</label>
                    <select name="category" id="category">
                        <option value="">-- Tất cả biểu đồ --</option>
                        <?php foreach($categories as $c): ?>
                            <option value="<?php echo htmlspecialchars($c['name']); ?>" <?php echo $category === $c['name'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($c['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Tiêu chí xếp hạng</label>
                    <select name="sort" id="sort">
                        <option value="qty_desc" <?php echo $sort === 'qty_desc' ? 'selected' : ''; ?>>Số lượng bán ra</option>
                        <option value="rev_desc" <?php echo $sort === 'rev_desc' ? 'selected' : ''; ?>>Tổng doanh thu</option>
                        <option value="profit_desc" <?php echo $sort === 'profit_desc' ? 'selected' : ''; ?>>Lợi nhuận ròng</option>
                    </select>
                </div>
                <button type="submit" class="btn-filter"><i class="fas fa-filter"></i> LỌC DỮ LIỆU</button>
            </form>

            <div class="dashboard-grid">
                <!-- BIỂU ĐỒ PIE -->
                <div class="chart-container">
                    <h3>CƠ CẤU TOP 5 SẢN PHẨM</h3>
                    <p style="font-size: 0.8rem; color: #777; margin-bottom: 10px;">(Theo tiêu chí đang chọn)</p>
                    <div style="position: relative; height: 300px; width: 100%; display: flex; justify-content: center;">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>

                <!-- BẢNG RANKING -->
                <div class="data-table-container">
                    <h3>BẢNG XẾP HẠNG CHI TIẾT (TOP 50)</h3>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Top</th>
                                <th>Sản Phẩm</th>
                                <th style="text-align: right;">SL Bán</th>
                                <th style="text-align: right;">Doanh Thu</th>
                                <th style="text-align: right;">Lợi Nhuận</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($bestsellers)): ?>
                            <tr><td colspan="5" style="text-align: center; padding: 20px; color: #7f8c8d;">Không có dữ liệu trong khoảng thời gian này.</td></tr>
                            <?php else: ?>
                                <?php $rank = 1; foreach ($bestsellers as $item): ?>
                                <tr>
                                    <td>
                                        <span class="rank-badge <?php echo $rank <= 3 ? 'rank-'.$rank : ''; ?>">
                                            <?php echo $rank; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($item['name']); ?></strong><br>
                                        <small style="color: #7f8c8d;">Mã: <?php echo htmlspecialchars($item['code']); ?> - <?php echo htmlspecialchars($item['category']); ?></small>
                                    </td>
                                    <td style="text-align: right; font-weight: bold; color: #2980b9;">
                                        <?php echo number_format($item['total_qty']); ?>
                                    </td>
                                    <td style="text-align: right; color: #27ae60;">
                                        <?php echo number_format($item['total_revenue']); ?>đ
                                    </td>
                                    <td style="text-align: right; font-weight: bold; color: #e74c3c;">
                                        <?php echo number_format($item['total_profit']); ?>đ
                                    </td>
                                </tr>
                                <?php $rank++; endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
        </main>
    </div>

    <!-- SCRIPT BIỂU ĐỒ -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const category = document.getElementById('category').value;
            const sort = document.getElementById('sort').value;

            // Gọi AJAX lấy data Top 5 vẽ biểu đồ
            fetch(`index.php?action=report_profit&ajax=1&start_date=${startDate}&end_date=${endDate}&category=${encodeURIComponent(category)}&sort=${sort}`)
                .then(response => response.json())
                .then(result => {
                    if(result.success && result.data.length > 0) {
                        const labels = result.data.map(item => item.name);
                        
                        // Quyết định lấy dataset nào vẽ biểu đồ dựa vào sort
                        let dataValues = [];
                        let labelName = '';
                        if (sort === 'qty_desc') {
                            dataValues = result.data.map(item => item.total_qty);
                            labelName = 'Số lượng bán';
                        } else if (sort === 'rev_desc') {
                            dataValues = result.data.map(item => item.total_revenue);
                            labelName = 'Doanh thu (VNĐ)';
                        } else {
                            dataValues = result.data.map(item => item.total_profit);
                            labelName = 'Lợi nhuận (VNĐ)';
                        }

                        const ctx = document.getElementById('pieChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: labelName,
                                    data: dataValues,
                                    backgroundColor: [
                                        '#3498db', '#e74c3c', '#2ecc71', '#f1c40f', '#9b59b6'
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { position: 'bottom' }
                                }
                            }
                        });
                    } else {
                        document.getElementById('pieChart').parentNode.innerHTML = '<p style="color:#777; margin-top:50px;">Không đủ dữ liệu vẽ biểu đồ</p>';
                    }
                });
        });
    </script>
</body>
</html>
