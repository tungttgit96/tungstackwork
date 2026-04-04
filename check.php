<?php
echo "<h2>PHP Info - Tuka Diagnostic</h2>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>PDO Drivers: " . implode(', ', PDO::getAvailableDrivers()) . "</p>";
echo "<p>SQLite3 extension: " . (extension_loaded('sqlite3') ? 'YES' : 'NO') . "</p>";
echo "<p>PDO_SQLite: " . (in_array('sqlite', PDO::getAvailableDrivers()) ? 'YES' : 'NO') . "</p>";
echo "<p>PDO_MySQL: " . (in_array('mysql', PDO::getAvailableDrivers()) ? 'YES' : 'NO') . "</p>";
echo "<p>Data dir writable: " . (is_writable(__DIR__ . '/data') ? 'YES' : 'NO') . "</p>";

// Try creating SQLite
try {
    $db = new PDO('sqlite:' . __DIR__ . '/data/test.db');
    echo "<p style='color:green'>SQLite connection: OK</p>";
    unlink(__DIR__ . '/data/test.db');
} catch (Exception $e) {
    echo "<p style='color:red'>SQLite error: " . $e->getMessage() . "</p>";
}

// Try MySQL
try {
    $db = new PDO('mysql:host=localhost', 'root', '');
    echo "<p style='color:green'>MySQL connection: OK</p>";
} catch (Exception $e) {
    echo "<p style='color:orange'>MySQL: " . $e->getMessage() . "</p>";
}
