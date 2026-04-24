<?php
require_once 'includes/db.php';
$conn->query("ALTER TABLE users ADD COLUMN full_name VARCHAR(100) AFTER id");
$conn->query("ALTER TABLE users CHANGE username email VARCHAR(100) NOT NULL UNIQUE");
echo "DB Updated!";
?>
