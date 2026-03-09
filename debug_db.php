<?php
require_once 'config/Database.php';
$db = (new Database())->getConnection();
if (!$db) { echo "DB Connection Failed\n"; exit; }
echo "Connected to Database Successfully\n";
$tables = ['orders', 'order_details', 'products'];
foreach ($tables as $table) {
    echo "--- $table ---\n";
    try {
        $stmt = $db->query("DESCRIBE $table");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo $row['Field'] . " (" . $row['Type'] . ")\n";
        }
    } catch (Exception $e) {
        echo "Error describing $table: " . $e->getMessage() . "\n";
    }
}
