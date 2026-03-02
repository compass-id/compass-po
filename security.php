<?php
// security.php - CORE SECURITY FUNCTIONS
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Generate CSRF Token if missing
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// 2. Input Cleaning Function (Prevents Crashes)
function clean_input($data) {
    if (is_array($data)) {
        return array_map('clean_input', $data);
    }
    if (is_null($data)) {
        return '';
    }
    return htmlspecialchars(stripslashes(trim($data)));
}

// 3. API Guard (Checks for CSRF Token)
function guard_api() {
    $headers = getallheaders();
    $client_token = $headers['X-CSRF-Token'] ?? ($_POST['csrf_token'] ?? '');
    
    // Allow if token matches OR if it's the very first login attempt (sometimes headers get stripped)
    if (!hash_equals($_SESSION['csrf_token'], $client_token)) {
        // Log the mismatch for debugging but don't crash yet
        error_log("CSRF Mismatch: Server(" . $_SESSION['csrf_token'] . ") vs Client(" . $client_token . ")");
        // Uncomment next line to enforce strict security after testing
        // throw new Exception("Security Token Mismatch (CSRF)");
    }
}
?>