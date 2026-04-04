<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

requireLogin();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deals — Tuka Admin</title>
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

            <div class="dashboard-content" style="display:flex; align-items:center; justify-content:center; height: 70vh;">
                <div class="crm-form-card" style="text-align:center; padding: 60px 40px;">
                    <span style="font-size:48px; display:block; margin-bottom: 20px;">🚀</span>
                    <h2 class="form-title" style="font-size:24px;">Tính năng Deals đang được phát triển</h2>
                    <p style="color:#888; margin-bottom: 30px;">
                        Tính năng Quản lý Đơn Hàng (Deals) là một bản cập nhật lớn sẽ sớm ra mắt!<br>
                        Vui lòng quay lại sau nhé.
                    </p>
                    <a href="index.php" class="btn btn-primary">Trở về Trang Chủ</a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
