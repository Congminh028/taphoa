<?php
// views/partials/sidebar.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$username = $_SESSION['username'] ?? 'User';
$permissions = $_SESSION['permissions'] ?? [];
$userRole = in_array('ADMIN', $permissions) ? 'Administrator' : 'Nhân viên';

// Lấy tên bộ phận từ permissions (UC1: Kho, UC2: Nhập hàng, UC3: Thu ngân, UC4: Khách hàng, UC5: Báo cáo)
$departmentName = 'Nội bộ';
$myChannel = 'GLOBAL';

if (in_array('ADMIN', $permissions)) {
    $departmentName = 'Quản lý';
    $myChannel = 'ADMIN';
} else if (in_array('UC1', $permissions)) { $departmentName = 'Kho hàng'; $myChannel = 'KHO'; }
else if (in_array('UC2', $permissions)) { $departmentName = 'Nhập hàng'; $myChannel = 'NHAP_HANG'; }
else if (in_array('UC3', $permissions)) { $departmentName = 'Thu ngân'; $myChannel = 'THU_NGAN'; }
else if (in_array('UC4', $permissions)) { $departmentName = 'Khách hàng'; $myChannel = 'KHACH_HANG'; }
else if (in_array('UC5', $permissions)) { $departmentName = 'Báo cáo'; $myChannel = 'BAO_CAO'; }
?>

<aside class="sidebar">
    <div class="brand-box">
        <div class="brand-icon">
            <i class="fas fa-shopping-basket"></i>
        </div>
        <div class="brand-text">
            <h2>TAPHOA</h2>
            <p>MANAGER PRO</p>
        </div>
    </div>

    <div class="user-profile-card">
        <div class="avatar-wrapper">
            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($username); ?>&background=000&color=fff" alt="User">
            <div class="status-dot pulse"></div>
        </div>
        <div class="user-meta">
            <span class="user-name"><?php echo $username; ?></span>
            <span class="user-role"><?php echo $userRole; ?></span>
            <span class="status-text">Đang hoạt động</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="menu-label">Main Menu</div>
        
        <a href="index.php?action=dashboard" class="menu-item <?php echo (!isset($_GET['action']) || $_GET['action'] == 'dashboard') ? 'active' : ''; ?>">
            <i class="fas fa-th-large"></i> <span>Tổng quan</span>
        </a>

        <?php if (in_array('UC1', $permissions) || in_array('ADMIN', $permissions)): ?>
        <a href="index.php?action=products" class="menu-item <?php echo (isset($_GET['action']) && $_GET['action'] == 'products') ? 'active' : ''; ?>">
            <i class="fas fa-box"></i> <span>Kho hàng</span>
        </a>
        <?php endif; ?>

        <?php if (in_array('UC5', $permissions) || in_array('ADMIN', $permissions)): ?>
        <a href="index.php?action=reports" class="menu-item <?php echo (isset($_GET['action']) && strpos($_GET['action'], 'report') === 0) ? 'active' : ''; ?>">
            <i class="fas fa-chart-line"></i> <span>Báo cáo</span>
        </a>
        <?php endif; ?>

        <?php if (in_array('UC2', $permissions) || in_array('ADMIN', $permissions)): ?>
        <a href="index.php?action=imports" class="menu-item">
            <i class="fas fa-truck-loading"></i> <span>Nhập hàng</span>
        </a>
        <?php endif; ?>

        <?php if (in_array('UC3', $permissions) || in_array('ADMIN', $permissions)): ?>
        <a href="index.php?action=pos" class="menu-item">
            <i class="fas fa-cash-register"></i> <span>Thu ngân</span>
        </a>
        <?php endif; ?>

        <a href="index.php?action=customers" class="menu-item">
            <i class="fas fa-users"></i> <span>Khách hàng</span>
        </a>

        <?php // Cấu hình đã được gộp vào Khách hàng -> Quản lý thành viên ?>

        <a href="index.php?action=logout" class="menu-item logout">
            <i class="fas fa-sign-out-alt"></i> <span>Đăng xuất</span>
        </a>
    </nav>

    <?php 
    $chatDeptName = $departmentName; 
    // $myChannel đã được định nghĩa ở trên
    ?>
    <div class="sidebar-chat-box" style="margin-top: auto; background: #111; border-radius: 12px; border: 1px solid #222; overflow: hidden; display: flex; flex-direction: column; height: 350px;">
        <div class="chat-tabs" style="display: flex; background: #1a1a1a; cursor: pointer; border-bottom: 1px solid #222;">
            <div id="tab-global" class="chat-tab active" style="flex: 1; padding: 10px; text-align: center; font-size: 0.7rem; font-weight: 700; color: #fff;" onclick="switchChannel('GLOBAL')">Toàn công ty</div>
            <div id="tab-internal" class="chat-tab" style="flex: 1; padding: 10px; text-align: center; font-size: 0.7rem; font-weight: 700; color: #777;" onclick="handleInternalClick()">
                <span id="dept-label"><?php echo $chatDeptName; ?></span>
            </div>
        </div>

        <?php if (in_array('ADMIN', $permissions)): ?>
            <!-- Lựa chọn phòng ban cho Admin -->
            <div id="admin-channel-selector" style="display: none; padding: 5px; background: #222; border-bottom: 1px solid #333;">
                <select id="target-dept" style="width: 100%; background: #000; color: #fff; border: 1px solid #444; font-size: 0.7rem; padding: 2px;" onchange="switchChannel(this.value)">
                    <option value="ADMIN">Kênh Admin</option>
                    <option value="KHO">Kho hàng</option>
                    <option value="NHAP_HANG">Nhập hàng</option>
                    <option value="THU_NGAN">Thu ngân</option>
                    <option value="KHACH_HANG">Khách hàng</option>
                    <option value="BAO_CAO">Báo cáo</option>
                </select>
            </div>
        <?php endif; ?>

        <div id="chat-messages" style="flex: 1; overflow-y: auto; padding: 10px; font-size: 0.8rem; color: #ccc; display: flex; flex-direction: column; gap: 8px;">
            <!-- Tin nhắn sẽ được load ở đây -->
        </div>

        <div class="chat-input-area" style="padding: 10px; border-top: 1px solid #222; display: flex; gap: 5px;">
            <input type="text" id="chat-input" placeholder="Nhập tin..." style="flex: 1; background: #000; border: 1px solid #333; color: #fff; padding: 5px 10px; border-radius: 4px; font-size: 0.8rem; outline: none;" onkeypress="if(event.key === 'Enter') sendMessage()">
            <button id="chat-send" style="background: #000; border: 1px solid #333; color: #fff; width: 30px; height: 30px; border-radius: 4px; cursor: pointer;" onclick="sendMessage()">
                <i class="fas fa-paper-plane" style="font-size: 0.7rem;"></i>
            </button>
        </div>
    </div>
</aside>

<style>
.sidebar-chat-box { font-family: 'Inter', sans-serif; }
.chat-tab:hover { background: #222; }
.chat-tab.active { background: #000; color: #fff !important; }
.msg-item { margin-bottom: 5px; line-height: 1.4; border-bottom: 1px solid #1a1a1a; padding-bottom: 5px; word-break: break-word; }
.msg-user { font-weight: bold; color: #fff; display: block; font-size: 0.7rem; }
.msg-role { color: #555; font-size: 0.6rem; margin-left: 5px; font-weight: normal; }
.msg-time { font-size: 0.6rem; color: #444; margin-left: 5px; }
.msg-text { display: block; margin-top: 2px; }
</style>

<script>
let currentChannel = 'GLOBAL';
const userRole = "<?php echo in_array('ADMIN', $permissions) ? 'ADMIN' : 'USER'; ?>";
const myDeptChannel = "<?php echo $myChannel; ?>";

function handleInternalClick() {
    if (userRole === 'ADMIN') {
        document.getElementById('admin-channel-selector').style.display = 'block';
        switchChannel(document.getElementById('target-dept').value);
    } else {
        switchChannel(myDeptChannel);
    }
}

function switchChannel(channel) {
    currentChannel = channel;
    document.querySelectorAll('.chat-tab').forEach(t => t.classList.remove('active'));
    if (channel === 'GLOBAL') {
        document.getElementById('tab-global').classList.add('active');
        if (document.getElementById('admin-channel-selector')) document.getElementById('admin-channel-selector').style.display = 'none';
    } else {
        document.getElementById('tab-internal').classList.add('active');
    }
    loadMessages();
}

async function loadMessages() {
    try {
        const res = await fetch('index.php?action=get_chat_messages&channel=' + currentChannel);
        const data = await res.json();
        const container = document.getElementById('chat-messages');
        
        // Chỉ cập nhật nếu có nội dung mới hoặc chưa có gì
        let newHtml = '';
        data.forEach(msg => {
            newHtml += `<div class="msg-item">
                            <span class="msg-user">${msg.sender_name} <span class="msg-role">[${msg.role_label}]</span> <span class="msg-time">${msg.created_at}</span></span>
                            <span class="msg-text">${msg.message}</span>
                        </div>`;
        });
        
        if (container.innerHTML !== newHtml) {
            container.innerHTML = newHtml;
            container.scrollTop = container.scrollHeight;
        }
    } catch (e) {
        console.error("Lỗi tải chat:", e);
    }
}

async function sendMessage() {
    const input = document.getElementById('chat-input');
    const msg = input.value.trim();
    if (!msg) return;

    try {
        const res = await fetch('index.php?action=send_chat_message', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `message=${encodeURIComponent(msg)}&channel=${currentChannel}`
        });
        const result = await res.json();
        if (result.status === 'success') {
            input.value = '';
            loadMessages();
        } else {
            console.error("Lỗi gửi tin:", result.message);
        }
    } catch (e) {
        console.error("Không thể gửi tin nhắn:", e);
    }
}

// Khởi tạo
setInterval(loadMessages, 3000);
loadMessages();
</script>
