<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
if (!$order_id) {
    header("Location: index.php");
    exit;
}

// Check if order belongs to user
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: index.php");
    exit;
}
$order = $result->fetch_assoc();

require_once 'includes/header.php';
?>
<section class="section-padding container" style="margin-top: 80px; text-align: center; min-height: 60vh; display: flex; flex-direction: column; justify-content: center; align-items: center;">
    <div style="background: #fff; padding: 3rem; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); max-width: 600px; width: 100%; border-top: 4px solid var(--electric-gold);">
        <i class="fas fa-check-circle" style="font-size: 5rem; color: #10b981; margin-bottom: 1.5rem;"></i>
        <h2 style="color: var(--navy-blue); margin-bottom: 1rem;">Order Placed Successfully!</h2>
        <p style="font-size: 1.1rem; color: #475569; margin-bottom: 2rem;">
            Thank you for shopping with Joshi Trading. Your order <strong>#JT-<?= sprintf("%05d", $order_id) ?></strong> has been received and is being processed.
        </p>
        
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="index.php" class="btn btn-outline" style="border: 2px solid var(--navy-blue); color: var(--navy-blue); padding: 0.8rem 1.5rem; text-decoration: none; border-radius: 4px; transition: all 0.3s ease;">
                Continue Shopping
            </a>
            <a href="download_invoice.php?order_id=<?= $order_id ?>" target="_blank" class="btn btn-primary" style="background: var(--navy-blue); color: var(--electric-gold); padding: 0.8rem 1.5rem; text-decoration: none; border-radius: 4px; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease; border: 2px solid var(--navy-blue);">
                <i class="fas fa-file-invoice"></i> Download Invoice
            </a>
        </div>
    </div>
</section>

<style>
.btn-outline:hover {
    background: var(--navy-blue) !important;
    color: var(--electric-gold) !important;
}
.btn-primary:hover {
    background: var(--electric-gold) !important;
    color: var(--navy-blue) !important;
    border-color: var(--electric-gold) !important;
}
</style>

<?php require_once 'includes/footer.php'; ?>
