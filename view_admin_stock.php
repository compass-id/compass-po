<?php
if (($_SESSION['role'] ?? '') !== 'admin') die("Access Denied");
$pdo = getDB();
$books = $pdo->query("SELECT * FROM books ORDER BY title ASC")->fetchAll();
?>

<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h2>Stock Management</h2>
        <a href="?page=admin" class="btn" style="width:auto; padding:8px 16px;">Back to Dashboard</a>
    </div>
    
    <div style="overflow-x:auto; margin-top:20px;">
        <table style="width:100%; border-collapse:collapse; min-width:600px;">
            <tr style="background:#f5f5f7; text-align:left;">
                <th style="padding:10px;">Title</th>
                <th style="padding:10px;">Price</th>
                <th style="padding:10px;">Stock Level</th>
                <th style="padding:10px;">Action</th>
            </tr>
            <?php foreach($books as $b): ?>
            <tr style="border-bottom:1px solid #eee;">
                <td style="padding:10px;"><?php echo htmlspecialchars($b['title']); ?></td>
                <td style="padding:10px;">Rp <?php echo number_format($b['base_price']); ?></td>
                <td style="padding:10px;">
                    <input type="number" id="stock-<?php echo $b['id']; ?>" value="<?php echo $b['stock']; ?>" 
                           style="width:80px; padding:5px; border:1px solid #ccc; border-radius:5px;">
                </td>
                <td style="padding:10px;">
                    <button onclick="updateStock(<?php echo $b['id']; ?>)" 
                            class="btn" style="width:auto; padding:6px 12px; font-size:12px;">Save</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<script>
async function updateStock(id) {
    const val = document.getElementById('stock-'+id).value;
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    
    try {
        const res = await fetch('api.php?action=update_stock', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({id: id, stock: val, csrf_token: csrf})
        });
        const json = await res.json();
        if(json.status === 'success') alert("Stock Updated");
        else alert("Error Updating Stock");
    } catch(e) { alert("Connection Error"); }
}
</script>