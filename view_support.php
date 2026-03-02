<?php
// view_support.php - USER SUPPORT WITH SMART LOCAL AI
if (!isset($_SESSION['user_id'])) echo "<script>window.location='?page=login';</script>";
$pdo = getDB();
$uid = $_SESSION['user_id'];

// --- SMART LOCAL AI ENGINE (No API Key Needed) ---
function getLocalAIResponse($subject, $message) {
    $s = strtolower($subject . " " . $message);
    
    // 1. Order Status / Tracking
    if (strpos($s, 'where') !== false || strpos($s, 'status') !== false || strpos($s, 'track') !== false || strpos($s, 'arrive') !== false) {
        return "I can help with that. You can track the real-time status of your package in the 'Order History' tab. If it has been marked as 'delivering', a tracking number should be visible there. Delivery usually takes 3-5 business days.";
    }
    // 2. Payment Issues
    elseif (strpos($s, 'pay') !== false || strpos($s, 'transfer') !== false || strpos($s, 'verify') !== false) {
        return "Regarding your payment: Our system automatically verifies transfers within 24 hours. If you have uploaded your proof of payment, please allow some time for our team to confirm it. Ensure the amount matches exactly to speed up the process.";
    }
    // 3. Wrong/Damaged Item
    elseif (strpos($s, 'wrong') !== false || strpos($s, 'damage') !== false || strpos($s, 'broken') !== false || strpos($s, 'item') !== false) {
        return "I apologize for the inconvenience. To resolve this quickly, please reply with a photo of the item you received. Our team will arrange a replacement or refund immediately upon verification.";
    }
    // 4. Cancellation
    elseif (strpos($s, 'cancel') !== false || strpos($s, 'refund') !== false) {
        return "You can cancel your order directly from the 'Order Detail' page if it hasn't been delivering yet. If the button is missing, the order is already being processed. Let us know if you need further assistance.";
    }
    // 5. Default Fallback
    else {
        return "Thank you for contacting us. I have logged your ticket and notified a human support specialist. They will review your request regarding '" . htmlspecialchars($subject) . "' and respond shortly.";
    }
}

// --- HANDLE FORM SUBMISSION ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_ticket'])) {
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($subject && $message) {
        // Generate Instant AI Response
        $ai_reply = getLocalAIResponse($subject, $message);

        // Insert Ticket
        $stmt = $pdo->prepare("INSERT INTO tickets (user_id, subject, message, status, ai_reply, created_at) VALUES (?, ?, ?, 'open', ?, NOW())");
        $stmt->execute([$uid, $subject, $message, $ai_reply]);
        
        echo "<script>alert('Ticket submitted! AI Agent has responded.'); window.location='?page=support';</script>";
        exit;
    }
}

// --- FETCH TICKETS ---
$stmt = $pdo->prepare("SELECT * FROM tickets WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$uid]);
$tickets = $stmt->fetchAll();
?>

<div class="container" style="max-width: 900px; padding-top: 20px;">
    
    <div class="card" style="margin-bottom: 40px;">
        <h2 style="margin-top: 0;">Customer Care</h2>
        <p style="color: #666; margin-bottom: 20px;">
            Submit a ticket below. Our <strong>AI Agent</strong> replies instantly!
        </p>
        
        <form method="POST">
            <input type="hidden" name="submit_ticket" value="1">
            <div style="margin-bottom: 15px;">
                <label style="font-weight:bold; font-size:13px;">Subject</label>
                <input type="text" name="subject" class="form-control" placeholder="e.g. Order #123 Late" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; margin-top:5px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="font-weight:bold; font-size:13px;">Message</label>
                <textarea name="message" class="form-control" rows="5" placeholder="Describe your issue..." required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-family: inherit; margin-top:5px;"></textarea>
            </div>
            <button type="submit" class="btn" style="width:100%; padding: 12px; font-size:16px;">Ask AI Agent</button>
        </form>
    </div>

    <div>
        <h3 style="margin-bottom: 15px;">Your Ticket History</h3>
        <?php if (empty($tickets)): ?>
            <div class="card" style="text-align: center; color: #888; padding: 40px;">No tickets yet.</div>
        <?php else: ?>
            <div class="card" style="padding:0; overflow:hidden;">
                <table style="width:100%; border-collapse:collapse; font-size:14px;">
                    <thead style="background:#f9f9f9; text-align:left;">
                        <tr>
                            <th style="padding:15px;">Discussion</th>
                            <th style="padding:15px; width:120px;">Date</th>
                            <th style="padding:15px; width:100px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tickets as $t): ?>
                        <tr style="border-bottom:1px solid #eee;">
                            <td style="padding:15px;">
                                <div style="font-weight:bold; font-size:15px; color:#333; margin-bottom:5px;">
                                    <?php echo htmlspecialchars($t['subject']); ?>
                                </div>
                                <div style="color:#666; font-size:13px; margin-bottom:15px;">
                                    "<?php echo htmlspecialchars($t['message']); ?>"
                                </div>
                                
                                <?php if (!empty($t['admin_reply'])): ?>
                                    <div style="background:#e8f5e9; border:1px solid #c8e6c9; border-radius:8px; padding:12px; margin-top:10px;">
                                        <div style="font-weight:bold; color:#2e7d32; font-size:12px; margin-bottom:4px; display:flex; align-items:center; gap:5px;">
                                            <ion-icon name="person"></ion-icon> Admin Response
                                        </div>
                                        <div style="color:#1b5e20; line-height:1.5;">
                                            <?php echo nl2br(htmlspecialchars($t['admin_reply'])); ?>
                                        </div>
                                    </div>
                                <?php elseif (!empty($t['ai_reply'])): ?>
                                    <div style="background:#f0f9ff; border:1px solid #bae6fd; border-radius:8px; padding:12px; margin-top:10px;">
                                        <div style="font-weight:bold; color:#0284c7; font-size:12px; margin-bottom:4px; display:flex; align-items:center; gap:5px;">
                                            <ion-icon name="sparkles"></ion-icon> AI Response
                                        </div>
                                        <div style="color:#334155; line-height:1.5;">
                                            <?php echo nl2br(htmlspecialchars($t['ai_reply'])); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td style="padding:15px; color:#666; white-space:nowrap; vertical-align:top;">
                                <?php echo date('d M', strtotime($t['created_at'])); ?>
                            </td>
                            <td style="padding:15px; vertical-align:top;">
                                <span style="background:<?php echo $t['status']=='open'?'#e3f2fd':'#eee'; ?>; color:<?php echo $t['status']=='open'?'#1565c0':'#666'; ?>; padding:4px 10px; border-radius:15px; font-size:11px; font-weight:bold;">
                                    <?php echo strtoupper($t['status']); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>