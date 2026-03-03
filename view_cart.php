<?php
// view_cart.php - HYBRID: ORIGINAL FEATURES + API SUPPORT
if (session_status() === PHP_SESSION_NONE) session_start();
$pdo = getDB();

// --- CONTROLLER ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    
    // 1. PLACE ORDER LOGIC
    if ($_POST['action'] === 'place_order') {
        $uid = $_SESSION['user_id'] ?? 0;
        $address_id = (int)($_POST['address_id'] ?? 0);
        $cart = $_SESSION['cart'] ?? [];

        // Validation
        if ($uid <= 0) {
             echo "<script>alert('Your session has expired. Please login again.'); window.location='?page=login';</script>"; exit;
        }
        if (empty($cart)) {
            echo "<script>alert('Cart is empty'); window.location='?page=cart';</script>"; exit;
        }
        if ($address_id <= 0) {
            echo "<script>alert('Please select a shipping address'); window.location='?page=cart';</script>"; exit;
        }

        // --- FETCH ADDRESS DETAILS ---
        $stmt_addr = $pdo->prepare("SELECT * FROM user_addresses WHERE id = ? AND user_id = ?");
        $stmt_addr->execute([$address_id, $uid]);
        $addr_data = $stmt_addr->fetch(PDO::FETCH_ASSOC);
        
        if (!$addr_data) {
            echo "<script>alert('Invalid address selected.'); window.location='?page=cart';</script>"; exit;
        }
        
        $shipping_snapshot = json_encode($addr_data);
        $shipping_city = $addr_data['city'] ?? '';

        // --- RE-CALCULATE TOTALS (DB + API) ---
        $rows = $pdo->query("SELECT * FROM system_settings")->fetchAll();
        $settings = [];
        foreach ($rows as $r) $settings[$r['setting_key']] = $r['setting_value'];
        $eb_deadline = $settings['early_bird_deadline'] ?? '2026-04-30';
        $today = date('Y-m-d');
        
        // 1. DB Items
        $cart_ids = array_keys($cart);
        $db_ids = array_filter($cart_ids, 'is_numeric');
        $db_books = [];
        
        if (!empty($db_ids)) {
            $in = str_repeat('?,', count($db_ids) - 1) . '?';
            $stmt = $pdo->prepare("SELECT * FROM books WHERE id IN ($in)");
            $stmt->execute($db_ids);
            $db_books = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // 2. API Items (Fetch if needed)
        $found_db_ids = array_column($db_books, 'id');
        $missing_ids = array_diff($cart_ids, $found_db_ids);
        $api_items_map = [];

        if (!empty($missing_ids)) {
            $ch = curl_init("https://api452.rallyz.co.kr/api/savewon/goods");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            $response = curl_exec($ch);
            // curl_close($ch); // PHP 8+ auto-closes
            
            $apiData = json_decode($response, true);
            if (isset($apiData['Data'])) {
                foreach ($apiData['Data'] as $g) {
                    $raw_id = $g['GDS_ID'];
                    $pre_id = 'ext_' . $g['GDS_ID'];
                    // Map API price
                    if (in_array($raw_id, $missing_ids) || in_array($pre_id, $missing_ids)) {
                        $key = in_array($pre_id, $missing_ids) ? $pre_id : $raw_id;
                        $api_items_map[$key] = [
                            'price' => $g['GDS_PRICE'] * 12, // Exchange Rate
                            'id_val' => 0 // API items get ID 0 in DB or specific logic
                        ];
                    }
                }
            }
        }

        // 3. Process All Items
        $total_qty = 0;
        $gross_total = 0;
        $order_items_data = [];

        // Add DB Items
        foreach ($db_books as $b) {
            $qty = $cart[$b['id']];
            $is_eb = ($eb_deadline && $today <= $eb_deadline);
            $price = $is_eb ? ($b['base_price'] * 0.95) : $b['base_price'];
            
            $total_qty += $qty;
            $gross_total += $price * $qty;
            
            $order_items_data[] = ['id' => $b['id'], 'qty' => $qty, 'price' => $price];
        }

        // Add API Items
        foreach ($missing_ids as $mid) {
            if (isset($api_items_map[$mid])) {
                $qty = $cart[$mid];
                $price = $api_items_map[$mid]['price'];
                
                $total_qty += $qty;
                $gross_total += $price * $qty;
                
                $order_items_data[] = ['id' => 0, 'qty' => $qty, 'price' => $price]; // ID 0 for non-DB items
            }
        }

        // Tier Discount
        $tiers = $pdo->query("SELECT * FROM tier_rules ORDER BY min_qty ASC")->fetchAll();
        $current_tier = ['discount_percent' => 0];
        foreach ($tiers as $tier) {
            if ($total_qty >= $tier['min_qty']) $current_tier = $tier;
        }
        $discount = ($gross_total * $current_tier['discount_percent']) / 100;
        $net_total = $gross_total - $discount;

        // MOQ Check
        $moq = $settings['global_moq'] ?? 10;
        if ($total_qty < $moq) {
            echo "<script>alert('Minimum Order Quantity ($moq) not met.'); window.location='?page=cart';</script>"; exit;
        }

        // --- INSERT INTO DB ---
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("INSERT INTO orders (user_id, shipping_snapshot, shipping_city, total_amount, status, payment_status, created_at) VALUES (?, ?, ?, ?, 'pending', 'unpaid', NOW())");
            $stmt->execute([$uid, $shipping_snapshot, $shipping_city, $net_total]);
            $order_id = $pdo->lastInsertId();

            // Insert Items
            $stmt_item = $pdo->prepare("INSERT INTO order_items (order_id, book_id, quantity, unit_price, total_price) VALUES (?, ?, ?, ?, ?)");
            foreach ($order_items_data as $item) {
                $line_total = $item['qty'] * $item['price'];
                $stmt_item->execute([$order_id, $item['id'], $item['qty'], $item['price'], $line_total]);
            }

            $pdo->commit();
            
            // Success
            unset($_SESSION['cart']);
            echo "<script>alert('Order placed successfully!'); window.location='?page=profile';</script>";
            exit;

        } catch (Exception $e) {
            $pdo->rollBack();
            $error_msg = addslashes($e->getMessage());
            echo "<script>alert('Error placing order: $error_msg'); window.location='?page=cart';</script>";
            exit;
        }
    }

    // 2. CART UPDATE LOGIC
    $id = $_POST['book_id'] ?? 0; // Removed (int) cast to support API string IDs
    if ($id) {
        if ($_POST['action'] === 'add_to_cart') {
            $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
        } elseif ($_POST['action'] === 'update_cart' || $_POST['action'] === 'update_qty') {
            if (isset($_POST['delta'])) {
                $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + (int)$_POST['delta'];
            } elseif (isset($_POST['qty'])) {
                $_SESSION['cart'][$id] = (int)$_POST['qty'];
            }
            if ($_SESSION['cart'][$id] <= 0) unset($_SESSION['cart'][$id]);
        }
    }

    if (isset($_GET['ajax']) || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest')) exit;
    echo "<script>window.location='?page=cart';</script>";
    exit;
}

// AJAX Helper
if (isset($_GET['ajax']) && $_GET['ajax'] === 'get_count') {
    echo array_sum($_SESSION['cart'] ?? []);
    exit;
}

// --- VIEW ---
$cart_items = $_SESSION['cart'] ?? [];
$uid = $_SESSION['user_id'] ?? 0;

// Config
$settings = [];
$rows = $pdo->query("SELECT * FROM system_settings")->fetchAll();
foreach ($rows as $r) $settings[$r['setting_key']] = $r['setting_value'];
$MOQ = $settings['global_moq'] ?? 10;
$eb_deadline = $settings['early_bird_deadline'] ?? '2026-04-30';

if (empty($cart_items)) {
    echo "<div class='container' style='text-align: center; padding: 60px;'>
        <div style='font-size: 60px; color: #ddd; margin-bottom: 20px;'><ion-icon name='cart-outline'></ion-icon></div>
        <h3>Your cart is empty</h3>
        <p style='color: #888; margin-bottom: 20px;'>Start adding books to unlock discounts.</p>
        <a href='?page=catalog' class='btn' style='width: auto; display: inline-block;'>Browse Catalog</a>
    </div>";
    return;
}

// Data Fetching (Hybrid)
$cart_ids = array_keys($cart_items);
$db_ids = array_filter($cart_ids, 'is_numeric');
$books_display = [];

// 1. Fetch DB Items
if (!empty($db_ids)) {
    $in = str_repeat('?,', count($db_ids) - 1) . '?';
    $stmt = $pdo->prepare("SELECT * FROM books WHERE id IN ($in)");
    $stmt->execute($db_ids);
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($books as $b) {
        $b['is_external'] = false;
        $books_display[] = $b;
    }
}

// 2. Fetch API Items (If needed)
$found_db_ids = array_column($books_display, 'id');
$missing_ids = array_diff($cart_ids, $found_db_ids);

if (!empty($missing_ids)) {
    $ch = curl_init("https://api452.rallyz.co.kr/api/savewon/goods");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $response = curl_exec($ch);
    $apiData = json_decode($response, true);

    if (isset($apiData['Data'])) {
        foreach ($apiData['Data'] as $g) {
            $raw_id = $g['GDS_ID'];
            $pre_id = 'ext_' . $g['GDS_ID'];
            
            if (in_array($raw_id, $missing_ids) || in_array($pre_id, $missing_ids)) {
                $matched_id = in_array($pre_id, $missing_ids) ? $pre_id : $raw_id;
                $books_display[] = [
                    'id' => $matched_id,
                    'title' => $g['PB_NM'],
                    'cover_image' => 'https://api452.rallyz.co.kr/' . $g['THUMB_URL'],
                    'base_price' => $g['GDS_PRICE'] * 12,
                    'is_external' => true
                ];
            }
        }
    }
}

// Fetch Addresses
$addrs = $pdo->prepare("SELECT * FROM user_addresses WHERE user_id=? ORDER BY is_default DESC");
$addrs->execute([$uid]);
$addresses = $addrs->fetchAll();

// Calculations
$total_qty = 0;
$gross_total = 0;
$today = date('Y-m-d');

foreach ($books_display as &$b) {
    $qty = $cart_items[$b['id']];
    $is_eb = ($eb_deadline && $today <= $eb_deadline);
    $price = $is_eb ? ($b['base_price'] * 0.95) : $b['base_price'];
    
    $total_qty += $qty;
    $gross_total += $price * $qty;
    
    $b['qty'] = $qty;
    $b['active_price'] = $price;
    $b['is_eb'] = $is_eb;
}
unset($b); // break ref

// Tier Logic
$tiers = $pdo->query("SELECT * FROM tier_rules ORDER BY min_qty ASC")->fetchAll();
$current_tier = ['name' => 'Retail', 'discount_percent' => 0];
$next_tier = null;

foreach ($tiers as $tier) {
    if ($total_qty >= $tier['min_qty']) {
        $current_tier = $tier;
    } else {
        if (!$next_tier) $next_tier = $tier; 
        break; 
    }
}

$discount = ($gross_total * $current_tier['discount_percent']) / 100;
$net_total = $gross_total - $discount;

// Progress Bar
$progress_pct = 0;
$progress_msg = "You are on the highest tier!";
if ($next_tier) {
    $needed = $next_tier['min_qty'] - $total_qty;
    $progress_pct = min(100, ($total_qty / $next_tier['min_qty']) * 100);
    $progress_msg = "Add <strong>$needed more</strong> to unlock {$next_tier['name']} ({$next_tier['discount_percent']}%)!";
} else {
    $progress_pct = 100;
}
?>

<div class="container" style="max-width: 1000px;">
    <h1>Your Cart</h1>
    <div style="display: grid; grid-template-columns: 1fr 350px; gap: 40px; align-items: start;">
        
        <div class="card" style="padding: 0; overflow: hidden;">
            <table style="width:100%; border-collapse:collapse;">
                <?php foreach($books_display as $item): ?>
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:15px; width:60px;">
                        <img src="<?php echo htmlspecialchars($item['cover_image'] ?? ''); ?>" style="width:50px; border-radius:4px;">
                    </td>
                    <td style="padding:15px;">
                        <strong><?php echo htmlspecialchars($item['title'] ?? 'Unknown'); ?></strong>
                        
                        <div style="color: #666; margin-top: 5px; display: flex; align-items: center; flex-wrap: wrap; gap: 5px;">
                            <span>Rp <?php echo number_format($item['active_price']); ?></span>
                            
                            <?php if($item['is_eb']): ?>
                                <span style="font-size:10px; background:#d32f2f; color:white; padding:3px 6px; border-radius:4px; display:inline-block; white-space:nowrap;">EARLY BIRD</span>
                            <?php endif; ?>
                            
                            <?php if($item['is_external']): ?>
                                <span style="font-size:10px; background:#34C759; color:white; padding:3px 6px; border-radius:4px; display:inline-block; white-space:nowrap;">IMPORT</span>
                            <?php endif; ?>
                        </div>

                    </td>
                    <td style="padding:15px;">
                        <div style="display:flex; align-items:center; background:#f5f5f7; border-radius:6px; border:1px solid #eee; width: fit-content;">
                            <form method="POST" style="margin:0;">
                                <input type="hidden" name="action" value="update_qty">
                                <input type="hidden" name="book_id" value="<?php echo $item['id']; ?>">
                                <input type="hidden" name="qty" value="<?php echo $item['qty'] - 1; ?>">
                                <button type="submit" style="border:none; background:none; padding:5px 12px; cursor:pointer;">−</button>
                            </form>
                            
                            <form method="POST" style="margin:0;">
                                <input type="hidden" name="action" value="update_qty">
                                <input type="hidden" name="book_id" value="<?php echo $item['id']; ?>">
                                <input type="number" name="qty" value="<?php echo $item['qty']; ?>" 
                                       onchange="this.form.submit()"
                                       style="min-width: 50px; border:none; background:none; text-align:center; font-weight:600; -moz-appearance: textfield;">
                            </form>
                            
                            <form method="POST" style="margin:0;">
                                <input type="hidden" name="action" value="update_qty">
                                <input type="hidden" name="book_id" value="<?php echo $item['id']; ?>">
                                <input type="hidden" name="qty" value="<?php echo $item['qty'] + 1; ?>">
                                <button type="submit" style="border:none; background:none; padding:5px 12px; cursor:pointer;">+</button>
                            </form>
                        </div>
                    </td>
                    <td style="padding:15px; text-align:right;">
                        Rp <?php echo number_format($item['active_price'] * $item['qty']); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div class="cart-summary card">
            <h3 style="margin-top: 0;">Order Summary</h3>
            
            <div style="display:flex; justify-content:space-between; margin-bottom:10px;">
                <span>Total Items</span>
                <strong><?php echo $total_qty; ?></strong>
            </div>
            <div style="display:flex; justify-content:space-between; margin-bottom:10px;">
                <span>Gross Total</span>
                <span>Rp <?php echo number_format($gross_total); ?></span>
            </div>

            <div style="display:flex; justify-content:space-between; margin-bottom:10px; color: var(--success); font-weight:bold;">
                <span>Tier: <?php echo strtoupper($current_tier['name']); ?></span>
                <span>- Rp <?php echo number_format($discount); ?></span>
            </div>

            <div style="background: #fff; padding: 15px; border-radius: 12px; border: 1px solid #eee; margin: 15px 0;">
                <div style="display: flex; justify-content: space-between; font-size: 10px; font-weight: 700; color: #888; margin-bottom: 5px;">
                    <span>Retail</span>
                    <span>Class Set</span>
                    <span>Partner</span>
                </div>
                <div style="background:#eee; height:8px; border-radius:4px; overflow:hidden;">
                    <div style="background:var(--primary); width:<?php echo $progress_pct; ?>%; height:100%;"></div>
                </div>
                <div style="font-size: 11px; text-align: center; color: var(--primary); margin-top: 5px;">
                    <?php echo $progress_msg; ?>
                </div>
            </div>

            <div style="border-top: 1px solid #eee; padding-top: 15px; display:flex; justify-content:space-between; font-size: 18px; font-weight: 800; margin-bottom: 20px;">
                <span>Net Total</span>
                <span>Rp <?php echo number_format($net_total); ?></span>
            </div>

            <?php if ($total_qty < $MOQ): ?>
                <div style="background:#fff3e0; border:1px solid #ffe0b2; color:#e65100; padding:15px; border-radius:8px; font-size:13px; margin-bottom:20px; text-align:center;">
                    ⚠️ <strong>Minimum Order Not Met</strong><br>
                    You need at least <?php echo $MOQ; ?> units.
                </div>
                <button disabled class="btn" style="width:100%; background:#ccc;">Place Order</button>
            <?php else: ?>
                <form method="POST" onsubmit="return confirm('Are you sure you want to place this order?');">
                    <input type="hidden" name="action" value="place_order">
                    
                    <label style="font-weight:bold; font-size:12px; display:block; margin-bottom:5px;">Ship To:</label>
                    <select name="address_id" style="width:100%; padding:10px; border-radius:6px; border:1px solid #ccc; margin-bottom: 15px;">
                        <?php foreach($addresses as $a): ?>
                            <option value="<?php echo $a['id']; ?>"><?php echo htmlspecialchars($a['label'] . " - " . $a['city']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    
                    <?php if(empty($addresses)): ?>
                        <div style="color:red; font-size:12px; margin-bottom:10px;">Add an address in your profile first.</div>
                        <button disabled class="btn" style="width:100%; background:#ccc;">Place Order</button>
                    <?php else: ?>
                        <button type="submit" class="btn" style="width:100%;">Place Order</button>
                    <?php endif; ?>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>