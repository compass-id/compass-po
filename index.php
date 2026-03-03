<?php
// index.php - FIXED MOBILE MENUS & ADDED NEW ADMIN ROUTES
session_start();
require_once 'config.php';
require_once 'security.php';

$page = $_GET['page'] ?? 'home';
$role = $_SESSION['role'] ?? 'public';
$user_id = $_SESSION['user_id'] ?? null;
$landing_pages = ['home', 'form', 'catalog', 'cart', 'login', 'register'];
$is_landing = in_array($page, $landing_pages);

// 2. ROUTING LOGIC (The Switch)
switch ($page) {
    // --- PUBLIC PAGES ---
    case 'home':
    case 'catalog':
        $content = 'view_catalog.php';
        $page_title = 'Catalog - Compass';
        break;
        
    case 'cart':
        $content = 'view_cart.php';
        $page_title = 'My Cart';
        break;

    case 'form':
        $content = 'view_form.php';
        $page_title = 'Interest Form';
        break;

    // --- AUTH PAGES ---
    case 'login':
        $content = 'view_login.php';
        $page_title = 'Login';
        break;

    case 'register':
        $content = 'view_register.php';
        $page_title = 'Register';
        break;

    // --- USER DASHBOARD ---
    case 'profile':
        $content = 'view_profile.php';
        $page_title = 'My Dashboard';
        break;

    case 'profile_settings':
        $content = 'view_profile_settings.php';
        $page_title = 'Address Book';
        break;

    case 'support':
        $content = 'view_support.php';
        $page_title = 'Support Tickets';
        break;

    case 'order_detail':
        $content = 'view_order_detail.php'; 
        $page_title = 'Order Details';
        break;

    // --- ADMIN PAGES ---
    case 'admin':
        $content = 'view_admin.php'; 
        $page_title = 'Admin Overview';
        break;

    case 'admin_orders':
        $content = 'view_admin_orders.php';
        $page_title = 'Manage Orders';
        break;

    case 'admin_order_detail':
        $content = 'view_admin_order_detail.php';
        $page_title = 'Order Details (Admin)';
        break;

    // *** NEW ROUTES ADDED HERE ***
    case 'admin_payments':
        $content = 'view_admin_payments.php';
        $page_title = 'Payment History';
        break;

    case 'admin_shipments':
        $content = 'view_admin_shipments.php';
        $page_title = 'Shipment Tracker';
        break;
    // *****************************
        
    case 'admin_forms':
        $content = 'view_admin_forms.php';
        $page_title = 'Interest Leads';
        break;

    case 'admin_tiers':
        $content = 'view_admin_tiers.php';
        $page_title = 'Tier Manager';
        break;

    case 'admin_books':
        $content = 'view_admin_books.php';
        $page_title = 'Book Inventory';
        break;

    case 'admin_users':
        $content = 'view_admin_users.php';
        $page_title = 'User Management';
        break;

    case 'admin_tickets':
        $content = 'view_admin_tickets.php';
        $page_title = 'Support Tickets';
        break;

    case 'admin_settings':
        $content = 'view_admin_settings.php';
        $page_title = 'System Settings';
        break;

    // --- 404 ERROR ---
    default:
        $content = '404'; // Internal signal for 404
        $page_title = 'Page Not Found';
        break;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
</head>
<body class="<?php echo $is_landing ? 'layout-landing' : 'layout-app'; ?>">

<?php if ($is_landing): ?>
    <header class="landing-header">
        <div class="container header-content">
            <a href="?page=home" class="brand" style="margin:0;">Compass.</a>
            
            <nav class="landing-nav">
                <a href="?page=home" class="<?php echo $page=='home'?'active-link':''; ?>">Home</a>
                <a href="?page=catalog" class="<?php echo $page=='catalog'?'active-link':''; ?>">Catalog</a>
                <a href="?page=form" class="<?php echo $page=='form'?'active-link':''; ?>">Interest Form</a>
            </nav>

            <div style="display:flex; align-items:center; gap:15px;">
                <div class="landing-auth">
                    <?php if($user_id): ?>
                        <a href="?page=profile" class="btn btn-sm">Dashboard</a>
                    <?php else: ?>
                        <a href="?page=login" class="nav-link" style="font-size:14px;">Sign In</a>
                        <a href="?page=register" class="btn btn-sm">Join</a>
                    <?php endif; ?>
                </div>
                
                <button class="mobile-menu-btn" onclick="toggleLandingMenu()">
                    <ion-icon name="menu-outline"></ion-icon>
                </button>
            </div>
        </div>
        
        <div id="landing-mobile-menu" class="landing-mobile-menu">
            <a href="?page=home">Home</a>
            <a href="?page=catalog">Catalog</a>
            <a href="?page=form">Interest Form</a>
            <?php if($user_id): ?>
                <a href="?page=profile" style="color:var(--primary);">Go to Dashboard</a>
            <?php else: ?>
                <a href="?page=login">Sign In</a>
                <a href="?page=register">Register</a>
            <?php endif; ?>
        </div>
    </header>

    <main>
        <?php 
        if ($page === 'home') { require 'view_home.php'; } 
        else {
            echo '<div class="container" style="padding-top: 40px; padding-bottom: 80px;">';
            $view = "view_{$page}.php";
            if (file_exists($view)) require $view;
            else echo "<div class='card'><h2>404</h2><p>Page not found.</p></div>";
            echo '</div>';
        }
        ?>
    </main>
    <footer class="landing-footer"><div class="container"><p>&copy; 2026 Compass Publishing.</p></div></footer>

<?php else: ?>
    <div class="app-shell">
        
        <header class="mobile-header">
            <button onclick="toggleSidebar()" style="background:none; border:none; font-size:28px; color:#333; cursor:pointer;">
                <ion-icon name="menu-outline"></ion-icon>
            </button>
            <div style="font-weight:800; font-size:20px; color:var(--primary);">Compass.</div>
            <a href="?page=profile" style="color:#333; font-size:24px;">
                <ion-icon name="person-circle-outline"></ion-icon>
            </a>
        </header>

        <div id="sidebar-overlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>

        <aside id="app-sidebar" class="sidebar">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 30px;">
                <div class="brand" style="margin:0;">Compass.</div>
                <button onclick="toggleSidebar()" class="close-sidebar-btn">
                    <ion-icon name="close-outline"></ion-icon>
                </button>
            </div>

            <nav>
                <a href="?page=home" class="nav-link"><ion-icon name="arrow-back-outline"></ion-icon> Website</a>
                <a href="?page=catalog" class="nav-link"><ion-icon name="arrow-back-outline"></ion-icon> Catalog</a>
                <div style="height: 1px; background: #eee; margin: 10px 0;"></div>
                
                <?php if($role === 'admin'): ?>
                    <div class="nav-label">ADMINISTRATION</div>
                    <a href="?page=admin" class="nav-link <?php echo $page=='admin'?'active':''; ?>">
                        <ion-icon name="stats-chart-outline"></ion-icon> Overview
                    </a>
                    <a href="?page=admin_orders" class="nav-link <?php echo $page=='admin_orders'?'active':''; ?>">
                        <ion-icon name="receipt-outline"></ion-icon> Orders
                    </a>

                    <a href="?page=admin_payments" class="nav-link <?php echo $page=='admin_payments'?'active':''; ?>">
                        <ion-icon name="cash-outline"></ion-icon> Payments
                    </a>
                    <a href="?page=admin_shipments" class="nav-link <?php echo $page=='admin_shipments'?'active':''; ?>">
                        <ion-icon name="cube-outline"></ion-icon> Shipments
                    </a>
                    <a href="?page=admin_forms" class="nav-link <?php echo $page=='admin_forms'?'active':''; ?>">
                        <ion-icon name="document-text-outline"></ion-icon> Leads / Forms
                    </a>
                    <a href="?page=admin_books" class="nav-link <?php echo $page=='admin_books'?'active':''; ?>">
                        <ion-icon name="library-outline"></ion-icon> Books & Stock
                    </a>
                    <a href="?page=admin_users" class="nav-link <?php echo $page=='admin_users'?'active':''; ?>">
                        <ion-icon name="people-outline"></ion-icon> Users
                    </a>
                    <a href="?page=admin_tickets" class="nav-link <?php echo $page=='admin_tickets'?'active':''; ?>">
                        <ion-icon name="chatbubbles-outline"></ion-icon> Support Tickets
                    </a>
                    <a href="?page=admin_settings" class="nav-link <?php echo $page=='admin_settings'?'active':''; ?>">
                        <ion-icon name="settings-outline"></ion-icon> System Settings
                    </a>
                <?php endif; ?>

                <?php if($user_id): ?>
                    <div class="nav-label">ACCOUNT</div>
                    <a href="?page=profile" class="nav-link <?php echo $page=='profile'?'active':''; ?>">
                        <ion-icon name="person-outline"></ion-icon> My Dashboard
                    </a>
                    <a href="?page=profile_settings" class="nav-link <?php echo $page=='profile_settings'?'active':''; ?>">
                        <ion-icon name="map-outline"></ion-icon> Address Book
                    </a>
                    <a href="?page=support" class="nav-link <?php echo $page=='support'?'active':''; ?>">
                        <ion-icon name="help-buoy-outline"></ion-icon> Customer Care
                    </a>
                    <a href="logout.php" class="nav-link" style="color: #FF3B30; margin-top:20px;">
                        <ion-icon name="log-out-outline"></ion-icon> Sign Out
                    </a>
                <?php endif; ?>
            </nav>
        </aside>

        <main class="main-content">
            <?php
            $view = "view_{$page}.php";
            if (file_exists($view)) require $view;
            else echo "<div class='card'><h2>404</h2><p>Page '$page' not found.</p></div>";
            ?>
        </main>
    </div>
<?php endif; ?>

<script src="app.js"></script>
<script>
// Toggle Dashboard Sidebar
function toggleSidebar() {
    const sb = document.getElementById('app-sidebar');
    const ov = document.getElementById('sidebar-overlay');
    if(sb) sb.classList.toggle('active');
    if(ov) ov.classList.toggle('active');
}

// Toggle Landing Page Mobile Menu
function toggleLandingMenu() {
    const menu = document.getElementById('landing-mobile-menu');
    if(menu) menu.classList.toggle('active');
}
</script>

<?php if($is_landing || $page === 'catalog'): ?>
    <a href="?page=cart" class="floating-cart-btn">
        <ion-icon name="cart"></ion-icon>
    </a>
<?php endif; ?>

</body>
</html>