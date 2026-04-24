<?php
require_once 'includes/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
    header("Location: products.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    header("Location: products.php");
    exit;
}

require_once 'includes/header.php';
?>

<style>
    .details-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        margin-top: 2rem;
        align-items: start;
    }
    .product-main-image {
        width: 100%;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        background: #fff;
        padding: 10px;
    }
    .details-right h1 {
        font-size: 2.5rem;
        color: var(--navy-blue);
        margin-bottom: 0.5rem;
    }
    .category-badge {
        display: inline-block;
        background: #f1f5f9;
        color: #64748b;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        text-transform: uppercase;
    }
    .price-tag {
        font-size: 2rem;
        color: #10b981;
        font-weight: 800;
        margin-bottom: 2rem;
    }
    .selection-group {
        margin-bottom: 25px;
        text-align: left;
    }
    .selection-group label {
        display: block;
        font-weight: 700;
        margin-bottom: 0.8rem;
        color: var(--navy-blue);
        font-size: 14px;
        letter-spacing: 0.5px;
    }
    .custom-select {
        width: 200px;
        padding: 12px 15px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.95rem;
        background: #fff;
        cursor: pointer;
        outline: none;
        transition: all 0.3s ease;
    }
    .custom-select:focus {
        border-color: var(--electric-gold);
    }
    .swatch-container {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        margin-top: 5px;
    }
    .color-swatch {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 2px solid #e2e8f0;
        cursor: pointer;
        position: relative;
        transition: all 0.2s ease;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .color-swatch:hover {
        transform: scale(1.1);
    }
    .color-swatch.selected {
        border: 3px solid var(--electric-gold);
        box-shadow: 0 0 10px rgba(255, 215, 0, 0.6);
        transform: scale(1.05);
    }
    .color-swatch.selected::after {
        content: '\f00c';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        color: #fff;
        font-size: 0.9rem;
    }
    /* White/Ivory need dark checkmark */
    .swatch-white.selected::after, .swatch-ivory.selected::after {
        color: var(--navy-blue);
    }

    /* Specific Colors */
    .swatch-brown { background-color: #5d4037; }
    .swatch-white { background-color: #ffffff; border-color: #cbd5e1; }
    .swatch-ivory { background-color: #fffff0; border-color: #cbd5e1; }
    .swatch-matte-black { background-color: #212121; }
    .swatch-red { background-color: #ef4444; }
    .swatch-black { background-color: #000000; }
    .swatch-green { background-color: #22c55e; }
    .swatch-yellow { background-color: #eab308; }
    .swatch-blue { background-color: #3b82f6; }

    .details-right {
        padding-left: 20px;
    }
    .quantity-wrapper {
        display: flex;
        flex-direction: column;
        gap: 25px;
        margin-top: 2rem;
        align-items: flex-start;
    }
    .qty-control {
        display: flex;
        align-items: center;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
        width: 200px;
        justify-content: space-between;
    }
    .qty-btn {
        background: #f8fafc;
        color: var(--navy-blue);
        border: none;
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
        font-weight: bold;
        cursor: pointer;
        transition: background 0.3s;
    }
    .qty-btn:hover { background: #e2e8f0; }
    .qty-input {
        width: 50px;
        text-align: center;
        border: none;
        font-size: 1.2rem;
        font-weight: 700;
        outline: none;
        color: var(--navy-blue);
    }
    .add-to-cart-btn {
        width: 100%;
        max-width: 400px;
        background: var(--navy-blue);
        color: var(--electric-gold);
        border: none;
        padding: 18px;
        font-size: 1.2rem;
        font-weight: 800;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 15px;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .add-to-cart-btn:hover {
        background: #1e293b;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(10, 25, 47, 0.2);
    }
    .product-meta {
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 1px solid #e2e8f0;
        font-size: 0.95rem;
        color: #64748b;
        text-align: left;
    }
    @media (max-width: 992px) {
        .details-container { grid-template-columns: 1fr; gap: 3rem; }
        .details-right { padding-left: 0; }
        .custom-select, .add-to-cart-btn, .qty-control { max-width: 100%; width: 100%; }
    }
</style>

<section class="section-padding container" style="margin-top: 80px;">
    <div class="details-container">
        <!-- Left: Image -->
        <div class="details-left">
            <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-main-image" onerror="this.src='https://images.unsplash.com/photo-1580983546571-085e3477c7c0?auto=format&fit=crop&q=80&w=600';">
        </div>

        <!-- Right: Content -->
        <div class="details-right">
            <span class="category-badge"><?= htmlspecialchars($product['category']) ?></span>
            <h1><?= htmlspecialchars($product['name']) ?></h1>
            <div class="price-tag">₹<?= number_format($product['price'], 2) ?></div>
            
            <p style="line-height: 1.8; color: #475569; margin-bottom: 2rem;">
                <?= nl2br(htmlspecialchars($product['description'])) ?>
            </p>

            <form action="cart.php" method="POST">
                <input type="hidden" name="product_id" value="<?= $id ?>">
                <input type="hidden" name="action" value="add">

                <?php 
                $category = strtolower($product['category']);
                $name = strtolower($product['name']);
                
                if ($category === 'lighting'): ?>
                    <div class="selection-group">
                        <label>Select Size/Wattage:</label>
                        <select name="variant" class="custom-select" required>
                            <option value="9W">9 Watt</option>
                            <option value="12W">12 Watt</option>
                            <option value="15W">15 Watt</option>
                            <option value="18W">18 Watt</option>
                            <option value="22W">22 Watt</option>
                        </select>
                    </div>
                <?php elseif ($category === 'wires'): ?>
                    <div class="selection-group">
                        <label>Select Size/Thickness:</label>
                        <select name="variant" class="custom-select" required>
                            <option value="0.75mm">0.75 mm</option>
                            <option value="1mm">1.0 mm</option>
                            <option value="1.5mm">1.5 mm</option>
                            <option value="2.5mm">2.5 mm</option>
                            <option value="4mm">4.0 mm</option>
                            <option value="6mm">6.0 mm</option>
                        </select>
                    </div>
                    <div class="selection-group">
                        <label>Choose Color:</label>
                        <div class="swatch-container">
                            <div class="color-swatch swatch-red" data-color="Red" title="Red"></div>
                            <div class="color-swatch swatch-black" data-color="Black" title="Black"></div>
                            <div class="color-swatch swatch-green" data-color="Green" title="Green"></div>
                            <div class="color-swatch swatch-yellow" data-color="Yellow" title="Yellow"></div>
                            <div class="color-swatch swatch-blue" data-color="Blue" title="Blue"></div>
                        </div>
                        <input type="hidden" name="color" id="selected-color" required>
                    </div>
                <?php elseif ($category === 'fans'): ?>
                    <div class="selection-group">
                        <label>Select Sweep Size:</label>
                        <select name="variant" class="custom-select" required>
                            <option value="600mm">600 mm</option>
                            <option value="900mm">900 mm</option>
                            <option value="1200mm">1200 mm</option>
                            <option value="1400mm">1400 mm</option>
                        </select>
                    </div>
                    <div class="selection-group">
                        <label>Choose Color:</label>
                        <div class="swatch-container">
                            <div class="color-swatch swatch-brown" data-color="Brown" title="Brown"></div>
                            <div class="color-swatch swatch-white" data-color="White" title="White"></div>
                            <div class="color-swatch swatch-ivory" data-color="Ivory" title="Ivory"></div>
                            <div class="color-swatch swatch-matte-black" data-color="Matte Black" title="Matte Black"></div>
                        </div>
                        <input type="hidden" name="color" id="selected-color" required>
                    </div>
                <?php elseif ($category === 'tools' && (strpos($name, 'switch board') !== false || strpos($name, 'modular') !== false)): ?>
                    <div class="selection-group">
                        <label>Select Modules:</label>
                        <select name="variant" class="custom-select" required>
                            <option value="1 Module">1 Module</option>
                            <option value="2 Module">2 Module</option>
                            <option value="4 Module">4 Module</option>
                            <option value="6 Module">6 Module</option>
                            <option value="8 Module">8 Module</option>
                            <option value="12 Module">12 Module</option>
                            <option value="18 Module">18 Module</option>
                        </select>
                    </div>
                <?php endif; ?>

                <div class="quantity-wrapper">
                    <div class="qty-control">
                        <button type="button" class="qty-btn" id="minus">-</button>
                        <input type="number" name="quantity" value="1" min="1" max="<?= $product['stock'] ?>" class="qty-input" id="qty-input" readonly>
                        <button type="button" class="qty-btn" id="plus">+</button>
                    </div>
                    <button type="submit" class="add-to-cart-btn">
                        <i class="fas fa-cart-plus"></i> ADD TO CART
                    </button>
                </div>
            </form>

            <div class="product-meta">
                <div style="margin-bottom: 0.5rem;"><i class="fas fa-check-circle" style="color: #10b981; margin-right: 8px;"></i> In Stock (<?= $product['stock'] ?> units)</div>
                <div style="margin-bottom: 0.5rem;"><i class="fas fa-truck" style="color: var(--navy-blue); margin-right: 8px;"></i> Free Delivery on orders above ₹2000</div>
                <div><i class="fas fa-shield-alt" style="color: var(--navy-blue); margin-right: 8px;"></i> 1 Year Brand Warranty</div>
            </div>
        </div>
    </div>
</section>

<script>
    const minusBtn = document.getElementById('minus');
    const plusBtn = document.getElementById('plus');
    const qtyInput = document.getElementById('qty-input');
    const maxStock = <?= $product['stock'] ?>;

    minusBtn.addEventListener('click', () => {
        let val = parseInt(qtyInput.value);
        if (val > 1) qtyInput.value = val - 1;
    });

    plusBtn.addEventListener('click', () => {
        let val = parseInt(qtyInput.value);
        if (val < maxStock) {
            qtyInput.value = val + 1;
        } else {
            alert('Stock limit reached.');
        }
    });

    // Color Swatch Selection
    const swatches = document.querySelectorAll('.color-swatch');
    const colorInput = document.getElementById('selected-color');

    swatches.forEach(swatch => {
        swatch.addEventListener('click', function() {
            swatches.forEach(s => s.classList.remove('selected'));
            this.classList.add('selected');
            colorInput.value = this.getAttribute('data-color');
        });
    });

    // Form Validation for Color
    document.querySelector('form').addEventListener('submit', function(e) {
        const colorReq = document.getElementById('selected-color');
        if (colorReq && !colorReq.value) {
            e.preventDefault();
            alert('Please select a color before adding to cart.');
        }
    });
</script>

<?php require_once 'includes/footer.php'; ?>
