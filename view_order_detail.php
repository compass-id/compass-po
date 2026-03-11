<?php
// view_order_detail.php - DYNAMIC 12 MONTHS (MANUAL DP & DECREASING AMOUNTS)
if (!isset($_SESSION['user_id'])) echo "<script>window.location='?page=login';</script>";
$pdo = getDB();
$uid = $_SESSION['user_id'];
$oid = $_GET['id'] ?? 0;

// --- 1. HANDLE POST ACTIONS ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // ACTION: Cancel Order
    if (isset($_POST['cancel_order'])) {
        $pdo->prepare("UPDATE orders SET status = 'cancelled', shipping_status = 'cancelled' WHERE id = ? AND user_id = ? AND paid_amount = 0")
            ->execute([$oid, $uid]);
        echo "<script>alert('Order has been cancelled.'); window.location.href='?page=order_detail&id=$oid';</script>";
        exit;
    }

    // ACTION: Confirm Delivery WITH PROOF
    if (isset($_POST['confirm_delivery'])) {
        $proof_img = null;
        if (isset($_FILES['arrival_proof']) && $_FILES['arrival_proof']['error'] === UPLOAD_ERR_OK) {
            if (!is_dir('uploads')) {
                mkdir('uploads', 0777, true);
            }
            $ext = pathinfo($_FILES['arrival_proof']['name'], PATHINFO_EXTENSION);
            $proof_img = "arrival_" . $oid . "_" . time() . "." . ($ext ?: 'jpg');
            
            if(move_uploaded_file($_FILES['arrival_proof']['tmp_name'], "uploads/" . $proof_img)) {
                $pdo->prepare("UPDATE orders SET shipping_status = 'completed', confirmed_arrival = NOW(), arrival_proof = ? WHERE id = ? AND user_id = ?")
                    ->execute([$proof_img, $oid, $uid]);
                echo "<script>alert('Thank you! Item arrival confirmed.'); window.location.href='?page=order_detail&id=$oid';</script>";
                exit;
            } else {
                echo "<script>alert('Failed to save file. Check folder permissions.');</script>";
            }
        } else {
            $err = $_FILES['arrival_proof']['error'] ?? 'Unknown';
            echo "<script>alert('Upload failed or no file selected. Error code: $err');</script>";
        }
    }

    // ACTION: Upload Payment Proof (Handles DP, Checklist, and Manual Inputs)
    if (isset($_FILES['proof'])) {
        if ($_FILES['proof']['error'] === UPLOAD_ERR_OK) {
            if (!is_dir('uploads')) {
                mkdir('uploads', 0777, true);
            }
            $ext = pathinfo($_FILES['proof']['name'], PATHINFO_EXTENSION);
            $filename = "proof_" . $oid . "_" . time() . "." . ($ext ?: 'jpg');
            $amount = (float)($_POST['amount'] ?? 0);
            
            if ($amount > 0) {
                if (move_uploaded_file($_FILES['proof']['tmp_name'], "uploads/" . $filename)) {
                    $pdo->prepare("INSERT INTO order_payments (order_id, amount, proof_image, status, payment_date) VALUES (?, ?, ?, 'pending', NOW())")->execute([$oid, $amount, $filename]);
                    
                    // Set to partial to stop the initial 24h timer cancellation
                    $pdo->prepare("UPDATE orders SET payment_status = 'partial' WHERE id = ?")->execute([$oid]);
                    echo "<script>alert('Proof uploaded! Waiting for verification.'); window.location.href='?page=order_detail&id=$oid';</script>";
                } else {
                    echo "<script>alert('Failed to save uploaded file. Check directory permissions.'); window.location.href='?page=order_detail&id=$oid';</script>";
                }
            } else {
                echo "<script>alert('Please input a valid amount to pay.'); window.location.href='?page=order_detail&id=$oid';</script>";
            }
        } else {
            $err = $_FILES['proof']['error'];
            echo "<script>alert('Upload error code: $err. Check file size limits.'); window.location.href='?page=order_detail&id=$oid';</script>";
        }
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

// Fetch Items
$stmtItems = $pdo->prepare("SELECT oi.*, b.title, b.cover_image FROM order_items oi JOIN books b ON oi.book_id = b.id WHERE oi.order_id = ?");
$stmtItems->execute([$oid]);
$items = $stmtItems->fetchAll();

// Fetch Payments & Calculate Verified Totals
$payments = $pdo->query("SELECT * FROM order_payments WHERE order_id = $oid ORDER BY payment_date DESC")->fetchAll();
$verified_paid = 0; 
$pending_payments = 0;
foreach ($payments as $p) {
    if ($p['status'] == 'verified') $verified_paid += $p['amount'];
    if ($p['status'] == 'pending') $pending_payments += $p['amount'];
}
$remaining = $order['total_amount'] - $verified_paid;
if($remaining < 0) $remaining = 0;
$progress = ($order['total_amount'] > 0) ? ($verified_paid / $order['total_amount']) * 100 : 0;


// --- 3. DYNAMIC 12-MONTH SCHEDULE LOGIC ---
$created_time = strtotime($order['created_at']);
$now = time();
$schedule = [];
$next_deadline = null; 
$is_cancelled = in_array($order['status'], ['cancelled', 'rejected']);

// Strict Initial 24h Rule Check (For First Payment / DP)
$initial_deadline = $created_time + (24 * 60 * 60);
if ($now > $initial_deadline && $verified_paid <= 0 && $order['payment_status'] !== 'partial' && !$is_cancelled) {
    $pdo->prepare("UPDATE orders SET status = 'rejected', payment_status = 'unpaid', shipping_status = 'cancelled' WHERE id = ?")->execute([$oid]);
    $order['status'] = 'rejected';
    $order['payment_status'] = 'unpaid';
    $order['shipping_status'] = 'cancelled';
    $is_cancelled = true;
}

// DP Check & Calculation
// If $verified_paid == 0, DP hasn't been paid. 
// If paid, the remaining balance is dynamically split across the 12 months.
if ($verified_paid == 0) {
    $monthly_amount = $order['total_amount'] / 12; // Base preview amount
    $next_deadline = $initial_deadline;
} else {
    $monthly_amount = $remaining / 12; // Spread remaining evenly to decrease installment amounts
}

for ($i = 1; $i <= 12; $i++) {
    // Due Dates: Month 1-12 are +30, 60, 90... days after the initial 24h DP window.
    $due_date = $initial_deadline + ($i * 30 * 24 * 60 * 60);
    
    $month_remaining = $monthly_amount;

    if ($is_cancelled) {
        $st = 'Cancelled';
        $col = 'red';
    } elseif ($verified_paid == 0) {
        $st = 'Awaiting DP';
        $col = '#999';
    } elseif ($remaining <= 0) {
        $st = 'Paid';
        $col = 'green';
        $month_remaining = 0;
    } else {
        $st = ($now > $due_date) ? 'Overdue' : 'Pending';
        $col = ($now > $due_date) ? 'red' : 'orange';
        
        if (!$next_deadline && $now <= $due_date) $next_deadline = $due_date;
        if (!$next_deadline && $st == 'Overdue') $next_deadline = $due_date;
    }

    $schedule[] = [
        'month' => $i,
        'due_date' => $due_date,
        'status' => $st,
        'color' => $col,
        'remaining_amount' => $month_remaining
    ];
}

// Address Snapshot
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
                $st = $order['status'] ?? 'pending';
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

    <?php if(!$is_cancelled && $remaining > 0 && isset($next_deadline)): ?>
        <?php $time_left = $next_deadline - $now; ?>
        <div class="card" style="background:<?php echo $time_left > 0 ? '#fff3e0' : '#ffebee'; ?>; border:1px solid <?php echo $time_left > 0 ? '#ffe0b2' : '#ffcdd2'; ?>; text-align:center; padding:15px; margin-bottom:20px;">
            <div style="font-weight:bold; color:<?php echo $time_left > 0 ? '#ef6c00' : '#c62828'; ?>; font-size:16px; margin-bottom:5px;">
                <ion-icon name="time-outline" style="vertical-align:middle;"></ion-icon> Next Payment Deadline
            </div>
            <?php if($time_left > 0): ?>
                <div style="color:#555; font-size:13px; margin-bottom:10px;">
                    Please complete your next scheduled payment before the timer runs out.
                </div>
                <div id="next-countdown" style="font-size:24px; font-weight:800; color:#d84315;"></div>
                <script>
                    var nextTimeLeft = <?php echo $time_left; ?>;
                    var timerId = setInterval(function(){
                        if(nextTimeLeft <= 0){ clearInterval(timerId); window.location.reload(); } 
                        else {
                            var d = Math.floor(nextTimeLeft / 86400);
                            var h = Math.floor((nextTimeLeft % 86400) / 3600);
                            var m = Math.floor((nextTimeLeft % 3600) / 60);
                            var s = Math.floor(nextTimeLeft % 60);
                            var displayStr = (d > 0 ? d + "d " : "") + h + "h " + m + "m " + s + "s";
                            document.getElementById("next-countdown").innerHTML = displayStr;
                            nextTimeLeft--;
                        }
                    }, 1000);
                </script>
            <?php else: ?>
                <div style="font-size:18px; font-weight:800; color:#c62828;">STATUS: OVERDUE</div>
            <?php endif; ?>
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
                        <div style="margin-top:10px; font-weight:bold; color:<?php echo ($order['shipping_status'] ?? '')=='cancelled' ? 'red' : 'green'; ?>">
                            Status: <?php echo ucfirst($order['shipping_status'] ?? 'Processing'); ?>
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-size:12px; color:#888; font-weight:bold;">ESTIMATED ARRIVAL</div>
                        <div style="font-weight:bold; color:#007AFF; margin-bottom:10px;">
                            <?php echo date('d M Y', strtotime($est_arrival)); ?>
                        </div>

                        <?php if(in_array(($order['shipping_status'] ?? ''), ['delivering', 'shipping'])): ?>
                            <form method="POST" enctype="multipart/form-data" style="background:#f9f9f9; padding:15px; border-radius:8px; text-align:left;">
                                <label style="font-size:12px; font-weight:bold; display:block; margin-bottom:8px;">Upload Arrival Proof (Required)</label>
                                <input type="file" name="arrival_proof" required style="font-size:12px; width:100%; margin-bottom:10px; padding:5px; border:1px solid #ddd; border-radius:4px; background:white;">
                                <input type="hidden" name="confirm_delivery" value="1">
                                <button type="submit" class="btn btn-sm" style="background:#34C759; color:white; width:100%;">
                                    <ion-icon name="checkmark-done-circle"></ion-icon> Confirm Item Received
                                </button>
                            </form>
                        <?php elseif(($order['shipping_status'] ?? '') == 'completed'): ?>
                            <div style="color:#2e7d32; font-size:12px;">
                                <ion-icon name="checkmark-done"></ion-icon> Arrived on <?php echo date('d M Y', strtotime($order['confirmed_arrival'])); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="card">
                <h3 style="margin-top:0;">Dynamic 12-Month Schedule</h3>
                <div style="font-size:12px; color:#666; margin-bottom:15px;">
                    Any manual payments or down payments strictly reduce your monthly installments evenly over the remaining months. 12 terms are maintained.
                </div>
                <table style="width:100%; font-size:13px; border-collapse:collapse;">
                    <tr style="background:#f9f9f9; text-align:left; color:#666;">
                        <th style="padding:8px;">Term</th>
                        <th style="padding:8px;">Current Installment</th>
                        <th style="padding:8px;">Due Date</th>
                        <th style="padding:8px;">Status</th>
                    </tr>
                    <?php foreach($schedule as $s): ?>
                    <tr style="border-bottom:1px solid #eee;">
                        <td style="padding:10px 8px; font-weight:bold;">Month <?php echo $s['month']; ?></td>
                        <td style="padding:10px 8px; color:var(--primary); font-weight:bold;">Rp <?php echo number_format($s['remaining_amount']); ?></td>
                        <td style="padding:10px 8px; color:#555;"><?php echo date('d M Y', $s['due_date']); ?></td>
                        <td style="padding:10px 8px; font-weight:bold; color:<?php echo $s['color']; ?>;">
                            <?php echo $s['status']; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>

        </div>

        <div>
            <div class="card" style="border-top: 4px solid <?php echo $remaining <= 0 ? '#34C759' : '#FF9500'; ?>;">
                <h3 style="margin-top:0;">Payment Summary</h3>
                <div style="margin:20px 0;">
                    <div style="display:flex; justify-content:space-between; font-size:12px; font-weight:600;">
                        <span>Verified Paid: Rp <?php echo number_format($verified_paid); ?></span>
                        <span>Total: Rp <?php echo number_format($order['total_amount']); ?></span>
                    </div>
                    <div style="width:100%; height:8px; background:#eee; border-radius:4px; overflow:hidden; margin-top:5px;">
                        <div style="width:<?php echo $progress; ?>%; height:100%; background:<?php echo $remaining <= 0 ? '#34C759' : '#FF9500'; ?>;"></div>
                    </div>
                    <div style="text-align:right; font-size:12px; color:#666; margin-top:5px;">
                        Remaining: Rp <?php echo number_format($remaining); ?>
                    </div>
                    
                    <?php if($pending_payments > 0): ?>
                        <div style="background:#fff3e0; color:#ef6c00; font-size:12px; padding:10px; border-radius:4px; margin-top:10px; text-align:center;">
                            <ion-icon name="time-outline" style="vertical-align:middle;"></ion-icon> 
                            Rp <?php echo number_format($pending_payments); ?> pending verification.
                        </div>
                    <?php endif; ?>
                </div>

                <?php if($remaining > 0 && !$is_cancelled): ?>
                    
                    <div style="background:#f0f9ff; border:1px solid #bce8f1; color:#31708f; padding:15px; border-radius:8px; font-size:13px; margin-bottom:20px; text-align:center;">
                        <ion-icon name="card-outline" style="font-size: 18px; vertical-align: middle; margin-right: 5px;"></ion-icon>
                        <strong>Payment Method: Bank Transfer</strong><br>
                        PT Solusi Edukasi Gemilang<br>
                        Account No: <strong>4685015898</strong>
                    </div>

                    <?php if($verified_paid == 0): ?>
                        <form method="POST" enctype="multipart/form-data" style="background:#f9f9f9; padding:15px; border-radius:8px;">
                            <h4 style="margin-top:0; color:#333;"><ion-icon name="wallet-outline"></ion-icon> Initial Down Payment</h4>
                            <p style="font-size:12px; color:#666; margin-bottom:15px;">Please enter your DP amount in Rupiah to begin your installment schedule.</p>
                            
                            <label style="font-size:12px; font-weight:bold; display:block; margin-bottom:5px;">Manual DP Amount (Rp)</label>
                            <input type="number" id="manual-amount-input" name="amount" required min="10000" max="<?php echo $remaining; ?>" style="width:100%; padding:10px; border-radius:4px; border:1px solid #ccc; margin-bottom:15px;" placeholder="e.g. 500000" oninput="checkUploadBtn()">
                            
                            <label style="font-size:12px; font-weight:bold; display:block; margin-bottom:5px;">Upload Proof of Payment</label>
                            <input type="file" name="proof" required style="font-size:13px; width:100%; padding:8px; margin-bottom:10px; background:white; border:1px solid #ddd; border-radius:4px;">
                            
                            <button type="submit" class="btn btn-sm" id="upload-btn" disabled style="width:100%; background:#007AFF; color:white;">Submit DP & Proof</button>
                        </form>
                    <?php else: ?>
                        <form method="POST" enctype="multipart/form-data" style="background:#f9f9f9; padding:15px; border-radius:8px;">
                            <h4 style="margin-top:0; color:#333;"><ion-icon name="cash-outline"></ion-icon> Pay Installment</h4>
                            
                            <label style="font-size:12px; font-weight:bold; display:block; margin-bottom:5px;">Select Months to Pay</label>
                            <div style="max-height: 180px; overflow-y: auto; background: #fff; border: 1px solid #ddd; padding: 10px; border-radius: 4px; margin-bottom: 5px;">
                                <?php foreach($schedule as $s): ?>
                                    <?php if($s['remaining_amount'] > 0): ?>
                                        <div style="display:flex; justify-content:space-between; padding:5px 0; border-bottom:1px solid #eee;">
                                            <label style="display:flex; align-items:center; gap:8px; cursor:pointer; width:100%;">
                                                <input type="checkbox" class="month-checkbox" value="<?php echo $s['remaining_amount']; ?>" onchange="calculateTotal()">
                                                <span>Month <?php echo $s['month']; ?></span>
                                                <span style="margin-left:auto; font-weight:bold; color:var(--primary);">Rp <?php echo number_format($s['remaining_amount']); ?></span>
                                            </label>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>

                            <div style="font-size:13px; font-weight:800; text-align:right; margin-bottom:15px; color:#333;">
                                Selected: Rp <span id="selected-total-display">0</span>
                            </div>

                            <div style="text-align:center; font-size:12px; font-weight:bold; color:#888; margin-bottom:15px;">— OR —</div>

                            <label style="font-size:12px; font-weight:bold; display:block; margin-bottom:5px;">Input Manual Amount (Rp)</label>
                            <input type="number" id="manual-amount-input" name="amount" min="10000" max="<?php echo $remaining; ?>" style="width:100%; padding:10px; border-radius:4px; border:1px solid #ccc; margin-bottom:15px;" placeholder="e.g. 150000" oninput="overrideChecklists()">

                            <label style="font-size:12px; font-weight:bold; display:block; margin-bottom:5px;">Upload Proof of Payment</label>
                            <input type="file" name="proof" required style="font-size:13px; width:100%; padding:8px; margin-bottom:10px; background:white; border:1px solid #ddd; border-radius:4px;">
                            
                            <button type="submit" class="btn btn-sm" style="width:100%;" id="upload-btn" disabled>Pay Amount</button>
                        </form>
                    <?php endif; ?>
                    
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
                                <span style="font-weight:bold; color:<?php echo ($p['status']=='verified')?'green':($p['status']=='rejected'?'red':'orange'); ?>;">
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

<script>
// Unifies Checklist Checkboxes with Manual Input field
function calculateTotal() {
    let total = 0;
    const checkboxes = document.querySelectorAll('.month-checkbox');
    checkboxes.forEach(function(cb) {
        if(cb.checked) {
            total += parseFloat(cb.value);
        }
    });
    
    // Update Display
    const displayElem = document.getElementById('selected-total-display');
    if (displayElem) displayElem.innerText = total.toLocaleString('id-ID');
    
    // Sync with Manual Input
    const manualInput = document.getElementById('manual-amount-input');
    if (manualInput) {
        manualInput.value = total > 0 ? total : '';
    }
    
    checkUploadBtn();
}

// Clears Checkboxes when User Types Manually
function overrideChecklists() {
    const checkboxes = document.querySelectorAll('.month-checkbox');
    checkboxes.forEach(function(cb) {
        cb.checked = false;
    });
    
    const displayElem = document.getElementById('selected-total-display');
    if (displayElem) displayElem.innerText = '0';
    
    checkUploadBtn();
}

function checkUploadBtn() {
    const manualInput = document.getElementById('manual-amount-input');
    const total = manualInput ? (parseFloat(manualInput.value) || 0) : 0;
    document.getElementById('upload-btn').disabled = (total <= 0);
}
</script>