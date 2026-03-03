<?php
require 'config.php';

try {
    $pdo = getDB();
    echo "<h1>🕒 Setting up Early Bird System...</h1>";

    // 1. Fetch External API Books
    $apiBooks = [];
    $apiUrl = "https://api452.rallyz.co.kr/api/savewon/goods";
    $exchangeRate = 12; 

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Increased timeout slightly
    $response = curl_exec($ch);
    // curl_close($ch); // Deprecated in newer PHP

    if ($response) {
        $json = json_decode($response, true);
        if (isset($json['Success']) && $json['Success'] == true && !empty($json['Data'])) {
            foreach ($json['Data'] as $item) {
                $apiBooks[] = [
                    'title' => $item['PB_NM'],
                    'isbn' => $item['PB_ISBN'],
                    'category' => $item['PB_PUBLISHER'] ?: 'Imported',
                    'base_price' => $item['GDS_PRICE'] * $exchangeRate,
                    'cover_image' => 'https://8izg4bob10557.edge.naverncp.com/' . $item['THUMB_URL']
                ];
            }
        }
    }

    echo "<li>Fetched " . count($apiBooks) . " books from API.</li>";

    // 2. Insert into Database using Prepared Statements (Fixes the Error)
    $stmt = $pdo->prepare("
        INSERT IGNORE INTO books 
        (title, isbn, category, cover_image, base_price, stock, is_featured) 
        VALUES (?, ?, ?, ?, ?, 1000, 1)
    ");

    $count = 0;
    foreach($apiBooks as $b) {
        // Execute cleanly handles quotes and special characters
        $stmt->execute([
            $b['title'], 
            $b['isbn'], 
            $b['category'], 
            $b['cover_image'], 
            $b['base_price']
        ]);
        $count++;
    }
    echo "<li>Successfully seeded/updated $count books into database.</li>";

    // 3. Add columns if they don't exist
    $columns = $pdo->query("SHOW COLUMNS FROM books")->fetchAll(PDO::FETCH_COLUMN);

    if (!in_array('early_bird_price', $columns)) {
        $pdo->exec("ALTER TABLE books ADD COLUMN early_bird_price DECIMAL(10,2) DEFAULT 0");
        echo "<li>Added 'early_bird_price' column.</li>";
    }

    if (!in_array('early_bird_end_date', $columns)) {
        $pdo->exec("ALTER TABLE books ADD COLUMN early_bird_end_date DATE NULL");
        echo "<li>Added 'early_bird_end_date' column.</li>";
    }

    echo "<h3>✅ Database Seeded Successfully!</h3>";
    echo "<a href='index.php?page=catalog'>Go to Catalog</a>";

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>