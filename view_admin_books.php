<?php
// view_admin_books.php - FIXED: Column Name 'stock' to Match Database

if (($_SESSION['role'] ?? '') !== 'admin') die("Access Denied");
$pdo = getDB();

// --- 1. HANDLE FORM SUBMISSIONS (SAVE BATCH) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // --- DELETE ---
    if (isset($_POST['delete_selected']) && !empty($_POST['selected_ids'])) {
        $placeholders = implode(',', array_fill(0, count($_POST['selected_ids']), '?'));
        $stmt = $pdo->prepare("DELETE FROM books WHERE id IN ($placeholders)");
        $stmt->execute($_POST['selected_ids']);
        echo "<script>window.location.href='?page=admin_books';</script>";
        exit;
    }

    // --- SAVE BATCH (ADD OR EDIT) ---
    if (isset($_POST['save_batch'])) {
        $books_data = $_POST['books']; // Array of books
        
        foreach ($books_data as $index => $book) {
            // Skip empty rows if title is missing
            if (empty($book['title'])) continue;

            $b_id = $book['id'] ?? null;
            $title = $book['title'];
            $isbn = $book['isbn'] ?? '';
            $category = $book['category'] ?? 'General';
            $price = $book['base_price'] ?? 0;
            $stock = $book['stock'] ?? 0; // FIXED: Changed 'stock_quantity' to 'stock'
            
            // Image Logic
            $image_path = $book['current_image'] ?? '';
            
            // 1. Check File Upload (using unique key per row)
            $file_key = 'cover_image_' . $index; 
            if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] === UPLOAD_ERR_OK) {
                $upload_dir = 'uploads/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
                
                $ext = pathinfo($_FILES[$file_key]['name'], PATHINFO_EXTENSION);
                $new_name = uniqid('book_', true) . '.' . $ext;
                if (move_uploaded_file($_FILES[$file_key]['tmp_name'], $upload_dir . $new_name)) {
                    $image_path = $upload_dir . $new_name;
                }
            }
            // 2. Check URL if no file
            elseif (!empty($book['cover_image_url'])) {
                $image_path = $book['cover_image_url'];
            }

            // DB Operation
            if ($b_id) {
                // FIXED: Changed 'stock_quantity' to 'stock' in SQL
                $stmt = $pdo->prepare("UPDATE books SET title=?, isbn=?, category=?, base_price=?, stock=?, cover_image=? WHERE id=?");
                $stmt->execute([$title, $isbn, $category, $price, $stock, $image_path, $b_id]);
            } else {
                // FIXED: Changed 'stock_quantity' to 'stock' in SQL
                $stmt = $pdo->prepare("INSERT INTO books (title, isbn, category, base_price, stock, cover_image) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$title, $isbn, $category, $price, $stock, $image_path]);
            }
        }
        echo "<script>window.location.href='?page=admin_books';</script>";
        exit;
    }
}

// --- 2. DETERMINE VIEW MODE ---
$mode = 'list';
$editor_books = [];

// Check if entering "Add" or "Edit" mode
if (isset($_GET['action']) && $_GET['action'] === 'add') {
    $mode = 'editor';
    $editor_books[] = []; // Start with 1 empty row
} elseif (isset($_POST['edit_selected']) && !empty($_POST['selected_ids'])) {
    $mode = 'editor';
    $placeholders = implode(',', array_fill(0, count($_POST['selected_ids']), '?'));
    $stmt = $pdo->prepare("SELECT * FROM books WHERE id IN ($placeholders)");
    $stmt->execute($_POST['selected_ids']);
    $editor_books = $stmt->fetchAll();
} elseif (isset($_GET['edit_id'])) {
    $mode = 'editor';
    $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->execute([$_GET['edit_id']]);
    $editor_books = $stmt->fetchAll();
}

// Fetch all books for List View
$all_books = $pdo->query("SELECT * FROM books ORDER BY id DESC")->fetchAll();
?>

<div class="container" style="max-width: 1400px; padding-top: 20px;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1 style="margin:0;">Book Manager</h1>
        <?php if ($mode === 'editor'): ?>
            <a href="?page=admin_books" class="btn btn-outline">Cancel & Go Back</a>
        <?php else: ?>
            <a href="?page=admin_books&action=add" class="btn">+ Add New Books</a>
        <?php endif; ?>
    </div>

    <?php if ($mode === 'editor'): ?>
    <form method="POST" enctype="multipart/form-data" class="card" style="padding: 20px;">
        <h3><?php echo count($editor_books) > 0 && isset($editor_books[0]['id']) ? 'Edit Books' : 'Add New Books'; ?></h3>
        
        <div id="rows-container">
            <?php foreach ($editor_books as $idx => $book): ?>
            <div class="book-row" style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr 1fr 2fr 50px; gap: 15px; padding: 15px; border: 1px solid #eee; margin-bottom: 10px; background: #f9f9f9; border-radius: 8px; align-items: start;">
                <input type="hidden" name="books[<?php echo $idx; ?>][id]" value="<?php echo $book['id'] ?? ''; ?>">
                <input type="hidden" name="books[<?php echo $idx; ?>][current_image]" value="<?php echo $book['cover_image'] ?? ''; ?>">
                
                <div>
                    <label style="font-size:11px; font-weight:bold;">Title *</label>
                    <input type="text" name="books[<?php echo $idx; ?>][title]" class="form-control" value="<?php echo htmlspecialchars($book['title'] ?? ''); ?>" required placeholder="Book Title">
                </div>

                <div>
                    <label style="font-size:11px; font-weight:bold;">ISBN</label>
                    <input type="text" name="books[<?php echo $idx; ?>][isbn]" class="form-control" value="<?php echo htmlspecialchars($book['isbn'] ?? ''); ?>" placeholder="ISBN">
                </div>

                <div>
                    <label style="font-size:11px; font-weight:bold;">Category</label>
                    <input type="text" name="books[<?php echo $idx; ?>][category]" class="form-control" value="<?php echo htmlspecialchars($book['category'] ?? ''); ?>" placeholder="Category">
                </div>

                <div>
                    <label style="font-size:11px; font-weight:bold;">Price (Rp)</label>
                    <input type="number" name="books[<?php echo $idx; ?>][base_price]" class="form-control" value="<?php echo $book['base_price'] ?? ''; ?>" required placeholder="0">
                </div>

                <div>
                    <label style="font-size:11px; font-weight:bold;">Stock</label>
                    <input type="number" name="books[<?php echo $idx; ?>][stock]" class="form-control" value="<?php echo $book['stock'] ?? ''; ?>" required placeholder="0">
                </div>

                <div>
                    <label style="font-size:11px; font-weight:bold;">Cover Image</label>
                    <?php if (!empty($book['cover_image'])): ?>
                        <div style="margin-bottom:5px;"><img src="<?php echo htmlspecialchars($book['cover_image']); ?>" style="height:30px;"> <small>Current</small></div>
                    <?php endif; ?>
                    <input type="file" name="cover_image_<?php echo $idx; ?>" accept="image/*" style="font-size:11px; width:100%; margin-bottom:5px;">
                    <input type="text" name="books[<?php echo $idx; ?>][cover_image_url]" class="form-control" placeholder="OR Paste URL" style="font-size:11px;">
                </div>

                <div style="text-align: right; padding-top: 20px;">
                    <button type="button" onclick="this.closest('.book-row').remove()" style="color:red; background:none; border:none; cursor:pointer; font-size:18px;">&times;</button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 20px;">
            <button type="button" class="btn btn-outline" onclick="addEmptyRow()">+ Add Another Row</button>
            <button type="submit" name="save_batch" class="btn" style="flex-grow: 1;">Save All Books</button>
        </div>
    </form>
    
    <?php else: ?>
    <form method="POST">
        <div class="card" style="padding: 0; overflow: hidden;">
            <div style="padding: 15px; background: #f8f9fa; border-bottom: 1px solid #eee; display: flex; gap: 10px;">
                <button type="submit" name="edit_selected" class="btn btn-outline btn-sm">Edit Selected</button>
                <button type="submit" name="delete_selected" class="btn btn-outline btn-sm" style="color: #d32f2f; border-color: #d32f2f;" onclick="return confirm('Delete selected books?')">Delete Selected</button>
            </div>

            <table class="table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #fff; border-bottom: 2px solid #eee;">
                        <th style="padding: 12px; width: 40px;"><input type="checkbox" onclick="toggleAll(this)"></th>
                        <th style="padding: 12px; width: 60px;">Image</th>
                        <th style="padding: 12px; text-align: left;">Title</th>
                        <th style="padding: 12px; text-align: left;">Category</th> <th style="padding: 12px; text-align: left;">ISBN</th>
                        <th style="padding: 12px; text-align: left;">Price</th>
                        <th style="padding: 12px; text-align: left;">Stock</th>
                        <th style="padding: 12px; text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_books as $book): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 12px; text-align: center;">
                            <input type="checkbox" name="selected_ids[]" value="<?php echo $book['id']; ?>">
                        </td>
                        <td style="padding: 12px;">
                            <?php if ($book['cover_image']): ?>
                                <img src="<?php echo htmlspecialchars($book['cover_image']); ?>" style="height: 40px; width: 30px; object-fit: cover; border-radius: 3px; border:1px solid #eee;">
                            <?php else: ?>
                                <div style="height:40px; width:30px; background:#eee; border-radius:3px;"></div>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 12px;"><strong><?php echo htmlspecialchars($book['title']); ?></strong></td>
                        <td style="padding: 12px; color: #555;"><?php echo htmlspecialchars($book['category'] ?? '-'); ?></td>
                        <td style="padding: 12px; color: #888; font-size: 13px;"><?php echo htmlspecialchars($book['isbn']); ?></td>
                        <td style="padding: 12px;">Rp <?php echo number_format($book['base_price']); ?></td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 8px; border-radius: 12px; font-size: 11px; background: <?php echo ($book['stock'] ?? 0) > 10 ? '#e8f5e9' : '#ffebee'; ?>; color: <?php echo ($book['stock'] ?? 0) > 10 ? '#2e7d32' : '#c62828'; ?>;">
                                <?php echo $book['stock'] ?? 0; ?>
                            </span>
                        </td>
                        <td style="padding: 12px; text-align: right;">
                            <a href="?page=admin_books&edit_id=<?php echo $book['id']; ?>" class="btn btn-outline btn-sm" style="padding: 2px 8px;">Edit</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </form>
    <?php endif; ?>
</div>

<script>
function toggleAll(source) {
    checkboxes = document.getElementsByName('selected_ids[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}

function addEmptyRow() {
    // Generate a random index to avoid ID collisions in the array
    const idx = Date.now(); 
    const html = `
    <div class="book-row" style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr 1fr 2fr 50px; gap: 15px; padding: 15px; border: 1px solid #eee; margin-bottom: 10px; background: #f9f9f9; border-radius: 8px; align-items: start;">
        <div>
            <label style="font-size:11px; font-weight:bold;">Title *</label>
            <input type="text" name="books[${idx}][title]" class="form-control" required placeholder="Book Title">
        </div>
        <div>
            <label style="font-size:11px; font-weight:bold;">ISBN</label>
            <input type="text" name="books[${idx}][isbn]" class="form-control" placeholder="ISBN">
        </div>
        <div>
            <label style="font-size:11px; font-weight:bold;">Category</label>
            <input type="text" name="books[${idx}][category]" class="form-control" placeholder="Category">
        </div>
        <div>
            <label style="font-size:11px; font-weight:bold;">Price (Rp)</label>
            <input type="number" name="books[${idx}][base_price]" class="form-control" required placeholder="0">
        </div>
        <div>
            <label style="font-size:11px; font-weight:bold;">Stock</label>
            <input type="number" name="books[${idx}][stock]" class="form-control" required placeholder="0">
        </div>
        <div>
            <label style="font-size:11px; font-weight:bold;">Cover Image</label>
            <input type="file" name="cover_image_${idx}" accept="image/*" style="font-size:11px; width:100%; margin-bottom:5px;">
            <input type="text" name="books[${idx}][cover_image_url]" class="form-control" placeholder="OR Paste URL" style="font-size:11px;">
        </div>
        <div style="text-align: right; padding-top: 20px;">
            <button type="button" onclick="this.closest('.book-row').remove()" style="color:red; background:none; border:none; cursor:pointer; font-size:18px;">&times;</button>
        </div>
    </div>`;
    document.getElementById('rows-container').insertAdjacentHTML('beforeend', html);
}
</script>