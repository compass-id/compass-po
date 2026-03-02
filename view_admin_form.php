<?php
// view_admin_forms.php - DATA VIEW FOR ADMINS
if (($_SESSION['role'] ?? '') !== 'admin') die("Access Denied");
$pdo = getDB();

// Fetch all form submissions
$forms = $pdo->query("SELECT * FROM interest_forms ORDER BY created_at DESC")->fetchAll();
?>

<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h2>Interest Form Submissions</h2>
        <span style="background:var(--primary); color:white; padding:5px 10px; border-radius:20px; font-size:12px;">
            <?php echo count($forms); ?> Responses
        </span>
    </div>

    <div style="overflow-x:auto; margin-top:20px;">
        <table style="width:100%; border-collapse:collapse; font-size:14px;">
            <tr style="background:#f5f5f7; text-align:left; color:#888;">
                <th style="padding:15px; border-radius:10px 0 0 10px;">Date</th>
                <th style="padding:15px;">School / City</th>
                <th style="padding:15px;">Contact Person</th>
                <th style="padding:15px;">Students</th>
                <th style="padding:15px; border-radius:0 10px 10px 0;">Interested In</th>
            </tr>
            
            <?php if(empty($forms)): ?>
                <tr><td colspan="5" style="padding:20px; text-align:center;">No submissions yet.</td></tr>
            <?php else: ?>
                <?php foreach($forms as $f): 
                    $programs = json_decode($f['programs'] ?? '[]'); 
                ?>
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:15px; color:#555;">
                        <?php echo date('d M Y', strtotime($f['created_at'])); ?><br>
                        <small><?php echo date('H:i', strtotime($f['created_at'])); ?></small>
                    </td>
                    <td style="padding:15px;">
                        <strong><?php echo htmlspecialchars($f['school_name']); ?></strong><br>
                        <span style="color:#888; font-size:12px;"><?php echo htmlspecialchars($f['city']); ?></span>
                    </td>
                    <td style="padding:15px;">
                        <?php echo htmlspecialchars($f['participant_name']); ?>
                    </td>
                    <td style="padding:15px; font-weight:600;">
                        <?php echo number_format($f['student_count']); ?>
                    </td>
                    <td style="padding:15px;">
                        <?php foreach($programs as $p): ?>
                            <span style="display:inline-block; background:#eef2ff; color:var(--primary); padding:2px 8px; border-radius:4px; font-size:11px; margin:2px;">
                                <?php echo htmlspecialchars($p); ?>
                            </span>
                        <?php endforeach; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
</div>