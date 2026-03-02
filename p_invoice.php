<?php
// invoice_receipt.php - PER TRANSACTION RECEIPT
session_start();
require 'config.php';
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) die("Access Denied");

$pdo = getDB();
$trans_id = (int)$_GET['id'];

// Fetch Transaction & Order Info
$stmt = $pdo->prepare("
    SELECT t.*, o.id as order_ref, u.name, u.email, u.phone 
    FROM transactions t 
    JOIN orders o ON t.order_id = o.id 
    JOIN users u ON t.user_id = u.id 
    WHERE t.id = ?
");
$stmt->execute([$trans_id]);
$trans = $stmt->fetch();

if (!$trans || ($trans['user_id'] != $_SESSION['user_id'] && $_SESSION['role'] !== 'admin')) die("Access Denied");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Receipt #<?php echo $trans['id']; ?></title>
    <style>
        body { font-family: sans-serif; padding: 40px; max-width: 600px; margin: 0 auto; border: 1px solid #eee; }
        .header { text-align: center; margin-bottom: 40px; }
        .logo { font-size: 24px; font-weight: bold; color: #007AFF; margin-bottom: 10px; }
        .success-stamp { color: #34C759; font-weight: bold; border: 2px solid #34C759; display: inline-block; padding: 5px 15px; border-radius: 4px; transform: rotate(-5deg); }
        .row { display: flex; justify-content: space-between; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 15px; }
        .total { font-size: 24px; font-weight: bold; text-align: right; margin-top: 30px; }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <div class="logo">Compass.</div>
        <h2>PAYMENT RECEIPT</h2>
        <div class="success-stamp">PAID & VERIFIED</div>
    </div>

    <div class="row">
        <strong>Transaction ID:</strong>
        <span>#<?php echo str_pad($trans['id'], 6, '0', STR_PAD_LEFT); ?></span>
    </div>
    <div class="row">
        <strong>Date Paid:</strong>
        <span><?php echo date('d M Y, H:i', strtotime($trans['created_at'])); ?></span>
    </div>
    <div class="row">
        <strong>Payer:</strong>
        <span><?php echo htmlspecialchars($trans['name']); ?></span>
    </div>
    <div class="row">
        <strong>Reference Order:</strong>
        <span>Order #<?php echo $trans['order_ref']; ?></span>
    </div>
    <div class="row">
        <strong>Payment Note:</strong>
        <span><?php echo htmlspecialchars($trans['note']); ?></span>
    </div>

    <div class="total">
        Amount Paid: Rp <?php echo number_format($trans['amount']); ?>
    </div>

    <div style="text-align: center; margin-top: 50px; font-size: 12px; color: #888;">
        This is a computer-generated receipt.<br>
        Compass Publishing Indonesia
    </div>
</body>
</html>