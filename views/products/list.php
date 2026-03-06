<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách Hàng hóa</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include '../views/partials/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="list-header">
                <div>
                    <h1>DANH SÁCH SẢN PHẨM</h1>
                    <p>Quản lý kho hàng hiện tại</p>
                </div>
                <button onclick="openModal()" class="btn-black" style="width: auto; padding: 10px 20px;">
                    <i class="fas fa-plus"></i> THÊM MỚI
                </button>
            </div>

            <div class="table-container">
                <table class="nike-table">
                    <thead>
                        <tr>
                            <th>MÃ SP</th>
                            <th>TÊN SẢN PHẨM</th>
                            <th>GIÁ VỐN</th>
                            <th>GIÁ BÁN</th>
                            <th>TỒN KHO</th>
                            <th>ĐƠN VỊ</th>
                            <th>THAO TÁC</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($products) && count($products) > 0): ?>
                            <?php foreach($products as $row): ?>
                            <tr>
                                <td style="font-weight:bold"><?php echo $row['code']; ?></td>
                                <td>
                                    <div class="product-name-cell">
                                        <div class="p-img"><i class="fas fa-box"></i></div>
                                        <span><?php echo $row['name']; ?></span>
                                    </div>
                                </td>
                                <td><?php echo number_format($row['cost']); ?></td>
                                <td style="font-weight:bold; color:#000"><?php echo number_format($row['price']); ?></td>
                                <?php $stockClass = ($row['stock'] < 10) ? 'text-danger' : 'text-success'; ?>
                                <td class="<?php echo $stockClass; ?>" style="font-weight:bold">
                                    <?php echo $row['stock']; ?>
                                </td>
                                <td><?php echo $row['unit']; ?></td>
                                <td>
                                    <a href="index.php?action=product_edit&id=<?php echo $row['id']; ?>" class="action-btn edit"><i class="fas fa-edit"></i></a>
                                    <a href="index.php?action=product_delete&id=<?php echo $row['id']; ?>" class="action-btn delete" onclick="return confirm('Xóa nhé?');"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7" style="text-align:center; padding:30px;">Chưa có dữ liệu. Hãy thêm mới!</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <div id="addModal" class="modal-overlay">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            
            <div class="modal-header">
                <h2>NHẬP HÀNG MỚI</h2>
                <p>Điền thông tin sản phẩm vào kho</p>
            </div>
            
            <form action="index.php?action=product_store" method="POST" class="modern-form">
                <div class="form-grid-row">
                    <div class="form-group col-1">
                        <label>MÃ SẢN PHẨM</label>
                        <input type="text" name="code" required placeholder="VD: SP001" autocomplete="off">
                    </div>
                    <div class="form-group col-2">
                        <label>TÊN SẢN PHẨM</label>
                        <input type="text" name="name" required placeholder="VD: Nước tăng lực Redbull..." autocomplete="off">
                    </div>
                </div>

                <div class="form-grid-3">
                    <div class="form-group">
                        <label>GIÁ VỐN (NHẬP)</label>
                        <input type="number" name="cost" required min="0" placeholder="0" 
                               oninput="validity.valid||(value='');">
                    </div>
                    <div class="form-group">
                        <label>GIÁ BÁN (LẺ)</label>
                        <input type="number" name="price" required min="0" placeholder="0" 
                               style="font-weight:bold; color:#000;"
                               oninput="validity.valid||(value='');">
                    </div>
                    <div class="form-group">
                        <label>ĐƠN VỊ TÍNH</label>
                        <input type="text" name="unit" required placeholder="Chai/Lốc/Cái">
                    </div>
                </div>

                <div class="form-grid-row">
                    <div class="form-group col-1">
    <label>DANH MỤC</label>
    <div class="select-wrapper">
        <select name="category">
            <?php foreach($categories as $cat): ?>
                <option value="<?php echo $cat['name']; ?>">
                    <?php echo $cat['name']; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <i class="fas fa-chevron-down"></i>
    </div>
</div>
                    <div class="form-group col-1">
                        <label>TỒN KHO BAN ĐẦU</label>
                        <input type="number" name="stock" value="0" min="0" oninput="validity.valid||(value='');">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-gray" onclick="closeModal()">HỦY BỎ</button>
                    <button type="submit" class="btn-black">LƯU SẢN PHẨM</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById("addModal").style.display = "flex";
        }
        function closeModal() {
            document.getElementById("addModal").style.display = "none";
        }
        // Ấn ra ngoài vùng trắng thì tự đóng
        window.onclick = function(event) {
            var modal = document.getElementById("addModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>