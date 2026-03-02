<?php
// view_admin_users.php - USER MANAGER
if (($_SESSION['role'] ?? '') !== 'admin') die("Access Denied");
$pdo = getDB();

$search = $_GET['q'] ?? '';
$sql = "SELECT * FROM users WHERE 1=1";
$params = [];

if ($search) {
    $sql .= " AND (name LIKE ? OR email LIKE ? OR institution LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}
$sql .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$users = $stmt->fetchAll();
?>

<div class="card">
    <div style="margin-bottom:20px;">
        <h2>User Management</h2>
    </div>

    <form method="GET" style="display:flex; gap:10px; margin-bottom:20px;">
        <input type="hidden" name="page" value="admin_users">
        <input type="text" name="q" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search User, Email, or School..." style="flex:1; margin:0;">
        <button type="submit" class="btn btn-sm">Search</button>
    </form>

    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-size:14px;">
            <tr style="background:#f5f5f7; text-align:left;">
                <th style="padding:12px;">Name</th>
                <th>Role</th>
                <th>Institution</th>
                <th>Email</th>
                <th>Joined</th>
                <th>Action</th>
            </tr>
            <?php foreach($users as $u): ?>
            <tr style="border-bottom:1px solid #eee;">
                <td style="padding:12px; font-weight:600;"><?php echo htmlspecialchars($u['name']); ?></td>
                <td>
                    <span style="background:<?php echo $u['role']=='admin'?'#ffebee':'#e8f5e9'; ?>; color:<?php echo $u['role']=='admin'?'#d32f2f':'#2e7d32'; ?>; padding:2px 8px; border-radius:10px; font-size:11px; font-weight:bold;">
                        <?php echo strtoupper($u['role']); ?>
                    </span>
                </td>
                <td><?php echo htmlspecialchars($u['institution'] ?? '-'); ?></td>
                <td><?php echo htmlspecialchars($u['email']); ?></td>
                <td style="color:#888; font-size:12px;"><?php echo date('d M Y', strtotime($u['created_at'])); ?></td>
                <td>
                    <?php if(!$u['is_banned']): ?>
                    <button onclick="banUser(<?php echo $u['id']; ?>)" class="btn btn-sm" style="background:#fff0f0; color:red; padding:4px 8px; font-size:11px;">Ban</button>
                    <?php else: ?>
                    <span style="color:red; font-weight:bold;">BANNED</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<script>
async function banUser(id) {
    if(!confirm("Ban this user?")) return;
    await fetch('api.php?action=ban_user', {
        method:'POST', body:JSON.stringify({user_id:id})
    });
    location.reload();
}
</script>