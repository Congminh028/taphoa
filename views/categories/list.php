<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Nhóm hàng</title>
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
                    <h1>DANH MỤC SẢN PHẨM</h1>
                    <p>Quản lý các nhóm hàng hóa</p>
                </div>
                <button onclick="openModal()" class="btn-black" style="width: auto; padding: 10px 20px;">
                    <i class="fas fa-plus"></i> THÊM NHÓM
                </button>
            </div>

            <div class="table-container">
                <table class="nike-table">
                    <thead>
                        <tr>
                            <th style="width: 10%;">ID</th>
                            <th style="width: 30%;">TÊN NHÓM</th>
                            <th>MÔ TẢ</th>
                            <th style="width: 15%;">THAO TÁC</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($categories) > 0): ?>
                            <?php foreach($categories as $row): ?>
                            <tr>
                                <td style="font-weight:bold">#<?php echo $row['id']; ?></td>
                                <td style="font-weight:bold; color:#000; font-size: 1.1rem;">
                                    <?php echo $row['name']; ?>
                                </td>
                                <td style="color:#666"><?php echo $row['description']; ?></td>
                                <td>
                                    <a href="index.php?action=category_delete&id=<?php echo $row['id']; ?>" 
                                       class="action-btn delete"
                                       onclick="return confirm('Bạn có chắc muốn xóa nhóm: <?php echo $row['name']; ?>?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" style="text-align:center; padding:20px;">Chưa có danh mục nào.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <div id="catModal" class="modal-overlay">
        <div class="modal-content" style="max-width: 500px;">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            
            <div class="modal-header">
                <h2>THÊM NHÓM MỚI</h2>
            </div>
            
            <form action="index.php?action=category_store" method="POST" class="modern-form">
                <div class="form-group">
                    <label>TÊN NHÓM HÀNG</label>
                    <input type="text" name="name" required placeholder="VD: Mỹ phẩm, Văn phòng phẩm..." autocomplete="off">
                </div>
                <div class="form-group">
                    <label>MÔ TẢ NGẮN</label>
                    <input type="text" name="description" placeholder="Mô tả chi tiết về nhóm này..." autocomplete="off">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-gray" onclick="closeModal()">HỦY BỎ</button>
                    <button type="submit" class="btn-black">LƯU LẠI</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() { document.getElementById("catModal").style.display = "flex"; }
        function closeModal() { document.getElementById("catModal").style.display = "none"; }
        window.onclick = function(e) {
            if (e.target == document.getElementById("catModal")) closeModal();
        }
    </script>
</body>
</html>