<?php
require_once 'config/Database.php';
$db = (new Database())->getConnection();
if (!$db) { echo "DB Connection Failed\n"; exit; }

$tables = ['orders', 'order_details', 'products'];
foreach ($tables as $table) {
    echo "--- $table ---\n";
    try {
        $stmt = $db->query("DESCRIBE $table");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "Field: {$row['Field']}, Type: {$row['Type']}, Null: {$row['Null']}, Default: {$row['Default']}\n";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
