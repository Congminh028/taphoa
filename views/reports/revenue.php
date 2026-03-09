<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo cáo Doanh thu - Taphoa Manager</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .report-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .filter-section { background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px; display: flex; gap: 15px; align-items: flex-end; }
        .filter-group { display: flex; flex-direction: column; }
        .filter-group label { font-size: 0.9rem; font-weight: 500; margin-bottom: 5px; color: #555; }
        .filter-group input { padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .btn-filter { background: #3498db; color: white; border: none; padding: 9px 15px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        
        .kpi-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 20px; }
        .kpi-card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border-left: 4px solid #3498db; }
        .kpi-title { font-size: 0.9rem; color: #7f8c8d; font-weight: 500; text-transform: uppercase; }
        .kpi-value { font-size: 2rem; font-weight: bold; color: #2c3e50; margin: 10px 0; }
        .kpi-trend { font-size: 0.9rem; font-weight: 500; }
        .trend-up { color: #2ecc71; }
        .trend-down { color: #e74c3c; }

        .chart-container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px; position: relative; height: 400px; }
        
        .data-table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border-radius: 8px; overflow: hidden; }
        .data-table th, .data-table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; }
        .data-table th { background: #f8f9fa; font-weight: 600; color: #333; }
        .data-table tbody tr:hover { background: #fdfdfd; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <?php include '../views/partials/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="report-header">
                <div>
                    <a href="index.php?action=reports" class="back-btn"><i class="fas fa-arrow-left"></i> Quay lại</a>
                    <h1 style="font-family: 'Oswald', sans-serif; margin-top: 10px;">BÁO CÁO DOANH THU</h1>
                    <p style="color: #7f8c8d;">Biểu đồ tăng trưởng và dòng tiền chi tiết</p>
                </div>
            </div>

            <!-- BỘ LỌC -->
            <form class="filter-section" method="GET" action="index.php" id="filterForm">
                <input type="hidden" name="action" value="report_revenue">
                <div class="filter-group">
                    <label>Từ ngày</label>
                    <input type="date" name="start_date" id="start_date" value="<?php echo htmlspecialchars($startDate); ?>">
                </div>
                <div class="filter-group">
                    <label>Đến ngày</label>
                    <input type="date" name="end_date" id="end_date" value="<?php echo htmlspecialchars($endDate); ?>">
                </div>
                <button type="submit" class="btn-filter"><i class="fas fa-filter"></i> ÁP DỤNG LỌC</button>
            </form>

            <!-- KPI CARDS (Dữ liệu sẽ được điền bằng JS) -->
            <div class="kpi-cards">
                <div class="kpi-card">
                    <div class="kpi-title">TỔNG DOANH THU (KỲ CHỌN)</div>
                    <div class="kpi-value" id="kpi-total">0đ</div>
                    <div class="kpi-trend" id="kpi-growth"><i class="fas fa-minus"></i> 0% so với kỳ trước</div>
                </div>
                <div class="kpi-card" style="border-left-color: #9b59b6;">
                    <div class="kpi-title">TỔNG SỐ HÓA ĐƠN</div>
                    <div class="kpi-value"><?php echo count($orders); ?></div>
                    <div class="kpi-trend" style="color: #7f8c8d;">Giao dịch thành công</div>
                </div>
            </div>

            <!-- BIỂU ĐỒ -->
            <div class="chart-container">
                <canvas id="revenueChart"></canvas>
            </div>

            <!-- BẢNG CHI TIẾT -->
            <h3 style="margin-bottom: 15px; font-family: 'Oswald', sans-serif;">DANH SÁCH LỊCH SỬ GIAO DỊCH</h3>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Mã Hóa Đơn</th>
                        <th>Ngày Giờ</th>
                        <th>Khách Hàng</th>
                        <th>Phương Thức</th>
                        <th>Tổng Tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                    <tr><td colspan="5" style="text-align: center; color: #7f8c8d;">Không có giao dịch nào trong khoảng thời gian này.</td></tr>
                    <?php else: ?>
                        <?php foreach ($orders as $o): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($o['order_code']); ?></strong></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($o['created_at'])); ?></td>
                            <td><?php echo htmlspecialchars($o['customer_name']); ?></td>
                            <td><span style="background: #ebf5fb; color: #2980b9; padding: 3px 8px; border-radius: 4px; font-size: 0.8rem;"><?php echo htmlspecialchars($o['payment_method']); ?></span></td>
                            <td style="font-weight: bold; color: #e74c3c;"><?php echo number_format($o['total_amount']); ?>đ</td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            
        </main>
    </div>

    <!-- SCRIPT BIỂU ĐỒ -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;

            // Gọi AJAX lấy data vẽ biểu đồ
            fetch(`index.php?action=report_revenue&ajax=1&start_date=${startDate}&end_date=${endDate}`)
                .then(response => response.json())
                .then(result => {
                    if(result.success) {
                        // Cập nhật thẻ KPI
                        document.getElementById('kpi-total').innerText = result.total.toLocaleString() + 'đ';
                        const growthEl = document.getElementById('kpi-growth');
                        if (result.growth > 0) {
                            growthEl.innerHTML = `<i class="fas fa-arrow-up"></i> Tăng ${result.growth}% so với kỳ trước`;
                            growthEl.className = 'kpi-trend trend-up';
                        } else if (result.growth < 0) {
                            Math.abs(result.growth)
                            growthEl.innerHTML = `<i class="fas fa-arrow-down"></i> Giảm ${Math.abs(result.growth)}% so với kỳ trước`;
                            growthEl.className = 'kpi-trend trend-down';
                        } else {
                            growthEl.innerHTML = `<i class="fas fa-minus"></i> Không đổi (0%)`;
                        }

                        // Vẽ biểu đồ
                        const labels = result.data.map(item => item.date);
                        const revenues = result.data.map(item => item.revenue);

                        const ctx = document.getElementById('revenueChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Doanh thu (VNĐ)',
                                    data: revenues,
                                    borderColor: '#3498db',
                                    backgroundColor: 'rgba(52, 152, 219, 0.1)',
                                    borderWidth: 2,
                                    pointBackgroundColor: '#2980b9',
                                    pointRadius: 4,
                                    fill: true,
                                    tension: 0.3 // Làm cong mượt đường line
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                return context.parsed.y.toLocaleString() + ' đ';
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            callback: function(value) {
                                                if (value >= 1000000) return (value / 1000000) + 'Tr';
                                                if (value >= 1000) return (value / 1000) + 'k';
                                                return value;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                });
        });
    </script>
</body>
</html>
