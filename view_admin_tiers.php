<?php
// view_admin_tiers.php - DYNAMIC PRICING MANAGER
if (($_SESSION['role'] ?? '') !== 'admin') die("Access Denied");
$pdo = getDB();

// HANDLE ACTIONS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_tier'])) {
        $pdo->prepare("INSERT INTO tier_rules (name, min_qty, discount_percent, color) VALUES (?, ?, ?, ?)")
            ->execute([$_POST['name'], $_POST['min_qty'], $_POST['discount'], $_POST['color']]);
    }
    elseif (isset($_POST['delete_tier'])) {
        $pdo->prepare("DELETE FROM tier_rules WHERE id = ?")->execute([$_POST['id']]);
    }
}

// FETCH TIERS
$tiers = $pdo->query("SELECT * FROM tier_rules ORDER BY min_qty ASC")->fetchAll();
?>

<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h2>Discount Tiers</h2>
        <div style="font-size:13px; color:#666;">Define bulk purchase discounts here.</div>
    </div>

    <table style="width:100%; margin-top:20px; border-collapse:collapse;">
        <tr style="background:#f9f9f9; text-align:left;">
            <th style="padding:10px;">Tier Name</th>
            <th>Min Qty</th>
            <th>Discount</th>
            <th>Color Badge</th>
            <th>Action</th>
        </tr>
        <?php foreach($tiers as $t): ?>
        <tr style="border-bottom:1px solid #eee;">
            <td style="padding:10px; font-weight:bold;"><?php echo htmlspecialchars($t['name']); ?></td>
            <td><?php echo number_format($t['min_qty']); ?> units</td>
            <td style="color:green; font-weight:bold;"><?php echo $t['discount_percent']; ?>% OFF</td>
            <td><span style="padding:3px 8px; border-radius:4px; color:white; font-size:11px; background:<?php echo $t['color']; ?>;"><?php echo $t['color']; ?></span></td>
            <td>
                <?php if($t['min_qty'] > 0): ?>
                <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this tier?');">
                    <input type="hidden" name="id" value="<?php echo $t['id']; ?>">
                    <button type="submit" name="delete_tier" class="btn btn-sm" style="background:#ffebee; color:red;">Delete</button>
                </form>
                <?php else: ?>
                    <span style="color:#ccc; font-size:11px;">Default</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <hr style="margin:30px 0; border:0; border-top:1px solid #eee;">

    <h3>Add New Tier</h3>
    <form method="POST" style="display:grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap:10px; align-items:end;">
        <div>
            <label style="font-size:12px; font-weight:bold;">Tier Name</label>
            <input type="text" name="name" placeholder="e.g. Mega Bundle" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;">
        </div>
        <div>
            <label style="font-size:12px; font-weight:bold;">Min Qty</label>
            <input type="number" name="min_qty" placeholder="500" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;">
        </div>
        <div>
            <label style="font-size:12px; font-weight:bold;">Discount %</label>
            <input type="number" name="discount" placeholder="25" step="0.1" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;">
        </div>
        <div>
            <label style="font-size:12px; font-weight:bold;">Color</label>
            <select name="color" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;">
                <option value="blue">Blue</option>
                <option value="green">Green</option>
                <option value="gold">Gold</option>
                <option value="purple">Purple</option>
                <option value="orange">Orange</option>
            </select>
        </div>
        <button type="submit" name="add_tier" class="btn btn-sm" style="background:var(--primary); color:white; height:35px;">Add Rule</button>
    </form>
</div>