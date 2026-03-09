<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Doanh thu Ca - POS</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .report-cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px; }
        .r-card { background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); text-align: center; border: 1px solid #eee; transition: 0.3s; }
        .r-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        .r-icon { font-size: 2.5rem; color: #000; margin-bottom: 15px; }
        .r-title { font-family: 'Oswald', sans-serif; font-size: 1.2rem; color: #666; margin-bottom: 10px; }
        .r-value { font-size: 2rem; font-weight: bold; color: #000; }
        .r-count { font-size: 0.9rem; color: #888; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <?php include '../views/partials/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="list-header">
                <div>
                    <a href="javascript:history.back()" class="back-btn" style="display:inline-block; margin-bottom:10px; color:#666; text-decoration:none; font-weight:bold;"><i class="fas fa-arrow-left"></i> Quay lại</a>
                    <h1 style="font-family: 'Oswald', sans-serif;"><i class="fas fa-coins"></i> DOANH THU BÁN HÀNG</h1>
                    <p>Tổng kết tiền mặt thu vào từ POS</p>
                </div>
            </div>

            <div class="report-cards">
                <div class="r-card" style="border-bottom: 4px solid #000;">
                    <div class="r-icon"><i class="fas fa-calendar-day"></i></div>
                    <div class="r-title">HÔM NAY T T</div>
                    <div class="r-value"><?php echo number_format($reportToday['total'] ?? 0); ?> đ</div>
                    <div class="r-count"><?php echo $reportToday['ord_count'] ?? 0; ?> hóa đơn</div>
                </div>

                <div class="r-card" style="border-bottom: 4px solid #666;">
                    <div class="r-icon"><i class="fas fa-calendar-week"></i></div>
                    <div class="r-title">TUẦN NÀY</div>
                    <div class="r-value"><?php echo number_format($reportWeek['total'] ?? 0); ?> đ</div>
                    <div class="r-count"><?php echo $reportWeek['ord_count'] ?? 0; ?> hóa đơn</div>
                </div>

                <div class="r-card" style="border-bottom: 4px solid #999;">
                    <div class="r-icon"><i class="fas fa-globe"></i></div>
                    <div class="r-title">TÔNG DOANH THU</div>
                    <div class="r-value"><?php echo number_format($reportAll['total'] ?? 0); ?> đ</div>
                    <div class="r-count"><?php echo $reportAll['ord_count'] ?? 0; ?> hóa đơn</div>
                </div>
            </div>

            <div class="modern-card">
                <h3 style="margin-bottom: 15px; font-family: 'Oswald', sans-serif;">CÁC GIAO DỊCH GẦN NHẤT</h3>
                <table class="nike-table">
                    <thead>
                        <tr>
                            <th>NGÀY</th>
                            <th>MÃ HÓA ĐƠN</th>
                            <th>KHÁCH HÀNG</th>
                            <th>TIỀN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($recentOrders)): ?>
                            <?php foreach($recentOrders as $item): ?>
                            <tr>
                                <td><?php echo date('d/m/Y H:i', strtotime($item['created_at'])); ?></td>
                                <td><span class="badge-code"><?php echo $item['order_code']; ?></span></td>
                                <td><?php echo htmlspecialchars($item['customer_name']); ?></td>
                                <td style="font-weight:bold; color: #000;"><?php echo number_format($item['total_amount']); ?> đ</td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" style="text-align:center;">Chưa có dữ liệu.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </main>
    </div>
</body>
</html>
