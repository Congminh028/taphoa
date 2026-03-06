<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hệ thống POS - Bán hàng</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        /* Style cũ của bạn */
        .pos-container { display: flex; gap: 20px; padding: 20px; height: 80vh; }
        .product-list { flex: 1; background: #fff; padding: 15px; border-radius: 8px; overflow-y: auto; }
        .cart-section { flex: 1; background: #f8f9fa; padding: 15px; border-radius: 8px; display: flex; flex-direction: column; }
        .scan-box { margin-bottom: 20px; }
        .scan-box input { width: 100%; padding: 12px; font-size: 1.2rem; border: 2px solid #000; }
        .cart-table { width: 100%; border-collapse: collapse; }
        .cart-table th { text-align: left; border-bottom: 2px solid #ddd; padding: 10px; }
        .total-section { margin-top: auto; padding-top: 20px; border-top: 2px dashed #ccc; }
        .btn-checkout { background: #2ecc71; color: white; width: 100%; padding: 15px; border: none; font-size: 1.5rem; cursor: pointer; font-weight: bold; }
        
        /* CSS CHO MODAL XEM TRƯỚC */
        .modal { display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.6); }
        .modal-content { background:#fff; margin:2% auto; padding:25px; width:450px; border-radius:10px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); }
        .btn-confirm { background:#2ecc71; color:#fff; flex:1; padding:12px; border:none; cursor:pointer; font-weight:bold; }
        .btn-cancel { background:#e74c3c; color:#fff; flex:1; padding:12px; border:none; cursor:pointer; }
    </style>
</head>
<body>
    <?php include '../views/partials/sidebar.php'; ?>

    <main class="main-content">
        <h1 style="font-family: 'Oswald', sans-serif;">MÁY TÍNH TIỀN (POS)</h1>

        <div class="pos-container">
            <div class="product-list">
                <div class="scan-box">
                    <label><i class="fas fa-barcode"></i> Quét mã vạch hoặc nhập tên:</label>
                    <input type="text" id="barcode-input" placeholder="Đưa máy quét vào đây..." autofocus autocomplete="off">
                </div>
                
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Giá</th>
                            <th>Kho</th>
                            <th>Thêm</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $p): ?>
                        <tr>
                            <td><strong><?php echo $p['name']; ?></strong><br><small><?php echo $p['barcode'] ?? 'N/A'; ?></small></td>
                            <td><?php echo number_format($p['price']); ?>đ</td>
                            <td><?php echo $p['stock']; ?></td>
                            <td><button onclick="addToCart(<?php echo htmlspecialchars(json_encode($p)); ?>)" class="btn-add">+</button></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="cart-section">
                <h3>GIỎ HÀNG</h3>
                <table class="cart-table" id="cart-table">
                    <thead>
                        <tr>
                            <th>Tên</th>
                            <th>SL</th>
                            <th>Thành tiền</th>
                            <th>Xóa</th>
                        </tr>
                    </thead>
                    <tbody id="cart-body"></tbody>
                </table>

                <div class="total-section">
                    <div style="display: flex; justify-content: space-between; font-size: 1.5rem; font-weight: bold; margin-bottom: 10px;">
                        <span>TỔNG CỘNG:</span>
                        <span id="grand-total">0đ</span>
                    </div>
                    <button class="btn-checkout" onclick="showPreview()">XUẤT HÓA ĐƠN</button>
                </div>
            </div>
        </div>
    </main>

    <div id="invoiceModal" class="modal">
        <div class="modal-content">
            <div id="printArea" style="padding:10px; font-family: 'Courier New', monospace; color: #000;">
                <div style="text-align:center; border-bottom: 1px dashed #000; padding-bottom: 10px;">
                    <h2 style="margin:0;">TAPHOA TẠP NHAM</h2>
                    <p style="margin:5px 0; font-size:13px;">Địa chỉ: 123 Đường Tài Chính, Hà Nội</p>
                    <p style="margin:0; font-size:13px;">Ngày: <span id="inv-date"></span></p>
                </div>
                <table style="width:100%; margin-top:15px; border-collapse: collapse; font-size: 14px;">
                    <thead>
                        <tr style="border-bottom: 1px solid #000;">
                            <th style="text-align:left;">SP</th>
                            <th style="text-align:center;">SL</th>
                            <th style="text-align:right;">T.Tiền</th>
                        </tr>
                    </thead>
                    <tbody id="inv-details"></tbody>
                </table>
                <div style="text-align:right; margin-top:15px; font-weight:bold; font-size:18px; border-top: 1px dashed #000; padding-top:10px;">
                    TỔNG: <span id="inv-total"></span>
                </div>
                <p style="text-align:center; margin-top:20px; font-size:12px;">Cảm ơn quý khách!</p>
            </div>
            <div style="display:flex; gap:10px; margin-top:20px;">
                <button class="btn-confirm" onclick="executePrint()"><i class="fas fa-print"></i> XÁC NHẬN IN</button>
                <button class="btn-cancel" onclick="closeModal()">ĐÓNG</button>
            </div>
        </div>
    </div>

    <script>
        let cart = [];

        function updateCartUI() {
            const body = document.getElementById('cart-body');
            body.innerHTML = '';
            let total = 0;
            cart.forEach((item, index) => {
                const subtotal = item.price * item.quantity;
                total += subtotal;
                body.innerHTML += `
                    <tr>
                        <td>${item.name}</td>
                        <td><input type="number" value="${item.quantity}" min="1" onchange="changeQty(${index}, this.value)" style="width: 50px"></td>
                        <td>${subtotal.toLocaleString()}đ</td>
                        <td><button onclick="removeItem(${index})" style="color:red; border:none; background:none; cursor:pointer;">&times;</button></td>
                    </tr>`;
            });
            document.getElementById('grand-total').innerText = total.toLocaleString() + 'đ';
        }

        // QUÉT MÃ VẠCH
        document.getElementById('barcode-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const barcode = this.value.trim();
                const products = <?php echo json_encode($products); ?>;
                const found = products.find(p => p.barcode === barcode);
                if (found) {
                    addToCart(found);
                    this.value = '';
                } else {
                    alert('Không tìm thấy sản phẩm!');
                    this.value = '';
                }
            }
        });

        function addToCart(product) {
            const index = cart.findIndex(item => item.id === product.id);
            if (index > -1) { cart[index].quantity++; } 
            else { cart.push({...product, quantity: 1}); }
            updateCartUI();
        }

        function changeQty(index, val) {
            cart[index].quantity = parseInt(val) || 1;
            updateCartUI();
        }

        function removeItem(index) {
            cart.splice(index, 1);
            updateCartUI();
        }

        // HÀM XEM TRƯỚC HÓA ĐƠN
        function showPreview() {
            if (cart.length === 0) return alert("Giỏ hàng đang trống!");

            document.getElementById('inv-date').innerText = new Date().toLocaleString();
            const details = document.getElementById('inv-details');
            details.innerHTML = '';
            let total = 0;

            cart.forEach(item => {
                const subtotal = item.price * item.quantity;
                total += subtotal;
                details.innerHTML += `
                    <tr>
                        <td>${item.name}</td>
                        <td style="text-align:center;">${item.quantity}</td>
                        <td style="text-align:right;">${subtotal.toLocaleString()}đ</td>
                    </tr>`;
            });

            document.getElementById('inv-total').innerText = total.toLocaleString() + 'đ';
            document.getElementById('invoiceModal').style.display = 'block';
        }

        function closeModal() { document.getElementById('invoiceModal').style.display = 'none'; }

        // HÀM IN PDF CHÍNH THỨC
        function executePrint() {
            const element = document.getElementById('printArea');
            const opt = {
                margin: 10,
                filename: 'HoaDon_' + Date.now() + '.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };

            html2pdf().set(opt).from(element).save().then(() => {
                closeModal();
                alert("Đã xuất hóa đơn thành công!");
                // Bạn có thể thêm lệnh cart = []; updateCartUI(); ở đây để làm mới giỏ hàng sau khi bán
            });
        }
    </script>
</body>
</html>