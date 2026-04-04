<?php
require_once __DIR__ . '/db.php';

function startSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function isLoggedIn() {
    startSession();
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function login($password) {
    startSession();
    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE username = ?");
    $stmt->execute(array('admin'));
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['admin_logged_in'] = true;
        return true;
    }
    return false;
}

function changePassword($oldPassword, $newPassword) {
    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE username = ?");
    $stmt->execute(array('admin'));
    $user = $stmt->fetch();
    
    if ($user && password_verify($oldPassword, $user['password_hash'])) {
        $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
        $updateStmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE username = ?");
        $updateStmt->execute(array($newHash, 'admin'));
        return true;
    }
    return false;
}

function logout() {
    startSession();
    session_destroy();
}
