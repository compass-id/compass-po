<?php
// admin.php
require 'config.php';
session_start();

// Security Check 
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access Denied. Admins Only.");
}

// Data Analytics for Charts 
// 1. Count forms by Interest Level
$stats = $pdo->query("SELECT interest_level, COUNT(*) as count FROM interest_forms GROUP BY interest_level")->fetchAll();
// 2. Latest Orders
$orders = $pdo->query("SELECT * FROM orders ORDER BY order_date DESC LIMIT 5")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Admin Dashboard</title>
</head>
<body>
    <div class="header-glass">
        <h3>Dashboard</h3>
        <a href="auth.php?action=logout" style="color:red; text-decoration:none;">Log Out</a>
    </div>

    <div class="ios-card">
        <h3>📊 Interest Analysis</h3>
        <p style="font-size:0.8rem; color:gray;">Form Submissions by Interest Level</p>
        <div class="chart-container">
            <?php foreach($stats as $stat): 
                $height = ($stat['count'] * 20) + 10; // Simple scaling
            ?>
            <div class="bar" style="height: <?php echo $height; ?>px;">
                <span><?php echo $stat['count']; ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <div style="display:flex; justify-content:space-between; font-size:0.7rem; margin-top:5px;">
            <?php foreach($stats as $stat) echo "<span>{$stat['interest_level']}</span>"; ?>
        </div>
    </div>

    <div class="ios-card">
        <h3>Recent Pre-Orders</h3>
        <table style="width:100%; font-size:0.8rem; border-collapse:collapse;">
            <tr style="text-align:left; border-bottom:1px solid #eee;">
                <th>ID</th><th>Tier</th><th>Total</th><th>Status</th>
            </tr>
            <?php foreach($orders as $o): ?>
            <tr style="border-bottom:1px solid #eee; height:40px;">
                <td>#<?php echo $o['id']; ?></td>
                <td>
                    <span class="badge badge-tier<?php echo $o['tier_applied']; ?>">
                        Tier <?php echo $o['tier_applied']; ?>
                    </span>
                </td>
                <td>Rp <?php echo number_format($o['total_amount']); ?></td>
                <td><?php echo $o['status']; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    
    <div style="padding:20px;">
        <button class="ios-card" style="width:100%; text-align:center; color:var(--primary);">Export Data to CSV</button>
    </div>
</body>
</html>