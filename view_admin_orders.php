<?php
// view_admin_orders.php - SIMPLIFIED STATUS FILTERS
if (($_SESSION['role'] ?? '') !== 'admin') die("Access Denied");
$pdo = getDB();

$search = $_GET['q'] ?? '';
$status = $_GET['status'] ?? '';
$sort = $_GET['sort'] ?? 'newest';

// Build Query
$sql = "SELECT o.*, u.name as customer_name, u.institution 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        WHERE 1=1";
$params = [];

if ($search) {
    $sql .= " AND (o.id LIKE ? OR u.name LIKE ?)";
    $params[] = "%$search%"; $params[] = "%$search%";
}

if ($status) {
    $sql .= " AND o.status = ?";
    $params[] = $status;
}

if ($sort === 'oldest') $sql .= " ORDER BY o.created_at ASC";
elseif ($sort === 'highest') $sql .= " ORDER BY o.total_amount DESC";
else $sql .= " ORDER BY o.created_at DESC";

$orders = $pdo->prepare($sql);
$orders->execute($params);
$orders = $orders->fetchAll();
?>

<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2 style="margin:0;">Order Management</h2>
        <form action="export.php" method="GET">
            <input type="hidden" name="type" value="orders">
            <button type="button" class="btn btn-sm" style="background:#e3f2fd; color:#1565c0;">Export CSV</button>
        </form>
    </div>

    <form method="GET" style="display:grid; grid-template-columns: 2fr 1fr 1fr auto; gap:10px; background:#f9f9f9; padding:15px; border-radius:12px; margin-bottom:20px;">
        <input type="hidden" name="page" value="admin_orders">
        <input type="text" name="q" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search Order ID or Customer..." style="width:100%;">

        <select name="status">
            <option value="">All Order Statuses</option>
            <option value="pending" <?php echo $status=='pending'?'selected':''; ?>>Pending</option>
            <option value="processing" <?php echo $status=='processing'?'selected':''; ?>>Processing</option>
            <option value="completed" <?php echo $status=='completed'?'selected':''; ?>>Completed</option>
        </select>

        <select name="sort">
            <option value="newest" <?php echo $sort=='newest'?'selected':''; ?>>Newest First</option>
            <option value="oldest" <?php echo $sort=='oldest'?'selected':''; ?>>Oldest First</option>
            <option value="highest" <?php echo $sort=='highest'?'selected':''; ?>>Highest Amount</option>
        </select>
        <button type="submit" class="btn btn-sm">Filter</button>
    </form>

    <div class="responsive-table">
        <table style="width:100%; border-collapse:collapse; font-size:13px;">
            <thead>
                <tr style="background:#f5f5f7; text-align:left; color:#555;">
                    <th style="padding:12px;">ID</th>
                    <th style="padding:12px;">Date</th>
                    <th style="padding:12px;">Customer</th>
                    <th style="padding:12px;">Amount</th>
                    <th style="padding:12px;">Payment</th>
                    <th style="padding:12px;">Delivery</th>
                    <th style="padding:12px;">Order Status</th>
                    <th style="padding:12px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($orders as $o): ?>
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:12px; font-weight:bold;">#<?php echo $o['id']; ?></td>
                    <td style="padding:12px; color:#666;"><?php echo date('d M Y', strtotime($o['created_at'])); ?></td>
                    <td style="padding:12px;">
                        <div style="font-weight:600;"><?php echo htmlspecialchars($o['customer_name']); ?></div>
                    </td>
                    <td style="padding:12px; font-weight:bold;">Rp <?php echo number_format($o['total_amount']); ?></td>
                    
                    <td style="padding:12px;">
                        <?php 
                            $pay_color = ($o['payment_status'] == 'paid') ? 'green' : (($o['payment_status'] == 'partial') ? 'orange' : 'red');
                        ?>
                        <span style="color:<?php echo $pay_color; ?>; font-weight:bold; font-size:11px;">
                            <?php echo strtoupper($o['payment_status']); ?>
                        </span>
                    </td>

                    <td style="padding:12px;">
                        <span style="color:#666; font-size:12px;">
                            <?php echo ucfirst($o['shipping_status'] ?? 'Processing'); ?>
                        </span>
                    </td>

                    <td style="padding:12px;">
                        <span style="padding:4px 8px; border-radius:4px; font-size:11px; font-weight:bold;
                            background: <?php echo $o['status']=='completed'?'#e8f5e9':($o['status']=='processing'?'#e3f2fd':'#fff3e0'); ?>;
                            color: <?php echo $o['status']=='completed'?'#2e7d32':($o['status']=='processing'?'#1565c0':'#ef6c00'); ?>;">
                            <?php echo strtoupper($o['status']); ?>
                        </span>
                    </td>

                    <td style="padding:12px;">
                        <a href="?page=admin_order_detail&id=<?php echo $o['id']; ?>" class="btn btn-sm" style="background:#f5f5f7; color:#333;">Manage</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>