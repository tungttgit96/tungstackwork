<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

requireLogin();

$month = isset($_GET['m']) ? (int)$_GET['m'] : (int)date('m');
$year = isset($_GET['y']) ? (int)$_GET['y'] : (int)date('Y');

// Ngăn chèn số tào lao
$time = mktime(0, 0, 0, $month, 1, $year);
$month = (int)date('m', $time);
$year = (int)date('Y', $time);

$prevMonth = $month - 1;
$prevYear = $year;
$nextMonth = $month + 1;
$nextYear = $year;

$viewsData = getDailyViews($year, $month);

$daysInMonth = (int)date('t', $time);
$firstDayOfWeek = (int)date('w', $time); // 0 (Sun) to 6 (Sat)
$firstDayIndex = ($firstDayOfWeek == 0) ? 6 : $firstDayOfWeek - 1; // Mon=0, Tue=1, ..., Sun=6
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traffic Calendar — Tuka Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon_io/favicon-32x32.png">
    
    <style>
    .calendar-wrapper { margin-top: 20px; }
    .calendar-nav { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .calendar-nav h2 { margin: 0; font-size: 20px; font-weight: 600; color: #fff; }
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 10px;
    }
    .calendar-header-cell {
        text-align: center; font-weight: 600; color: #888; padding: 10px 0; text-transform: uppercase; font-size: 13px;
    }
    .calendar-cell {
        background: #1e1e24; border-radius: 8px; padding: 15px; min-height: 100px;
        border: 1px solid #2a2a32; display: flex; flex-direction: column; justify-content: space-between;
        transition: border 0.2s;
    }
    .calendar-cell:hover { border-color: var(--accent); }
    .calendar-empty { background: transparent; border: none; }
    .calendar-empty:hover { border-color: transparent; }
    .cal-date { font-size: 14px; color: #fff; opacity: 0.8; font-weight: 600; }
    .cal-traffic { font-size: 26px; font-weight: 800; color: var(--accent); margin-top: 10px; }
    .cal-traffic.zero { opacity: 0.3; color: #888;}
    </style>
</head>
<body class="admin-page">
    <div class="dashboard-layout">
        <?php include __DIR__ . '/../includes/admin_sidebar.php'; ?>
        
        <main class="dashboard-main">
            <header class="dashboard-topbar">
                <div class="topbar-search"><span class="icon-search">🔍</span><input type="text" placeholder="Search..."></div>
                <div class="topbar-right">
                    <a href="#" class="user-profile"><div class="user-avatar">AD</div></a>
                </div>
            </header>

            <div class="dashboard-content">
                <div class="dashboard-header-block">
                    <div>
                        <h1>Traffic Calendar</h1>
                        <p>Thống kê số lượng khách ghé thăm trang Landing Page theo từng ngày.</p>
                    </div>
                </div>

                <div class="crm-form-card calendar-wrapper" style="max-width: 100%;">
                    <div class="calendar-nav">
                        <a href="?m=<?php echo $prevMonth; ?>&y=<?php echo $prevYear; ?>" class="btn btn-outline btn-sm">◄ Tháng Trước</a>
                        <h2>Tháng <?php echo $month; ?>/<?php echo $year; ?></h2>
                        <a href="?m=<?php echo $nextMonth; ?>&y=<?php echo $nextYear; ?>" class="btn btn-outline btn-sm">Tháng Sau ►</a>
                    </div>
                    
                    <div class="calendar-grid">
                        <div class="calendar-header-cell">Thứ 2</div>
                        <div class="calendar-header-cell">Thứ 3</div>
                        <div class="calendar-header-cell">Thứ 4</div>
                        <div class="calendar-header-cell">Thứ 5</div>
                        <div class="calendar-header-cell">Thứ 6</div>
                        <div class="calendar-header-cell">Thứ 7</div>
                        <div class="calendar-header-cell">Chủ Nhật</div>
                        
                        <?php
                        // Căn chỉnh ngày trống đầu tháng
                        for ($i = 0; $i < $firstDayIndex; $i++) {
                            echo '<div class="calendar-cell calendar-empty"></div>';
                        }
                        
                        // In ngày
                        for ($d = 1; $d <= $daysInMonth; $d++) {
                            $dateStr = sprintf("%04d-%02d-%02d", $year, $month, $d);
                            $views = $viewsData[$dateStr] ?? 0;
                            $viewClass = $views > 0 ? '' : 'zero';
                            $viewText = $views > 0 ? number_format($views) : '0';
                            
                            echo '<div class="calendar-cell">';
                            echo '  <div class="cal-date">' . $d . '</div>';
                            echo '  <div class="cal-traffic '. $viewClass .'">' . $viewText . '</div>';
                            echo '  <div style="font-size:11px; color:#888;">lượt xem</div>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
