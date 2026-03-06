<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Access Denied</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@700&display=swap" rel="stylesheet">
    <style>
        .error-container {
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: #000;
            color: #fff;
            text-align: center;
        }
        .error-code { font-size: 8rem; font-family: 'Oswald'; line-height: 1; color: #ff4d4d; }
        .error-msg { font-size: 2rem; font-family: 'Oswald'; text-transform: uppercase; margin-bottom: 20px; }
        .back-btn { padding: 15px 30px; background: #fff; color: #000; text-decoration: none; font-weight: bold; font-family: 'Oswald'; text-transform: uppercase; }
        .back-btn:hover { background: #ccc; }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">403</div>
        <div class="error-msg">BẠN KHÔNG CÓ QUYỀN TRUY CẬP KHU VỰC NÀY</div>
        <p style="color:#777; margin-bottom: 40px;">Chức năng này chỉ dành cho Quản trị viên (Admin).</p>
        <a href="index.php?action=dashboard" class="back-btn">QUAY VỀ TRANG CHỦ</a>
    </div>
</body>
</html>