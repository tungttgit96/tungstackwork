<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="dashboard-sidebar">
    <a href="../index.php" class="sidebar-logo">
        <img src="../pictures/TSWlogo-web.svg" alt="Tungstack">
        <span>Square</span>
    </a>
    
    <nav class="sidebar-nav">
        <a href="calendar.php" class="sidebar-link <?php echo ($current_page == 'calendar.php') ? 'active' : ''; ?>"><div class="sidebar-link-inner"><span class="icon">📊</span> Overview</div></a>
        <a href="companies.php" class="sidebar-link <?php echo ($current_page == 'companies.php') ? 'active' : ''; ?>"><div class="sidebar-link-inner"><span class="icon">🏢</span> My Info</div></a>
        <a href="deals.php" class="sidebar-link <?php echo ($current_page == 'deals.php') ? 'active' : ''; ?>"><div class="sidebar-link-inner"><span class="icon">💰</span> Deals</div></a>
        
        <div class="sidebar-label">Main</div>
        
        <a href="index.php" class="sidebar-link <?php echo ($current_page == 'index.php' || $current_page == 'edit.php') ? 'active' : ''; ?>"><div class="sidebar-link-inner"><span class="icon">📄</span> Quản Lý Website</div></a>
        <a href="calendar.php" class="sidebar-link <?php echo ($current_page == 'calendar.php') ? 'active' : ''; ?>"><div class="sidebar-link-inner"><span class="icon">📅</span> Traffic Calendar</div></a>
        <a href="settings.php" class="sidebar-link <?php echo ($current_page == 'settings.php') ? 'active' : ''; ?>"><div class="sidebar-link-inner"><span class="icon">⚙️</span> Cài Đặt Mật khẩu</div></a>
    </nav>

    <div class="sidebar-footer">
        <a href="logout.php" class="sidebar-link"><div class="sidebar-link-inner"><span class="icon">🚪</span> Đăng Xuất</div></a>
    </div>
</aside>
