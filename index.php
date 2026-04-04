<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/helpers.php';

startSession();
recordPageView();

$websites = getAllWebsites();
$total = count($websites);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>tungstack.work - Demo Website Collection</title>
    <meta name="description" content="tungstack.work - Demo Website Collection. Contact: Trần Thanh Tùng - 0975872497 - admin@tungstack.work">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="apple-touch-icon" sizes="180x180" href="favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon_io/favicon-16x16.png">
    <link rel="manifest" href="favicon_io/site.webmanifest">
</head>
<body>
    <!-- ===== NAVBAR ===== -->
    <nav class="navbar">
        <div class="container navbar-inner">
            <a href="index.php" class="navbar-logo">
                <img src="pictures/TSWlogo-web.svg" alt="Tungstack" class="navbar-logo-img">
            </a>
            <div class="navbar-links">
                <a href="#demos" class="nav-link">Demos</a>
                <a href="https://www.facebook.com/tungtt.3996" target="_blank" rel="noopener" class="nav-link">Contact</a>
                <a href="admin/login.php" class="btn btn-outline btn-sm">⚙️ Admin</a>
            </div>
        </div>
    </nav>

    <!-- ===== HERO ===== -->
    <header class="hero">
        <div class="hero-glow"></div>
        <div class="container">
            <div class="hero-content">
                <img src="pictures/TSW-web.svg" alt="Tungstack Logo" class="hero-logo">
                <h1 class="hero-title">Demo Website <span class="gradient-text">Collection</span></h1>
                <p class="hero-subtitle"><?php echo $total; ?> professional demo websites for every industry</p>
                <div class="hero-contact">
                    <a href="https://www.facebook.com/tungtt.3996" target="_blank" rel="noopener" class="contact-chip">
                        <span class="chip-icon">👤</span> Trần Thanh Tùng
                    </a>
                    <a href="https://zalo.me/84975872497" target="_blank" rel="noopener" class="contact-chip">
                        <span class="chip-icon">📱</span> +84975872497
                    </a>
                    <a href="mailto:admin@tungstack.work" class="contact-chip">
                        <span class="chip-icon">✉️</span> admin@tungstack.work
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- ===== DEMOS GRID ===== -->
    <main class="main-content" id="demos">
        <div class="container">
            <div class="section-header">
                <h2>All Demos</h2>
                <span class="section-count"><?php echo $total; ?> websites</span>
            </div>
            <?php if (empty($websites)): ?>
                <div class="empty-state">
                    <div class="empty-icon">📭</div>
                    <h2>Chưa có website nào</h2>
                    <p>Hãy <a href="admin/login.php">đăng nhập Admin</a> để thêm website</p>
                </div>
            <?php else: ?>
                <div class="website-grid">
                    <?php foreach ($websites as $i => $w):
                        $domain = parse_url($w['demo_url'], PHP_URL_HOST);
                        if (!$domain) $domain = 'demo';
                        $hasPassword = !empty($w['password']);
                        $hasOfficial = !empty($w['official_url']);
                        $officialHref = '';
                        if ($hasOfficial) {
                            $officialHref = (strpos($w['official_url'], 'http') === 0) ? $w['official_url'] : 'https://' . $w['official_url'];
                        }
                    ?>
                    <div class="website-card">
                        <div class="card-header">
                            <div class="mac-dots">
                                <span class="mac-dot mac-dot-red"></span>
                                <span class="mac-dot mac-dot-yellow"></span>
                                <span class="mac-dot mac-dot-green"></span>
                            </div>
                            <span class="category-badge" style="background: <?php echo generateCategoryBadge($w['category']); ?>">
                                <?php echo e($w['category']); ?>
                            </span>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title"><?php echo e($w['category']); ?></h3>
                            <a href="<?php echo e($w['demo_url']); ?>" target="_blank" rel="noopener" class="demo-link">
                                <span class="demo-link-icon">🔗</span>
                                <span class="demo-link-text"><?php echo e($domain); ?></span>
                                <span class="demo-link-arrow">↗</span>
                            </a>
                            <?php if ($hasPassword): ?>
                            <div class="password-box">
                                <span class="password-label">🔑 Mật khẩu:</span>
                                <code class="password-value" id="pw-<?php echo $w['id']; ?>"><?php echo e($w['password']); ?></code>
                                <button class="copy-btn" onclick="copyPassword(<?php echo $w['id']; ?>)" title="Copy mật khẩu">📋</button>
                            </div>
                            <?php endif; ?>
                            <?php if ($hasOfficial): ?>
                            <a href="<?php echo e($officialHref); ?>" target="_blank" rel="noopener" class="official-link">
                                🌐 Web chính thức: <?php echo e($w['official_url']); ?>
                            </a>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer">
                            <a href="<?php echo e($w['demo_url']); ?>" target="_blank" rel="noopener" class="btn btn-primary btn-sm btn-block">
                                Xem Demo
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- ===== FOOTER ===== -->
    <footer class="footer">
        <div class="container footer-inner">
            <div class="footer-brand">
                <img src="pictures/TSWlogo-web.svg" alt="Tungstack" class="footer-logo">
                <span>Tungstack.work</span>
            </div>
            <p class="footer-copy">© <?php echo date('Y'); ?> Tungstack.work. All rights reserved.</p>
        </div>
    </footer>

    <script>
    function copyPassword(id) {
        var el = document.getElementById('pw-' + id);
        var text = el.textContent;
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(function() {
                var btn = el.nextElementSibling;
                btn.textContent = '✅';
                setTimeout(function() { btn.textContent = '📋'; }, 1500);
            });
        } else {
            var range = document.createRange();
            range.selectNode(el);
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(range);
            document.execCommand('copy');
            window.getSelection().removeAllRanges();
        }
    }

    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            var target = document.querySelector(this.getAttribute('href'));
            if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });
    </script>
</body>
</html>
