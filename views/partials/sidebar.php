<?php
// views/partials/sidebar.php
if (!function_exists('hasPerm')) {
    function hasPerm($p, $perms) {
        // Chuẩn hóa tất cả về chữ HOA để so sánh chính xác
        $perms = array_map('strtoupper', $perms ?? []); 
        $p = strtoupper($p);
        
        return in_array('ADMIN', $perms) || in_array($p, $perms);
    }
}
?>

<aside class="sidebar">
    <div class="brand-box">
        <i class="fas fa-cube brand-icon"></i>
        <div class="brand-text">
            <h2>TAPHOA</h2>
            <p>MANAGER PRO</p>
        </div>
    </div>

    <div class="user-profile-card">
        <div class="avatar-wrapper">
            <img src="https://ui-avatars.com/api/?name=<?php echo $username ?? 'User'; ?>&background=random&color=fff&size=128" alt="User Avatar">
            <span class="status-dot pulse"></span>
        </div>
        <div class="user-meta">
            <span class="user-name"><?php echo htmlspecialchars($username ?? 'Guest'); ?></span>
            <span class="user-role">
                <i class="fas fa-shield-alt"></i> 
                <?php 
                    $perms = $permissions ?? [];
                    echo in_array('ADMIN', $perms) ? 'Administrator' : 'Nhân viên'; 
                ?>
            </span>
            <span class="status-text">Đang hoạt động</span>
        </div>
    </div>

    <nav class="sidebar-menu">
        <p class="menu-label">MAIN MENU</p>
        
        <a href="index.php?action=dashboard" class="menu-item <?php echo ($_GET['action'] == 'dashboard') ? 'active' : ''; ?>">
            <i class="fas fa-th-large"></i> <span>Tổng quan</span>
        </a>

        <?php if (hasPerm('UC1', $permissions)): ?>
        <a href="index.php?action=products" class="menu-item <?php echo ($_GET['action'] == 'products') ? 'active' : ''; ?>">
            <i class="fas fa-box"></i> <span>Kho hàng</span>
        </a>
        <?php endif; ?>
        <?php if (hasPerm('UC1', $permissions)): ?>
<a href="index.php?action=category_list" class="menu-item <?php echo ($_GET['action'] == 'category_list') ? 'active' : ''; ?>">
    <i class="fas fa-tags"></i> <span>Danh mục</span>
</a>
<?php endif; ?>
        

        <?php if (hasPerm('UC2', $permissions)): ?>
        <a href="index.php?action=imports" class="menu-item <?php echo ($_GET['action'] == 'imports') ? 'active' : ''; ?>">
            <i class="fas fa-truck"></i> <span>Nhập hàng</span>
        </a>
        <?php endif; ?>

            <?php if (hasPerm('UC3', $permissions)): ?>
    <a href="index.php?action=cashier_pos" class="menu-item <?php echo ($_GET['action'] == 'cashier_pos') ? 'active' : ''; ?>">
        <i class="fas fa-cash-register"></i> <span>Thu ngân</span>
    </a>
    <?php endif; ?>

        <?php if (hasPerm('UC4', $permissions)): ?>
        <a href="index.php?action=customers" class="menu-item <?php echo ($_GET['action'] == 'customers') ? 'active' : ''; ?>">
            <i class="fas fa-users"></i> <span>Khách hàng</span>
        </a>
        <?php endif; ?>

        <a href="index.php?action=logout" class="menu-item logout">
            <i class="fas fa-power-off"></i> <span>Đăng xuất</span>
        </a>
        
    </nav>
</aside>