<?php

function e($str) {
    return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function flash($type, string $message) {
    startSession();
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array {
    startSession();
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

function generateCategoryBadge($category) {
    $colors = [
        '#6366f1', '#8b5cf6', '#ec4899', '#f43f5e', '#f97316',
        '#eab308', '#22c55e', '#14b8a6', '#06b6d4', '#3b82f6',
    ];
    $hash = crc32($category);
    $color = $colors[abs($hash) % count($colors)];
    return $color;
}

function getDemoThumbnail($url) {
    // Use a simple pattern-based icon
    $domain = parse_url($url, PHP_URL_HOST) ?: 'site';
    return 'https://www.google.com/s2/favicons?domain=' . urlencode($domain) . '&sz=128';
}
