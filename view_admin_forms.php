<?php
// view_admin_forms.php - FIXED NULL ERROR
if (($_SESSION['role'] ?? '') !== 'admin') die("Access Denied");
$pdo = getDB();

$search = $_GET['q'] ?? '';
$level = $_GET['level'] ?? '';

$sql = "SELECT * FROM interest_forms WHERE 1=1";
$params = [];

if ($search) {
    $sql .= " AND (school_name LIKE ? OR participant_name LIKE ? OR city LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}
if ($level) {
    $sql .= " AND interest_level = ?";
    $params[] = $level;
}
$sql .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();
?>

<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px; margin-bottom:20px;">
        <h2>Interest Form Responses</h2>
        
        <form action="export.php" method="GET" style="display:flex; gap:5px; align-items:center; background:#f0f9ff; padding:8px; border-radius:8px;">
            <input type="hidden" name="type" value="forms">
            <span style="font-size:12px; font-weight:bold; color:#007AFF;">EXPORT:</span>
            <input type="date" name="start" required value="<?php echo date('Y-m-01'); ?>" style="padding:5px; border:1px solid #ddd; border-radius:4px; font-size:12px; width:auto; margin:0;">
            <span style="font-size:12px;">to</span>
            <input type="date" name="end" required value="<?php echo date('Y-m-d'); ?>" style="padding:5px; border:1px solid #ddd; border-radius:4px; font-size:12px; width:auto; margin:0;">
            <button type="submit" class="btn btn-sm" style="padding:6px 12px; font-size:12px;"><ion-icon name="download"></ion-icon></button>
        </form>
    </div>

    <form method="GET" style="display:grid; grid-template-columns: 2fr 1fr auto; gap:10px; margin-bottom:20px;">
        <input type="hidden" name="page" value="admin_forms">
        <input type="text" name="q" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search School, Person, City..." style="margin:0;">
        <select name="level" style="margin:0;">
            <option value="">All Interests</option>
            <option value="Tertarik" <?php echo $level=='Tertarik'?'selected':''; ?>>High Interest</option>
            <option value="Ingin Tahu" <?php echo $level=='Ingin Tahu'?'selected':''; ?>>Curious</option>
        </select>
        <button type="submit" class="btn btn-sm">Filter</button>
    </form>

    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-size:13px;">
            <tr style="background:#f5f5f7; text-align:left;">
                <th style="padding:10px;">Date</th>
                <th>Interest Rate</th>
                <th>Visit Consent</th>
                <th>Participant Name</th>
                <th>Position</th>
                <th>School & City</th>
                <th>Student Counts</th>
                <th>Programs</th>
            </tr>
            <?php foreach($rows as $f): ?>
            <tr style="border-bottom:1px solid #eee;">
                <td style="padding:10px; color:#666;"><?php echo date('d M Y', strtotime($f['created_at'])); ?></td>
                <td>
                    <?php if(($f['interest_level'] ?? '') == 'Tertarik'): ?>
                        <span style="color:#d32f2f; font-weight:bold;">High</span>
                    <?php else: ?>
                        Medium
                    <?php endif; ?>
                </td>
                <td>
                    <?php if(($f['visit_consent'] ?? '') == 'Ya'): ?>
                        <span style="color:green; font-weight:bold; background:#e8f5e9; padding:2px 6px; border-radius:4px;">YES</span>
                    <?php else: ?>
                        <span style="color:#999;">No</span>
                    <?php endif; ?>
                </td>
                <td>
                    <div><?php echo htmlspecialchars($f['participant_name'] ?? '-'); ?></div>
                </td>
                <td>
                    <div style="font-weight:bold; color:#007AFF;"><?php echo htmlspecialchars($f['position'] ?? ''); ?></div>
                </td>
                <td>
                    <div style="font-weight:bold; color:#007AFF;"><?php echo htmlspecialchars($f['school_name'] ?? ''); ?></div>
                    <div style="font-size:11px;"><?php echo htmlspecialchars($f['city'] ?? ''); ?></div>
                </td>
                
                <td><?php echo $f['student_count']; ?></td>
                <td><?php $json = $f['programs'];

$array = json_decode($json, true);

$text = implode(", ", $array);

echo $text; ?></td>
                
                
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>