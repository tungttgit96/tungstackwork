<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

requireLogin();

$flash = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldPassword = $_POST['old_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    if ($newPassword !== $confirmPassword) {
        $flash = ['type' => 'error', 'message' => 'Mật khẩu mới không khớp!'];
    } elseif (strlen($newPassword) < 6) {
        $flash = ['type' => 'error', 'message' => 'Mật khẩu mới quá ngắn (tối thiểu 6 ký tự).'];
    } else {
        if (changePassword($oldPassword, $newPassword)) {
            $flash = ['type' => 'success', 'message' => 'Đổi mật khẩu thành công! Bạn có thể sử dụng mật khẩu này cho lần đăng nhập sau.'];
        } else {
            $flash = ['type' => 'error', 'message' => 'Mật khẩu cũ không chính xác.'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cài Đặt — Tuka Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon_io/favicon-32x32.png">
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
                        <h1>Tài Khoản & Cài Đặt</h1>
                        <p>Quản lý an ninh mật khẩu truy cập Admin.</p>
                    </div>
                </div>

                <?php if ($flash): ?>
                    <div class="alert alert-<?php echo e($flash['type']); ?>"><?php echo e($flash['message']); ?></div>
                <?php endif; ?>

                <div class="crm-form-card">
                    <h2 class="form-title">Đổi Mật Khẩu Admin</h2>
                    <p style="margin-bottom:20px; color:#888;">Hãy sử dụng mật khẩu mạnh để bảo vệ dữ liệu.</p>
                    <form method="POST" class="edit-form">
                        <div class="form-group">
                            <label for="old_password">Mật khẩu cũ <span class="required">*</span></label>
                            <input type="password" class="form-input" id="old_password" name="old_password" required>
                        </div>
                        <div class="form-group">
                            <label for="new_password">Mật khẩu mới <span class="required">*</span></label>
                            <input type="password" class="form-input" id="new_password" name="new_password" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Nhập lại mật khẩu mới <span class="required">*</span></label>
                            <input type="password" class="form-input" id="confirm_password" name="confirm_password" required>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Lưu Thay Đổi</button>
                            <a href="index.php" class="btn btn-ghost">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
