<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tra cứu Lịch sử Giao dịch</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .search-box { padding: 10px; width: 100%; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; }
        .filter-section { background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: end; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <?php include '../views/partials/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="list-header">
                <div>
                    <a href="javascript:history.back()" class="back-btn" style="display:inline-block; margin-bottom:10px; color:#666; text-decoration:none; font-weight:bold;"><i class="fas fa-arrow-left"></i> Quay lại</a>
                    <h1 style="font-family: 'Oswald', sans-serif;"><i class="fas fa-history"></i> TRA CỨU LỊCH SỬ GIAO DỊCH</h1>
                    <p>Bộ lọc & Thống kê thông minh</p>
                </div>
            </div>

            <!-- Filters -->
            <div class="filter-section">
                <div>
                    <label style="font-size: 13px; font-weight: bold; display: block; margin-bottom: 5px;">CHỌN KHÁCH HÀNG (BẮT BUỘC)</label>
                    <select id="customerIdSelect" class="search-box">
                        <option value="">-- Vui lòng chọn khách hàng --</option>
                        <?php foreach($customers as $c): ?>
                            <option value="<?php echo $c['id']; ?>" data-name="<?php echo htmlspecialchars($c['name']); ?>">
                                <?php echo htmlspecialchars($c['name']); ?> - <?php echo htmlspecialchars($c['phone']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label style="font-size: 13px; font-weight: bold; display: block; margin-bottom: 5px;">TỪ NGÀY</label>
                    <input type="date" id="filterFromDate" class="search-box">
                </div>
                <div>
                    <label style="font-size: 13px; font-weight: bold; display: block; margin-bottom: 5px;">ĐẾN NGÀY</label>
                    <input type="date" id="filterToDate" class="search-box">
                </div>
                <div>
                    <label style="font-size: 13px; font-weight: bold; display: block; margin-bottom: 5px;">MÃ HÓA ĐƠN</label>
                    <input type="text" id="filterOrderCode" class="search-box" placeholder="Ví dụ: HD-2026...">
                </div>
                <div>
                    <button onclick="applyHistoryFilter()" class="btn-black" style="width: 100%; height: 42px;"><i class="fas fa-search"></i> LỌC TÌM KIẾM</button>
                </div>
            </div>

            <div style="text-align: right; margin-bottom: 15px;">
                <button type="button" class="btn-black" style="background: #2ecc71;" onclick="exportExcel()"><i class="fas fa-file-csv"></i> XUẤT EXCEL BÁO CÁO</button>
            </div>

            <!-- Stats -->
            <div id="historyStats" style="display: flex; gap: 20px; margin-bottom: 20px; font-size: 16px;">
                <!-- Stats injected here -->
            </div>

            <!-- Content Table -->
            <div id="historyContent" class="table-container">
                <p style='text-align:center; padding: 40px; color:#666; font-size: 1.1rem;'><i class="fas fa-arrow-up" style="font-size: 2rem; display:block; margin-bottom:10px;"></i>Vui lòng chọn khách hàng bên trên để xem lịch sử.</p>
            </div>
        </main>
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

        function applyHistoryFilter() {
            const selectEl = document.getElementById('customerIdSelect');
            if(!selectEl.value) {
                alert("Vui lòng chọn khách hàng cần tra cứu!");
                return;
            }
            
            currentCustomerId = selectEl.value;
            currentCustomerName = selectEl.options[selectEl.selectedIndex].getAttribute('data-name');
            
            fetchHistoryData();
        }

        async function fetchHistoryData() {
            const contentDiv = document.getElementById('historyContent');
            contentDiv.innerHTML = "<p style='text-align:center; padding:20px;'>Đang tải dữ liệu...</p>";
            document.getElementById('historyStats').innerHTML = '';
            
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
                    contentDiv.innerHTML = `<p style="color:red; text-align:center;">Lỗi: ${result.message}</p>`;
                }
            } catch (error) {
                contentDiv.innerHTML = `<p style="color:red; text-align:center;">Lỗi kết nối máy chủ!</p>`;
            }
        }

        function renderHistoryData(data, stats) {
            // Render Stats
            document.getElementById('historyStats').innerHTML = `
                <div style="background:#e8f4f8; border-left: 5px solid #3498db; padding:15px; border-radius:5px; flex:1;">
                    <div style="font-size:12px; color:#555;">TỔNG SỐ ĐƠN</div>
                    <div style="font-size:24px; font-weight:bold; color:#2980b9">${stats.total_orders}</div>
                </div>
                <div style="background:#fef5e7; border-left: 5px solid #e67e22; padding:15px; border-radius:5px; flex:1;">
                    <div style="font-size:12px; color:#555;">TỔNG CHI TIÊU</div>
                    <div style="font-size:24px; font-weight:bold; color:#d35400">${stats.total_spent.toLocaleString()}đ</div>
                </div>
                <div style="background:#e8f8f5; border-left: 5px solid #2ecc71; padding:15px; border-radius:5px; flex:1;">
                    <div style="font-size:12px; color:#555;">TẦN SUẤT MUA</div>
                    <div style="font-size:24px; font-weight:bold; color:#27ae60">${stats.avg_frequency}</div>
                </div>
            `;

            // Render Table
            const contentDiv = document.getElementById('historyContent');
            if (data.length === 0) {
                contentDiv.innerHTML = "<p style='text-align:center; padding: 40px; color:#666;'>Không tìm thấy giao dịch nào phù hợp.</p>";
                return;
            }

            let html = `
                <table class="nike-table">
                    <thead>
                        <tr>
                            <th>MÃ HĐ</th>
                            <th>NGÀY MUA</th>
                            <th>TỔNG TIỀN / ĐIỂM</th>
                            <th style="width: 40%;">CHI TIẾT MUA HÀNG</th>
                            <th>IN LẠI</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            data.forEach((order, index) => {
                let detailsHtml = '';
                order.details.forEach(item => {
                    detailsHtml += `<div style="font-size: 14px; color: #444; border-bottom: 1px dotted #ccc; padding: 5px 0;">
                                        - <strong>${item.name}</strong> (SL: ${item.quantity}) - ${(item.price * item.quantity).toLocaleString()}đ
                                    </div>`;
                });

                html += `
                    <tr>
                        <td><span class="badge-code" style="font-size:13px;">${order.order_code}</span></td>
                        <td style="font-size:14px; font-weight: 500;">${order.created_at}</td>
                        <td>
                            <strong style="color:#000; font-size: 16px;">${order.total_amount.toLocaleString()}đ</strong><br>
                            <span style="font-size:13px; color:#27ae60; font-weight: bold;">+${order.points_earned} điểm</span>
                        </td>
                        <td>
                            <div style="background:#f9f9f9; padding:10px; border-radius:6px; border: 1px solid #eee;">
                                ${detailsHtml}
                            </div>
                        </td>
                        <td>
                            <button onclick="reprintInvoice(${index})" class="btn-black" style="background:#95a5a6; padding: 8px 15px;"><i class="fas fa-print"></i></button>
                        </td>
                    </tr>
                `;
            });

            html += `</tbody></table>`;
            contentDiv.innerHTML = html;
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
                jsPDF: { unit: 'mm', format: [80, 200], orientation: 'portrait' } 
            };

            html2pdf().set(opt).from(element).save().then(() => {
                document.getElementById('printArea').style.display = 'none';
            });
        }

        function exportExcel() {
            if (currentHistoryData.length === 0) {
                alert("Vui lòng tải dữ liệu lịch sử trước khi xuất!");
                return;
            }

            let csvContent = "data:text/csv;charset=utf-8,\uFEFF"; 
            csvContent += "Mã HĐ,Ngày mua,Sản phẩm,Số lượng,Đơn giá,Thành tiền,Tổng hóa đơn,Điểm cộng\n";

            currentHistoryData.forEach(order => {
                order.details.forEach((item, index) => {
                    let totalStr = index === 0 ? order.total_amount : '';
                    let pointsStr = index === 0 ? order.points_earned : '';
                    
                    let row = `${order.order_code},${order.created_at},"${item.name}",${item.quantity},${item.price},${item.price * item.quantity},${totalStr},${pointsStr}`;
                    csvContent += row + "\n";
                });
            });

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", `LichSuGiaoDich_${currentCustomerName}_${new Date().toISOString().split('T')[0]}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
</body>
</html>
