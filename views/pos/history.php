<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lịch sử Hóa đơn</title>
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
                    <h1><i class="fas fa-file-invoice-dollar"></i> LỊCH SỬ HÓA ĐƠN</h1>
                    <p>Danh sách các hóa đơn đã bán (POS)</p>
                </div>
            </div>

            <div class="table-container">
                <table class="nike-table">
                    <thead>
                        <tr>
                            <th>NGÀY BÁN</th>
                            <th>MÃ HÓA ĐƠN</th>
                            <th>HỌ TÊN KHÁCH</th>
                            <th>TỔNG TIỀN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($orders)): ?>
                            <?php foreach($orders as $item): ?>
                            <tr>
                                <td><?php echo date('d/m/Y H:i', strtotime($item['created_at'])); ?></td>
                                <td><span class="badge-code"><?php echo $item['order_code']; ?></span></td>
                                <td><?php echo htmlspecialchars($item['customer_name']); ?></td>
                                <td style="font-weight:bold; color: #000; font-size: 1.1rem;"><?php echo number_format($item['total_amount']); ?> đ</td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" style="text-align:center;">Chưa có lịch sử bán hàng.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
