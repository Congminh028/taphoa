<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Quản Lý Tạp Hóa</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include '../views/partials/sidebar.php'; ?>

        <main class="main-content">
            <header>
                <h1>BẢNG ĐIỀU KHIỂN</h1>
                <p>Quản lý mọi thứ tại đây</p>
            </header>

            <div class="card-grid">
                <?php 
                function hasPerm($p, $perms) {
                    return in_array('ADMIN', $perms) || in_array($p, $perms);
                }
                ?>

                <?php if (hasPerm('UC1', $permissions)): ?>
                <a href="index.php?action=products" class="card">
                    <div class="card-icon"><i class="fas fa-box"></i></div>
                    <h3>KHO HÀNG</h3>
                    <p>Quản lý sản phẩm</p>
                </a>
                <?php endif; ?>

                <?php if (hasPerm('UC2', $permissions)): ?>
                <a href="index.php?action=imports" class="card">
                    <div class="card-icon"><i class="fas fa-truck"></i></div>
                    <h3>NHẬP HÀNG</h3>
                    <p>Nhập kho & NCC</p>
                </a>
                <?php endif; ?>

                <?php if (hasPerm('UC3', $permissions)): ?>
                <a href="index.php?action=pos" class="card">
                    <div class="card-icon"><i class="fas fa-cash-register"></i></div>
                    <h3>THU NGÂN</h3>
                    <p>Bán hàng</p>
                </a>
                <?php endif; ?>

                <?php if (hasPerm('UC4', $permissions)): ?>
                <a href="index.php?action=customers" class="card">
                    <div class="card-icon"><i class="fas fa-users"></i></div>
                    <h3>KHÁCH HÀNG</h3>
                    <p>Thành viên</p>
                </a>
                <?php endif; ?>

                <?php if (hasPerm('UC5', $permissions)): ?>
                <a href="index.php?action=reports" class="card">
                    <div class="card-icon"><i class="fas fa-chart-line"></i></div>
                    <h3>BÁO CÁO</h3>
                    <p>Doanh thu</p>
                </a>
                <?php endif; ?>
                
                
            </div>
        </main>
    </div>
    <div id="ai-chat-container" style="position: fixed; bottom: 30px; right: 30px; z-index: 1000;">
    <button id="ai-toggle-btn" style="width: 60px; height: 60px; border-radius: 50%; background: #000; color: #fff; border: none; cursor: pointer; box-shadow: 0 4px 15px rgba(0,0,0,0.3); font-size: 24px;">
        <i class="fas fa-robot"></i>
    </button>

    <div id="ai-chat-window" style="display: none; width: 350px; height: 450px; background: #fff; border-radius: 15px; box-shadow: 0 8px 30px rgba(0,0,0,0.2); position: absolute; bottom: 80px; right: 0; flex-direction: column; border: 1px solid #eee; overflow: hidden;">
        <div style="background: #000; color: #fff; padding: 15px; font-weight: bold; display: flex; justify-content: space-between; align-items: center;">
            <span><i class="fas fa-brain"></i> Trợ lý TAPHOA AI</span>
            <button onclick="toggleAIChat()" style="background:none; border:none; color:#fff; cursor:pointer; font-size: 20px;">&times;</button>
        </div>
        
        <div id="ai-messages" style="flex: 1; padding: 15px; overflow-y: auto; background: #f9f9f9; font-size: 14px; display: flex; flex-direction: column; gap: 10px;">
            <div style="background: #ececec; padding: 10px; border-radius: 10px; align-self: flex-start; max-width: 80%;">
                Chào **Minh**! Tôi có thể giúp gì cho hệ thống quản lý của bạn?
            </div>
        </div>

        <div style="padding: 10px; border-top: 1px solid #eee; display: flex; gap: 5px; background: #fff;">
            <input type="text" id="ai-user-input" placeholder="Nhập câu hỏi..." style="flex: 1; border: 1px solid #ddd; padding: 10px; border-radius: 20px; outline: none;">
            <button onclick="sendToAI()" style="background: #000; color: #fff; border: none; width: 40px; height: 40px; border-radius: 50%; cursor: pointer;">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>
<script>
const chatWindow = document.getElementById('ai-chat-window');
const chatMessages = document.getElementById('ai-messages');
const userInput = document.getElementById('ai-user-input');

function toggleAIChat() {
    chatWindow.style.display = (chatWindow.style.display === 'none' || chatWindow.style.display === '') ? 'flex' : 'none';
}

document.getElementById('ai-toggle-btn').onclick = toggleAIChat;

async function sendToAI() {
    const text = userInput.value.trim();
    if (!text) return;

    // Hiển thị tin nhắn người dùng
    chatMessages.innerHTML += `<div style="background: #000; color: #fff; padding: 10px; border-radius: 10px; align-self: flex-end; max-width: 80%;">${text}</div>`;
    userInput.value = '';
    chatMessages.scrollTop = chatMessages.scrollHeight;

    // Hiển thị trạng thái đang trả lời
    const loadingId = 'ai-loading-' + Date.now();
    chatMessages.innerHTML += `<div id="${loadingId}" style="background: #ececec; padding: 10px; border-radius: 10px; align-self: flex-start;">Đang suy nghĩ...</div>`;

    try {
        // Gửi yêu cầu đến Router index.php
        const response = await fetch('index.php?action=ask_ai', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'message=' + encodeURIComponent(text)
        });
        const data = await response.json();
        
        document.getElementById(loadingId).innerText = data.answer;
    } catch (error) {
        document.getElementById(loadingId).innerText = "Lỗi kết nối AI rồi Minh ơi!";
    }
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Cho phép nhấn Enter để gửi
userInput.onkeypress = (e) => { if(e.key === 'Enter') sendToAI(); };
</script>
</body>
</html>