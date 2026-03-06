<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kiểm Kho Thực Tế</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .check-input { width: 80px; text-align: center; font-weight: bold; border: 2px solid #ddd; padding: 5px; border-radius: 4px; }
        .check-input:focus { border-color: #000; outline: none; background: #fffbe6; }
        .diff-cell { font-weight: bold; }
        .diff-plus { color: #27ae60; } /* Thừa */
        .diff-minus { color: #c0392b; } /* Thiếu */
    </style>
</head>
<body>
    <div class="dashboard-container">
        <?php include '../views/partials/sidebar.php'; ?>
        
        <main class="main-content">
            <form action="index.php?action=inventory_store" method="POST">
                
                <div class="list-header">
                    <div>
                        <h1>PHIẾU KIỂM KHO</h1>
                        <p>Mã phiếu: <b style="color:#000"><?php echo $newCode; ?></b></p>
                        <input type="hidden" name="code" value="<?php echo $newCode; ?>">
                    </div>
                    <button type="submit" class="btn-black" style="width:auto; padding: 12px 30px;" onclick="return confirm('Xác nhận cập nhật kho?');">
                        <i class="fas fa-save"></i> HOÀN TẤT & CẬP NHẬT
                    </button>
                </div>
                <div style="margin-bottom: 20px; position: relative;">
    <i class="fas fa-search" style="position: absolute; left: 15px; top: 12px; color: #999;"></i>
    <input type="text" id="searchInput" onkeyup="filterProducts()" 
           placeholder="Tìm tên hoặc mã sản phẩm để kiểm kho..." 
           style="width: 100%; padding: 12px 12px 12px 40px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; outline: none;">
</div>
                <div style="background:#fff; padding:20px; border-radius:8px; margin-bottom:20px; box-shadow:0 2px 5px rgba(0,0,0,0.05);">
                    <label style="font-weight:bold; display:block; margin-bottom:5px;">GHI CHÚ KIỂM KÊ:</label>
                    <input type="text" name="note" placeholder="Ví dụ: Kiểm kho định kỳ tháng 1..." style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px;">
                </div>

                <div class="table-container">
                    <table class="nike-table">
                        <thead>
                            <tr>
                                <th>MÃ SP</th>
                                <th>TÊN SẢN PHẨM</th>
                                <th>TỒN MÁY (A)</th>
                                <th>THỰC TẾ (B)</th>
                                <th>CHÊNH LỆCH (B-A)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($products as $p): ?>
                            <tr>
                                <td style="font-weight:bold; color:#666;"><?php echo $p['code']; ?></td>
                                <td><?php echo $p['name']; ?></td>
                                
                                <td style="font-weight:bold; font-size:1.1rem;">
                                    <?php echo $p['stock']; ?>
                                    <input type="hidden" name="system_stock[<?php echo $p['id']; ?>]" value="<?php echo $p['stock']; ?>">
                                </td>

                                <td>
                                    <input type="number" 
                                           name="actual_stock[<?php echo $p['id']; ?>]" 
                                           class="check-input" 
                                           value="<?php echo $p['stock']; ?>" 
                                           min="0"
                                           oninput="calcDiff(this, <?php echo $p['stock']; ?>, 'diff-<?php echo $p['id']; ?>')">
                                </td>

                                <td id="diff-<?php echo $p['id']; ?>" class="diff-cell">0</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </form>
            <hr style="margin: 50px 0; border: 0; border-top: 2px dashed #eee;">

<div class="history-section">
    <div class="list-header">
        <div>
            <h1>LỊCH SỬ CÁC PHIẾU KIỂM</h1>
            <p>Các lần điều chỉnh kho gần đây</p>
        </div>
    </div>

    <div class="table-container">
        <table class="nike-table">
            <thead>
                <tr>
                    <th>MÃ PHIẾU</th>
                    <th>NGÀY KIỂM</th>
                    <th>NGƯỜI KIỂM</th>
                    <th>GHI CHÚ</th>
                    <th style="text-align:right">TÌNH TRẠNG</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($history) && count($history) > 0): ?>
                    <?php foreach($history as $row): ?>
                    <tr>
                        <td style="font-weight:bold; color:#000;"><?php echo $row['code']; ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($row['check_date'])); ?></td>
                        <td><i class="fas fa-user-circle"></i> <?php echo $row['fullname'] ?? 'Admin'; ?></td>
                        <td style="font-style:italic; color:#666;">
                            <?php echo !empty($row['note']) ? $row['note'] : '---'; ?>
                        </td>
                        <td style="text-align:right">
                            <span style="background:#e6fffa; color:#2c7a7b; padding:4px 10px; border-radius:4px; font-size:0.8rem; font-weight:bold;">
                                ĐÃ CẬP NHẬT KHO
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align:center; padding:30px;">Chưa có lịch sử kiểm kho.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
        </main>
    </div>

    <script>
        function calcDiff(input, systemStock, diffId) {
            var actual = parseInt(input.value) || 0;
            var diff = actual - systemStock;
            var cell = document.getElementById(diffId);
            
            if (diff > 0) {
                cell.innerHTML = "+" + diff;
                cell.className = "diff-cell diff-plus";
            } else if (diff < 0) {
                cell.innerHTML = diff;
                cell.className = "diff-cell diff-minus";
            } else {
                cell.innerHTML = "Khớp";
                cell.className = "diff-cell";
            }
        }
    </script>
    <script>
function filterProducts() {
    // Lấy giá trị từ ô nhập liệu
    var input = document.getElementById("searchInput");
    var filter = input.value.toUpperCase();
    var table = document.querySelector(".nike-table");
    var tr = table.getElementsByTagName("tr");

    // Lặp qua tất cả các hàng trong bảng (trừ hàng tiêu đề)
    for (var i = 1; i < tr.length; i++) {
        var tdCode = tr[i].getElementsByTagName("td")[0]; // Cột Mã SP
        var tdName = tr[i].getElementsByTagName("td")[1]; // Cột Tên SP
        
        if (tdCode || tdName) {
            var txtValueCode = tdCode.textContent || tdCode.innerText;
            var txtValueName = tdName.textContent || tdName.innerText;
            
            // Nếu khớp với mã hoặc tên thì hiển thị, không thì ẩn
            if (txtValueCode.toUpperCase().indexOf(filter) > -1 || 
                txtValueName.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}
</script>
</body>
</html>