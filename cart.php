<?php
require_once 'includes/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle cart actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $variant = isset($_POST['variant']) ? trim($_POST['variant']) : '';
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    $cart_id = $product_id . '_' . base64_encode($variant);
    
    if ($_POST['action'] === 'add' && $product_id > 0) {
        $color = isset($_POST['color']) ? trim($_POST['color']) : '';
        if ($color !== '') {
            $variant = ($variant !== '') ? $variant . " - " . $color : $color;
        }
        $cart_id = $product_id . '_' . base64_encode($variant);
        
        if (isset($_SESSION['cart'][$cart_id])) {
            $_SESSION['cart'][$cart_id]['qty'] += $quantity;
        } else {
            $_SESSION['cart'][$cart_id] = [
                'id' => $product_id,
                'qty' => $quantity,
                'variant' => $variant
            ];
        }
        $_SESSION['message'] = "Product added to cart!";
        header("Location: cart.php");
        exit;
    }
    
    $target_id = isset($_POST['cart_id']) ? $_POST['cart_id'] : '';

    if ($_POST['action'] === 'remove' && $target_id !== '') {
        if (isset($_SESSION['cart'][$target_id])) {
            unset($_SESSION['cart'][$target_id]);
            $_SESSION['message'] = "Product removed from cart.";
        }
        header("Location: cart.php");
        exit;
    }

    if ($_POST['action'] === 'update_quantity' && $target_id !== '') {
        $qty = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        if ($qty > 0) {
            $_SESSION['cart'][$target_id]['qty'] = $qty;
            $_SESSION['message'] = "Cart updated.";
        } else {
            unset($_SESSION['cart'][$target_id]);
            $_SESSION['message'] = "Product removed from cart.";
        }
        header("Location: cart.php");
        exit;
    }
}

require_once 'includes/header.php';
?>

<section class="section-padding container" style="margin-top: 80px;">
    <h2>Your Shopping Cart</h2>
    
    <?php
    if (isset($_SESSION['message'])) {
        echo "<div class='alert alert-success'>" . $_SESSION['message'] . "</div>";
        unset($_SESSION['message']);
    }
    ?>

    <style>
        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        .cart-table th {
            text-align: left;
            padding: 1rem;
            border-bottom: 2px solid var(--electric-gold);
            color: var(--navy-blue);
            font-weight: 700;
        }
        /* Alignment for numeric and action columns */
        .cart-table th:nth-child(2), .cart-table td:nth-child(2),
        .cart-table th:nth-child(4), .cart-table td:nth-child(4) {
            text-align: right;
        }
        .cart-table th:nth-child(3), .cart-table td:nth-child(3),
        .cart-table th:nth-child(5), .cart-table td:nth-child(5) {
            text-align: center;
        }
        
        .cart-table td {
            padding: 1.2rem 1rem;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .price-text {
            color: #333;
            font-weight: 600;
        }
        .subtotal-text {
            color: #333;
            font-weight: 700;
        }
        .qty-selector {
            display: inline-flex;
            align-items: center;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            overflow: hidden;
            background: #fff;
        }
        .qty-btn {
            background: #f8fafc;
            border: none;
            padding: 5px 12px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.2s;
        }
        .qty-btn:hover { background: #e2e8f0; }
        .qty-input {
            width: 45px;
            text-align: center;
            border: none;
            border-left: 1px solid #cbd5e1;
            border-right: 1px solid #cbd5e1;
            padding: 5px 0;
            font-weight: 600;
            color: #333;
        }
        .variant-tag {
            display: inline-block;
            background: #f1f5f9;
            color: #475569;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            margin-top: 4px;
            font-weight: 600;
        }
    </style>

    <div class="glass-panel" style="margin-top: 2rem; background: #fff; padding: 2rem;">
        <?php if (empty($_SESSION['cart'])): ?>
            <div style="text-align: center; padding: 3rem;">
                <i class="fas fa-shopping-basket" style="font-size: 4rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
                <p style="font-size: 1.2rem; color: #64748b;">Your cart is feeling a bit light.</p>
                <a href="products.php" class="btn btn-primary" style="margin-top: 1rem; display: inline-block;">Continue Shopping</a>
            </div>
        <?php else: ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product Details</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $grand_total = 0;
                    foreach ($_SESSION['cart'] as $cart_id => $item):
                        $pid = $item['id'];
                        $qty = $item['qty'];
                        $variant = $item['variant'];
                        
                        $sql = "SELECT * FROM products WHERE id = " . (int)$pid;
                        $result = $conn->query($sql);
                        if ($row = $result->fetch_assoc()):
                            $subtotal = $row['price'] * $qty;
                            $grand_total += $subtotal;
                    ?>
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <img src="<?= htmlspecialchars($row['image']) ?>" style="width: 65px; height: 65px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0;">
                                <div>
                                    <strong style="color: var(--navy-blue); font-size: 1.05rem;"><?= htmlspecialchars($row['name']) ?></strong><br>
                                    <?php if ($variant): ?>
                                        <span class="variant-tag"><?= htmlspecialchars($variant) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td class="price-text">₹<?= number_format($row['price'], 2) ?></td>
                        <td>
                            <form action="cart.php" method="POST" class="qty-form">
                                <input type="hidden" name="cart_id" value="<?= $cart_id ?>">
                                <input type="hidden" name="action" value="update_quantity">
                                <div class="qty-selector">
                                    <button type="button" class="qty-btn minus-btn">-</button>
                                    <input type="number" name="quantity" value="<?= $qty ?>" min="1" max="<?= $row['stock'] ?>" class="qty-input" readonly>
                                    <button type="button" class="qty-btn plus-btn">+</button>
                                </div>
                            </form>
                        </td>
                        <td class="subtotal-text">₹<?= number_format($subtotal, 2) ?></td>
                        <td>
                            <form action="cart.php" method="POST">
                                <input type="hidden" name="cart_id" value="<?= $cart_id ?>">
                                <input type="hidden" name="action" value="remove">
                                <button type="submit" style="background: #fff5f5; border: 1px solid #fed7d7; color: #ef4444; cursor: pointer; font-size: 1rem; padding: 8px 12px; border-radius: 6px; transition: all 0.2s;" onmouseover="this.style.background='#feb2b2'" onmouseout="this.style.background='#fff5f5'"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </tbody>
            </table>
            
            <div style="margin-top: 2rem; display: flex; justify-content: space-between; align-items: flex-end;">
                <a href="products.php" style="color: var(--navy-blue); text-decoration: none; font-weight: 600;"><i class="fas fa-arrow-left"></i> Continue Shopping</a>
                <div style="text-align: right;">
                    <h3 style="color: var(--navy-blue); margin-bottom: 0.5rem; font-size: 1.4rem;">Grand Total: <span style="color: var(--success); font-size: 2.5rem; font-weight: 800; display: block; margin-top: 5px;">₹<?= number_format($grand_total, 2) ?></span></h3>
                    <a href="checkout.php" class="btn btn-primary" style="display: inline-block; padding: 1.2rem 3rem; font-size: 1.2rem; border-radius: 8px; text-transform: uppercase; letter-spacing: 1px;">Proceed to Checkout</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const qtyForms = document.querySelectorAll('.qty-form');
    
    qtyForms.forEach(form => {
        const minusBtn = form.querySelector('.minus-btn');
        const plusBtn = form.querySelector('.plus-btn');
        const input = form.querySelector('.qty-input');
        
        minusBtn.addEventListener('click', () => {
            let val = parseInt(input.value);
            if (val > 1) {
                input.value = val - 1;
                form.submit();
            }
        });
        
        plusBtn.addEventListener('click', () => {
            let val = parseInt(input.value);
            let max = parseInt(input.getAttribute('max')) || 999;
            if (val < max) {
                input.value = val + 1;
                form.submit();
            } else {
                alert('Maximum stock reached.');
            }
        });
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>

