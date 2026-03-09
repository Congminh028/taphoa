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
        <a href="javascript:history.back()" class="back-btn" style="display:inline-block; margin-bottom:10px; color:#666; text-decoration:none; font-weight:bold;"><i class="fas fa-arrow-left"></i> Quay lại</a>
        <h1 style="font-family: 'Oswald', sans-serif;">MÁY TÍNH TIỀN (POS)</h1>

        <div class="pos-container">
            <div class="product-list">
                <div class="scan-box">
                    <label><i class="fas fa-search"></i> Tìm kiếm sản phẩm:</label>
                    <input type="text" id="search-input" placeholder="Nhập tên sản phẩm..." autocomplete="off" oninput="filterProducts()">
                </div>
                
                <table class="cart-table" id="product-table">
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
                        <tr class="product-row" data-name="<?php echo strtolower($p['name']); ?>">
                            <td><strong><?php echo $p['name']; ?></strong><br><small><?php echo $p['category'] ?? 'N/A'; ?></small></td>
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
                    <div style="display: flex; justify-content: space-between; font-size: 1.5rem; font-weight: bold; margin-bottom: 5px;">
                        <span>TỔNG CỘNG:</span>
                        <span id="grand-total">0đ</span>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="font-weight: bold;">Khách hàng:</label>
                        <select id="customer-select" style="width: 100%; padding: 10px; margin-top: 5px; font-size: 1.1rem; margin-bottom: 10px;" onchange="updateCartUI()">
                            <option value="" data-tier="Đồng">-- Khách vãng lai --</option>
                            <?php foreach ($customers as $c): ?>
                                <option value="<?php echo $c['id']; ?>" data-name="<?php echo htmlspecialchars($c['name']); ?>" data-tier="<?php echo htmlspecialchars($c['tier'] ?? 'Đồng'); ?>">
                                    <?php echo htmlspecialchars($c['name']); ?> - <?php echo htmlspecialchars($c['phone']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        
                        <label style="font-weight: bold;">Phương thức thanh toán:</label>
                        <select id="payment-method" style="width: 100%; padding: 10px; margin-top: 5px; font-size: 1.1rem;">
                            <option value="Tiền mặt">Tiền mặt</option>
                            <option value="Chuyển khoản QR">Chuyển khoản QR</option>
                        </select>
                    </div>

                    <button class="btn-checkout" onclick="processCheckout()">THANH TOÁN LƯU HÓA ĐƠN</button>
                    <button class="btn-checkout" style="background: #3498db; margin-top: 10px; display: none;" id="btn-print" onclick="showPreview()">IN BIÊN LAI</button>
                </div>
            </div>
        </div>
    </main>

    <input type="hidden" id="globalDiscountValue" value="<?php echo isset($globalDiscount) ? $globalDiscount : 0; ?>">
    <input type="hidden" id="silverDiscountValue" value="<?php echo isset($discountSilver) ? $discountSilver : 0; ?>">
    <input type="hidden" id="goldDiscountValue" value="<?php echo isset($discountGold) ? $discountGold : 0; ?>">

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
                <p style="text-align:center; margin-top:5px; font-size:12px;">Phương thức: <span id="inv-payment"></span></p>
                <p style="text-align:center; margin-top:15px; font-size:12px;">Cảm ơn quý khách!</p>
            </div>
            <div style="display:flex; gap:10px; margin-top:20px;">
                <button class="btn-confirm" onclick="executePrint()"><i class="fas fa-print"></i> XÁC NHẬN IN</button>
                <button class="btn-cancel" onclick="closeModal()">ĐÓNG</button>
            </div>
        </div>
    </div>

    <script>
        let cart = [];
        let currentOrderCode = '';
        let paymentMethodCache = '';

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

            const globalDiscountPercent = parseInt(document.getElementById('globalDiscountValue').value) || 0;
            
            // Lấy hạng khách hàng và % giảm giá tương ứng
            const customerSelect = document.getElementById('customer-select');
            const selectedOption = customerSelect.options[customerSelect.selectedIndex];
            const customerTier = selectedOption ? selectedOption.getAttribute('data-tier') : 'Đồng';
            
            let tierDiscountPercent = 0;
            if (customerTier === 'Bạc') {
                tierDiscountPercent = parseInt(document.getElementById('silverDiscountValue').value) || 0;
            } else if (customerTier === 'Vàng') {
                tierDiscountPercent = parseInt(document.getElementById('goldDiscountValue').value) || 0;
            }

            const totalDiscountPercent = globalDiscountPercent + tierDiscountPercent;
            let finalTotal = total;
            let discountHtml = '';

            if (totalDiscountPercent > 0 && total > 0) {
                const discountAmount = total * (totalDiscountPercent / 100);
                finalTotal = total - discountAmount;
                
                let detailText = '';
                if (globalDiscountPercent > 0 && tierDiscountPercent > 0) {
                    detailText = `(Hệ thống ${globalDiscountPercent}% + Hạng ${customerTier} ${tierDiscountPercent}%)`;
                } else if (globalDiscountPercent > 0) {
                    detailText = `(Hệ thống ${globalDiscountPercent}%)`;
                } else {
                    detailText = `(Hạng ${customerTier} ${tierDiscountPercent}%)`;
                }

                discountHtml = `<div style="font-size: 1rem; color: #e74c3c; margin-bottom: 5px;">
                                    Giảm giá ${detailText}: -${discountAmount.toLocaleString()}đ
                                </div>`;
            }

            // Lưu finalTotal vào div cha để thẻ <script> bên dưới dùng được nếu cần
            document.getElementById('grand-total').dataset.finalTotal = finalTotal;
            document.getElementById('grand-total').innerHTML = `${discountHtml}${finalTotal.toLocaleString()}đ`;
        }

        // TÌM KIẾM SẢN PHẨM BẰNG TÊN
        function filterProducts() {
            const input = document.getElementById('search-input').value.toLowerCase();
            const rows = document.querySelectorAll('.product-row');
            
            rows.forEach(row => {
                const name = row.getAttribute('data-name');
                if (name.includes(input)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

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

        // XỬ LÝ THANH TOÁN & LƯU HÓA ĐƠN VÀO DB
        async function processCheckout() {
            if (cart.length === 0) return alert("Giỏ hàng đang trống!");

            let total = 0;
            cart.forEach(item => {
                total += item.price * item.quantity;
            });

            // TÍNH LẠI DISCOUNT TRƯỚC KHI GỬI
            const globalDiscountPercent = parseInt(document.getElementById('globalDiscountValue').value) || 0;
            const customerSelect = document.getElementById('customer-select');
            const selectedOption = customerSelect.options[customerSelect.selectedIndex];
            const customerTier = selectedOption ? selectedOption.getAttribute('data-tier') : 'Đồng';
            
            let tierDiscountPercent = 0;
            if (customerTier === 'Bạc') {
                tierDiscountPercent = parseInt(document.getElementById('silverDiscountValue').value) || 0;
            } else if (customerTier === 'Vàng') {
                tierDiscountPercent = parseInt(document.getElementById('goldDiscountValue').value) || 0;
            }

            const totalDiscountPercent = globalDiscountPercent + tierDiscountPercent;
            let finalTotal = total;
            if (totalDiscountPercent > 0 && total > 0) {
                finalTotal = total - (total * (totalDiscountPercent / 100));
            }

            const method = document.getElementById('payment-method').value;
            // Lưu lại method để lát nếu có in bill thì hiển thị
            paymentMethodCache = method;

            const customerId = customerSelect.value;
            const customerName = customerId ? customerSelect.options[customerSelect.selectedIndex].getAttribute('data-name') : 'Khách vãng lai';

            if(!confirm(`Xác nhận thanh toán ${finalTotal.toLocaleString()}đ bằng ${method}?`)) return;

            try {
                const response = await fetch('index.php?action=cashier_checkout', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        cart: cart,
                        total: finalTotal, // Gửi giá thực tế khách phải trả
                        original_total: total, // Gửi thêm giá gốc để lưu vết nếu cần
                        customer_id: customerId,
                        customer_name: customerName,
                        payment_method: method
                    })
                });
                
                const text = await response.text();
                let result;
                try {
                    result = JSON.parse(text);
                } catch (e) {
                    console.error("Server raw response:", text);
                    alert("Lỗi máy chủ (không trả về JSON): " + text.substring(0, 200));
                    return;
                }
                
                if (result.success) {
                    alert("Thanh toán thành công! Mã đơn: " + result.order_code);
                    currentOrderCode = result.order_code;
                    
                    // Cập nhật trạng thái nút
                    const checkoutBtn = document.querySelector('.btn-checkout');
                    checkoutBtn.innerText = 'ĐÃ THANH TOÁN (LƯU HĐ)';
                    checkoutBtn.style.background = '#7f8c8d';
                    checkoutBtn.disabled = true;
                    checkoutBtn.removeAttribute('onclick');
                    
                    // Hiện nút IN BIÊN LAI
                    const printBtn = document.getElementById('btn-print');
                    printBtn.style.display = 'block';
                    
                    // Thêm nút "Đơn mới"
                    const newOrderBtn = document.createElement('button');
                    newOrderBtn.className = 'btn-checkout';
                    newOrderBtn.style.background = '#f39c12';
                    newOrderBtn.style.marginTop = '10px';
                    newOrderBtn.innerText = 'BẮT ĐẦU ĐƠN MỚI';
                    newOrderBtn.onclick = () => window.location.reload();
                    printBtn.parentNode.appendChild(newOrderBtn);
                    
                } else {
                    alert('Lỗi thanh toán: ' + result.message);
                }
            } catch (error) {
                console.error("Lỗi JS:", error);
                alert("Đã xảy ra lỗi kết nối mạng hoặc lỗi JS!");
            }
        }

        // HÀM XEM TRƯỚC HÓA ĐƠN
        function showPreview() {
            if (cart.length === 0) return alert("Giỏ hàng đang trống!");

            document.getElementById('inv-date').innerText = new Date().toLocaleString();
            document.getElementById('inv-payment').innerText = paymentMethodCache;
            
            const details = document.getElementById('inv-details');
            let total = 0;
            let detailsHtml = '';
            cart.forEach(item => {
                const sub = item.price * item.quantity;
                total += sub;
                detailsHtml += `
                    <tr>
                        <td>${item.name}</td>
                        <td style="text-align:center;">${item.quantity}</td>
                        <td style="text-align:right;">${sub.toLocaleString()}</td>
                    </tr>
                `;
            });
            document.getElementById('inv-details').innerHTML = detailsHtml;

            // Tính discount cho hóa đơn in
            const globalDiscountPercent = parseInt(document.getElementById('globalDiscountValue').value) || 0;
            const customerSelect = document.getElementById('customer-select');
            const selectedOption = customerSelect.options[customerSelect.selectedIndex];
            const customerTier = selectedOption ? selectedOption.getAttribute('data-tier') : 'Đồng';
            
            let tierDiscountPercent = 0;
            if (customerTier === 'Bạc') {
                tierDiscountPercent = parseInt(document.getElementById('silverDiscountValue').value) || 0;
            } else if (customerTier === 'Vàng') {
                tierDiscountPercent = parseInt(document.getElementById('goldDiscountValue').value) || 0;
            }

            const totalDiscountPercent = globalDiscountPercent + tierDiscountPercent;

            let finalTotalHtml = total.toLocaleString() + 'đ';

            if (totalDiscountPercent > 0 && total > 0) {
                const discountAmount = total * (totalDiscountPercent / 100);
                const finalTotal = total - discountAmount;
                
                let detailText = '';
                if (globalDiscountPercent > 0 && tierDiscountPercent > 0) {
                    detailText = `(${globalDiscountPercent}% + Hạng ${customerTier} ${tierDiscountPercent}%)`;
                } else if (globalDiscountPercent > 0) {
                    detailText = `(Hệ thống ${globalDiscountPercent}%)`;
                } else {
                    detailText = `(Hạng ${customerTier} ${tierDiscountPercent}%)`;
                }

                finalTotalHtml = `
                    <div style="font-size: 14px; font-weight: normal; margin-bottom: 5px;">Cộng tiền: ${total.toLocaleString()}đ</div>
                    <div style="font-size: 14px; font-weight: normal; margin-bottom: 5px;">Giảm giá ${detailText}: -${discountAmount.toLocaleString()}đ</div>
                    <div style="font-size: 18px;">KHÁCH TRẢ: ${finalTotal.toLocaleString()}đ</div>
                `;
            } else {
                finalTotalHtml = `TỔNG: ${total.toLocaleString()}đ`;
            }

            document.getElementById('inv-total').innerHTML = finalTotalHtml;
            document.getElementById('inv-payment').innerText = paymentMethodCache;

            document.getElementById('invoiceModal').style.display = 'block';
        }

        function closeModal() { 
            document.getElementById('invoiceModal').style.display = 'none';
            // Nếu đóng modal sau khi đã lưu DB thì refresh trang
            if(currentOrderCode !== '') {
                window.location.reload();
            }
        }

        // HÀM IN PDF CHÍNH THỨC
        function executePrint() {
            const element = document.getElementById('printArea');
            const fileName = currentOrderCode ? currentOrderCode + '.pdf' : 'HoaDon_' + Date.now() + '.pdf';
            
            const opt = {
                margin: 10,
                filename: fileName,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };

            html2pdf().set(opt).from(element).save().then(() => {
                alert("Đã xuất hóa đơn thành công!");
                window.location.reload();
            });
        }
    </script>
</body>
</html>