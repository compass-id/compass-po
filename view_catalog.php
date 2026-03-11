<?php
// view_catalog.php 
$pdo = getDB();
$is_logged_in = isset($_SESSION['user_id']);

// 1. Fetch Local Books ONLY
$allBooks = $pdo->query("SELECT * FROM books ORDER BY title ASC")->fetchAll(PDO::FETCH_ASSOC);

// 2. Total Count based only on local DB
$total_count = count($allBooks); 

// 3. Cart Count (Unique Items Count)
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

// 4. Fetch Global Settings
$eb_stmt = $pdo->query("SELECT setting_value FROM system_settings WHERE setting_key = 'early_bird_deadline'");
$eb_deadline = $eb_stmt->fetchColumn(); 
$today = date('Y-m-d'); 
$is_early_bird_active = ($eb_deadline && $today <= $eb_deadline);

// --- NEW: DYNAMIC ALPHABET SCAN ---
$existing_letters = [];
foreach ($allBooks as $book) {
    // Remove leading symbols like [ or ( to find the first real letter
    $clean_title = ltrim($book['title'], "[( \t\n\r\0\x0B-_");
    if (!empty($clean_title)) {
        $first_letter = strtoupper($clean_title[0]);
        if (ctype_alpha($first_letter)) {
            $existing_letters[$first_letter] = true;
        }
    }
}
ksort($existing_letters); // Sort A-Z
?>

<style>
    .catalog-toolbar { 
        display: flex; 
        flex-direction: column;
        gap: 15px; 
        margin-bottom: 20px; 
        background: #fff; 
        padding: 15px; 
        border-radius: 8px; 
        border: 1px solid #eee; 
    }
    .alphabet-filter {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        justify-content: center;
        width: 100%;
    }
    .alpha-btn {
        padding: 8px 14px;
        border: 1px solid #ccc;
        background: #f9f9f9;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        color: #555;
        transition: all 0.2s ease;
    }
    .alpha-btn:hover { background: #e0e0e0; }
    .alpha-btn.active {
        background: #007AFF;
        color: white;
        border-color: #007AFF;
    }
    .toolbar-bottom {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        border-top: 1px solid #eee;
        padding-top: 15px;
    }
    .sort-select { 
        width: 200px; 
        padding: 10px; 
        border: 1px solid #ccc; 
        border-radius: 6px; 
        font-size: 14px; 
        background: white; 
    }
    .grid-books { 
        display: grid; 
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); 
        gap: 20px; 
    }
</style>

<div class="container" style="padding: 40px 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h2 style="margin: 0;">Book Catalog</h2>
            <small style="color: #666;"><?php echo number_format($total_count); ?> books available</small>
        </div>
    </div>

    <div class="catalog-toolbar">
        <div class="alphabet-filter" id="alphabet-container">
            <button class="alpha-btn active" data-letter="All" onclick="setLetter('All')">All</button>
            <?php foreach(array_keys($existing_letters) as $letter): ?>
                <button class="alpha-btn" data-letter="<?php echo $letter; ?>" onclick="setLetter('<?php echo $letter; ?>')"><?php echo $letter; ?></button>
            <?php endforeach; ?>
        </div>
        
        <div class="toolbar-bottom">
            <select id="catalog-sort" class="sort-select" onchange="sortCatalog()">
                <option value="title">Sort by Name (A-Z)</option>
                <option value="price_low">Price: Low to High</option>
                <option value="price_high">Price: High to Low</option>
            </select>
        </div>
    </div>

    <div class="grid-books" id="book-grid">
        <?php foreach($allBooks as $b): 
            $b_id = $b['id'] ?? 0;
            // CLEAN TITLE for JS filtering (removes [ or ( from the start)
            $js_clean_title = ltrim($b['title'], "[( \t\n\r\0\x0B-_");
            $b_title = $b['title'] ?? 'Untitled';
            $b_isbn = $b['isbn'] ?? 'N/A';
            $b_cat = $b['category'] ?? 'General';
            $b_price = $b['base_price'] ?? 0;
            $b_img = $b['cover_image'] ?? 'https://via.placeholder.com/150';
            $is_ext = $b['is_external'] ?? false;
            $b_json = htmlspecialchars(json_encode($b) ?: '{}', ENT_QUOTES, 'UTF-8');
        ?>
        <div class="card product-card" 
             data-id="<?php echo htmlspecialchars($b_id); ?>" 
             data-title="<?php echo htmlspecialchars($js_clean_title, ENT_QUOTES); ?>"
             data-display-title="<?php echo htmlspecialchars($b_title, ENT_QUOTES); ?>"
             data-price="<?php echo $b_price; ?>"
             style="padding: 15px; display: none; flex-direction: column; position: relative;"> 
             
             <div style="position: absolute; top: 15px; right: 15px; display: flex; flex-direction: column; gap: 5px; align-items: flex-end; z-index: 10;">
                <?php if ($is_early_bird_active): ?>
                    <span style="background: #007AFF; color: white; padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: bold;">EARLY BIRD</span>
                <?php endif; ?>
                <span style="background: <?php echo $is_ext ? '#34C759' : '#FF9500'; ?>; color: white; padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: bold;">
                    <?php echo $is_ext ? 'IMPORT' : 'LOCAL'; ?>
                </span>
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
                <?php if ($is_early_bird_active): $eb_price = $b_price * 0.95; ?>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="font-size: 14px; color: #888; text-decoration: line-through;">Rp <?php echo number_format($b_price); ?></span>
                        <span style="font-weight: 700; color: #d32f2f; font-size: 18px;">Rp <?php echo number_format($eb_price); ?></span>
                    </div>
                <?php else: ?>
                    <div style="font-weight: 700; color: #007AFF; font-size: 18px;">Rp <?php echo number_format($b_price); ?></div>
                <?php endif; ?>
            </div>
            
            <div style="margin-top: 15px;" id="cart-ui-<?php echo htmlspecialchars($b_id); ?>">
                <?php if ($is_logged_in): 
                    $inCart = isset($_SESSION['cart'][$b_id]) ? (int)$_SESSION['cart'][$b_id] : 0;
                ?>
                    <?php if ($inCart <= 0): ?>
                        <form method="POST" onsubmit="return handleCartAjax(event, '<?php echo htmlspecialchars($b_id); ?>', 'add_to_cart', 1)" style="margin:0;">
                            <input type="hidden" name="action" value="add_to_cart"><input type="hidden" name="book_id" value="<?php echo htmlspecialchars($b_id); ?>">
                            <button type="submit" class="btn" style="width: 100%;">+ Add</button>
                        </form>
                    <?php else: ?>
                        <div class="qty-control" style="display: flex; align-items: center; justify-content: center; gap: 10px; background: #f5f5f7; padding: 8px; border-radius: 6px;">
                            <form method="POST" onsubmit="return handleCartAjax(event, '<?php echo htmlspecialchars($b_id); ?>', 'update_cart', -1)" style="margin:0;">
                                <input type="hidden" name="action" value="update_cart"><input type="hidden" name="book_id" value="<?php echo htmlspecialchars($b_id); ?>"><input type="hidden" name="delta" value="-1">
                                <button type="submit" class="qty-btn" style="border:none; background:none; cursor:pointer; font-size:16px;">−</button>
                            </form>
                            <span style="font-weight:600;"><?php echo $inCart; ?></span>
                            <form method="POST" onsubmit="return handleCartAjax(event, '<?php echo htmlspecialchars($b_id); ?>', 'update_cart', 1)" style="margin:0;">
                                <input type="hidden" name="action" value="update_cart"><input type="hidden" name="book_id" value="<?php echo htmlspecialchars($b_id); ?>"><input type="hidden" name="delta" value="1">
                                <button type="submit" class="qty-btn" style="border:none; background:none; cursor:pointer; font-size:16px;">+</button>
                            </form>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="?page=login" class="btn" style="width: 100%; background: #eee; color: #333; text-align: center; display:block;">Login to Add</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
let allCards = [];
let filteredCards = [];
const increment = 12;
let visibleLimit = increment; 
let currentActiveLetter = 'All';

document.addEventListener('DOMContentLoaded', function() {
    allCards = Array.from(document.querySelectorAll('.product-card'));
    applyFiltersAndSort();
    window.addEventListener('scroll', handleScroll);
});

function setLetter(letter) {
    currentActiveLetter = letter;
    document.querySelectorAll('.alpha-btn').forEach(btn => {
        btn.classList.toggle('active', btn.getAttribute('data-letter') === letter);
    });
    applyFiltersAndSort();
}

function sortCatalog() { applyFiltersAndSort(); }

function applyFiltersAndSort() {
    const sortVal = document.getElementById('catalog-sort').value;

    filteredCards = allCards.filter(card => {
        if (currentActiveLetter === 'All') return true;
        const title = (card.getAttribute('data-title') || '').trim();
        return title.charAt(0).toUpperCase() === currentActiveLetter;
    });

    filteredCards.sort((a, b) => {
        const titleA = a.getAttribute('data-title');
        const titleB = b.getAttribute('data-title');
        if(sortVal === 'title') return titleA.localeCompare(titleB);
        const pA = parseFloat(a.getAttribute('data-price')), pB = parseFloat(b.getAttribute('data-price'));
        return sortVal === 'price_low' ? pA - pB : pB - pA;
    });

    visibleLimit = increment;
    renderGrid();
}

function renderGrid() {
    allCards.forEach(c => { c.style.display = 'none'; c.style.order = ''; });
    filteredCards.forEach((c, i) => {
        if (i < visibleLimit) { c.style.display = 'flex'; c.style.order = i; }
    });
}

function handleScroll() {
    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 500) {
        if (visibleLimit < filteredCards.length) {
            visibleLimit += increment;
            renderGrid();
        }
    }
}

function handleCartAjax(event, id, action, delta) {
    event.preventDefault();
    fetch('?page=cart&ajax=1', { method: 'POST', body: new FormData(event.target) })
    .then(() => location.reload()); // Reloading simplifies state for this specific logic
    return false;
}

function showBookDetails(book) {
    document.getElementById('modal-img').src = book.cover_image || 'https://via.placeholder.com/150';
    document.getElementById('modal-title').innerText = book.title;
    document.getElementById('book-modal').style.display = 'flex';
}
</script>