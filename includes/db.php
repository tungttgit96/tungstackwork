<?php
// ========== DATABASE CONFIG ==========
// Mặc định dùng SQLite — upload lên hosting là chạy
// Nếu muốn dùng MySQL: đổi DB_TYPE = 'mysql'
define('DB_TYPE', 'sqlite'); // 'sqlite' hoặc 'mysql'

// MySQL config (chỉ cần khi DB_TYPE = 'mysql')
define('DB_HOST', 'localhost');
define('DB_NAME', 'website_viewer');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        if (DB_TYPE === 'mysql') {
            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', DB_HOST, DB_NAME, DB_CHARSET);
            $pdo = new PDO($dsn, DB_USER, DB_PASS, array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ));
        } else {
            $dbPath = __DIR__ . '/../data/websites.db';
            $pdo = new PDO('sqlite:' . $dbPath, null, null, array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ));
            $pdo->exec('PRAGMA journal_mode=WAL');
        }
        initDB($pdo);
    }
    return $pdo;
}

function initDB($pdo) {
    if (DB_TYPE === 'mysql') {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `websites` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `category` VARCHAR(255) NOT NULL,
                `demo_url` VARCHAR(500) NOT NULL,
                `password` VARCHAR(100) DEFAULT '',
                `official_url` VARCHAR(500) DEFAULT '',
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `users` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `username` VARCHAR(50) NOT NULL UNIQUE,
                `password_hash` VARCHAR(255) NOT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `page_views` (
                `view_date` DATE PRIMARY KEY,
                `views` INT DEFAULT 0
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `settings` (
                `settings_key` VARCHAR(100) PRIMARY KEY,
                `settings_value` TEXT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
    } else {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS websites (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                category TEXT NOT NULL,
                demo_url TEXT NOT NULL,
                password TEXT DEFAULT '',
                official_url TEXT DEFAULT '',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT NOT NULL UNIQUE,
                password_hash TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS page_views (
                view_date DATE PRIMARY KEY,
                views INTEGER DEFAULT 0
            )
        ");
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS settings (
                settings_key TEXT PRIMARY KEY,
                settings_value TEXT
            )
        ");
    }

    $userCount = (int) $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    if ($userCount === 0) {
        $hash = password_hash('admin123', PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
        $stmt->execute(array('admin', $hash));
    }

    $count = (int) $pdo->query("SELECT COUNT(*) FROM websites")->fetchColumn();
    if ($count === 0) {
        seedData($pdo);
    }

    migrateDefaultSettings($pdo);
}

function migrateDefaultSettings($pdo) {
    // Keep legacy installs in sync with the new default contact email.
    $stmt = $pdo->prepare("SELECT settings_value FROM settings WHERE settings_key = ?");
    $stmt->execute(array('company_email'));
    $currentEmail = $stmt->fetchColumn();

    if ($currentEmail === false || $currentEmail === null || trim((string) $currentEmail) === '' || trim((string) $currentEmail) === 'admin@tungstack.work') {
        upsertSetting($pdo, 'company_email', 'tungtt96@tungstack.work');
    }
}

function upsertSetting($pdo, $key, $value) {
    if (DB_TYPE === 'mysql') {
        $stmt = $pdo->prepare("INSERT INTO settings (settings_key, settings_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE settings_value = VALUES(settings_value)");
        $stmt->execute(array($key, $value));
        return;
    }

    $stmt = $pdo->prepare("INSERT INTO settings (settings_key, settings_value) VALUES (?, ?) ON CONFLICT(settings_key) DO UPDATE SET settings_value = excluded.settings_value");
    $stmt->execute(array($key, $value));
}

function seedData($pdo) {
    $data = array(
        array('XE MÁY', 'https://mau01.hydrosite.site/', '765426', 'https://xemay365.com.vn/'),
        array('KIM TUYẾN', 'https://mau02.hydrosite.site/', '876542', 'https://dodo-glitter.com/'),
        array('ĐÈN TRẦN XUYÊN SÁNG', 'https://mau03.hydrosite.site/', '476654', 'https://dentranxuyensang.com/'),
        array('MỸ PHẨM (NEW)', 'https://mau05.hydrosite.site/', '888542', 'https://scentlabo.vn/'),
        array('NỒI HƠI - MÁY MÓC (NEW)', 'https://mau06.hydrosite.site/', '091098', 'https://tounshingkai.com.vn/vi/'),
        array('MÁY LỌC KHÔNG KHÍ', 'https://mau07.hydrosite.site/', '091098', ''),
        array('NHÀ XE - ĐẶT VÉ XE KHÁCH', 'https://mau08.hydrosite.site/', '091098', 'xekhachhanoiyty.com'),
        array('VẬN CHUYỂN HLE EXPRESS', 'https://mau09.hydrosite.site/', '091098', 'hleexpress.com'),
        array('THAN ĐÁ', 'https://mau10.hydrosite.site/', '091098', 'https://thanhuynhphuong.vn/'),
        array('NỘI THẤT', 'https://mau11.hydrosite.site/', '091098', ''),
        array('NỘI THẤT', 'https://mau12.hydrosite.site/', '091098', 'https://j-design.info/'),
        array('VISA - TOUR DU LỊCH', 'https://mau14.hydrosite.site/', '091098', 'https://saigonfirstsky.com/'),
        array('RESORT (NEW)', 'https://mau15.hydrosite.site/', '091098', 'https://cloudparadiseyty.com/'),
        array('VISA VIỆT NHẬT (NEW)', 'https://mau16.hydrosite.site/', '981009', 'https://favijatravel.com/'),
        array('THIẾT KẾ XÂY DỰNG (NEW)', 'https://mau17.hydrosite.site/', '091098', 'https://thietkexaydungbinhminh.com/'),
        array('NỒI HƠI - MÁY MÓC (NEW)', 'https://mau18.hydrosite.site/', '091098', 'https://petpboiler.com/'),
        array('NỘI THẤT', 'https://mau19.hydrosite.site/', '091098', 'https://bdstan.com/'),
        array('BÁNH TRUNG THU (NEW)', 'https://mau20.hydrosite.site/', '865453', ''),
        array('LOGISTIC - VẬN CHUYỂN HÀN QUỐC (NEW)', 'https://mau21.hydrosite.site/', '846213', 'https://jinakorea.co.kr/'),
        array('LỐP XE - RESTONE (NEW)', 'https://mau22.hydrosite.site/', '654322', 'https://restone-tire.com/'),
        array('KEM HAPPY COOL (NEW)', 'https://mau23.hydrosite.site/', '756856', ''),
        array('THUỶ SẢN - VS SEAFOOD (NEW)', 'https://mau24.hydrosite.site/', '157896', 'https://vietseavn.com/en/'),
        array('TECHNO FARM - NÔNG TRẠI (NEW)', 'https://mau25.hydrosite.site/', '765156', ''),
        array('GAABOR - SHOPEE MALL (NEW)', 'https://mau26.hydrosite.site/', '975156', 'https://gaaborvn.com/'),
        array('SPA MASSAGE', 'https://mau27.hydrosite.site/', '563489', ''),
        array('HELLO HOKKAIDO - KIMONO - CHỤP ẢNH (NEW)', 'https://mau28.hydrosite.site/', '682043', 'https://hellohokkaido.com'),
        array('Siêu Metis (DaLat Milk)', 'https://mau29.hydrosite.site/', '', ''),
        array('DD OFFROAD (NEW)', 'https://mau30.hydrosite.site/', '956431', ''),
        array('RƯỢU ĐỒ MƯỜNG ĐÌNH (LANDING PAGE)', 'https://mau31.hydrosite.site/', '586456', 'https://ruoudomuongdinh.vn/'),
        array('SPA - SKIN TREATMENT BY TRANG (NEW)', 'https://mau32.hydrosite.site/', '846546', ''),
        array('PHỤ TÙNG KHÁNH CHÂU (NEW)', 'https://mau33.hydrosite.site/', '898651', 'https://ptkhanhchau.com/'),
        array('LUX MASSAGE (NEW)', 'https://mau34.hydrosite.site/', '896435', ''),
        array('BAMBI - Ghế Ô tô trẻ em 3D (NEW)', 'https://mau35.hydrosite.site/', '896456', ''),
        array('AVA COFFEE', 'https://mau36.hydrosite.site/', '894563', ''),
        array('GALAXY PROPERTY (NEW)', 'https://mau37.hydrosite.site/', '768964', ''),
        array('SHOPEE', 'https://mau38.hydrosite.site/', '071196', 'shopee'),
        array('Aura Realty (Web BDS)', 'https://web01.hydrosite.vn/', '', ''),
        array('Kim Long Hoa (Web Nội Thất)', 'https://web02.hydrosite.vn/', '', ''),
        array('Phụ tùng xe Đại Thắng Phát', 'https://web03.hydrosite.vn/', '', ''),
    );

    $stmt = $pdo->prepare("INSERT INTO websites (category, demo_url, password, official_url) VALUES (?, ?, ?, ?)");
    foreach ($data as $row) {
        $stmt->execute($row);
    }
}

// CRUD helpers
function getAllWebsites() {
    return getDB()->query("SELECT * FROM websites ORDER BY id ASC")->fetchAll();
}

function getWebsite($id) {
    $stmt = getDB()->prepare("SELECT * FROM websites WHERE id = ?");
    $stmt->execute(array($id));
    return $stmt->fetch();
}

function createWebsite($category, $demoUrl, $password, $officialUrl) {
    $stmt = getDB()->prepare("INSERT INTO websites (category, demo_url, password, official_url) VALUES (?, ?, ?, ?)");
    $stmt->execute(array($category, $demoUrl, $password, $officialUrl));
    return (int) getDB()->lastInsertId();
}

function updateWebsite($id, $category, $demoUrl, $password, $officialUrl) {
    $stmt = getDB()->prepare("UPDATE websites SET category=?, demo_url=?, password=?, official_url=? WHERE id=?");
    return $stmt->execute(array($category, $demoUrl, $password, $officialUrl, $id));
}

function deleteWebsite($id) {
    $stmt = getDB()->prepare("DELETE FROM websites WHERE id=?");
    return $stmt->execute(array($id));
}

function getUniqueCategories() {
    return getDB()->query("SELECT DISTINCT category FROM websites ORDER BY category ASC")->fetchAll(PDO::FETCH_COLUMN);
}

function searchWebsites($keyword = '', $category = '') {
    $sql = "SELECT * FROM websites WHERE 1=1";
    $params = array();
    if ($keyword !== '') {
        $sql .= " AND (category LIKE ? OR demo_url LIKE ? OR official_url LIKE ?)";
        $like = "%" . $keyword . "%";
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
    }
    if ($category !== '') {
        $sql .= " AND category = ?";
        $params[] = $category;
    }
    $sql .= " ORDER BY id ASC";
    $stmt = getDB()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

// ========== GOOGLE SHEETS SYNC ==========
define('GOOGLE_SHEET_CSV_URL', 'https://docs.google.com/spreadsheets/d/1L5OqP8IGL_2kLPZL7au66WvfL4VASK0-Z-y9repBeoA/export?format=csv');

function syncFromGoogleSheets() {
    try {
        $context = stream_context_create(array(
            'http' => array('timeout' => 15, 'ignore_errors' => true),
            'ssl' => array('verify_peer' => false, 'verify_peer_name' => false),
        ));
        $csv = @file_get_contents(GOOGLE_SHEET_CSV_URL, false, $context);

        if ($csv === false) {
            return array('type' => 'error', 'message' => 'Không thể kết nối đến Google Sheets.');
        }

        $lines = explode("\n", $csv);
        $lines = array_filter($lines, function($line) { return trim($line) !== ''; });
        if (count($lines) < 2) {
            return array('type' => 'error', 'message' => 'Google Sheet không có dữ liệu.');
        }

        array_shift($lines);

        $existing = getDB()->query("SELECT demo_url FROM websites")->fetchAll(PDO::FETCH_COLUMN);
        $existingUrls = array_map(function($u) { return rtrim(trim($u), '/'); }, $existing);

        $added = 0;
        $skipped = 0;

        foreach ($lines as $line) {
            $cols = str_getcsv($line);
            if (count($cols) < 2) continue;

            $category = trim(isset($cols[0]) ? $cols[0] : '');
            $demoUrl = trim(isset($cols[1]) ? $cols[1] : '');
            $password = trim(isset($cols[2]) ? $cols[2] : '');
            $officialUrl = trim(isset($cols[3]) ? $cols[3] : '');

            $demoUrl = str_replace(array("\r", "\n"), '', $demoUrl);
            $officialUrl = str_replace(array("\r", "\n"), '', $officialUrl);

            if ($category === '' || $demoUrl === '') continue;
            if ($password === 'null') $password = '';

            $cleanUrl = rtrim($demoUrl, '/');
            if (in_array($cleanUrl, $existingUrls)) {
                $skipped++;
                continue;
            }

            createWebsite($category, $demoUrl, $password, $officialUrl);
            $existingUrls[] = $cleanUrl;
            $added++;
        }

        if ($added === 0) {
            return array('type' => 'success', 'message' => "Đã đồng bộ! Không có website mới ({$skipped} website đã tồn tại).");
        }

        return array('type' => 'success', 'message' => "Đồng bộ thành công! Đã thêm {$added} website mới, bỏ qua {$skipped} website đã tồn tại.");

    } catch (Exception $e) {
        return array('type' => 'error', 'message' => 'Lỗi: ' . $e->getMessage());
    }
}

// ========== TRAFFIC TRACKING ==========
function recordPageView() {
    try {
        $pdo = getDB();
        $today = date('Y-m-d');
        if (DB_TYPE === 'mysql') {
            $stmt = $pdo->prepare("INSERT INTO page_views (view_date, views) VALUES (?, 1) ON DUPLICATE KEY UPDATE views = views + 1");
            $stmt->execute(array($today));
        } else {
            // SQLite
            $stmt = $pdo->prepare("INSERT INTO page_views (view_date, views) VALUES (?, 1) ON CONFLICT(view_date) DO UPDATE SET views = views + 1");
            $stmt->execute(array($today));
        }
    } catch(Exception $e) {}
}

function getDailyViews($year, $month) {
    $pdo = getDB();
    $start = sprintf("%04d-%02d-01", $year, $month);
    $end = date("Y-m-t", strtotime($start));
    $stmt = $pdo->prepare("SELECT view_date, views FROM page_views WHERE view_date >= ? AND view_date <= ?");
    $stmt->execute(array($start, $end));
    $results = $stmt->fetchAll();
    $map = array();
    foreach($results as $r) {
        $map[$r['view_date']] = (int)$r['views'];
    }
    return $map;
}

function getSetting($key, $default = '') {
    $stmt = getDB()->prepare("SELECT settings_value FROM settings WHERE settings_key = ?");
    $stmt->execute(array($key));
    $value = $stmt->fetchColumn();
    return ($value === false || $value === null || trim((string) $value) === '') ? $default : $value;
}
