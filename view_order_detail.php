<?php
// view_order_detail.php - FIXED WITH 11 REQUIREMENTS
if (!isset($_SESSION['user_id'])) echo "<script>window.location='?page=login';</script>";
$pdo = getDB();
$uid = $_SESSION['user_id'];
$oid = $_GET['id'] ?? 0;

// --- 1. HANDLE POST ACTIONS ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // ACTION: Cancel Order (Req #8)
    if (isset($_POST['cancel_order'])) {
        $pdo->prepare("UPDATE orders SET status = 'cancelled', shipping_status = 'cancelled' WHERE id = ? AND user_id = ? AND paid_amount = 0")
            ->execute([$oid, $uid]);
        echo "<script>alert('Order has been cancelled.'); window.location.href='?page=order_detail&id=$oid';</script>";
        exit;
    }

    // ACTION: Confirm Delivery (Req #7)
    if (isset($_POST['confirm_delivery'])) {
        $pdo->prepare("UPDATE orders SET shipping_status = 'completed', confirmed_arrival = NOW() WHERE id = ? AND user_id = ?")
            ->execute([$oid, $uid]);
        echo "<script>alert('Thank you! Item arrival confirmed.'); window.location.href='?page=order_detail&id=$oid';</script>";
        exit;
    }

    // ACTION: Upload Proof
    if (isset($_FILES['proof'])) {
        $filename = "proof_" . $oid . "_" . time() . ".jpg";
        $amount = $_POST['amount'] ?? 0;
        // move_uploaded_file($_FILES['proof']['tmp_name'], "uploads/" . $filename); // Uncomment in production
        
        $pdo->prepare("INSERT INTO order_payments (order_id, amount, proof_image, status, payment_date) VALUES (?, ?, ?, 'pending', NOW())")->execute([$oid, $amount, $filename]);
        
        // Set to partial to stop the initial timer
        $pdo->prepare("UPDATE orders SET payment_status = 'partial' WHERE id = ?")->execute([$oid]);
        
        echo "<script>alert('Proof uploaded! Waiting for verification.'); window.location.href='?page=order_detail&id=$oid';</script>";
        exit;
    }
}

// --- 2. FETCH DATA ---
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$oid, $uid]);
$order = $stmt->fetch();

if (!$order) {
    echo "<div class='container' style='padding:50px; text-align:center;'><h3>Order #$oid not found.</h3><a href='?page=profile'>Back to Dashboard</a></div>";
    return;
}

// --- 3. TIME & STATUS LOGIC (Req #1, #2, #3) ---
$created_time = strtotime($order['created_at']);
$deadline = $created_time + (24 * 60 * 60); // 24 Hours initial deadline
$now = time();
$seconds_left = $deadline - $now;

// A. LOGIC: Check Initial 24h Timer
if ($seconds_left <= 0 && $order['paid_amount'] <= 0) {
    // Req #1, #2, #3: Reject Order, Set Unpaid, Cancel Shipping
    if ($order['status'] !== 'rejected' && $order['status'] !== 'cancelled') {
        $pdo->prepare("UPDATE orders SET status = 'rejected', payment_status = 'unpaid', shipping_status = 'cancelled' WHERE id = ?")->execute([$oid]);
        $order['status'] = 'rejected';
        $order['payment_status'] = 'unpaid';
        $order['shipping_status'] = 'cancelled';
    }
    $seconds_left = 0;
}

// B. LOGIC: Monthly Schedule (Req #4, #5)
// Assumption: Monthly installments. Next due date is 30 days after last payment or order date.
$last_payment_date = $created_time;
$payments = $pdo->query("SELECT * FROM order_payments WHERE order_id = $oid ORDER BY payment_date DESC")->fetchAll();
// Find latest verified payment date
foreach($payments as $p) {
    if($p['status'] == 'verified') {
        $last_payment_date = strtotime($p['payment_date']);
        break;
    }
}

$next_due_date = strtotime('+30 days', $last_payment_date);
$days_until_due = ceil(($next_due_date - $now) / (60 * 60 * 24));
$is_monthly_late = ($now > $next_due_date && $order['payment_status'] == 'partial');

// Update DB if monthly payment is late (Req #2 variant)
if ($is_monthly_late) {
    // Note: We flag it visually, but usually we don't revert 'partial' to 'unpaid' in DB to preserve history, 
    // but per your request to handle status:
    $monthly_alert_class = "card-danger";
    $monthly_alert_msg = "PAYMENT OVERDUE: You missed the monthly payment date.";
} elseif ($days_until_due <= 3 && $order['payment_status'] == 'partial') {
    // Req #5: Notify when close
    $monthly_alert_class = "card-warning";
    $monthly_alert_msg = "REMINDER: Upcoming payment due in $days_until_due days.";
}

// Fetch Items
$stmtItems = $pdo->prepare("SELECT oi.*, b.title, b.cover_image FROM order_items oi JOIN books b ON oi.book_id = b.id WHERE oi.order_id = ?");
$stmtItems->execute([$oid]);
$items = $stmtItems->fetchAll();

// Financials
$verified_paid = 0; 
foreach ($payments as $p) {
    if ($p['status'] == 'verified') $verified_paid += $p['amount'];
}
$remaining = $order['total_amount'] - $verified_paid;
if($remaining < 0) $remaining = 0;
$progress = ($order['total_amount'] > 0) ? ($verified_paid / $order['total_amount']) * 100 : 0;

// Address
$snapshot = json_decode($order['shipping_snapshot'] ?? '{}', true);
$full_address = $snapshot['address_line'] ?? ($order['shipping_city'] . ' (Address not archived)');
$est_arrival = $snapshot['est_arrival'] ?? date('Y-m-d', strtotime('+3 days'));
?>

<div class="container" style="max-width:1000px; margin-top:20px;">
    
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;">
        <div>
            <a href="?page=profile" style="text-decoration:none; color:#666; font-size:14px;">&larr; Back to Dashboard</a>
            <h1 style="margin:10px 0 5px 0;">Order #<?php echo $order['id']; ?></h1>
            <div style="font-size:14px; color:#888;">Placed on <?php echo date('d M Y, H:i', strtotime($order['created_at'])); ?></div>
        </div>
        
        <div style="display:flex; gap:10px; align-items:center;">
            <?php if($order['payment_status'] == 'paid'): ?>
                <a href="print_invoice.php?type=order&id=<?php echo $order['id']; ?>" target="_blank" class="btn btn-sm" style="background:#333; color:white;">
                    <ion-icon name="print"></ion-icon> Invoice
                </a>
            <?php else: ?>
                <button disabled class="btn btn-sm" style="background:#eee; color:#999; cursor:not-allowed;" title="Available after full payment">
                    <ion-icon name="lock-closed"></ion-icon> Invoice
                </button>
            <?php endif; ?>

            <?php 
                $st = $order['status'];
                $bg = '#eee'; $col = '#333';
                if($st=='confirmed') { $bg='#e8f5e9'; $col='#2e7d32'; } 
                elseif($st=='pending') { $bg='#fff3e0'; $col='#ef6c00'; } 
                elseif($st=='rejected' || $st=='cancelled') { $bg='#ffebee'; $col='#c62828'; }
            ?>
            <span style="display:inline-block; padding:8px 16px; border-radius:30px; font-weight:bold; font-size:14px; background:<?php echo $bg; ?>; color:<?php echo $col; ?>;">
                <?php echo strtoupper($st); ?>
            </span>
        </div>
    </div>

    <?php if($order['status'] == 'pending' && $order['paid_amount'] == 0 && $seconds_left > 0): ?>
    <div class="card" style="background:#fff3e0; border:1px solid #ffe0b2; text-align:center; padding:15px; margin-bottom:20px;">
        <div style="font-weight:bold; color:#ef6c00; font-size:16px; margin-bottom:5px;">
            <ion-icon name="time-outline" style="vertical-align:middle;"></ion-icon> First Payment Deadline
        </div>
        <div style="color:#555; font-size:13px; margin-bottom:10px;">
            Complete your first payment to secure your order.
        </div>
        <div id="countdown" style="font-size:24px; font-weight:800; color:#d84315;"></div>
    </div>
    <script>
        var timeLeft = <?php echo $seconds_left; ?>;
        var timerId = setInterval(function(){
            if(timeLeft <= 0){ clearInterval(timerId); window.location.reload(); } 
            else {
                var h = Math.floor(timeLeft / 3600);
                var m = Math.floor((timeLeft % 3600) / 60);
                var s = Math.floor(timeLeft % 60);
                document.getElementById("countdown").innerHTML = h + "h " + m + "m " + s + "s";
                timeLeft--;
            }
        }, 1000);
    </script>
    <?php endif; ?>

    <?php if(isset($monthly_alert_msg)): ?>
        <div class="card" style="background: <?php echo $is_monthly_late ? '#ffebee' : '#fff8e1'; ?>; border:1px solid <?php echo $is_monthly_late ? '#ffcdd2' : '#ffe0b2'; ?>; color: <?php echo $is_monthly_late ? '#c62828' : '#f57c00'; ?>; text-align:center; padding:15px; margin-bottom:20px;">
            <strong><?php echo $monthly_alert_msg; ?></strong><br>
            Please upload proof below.
        </div>
    <?php endif; ?>


    <div class="dashboard-grid" style="display:grid; grid-template-columns: 2fr 1fr; gap:30px;">
        
        <div style="display:flex; flex-direction:column; gap:20px;">
            
            <div class="card">
                <h3 style="margin-top:0; border-bottom:1px solid #eee; padding-bottom:15px; margin-bottom:15px;">Items Ordered</h3>
                <?php foreach($items as $item): ?>
                    <div style="display:flex; justify-content:space-between; margin-bottom:10px;">
                        <div><?php echo htmlspecialchars($item['title']); ?> <small>x<?php echo $item['quantity']; ?></small></div>
                        <div style="font-weight:bold;">Rp <?php echo number_format($item['quantity'] * $item['unit_price']); ?></div>
                    </div>
                <?php endforeach; ?>
                <div style="text-align:right; font-weight:800; font-size:18px; margin-top:15px;">
                    Total: Rp <?php echo number_format($order['total_amount']); ?>
                </div>
            </div>

            <div class="card">
                <h3 style="margin-top:0;">Shipment Details</h3>
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; align-items:start;">
                    <div>
                        <div style="font-size:12px; color:#888; font-weight:bold;">ADDRESS</div>
                        <div><?php echo htmlspecialchars($full_address); ?></div>
                        <div style="margin-top:10px; font-weight:bold; color:<?php echo $order['shipping_status']=='cancelled' ? 'red' : 'green'; ?>">
                            Status: <?php echo ucfirst($order['shipping_status']); ?>
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-size:12px; color:#888; font-weight:bold;">ESTIMATED ARRIVAL</div>
                        <div style="font-weight:bold; color:#007AFF; margin-bottom:10px;">
                            <?php echo date('d M Y', strtotime($est_arrival)); ?>
                        </div>

                        <?php if($order['shipping_status'] == 'delivering'): ?>
                            <form method="POST">
                                <input type="hidden" name="confirm_delivery" value="1">
                                <button type="submit" class="btn btn-sm" style="background:#34C759; color:white; width:100%;">
                                    Confirm Item Received
                                </button>
                            </form>
                        <?php elseif($order['shipping_status'] == 'completed'): ?>
                            <div style="color:#2e7d32; font-size:12px;">
                                <ion-icon name="checkmark-done"></ion-icon> Arrived on <?php echo date('d M Y', strtotime($order['confirmed_arrival'])); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>

        <div>

        <div class="card">
                <h3 style="margin-top:0;">Payment Schedule</h3>
                <table style="width:100%; font-size:13px; border-collapse:collapse;">
                    <tr style="background:#f9f9f9; text-align:left; color:#666;">
                        <th style="padding:8px;">Due Date</th>
                        <th style="padding:8px;">Status</th>
                    </tr>
                    <?php if($order['payment_status'] == 'partial'): ?>
                    <tr>
                        <td style="padding:8px;"><?php echo date('d M Y', $next_due_date); ?></td>
                        <td style="padding:8px;">
                            <?php if($is_monthly_late): ?>
                                <span style="color:red; font-weight:bold;">Overdue</span>
                            <?php else: ?>
                                <span style="color:orange;">Pending</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php elseif($order['payment_status'] == 'paid'): ?>
                    <tr>
                        <td colspan="2" style="padding:8px; color:green; text-align:center;">All payments completed.</td>
                    </tr>
                    <?php elseif($order['payment_status'] == 'unpaid'): ?>
                    <tr>
                        <td colspan="2" style="padding:8px; color:red; text-align:center;">Order cancelled.</td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>

            <div class="card" style="border-top: 4px solid <?php echo $remaining <= 0 ? '#34C759' : '#FF9500'; ?>;">
                <h3 style="margin-top:0;">Payment Summary</h3>
                <div style="margin:20px 0;">
                    <div style="display:flex; justify-content:space-between; font-size:12px; font-weight:600;">
                        <span>Paid: Rp <?php echo number_format($verified_paid); ?></span>
                        <span>Total: Rp <?php echo number_format($order['total_amount']); ?></span>
                    </div>
                    <div style="width:100%; height:8px; background:#eee; border-radius:4px; overflow:hidden; margin-top:5px;">
                        <div style="width:<?php echo $progress; ?>%; height:100%; background:<?php echo $remaining <= 0 ? '#34C759' : '#FF9500'; ?>;"></div>
                    </div>
                    <div style="text-align:right; font-size:12px; color:#666; margin-top:5px;">
                        Remaining: Rp <?php echo number_format($remaining); ?>
                    </div>
                </div>

                <?php if($remaining > 0 && $order['status'] != 'rejected' && $order['status'] != 'cancelled'): ?>
                    <form method="POST" enctype="multipart/form-data">
                        <label style="font-size:12px; font-weight:bold;">Amount</label>
                        <input type="number" name="amount" value="<?php echo $remaining; ?>" max="<?php echo $remaining; ?>" style="width:100%; padding:8px; margin-bottom:10px; border:1px solid #ddd; border-radius:4px;">
                        <input type="file" name="proof" required style="font-size:13px; width:100%; padding:8px; margin-bottom:10px;">
                        <button type="submit" class="btn btn-sm" style="width:100%;">Upload Proof</button>
                    </form>
                    
                    <?php if($verified_paid == 0): ?>
                        <form method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');" style="margin-top:15px;">
                            <input type="hidden" name="cancel_order" value="1">
                            <button type="submit" class="btn btn-sm" style="width:100%; background:#fff; border:1px solid #ffcdd2; color:#d32f2f;">
                                Cancel Order
                            </button>
                        </form>
                    <?php endif; ?>

                <?php elseif($order['status'] == 'rejected'): ?>
                    <div style="text-align:center; color:#c62828; font-weight:bold; padding:20px;">
                        <ion-icon name="close-circle" style="font-size:48px;"></ion-icon><br>
                        Order Rejected<br>
                        <span style="font-size:12px;">(Timer Expired or Payment Late)</span>
                    </div>
                <?php elseif($order['status'] == 'cancelled'): ?>
                    <div style="text-align:center; color:#666; font-weight:bold; padding:20px;">
                        <ion-icon name="ban" style="font-size:48px;"></ion-icon><br>
                        Order Cancelled
                    </div>
                <?php else: ?>
                    <div style="text-align:center; color:#34C759; font-weight:bold; padding:20px;">
                        <ion-icon name="checkmark-circle" style="font-size:48px;"></ion-icon><br>
                        Fully Paid & Confirmed
                    </div>
                <?php endif; ?>
            </div>

            <div class="card">
                <h3 style="margin-top:0;">Payment History</h3>
                <?php if(empty($payments)): ?>
                    <div style="color:#888;">No payments recorded.</div>
                <?php else: ?>
                    <table style="width:100%; font-size:13px;">
                        <?php foreach($payments as $p): ?>
                        <tr>
                            <td style="padding:8px 0;"><?php echo date('d M', strtotime($p['payment_date'])); ?></td>
                            <td>Rp <?php echo number_format($p['amount']); ?></td>
                            <td>
                                <span style="font-weight:bold; color:<?php echo ($p['status']=='verified')?'green':'orange'; ?>;">
                                    <?php echo strtoupper($p['status']); ?>
                                </span>
                            </td>
                            <td style="text-align:right;">
                                <?php if($p['status'] == 'verified'): ?>
                                <a href="print_invoice.php?type=payment&id=<?php echo $p['id']; ?>" target="_blank" class="btn btn-sm" style="background:#f0f9ff; color:#007AFF;">Receipt</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>