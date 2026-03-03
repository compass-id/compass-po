<?php
// view_admin_shipments.php - DEDICATED SHIPMENT HISTORY & EDITOR
if (($_SESSION['role'] ?? '') !== 'admin') die("Access Denied");
$pdo = getDB();

// --- HANDLE POST ACTIONS ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_logistics'])) {
    $order_id = $_POST['order_id'];
    $ship_status = $_POST['ship_status'];
    $est_date = $_POST['est_date'];
    
    $pdo->prepare("UPDATE orders SET shipping_status = ?, shipping_snapshot = JSON_SET(COALESCE(shipping_snapshot, '{}'), '$.est_arrival', ?) WHERE id = ?")
        ->execute([$ship_status, $est_date, $order_id]);
    
    echo "<script>alert('Shipment details updated successfully.'); window.location.href='?page=admin_shipments';</script>";
    exit;
}

// Fetch Orders
$shipments = $pdo->query("
    SELECT o.id, o.shipping_status, o.shipping_city, o.shipping_snapshot, o.confirmed_arrival, o.arrival_proof, o.created_at, u.name as user_name 
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
    WHERE o.status NOT IN ('cancelled', 'rejected')
    ORDER BY o.created_at DESC
")->fetchAll();
?>

<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2 style="margin:0;">📦 Shipment & Logistics Tracker</h2>
        
        <div style="display:flex; gap:10px;">
            <input type="text" id="ship-search" placeholder="Search ID, City, Status..." onkeyup="filterShipments()" style="padding:8px; border-radius:4px; border:1px solid #ccc; font-size:14px;">
            <select id="ship-sort" onchange="sortShipments()" style="padding:8px; border-radius:4px; border:1px solid #ccc;">
                <option value="newest">Newest Orders</option>
                <option value="oldest">Oldest Orders</option>
            </select>
        </div>
    </div>

    <div class="responsive-table">
        <table style="width:100%; border-collapse:collapse; font-size:14px;">
            <thead>
                <tr style="background:#f5f5f7; text-align:left; color:#666;">
                    <th style="padding:12px;">Order ID</th>
                    <th style="padding:12px;">Client & Dest.</th>
                    <th style="padding:12px;">Arrival Proof</th>
                    <th style="padding:12px;">ETA & Status Manager</th>
                </tr>
            </thead>
            <tbody id="shipment-tbody">
                <?php foreach($shipments as $s): 
                    $timestamp = strtotime($s['created_at']);
                    $snapshot = json_decode($s['shipping_snapshot'] ?? '{}', true);
                    $eta = $snapshot['est_arrival'] ?? date('Y-m-d', strtotime('+3 days'));
                    $current_status = $s['shipping_status'] ?: 'processing';
                ?>
                <tr class="shipment-row" 
                    data-search="<?php echo strtolower($s['id'] . ' ' . $s['user_name'] . ' ' . $s['shipping_city'] . ' ' . $current_status); ?>"
                    data-time="<?php echo $timestamp; ?>"
                    style="border-bottom:1px solid #eee;">
                    
                    <td style="padding:12px; font-weight:bold;">
                        <a href="?page=admin_order_detail&id=<?php echo $s['id']; ?>" style="text-decoration:none; color:#007AFF;">#<?php echo $s['id']; ?></a><br>
                        <span style="font-size:11px; color:#999; font-weight:normal;"><?php echo date('d M Y', $timestamp); ?></span>
                    </td>
                    
                    <td style="padding:12px;">
                        <strong><?php echo htmlspecialchars($s['user_name']); ?></strong><br>
                        <span style="color:#555; font-size:12px;">📍 <?php echo htmlspecialchars($s['shipping_city']); ?></span>
                    </td>

                    <td style="padding:12px;">
                        <?php if($s['arrival_proof']): ?>
                            <a href="uploads/<?php echo htmlspecialchars($s['arrival_proof']); ?>" target="_blank" style="color:#2e7d32; font-weight:bold; font-size:12px;"><ion-icon name="image"></ion-icon> View Proof</a><br>
                            <span style="font-size:11px; color:#888;">Recv: <?php echo date('d M Y', strtotime($s['confirmed_arrival'])); ?></span>
                        <?php else: ?>
                            <span style="color:#999; font-size:12px; font-style:italic;">No proof uploaded</span>
                        <?php endif; ?>
                    </td>

                    <td style="padding:12px;">
                        <form method="POST" style="display:flex; gap:5px; align-items:center;">
                            <input type="hidden" name="order_id" value="<?php echo $s['id']; ?>">
                            
                            <input type="date" name="est_date" value="<?php echo $eta; ?>" style="padding:6px; font-size:12px; border:1px solid #ccc; border-radius:4px; width:120px;" title="Estimated Time of Arrival">
                            
                            <select name="ship_status" style="padding:6px; font-size:12px; border:1px solid #ccc; border-radius:4px; font-weight:bold; 
                                color:<?php echo ($current_status=='completed')?'green':(($current_status=='shipping' || $current_status=='delivering')?'#1565c0':'#ef6c00'); ?>;">
                                <option value="processing" <?php echo $current_status=='processing'?'selected':''; ?>>Processing</option>
                                <option value="shipping" <?php echo $current_status=='shipping'?'selected':''; ?>>Shipping</option>
                                <option value="delivering" <?php echo $current_status=='delivering'?'selected':''; ?>>Delivering</option>
                                <option value="completed" <?php echo $current_status=='completed'?'selected':''; ?>>Completed</option>
                                <option value="cancelled" <?php echo $current_status=='cancelled'?'selected':''; ?>>Cancelled</option>
                            </select>
                            
                            <button type="submit" name="update_logistics" class="btn btn-sm" style="padding:6px 12px; font-size:12px;">Save</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function filterShipments() {
    const query = document.getElementById('ship-search').value.toLowerCase();
    const rows = document.querySelectorAll('.shipment-row');
    rows.forEach(row => {
        row.style.display = row.getAttribute('data-search').includes(query) ? '' : 'none';
    });
}

function sortShipments() {
    const val = document.getElementById('ship-sort').value;
    const tbody = document.getElementById('shipment-tbody');
    const rows = Array.from(tbody.querySelectorAll('.shipment-row'));

    rows.sort((a, b) => {
        if(val === 'newest') return b.getAttribute('data-time') - a.getAttribute('data-time');
        if(val === 'oldest') return a.getAttribute('data-time') - b.getAttribute('data-time');
    });

    rows.forEach(row => tbody.appendChild(row));
}
</script>