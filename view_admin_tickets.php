<?php
// view_admin_tickets.php - ADMIN REPLY SYSTEM ENABLED
if (($_SESSION['role'] ?? '') !== 'admin') die("Access Denied");
$pdo = getDB();

// --- HANDLE ACTIONS ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Update Status & Admin Reply
    if (isset($_POST['update_ticket'])) {
        $tid = $_POST['ticket_id'];
        $status = $_POST['status'];
        $reply = trim($_POST['admin_reply']);

        $sql = "UPDATE tickets SET status = ?";
        $params = [$status];

        // Only update reply if admin typed something
        if (!empty($reply)) {
            $sql .= ", admin_reply = ?";
            $params[] = $reply;
        }
        
        $sql .= " WHERE id = ?";
        $params[] = $tid;

        $pdo->prepare($sql)->execute($params);
        echo "<script>window.location.href='?page=admin_tickets';</script>";
        exit;
    }
}

// --- FETCH TICKETS ---
$status = $_GET['status'] ?? '';
$sql = "SELECT t.*, u.name, u.email FROM tickets t JOIN users u ON t.user_id = u.id WHERE 1=1";
if ($status) $sql .= " AND t.status = '$status'";
$sql .= " ORDER BY t.created_at DESC";
$tickets = $pdo->query($sql)->fetchAll();
?>

<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2 style="margin:0;">Support Inbox</h2>
        <form method="GET">
            <input type="hidden" name="page" value="admin_tickets">
            <select name="status" onchange="this.form.submit()" style="padding:6px; border-radius:4px;">
                <option value="">All Tickets</option>
                <option value="open" <?php echo $status=='open'?'selected':''; ?>>Open</option>
                <option value="closed" <?php echo $status=='closed'?'selected':''; ?>>Closed</option>
            </select>
        </form>
    </div>

    <div class="responsive-table">
        <table style="width:100%; border-collapse:collapse; font-size:14px;">
            <tr style="background:#f5f5f7; text-align:left; color:#666;">
                <th style="padding:10px;">ID</th>
                <th>User</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php foreach($tickets as $t): 
                $t_json = htmlspecialchars(json_encode($t), ENT_QUOTES, 'UTF-8');
            ?>
            <tr style="border-bottom:1px solid #eee;">
                <td style="padding:12px;">#<?php echo $t['id']; ?></td>
                <td><strong><?php echo htmlspecialchars($t['name']); ?></strong><br><small style="color:#888;"><?php echo $t['email']; ?></small></td>
                <td><?php echo htmlspecialchars($t['subject']); ?></td>
                <td>
                    <span style="background:<?php echo $t['status']=='open'?'#e3f2fd':'#eee'; ?>; color:<?php echo $t['status']=='open'?'#1976d2':'#555'; ?>; padding:2px 8px; border-radius:10px; font-size:11px; font-weight:bold;">
                        <?php echo strtoupper($t['status']); ?>
                    </span>
                </td>
                <td>
                    <button onclick="manageTicket(<?php echo $t_json; ?>)" class="btn btn-sm" style="background:#007AFF; color:white;">Reply / Manage</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<div id="ticket-modal" class="modal-overlay" onclick="if(event.target===this) this.style.display='none'" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000; justify-content:center; align-items:center;">
    <div class="modal-content" style="background:white; padding:25px; border-radius:12px; max-width:600px; width:90%; position:relative;">
        <button onclick="document.getElementById('ticket-modal').style.display='none'" style="position:absolute; top:15px; right:15px; border:none; background:none; font-size:24px; cursor:pointer;">&times;</button>
        
        <h2 style="margin-top:0;">Manage Ticket #<span id="modal-id-disp"></span></h2>
        
        <div style="background:#f9f9f9; padding:15px; border-radius:8px; margin-bottom:15px;">
            <strong id="modal-subject"></strong>
            <p id="modal-message" style="margin:5px 0; color:#555; white-space: pre-wrap;"></p>
            <div style="font-size:12px; color:#888; margin-top:5px;">AI Reply: <span id="modal-ai"></span></div>
        </div>

        <form method="POST">
            <input type="hidden" name="update_ticket" value="1">
            <input type="hidden" name="ticket_id" id="modal-id">
            
            <div style="margin-bottom:15px;">
                <label style="font-weight:bold; font-size:12px;">Admin Reply (Optional)</label>
                <textarea name="admin_reply" id="modal-admin-reply" rows="3" class="form-control" style="width:100%; padding:10px; margin-top:5px; border:1px solid #ccc; border-radius:5px;" placeholder="Type your reply here to override AI..."></textarea>
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center;">
                <select name="status" id="modal-status" style="padding:8px; border-radius:4px; border:1px solid #ccc;">
                    <option value="open">Status: Open</option>
                    <option value="closed">Status: Closed</option>
                </select>
                <button type="submit" class="btn">Save & Update</button>
            </div>
        </form>
    </div>
</div>

<script>
function manageTicket(t) {
    document.getElementById('modal-id').value = t.id;
    document.getElementById('modal-id-disp').innerText = t.id;
    document.getElementById('modal-subject').innerText = t.subject;
    document.getElementById('modal-message').innerText = t.message;
    document.getElementById('modal-ai').innerText = t.ai_reply || 'No AI response';
    document.getElementById('modal-admin-reply').value = t.admin_reply || ''; // Pre-fill if exists
    document.getElementById('modal-status').value = t.status;
    
    document.getElementById('ticket-modal').style.display = 'flex';
}
</script>