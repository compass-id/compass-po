<?php
// view_admin_order_detail.php - FIXED NULL DEPRECATION
if (($_SESSION['role'] ?? '') !== 'admin') die("Access Denied");
$pdo = getDB();
$oid = $_GET['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // A. UPDATE STATUS
    if (isset($_POST['update_status'])) {
        $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?")->execute([$_POST['order_status'], $oid]);
        echo "<script>alert('Status Updated'); window.location.href='?page=admin_order_detail&id=$oid';</script>";
        exit;
    }

    // C. VERIFY PAYMENT (AUTO-CONFIRM FIX)
    if (isset($_POST['verify_payment'])) {
        $pdo->prepare("UPDATE order_payments SET status = 'verified' WHERE id = ?")->execute([$_POST['payment_id']]);
        
        $pdo->prepare("UPDATE orders SET paid_amount = paid_amount + ? WHERE id = ?")->execute([$_POST['amount'], $oid]);
        
        $chk = $pdo->query("SELECT total_amount, paid_amount FROM orders WHERE id=$oid")->fetch();
        $new_pay_status = ($chk['paid_amount'] >= $chk['total_amount'] - 0.01) ? 'paid' : 'partial'; // -0.01 margin for decimals
        
        $pdo->prepare("UPDATE orders SET payment_status = ?, status = 'confirmed' WHERE id = ?")
            ->execute([$new_pay_status, $oid]);

        echo "<script>alert('Payment Verified & Order Confirmed'); window.location.href='?page=admin_order_detail&id=$oid';</script>";
        exit;
    }
    
    // D. REJECT PAYMENT
    if (isset($_POST['reject_payment'])) {
        $pdo->prepare("UPDATE order_payments SET status = 'rejected' WHERE id = ?")->execute([$_POST['payment_id']]);
        echo "<script>alert('Payment Rejected'); window.location.href='?page=admin_order_detail&id=$oid';</script>";
        exit;
    }
}

// FETCH DATA
$stmt = $pdo->prepare("SELECT o.*, u.name as user_name, u.email as user_email, u.phone as user_phone, u.institution as user_inst FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = ?");
$stmt->execute([$oid]);
$order = $stmt->fetch();
if(!$order) die("Order not found");

$items = $pdo->query("SELECT oi.*, b.title, b.cover_image FROM order_items oi JOIN books b ON oi.book_id = b.id WHERE oi.order_id = $oid")->fetchAll();
$payments = $pdo->query("SELECT * FROM order_payments WHERE order_id = $oid ORDER BY payment_date DESC")->fetchAll();

// Calculations
$remaining = $order['total_amount'] - $order['paid_amount'];
if($remaining < 0) $remaining = 0;
$progress = ($order['total_amount'] > 0) ? ($order['paid_amount'] / $order['total_amount']) * 100 : 0;

// Snapshot Data
$snapshot = json_decode($order['shipping_snapshot'] ?? '{}', true);
$dest_address = $snapshot['address_line'] ?? ($order['shipping_city'] . " (Address not archived)");
$dest_recipient = $snapshot['recipient'] ?? $order['user_name'];
$est_arrival = $snapshot['est_arrival'] ?? date('Y-m-d', strtotime('+3 days'));
?>

<div class="container" style="max-width:1100px; margin-top:20px;">
    
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;">
        <div>
            <a href="?page=admin_orders" style="color:#666; font-size:14px;">&larr; Back to List</a>
            <h1 style="margin:10px 0 5px 0;">Order #<?php echo $order['id']; ?> <span style="font-weight:normal; color:#666; font-size:16px;">(Admin)</span></h1>
            <div style="font-size:14px; color:#888;">Client: <?php echo htmlspecialchars($order['user_inst'] ?: 'Individual'); ?></div>
        </div>
        <div>
            <?php 
                $st = $order['status'];
                $bg = '#eee'; $col = '#333';
                if($st=='confirmed') { $bg='#e8f5e9'; $col='#2e7d32'; }
                elseif($st=='pending') { $bg='#fff3e0'; $col='#ef6c00'; }
                elseif($st=='rejected' || $st=='cancelled') { $bg='#ffebee'; $col='#c62828'; }
            ?>
            <span style="padding:8px 16px; border-radius:30px; font-weight:bold; font-size:14px; background: <?php echo $bg; ?>; color: <?php echo $col; ?>;">
                <?php echo strtoupper($st); ?>
            </span>
        </div>
    </div>

    <div class="dashboard-grid" style="display:grid; grid-template-columns: 2fr 1fr; gap:30px;">
        
        <div style="display:flex; flex-direction:column; gap:20px;">
            
            <div class="card">
                <h3>Items Ordered</h3>
                <?php foreach($items as $item): ?>
                    <div style="display:flex; justify-content:space-between; margin-bottom:5px;">
                        <div><?php echo htmlspecialchars($item['title'] ?? ''); ?> x<?php echo $item['quantity']; ?></div>
                        <div style="font-weight:bold;">Rp <?php echo number_format($item['quantity'] * $item['unit_price']); ?></div>
                    </div>
                <?php endforeach; ?>
                <div style="text-align:right; font-weight:bold; margin-top:10px; font-size:18px;">Total: Rp <?php echo number_format($order['total_amount']); ?></div>
            </div>

            <div class="card">
                <h3>Shipment Status</h3>
                <div style="display:grid; grid-template-columns:1fr 1fr;">
                    <div>
                        <strong>To:</strong> <?php echo htmlspecialchars($dest_recipient ?? ''); ?><br>
                        <span style="font-size:12px; color:#666;"><?php echo htmlspecialchars($dest_address ?? ''); ?></span>
                    </div>
                    <div>
                        <strong>Status:</strong> <?php echo ucfirst($order['shipping_status'] ?? 'Processing'); ?><br>
                        <strong>ETA:</strong> <?php echo date('d M Y', strtotime($est_arrival)); ?>
                    </div>
                </div>
            </div>

            <div class="card">
                <h3>Payment Verification</h3>
                <?php if(empty($payments)): ?>
                    <div style="color:#888;">No payments found.</div>
                <?php else: ?>
                    <table style="width:100%;">
                        <?php foreach($payments as $p): ?>
                        <tr style="border-bottom:1px solid #eee;">
                            <td style="padding:8px 0;">Rp <?php echo number_format($p['amount']); ?></td>
                            <td><a href="uploads/<?php echo htmlspecialchars($p['proof_image'] ?? ''); ?>" target="_blank" style="color:#007AFF;">View Proof</a></td>
                            <td style="text-align:right;">
                                <?php if($p['status'] == 'pending'): ?>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="payment_id" value="<?php echo $p['id']; ?>">
                                        <input type="hidden" name="amount" value="<?php echo $p['amount']; ?>">
                                        <button type="submit" name="verify_payment" class="btn btn-sm" style="background:#34C759; color:white;">Accept</button>
                                        <button type="submit" name="reject_payment" class="btn btn-sm" style="background:#FF3B30; color:white;">Reject</button>
                                    </form>
                                <?php else: ?>
                                    <span style="font-weight:bold; color:<?php echo $p['status']=='verified'?'green':'red'; ?>;"><?php echo strtoupper($p['status']); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                <?php endif; ?>
            </div>
        </div>

        <div style="display:flex; flex-direction:column; gap:20px;">
            
            <div class="card" style="border-top: 4px solid <?php echo $remaining <= 0 ? '#34C759' : '#FF9500'; ?>;">
                <h3>Payment Summary</h3>
                <div style="margin:20px 0;">
                    <div style="display:flex; justify-content:space-between; font-size:12px; font-weight:600;">
                        <span>Received: Rp <?php echo number_format($order['paid_amount']); ?></span>
                        <span>Total: Rp <?php echo number_format($order['total_amount']); ?></span>
                    </div>
                    <div style="width:100%; height:8px; background:#eee; border-radius:4px; overflow:hidden; margin-top:5px;">
                        <div style="width:<?php echo $progress; ?>%; height:100%; background:<?php echo $remaining <= 0 ? '#34C759' : '#FF9500'; ?>;"></div>
                    </div>
                    <div style="text-align:right; font-size:12px; color:#666; margin-top:5px;">
                        Remaining: Rp <?php echo number_format($remaining); ?>
                    </div>
                </div>
            </div>

            <div class="card">
                <h3>Admin Actions</h3>
                <form method="POST">
                    <label style="font-weight:bold;">Set Order Status</label>
                    <div style="display:flex; gap:5px; margin-top:5px;">
                        <select name="order_status" style="flex:1; padding:8px; border-radius:4px; border:1px solid #ccc;">
                            <option value="pending" <?php echo $order['status']=='pending'?'selected':''; ?>>Pending</option>
                            <option value="confirmed" <?php echo $order['status']=='confirmed'?'selected':''; ?>>Confirmed</option>
                            <option value="rejected" <?php echo $order['status']=='rejected'?'selected':''; ?>>Rejected</option>
                            <option value="cancelled" <?php echo $order['status']=='cancelled'?'selected':''; ?>>Cancelled</option>
                        </select>
                        <button type="submit" name="update_status" class="btn btn-sm" style="background:#333; color:white;">Set</button>
                    </div>
                </form>
            </div>

            <div class="card">
                <h3>Account Contact</h3>
                <div style="font-size:13px; margin-bottom:10px;">
                    <ion-icon name="person"></ion-icon> <?php echo htmlspecialchars($order['user_name'] ?? ''); ?>
                </div>
                <div style="font-size:13px; margin-bottom:10px;">
                    <ion-icon name="mail"></ion-icon> <?php echo htmlspecialchars($order['user_email'] ?? ''); ?>
                </div>
                <div style="font-size:13px;">
                    <ion-icon name="call"></ion-icon> <?php echo htmlspecialchars($order['user_phone'] ?? ''); ?>
                </div>
                <a href="mailto:<?php echo htmlspecialchars($order['user_email'] ?? ''); ?>" class="btn btn-sm btn-outline" style="width:100%; margin-top:15px; text-align:center;">Send Email</a>
            </div>
        </div>

    </div>
</div>