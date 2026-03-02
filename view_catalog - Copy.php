<?php
// view_catalog.php - FULL FEATURES + ROBUST LOGIC
$pdo = getDB();
$is_logged_in = isset($_SESSION['user_id']);
$allBooks = $pdo->query("SELECT * FROM books ORDER BY title ASC")->fetchAll();

// Fetch Global Settings
$eb_stmt = $pdo->query("SELECT setting_value FROM system_settings WHERE setting_key = 'early_bird_deadline'");
$eb_deadline = $eb_stmt->fetchColumn(); 
$today = date('Y-m-d'); 
$is_early_bird = ($eb_deadline && $today <= $eb_deadline);
?>

<div class="container" style="padding: 40px 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0;">Book Catalog</h2>
        <?php if ($is_logged_in): ?>
        <a href="?page=cart" class="btn btn-outline">
            <ion-icon name="cart"></ion-icon> Cart (<span id="header-cart-count">0</span>)
        </a>
        <?php endif; ?>
    </div>

    <div class="catalog-toolbar">
        <div class="search-box">
            <ion-icon name="search" class="search-icon"></ion-icon>
            <input type="text" id="catalog-search" class="search-input" placeholder="Search by title, ISBN, or category..." onkeyup="filterCatalog()">
        </div>
        <select id="catalog-sort" class="sort-select" onchange="sortCatalog()">
            <option value="title">Sort by Name (A-Z)</option>
            <option value="price_low">Price: Low to High</option>
            <option value="price_high">Price: High to Low</option>
        </select>
    </div>

    <div class="grid-books" id="book-grid">
        <?php foreach($allBooks as $b): 
            // Null Safety
            $b_id = $b['id'] ?? 0;
            $b_title = $b['title'] ?? 'Untitled';
            $b_isbn = $b['isbn'] ?? 'N/A';
            $b_cat = $b['category'] ?? 'General';
            $b_price = $b['base_price'] ?? 0;
            $b_img = $b['cover_image'] ?? 'https://via.placeholder.com/150';
            
            // JSON for Modal
            $b_json = htmlspecialchars(json_encode($b) ?: '{}');
        ?>
        <div class="card product-card" 
             data-id="<?php echo $b_id; ?>" 
             data-title="<?php echo strtolower(htmlspecialchars($b_title)); ?>"
             data-isbn="<?php echo htmlspecialchars($b_isbn); ?>"
             data-category="<?php echo strtolower(htmlspecialchars($b_cat)); ?>"
             data-price="<?php echo $b_price; ?>"
             style="padding: 15px; display: flex; flex-direction: column; position: relative;">
            
            <?php if ($is_early_bird): ?>
            <div style="position: absolute; top: 25px; right: 25px; background: #007AFF; color: white; padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: bold; z-index: 10;">EARLY BIRD</div>
            <?php endif; ?>

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
                <?php if ($is_early_bird): 
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
                        <div class="qty-control" style="display: flex; align-items: center; justify-content: center; gap: 10px; background: #f5f5f7; padding: 8px; border-radius: 6px;">
                            <form method="POST" onsubmit="return addToCart(event)" style="margin:0;">
                                <input type="hidden" name="action" value="update_cart">
                                <input type="hidden" name="book_id" value="<?php echo $b_id; ?>">
                                <input type="hidden" name="delta" value="-1">
                                <button class="qty-btn" style="border:none; background:none; cursor:pointer;">−</button>
                            </form>
                            <span style="font-weight:600;"><?php echo $inCart; ?></span>
                            <form method="POST" onsubmit="return addToCart(event)" style="margin:0;">
                                <input type="hidden" name="action" value="update_cart">
                                <input type="hidden" name="book_id" value="<?php echo $b_id; ?>">
                                <input type="hidden" name="delta" value="1">
                                <button class="qty-btn" style="border:none; background:none; cursor:pointer;">+</button>
                            </form>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="?page=login" class="btn" style="width: 100%; background: #eee; color: #333;">Login</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
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
// Modal Logic
function showBookDetails(book) {
    document.getElementById('modal-img').src = book.cover_image || 'https://via.placeholder.com/150';
    document.getElementById('modal-title').innerText = book.title || 'Untitled';
    document.getElementById('modal-meta').innerText = (book.category || 'General') + ' • ' + (book.isbn || 'N/A');
    document.getElementById('modal-price').innerText = 'Rp ' + parseInt(book.base_price).toLocaleString('id-ID');
    document.getElementById('book-modal').style.display = 'flex';
}

// Add to Cart Logic
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
    cards.forEach(card => {
        const title = card.getAttribute('data-title');
        const isbn = card.getAttribute('data-isbn').toLowerCase();
        card.style.display = (title.includes(input) || isbn.includes(input)) ? 'flex' : 'none';
    });
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
}

// Init Cart Count
fetch('?page=cart&ajax=get_count').then(r=>r.text()).then(c=>{
    if(document.getElementById('header-cart-count')) document.getElementById('header-cart-count').innerText=c;
});
</script>