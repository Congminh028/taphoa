<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh Sách Khách Hàng</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .modal { display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.6); }
        .modal-content { background:#fff; margin:5% auto; padding:25px; width:500px; border-radius:10px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); }
        .search-box { padding: 10px; width: 300px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <?php include '../views/partials/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="list-header">
                <div>
                    <a href="javascript:history.back()" class="back-btn" style="display:inline-block; margin-bottom:10px; color:#666; text-decoration:none; font-weight:bold;"><i class="fas fa-arrow-left"></i> Quay lại</a>
                    <h1 style="font-family: 'Oswald', sans-serif;">HỒ SƠ KHÁCH HÀNG</h1>
                    <p>Quản lý thông tin & Lịch sử mua hàng</p>
                </div>
                <div style="display: flex; gap: 10px;">
                    <input type="text" id="searchInput" class="search-box" placeholder="Tìm bằng số điện thoại..." onkeyup="searchCustomer()">
                    <button class="btn-black" onclick="openModal()"><i class="fas fa-plus"></i> THÊM MỚI</button>
                </div>
            </div>

            <?php if(isset($_SESSION['success'])): ?>
                <div class="alert alert-success" style="background: #2ecc71; color: white; padding: 10px; margin-bottom: 15px;"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>
            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger" style="background: #e74c3c; color: white; padding: 10px; margin-bottom: 15px;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <div class="table-container">
                <table class="nike-table" id="customerTable">
                    <thead>
                        <tr>
                            <th>TÊN KHÁCH HÀNG</th>
                            <th>SĐT</th>
                            <th>ĐIỂM</th>
                            <th>HẠNG</th>
                            <th>THAO TÁC</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($customers)): ?>
                            <?php foreach($customers as $c): ?>
                            <tr>
                                <td style="font-weight: bold;"><?php echo htmlspecialchars($c['name']); ?></td>
                                <td class="c-phone"><?php echo htmlspecialchars($c['phone']); ?></td>
                                <td><?php echo $c['points']; ?></td>
                                <td><span class="badge" style="background: <?php echo $c['tier']=='Vàng'?'#FFD700':($c['tier']=='Bạc'?'#C0C0C0':'#cd7f32'); ?>"><?php echo $c['tier']; ?></span></td>
                                <td>
                                    <button onclick='loadHistory(<?php echo $c['id']; ?>, "<?php echo htmlspecialchars($c['name']); ?>")' class="btn-black" style="padding: 5px 10px; font-size: 12px; background: #3498db;"><i class="fas fa-history"></i> LỊCH SỬ</button>
                                    <button onclick='editCustomer(<?php echo json_encode($c); ?>)' class="btn-black" style="padding: 5px 10px; font-size: 12px; background: #f39c12;"><i class="fas fa-edit"></i> SỬA</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" style="text-align:center;">Chưa có khách hàng nào.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Modal Form (Thêm/Sửa) -->
    <div id="customerModal" class="modal">
        <div class="modal-content">
            <h2 id="modalTitle" style="font-family: 'Oswald', sans-serif; margin-bottom: 15px;">THÊM KHÁCH HÀNG</h2>
            <form action="index.php?action=customer_store" method="POST" class="nike-form">
                <input type="hidden" name="id" id="custId">
                <div class="form-group">
                    <label>SỐ ĐIỆN THOẠI (Bắt buộc)</label>
                    <input type="text" name="phone" id="custPhone" required>
                </div>
                <div class="form-group">
                    <label>TÊN KHÁCH HÀNG</label>
                    <input type="text" name="name" id="custName" required>
                </div>
                <div class="form-group">
                    <label>ĐỊA CHỈ</label>
                    <input type="text" name="address" id="custAddress">
                </div>
                <div style="display:flex; gap:10px; margin-top: 20px;">
                    <button type="submit" class="btn-black btn-large" style="flex: 1;">LƯU THÔNG TIN</button>
                    <button type="button" class="btn-black btn-large" style="flex: 1; background: #e74c3c;" onclick="closeModal()">HỦY</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Lịch sử AJAX Nâng cao -->
    <div id="historyModal" class="modal">
        <div class="modal-content" style="width: 800px; max-width: 90%;">
            <h2 style="font-family: 'Oswald', sans-serif; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 15px;">
                <i class="fas fa-file-invoice-dollar"></i> LỊCH SỬ GIAO DỊCH - <span id="histName"></span>
            </h2>
            
            <!-- Filters -->
            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px; display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
                <div>
                    <label style="font-size: 12px; font-weight: bold; display: block; margin-bottom: 5px;">TỪ NGÀY</label>
                    <input type="date" id="filterFromDate" class="search-box" style="width: 150px; padding: 8px;">
                </div>
                <div>
                    <label style="font-size: 12px; font-weight: bold; display: block; margin-bottom: 5px;">ĐẾN NGÀY</label>
                    <input type="date" id="filterToDate" class="search-box" style="width: 150px; padding: 8px;">
                </div>
                <div>
                    <label style="font-size: 12px; font-weight: bold; display: block; margin-bottom: 5px;">MÃ HĐ</label>
                    <input type="text" id="filterOrderCode" class="search-box" placeholder="HD-..." style="width: 150px; padding: 8px;">
                </div>
                <button onclick="applyHistoryFilter()" class="btn-black" style="padding: 10px 20px;"><i class="fas fa-filter"></i> LỌC</button>
            </div>

            <!-- Stats -->
            <div id="historyStats" style="display: flex; gap: 20px; margin-bottom: 20px; font-size: 14px;">
                <!-- Stats injected here -->
            </div>

            <div id="historyContent" style="max-height: 400px; overflow-y: auto;">
                <p>Đang tải...</p>
            </div>
            
            <div style="text-align: right; margin-top: 20px; border-top: 2px solid #eee; padding-top: 15px;">
                <button type="button" class="btn-black" style="background: #2ecc71;" onclick="exportExcel()"><i class="fas fa-file-csv"></i> XUẤT EXCEL</button>
                <button type="button" class="btn-black" style="background: #e74c3c;" onclick="closeHistoryModal()"><i class="fas fa-times"></i> ĐÓNG</button>
            </div>
        </div>
    </div>

    <!-- Hidden Print Area -->
    <div id="printArea" style="display: none; padding:10px; font-family: 'Courier New', monospace; color: #000; width: 80mm; background: white;">
        <div style="text-align:center; border-bottom: 1px dashed #000; padding-bottom: 10px;">
            <h2 style="margin:0; font-size: 16px;">TAPHOA TẠP NHAM</h2>
            <p style="margin:5px 0; font-size:12px;">Địa chỉ: 123 Đường Tài Chính, Hà Nội</p>
            <p style="margin:0; font-size:12px;">Hóa đơn: <span id="printCode"></span></p>
            <p style="margin:0; font-size:12px;">Ngày: <span id="printDate"></span></p>
            <p style="margin:0; font-size:12px;">Khách hàng: <span id="printCustomer"></span></p>
        </div>
        <table style="width:100%; margin-top:15px; border-collapse: collapse; font-size: 12px;">
            <thead>
                <tr style="border-bottom: 1px solid #000;">
                    <th style="text-align:left;">SP</th>
                    <th style="text-align:center;">SL</th>
                    <th style="text-align:right;">T.Tiền</th>
                </tr>
            </thead>
            <tbody id="printDetails"></tbody>
        </table>
        <div style="text-align:right; margin-top:15px; font-weight:bold; font-size:14px; border-top: 1px dashed #000; padding-top:10px;">
            TỔNG CỘNG: <span id="printTotal"></span>
        </div>
        <p style="text-align:center; margin-top:10px; font-size:11px;">(Đại bản sao (Re-printed))</p>
        <p style="text-align:center; margin-top:5px; font-size:11px;">Cảm ơn quý khách!</p>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        let currentCustomerId = null;
        let currentCustomerName = '';
        let currentHistoryData = [];

        // Modal Logic
        function openModal() {
            document.getElementById('modalTitle').innerText = "THÊM KHÁCH HÀNG MỚI";
            document.getElementById('custId').value = "";
            document.getElementById('custPhone').value = "";
            document.getElementById('custName').value = "";
            document.getElementById('custAddress').value = "";
            document.getElementById('customerModal').style.display = 'block';
        }

        function editCustomer(customer) {
            document.getElementById('modalTitle').innerText = "CẬP NHẬT THÔNG TIN";
            document.getElementById('custId').value = customer.id;
            document.getElementById('custPhone').value = customer.phone;
            document.getElementById('custName').value = customer.name;
            document.getElementById('custAddress').value = customer.address;
            document.getElementById('customerModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('customerModal').style.display = 'none';
        }

        function searchCustomer() {
            let input = document.getElementById('searchInput').value.toLowerCase();
            let table = document.getElementById("customerTable");
            let tr = table.getElementsByTagName("tr");

            for (let i = 1; i < tr.length; i++) {
                let tdPhone = tr[i].getElementsByClassName("c-phone")[0];
                if (tdPhone) {
                    let textValue = tdPhone.textContent || tdPhone.innerText;
                    if (textValue.toLowerCase().indexOf(input) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

        // --- HISTORY LOGIC ---

        function loadHistory(customerId, customerName) {
            currentCustomerId = customerId;
            currentCustomerName = customerName;
            document.getElementById('histName').innerText = customerName;
            
            // Reset filters
            document.getElementById('filterFromDate').value = '';
            document.getElementById('filterToDate').value = '';
            document.getElementById('filterOrderCode').value = '';

            fetchHistoryData();
            document.getElementById('historyModal').style.display = 'block';
        }

        function applyHistoryFilter() {
            fetchHistoryData();
        }

        async function fetchHistoryData() {
            const contentDiv = document.getElementById('historyContent');
            contentDiv.innerHTML = "<p>Đang tải dữ liệu...</p>";
            
            let url = `index.php?action=customer_history&customer_id=${currentCustomerId}`;
            const fromDate = document.getElementById('filterFromDate').value;
            const toDate = document.getElementById('filterToDate').value;
            const orderCode = document.getElementById('filterOrderCode').value;

            if(fromDate) url += `&date_from=${fromDate}`;
            if(toDate) url += `&date_to=${toDate}`;
            if(orderCode) url += `&order_code=${orderCode}`;

            try {
                const response = await fetch(url);
                const result = await response.json();
                
                if(result.success) {
                    currentHistoryData = result.data;
                    renderHistoryData(result.data, result.stats);
                } else {
                    contentDiv.innerHTML = `<p style="color:red;">Lỗi: ${result.message}</p>`;
                }
            } catch (error) {
                contentDiv.innerHTML = `<p style="color:red;">Lỗi kết nối máy chủ!</p>`;
            }
        }

        function renderHistoryData(data, stats) {
            // Render Stats
            document.getElementById('historyStats').innerHTML = `
                <div style="background:#e8f4f8; padding:10px; border-radius:5px; flex:1;">
                    <strong>Tổng số Đơn:</strong> <span style="color:#2980b9">${stats.total_orders}</span>
                </div>
                <div style="background:#e8f4f8; padding:10px; border-radius:5px; flex:1;">
                    <strong>Tổng Chi tiêu:</strong> <span style="color:#e67e22">${stats.total_spent.toLocaleString()}đ</span>
                </div>
                <div style="background:#e8f4f8; padding:10px; border-radius:5px; flex:1;">
                    <strong>Tần suất Mua:</strong> <span style="color:#27ae60">${stats.avg_frequency}</span>
                </div>
            `;

            // Render Table
            const contentDiv = document.getElementById('historyContent');
            if (data.length === 0) {
                contentDiv.innerHTML = "<p style='text-align:center; padding: 20px; color:#666;'>Không tìm thấy giao dịch nào phù hợp.</p>";
                return;
            }

            let html = `
                <table class="nike-table">
                    <thead>
                        <tr>
                            <th>MÃ HĐ</th>
                            <th>NGÀY MUA</th>
                            <th>TỔNG TIỀN / ĐIỂM</th>
                            <th>CHI TIẾT MUA HÀNG</th>
                            <th>IN LẠI</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            data.forEach((order, index) => {
                let detailsHtml = '';
                order.details.forEach(item => {
                    detailsHtml += `<div style="font-size: 13px; color: #555; border-bottom: 1px dotted #ccc; padding: 3px 0;">
                                        - ${item.name} (x${item.quantity}): ${(item.price * item.quantity).toLocaleString()}đ
                                    </div>`;
                });

                html += `
                    <tr>
                        <td><span class="badge-code" style="font-size:12px;">${order.order_code}</span></td>
                        <td style="font-size:13px;">${order.created_at}</td>
                        <td>
                            <strong style="color:#000;">${order.total_amount.toLocaleString()}đ</strong><br>
                            <span style="font-size:12px; color:#27ae60;">+${order.points_earned} điểm</span>
                        </td>
                        <td>
                            <div style="background:#f9f9f9; padding:5px; border-radius:4px;">
                                ${detailsHtml}
                            </div>
                        </td>
                        <td>
                            <button onclick="reprintInvoice(${index})" class="btn-black" style="background:#95a5a6; padding: 5px; font-size:12px;"><i class="fas fa-print"></i></button>
                        </td>
                    </tr>
                `;
            });

            html += `</tbody></table>`;
            contentDiv.innerHTML = html;
        }

        function closeHistoryModal() {
            document.getElementById('historyModal').style.display = 'none';
        }

        // --- IN ẤN & XUẤT FILE ---

        function reprintInvoice(orderIndex) {
            const order = currentHistoryData[orderIndex];
            
            document.getElementById('printCode').innerText = order.order_code;
            document.getElementById('printDate').innerText = order.created_at;
            document.getElementById('printCustomer').innerText = currentCustomerName;
            
            let detailsHtml = '';
            order.details.forEach(item => {
                detailsHtml += `
                    <tr>
                        <td>${item.name}</td>
                        <td style="text-align:center;">${item.quantity}</td>
                        <td style="text-align:right;">${(item.price * item.quantity).toLocaleString()}</td>
                    </tr>
                `;
            });
            document.getElementById('printDetails').innerHTML = detailsHtml;
            document.getElementById('printTotal').innerText = order.total_amount.toLocaleString() + 'đ';

            document.getElementById('printArea').style.display = 'block';

            // Dùng html2pdf
            const element = document.getElementById('printArea');
            const opt = {
                margin: 2,
                filename: `RePrint_${order.order_code}.pdf`,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'mm', format: [80, 200], orientation: 'portrait' } // Mô phỏng máy in nhiệt 80mm
            };

            html2pdf().set(opt).from(element).save().then(() => {
                document.getElementById('printArea').style.display = 'none';
            });
        }

        function exportExcel() {
            if (currentHistoryData.length === 0) {
                alert("Không có dữ liệu để xuất!");
                return;
            }

            let csvContent = "data:text/csv;charset=utf-8,\uFEFF"; // Thêm BOM để hỗ trợ tiếng Việt
            csvContent += "Mã HĐ,Ngày mua,Sản phẩm,Số lượng,Đơn giá,Thành tiền,Tổng hóa đơn,Điểm cộng\n";

            currentHistoryData.forEach(order => {
                order.details.forEach((item, index) => {
                    // Cột tổng hóa đơn và điểm chỉ hiện ở dòng đầu tiên của mỗi hóa đơn
                    let totalStr = index === 0 ? order.total_amount : '';
                    let pointsStr = index === 0 ? order.points_earned : '';
                    
                    let row = `${order.order_code},${order.created_at},"${item.name}",${item.quantity},${item.price},${item.price * item.quantity},${totalStr},${pointsStr}`;
                    csvContent += row + "\n";
                });
            });

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", `LichSu_${currentCustomerName}_${new Date().toISOString().split('T')[0]}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
</body>
</html>
