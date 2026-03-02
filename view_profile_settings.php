<?php
// view_profile_settings.php - ADDRESS BOOK FIXED
if (!isset($_SESSION['user_id'])) echo "<script>window.location='?page=login';</script>";
$pdo = getDB();
$uid = $_SESSION['user_id'];

// 1. HANDLE FORM SUBMISSION (Add New Address)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_address') {
    $label = $_POST['label'] ?? 'Home';
    $recipient = $_POST['recipient'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $city = $_POST['city'] ?? '';
    $addr = $_POST['address'] ?? '';
    $zip = $_POST['zip'] ?? '';

    $stmt = $pdo->prepare("INSERT INTO user_addresses (user_id, label, recipient, phone, city, address_line, postal_code) VALUES (?,?,?,?,?,?,?)");
    $stmt->execute([$uid, $label, $recipient, $phone, $city, $addr, $zip]);
    
    echo "<script>window.location='?page=profile_settings';</script>";
    exit;
}

// 2. HANDLE DELETION
if (isset($_GET['delete'])) {
    $aid = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM user_addresses WHERE id=? AND user_id=?");
    $stmt->execute([$aid, $uid]);
    echo "<script>window.location='?page=profile_settings';</script>";
    exit;
}

// 3. FETCH ADDRESSES (Fixed Query: Removed 'ORDER BY is_default')
$addresses = $pdo->query("SELECT * FROM user_addresses WHERE user_id=$uid ORDER BY id DESC")->fetchAll();
?>

<div style="max-width:800px; margin:0 auto;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2 style="margin:0;">Address Book</h2>
        <a href="?page=profile" class="btn btn-sm" style="background:#eee; color:#333;">&larr; Back to Dashboard</a>
    </div>

    <div class="card" style="margin-bottom:30px;">
        <h3 style="margin-top:0; border-bottom:1px solid #eee; padding-bottom:10px;">Add New Address</h3>
        <form method="POST" style="display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
            <input type="hidden" name="action" value="add_address">
            
            <div style="grid-column: span 2;">
                <label style="font-size:12px; font-weight:bold;">Address Label</label>
                <input type="text" name="label" placeholder="e.g. School Office, Home, Warehouse" required style="width:100%; margin-top:5px;">
            </div>

            <div>
                <label style="font-size:12px; font-weight:bold;">Recipient Name</label>
                <input type="text" name="recipient" placeholder="John Doe" required style="width:100%; margin-top:5px;">
            </div>
            <div>
                <label style="font-size:12px; font-weight:bold;">Phone Number</label>
                <input type="text" name="phone" placeholder="0812..." required style="width:100%; margin-top:5px;">
            </div>

            <div>
                <label style="font-size:12px; font-weight:bold;">City</label>
                <input type="text" name="city" placeholder="Jakarta Selatan" required style="width:100%; margin-top:5px;">
            </div>
            <div>
                <label style="font-size:12px; font-weight:bold;">Postal Code</label>
                <input type="text" name="zip" placeholder="12345" style="width:100%; margin-top:5px;">
            </div>

            <div style="grid-column: span 2;">
                <label style="font-size:12px; font-weight:bold;">Full Address</label>
                <textarea name="address" rows="2" placeholder="Street name, Building number..." required style="width:100%; margin-top:5px;"></textarea>
            </div>

            <div style="grid-column: span 2;">
                <button type="submit" class="btn" style="width:100%;">Save Address</button>
            </div>
        </form>
    </div>

    <h3>Saved Addresses</h3>
    <?php if(empty($addresses)): ?>
        <p style="color:#888;">No addresses saved yet.</p>
    <?php else: ?>
        <div style="display:grid; gap:15px;">
            <?php foreach($addresses as $addr): ?>
            <div class="card" style="display:flex; justify-content:space-between; align-items:flex-start;">
                <div>
                    <div style="font-weight:bold; color:var(--primary); font-size:16px;">
                        <ion-icon name="location" style="vertical-align:middle;"></ion-icon> 
                        <?php echo htmlspecialchars($addr['label']); ?>
                    </div>
                    <div style="margin-top:5px; font-weight:600;"><?php echo htmlspecialchars($addr['recipient']); ?> (<?php echo htmlspecialchars($addr['phone']); ?>)</div>
                    <div style="color:#555; font-size:14px; margin-top:2px;">
                        <?php echo htmlspecialchars($addr['address_line']); ?>, 
                        <?php echo htmlspecialchars($addr['city']); ?> <?php echo htmlspecialchars($addr['postal_code']); ?>
                    </div>
                </div>
                <div>
                    <a href="?page=profile_settings&delete=<?php echo $addr['id']; ?>" onclick="return confirm('Delete this address?')" style="color:red; font-size:20px;">
                        <ion-icon name="trash-outline"></ion-icon>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>