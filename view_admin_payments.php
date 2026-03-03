<?php
// view_admin_payments.php - DEDICATED PAYMENT HISTORY & EDITOR
if (($_SESSION['role'] ?? '') !== 'admin') die("Access Denied");
$pdo = getDB();

// --- HANDLE POST ACTIONS ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_payment'])) {
    $pid = $_POST['payment_id'];
    $oid = $_POST['order_id'];
    $new_amount = (float)$_POST['amount'];
    $new_status = $_POST['status'];

    // Get current state
    $curr = $pdo->prepare("SELECT amount, status FROM order_payments WHERE id=?");
    $curr->execute([$pid]);
    $curr_pay = $curr->fetch();

    if ($curr_pay) {
        // 1. Update Payment Record
        $pdo->prepare("UPDATE order_payments SET amount=?, status=? WHERE id=?")->execute([$new_amount, $new_status, $pid]);

        // 2. Adjust Order's Paid Amount Dynamically
        $paid_adjustment = 0;
        if ($curr_pay['status'] === 'verified' && $new_status === 'verified') {
            $paid_adjustment = $new_amount - $curr_pay['amount']; // Changed amount
        } elseif ($curr_pay['status'] !== 'verified' && $new_status === 'verified') {
            $paid_adjustment = $new_amount; // Newly verified
        } elseif ($curr_pay['status'] === 'verified' && $new_status !== 'verified') {
            $paid_adjustment = -$curr_pay['amount']; // Un-verified
        }

        if ($paid_adjustment != 0) {
            $pdo->prepare("UPDATE orders SET paid_amount = paid_amount + ? WHERE id=?")->execute([$paid_adjustment, $oid]);
        }

        // 3. Update Order's Payment Status
        $chk = $pdo->query("SELECT total_amount, paid_amount FROM orders WHERE id=$oid")->fetch();
        $new_pay_status = ($chk['paid_amount'] >= $chk['total_amount'] - 0.01) ? 'paid' : ($chk['paid_amount'] > 0 ? 'partial' : 'unpaid');
        $pdo->prepare("UPDATE orders SET payment_status = ? WHERE id = ?")->execute([$new_pay_status, $oid]);

        echo "<script>alert('Payment updated successfully.'); window.location.href='?page=admin_payments';</script>";
        exit;
    }
}

// Fetch All Payments & Order Data
$payments = $pdo->query("
    SELECT op.*, o.id as order_id, o.total_amount, o.paid_amount, o.created_at as order_date, u.name as user_name 
    FROM order_payments op 
    JOIN orders o ON op.order_id = o.id 
    JOIN users u ON o.user_id = u.id 
    ORDER BY op.payment_date DESC
")->fetchAll();
?>

<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2 style="margin:0;">💳 Payment History & Manager</h2>
        <div style="display:flex; gap:10px;">
            <input type="text" id="pay-search" placeholder="Search Order ID, Name..." onkeyup="filterPayments()" style="padding:8px; border-radius:4px; border:1px solid #ccc; font-size:14px;">
            <select id="pay-sort" onchange="sortPayments()" style="padding:8px; border-radius:4px; border:1px solid #ccc;">
                <option value="date_desc">Newest Payments</option>
                <option value="date_asc">Oldest Payments</option>
                <option value="amount_desc">Highest Amount</option>
                <option value="amount_asc">Lowest Amount</option>
            </select>
        </div>
    </div>

    <div class="responsive-table">
        <table style="width:100%; border-collapse:collapse; font-size:14px;">
            <thead>
                <tr style="background:#f5f5f7; text-align:left; color:#666;">
                    <th style="padding:12px;">Date</th>
                    <th style="padding:12px;">Order & Client</th>
                    <th style="padding:12px;">Proof</th>
                    <th style="padding:12px;">Amount & Status</th>
                    <th style="padding:12px; text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody id="payment-tbody">
                <?php foreach($payments as $p): 
                    $timestamp = strtotime($p['payment_date']);
                    $remaining = max(0, $p['total_amount'] - $p['paid_amount']);
                    
                    // Generate 12-Month Schedule for this order
                    $installment = floor($p['total_amount'] / 12);
                    $accum = 0;
                    $ctime = strtotime($p['order_date']);
                    $schedule_html = "";
                    for ($i=1; $i<=12; $i++) {
                        $this_inst = ($i==12) ? ($p['total_amount'] - ($installment*11)) : $installment;
                        $accum += $this_inst;
                        $due = ($i==1) ? $ctime + 86400 : $ctime + (($i-1)*30*86400);
                        
                        if ($p['paid_amount'] >= $accum - 0.01) { $st = 'Paid'; $col = 'green'; }
                        elseif ($p['paid_amount'] > ($accum - $this_inst)) { $st = 'Partial'; $col = 'orange'; }
                        else { $st = (time() > $due) ? 'Overdue' : 'Pending'; $col = (time() > $due) ? 'red' : '#666'; }
                        
                        $schedule_html .= "<tr><td>Month $i</td><td>Rp ".number_format($this_inst)."</td><td>".date('d M Y', $due)."</td><td style='color:$col; font-weight:bold;'>$st</td></tr>";
                    }
                ?>
                <tr class="payment-row" 
                    data-search="<?php echo strtolower($p['order_id'] . ' ' . $p['user_name'] . ' ' . $p['status']); ?>"
                    data-time="<?php echo $timestamp; ?>"
                    data-amount="<?php echo $p['amount']; ?>"
                    style="border-bottom:1px solid #eee;">
                    
                    <td style="padding:12px;"><?php echo date('d M Y, H:i', $timestamp); ?></td>
                    <td style="padding:12px;">
                        <a href="?page=admin_order_detail&id=<?php echo $p['order_id']; ?>" style="font-weight:bold; color:#007AFF; text-decoration:none;">#<?php echo $p['order_id']; ?></a><br>
                        <?php echo htmlspecialchars($p['user_name']); ?><br>
                        <span style="font-size:11px; color:#888;">Order Total: Rp <?php echo number_format($p['total_amount']); ?> | <strong style="color:#d32f2f;">Rem: Rp <?php echo number_format($remaining); ?></strong></span>
                    </td>
                    <td style="padding:12px;">
                        <a href="uploads/<?php echo htmlspecialchars($p['proof_image']); ?>" target="_blank" style="color:#34C759; font-weight:bold; font-size:12px;"><ion-icon name="image"></ion-icon> View Image</a>
                    </td>
                    
                    <td colspan="2" style="padding:12px; text-align:right;">
                        <form method="POST" style="display:flex; gap:5px; justify-content:flex-end; align-items:center;">
                            <input type="hidden" name="payment_id" value="<?php echo $p['id']; ?>">
                            <input type="hidden" name="order_id" value="<?php echo $p['order_id']; ?>">
                            
                            <input type="number" name="amount" value="<?php echo $p['amount']; ?>" style="width:120px; padding:6px; border:1px solid #ccc; border-radius:4px;">
                            
                            <select name="status" style="padding:6px; border:1px solid #ccc; border-radius:4px; font-weight:bold; color:<?php echo $p['status']=='verified'?'green':($p['status']=='rejected'?'red':'orange'); ?>;">
                                <option value="pending" <?php echo $p['status']=='pending'?'selected':''; ?>>Pending</option>
                                <option value="verified" <?php echo $p['status']=='verified'?'selected':''; ?>>Verified</option>
                                <option value="rejected" <?php echo $p['status']=='rejected'?'selected':''; ?>>Rejected</option>
                            </select>
                            
                            <button type="submit" name="save_payment" class="btn btn-sm" style="padding:6px 12px;">Save</button>
                            <button type="button" onclick="document.getElementById('sched-<?php echo $p['id']; ?>').style.display = document.getElementById('sched-<?php echo $p['id']; ?>').style.display === 'none' ? 'table-row' : 'none';" class="btn btn-sm btn-outline" style="padding:6px 10px;" title="View Schedule"><ion-icon name="calendar"></ion-icon></button>
                        </form>
                    </td>
                </tr>
                <tr id="sched-<?php echo $p['id']; ?>" style="display:none; background:#fcfcfc;">
                    <td colspan="5" style="padding:15px;">
                        <div style="font-weight:bold; margin-bottom:10px;">12-Month Schedule (Order #<?php echo $p['order_id']; ?>)</div>
                        <table style="width:100%; font-size:12px; background:white; border:1px solid #eee; border-collapse:collapse;">
                            <tr style="background:#eee;">
                                <th style="padding:5px;">Term</th><th style="padding:5px;">Amount</th><th style="padding:5px;">Due Date</th><th style="padding:5px;">Status</th>
                            </tr>
                            <?php echo $schedule_html; ?>
                        </table>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function filterPayments() {
    const query = document.getElementById('pay-search').value.toLowerCase();
    const rows = document.querySelectorAll('.payment-row');
    rows.forEach(row => {
        row.style.display = row.getAttribute('data-search').includes(query) ? '' : 'none';
        // Hide schedules when searching
        const nextRow = row.nextElementSibling;
        if(nextRow && nextRow.id.startsWith('sched-')) nextRow.style.display = 'none'; 
    });
}

function sortPayments() {
    const val = document.getElementById('pay-sort').value;
    const tbody = document.getElementById('payment-tbody');
    const rows = Array.from(tbody.querySelectorAll('.payment-row'));

    rows.sort((a, b) => {
        if(val === 'date_desc') return b.getAttribute('data-time') - a.getAttribute('data-time');
        if(val === 'date_asc') return a.getAttribute('data-time') - b.getAttribute('data-time');
        if(val === 'amount_desc') return b.getAttribute('data-amount') - a.getAttribute('data-amount');
        if(val === 'amount_asc') return a.getAttribute('data-amount') - b.getAttribute('data-amount');
    });

    // Re-append main row and its schedule child
    rows.forEach(row => {
        tbody.appendChild(row);
        const schedRow = document.getElementById('sched-' + row.querySelector('input[name="payment_id"]').value);
        if(schedRow) tbody.appendChild(schedRow);
    });
}
</script>