<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Nhập hàng hóa</title>
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
                    <a href="javascript:history.back()" class="back-btn" style="display:inline-block; margin-bottom:10px; color:#666; text-decoration:none; font-weight:bold;"><i class="fas fa-arrow-left"></i> Quay lại</a>
                    <h1>NHẬP HÀNG VÀO KHO</h1>
                    <p>Tạo phiếu nhập hàng từ nhà cung cấp</p>
                </div>
                <div class="header-actions">
    <?php $ma_phieu = "PN-" . date('YmdHis'); ?> <span class="badge-code">Mã phiếu: <b><?php echo $ma_phieu; ?></b></span>
    <input type="hidden" name="import_code" value="<?php echo $ma_phieu; ?>">
</div>
            </div>

            <form action="index.php?action=import_store" method="POST" class="modern-form">
    <input type="hidden" name="import_code" value="<?php echo $ma_phieu; ?>">

    <div class="form-grid-row" style="background: #fff; padding: 20px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
        
        <div class="form-group col-1">
            <label><i class="fas fa-truck"></i> CHỌN NHÀ CUNG CẤP</label>
            <div class="select-wrapper">
                <select name="supplier" required class="nike-input">
                    <option value="">-- Chọn đối tác đã lưu --</option>
                    <?php if (!empty($suppliers)): ?>
                        <?php foreach($suppliers as $s): ?>
                            <option value="<?php echo htmlspecialchars($s['name']); ?>">
                                <?php echo htmlspecialchars($s['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="supplier-action" style="margin-top: 10px; display: flex; align-items: center;">
    <a href="index.php?action=suppliers" class="btn-outline-small">
        <i class="fas fa-plus-circle"></i> THÊM NHÀ CUNG CẤP MỚI
    </a>
</div>

<style>
/* Thêm đoạn style này vào cuối file hoặc file CSS của bạn */
.btn-outline-small {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 15px;
    border: 1.5px solid #000;
    border-radius: 20px;
    color: #000;
    text-decoration: none;
    font-size: 0.8rem;
    font-weight: 700;
    transition: all 0.3s ease;
    background: transparent;
}

.btn-outline-small:hover {
    background: #000;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.btn-outline-small i {
    font-size: 0.9rem;
}
</style>
        </div>

        <div class="form-group col-1">
            <label><i class="fas fa-search"></i> CHỌN SẢN PHẨM NHẬP KHO</label>
            <div class="select-wrapper">
                <select id="productSelect" onchange="addProductRow()">
                    <option value="">-- Chọn để thêm vào danh sách --</option>
                    <?php foreach($products as $p): ?>
                        <option value="<?php echo $p['id']; ?>" 
                                data-name="<?php echo htmlspecialchars($p['name']); ?>" 
                                data-cost="<?php echo $p['cost']; ?>">
                            <?php echo $p['code']; ?> - <?php echo htmlspecialchars($p['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </div>

                <div class="table-container">
                    <table class="nike-table" id="importTable">
    <thead>
        <tr>
            <th>SẢN PHẨM</th>
            <th style="width: 150px;">SỐ LƯỢNG</th>
            <th style="width: 200px;">GIÁ NHẬP (VNĐ)</th>
            <th>THÀNH TIỀN</th>
            <th style="width: 80px;">XÓA</th>
        </tr>
    </thead>
    <tbody id="importBody">
    </tbody>
    <tfoot>
        <tr style="background: #f8f9fa; font-weight: bold;">
            <td colspan="3" style="text-align: right; font-size: 1.1rem;">TỔNG CỘNG HÓA ĐƠN:</td>
            <td colspan="2" id="grandTotal" style="font-size: 1.2rem; color: #000;">0 đ</td>
        </tr>
    </tfoot>
</table>
                </div>

                <div class="form-actions" style="margin-top: 30px; text-align: right;">
                    <button type="submit" class="btn-black" style="width: 250px; padding: 15px;">
                        <i class="fas fa-check-circle"></i> XÁC NHẬN NHẬP KHO
                    </button>
                </div>
            </form>
        </main>
    </div>

 <script>
function addProductRow() {
    
    const select = document.getElementById("productSelect");
    const id = select.value;
    if (!id) return;

    const option = select.options[select.selectedIndex];
    const name = option.getAttribute("data-name");
    const cost = option.getAttribute("data-cost");

    // Lấy đúng thẻ tbody qua ID đã sửa ở trên
    const tableBody = document.getElementById("importBody");
    if (!tableBody) {
        console.error("Không tìm thấy thẻ tbody với ID 'importBody'");
        return;
    }

    // Kiểm tra trùng sản phẩm
    const rows = tableBody.getElementsByTagName('tr');
    for (let i = 0; i < rows.length; i++) {
        if (rows[i].getAttribute('data-id') == id) {
            alert('Sản phẩm đã có trong danh sách!');
            select.value = ""; return;
        }
    }

    const row = tableBody.insertRow();
    row.setAttribute('data-id', id);

    row.innerHTML = `
        <td>
            <div style="font-weight:bold; color:#000;">${name}</div>
            <input type="hidden" name="p_id[]" value="${id}">
        </td>
        <td>
            <input type="number" name="qty[]" value="1" min="1" 
                   style="width:80px; text-align:center; border:1px solid #ccc; padding:5px;" 
                   oninput="updateRowTotal(this)">
        </td>
        <td>
            <input type="number" name="price[]" value="${cost}" min="0" 
                   style="width:120px; text-align:right; border:1px solid #ccc; padding:5px;" 
                   oninput="updateRowTotal(this)">
        </td>
        <td class="row-total" style="font-weight:bold; text-align:right;">${parseInt(cost).toLocaleString()} đ</td>
        <td style="text-align:center;">
            <button type="button" onclick="this.parentElement.parentElement.remove(); calculateGrandTotal();" class="action-btn delete">
                <i class="fas fa-trash" style="color:red;"></i>
            </button>
        </td>
    `;
    select.value = ""; 
    calculateGrandTotal();
}

function updateRowTotal(input) {
    const row = input.parentElement.parentElement;
    const qty = parseFloat(row.querySelector('input[name="qty[]"]').value) || 0;
    const price = parseFloat(row.querySelector('input[name="price[]"]').value) || 0;
    const rowTotal = row.querySelector('.row-total');
    if (rowTotal) {
        rowTotal.innerText = (qty * price).toLocaleString() + " đ";
    }
    calculateGrandTotal();
}

function calculateGrandTotal() {
    const totals = document.getElementsByClassName('row-total');
    let grandTotal = 0;
    for (let i = 0; i < totals.length; i++) {
        let val = totals[i].innerText.replace(/[^0-9]/g, "");
        grandTotal += parseInt(val) || 0;
    }
    const totalDisplay = document.getElementById('grandTotal');
    if (totalDisplay) {
        totalDisplay.innerText = grandTotal.toLocaleString() + " đ";
    }
}
</script>