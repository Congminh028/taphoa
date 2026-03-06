<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Sản Phẩm</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include '../views/partials/sidebar.php'; ?>
        
        <main class="main-content">
            <header class="module-header">
                <a href="index.php?action=product_list" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Hủy bỏ
                </a>
                <div class="header-text">
                    <h1>CHỈNH SỬA SẢN PHẨM</h1>
                    <p>Cập nhật thông tin cho mã: <b><?php echo $product['code']; ?></b></p>
                </div>
            </header>

            <div class="form-container">
                <form action="index.php?action=product_update&id=<?php echo $product['id']; ?>" method="POST" class="nike-form">
                    
                    <div class="form-row">
                        <div class="form-group" style="flex:1">
                            <label>MÃ SẢN PHẨM (KHÔNG ĐỔI)</label>
                            <input type="text" value="<?php echo $product['code']; ?>" disabled style="background: #f9f9f9; color: #999;">
                        </div>
                        <div class="form-group" style="flex:3">
                            <label>TÊN SẢN PHẨM</label>
                            <input type="text" name="name" value="<?php echo $product['name']; ?>" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>GIÁ VỐN</label>
                            <input type="number" name="cost" value="<?php echo $product['cost']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>GIÁ BÁN</label>
                            <input type="number" name="price" value="<?php echo $product['price']; ?>" required style="font-weight:bold">
                        </div>
                        <div class="form-group">
                            <select name="category">
    <?php if(isset($categories) && count($categories) > 0): ?>
        <?php foreach($categories as $cat): ?>
            <option value="<?php echo $cat['name']; ?>" 
                <?php echo ($cat['name'] == $product['category']) ? 'selected' : ''; ?>>
                <?php echo $cat['name']; ?>
            </option>
        <?php endforeach; ?>
    <?php else: ?>
        <option value="<?php echo $product['category']; ?>" selected>
            <?php echo $product['category']; ?>
        </option>
    <?php endif; ?>
</select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>TỒN KHO</label>
                            <input type="number" name="stock" value="<?php echo $product['stock']; ?>">
                        </div>
                        <div class="form-group">
                            <label>ĐƠN VỊ TÍNH</label>
                            <input type="text" name="unit" value="<?php echo $product['unit']; ?>" required>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-black btn-large">CẬP NHẬT</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>