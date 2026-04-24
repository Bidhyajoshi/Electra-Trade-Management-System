<?php
$host = 'localhost';
$user = 'root';
$pass = ''; // Default XAMPP password is empty
$dbname = 'joshi_trading_pro';

$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS joshi_trading_pro";
if ($conn->query($sql) === TRUE) {
    $conn->select_db($dbname);
} else {
    die("Error creating database: " . $conn->error);
}

// Ensure tables exist
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'customer') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($sql_users);

$sql_products = "CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category VARCHAR(50) DEFAULT 'Uncategorized',
    image VARCHAR(500) DEFAULT 'default.jpg',
    stock INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($sql_products);

$sql_orders = "CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    full_name VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20),
    shipping_address TEXT,
    status ENUM('pending', 'completed') DEFAULT 'pending',
    total DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
)";
$conn->query($sql_orders);

// Ensure columns exist if table was already created
$conn->query("ALTER TABLE orders ADD COLUMN IF NOT EXISTS full_name VARCHAR(100) AFTER user_id");
$conn->query("ALTER TABLE orders ADD COLUMN IF NOT EXISTS email VARCHAR(100) AFTER full_name");
$conn->query("ALTER TABLE orders ADD COLUMN IF NOT EXISTS phone VARCHAR(20) AFTER email");
$conn->query("ALTER TABLE orders ADD COLUMN IF NOT EXISTS shipping_address TEXT AFTER phone");

$sql_items = "CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
)";
$conn->query($sql_items);

// Insert default admin if not exists
$admin_pass = password_hash('admin123', PASSWORD_DEFAULT);
$sql_admin = "INSERT IGNORE INTO users (full_name, email, password, role) VALUES ('Admin', 'admin@joshitrading.com', '$admin_pass', 'admin')";
$conn->query($sql_admin);

?>
