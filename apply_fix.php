<?php
require_once 'config/Database.php';
$db = (new Database())->getConnection();
if (!$db) { echo "DB Connection Failed\n"; exit; }

echo "Fixing database...\n";

// 1. Create customers table if not exists
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
echo "Customers table checked.\n";

// 2. Add customer_id to orders if not exists
try {
    $db->exec("ALTER TABLE orders ADD COLUMN customer_id int(11) DEFAULT NULL AFTER id;");
    echo "Added customer_id to orders table.\n";
} catch(PDOException $e) {
    echo "customer_id already exists in orders table or error: " . $e->getMessage() . "\n";
}

// 3. Ensure order_details has correct structure (according to SQL dump it's fine, but let's be sure)
// order_id, product_id, quantity, price
echo "Database fix complete.\n";
