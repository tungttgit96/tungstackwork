<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

requireLogin();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$isEdit = $id > 0;
$website = $isEdit ? getWebsite($id) : null;

if ($isEdit && !$website) {
    flash('error', 'Website không tồn tại!');
    header('Location: index.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = trim($_POST['category'] ?? '');
    $demoUrl = trim($_POST['demo_url'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $officialUrl = trim($_POST['official_url'] ?? '');

    if ($category === '')
        $errors[] = 'Thể loại không được để trống';
    if ($demoUrl === '')
        $errors[] = 'Demo URL không được để trống';

    if (empty($errors)) {
        if ($isEdit) {
            updateWebsite($id, $category, $demoUrl, $password, $officialUrl);
            flash('success', 'Cập nhật website thành công!');
        } else {
            createWebsite($category, $demoUrl, $password, $officialUrl);
            flash('success', 'Thêm website mới thành công!');
        }
        header('Location: index.php');
        exit;
    }
} else {
    $category = $website['category'] ?? '';
    $demoUrl = $website['demo_url'] ?? '';
    $password = $website['password'] ?? '';
    $officialUrl = $website['official_url'] ?? '';
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isEdit ? 'Sửa' : 'Thêm' ?> Website — Admin</title>
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
                    <a href="index.php" class="btn btn-ghost">← Quay Lại</a>
                    <a href="#" class="user-profile">
                        <div class="user-avatar">AD</div>
                    </a>
                </div>
            </header>

            <!-- Scrollable Content -->
            <div class="dashboard-content">
                <div class="dashboard-header-block">
                    <div>
                        <h1><?= $isEdit ? '✏️ Sửa Website' : '➕ Thêm Website Mới' ?></h1>
                        <p>Fill out the details below to complete the action.</p>
                    </div>
                </div>

                <div class="crm-form-card">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-error">
                            <?php foreach ($errors as $err): ?>
                                <div><?= e($err) ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="edit-form">
                        <div class="form-group">
                            <label for="category">Thể Loại <span class="required">*</span></label>
                            <input type="text" class="form-input" id="category" name="category"
                                value="<?= e($category) ?>" placeholder="VD: NỘI THẤT, MỸ PHẨM..." required>
                        </div>
                        <div class="form-group">
                            <label for="demo_url">Demo URL <span class="required">*</span></label>
                            <input type="url" class="form-input" id="demo_url" name="demo_url"
                                value="<?= e($demoUrl) ?>" placeholder="https://mau01.hydrosite.site/" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="password">Mật Khẩu Demo</label>
                                <input type="text" class="form-input" id="password" name="password"
                                    value="<?= e($password) ?>" placeholder="Mật khẩu xem demo (nếu có)">
                            </div>
                            <div class="form-group">
                                <label for="official_url">Web Chính Thức</label>
                                <input type="text" class="form-input" id="official_url" name="official_url"
                                    value="<?= e($officialUrl) ?>" placeholder="https://example.com">
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit"
                                class="btn btn-primary"><?= $isEdit ? 'Cập Nhật' : 'Thêm Mới' ?></button>
                            <a href="index.php" class="btn btn-ghost">Huỷ</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>

</html>