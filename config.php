<?php
// config.php
declare(strict_types=1);

// --- CHANGE THESE LINES FOR DEBUGGING ---
error_reporting(E_ALL);
ini_set('display_errors', '1'); // Turn this to '1' to see the blank screen error
ini_set('log_errors', '1');
ini_set('error_log', 'sys_error.log');
// ----------------------------------------

// 1. Database Credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'u802091730_seg');
define('DB_USER', 'u802091730_compass');
define('DB_PASS', 'CompassPub2024!');

// ... keep the rest of the file the same ...
function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            // STOP SILENT FAILURES
            die("<h1>Database Error</h1><p>" . $e->getMessage() . "</p>");
        }
    }
    return $pdo;
}
?>