<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lịch sử nhập hàng</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include '../views/partials/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="list-header">
                <h1><i class="fas fa-history"></i> LỊCH SỬ NHẬP HÀNG</h1>
                <a href="index.php?action=import_add" class="btn-black">
                    <i class="fas fa-plus"></i> NHẬP HÀNG MỚI
                </a>
            </div>

            <div class="table-container">
                <table class="nike-table">
                    <thead>
                        <tr>
                            <th>NGÀY NHẬP</th>
                            <th>MÃ PHIẾU</th>
                            <th>NHÀ CUNG CẤP</th>
                            <th>TỔNG TIỀN</th>
                            <th>THAO TÁC</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($history)): ?>
                            <?php foreach($history as $item): ?>
                            <tr>
                                <td><?php echo date('d/m/Y H:i', strtotime($item['created_at'])); ?></td>
                                <td><span class="badge-code"><?php echo $item['import_code']; ?></span></td>
                                <td><?php echo htmlspecialchars($item['supplier']); ?></td>
                                <td style="font-weight:bold;"><?php echo number_format($item['total_amount']); ?> đ</td>
                                <td>
                                    <button class="action-btn view"><i class="fas fa-eye"></i></button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" style="text-align:center;">Chưa có lịch sử nhập hàng.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>