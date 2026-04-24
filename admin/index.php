<?php
session_start();
require_once '../includes/db.php';

// Check if admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Stats
$total_products = $conn->query("SELECT COUNT(*) as c FROM products")->fetch_assoc()['c'];
$total_orders = $conn->query("SELECT COUNT(*) as c FROM orders")->fetch_assoc()['c'];
$total_revenue = $conn->query("SELECT SUM(total) as t FROM orders")->fetch_assoc()['t'] ?? 0;

// Fetch latest orders
$orders_query = "
    SELECT 
        o.id as order_id,
        o.full_name,
        o.shipping_address,
        o.created_at,
        p.name as product_name,
        oi.quantity
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN products p ON oi.product_id = p.id
    ORDER BY o.created_at DESC
    LIMIT 15
";
$recent_orders = $conn->query($orders_query);

// Fetch products for listing
$products = $conn->query("SELECT * FROM products ORDER BY id DESC");

// Fetch feedbacks
$feedbacks = $conn->query("SELECT * FROM feedbacks ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Joshi Trading Pro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-layout {
            display: grid;
            grid-template-columns: 250px 1fr;
            min-height: 100vh;
        }
        /* Tab Styles */
        .tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            border-bottom: 1px solid rgba(0,0,0,0.1);
            padding-bottom: 0.5rem;
        }
        .tab-btn {
            background: none;
            border: none;
            padding: 0.5rem 1rem;
            font-size: 1.1rem;
            font-weight: 500;
            color: var(--light-slate);
            cursor: pointer;
            transition: all 0.3s ease;
            border-bottom: 2px solid transparent;
        }
        .tab-btn.active {
            color: var(--navy-blue);
            border-bottom-color: var(--electric-gold);
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
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
        }
        @media (max-width: 768px) {
            .admin-layout { grid-template-columns: 1fr; }
            .sidebar { display: none; } /* Simple hidden sidebar for mobile */
        }
    </style>
</head>
<body>

<div class="admin-layout">
    <aside class="sidebar">
        <h2 style="color: var(--electric-gold); font-size: 1.5rem; margin-bottom: 2rem; text-align: center;">Admin Panel</h2>
        <a href="index.php" class="active">Dashboard</a>
        <a href="users.php">Manage Users</a>
        <a href="add_product.php">Add Product</a>
        <a href="../index.php">View Site</a>
        <a href="../logout.php" style="color: var(--danger);">Logout</a>
    </aside>
    
    <main class="main-content">
        <h2>Dashboard</h2>
        
        <?php
        if (isset($_SESSION['message'])) {
            echo "<div class='alert alert-success'>" . $_SESSION['message'] . "</div>";
            unset($_SESSION['message']);
        }
        ?>

        <div class="dashboard-grid">
            <div class="glass-panel stat-card">
                <h3>Total Products</h3>
                <div class="number"><?= $total_products ?></div>
            </div>
            <div class="glass-panel stat-card">
                <h3>Total Orders</h3>
                <div class="number"><?= $total_orders ?></div>
            </div>
            <div class="glass-panel stat-card">
                <h3>Revenue</h3>
                <div class="number">₹<?= number_format($total_revenue, 2) ?></div>
            </div>
        </div>

        <div class="tabs">
            <button class="tab-btn active" onclick="switchTab('orders')">Latest Orders</button>
            <button class="tab-btn" onclick="switchTab('feedbacks')">Feedbacks</button>
            <button class="tab-btn" onclick="switchTab('products')">Manage Products</button>
        </div>

        <div id="orders" class="tab-content active glass-panel" style="margin-bottom: 2rem;">
            <h3 style="margin-bottom: 1.5rem;">Latest Orders</h3>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer Name</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Shipping Address</th>
                            <th>Order Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($recent_orders && $recent_orders->num_rows > 0): ?>
                            <?php while($ord = $recent_orders->fetch_assoc()): ?>
                            <tr>
                                <td>#<?= $ord['order_id'] ?></td>
                                <td><strong><?= htmlspecialchars($ord['full_name']) ?></strong></td>
                                <td><?= htmlspecialchars($ord['product_name']) ?></td>
                                <td><?= $ord['quantity'] ?></td>
                                <td><small><?= htmlspecialchars($ord['shipping_address']) ?></small></td>
                                <td><?= date('M d, g:i A', strtotime($ord['created_at'])) ?></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6" style="text-align:center;">No orders found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="feedbacks" class="tab-content glass-panel" style="margin-bottom: 2rem;">
            <h3 style="margin-bottom: 1.5rem;">Customer Feedbacks</h3>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Rating</th>
                            <th>Comments</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($feedbacks && $feedbacks->num_rows > 0): ?>
                            <?php while($fb = $feedbacks->fetch_assoc()): ?>
                            <tr>
                                <td><?= $fb['id'] ?></td>
                                <td><strong><?= htmlspecialchars($fb['name']) ?></strong></td>
                                <td style="color: var(--electric-gold); font-size: 1.2rem;"><?= str_repeat('★', $fb['rating']) . str_repeat('☆', 5 - $fb['rating']) ?></td>
                                <td><?= htmlspecialchars($fb['comments']) ?></td>
                                <td><?= date('M d, g:i A', strtotime($fb['created_at'])) ?></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5" style="text-align:center;">No feedbacks found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="products" class="tab-content glass-panel">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3>Manage Products</h3>
                <a href="add_product.php" class="btn btn-primary">Add New Product</a>
            </div>
            
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $products->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><img src="../assets/images/<?= htmlspecialchars($row['image']) ?>" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;"></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td>₹<?= number_format($row['price'], 2) ?></td>
                            <td><?= $row['stock'] ?></td>
                            <td>
                                <a href="edit_product.php?id=<?= $row['id'] ?>" class="btn btn-outline" style="padding: 0.3rem 0.8rem; font-size: 0.9rem;">Edit</a>
                                <a href="delete_product.php?id=<?= $row['id'] ?>" class="btn btn-danger" style="padding: 0.3rem 0.8rem; font-size: 0.9rem;" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <script>
        function switchTab(tabId) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            // Remove active from all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById(tabId).classList.add('active');
            // Find and set active button
            event.currentTarget.classList.add('active');
        }
        </script>
    </main>
</div>

</body>
</html>
