<?php
// view_catalog.php - FULL FEATURES: API + DB, SCROLL, SEARCH, LABELS
$pdo = getDB();
$is_logged_in = isset($_SESSION['user_id']);

// 1. Fetch Local Books
$localBooks = $pdo->query("SELECT * FROM books ORDER BY title ASC")->fetchAll(PDO::FETCH_ASSOC);

// 2. Fetch External API Books (Rallyz)
$apiBooks = [];
$apiUrl = "https://api452.rallyz.co.kr/api/savewon/goods";
$exchangeRate = 12; // 1 KRW = 12 IDR

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
$response = curl_exec($ch);
// curl_close($ch); // Deprecated in PHP 8+, handled automatically

if ($response) {
    $json = json_decode($response, true);
    if (isset($json['Success']) && $json['Success'] == true && !empty($json['Data'])) {
        foreach ($json['Data'] as $item) {
            $apiBooks[] = [
                'id' => 'ext_' . $item['GDS_ID'], 
                'title' => $item['PB_NM'],
                'isbn' => $item['PB_ISBN'],
                'category' => $item['PB_PUBLISHER'] ?: 'Imported',
                'base_price' => $item['GDS_PRICE'] * $exchangeRate,
                'cover_image' => 'https://8izg4bob10557.edge.naverncp.com/' . $item['THUMB_URL'],
                'stock' => 999,
                'is_external' => true 
            ];
        }
    }
}

// 3. Merge & Count
$allBooks = array_merge($localBooks, $apiBooks);
$total_count = count($allBooks);

// 4. Cart Count (Fixed Immediate Display)
$cart_count = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;

// Fetch Global Settings
$eb_stmt = $pdo->query("SELECT setting_value FROM system_settings WHERE setting_key = 'early_bird_deadline'");
$eb_deadline = $eb_stmt->fetchColumn(); 
$today = date('Y-m-d'); 
$is_early_bird_active = ($eb_deadline && $today <= $eb_deadline);
?>

<style>
    /* Fixed Toolbar Styling */
    .catalog-toolbar { 
        display: flex; 
        gap: 15px; 
        margin-bottom: 20px; 
        align-items: center; 
        background: #fff; 
        padding: 15px; 
        border-radius: 8px; 
        border: 1px solid #eee; 
    }
    .search-box { 
        flex: 1; 
        position: relative; 
        display: flex; 
        align-items: center; 
    }
    .search-input { 
        width: 100%; 
        padding: 10px 10px 10px 35px; 
        border: 1px solid #ccc; 
        border-radius: 6px; 
        font-size: 14px; 
    }
    .search-icon { 
        position: absolute; 
        left: 10px; 
        color: #888; 
        font-size: 18px; 
    }
    .sort-select { 
        width: 200px; 
        padding: 10px; 
        border: 1px solid #ccc; 
        border-radius: 6px; 
        font-size: 14px; 
        background: white; 
        cursor: pointer; 
    }
    .grid-books { 
        display: grid; 
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); 
        gap: 20px; 
    }
    /* Infinite Scroll Loading Text */
    #loading-trigger {
        text-align: center;
        padding: 20px;
        color: #888;
        font-style: italic;
        display: none;
        grid-column: 1 / -1;
    }
</style>

<div class="container" style="padding: 40px 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h2 style="margin: 0;">Book Catalog</h2>
            <small style="color: #666;"><?php echo number_format($total_count); ?> books available</small>
        </div>
        <?php if ($is_logged_in): ?>
        <a href="?page=cart" class="btn btn-outline" style="display:flex; align-items:center; gap:5px;">
            <ion-icon name="cart"></ion-icon> Cart (<?php echo $cart_count; ?>)
        </a>
        <?php endif; ?>
    </div>

    <div class="catalog-toolbar">
        <div class="search-box">
            <ion-icon name="search" class="search-icon"></ion-icon>
            <input type="text" id="catalog-search" class="search-input" placeholder="Search title, ISBN, or category..." onkeyup="filterCatalog()">
        </div>
        <select id="catalog-sort" class="sort-select" onchange="sortCatalog()">
            <option value="title">Sort by Name (A-Z)</option>
            <option value="price_low">Price: Low to High</option>
            <option value="price_high">Price: High to Low</option>
        </select>
    </div>

    <div class="grid-books" id="book-grid">
        <?php foreach($allBooks as $b): 
            $b_id = $b['id'] ?? 0;
            $b_title = $b['title'] ?? 'Untitled';
            $b_isbn = $b['isbn'] ?? 'N/A';
            $b_cat = $b['category'] ?? 'General';
            $b_price = $b['base_price'] ?? 0;
            $b_img = $b['cover_image'] ?? 'https://via.placeholder.com/150';
            $is_ext = $b['is_external'] ?? false;
            
            // JSON for Modal
            $b_json = htmlspecialchars(json_encode($b) ?: '{}', ENT_QUOTES, 'UTF-8');
        ?>
        <div class="card product-card" 
             data-id="<?php echo $b_id; ?>" 
             data-title="<?php echo strtolower(htmlspecialchars($b_title)); ?>"
             data-isbn="<?php echo htmlspecialchars($b_isbn); ?>"
             data-category="<?php echo strtolower(htmlspecialchars($b_cat)); ?>"
             data-price="<?php echo $b_price; ?>"
             style="padding: 15px; display: none; flex-direction: column; position: relative;"> <div style="position: absolute; top: 15px; right: 15px; display: flex; flex-direction: column; gap: 5px; align-items: flex-end; z-index: 10;">
                <?php if ($is_early_bird_active): ?>
                    <span style="background: #007AFF; color: white; padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: bold;">EARLY BIRD</span>
                <?php endif; ?>
            </div>

            <img src="<?php echo htmlspecialchars($b_img); ?>" 
                 style="width:100%; aspect-ratio:3/4; object-fit:cover; border-radius:12px; margin-bottom:15px; cursor: pointer;"
                 onclick="showBookDetails(<?php echo $b_json; ?>)">
            
            <div style="font-weight: 600; font-size: 16px; margin-bottom: 5px; cursor: pointer;"
                 onclick="showBookDetails(<?php echo $b_json; ?>)">
                <?php echo htmlspecialchars($b_title); ?>
            </div>
            
            <div style="font-size: 12px; color: #888; margin-bottom: 10px;">
                <?php echo htmlspecialchars($b_cat); ?> • <?php echo htmlspecialchars($b_isbn); ?>
            </div>
            
            <div style="margin-top: auto;">
                <?php if ($is_early_bird_active): 
                    $eb_price = $b_price * 0.95; 
                ?>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="font-size: 14px; color: #888; text-decoration: line-through;">
                            Rp <?php echo number_format($b_price); ?>
                        </span>
                        <span style="font-weight: 700; color: #d32f2f; font-size: 18px;">
                            Rp <?php echo number_format($eb_price); ?>
                        </span>
                    </div>
                    <div style="font-size: 11px; color: #d32f2f; margin-top: 2px; font-weight: 600;">
                        Ends <?php echo date('d M Y', strtotime($eb_deadline)); ?>
                    </div>
                <?php else: ?>
                    <div style="font-weight: 700; color: var(--primary); font-size: 18px;">
                        Rp <?php echo number_format($b_price); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div style="margin-top: 15px;">
                <?php if ($is_logged_in): 
                    $inCart = isset($_SESSION['cart'][$b_id]) ? $_SESSION['cart'][$b_id] : 0;
                ?>
                    <?php if ($inCart == 0): ?>
                        <form method="POST" onsubmit="return addToCart(event)" style="margin:0;">
                            <input type="hidden" name="action" value="add_to_cart">
                            <input type="hidden" name="book_id" value="<?php echo $b_id; ?>">
                            <button type="submit" class="btn btn-add" style="width: 100%;">+ Add</button>
                        </form>
                    <?php else: ?>
                        <div class="qty-control" style="display: flex; align-items: center; justify-content: center; gap: 10px; background: #eee; color: #333; padding: 8px; border-radius: 6px;">
                            <a class="btn" style="width: 100%; background: #eee; color: #333;">Added to Cart</a>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="?page=login" class="btn" style="width: 100%; background: #eee; color: #333;">Login</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <div id="loading-trigger">Loading more items...</div>
</div>

<div id="book-modal" class="modal-overlay" onclick="if(event.target===this) this.style.display='none'" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000; justify-content:center; align-items:center;">
    <div class="modal-content" style="background:white; padding:25px; border-radius:12px; max-width:500px; width:90%; position:relative;">
        <button onclick="document.getElementById('book-modal').style.display='none'" style="position:absolute; top:15px; right:15px; border:none; background:none; font-size:24px; cursor:pointer;">&times;</button>
        <div style="display: flex; gap: 20px;">
            <img id="modal-img" src="" style="width: 120px; border-radius: 8px; object-fit:cover;">
            <div>
                <h2 id="modal-title" style="margin-top: 0; font-size:20px;"></h2>
                <p id="modal-meta" style="color: #666; font-size: 14px;"></p>
                <h3 id="modal-price" style="color: var(--primary);"></h3>
            </div>
        </div>
        <div style="margin-top: 20px; background: #f9f9f9; padding: 15px; border-radius: 12px;">
            <strong style="display:block; margin-bottom:10px;">Bulk Pricing:</strong>
            <ul style="margin: 0; padding-left: 20px; font-size: 14px; color: #555;">
                <li>Buy 20+: <strong>10% Off</strong></li>
                <li>Buy 100+: <strong>20% Off</strong></li>
                <li>District (500+): <strong>30% Off</strong></li>
            </ul>
        </div>
    </div>
</div>

<script>
// --- INFINITE SCROLL LOGIC ---
let visibleLimit = 10; // Start with 12 items
const increment = 10;  // Load 12 more on scroll
let isSearchActive = false;

document.addEventListener('DOMContentLoaded', function() {
    renderBooks(); // Initial render
    window.addEventListener('scroll', handleScroll);
});

function handleScroll() {
    if (isSearchActive) return; // Disable scroll loading during search
    
    // Check if scrolled near bottom (buffer of 500px)
    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 500) {
        visibleLimit += increment;
        renderBooks();
    }
}

function renderBooks() {
    const cards = Array.from(document.querySelectorAll('.product-card'));
    let hiddenCount = 0;
    
    cards.forEach((card, index) => {
        if (!isSearchActive) {
            if (index < visibleLimit) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
                hiddenCount++;
            }
        }
    });
    
    // Show/Hide Loading Text
    const loader = document.getElementById('loading-trigger');
    if (hiddenCount > 0 && !isSearchActive) {
        loader.style.display = 'block';
    } else {
        loader.style.display = 'none';
    }
}

// --- STANDARD FEATURES ---

function showBookDetails(book) {
    document.getElementById('modal-img').src = book.cover_image || 'https://via.placeholder.com/150';
    document.getElementById('modal-title').innerText = book.title || 'Untitled';
    document.getElementById('modal-meta').innerText = (book.category || 'General') + ' • ' + (book.isbn || 'N/A');
    document.getElementById('modal-price').innerText = 'Rp ' + parseInt(book.base_price).toLocaleString('id-ID');
    document.getElementById('book-modal').style.display = 'flex';
}

function addToCart(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    fetch('?page=cart&ajax=1', { method: 'POST', body: formData })
    .then(() => window.location.reload())
    .catch(err => console.error(err));
    return false;
}

// Filter Logic (Restored)
function filterCatalog() {
    const input = document.getElementById('catalog-search').value.toLowerCase();
    const cards = document.querySelectorAll('.product-card');
    
    if (input.length > 0) {
        isSearchActive = true;
        document.getElementById('loading-trigger').style.display = 'none';
        
        cards.forEach(card => {
            const title = card.getAttribute('data-title');
            const isbn = card.getAttribute('data-isbn').toLowerCase();
            const cat = card.getAttribute('data-category').toLowerCase();
            
            if (title.includes(input) || isbn.includes(input) || cat.includes(input)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    } else {
        isSearchActive = false;
        renderBooks(); // Reset to scroll view
    }
}

// Sort Logic (Restored)
function sortCatalog() {
    const sortVal = document.getElementById('catalog-sort').value;
    const grid = document.getElementById('book-grid');
    const cards = Array.from(grid.children);

    cards.sort((a, b) => {
        if(sortVal === 'title') return a.getAttribute('data-title').localeCompare(b.getAttribute('data-title'));
        if(sortVal === 'price_low') return parseInt(a.getAttribute('data-price')) - parseInt(b.getAttribute('data-price'));
        if(sortVal === 'price_high') return parseInt(b.getAttribute('data-price')) - parseInt(a.getAttribute('data-price'));
    });

    cards.forEach(card => grid.appendChild(card));
    if (!isSearchActive) renderBooks();
}

// Init Cart Count
fetch('?page=cart&ajax=get_count').then(r=>r.text()).then(c=>{
    if(document.getElementById('header-cart-count')) document.getElementById('header-cart-count').innerText=c;
});
</script>