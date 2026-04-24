<?php 
require_once 'includes/db.php';
require_once 'includes/header.php'; 

// Fetch products
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

$pageTitle = "Our Catalog";
if (!empty($category)) {
    $pageTitle = "Our " . ucfirst(strtolower($category));
}

if ($search !== '') {
    $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ? OR category LIKE ? ORDER BY id DESC");
    $searchTerm = '%' . $search . '%';
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} elseif (!empty($category)) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE category = ? ORDER BY id DESC");
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT * FROM products ORDER BY id DESC";
    $result = $conn->query($sql);
}
?>

<section class="hero" style="height: 40vh; background: linear-gradient(rgba(0, 21, 41, 0.8), rgba(0, 21, 41, 0.8)), url('https://images.unsplash.com/photo-1555664424-778a1e5e1b48?auto=format&fit=crop&q=80') no-repeat center center/cover;">
    <div class="hero-content reveal active">
        <h1><?= htmlspecialchars($pageTitle) ?></h1>
    </div>
</section>

<section class="section-padding container">
    <?php
    if (isset($_SESSION['message'])) {
        echo "<div class='alert alert-success'>" . $_SESSION['message'] . "</div>";
        unset($_SESSION['message']);
    }
    ?>

    <div class="product-gallery">
        <?php if ($result === false): ?>
            <p style="color: red; grid-column: 1 / -1; text-align: center; padding: 2rem; background: rgba(255,0,0,0.1); border-radius: 8px;">
                <strong>Debug Error:</strong> <?= htmlspecialchars($conn->error) ?>
            </p>
        <?php elseif ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="product-card reveal">
                    <a href="product_details.php?id=<?= $row['id'] ?>" style="text-decoration: none; color: inherit;">
                        <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" class="product-image" onerror="this.src='https://images.unsplash.com/photo-1580983546571-085e3477c7c0?auto=format&fit=crop&q=80&w=400';">
                        <div class="product-info">
                            <h3 class="product-title"><?= htmlspecialchars($row['name']) ?></h3>
                            <div class="product-price">₹<?= number_format($row['price'], 2) ?></div>
                            <p class="product-desc"><?= htmlspecialchars(substr((string)$row['description'], 0, 80)) ?>...</p>
                            <a href="product_details.php?id=<?= $row['id'] ?>" class="btn btn-outline" style="width: 100%; display: inline-block; text-align: center; text-decoration: none;">View Details</a>
                        </div>
                    </a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 1rem;">
                <?php if (!empty($search)): ?>
                    <svg width="64" height="64" fill="none" stroke="var(--electric-gold)" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom: 1rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <h3 style="font-size: 1.5rem; margin-bottom: 0.5rem; color: #fff;">Sorry, no products match your search.</h3>
                    <p style="color: var(--light-slate);">Try checking your spelling or use more general terms.</p>
                    <a href="products.php" class="btn btn-outline" style="margin-top: 1.5rem; display: inline-block;">Clear Search</a>
                <?php else: ?>
                    <p style="color: var(--light-slate);">No products available at the moment. Please check back later.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
