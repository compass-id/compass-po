<?php
// view_admin.php - ULTIMATE CEO DASHBOARD (Fixed Top Clients & Product Revenue)
if (($_SESSION['role'] ?? '') !== 'admin') die("Access Denied");
$pdo = getDB();

// ==========================================
// 1. ADVANCED DATA MINING (KPIs)
// ==========================================
$total_orders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$total_rev = $pdo->query("SELECT SUM(total_amount) FROM orders WHERE status != 'cancelled'")->fetchColumn() ?: 0;
$pending_orders = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'")->fetchColumn();
$total_leads = $pdo->query("SELECT COUNT(*) FROM interest_forms")->fetchColumn();
$high_intent_leads = $pdo->query("SELECT COUNT(*) FROM interest_forms WHERE interest_level = 'Tertarik'")->fetchColumn();

// --- CALCULATED METRICS ---
$aov = $total_orders > 0 ? ($total_rev / $total_orders) : 0; 
$unpaid_cash = $pdo->query("SELECT SUM(total_amount) FROM orders WHERE payment_status != 'paid' AND status != 'cancelled'")->fetchColumn() ?: 0;
$early_bird_count = $pdo->query("SELECT COUNT(*) FROM orders WHERE is_early_bird = 1")->fetchColumn();

// ==========================================
// 2. SMARTER ANALYTICS QUERIES
// ==========================================

// A. REGIONAL PERFORMANCE (Revenue vs Interest vs Orders)
$regionData = $pdo->query("
    SELECT city,
           SUM(orders) as orders,
           SUM(leads) as leads,
           SUM(revenue) as revenue
    FROM (
        SELECT shipping_city as city, COUNT(*) as orders, 0 as leads, SUM(total_amount) as revenue 
        FROM orders WHERE status != 'cancelled' GROUP BY shipping_city
        UNION ALL
        SELECT city, 0 as orders, COUNT(*) as leads, 0 as revenue FROM interest_forms GROUP BY city
    ) x
    GROUP BY city
    ORDER BY revenue DESC, leads DESC
")->fetchAll();

// B. TOP PRODUCTS (Revenue Share)
$top_books = $pdo->query("
    SELECT b.title, 
           SUM(oi.quantity) as sold, 
           SUM(oi.total_price) as revenue,
           (SUM(oi.total_price) / (SELECT SUM(total_amount) FROM orders WHERE status != 'cancelled') * 100) as rev_share
    FROM order_items oi 
    JOIN books b ON oi.book_id = b.id 
    JOIN orders o ON oi.order_id = o.id
    WHERE o.status != 'cancelled'
    GROUP BY oi.book_id, b.title 
    ORDER BY revenue DESC
")->fetchAll();

// C. RECENT ACTIVITY
$recent_orders = $pdo->query("SELECT o.id, u.name, o.created_at FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC")->fetchAll();
$low_stock = $pdo->query("SELECT title, stock FROM books WHERE stock < 50 LIMIT 3")->fetchAll();
$recent_tickets = $pdo->query("SELECT t.subject, u.name, t.created_at FROM tickets t JOIN users u ON t.user_id = u.id ORDER BY t.created_at DESC")->fetchAll();

// D. TOP POTENTIAL SCHOOLS
$leads = $pdo->query("SELECT * FROM interest_forms ORDER BY student_count DESC")->fetchAll();

// E. TOP VALUE CLIENTS (FIXED: Exclude Rejected/Cancelled/Pending)
$top_orders_list = $pdo->query("
    SELECT o.id, o.created_at, u.name, u.institution, o.shipping_city as city, o.total_amount, SUM(oi.quantity) as total_qty 
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
    JOIN order_items oi ON o.id = oi.order_id 
    WHERE o.status NOT IN ('cancelled', 'rejected', 'pending')
    GROUP BY o.id, u.name, u.institution, o.shipping_city, o.total_amount 
    ORDER BY o.total_amount DESC LIMIT 10
")->fetchAll();

// F. UNcompleted PRE-ORDERS
$uncompleted_preorders = $pdo->query("
    SELECT o.id, u.name, o.shipping_status, o.created_at
    FROM orders o
    JOIN users u ON o.user_id = u.id
    WHERE o.is_early_bird = 1 
    AND o.shipping_status NOT IN ('completed', 'cancelled', 'delivering') 
    AND o.status NOT IN ('cancelled', 'rejected')
    ORDER BY o.created_at ASC
")->fetchAll();


// =========================================================
// 🧠 "STRATEGIC AI ENGINE" (6-Point Analysis)
// =========================================================
$insights = [];

// 1. MARKETING
$conversion_rate = $total_leads > 0 ? round(($total_orders / $total_leads) * 100, 1) : 0;
if ($high_intent_leads > $total_orders) {
    $gap = $high_intent_leads - $total_orders;
    $insights[] = ['cat'=>'Marketing', 'icon'=>'megaphone', 'color'=>'#007AFF', 'title'=>'Conversion Opportunity', 'msg'=>"<strong>$gap High-Intent Leads</strong> haven't purchased. Launch a specific 'School Visit' offer to close them."];
} else {
    $insights[] = ['cat'=>'Marketing', 'icon'=>'stats-chart', 'color'=>'#34C759', 'title'=>'Funnel Healthy', 'msg'=>"Conversion rate is <strong>$conversion_rate%</strong>. Maintain current ad spend."];
}

// 2. SALES
if (!empty($regionData) && $regionData[0]['orders'] == 0 && $regionData[0]['leads'] > 3) {
    $insights[] = ['cat'=>'Sales', 'icon'=>'map', 'color'=>'#AF52DE', 'title'=>"Target: {$regionData[0]['city']}", 'msg'=>"<strong>{$regionData[0]['city']}</strong> has high interest but ZERO sales. Deploy a sales rep immediately."];
} else {
    $insights[] = ['cat'=>'Sales', 'icon'=>'globe', 'color'=>'#FF9500', 'title'=>'Expand Territory', 'msg'=>"Sales are concentrated. Offer 'Free Shipping' to the 2nd top city to boost growth."];
}

// 3. FINANCE
$cash_risk_ratio = $total_rev > 0 ? round(($unpaid_cash / $total_rev) * 100) : 0;
if ($cash_risk_ratio > 40) {
    $insights[] = ['cat'=>'Finance', 'icon'=>'cash', 'color'=>'#FF3B30', 'title'=>'Cash Flow Risk', 'msg'=>"<strong>$cash_risk_ratio%</strong> of revenue is Unpaid. Pause shipments for overdue accounts."];
} else {
    $insights[] = ['cat'=>'Finance', 'icon'=>'wallet', 'color'=>'#34C759', 'title'=>'Strong Liquidity', 'msg'=>"Cash flow is healthy. Avg Order Value is <strong>Rp " . number_format($aov/1000) . "k</strong>."];
}

// 4. OPERATIONS
if ($pending_orders >= 5) {
    $insights[] = ['cat'=>'Ops', 'icon'=>'time', 'color'=>'#FF3B30', 'title'=>'Bottleneck Alert', 'msg'=>"<strong>$pending_orders orders</strong> need verification. Delays >24h hurt trust. Clear queue now."];
} else {
    $insights[] = ['cat'=>'Ops', 'icon'=>'checkmark-circle', 'color'=>'#34C759', 'title'=>'Operations Smooth', 'msg'=>"Order processing is efficient. Team is performing well."];
}

// 5. PRE-ORDER
$eb_ratio = $total_orders > 0 ? round(($early_bird_count / $total_orders) * 100) : 0;
$insights[] = ['cat'=>'Strategy', 'icon'=>'star', 'color'=>'#AF52DE', 'title'=>'Early Bird Stats', 'msg'=>"<strong>$eb_ratio%</strong> of orders are Early Bird. " . ($eb_ratio < 30 ? "Increase urgency to boost this." : "Strategy is effective.")];

// 6. INVENTORY
if (!empty($low_stock)) {
    $item = $low_stock[0]['title'];
    $insights[] = ['cat'=>'Stock', 'icon'=>'cube', 'color'=>'#FF3B30', 'title'=>'Low Inventory', 'msg'=>"<strong>$item</strong> is critical. Re-order immediately."];
} else {
    $insights[] = ['cat'=>'Stock', 'icon'=>'server', 'color'=>'#34C759', 'title'=>'Inventory Good', 'msg'=>"Stock levels are optimal for current sales velocity."];
}
?>

<div class="stat-grid">
    <div class="card stat-card border-blue">
        <div class="stat-label">Total Revenue</div>
        <div class="stat-value">Rp <?php echo number_format($total_rev, 0); ?></div>
        <div class="stat-trend text-up"><ion-icon name="wallet"></ion-icon> Real-time</div>
    </div>
    <div class="card stat-card border-green">
        <div class="stat-label">Total Orders</div>
        <div class="stat-value"><?php echo number_format($total_orders); ?></div>
        <div class="stat-trend text-up"><ion-icon name="cart"></ion-icon> <?php echo $pending_orders; ?> pending</div>
    </div>
    <div class="card stat-card border-orange">
        <div class="stat-label">Pending Action</div>
        <div class="stat-value"><?php echo number_format($pending_orders); ?></div>
        <div class="stat-trend text-neutral">Requires update</div>
    </div>
    <div class="card stat-card border-purple">
        <div class="stat-label">High Intent Leads</div>
        <div class="stat-value"><?php echo number_format($high_intent_leads); ?></div>
        <div class="stat-trend text-up"><ion-icon name="people"></ion-icon> Hot Prospects</div>
    </div>
</div>

<div class="dashboard-grid">
    
    <div class="main-column">
        
        

        <div class="card chart-card">
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <h3>Market Penetration</h3>
                <span class="badge-blue" style="font-size:11px;">Green: Revenue | Blue: Leads | Orange: Orders</span>
            </div>
            <div class="chart-container" style="height:350px;">
                <canvas id="regionChart"></canvas>
            </div>
        </div>

        <div class="card">
            <h3>🏆 Top 10 High Value Clients</h3>
            <table style="width:100%; font-size:13px; border-collapse:collapse;">
                <tr style="text-align:left; border-bottom:1px solid #eee; color:#666;">
                    <th style="padding:10px;">No.</th>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>User</th>
                    <th>Institution</th>
                    <th>Region</th>
                    <th>Qty</th>
                    <th>Revenue</th>
                    <th>Action</th>
                </tr>
                <?php $nom = 1; foreach($top_orders_list as $to): ?>
                <tr style="border-bottom:1px solid #f9f9f9;">
                    <td style="padding:10px; font-weight:bold;"><?php echo $nom++; ?></td>
                    <td>#<?php echo $to['id']; ?></td>
                    <td><?php echo $to['created_at']; ?></td>
                    <td><strong><?php echo htmlspecialchars($to['name']); ?></strong></td>
                    <td style="color:#555;"><?php echo htmlspecialchars($to['institution'] ?? '-'); ?></td>
                    <td style="color:#555;"><?php echo htmlspecialchars($to['city'] ?? '-'); ?></td>
                    <td style="font-weight:bold; color:#007AFF;"><?php echo number_format($to['total_qty']); ?> Cps</td>
                    <td style="font-weight:bold; color:#333;">Rp <?php echo number_format($to['total_amount']); ?></td>
                    <td><a href="?page=admin_order_detail&id=<?php echo $to['id']; ?>" class="btn btn-sm" style="padding:4px 12px; background:#f0f0f0; color:#333;">View</a></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        
        <div class="card">
            
            <h3>Recent Activity</h3>
            <div style="font-size:13px; color:#666; line-height:1.6;">
                <?php foreach($recent_orders as $ro): ?>
                    <div style="border-bottom:1px solid #eee; padding:10px 0; display:flex; justify-content:space-between;">
                        <span>New order <strong>#<?php echo $ro['id']; ?></strong><br><small><?php echo htmlspecialchars($ro['name']); ?></small></span>
                        <span style="font-size:11px; color:#999;"><?php echo date('d M', strtotime($ro['created_at'])); ?></span>
                    </div>
                <?php endforeach; ?>
                
                <?php foreach($low_stock as $ls): ?>
                    <div style="border-bottom:1px solid #eee; padding:10px 0; color:#d32f2f; background:#fff5f5; border-radius:6px; margin-top:5px; padding-left:10px;">
                        <ion-icon name="alert-circle"></ion-icon> <strong><?php echo htmlspecialchars($ls['title']); ?></strong> low stock (<?php echo $ls['stock']; ?>)
                    </div>
                <?php endforeach; ?>

                <?php foreach($recent_tickets as $rt): ?>
                    <div style="border-bottom:1px solid #eee; padding:10px 0;">
                        <span>🎟️ Ticket from <strong><?php echo htmlspecialchars($rt['name']); ?></strong></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

    <div class="side-column">
        
        <div class="card" style="background: linear-gradient(to right, #f8f9fa, #ffffff);">
            <h3 style="display:flex; align-items:center; gap:8px; margin-bottom:15px;">
                <ion-icon name="sparkles" style="color:#AF52DE;"></ion-icon> AI Insights & Business Strategy Hub
            </h3>
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:15px;">
                <?php foreach($insights as $inf): ?>
                <div style="background:white; padding:15px; border-radius:10px; border-top: 4px solid <?php echo $inf['color']; ?>; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                    <div style="display:flex; align-items:center; gap:6px; font-size:11px; font-weight:bold; color:<?php echo $inf['color']; ?>; text-transform:uppercase; margin-bottom:8px;">
                        <ion-icon name="<?php echo $inf['icon']; ?>"></ion-icon> <?php echo $inf['cat']; ?>
                    </div>
                    <div style="font-weight:bold; font-size:13px; color:#333; margin-bottom:5px;"><?php echo $inf['title']; ?></div>
                    <div style="font-size:12px; color:#666; line-height:1.4;"><?php echo $inf['msg']; ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="card">
            <h3>Top Potential Schools</h3>
            <div class="responsive-table">
                <table style="font-size: 12px; width:100%;">
                    <thead>
                        <tr style="color:#888;">
                            <th style="padding-bottom:8px;">School</th>
                            <th style="padding-bottom:8px; text-align:right;">Est. Students</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($leads as $l): ?>
                        <tr>
                            <td style="padding:8px 0;"><strong><?php echo htmlspecialchars($l['school_name']); ?></strong><br><small style="color:#888;"><?php echo htmlspecialchars($l['city']); ?></small></td>
                            <td style="padding:8px 0; text-align:right;"><span class="badge-blue"><?php echo $l['student_count']; ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <a href="?page=admin_forms" class="btn btn-sm btn-outline" style="width:100%; margin-top:10px; text-align:center; display:block;">View Leads</a>
        </div>
        
        <div class="card chart-card">
            <h3>Revenue by Product</h3>
            <div class="chart-container" style="height:250px; display:flex; justify-content:center;">
                <canvas id="bookChart"></canvas>
            </div>
                <h5>Total Revenue: Rp <?php echo number_format($total_rev, 0); ?></h5>
            <div style="margin-top:15px; max-height:200px; overflow-y:auto;">
                <?php foreach($top_books as $bk): ?>
                <div style="display:flex; justify-content:space-between; font-size:12px; margin-bottom:8px; border-bottom:1px solid #f5f5f5; padding-bottom:5px;">
                    <span style="max-width:60%;"><?php echo htmlspecialchars($bk['title']); ?></span>
                    <span style="text-align:right;">
                        <strong style="color:#333;"><?php echo number_format($bk['sold']); ?> Sold</strong> 
                        <span style="color:#888; font-size:11px;">(<?php echo number_format($bk['rev_share'], 1); ?>% of share)</span>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="card">
            <h3 style="color:#AF52DE;">⏳ Pending Pre-Orders</h3>
            <?php if(empty($uncompleted_preorders)): ?>
                <div style="color:#888; font-size:13px;">No pending early bird shipments.</div>
            <?php else: ?>
                <div style="font-size:12px; color:#666;">
                    <?php foreach($uncompleted_preorders as $po): ?>
                    <div style="border-bottom:1px solid #eee; padding:8px 0; display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <strong>#<?php echo $po['id']; ?></strong> <?php echo htmlspecialchars($po['name']); ?><br>
                            <span style="color:<?php echo $po['shipping_status']=='shipping'?'orange':'#999'; ?>">
                                • <?php echo ucfirst($po['shipping_status']); ?>
                            </span>
                        </div>
                        <a href="?page=admin_order_detail&id=<?php echo $po['id']; ?>" class="btn btn-sm btn-outline">Check</a>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// 1. REGION BAR CHART (Revenue, Leads, and Orders)
const ctxRegion = document.getElementById('regionChart');
if (ctxRegion) {
    new Chart(ctxRegion, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_column($regionData, 'city') ?: []); ?>,
            datasets: [
                {
                    label: 'Revenue (Millions)',
                    data: <?php 
                        $rev = array_map(function($r){ return $r/1000000; }, array_column($regionData, 'revenue'));
                        echo json_encode($rev ?: []); 
                    ?>,
                    backgroundColor: '#34C759',
                    borderRadius: 4,
                    order: 3,
                    yAxisID: 'y'
                },
                {
                    label: 'Orders', 
                    data: <?php echo json_encode(array_column($regionData, 'orders') ?: []); ?>,
                    backgroundColor: '#FF9500',
                    borderRadius: 4,
                    order: 2,
                    yAxisID: 'y1'
                },
                {
                    label: 'Leads',
                    data: <?php echo json_encode(array_column($regionData, 'leads') ?: []); ?>,
                    backgroundColor: 'rgba(0, 122, 255, 0.1)',
                    borderColor: '#007AFF',
                    borderWidth: 2,
                    type: 'line',
                    order: 1,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            scales: {
                y: { 
                    beginAtZero: true, 
                    title: { display: true, text: 'Revenue (Millions)' },
                    grid: { borderDash: [2, 2] }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    title: { display: true, text: 'Count (Orders/Leads)' },
                    grid: { drawOnChartArea: false }
                }
            }
        }
    });
}

// 2. PRODUCT CHART
const ctxBook = document.getElementById('bookChart');
if(ctxBook) {
    new Chart(ctxBook, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode(array_column($top_books, 'title') ?: []); ?>,
            datasets: [{
                data: <?php echo json_encode(array_column($top_books, 'revenue') ?: []); ?>,
                backgroundColor: ['#007AFF', '#34C759', '#FF9500', '#AF52DE', '#FF2D55', '#5856D6'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: { 
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            let val = ctx.raw;
                            return ' Rp ' + new Intl.NumberFormat('id-ID').format(val);
                        }
                    }
                }
            }
        }
    });
}
</script>