<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

requireLogin();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id > 0) {
    deleteWebsite($id);
    flash('success', 'Đã xoá website thành công!');
}
header('Location: index.php');
exit;
