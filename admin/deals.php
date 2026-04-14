<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

requireLogin();
flash('success', 'Tính năng Deals đã được gỡ khỏi Admin menu.');
header('Location: index.php');
exit;
