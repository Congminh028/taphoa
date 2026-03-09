<?php
require_once 'config/Database.php';
$db = (new Database())->getConnection();

// Let's also check which database we're actually using
$stmt = $db->query("SELECT DATABASE()");
$dbname = $stmt->fetchColumn();
echo "Using database: $dbname\n";

// Ensure customers table
$db->exec("CREATE TABLE IF NOT EXISTS customers (
    id int(11) NOT NULL AUTO_INCREMENT,
    phone varchar(20) NOT NULL UNIQUE,
    name varchar(255) NOT NULL,
    address text DEFAULT NULL,
    points int(11) DEFAULT 0,
    tier varchar(50) DEFAULT 'Đồng',
    created_at timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
echo "Customers table ensured\n";

// Add columns to orders
$columns = [
    'customer_id' => "INT(11) DEFAULT NULL AFTER id",
    'payment_method' => "VARCHAR(50) DEFAULT 'Tiền mặt' AFTER total_amount"
];

foreach ($columns as $col => $def) {
    try {
        $db->exec("ALTER TABLE orders ADD COLUMN $col $def");
        echo "Added column $col to orders\n";
    } catch (PDOException $e) {
        if ($e->getCode() == '42S21' || strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "Column $col already exists in orders\n";
        } else {
            echo "Error adding $col: " . $e->getMessage() . "\n";
        }
    }
}

echo "Final database setup complete\n";
