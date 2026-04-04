<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

requireLogin();

$syncResult = null;
if (isset($_POST['sync_sheets'])) {
    $syncResult = syncFromGoogleSheets();
}

$websites = getAllWebsites();
$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Website — Tuka Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon_io/favicon-16x16.png">
    <link rel="manifest" href="../favicon_io/site.webmanifest">
</head>
<body class="admin-page">
    <div class="dashboard-layout">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../includes/admin_sidebar.php'; ?>

        <!-- Main Content -->
        <main class="dashboard-main">
            <!-- Topbar -->
            <header class="dashboard-topbar">
                <div class="topbar-search">
                    <span class="icon-search">🔍</span>
                    <input type="text" placeholder="Search Something...">
                </div>
                <div class="topbar-right">
                    <form method="POST" style="display:inline">
                        <button type="submit" name="sync_sheets" value="1" class="btn btn-outline">
                            🔄 Sync Google Sheets
                        </button>
                    </form>
                    <a href="edit.php" class="btn btn-primary">+ Add Website</a>
                    <a href="#" class="user-profile">
                        <div class="user-avatar">AD</div>
                    </a>
                </div>
            </header>

            <!-- Scrollable Content -->
            <div class="dashboard-content">
                <div class="dashboard-header-block">
                    <div>
                        <h1>Quản Lý Website</h1>
                        <p>Sort by: <strong>Newest Docs ▼</strong></p>
                    </div>
                </div>

                <?php if ($flash): ?>
                    <div class="alert alert-<?php echo e($flash['type']); ?>"><?php echo e($flash['message']); ?></div>
                <?php endif; ?>

                <?php if ($syncResult !== null): ?>
                    <div class="alert alert-<?php echo $syncResult['type']; ?>">
                        <?php echo e($syncResult['message']); ?>
                    </div>
                <?php endif; ?>

                <div class="crm-grid">
                    <?php 
                    $icons = [
                        ['class' => 'crm-icon-blue', 'emoji' => '📄'],
                        ['class' => 'crm-icon-orange', 'emoji' => '🖼️'],
                        ['class' => 'crm-icon-green', 'emoji' => '🔊'],
                        ['class' => 'crm-icon-purple', 'emoji' => '▶️']
                    ];
                    foreach ($websites as $i => $w): 
                        $iconStyle = $icons[$i % count($icons)];
                        $catHtml = htmlspecialchars($w['category']);
                        $demoUrl = htmlspecialchars($w['demo_url']);
                        $offUrl = htmlspecialchars($w['official_url'] ?? '');
                        $pass = htmlspecialchars($w['password'] ?? '');
                    ?>
                    <div class="crm-card">
                        <div class="crm-card-header">
                            <div class="crm-icon-box <?php echo $iconStyle['class']; ?>">
                                <?php echo $iconStyle['emoji']; ?>
                            </div>
                            <div class="crm-card-info">
                                <h3><?php echo $demoUrl; ?></h3>
                                <p><?php echo $catHtml; ?> • ID: <?php echo $w['id']; ?></p>
                                <?php if($offUrl): ?>
                                    <a href="<?php echo (strpos($offUrl, 'http')===0) ? $offUrl : 'https://'.$offUrl; ?>" target="_blank"><?php echo $offUrl; ?></a>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if ($pass): ?>
                        <div class="crm-password">🔑 <?php echo $pass; ?></div>
                        <?php endif; ?>

                        <div class="crm-actions">
                            <a href="<?php echo $demoUrl; ?>" target="_blank" class="btn btn-outline btn-sm">Xem Demo</a>
                            <a href="edit.php?id=<?php echo $w['id']; ?>" class="btn btn-outline btn-sm">✏ Sửa</a>
                            <a href="delete.php?id=<?php echo $w['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xoá?')">🗑 Xoá</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
