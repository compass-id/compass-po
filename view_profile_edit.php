<?php
if (!isset($_SESSION['user_id'])) echo "<script>window.location='?page=login';</script>";
$pdo = getDB();

// Handle Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE users SET name = ?, phone = ?, address = ? WHERE id = ?");
    $stmt->execute([
        clean_input($_POST['name']),
        clean_input($_POST['phone']),
        clean_input($_POST['address']),
        $_SESSION['user_id']
    ]);
    $_SESSION['name'] = clean_input($_POST['name']); // Update Session
    echo "<div class='card' style='background:#d4edda; color:#155724;'>Profile Updated!</div>";
}

$user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$user->execute([$_SESSION['user_id']]);
$u = $user->fetch();
?>

<div class="card" style="max-width:600px; margin:0 auto;">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h2>Edit Profile</h2>
        <a href="?page=profile" class="btn" style="width:auto; padding:8px 16px; background:#8E8E93;">Cancel</a>
    </div>

    <form method="POST" style="margin-top:20px;">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        
        <div style="margin-bottom:15px;">
            <label style="font-size:12px; font-weight:700; color:gray;">FULL NAME</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($u['name']); ?>" required
                   style="width:100%; padding:12px; border-radius:10px; border:1px solid #ddd; margin-top:5px;">
        </div>

        <div style="margin-bottom:15px;">
            <label style="font-size:12px; font-weight:700; color:gray;">PHONE NUMBER</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($u['phone'] ?? ''); ?>" placeholder="+62..."
                   style="width:100%; padding:12px; border-radius:10px; border:1px solid #ddd; margin-top:5px;">
        </div>

        <div style="margin-bottom:20px;">
            <label style="font-size:12px; font-weight:700; color:gray;">SHIPPING ADDRESS</label>
            <textarea name="address" rows="4" placeholder="Street, City, Zip Code"
                      style="width:100%; padding:12px; border-radius:10px; border:1px solid #ddd; margin-top:5px;"><?php echo htmlspecialchars($u['address'] ?? ''); ?></textarea>
        </div>

        <button type="submit" class="btn">Save Changes</button>
    </form>
</div>