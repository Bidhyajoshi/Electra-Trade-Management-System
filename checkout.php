<?php
require_once 'includes/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "Please log in to checkout.";
    header("Location: login.php");
    exit;
}

if (empty($_SESSION['cart'])) {
    $_SESSION['message'] = "Your cart is empty.";
    header("Location: products.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['shipping_address'] ?? '');
    
    if (empty($full_name) || empty($email) || empty($phone) || empty($address)) {
        $error = "Please fill in all shipping details.";
    } else {
        $user_id = $_SESSION['user_id'];
        $total = 0;
        $items = [];
        
        // Calculate total and prepare items
        foreach ($_SESSION['cart'] as $cart_id => $item) {
            $pid = $item['id'];
            $qty = $item['qty'];
            $variant = $item['variant'];
            
            $sql = "SELECT price, stock FROM products WHERE id = " . (int)$pid;
            $res = $conn->query($sql);
            if ($row = $res->fetch_assoc()) {
                if ($qty > $row['stock']) {
                    $error = "Not enough stock for some items. Please update your cart.";
                    break;
                }
                $total += $row['price'] * $qty;
                $items[] = [
                    'pid' => $pid,
                    'qty' => $qty,
                    'price' => $row['price'],
                    'variant' => $variant
                ];
            }
        }
        
        if (empty($error)) {
            // Start transaction
            $conn->begin_transaction();
            try {
                // Insert order
                $stmt = $conn->prepare("INSERT INTO orders (user_id, full_name, email, phone, shipping_address, total, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
                $stmt->bind_param("issssd", $user_id, $full_name, $email, $phone, $address, $total);
                $stmt->execute();
                $order_id = $stmt->insert_id;
                
                // Insert items & update stock
                $stmt_items = $conn->prepare("INSERT INTO order_items (order_id, product_id, variant, quantity, price) VALUES (?, ?, ?, ?, ?)");
                $stmt_stock = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
                
                foreach ($items as $item) {
                    // Item
                    $stmt_items->bind_param("iisid", $order_id, $item['pid'], $item['variant'], $item['qty'], $item['price']);
                    $stmt_items->execute();
                    // Stock
                    $stmt_stock->bind_param("ii", $item['qty'], $item['pid']);
                    $stmt_stock->execute();
                }
                
                $conn->commit();
                
                $_SESSION['cart'] = [];
                $_SESSION['message'] = "Order placed successfully! Thank you for shopping with Joshi Trading.";
                header("Location: order_success.php?order_id=" . $order_id);
                exit;
            } catch (Exception $e) {
                $conn->rollback();
                $error = "Error placing order. Please try again.";
            }
        }
    }
}

require_once 'includes/header.php';
?>

<style>
.checkout-container {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    margin-top: 2rem;
}
.checkout-box {
    background: #ffffff;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    border: 1px solid #e2e8f0;
}
.checkout-box h3 {
    margin-bottom: 1.5rem;
    color: var(--navy-blue);
    border-bottom: 2px solid var(--electric-gold);
    padding-bottom: 0.5rem;
    display: inline-block;
}
.form-group label {
    color: #475569;
    font-weight: 500;
}
.form-control {
    background: #f8fafc;
    border: 1px solid #cbd5e1;
    color: #0f172a;
}
.form-control:focus {
    background: #fff;
    border-color: var(--electric-gold);
}
.summary-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #f1f5f9;
    color: #334155;
}
.summary-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
}
.variant-badge {
    background: #f1f5f9;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.75rem;
    color: #64748b;
}
@media (max-width: 768px) {
    .checkout-container {
        grid-template-columns: 1fr;
    }
}
</style>

<section class="section-padding container" style="margin-top: 80px;">
    <h2>Secure Checkout</h2>
    
    <?php if ($error): ?>
        <div class="alert alert-danger" style="margin-top: 1rem;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="checkout-container">
        <!-- Shipping Form -->
        <div class="checkout-box">
            <h3>Shipping Details</h3>
            <form action="checkout.php" method="POST">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name" class="form-control" placeholder="John Doe" required>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
                    </div>
                    <div class="form-group">
                        <label>Active Mobile Number</label>
                        <input type="text" name="phone" class="form-control" placeholder="+91 9876543210" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Complete Shipping Address (Building, Street, City, Pincode)</label>
                    <textarea name="shipping_address" class="form-control" rows="3" placeholder="Flat No. 101, Sai Apartments..." required></textarea>
                </div>
                
                <div class="form-group" style="margin-top: 1.5rem; background: #f8fafc; padding: 1rem; border-radius: 5px; border: 1px solid #cbd5e1;">
                    <label style="display: flex; align-items: center; cursor: pointer; color: var(--navy-blue);">
                        <input type="radio" name="payment" value="cod" checked style="margin-right: 10px; accent-color: var(--electric-gold);">
                        <strong>Cash on Delivery (COD)</strong>
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1.5rem; font-size: 1.1rem; padding: 1rem;">Confirm Order</button>
            </form>
        </div>
        
        <!-- Order Summary -->
        <div class="checkout-box" style="height: fit-content;">
            <h3>Order Summary</h3>
            <?php
            $grand_total = 0;
            foreach ($_SESSION['cart'] as $item):
                $pid = $item['id'];
                $qty = $item['qty'];
                $variant = $item['variant'];
                
                $sql = "SELECT name, price FROM products WHERE id = " . (int)$pid;
                $result = $conn->query($sql);
                if ($row = $result->fetch_assoc()):
                    $subtotal = $row['price'] * $qty;
                    $grand_total += $subtotal;
            ?>
                <div class="summary-item">
                    <div>
                        <strong><?= htmlspecialchars($row['name']) ?></strong>
                        <?php if ($variant): ?>
                            <br><span class="variant-badge"><?= htmlspecialchars($variant) ?></span>
                        <?php endif; ?>
                        <br><small>Qty: <?= $qty ?></small>
                    </div>
                    <strong>₹<?= number_format($subtotal, 2) ?></strong>
                </div>
            <?php 
                endif;
            endforeach; 
            ?>
            
            <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 2px dashed #cbd5e1; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin-bottom: 0; border: none; padding: 0;">Total to Pay</h3>
                <h3 style="margin-bottom: 0; border: none; padding: 0; color: #10b981;">₹<?= number_format($grand_total, 2) ?></h3>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
