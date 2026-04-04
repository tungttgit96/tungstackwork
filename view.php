<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/helpers.php';

startSession();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$website = $id > 0 ? getWebsite($id) : null;

if (!$website) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($website['category']) ?> — Website Viewer</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="viewer-page">
    <!-- Viewer Toolbar -->
    <div class="viewer-toolbar">
        <div class="toolbar-left">
            <a href="index.php" class="btn btn-ghost btn-sm">← Quay lại</a>
            <div class="toolbar-info">
                <span class="category-badge" style="background:<?= generateCategoryBadge($website['category']) ?>">
                    <?= e($website['category']) ?>
                </span>
                <span class="toolbar-url"><?= e($website['demo_url']) ?></span>
            </div>
        </div>
        <div class="toolbar-right">
            <?php if (!empty($website['password'])): ?>
                <span class="toolbar-password" title="Mật khẩu demo">🔑 <code><?= e($website['password']) ?></code></span>
            <?php endif; ?>
            <?php if (!empty($website['official_url'])): ?>
                <a href="<?= e((strpos($website['official_url'], 'http') === 0) ? $website['official_url'] : 'https://' . $website['official_url']) ?>" target="_blank" class="btn btn-outline btn-sm">
                    🌍 Web Chính Thức
                </a>
            <?php endif; ?>
            <a href="<?= e($website['demo_url']) ?>" target="_blank" class="btn btn-primary btn-sm">🔗 Mở Tab Mới</a>
            <div class="device-switcher">
                <button class="device-btn active" data-width="100%" title="Desktop">🖥️</button>
                <button class="device-btn" data-width="768px" title="Tablet">📱</button>
                <button class="device-btn" data-width="375px" title="Mobile">📲</button>
            </div>
        </div>
    </div>

    <!-- Iframe Viewer -->
    <div class="viewer-frame-wrapper" id="frameWrapper">
        <iframe src="<?= e($website['demo_url']) ?>" class="viewer-frame" id="viewerFrame" allowfullscreen></iframe>
    </div>

    <script>
    // Device switcher
    document.querySelectorAll('.device-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.device-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const width = this.dataset.width;
            const frame = document.getElementById('viewerFrame');
            const wrapper = document.getElementById('frameWrapper');
            if (width === '100%') {
                frame.style.maxWidth = '100%';
                wrapper.classList.remove('device-mode');
            } else {
                frame.style.maxWidth = width;
                wrapper.classList.add('device-mode');
            }
        });
    });
    </script>
</body>
</html>
