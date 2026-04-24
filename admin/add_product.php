<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $desc = trim($_POST['description']);
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $image = 'default.jpg'; // In a real app, handle file upload here
    
    // Simple URL/Name fallback for demo purposes
    if (!empty($_POST['image_url'])) {
        $image = trim($_POST['image_url']);
    }

    if (!empty($name) && $price > 0) {
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdis", $name, $desc, $price, $stock, $image);
        if ($stmt->execute()) {
            $_SESSION['message'] = "Product added successfully!";
            header("Location: index.php");
            exit;
        } else {
            $error = "Error adding product.";
        }
    } else {
        $error = "Please provide valid name and price.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Joshi Trading Pro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-layout {
            display: grid;
            grid-template-columns: 250px 1fr;
            min-height: 100vh;
        }
        .sidebar {
            background-color: var(--navy-light);
            padding: 2rem 1rem;
            border-right: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar a {
            display: block;
            padding: 1rem;
            color: var(--light-slate);
            border-radius: 5px;
            margin-bottom: 0.5rem;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: rgba(255,193,7,0.1);
            color: var(--electric-gold);
        }
        .main-content {
            padding: 2rem;
            max-width: 800px;
        }
    </style>
</head>
<body>

<div class="admin-layout">
    <aside class="sidebar">
        <h2 style="color: var(--electric-gold); font-size: 1.5rem; margin-bottom: 2rem; text-align: center;">Admin Panel</h2>
        <a href="index.php">Dashboard</a>
        <a href="users.php">Manage Users</a>
        <a href="add_product.php" class="active">Add Product</a>
        <a href="../index.php">View Site</a>
        <a href="../logout.php" style="color: var(--danger);">Logout</a>
    </aside>
    
    <main class="main-content">
        <h2 style="margin-bottom: 2rem;">Add New Product</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="glass-panel">
            <form action="add_product.php" method="POST">
                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="4"></textarea>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Price (₹)</label>
                        <input type="number" step="0.01" name="price" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Stock Quantity</label>
                        <input type="number" name="stock" class="form-control" value="0">
                    </div>
                </div>
                <div class="form-group">
                    <label>Image URL (Optional for demo)</label>
                    <input type="text" name="image_url" class="form-control" placeholder="https://images.unsplash.com/...">
                </div>
                
                <button type="submit" class="btn btn-primary" style="margin-top: 1rem;">Save Product</button>
                <a href="index.php" class="btn btn-outline" style="margin-top: 1rem; margin-left: 1rem;">Cancel</a>
            </form>
        </div>
    </main>
</div>

</body>
</html>
