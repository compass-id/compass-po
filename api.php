<?php
// api.php - FINAL MASTER ENGINE
require 'config.php';
require 'security.php';

header('Content-Type: application/json');

// 1. GLOBAL ERROR HANDLER
set_exception_handler(function($e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit;
});

$pdo = getDB();
// Handle both JSON body (for JS fetch) and POST vars (for Forms)
$input = json_decode(file_get_contents('php://input'), true) ?? [];
$action = $_GET['action'] ?? '';

// Helper: Fetch System Settings
function get_sys_settings($pdo) {
    try {
        return $pdo->query("SELECT setting_key, setting_value FROM system_settings")->fetchAll(PDO::FETCH_KEY_PAIR);
    } catch (Exception $e) { return []; }
}

try {
    // ====================================================
    // 1. PUBLIC DATA & SETTINGS
    // ====================================================
    
    if ($action === 'get_settings') {
        echo json_encode(get_sys_settings($pdo));
        exit;
    }

    // ====================================================
    // 2. INTEREST FORM SUBMISSION (Updated for Split Contacts)
    // ====================================================

    if ($action === 'submit_form') {
        // Insert into interest_forms with separated Email and Phone
        $stmt = $pdo->prepare("INSERT INTO interest_forms 
            (school_name, participant_name, email, phone, position, city, student_count, visit_consent, interest_level, programs) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
        $stmt->execute([
            clean_input($_POST['school_name']), 
            clean_input($_POST['participant_name']), 
            clean_input($_POST['email']), 
            clean_input($_POST['phone']), 
            clean_input($_POST['position']), 
            clean_input($_POST['city']), 
            (int)$_POST['student_count'],
            clean_input($_POST['visit_consent']),
            clean_input($_POST['interest_level']),
            json_encode($_POST['programs'] ?? [])
        ]);
        
        header("Location: index.php?page=form&msg=success");
        exit;
    }

    // ====================================================
    // 3. AUTHENTICATION (Login / Register)
    // ====================================================

    if ($action === 'login') {
        if (empty($input['email']) || empty($input['password'])) throw new Exception("Missing credentials");
        
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([clean_input($input['email'])]);
        $user = $stmt->fetch();

        if ($user && password_verify($input['password'], $user['password'])) {
            if ($user['is_banned'] == 1) throw new Exception("Account Suspended. Contact Support.");
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            
            echo json_encode(['status' => 'success']);
        } else {
            throw new Exception("Invalid email or password");
        }
        exit;
    }

    if ($action === 'register') {
        // Validate
        if (empty($input['name']) || empty($input['email']) || empty($input['password'])) throw new Exception("Please fill all required fields.");
        
        $email = clean_input($input['email']);
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);
        if ($check->rowCount() > 0) throw new Exception("Email already registered.");
        
        $pass = password_hash($input['password'], PASSWORD_DEFAULT);
        
        // Insert New User (Including Institution, Position, City)
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, position, institution, city, phone, address) VALUES (?, ?, ?, 'public', ?, ?, ?, NULL, NULL)");
        
        $stmt->execute([
            clean_input($input['name']), 
            $email, 
            $pass,
            clean_input($input['position'] ?? ''), 
            clean_input($input['institution'] ?? ''), 
            clean_input($input['city'] ?? '')
        ]);
        
        echo json_encode(['status' => 'success']);
        exit;
    }

    // ====================================================
    // 4. ADDRESS MANAGEMENT
    // ====================================================

    if ($action === 'add_address') {
        if (!isset($_SESSION['user_id'])) throw new Exception("Login required");
        
        // If set as default, unset others
        if (!empty($input['is_default'])) {
            $pdo->prepare("UPDATE user_addresses SET is_default=0 WHERE user_id=?")->execute([$_SESSION['user_id']]);
        }
        
        $stmt = $pdo->prepare("INSERT pro INTO user_addresses (user_id, label, recipient_name, phone, address_line, city, postal_code, is_default) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_SESSION['user_id'], clean_input($input['label']), clean_input($input['recipient']), 
            clean_input($input['phone']), clean_input($input['address']), clean_input($input['city']), 
            clean_input($input['zip']), !empty($input['is_default']) ? 1 : 0
        ]);
        echo json_encode(['status' => 'success']);
        exit;
    }
    
    if ($action === 'delete_address') {
        if (!isset($_SESSION['user_id'])) throw new Exception("Login required");
        $pdo->prepare("DELETE FROM user_addresses WHERE id=? AND user_id=?")->execute([(int)$input['id'], $_SESSION['user_id']]);
        echo json_encode(['status' => 'success']);
        exit;
    }

    // ====================================================
    // 5. ORDER PLACEMENT (Pre-Order Logic)
    // ====================================================

    if ($action === 'place_order') {
        if (!isset($_SESSION['user_id'])) throw new Exception("Login required");
        
        // 1. Get Selected Address
        $addrId = (int)($input['address_id'] ?? 0);
        $addrStmt = $pdo->prepare("SELECT * FROM user_addresses WHERE id = ? AND user_id = ?");
        $addrStmt->execute([$addrId, $_SESSION['user_id']]);
        $addr = $addrStmt->fetch();
        if (!$addr) throw new Exception("Invalid Shipping Address. Please add one in settings.");
        
        // 2. Load Business Rules
        $s = get_sys_settings($pdo);
        $moq2 = (int)($s['moq_tier_2'] ?? 20);
        $moq3 = (int)($s['moq_tier_3'] ?? 200);
        $disc2 = (float)($s['discount_tier_2'] ?? 10) / 100;
        $disc3 = (float)($s['discount_tier_3'] ?? 20) / 100;
        $deadline = $s['early_bird_deadline'] ?? '2026-04-30';
        
        // 3. Process Cart
        $cart = $input['cart'] ?? [];
        if (empty($cart)) throw new Exception("Cart is empty");

        $totalQty = 0; foreach ($cart as $i) $totalQty += (int)$i['qty'];
        
        // 4. Calculate Tier & Discount
        $tier = 1; $discount = 0;
        if ($totalQty >= $moq3) { $tier = 3; $discount = $disc3; }
        elseif ($totalQty >= $moq2) { $tier = 2; $discount = $disc2; }

        $is_early = (date('Y-m-d') <= $deadline);
        if ($is_early) $discount += 0.05;

        $pdo->beginTransaction();
        
        // 5. Create Order (With Address Snapshot)
        $snapshot = json_encode($addr);
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, shipping_city, shipping_snapshot, total_amount, paid_amount, status, payment_status, shipment_status, tier_level, is_early_bird) VALUES (?, ?, ?, 0, 0, 'pending', 'unpaid', 'processing', ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $addr['city'], $snapshot, $tier, $is_early]);
        $orderId = $pdo->lastInsertId();

        // 6. Create Order Items
        $grandTotal = 0;
        $itemStmt = $pdo->prepare("INSERT order_items (order_id, book_id, quantity, unit_price, total_price) VALUES (?, ?, ?, ?, ?)");
        
        foreach ($cart as $item) {
            $b = $pdo->prepare("SELECT base_price FROM books WHERE id = ?");
            $b->execute([$item['id']]); 
            $bData = $b->fetch();
            if (!$bData) continue;

            $price = $bData['base_price'] * (1 - $discount);
            $total = $price * $item['qty'];
            $grandTotal += $total;
            
            $itemStmt->execute([$orderId, $item['id'], $item['qty'], $price, $total]);
        }
        
        $pdo->prepare("UPDATE orders SET total_amount = ? WHERE id = ?")->execute([$grandTotal, $orderId]);
        $pdo->commit();
        echo json_encode(['status' => 'success', 'order_id' => $orderId]);
        exit;
    }

    // ====================================================
    // 6. PAYMENTS & LOGISTICS
    // ====================================================

    // User Uploads Payment Proof
    if ($action === 'upload_payment' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_SESSION['user_id'])) throw new Exception("Login required");
        $oid = $_POST['order_id'];
        $amt = (float)$_POST['amount'];
        
        if (isset($_FILES['proof']) && $_FILES['proof']['error'] === 0) {
            $fn = 'pay_' . $oid . '_' . time() . '.' . pathinfo($_FILES['proof']['name'], PATHINFO_EXTENSION);
            if (!is_dir('uploads')) mkdir('uploads');
            move_uploaded_file($_FILES['proof']['tmp_name'], 'uploads/' . $fn);
            
            $pdo->prepare("INSERT INTO transactions (order_id, user_id, amount, proof_image, status, note) VALUES (?, ?, ?, ?, 'pending', 'Installment')")->execute([$oid, $_SESSION['user_id'], $amt, $fn]);
            $pdo->prepare("UPDATE orders SET payment_status='deposit_pending' WHERE id=? AND payment_status='unpaid'")->execute([$oid]);
            
            header("Location: index.php?page=profile&msg=uploaded"); exit;
        } throw new Exception("Upload failed");
    }

    // User Confirms Arrival
    if ($action === 'confirm_arrival' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_SESSION['user_id'])) throw new Exception("Login required");
        $oid = $_POST['order_id'];
        
        if (isset($_FILES['proof']) && $_FILES['proof']['error'] === 0) {
            $fn = 'arrival_' . $oid . '_' . time() . '.' . pathinfo($_FILES['proof']['name'], PATHINFO_EXTENSION);
            if (!is_dir('uploads')) mkdir('uploads');
            move_uploaded_file($_FILES['proof']['tmp_name'], 'uploads/' . $fn);
            
            $pdo->prepare("UPDATE orders SET arrival_proof=?, shipment_status='completed' WHERE id=?")->execute([$fn, $oid]);
            header("Location: index.php?page=profile&msg=arrived"); exit;
        } throw new Exception("Upload failed");
    }

    // ====================================================
    // 7. ADMIN MANAGEMENT
    // ====================================================

    if (in_array($action, ['add_book', 'edit_book', 'delete_book', 'update_settings', 'ban_user', 'verify_payment', 'update_shipment'])) {
        if (($_SESSION['role'] ?? '') !== 'admin') throw new Exception("Access Denied");

        // Book Management
        if ($action === 'delete_book') {
            $pdo->prepare("DELETE FROM books WHERE id = ?")->execute([(int)$input['id']]);
        }
        elseif ($action === 'add_book') {
            $stmt = $pdo->prepare("INSERT INTO books (title, isbn, category, base_price, stock, cover_image) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([clean_input($input['title']), clean_input($input['isbn']), clean_input($input['category']), (float)$input['price'], (int)$input['stock'], clean_input($input['image'])]);
        }
        elseif ($action === 'edit_book') {
            $stmt = $pdo->prepare("UPDATE books SET title=?, isbn=?, category=?, base_price=?, stock=?, cover_image=? WHERE id=?");
            $stmt->execute([clean_input($input['title']), clean_input($input['isbn']), clean_input($input['category']), (float)$input['price'], (int)$input['stock'], clean_input($input['image']), (int)$input['id']]);
        }

        // User Management
        elseif ($action === 'ban_user') {
            $pdo->prepare("UPDATE users SET is_banned = 1 WHERE id = ?")->execute([(int)$input['user_id']]);
        }

        // Settings Management
        elseif ($action === 'update_settings') {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("INSERT INTO system_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
            foreach ($input as $k => $v) $stmt->execute([$k, $v]);
            $pdo->commit();
        }

        // Payment Verification
        elseif ($action === 'verify_payment') {
            $tid = $input['transaction_id'];
            $st = $input['status'];
            
            $pdo->beginTransaction();
            $pdo->prepare("UPDATE transactions SET status=? WHERE id=?")->execute([$st, $tid]);
            
            if ($st === 'verified') {
                $t = $pdo->prepare("SELECT order_id, amount FROM transactions WHERE id=?"); 
                $t->execute([$tid]); 
                $tr = $t->fetch();
                
                $pdo->prepare("UPDATE orders SET paid_amount = paid_amount + ? WHERE id=?")->execute([$tr['amount'], $tr['order_id']]);
                
                $o = $pdo->prepare("SELECT total_amount, paid_amount FROM orders WHERE id=?"); 
                $o->execute([$tr['order_id']]); 
                $ord = $o->fetch();
                
                $nst = ($ord['paid_amount'] >= $ord['total_amount']) ? 'paid' : 'partial';
                $pdo->prepare("UPDATE orders SET payment_status=? WHERE id=?")->execute([$nst, $tr['order_id']]);
            }
            $pdo->commit();
        }

        // Logistics Update
        elseif ($action === 'update_shipment') {
            $pdo->prepare("UPDATE orders SET shipment_status=?, estimated_delivery=? WHERE id=?")->execute([$input['status'], $input['date'], $input['order_id']]);
        }

        echo json_encode(['status' => 'success']);
        exit;
    }

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>