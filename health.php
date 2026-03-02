<?php
// health.php - Run this to verify your installation
echo "<h2>System Health Check</h2>";

// 1. Check PHP Version
echo "PHP Version: " . phpversion() . " ... " . (version_compare(phpversion(), '7.4.0', '>=') ? "✅ OK" : "❌ UPDATE PHP") . "<br>";

// 2. Check Required Files
$required = [
    'config.php', 'security.php', 'api.php', 'index.php', 'style.css', 'app.js',
    'view_home.php', 'view_admin.php', 'view_catalog.php'
];
echo "<h3>File Integrity:</h3>";
foreach ($required as $file) {
    echo "$file ... " . (file_exists($file) ? "✅ Found" : "❌ MISSING") . "<br>";
}

// 3. Check Database Connection
require_once 'config.php';
echo "<h3>Database Connection:</h3>";
try {
    $pdo = getDB();
    echo "Connection ... ✅ Success<br>";
    
    // Check Table Counts
    $tables = ['users', 'books', 'orders', 'support_tickets', 'interest_forms'];
    foreach ($tables as $t) {
        $count = $pdo->query("SELECT COUNT(*) FROM $t")->fetchColumn();
        echo "Table '$t' ... ✅ OK ($count rows)<br>";
    }
} catch (Exception $e) {
    echo "Connection ... ❌ FAILED: " . $e->getMessage();
}

// 4. Check Write Permissions (for Logs)
echo "<h3>Permissions:</h3>";
$logFile = 'sys_error.log';
if (!file_exists($logFile)) file_put_contents($logFile, '');
echo "Error Log Writable ... " . (is_writable($logFile) ? "✅ Yes" : "❌ NO (Check Permissions)") . "<br>";
?>