<?php
// view_profile.php - FULLY RESTORED WITH ADDRESS NOTIFICATION
if (!isset($_SESSION['user_id'])) echo "<script>window.location='?page=login';</script>";
$pdo = getDB();
$uid = $_SESSION['user_id'];

// --- HANDLE PROFILE UPDATE ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $inst = $_POST['institution'];
    $pos = $_POST['position'];
    
    $stmt = $pdo->prepare("UPDATE users SET name=?, email=?, phone=?, institution=?, position=? WHERE id=?");
    $stmt->execute([$name, $email, $phone, $inst, $pos, $uid]);
    echo "<script>alert('Profile updated successfully!'); window.location='?page=profile';</script>";
    exit;
}

// 1. Fetch User Data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$uid]);
$user = $stmt->fetch();

if (!$user) {
    session_destroy();
    echo "<script>alert('Session expired. Please login again.'); window.location='?page=login';</script>";
    exit;
}

// Check Address Count (For Notification Banner)
$addr_count = $pdo->query("SELECT COUNT(*) FROM user_addresses WHERE user_id=$uid")->fetchColumn();

// 2. Fetch Orders & Tickets
$orders = $pdo->query("SELECT * FROM orders WHERE user_id=$uid ORDER BY created_at DESC")->fetchAll();
$tickets = $pdo->query("SELECT * FROM tickets WHERE user_id=$uid ORDER BY created_at DESC")->fetchAll();

// 3. FILTER UNPAID ORDERS FOR NOTIFICATIONS
$unpaid_orders = [];
$server_time = time();

foreach ($orders as $o) {
    if ($o['payment_status'] !== 'paid' && $o['status'] !== 'cancelled' && $o['status'] !== 'rejected') {
        $deadline = 0;
        $type = '';
        $is_urgent = false;

        // A. Initial 24h Deadline (Pending & Unpaid)
        if ($o['status'] === 'pending' && $o['paid_amount'] == 0) {
            $deadline = strtotime($o['created_at']) + (24 * 60 * 60);
            $type = 'initial';
            if ($deadline > $server_time) $is_urgent = true;
        } 
        // B. Monthly Deadline (Partial)
        elseif ($o['payment_status'] === 'partial') {
            // Simplified: Next due date is 30 days after creation (or last payment logic if available)
            // For notification purposes, we use the creation date + 30 days logic as baseline
            $deadline = strtotime($o['created_at']) + (30 * 60 * 60 * 24); 
            $type = 'monthly';
            // Mark urgent if due within 3 days
            if (($deadline - $server_time) < (3 * 24 * 60 * 60)) $is_urgent = true;
        }

        // Only add if there is a valid future or relevant deadline
        if ($deadline > 0) {
            $unpaid_orders[] = [
                'id' => $o['id'],
                'amount' => $o['total_amount'] - $o['paid_amount'],
                'method' => $o['payment_status'],
                'deadline' => $deadline,
                'type' => $type,
                'urgent' => $is_urgent
            ];
        }
    }
}
?>

<h2 style="margin-top:0;">My Dashboard</h2>

<?php if ($addr_count == 0): ?>
<div class="card" style="background:#fff3e0; border:1px solid #ffe0b2; color:#ef6c00; display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; padding:15px;">
    <div style="display:flex; align-items:center; gap:10px;">
        <ion-icon name="warning" style="font-size:24px;"></ion-icon>
        <div>
            <strong>Shipping Address Missing</strong><br>
            <span style="font-size:13px;">Please add a shipping address to enable order placements and deliveries.</span>
        </div>
    </div>
    <a href="?page=profile_settings" class="btn btn-sm" style="background:#ef6c00; color:white; text-decoration:none;">Add Address</a>
</div>
<?php endif; ?>

<div class="dashboard-grid" style="margin-bottom: 30px;">
    <div class="card">
        <div style="display:flex; align-items:center; gap:15px; margin-bottom:15px;">
            <div style="width:50px; height:50px; background:var(--primary); color:white; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:24px; font-weight:bold;">
                <?php echo strtoupper(substr($user['name'] ?? 'U', 0, 1)); ?>
            </div>
            <div>
                <div style="font-weight:700; font-size:18px;"><?php echo htmlspecialchars($user['name']); ?></div>
                <div style="color:#666; font-size:14px;"><?php echo htmlspecialchars($user['email']); ?></div>
            </div>
        </div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
            <button onclick="document.getElementById('edit-profile-modal').style.display='flex'" class="btn btn-sm" style="background:#f5f5f7; color:#333;">Edit Profile</button>
            <a href="?page=profile_settings" class="btn btn-sm" style="background:#f5f5f7; color:#333;">Address Book</a>
            <a href="logout.php" class="btn btn-sm" style="background:#ffebee; color:#d32f2f;">Sign Out</a>
        </div>
    </div>

    <div class="card" style="background: linear-gradient(135deg, #007AFF 0%, #00C7BE 100%); color:white;">
        <h3 style="margin-top:0;">Your Status</h3>
        <div style="display:flex; justify-content:space-between; margin-top:20px;">
            <div style="text-align:center;">
                <div style="font-size:24px; font-weight:800;"><?php echo count($orders); ?></div>
                <div style="font-size:12px; opacity:0.9;">Orders</div>
            </div>
            <div style="text-align:center;">
                <div style="font-size:24px; font-weight:800;"><?php echo count($tickets); ?></div>
                <div style="font-size:12px; opacity:0.9;">Tickets</div>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($unpaid_orders)): ?>
<div class="card" style="border: 2px solid #FF9500; background: #fffdf5; margin-bottom: 30px;">
    <h3 style="margin-top:0; color:#d35400; display:flex; align-items:center; gap:8px;">
        <ion-icon name="time"></ion-icon> Payment Reminders
    </h3>
    <div class="responsive-table">
        <table style="width:100%; font-size:13px;">
            <thead>
                <tr style="text-align:left; color:#d35400;">
                    <th style="padding:10px;">Order ID</th>
                    <th>Amount Remaining</th>
                    <th>Payment Method</th>
                    <th>Deadline</th>
                    <th>Time Remaining</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($unpaid_orders as $uo): 
                    $remaining_seconds = $uo['deadline'] - $server_time;
                    $row_style = $uo['urgent'] ? "background: #fff3e0; font-weight:bold;" : "";
                ?>
                <tr style="border-bottom:1px solid #ffe0b2; <?php echo $row_style; ?>">
                    <td style="padding:12px;">#<?php echo $uo['id']; ?></td>
                    <td style="color:#c62828;">Rp <?php echo number_format($uo['amount']); ?></td>
                    <td><?php if ($uo['method'] == 'partial'): ?><span>Installment</span><?php endif; ?></td>
                    <td><?php echo date('d M Y, H:i', $uo['deadline']); ?></td>
                    <td>
                        <?php if ($uo['type'] == 'initial' && $remaining_seconds > 0): ?>
                            <span class="countdown-timer" data-time="<?php echo $remaining_seconds; ?>" style="color:#d32f2f; font-weight:800;">Calculated...</span>
                        <?php elseif ($remaining_seconds < 0): ?>
                            <span style="color:red;">Overdue</span>
                        <?php else: ?>
                            <span style="color:#f57c00;"><?php echo ceil($remaining_seconds / (60*60*24)); ?> Days Left</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="?page=order_detail&id=<?php echo $uo['id']; ?>" class="btn btn-sm" style="background:#2e7d32; color:white; padding:4px 10px;">Pay Now</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<div class="card">
    <h3>Order History</h3>
    <?php if(empty($orders)): ?>
        <p style="color:#888;">No orders found. <a href="?page=catalog">Start shopping!</a></p>
    <?php else: ?>
        <div class="responsive-table">
            <table style="width:100%; min-width:800px; font-size:13px;">
                <thead>
                    <tr style="background:#f9f9f9; color:#666; text-align:left;">
                        <th style="padding:12px;">Order ID</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Order Status</th>
                        <th>Payment Status</th>
                        <th>Delivery Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($orders as $o): ?>
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:12px; font-weight:bold;">#<?php echo $o['id']; ?></td>
                    
                    <td style="color:#666;"><?php echo date('d M Y', strtotime($o['created_at'])); ?></td>

                    <td style="font-weight:bold;">Rp <?php echo number_format($o['total_amount']); ?></td>

                    <td>
                        <span style="font-weight:bold; font-size:11px; padding:2px 8px; border-radius:4px;
                            <?php 
                                if($o['status']=='completed') echo 'background:#e8f5e9; color:green;';
                                elseif($o['status']=='cancelled' || $o['status']=='rejected') echo 'background:#ffebee; color:red;';
                                else echo 'background:#e3f2fd; color:#1565c0;';
                            ?>">
                            <?php echo strtoupper($o['status']); ?>
                        </span>
                    </td>
                    
                    <td>
                        <span style="font-weight:bold; font-size:11px; padding:2px 8px; border-radius:4px;
                            <?php 
                                if($o['payment_status']=='paid') echo 'background:#e8f5e9; color:green;';
                                elseif($o['payment_status']=='partial') echo 'background:#fff3e0; color:#ef6c00;';
                                else echo 'background:#ffebee; color:red;';
                            ?>">
                            <?php echo strtoupper($o['payment_status']); ?>
                        </span>
                    </td>

                    <td>
                        <?php 
                            $ship_status = $o['shipping_status'] ?? 'processing'; 
                            $ship_icon = 'cube-outline'; $ship_color = '#666';
                            $ship_label = ucfirst($ship_status);

                            if($ship_status == 'shipping') { 
                                $ship_icon = 'paper-plane'; 
                                $ship_color = '#1565c0'; 
                                $ship_label = 'Shipped'; 
                            }
                            if($ship_status == 'delivered' || $ship_status == 'completed') { $ship_icon = 'home'; $ship_color = 'green'; }
                            if($ship_status == 'cancelled') { $ship_icon = 'ban'; $ship_color = 'red'; }
                        ?>
                        <div style="display:flex; align-items:center; gap:5px; color:<?php echo $ship_color; ?>; font-weight:600; font-size:12px;">
                            <ion-icon name="<?php echo $ship_icon; ?>"></ion-icon> 
                            <?php echo $ship_label; ?>
                        </div>
                    </td>

                    <td>
                        <div style="display:flex; gap:5px;">
                            <a href="?page=order_detail&id=<?php echo $o['id']; ?>" class="btn btn-sm" style="padding:6px 12px; font-size:11px; background:#f5f5f7; color:#333;">
                                View
                            </a>
                            <?php if($o['payment_status'] == 'paid'): ?>
                            <a href="print_invoice.php?type=order&id=<?php echo $o['id']; ?>" target="_blank" class="btn btn-sm" style="padding:6px 10px; font-size:11px; background:#333; color:white;" title="Print Invoice">
                                <ion-icon name="print"></ion-icon>
                            </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<div class="card" style="margin-top:20px;">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h3>My Support Tickets</h3>
        <a href="?page=support" class="btn btn-sm">New Ticket</a>
    </div>
    
    <?php if(count($tickets) > 0): ?>
    <div class="responsive-table">
        <table style="width:100%; margin-top:15px; font-size:14px;">
            <tr style="background:#f9f9f9; color:#666; text-align:left;">
                <th style="padding:10px;">Subject</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
            <?php foreach($tickets as $t): ?>
            <tr style="border-bottom:1px solid #eee;">
                <td style="padding:12px;"><?php echo htmlspecialchars($t['subject']); ?></td>
                <td>
                    <span style="background:<?php echo $t['status']=='open'?'#e3f2fd':'#eee'; ?>; color:<?php echo $t['status']=='open'?'#1976d2':'#555'; ?>; padding:2px 8px; border-radius:10px; font-size:11px; font-weight:bold;">
                        <?php echo strtoupper($t['status']); ?>
                    </span>
                </td>
                <td style="color:#888;"><?php echo date('d M', strtotime($t['created_at'])); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?php endif; ?>
</div>

<div id="edit-profile-modal" class="modal-overlay" onclick="if(event.target===this) this.style.display='none'" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000; justify-content:center; align-items:center;">
    <div class="modal-content" style="background:white; padding:25px; border-radius:12px; max-width:500px; width:90%; position:relative;">
        <button onclick="document.getElementById('edit-profile-modal').style.display='none'" style="position:absolute; top:15px; right:15px; border:none; background:none; font-size:24px; cursor:pointer;">&times;</button>
        <h2 style="margin-top:0;">Edit Profile</h2>
        <form method="POST">
            <input type="hidden" name="update_profile" value="1">
            <div style="margin-bottom:10px;">
                <label style="font-weight:bold; font-size:12px;">Full Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            </div>
            <div style="margin-bottom:10px;">
                <label style="font-weight:bold; font-size:12px;">Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            </div>
            <div style="margin-bottom:10px;">
                <label style="font-weight:bold; font-size:12px;">Phone</label>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:15px;">
                <div>
                    <label style="font-weight:bold; font-size:12px;">Institution</label>
                    <input type="text" name="institution" value="<?php echo htmlspecialchars($user['institution'] ?? ''); ?>" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
                </div>
                <div>
                    <label style="font-weight:bold; font-size:12px;">Position</label>
                    <input type="text" name="position" value="<?php echo htmlspecialchars($user['position'] ?? ''); ?>" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
                </div>
            </div>
            <button type="submit" class="btn" style="width:100%;">Save Changes</button>
        </form>
    </div>
</div>

<script>
// COUNTDOWN TIMER LOGIC FOR MULTIPLE ORDERS
document.addEventListener('DOMContentLoaded', function() {
    const timers = document.querySelectorAll('.countdown-timer');
    
    timers.forEach(timer => {
        let timeLeft = parseInt(timer.getAttribute('data-time'));
        
        const updateTimer = () => {
            if (timeLeft <= 0) {
                timer.innerHTML = "EXPIRED";
                timer.style.color = "red";
                return;
            }
            
            const hours = Math.floor(timeLeft / 3600);
            const minutes = Math.floor((timeLeft % 3600) / 60);
            const seconds = timeLeft % 60;
            
            timer.innerHTML = `${hours}h ${minutes}m ${seconds}s`;
            timeLeft--;
        };
        
        updateTimer(); // Init
        setInterval(updateTimer, 1000);
    });
});
</script>