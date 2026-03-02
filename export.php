<?php
// export.php - MASTER EXPORT ENGINE WITH DATE RANGES
session_start();
require 'config.php';

if (($_SESSION['role'] ?? '') !== 'admin') die("Access Denied");

$pdo = getDB();
$type = $_GET['type'] ?? '';
$start = $_GET['start'] ?? '2020-01-01'; // Default to "beginning of time"
$end = $_GET['end'] ?? date('Y-m-d'); // Default to today

$filename = "Compass_Report_" . strtoupper($type) . "_" . $start . "_to_" . $end . ".xls";

// Force Excel Download
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

// HTML/Excel Protocol Wrapper
echo '<html xmlns:x="urn:schemas-microsoft-com:office:excel"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
echo '<style>body{font-family:Calibri,sans-serif;} table{border-collapse:collapse;width:100%;} th{background:#007AFF;color:white;padding:10px;border:1px solid #0056b3;} td{border:1px solid #ddd;padding:8px;}</style>';
echo '</head><body>';

echo "<h3>Report: " . strtoupper($type) . "</h3>";
echo "<p>Period: $start to $end</p>";

// --- REPORT LOGIC ---

if ($type === 'orders') {
    echo "<table>
        <tr><th>ID</th><th>Date</th><th>Customer</th><th>Institution</th><th>Items</th><th>Total</th><th>Status</th></tr>";
    
    $sql = "SELECT o.*, u.name, u.email FROM orders o 
            JOIN users u ON o.user_id = u.id 
            WHERE DATE(o.created_at) BETWEEN ? AND ? 
            ORDER BY o.created_at DESC";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$start, $end]);
    
    while ($row = $stmt->fetch()) {
        $snap = json_decode($row['shipping_snapshot'] ?? '{}', true);
        $inst = $snap['label'] ?? '-';
        echo "<tr>
            <td>#{$row['id']}</td>
            <td>{$row['created_at']}</td>
            <td>{$row['name']}</td>
            <td>{$inst}</td>
            <td>-</td> <td>" . number_format($row['total_amount']) . "</td>
            <td>{$row['status']}</td>
        </tr>";
    }
    echo "</table>";
}

elseif ($type === 'forms') {
    echo "<table>
        <tr><th>Date</th><th>School</th><th>Participant</th><th>Contact</th><th>City</th><th>Programs</th></tr>";
        
    $sql = "SELECT * FROM interest_forms WHERE DATE(created_at) BETWEEN ? AND ? ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$start, $end]);
    
    while ($row = $stmt->fetch()) {
        $progs = implode(", ", json_decode($row['programs'] ?? '[]', true));
        echo "<tr>
            <td>{$row['created_at']}</td>
            <td>{$row['school_name']}</td>
            <td>{$row['participant_name']}</td>
            <td>{$row['phone']}</td>
            <td>{$row['city']}</td>
            <td>{$progs}</td>
        </tr>";
    }
    echo "</table>";
}

echo '</body></html>';
exit;
?>