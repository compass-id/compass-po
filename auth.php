<?php
// auth.php
require 'config.php';
session_start();

$action = $_POST['action'] ?? '';

// Database Auto-Patch for fallback auth
try {
    $pdo->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS is_approved TINYINT(1) DEFAULT 1");
} catch (PDOException $e) { }

if ($action === 'register') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'] ?? '';
    
    // Server-side password match validation
    if($_POST['password'] !== $_POST['confirm_password']) {
        header("Location: index.php?page=register&err=passwords_mismatch");
        exit;
    }

    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'public'; // Default

    try {
        // Insert with is_approved = 0
        $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password, role, is_approved) VALUES (?, ?, ?, ?, ?, 0)");
        $stmt->execute([$name, $email, $phone, $pass, $role]);
        header("Location: index.php?page=login&msg=registered_pending");
    } catch (Exception $e) {
        header("Location: index.php?page=login&err=email_exists");
    }
} 
elseif ($action === 'login') {
    $email = $_POST['email'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['password'], $user['password'])) {
        
        // Prevent login if not approved yet
        if (isset($user['is_approved']) && $user['is_approved'] == 0) {
            header("Location: index.php?page=login&err=pending_approval");
            exit;
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];
        
        // Redirect based on role
        if($user['role'] === 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: index.php?page=catalog");
        }
    } else {
        header("Location: index.php?page=login&err=invalid");
    }
}
elseif ($action === 'logout') {
    session_destroy();
    header("Location: index.php");
}
?>