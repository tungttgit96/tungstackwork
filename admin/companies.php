<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

requireLogin();

$flash = null;

// Fake simple saving mechanism to Settings table
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $companyName = $_POST['company_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    
    $pdo = getDB();
    $stmt = $pdo->prepare("INSERT INTO settings (settings_key, settings_value) VALUES (?, ?) ON CONFLICT(settings_key) DO UPDATE SET settings_value = ?");
    if(DB_TYPE == 'mysql') {
        $stmt = $pdo->prepare("INSERT INTO settings (settings_key, settings_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE settings_value = ?");
    }
    
    // Lưu các biến
    $stmt->execute(['company_name', $companyName, $companyName]);
    $stmt->execute(['company_phone', $phone, $phone]);
    $stmt->execute(['company_email', $email, $email]);
    
    $flash = ['type' => 'success', 'message' => 'Lưu hồ sơ thành công!'];
}

// Load current data
$pdo = getDB();
$stmt = $pdo->query("SELECT settings_key, settings_value FROM settings WHERE settings_key LIKE 'company_%'");
$settings = [];
foreach($stmt->fetchAll() as $row) {
    $settings[$row['settings_key']] = $row['settings_value'];
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ Sơ Của Tôi — Tuka Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
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
                        <h1>Hồ Sơ Của Tôi</h1>
                        <p>Thông tin liên hệ của Agency.</p>
                    </div>
                </div>

                <?php if ($flash): ?>
                    <div class="alert alert-<?php echo e($flash['type']); ?>"><?php echo e($flash['message']); ?></div>
                <?php endif; ?>

                <div class="crm-form-card">
                    <h2 class="form-title">Cập Nhật Thông Tin</h2>
                    <form method="POST" class="edit-form">
                        <div class="form-group">
                            <label for="company_name">Tên Công Ty / Agency hiển thị</label>
                            <input type="text" class="form-input" id="company_name" name="company_name" value="<?php echo e($settings['company_name'] ?? 'Tungstack.work'); ?>">
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="phone">Số lượng điện thoại Zalo</label>
                                <input type="text" class="form-input" id="phone" name="phone" value="<?php echo e($settings['company_phone'] ?? '+84975872497'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="email">Địa chỉ Email</label>
                                <input type="email" class="form-input" id="email" name="email" value="<?php echo e($settings['company_email'] ?? 'admin@tungstack.work'); ?>">
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Cập Nhật Hồ Sơ</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
