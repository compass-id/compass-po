<?php
// invoice.php
session_start();
require 'config.php';

// Security Check
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) die("Access Denied");

$pdo = getDB();
$order_id = (int)$_GET['id'];

// 1. Fetch Order Details
$stmt = $pdo->prepare("SELECT o.*, u.name, u.email, u.phone FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) die("Order not found");

// 2. Security: Only Owner or Admin can view
if ($order['user_id'] != $_SESSION['user_id'] && ($_SESSION['role'] ?? '') !== 'admin') {
    die("Access Denied");
}

// 3. Fetch Order Items
$stmt = $pdo->prepare("SELECT oi.*, b.title FROM order_items oi JOIN books b ON oi.book_id = b.id WHERE oi.order_id = ?");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #<?php echo $order['id']; ?></title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; padding: 40px; max-width: 800px; margin: 0 auto; color: #1C1C1E; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 30px; }
        .brand { font-size: 24px; font-weight: 800; color: #007AFF; }
        .meta { text-align: right; }
        .info-grid { display: flex; justify-content: space-between; margin-bottom: 40px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { text-align: left; border-bottom: 2px solid #000; padding: 10px; }
        td { border-bottom: 1px solid #eee; padding: 10px; }
        .total { text-align: right; font-size: 20px; font-weight: 700; margin-top: 20px; }
        .badge { padding: 5px 10px; border-radius: 5px; font-size: 12px; font-weight: bold; background: #eee; }
        .paid { background: #34C759; color: white; }
        .pending { background: #FF9500; color: white; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>

    <div class="header">
        <div class="brand">Compass.</div>
        <div class="meta">
            <h1>INVOICE</h1>
            <div>#<?php echo str_pad((string)$order['id'], 5, '0', STR_PAD_LEFT); ?></div>
            <div><?php echo date('d M Y', strtotime($order['created_at'])); ?></div>
        </div>
    </div>

    <div class="info-grid">
        <div>
            <strong>Billed To:</strong><br>
            <?php echo htmlspecialchars($order['name']); ?><br>
            <?php echo htmlspecialchars($order['email']); ?><br>
            <?php echo htmlspecialchars($order['phone'] ?? ''); ?>
        </div>
        <div style="text-align: right;">
            <strong>Pay To:</strong><br>
            Compass Publishing Indonesia<br>
            Bank BCA: 123-456-7890<br>
            A/N Compass Indo
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item Description</th>
                <th>Price</th>
                <th>Qty</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($items as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['title']); ?></td>
                <td>Rp <?php echo number_format($item['unit_price']); ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td style="text-align: right;">Rp <?php echo number_format($item['total_price']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="total">
        Grand Total: Rp <?php echo number_format($order['total_amount']); ?>
    </div>
    
    <div style="margin-top: 20px;">
        Status: 
        <span class="badge <?php echo $order['status'] == 'paid' ? 'paid' : 'pending'; ?>">
            <?php echo strtoupper($order['status']); ?>
        </span>
    </div>

    <div class="no-print" style="margin-top: 50px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #007AFF; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 16px;">Print Invoice</button>
        <br><br>
        <a href="index.php?page=profile" style="color: #888; text-decoration: none;">&larr; Back to Dashboard</a>
    </div>

</body>
</html>