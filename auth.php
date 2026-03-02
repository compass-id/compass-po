<?php
// auth.php
require 'config.php';
session_start();

$action = $_POST['action'] ?? '';

if ($action === 'register') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'public'; // Default

    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $pass, $role]);
        header("Location: index.php?page=login&msg=registered");
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