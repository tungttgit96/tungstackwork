<?php
$current_page = basename($_SERVER['PHP_SELF']);

$navItems = [
    ['href' => 'index.php', 'label' => 'Overview', 'icon' => '📊', 'active' => ['index.php', 'edit.php']],
    ['href' => 'companies.php', 'label' => 'My Info', 'icon' => '🏢', 'active' => ['companies.php']],
    ['href' => 'calendar.php', 'label' => 'Traffic Calendar', 'icon' => '📅', 'active' => ['calendar.php']],
    ['href' => 'settings.php', 'label' => 'Cài Đặt Mật khẩu', 'icon' => '⚙️', 'active' => ['settings.php']],
];
?>
<aside class="dashboard-sidebar">
    <a href="../index.php" class="sidebar-logo">
        <img src="../pictures/TSWlogo-web.svg" alt="Tungstack">
        <span>Dashboard</span>
    </a>
    
    <nav class="sidebar-nav">
        <?php foreach ($navItems as $item): ?>
            <a href="<?php echo $item['href']; ?>" class="sidebar-link <?php echo in_array($current_page, $item['active'], true) ? 'active' : ''; ?>">
                <div class="sidebar-link-inner"><span class="icon"><?php echo $item['icon']; ?></span> <?php echo $item['label']; ?></div>
            </a>
        <?php endforeach; ?>
    </nav>

    <div class="sidebar-footer">
        <a href="logout.php" class="sidebar-link"><div class="sidebar-link-inner"><span class="icon">🚪</span> Đăng Xuất</div></a>
    </div>
</aside>
