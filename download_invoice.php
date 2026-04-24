<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
if (!$order_id) {
    die("Invalid Order ID");
}

$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $_SESSION['user_id']);
$stmt->execute();
$order_res = $stmt->get_result();

if ($order_res->num_rows === 0) {
    die("Order not found or unauthorized access.");
}
$order = $order_res->fetch_assoc();

$stmt_items = $conn->prepare("
    SELECT oi.*, p.name 
    FROM order_items oi 
    JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = ?
");
$stmt_items->bind_param("i", $order_id);
$stmt_items->execute();
$items_res = $stmt_items->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - #JT-<?= sprintf("%05d", $order_id) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy-blue: #0a192f;
            --electric-gold: #FFD700;
        }
        body {
            font-family: 'Open Sans', sans-serif;
            background: #f1f5f9;
            color: #0f172a;
            margin: 0;
            padding: 20px;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-top: 8px solid var(--navy-blue);
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid var(--electric-gold);
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .company-details {
            color: var(--navy-blue);
        }
        .company-name {
            font-family: 'Montserrat', sans-serif;
            font-weight: 800;
            font-size: 24px;
            color: var(--navy-blue);
            margin: 0 0 5px 0;
        }
        .company-address {
            font-size: 14px;
            color: #475569;
            margin: 0;
            line-height: 1.5;
        }
        .invoice-title {
            text-align: right;
        }
        .invoice-title h1 {
            font-family: 'Montserrat', sans-serif;
            color: var(--electric-gold);
            margin: 0;
            font-size: 36px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .invoice-meta {
            font-size: 14px;
            color: #475569;
            margin-top: 10px;
        }
        .billing-shipping {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }
        .billing-box {
            width: 48%;
        }
        .billing-box h3 {
            font-family: 'Montserrat', sans-serif;
            color: var(--navy-blue);
            font-size: 16px;
            margin-top: 0;
            margin-bottom: 10px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
        }
        .billing-box p {
            margin: 0;
            font-size: 14px;
            line-height: 1.6;
            color: #334155;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .invoice-table th {
            background: var(--navy-blue);
            color: #fff;
            padding: 12px;
            text-align: left;
            font-family: 'Montserrat', sans-serif;
            font-size: 14px;
        }
        .invoice-table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
            font-size: 14px;
        }
        .invoice-table th.text-right, .invoice-table td.text-right {
            text-align: right;
        }
        .invoice-total {
            width: 100%;
            display: flex;
            justify-content: flex-end;
        }
        .total-box {
            width: 300px;
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
            color: #475569;
        }
        .total-row.grand-total {
            font-family: 'Montserrat', sans-serif;
            font-size: 18px;
            font-weight: 600;
            color: var(--navy-blue);
            border-top: 2px solid var(--electric-gold);
            padding-top: 10px;
            margin-top: 10px;
            margin-bottom: 0;
        }
        .footer-note {
            margin-top: 40px;
            text-align: center;
            font-size: 14px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
        }
        .print-btn {
            display: block;
            width: 200px;
            margin: 20px auto;
            background: var(--navy-blue);
            color: var(--electric-gold);
            text-align: center;
            padding: 12px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            cursor: pointer;
            border: 2px solid var(--navy-blue);
            transition: all 0.3s ease;
        }
        .print-btn:hover {
            background: var(--electric-gold);
            color: var(--navy-blue);
        }
        @media print {
            body { background: #fff; padding: 0; }
            .invoice-container { box-shadow: none; border-top: none; padding: 0; max-width: 100%; }
            .print-btn { display: none; }
        }
    </style>
</head>
<body>

<button class="print-btn" onclick="window.print()">Print / Download PDF</button>

<div class="invoice-container">
    <div class="invoice-header">
        <div class="company-details">
            <h2 class="company-name">JOSHI TRADING</h2>
            <p class="company-address">
                In front of Krishi Bikas Bank, Rajbiraj<br>
                Saptari, Nepal<br>
                Phone: +977 9841954134, +977 9841815784<br>
                Email: bidhyajoshi21@gmail.com
            </p>
        </div>
        <div class="invoice-title">
            <h1>INVOICE</h1>
            <div class="invoice-meta">
                <div><strong>Invoice Number:</strong> #JT-<?= sprintf("%05d", $order_id) ?></div>
                <div><strong>Date:</strong> <?= date('F j, Y', strtotime($order['created_at'] ?? 'now')) ?></div>
                <div><strong>Status:</strong> <span style="text-transform: capitalize; color: #10b981; font-weight: 600;"><?= htmlspecialchars($order['status'] ?? 'completed') ?></span></div>
            </div>
        </div>
    </div>

    <div class="billing-shipping">
        <div class="billing-box">
            <h3>Billed To</h3>
            <p>
                <strong><?= htmlspecialchars($order['full_name']) ?></strong><br>
                <?= nl2br(htmlspecialchars($order['shipping_address'])) ?><br>
                Phone: <?= htmlspecialchars($order['phone']) ?><br>
                Email: <?= htmlspecialchars($order['email']) ?>
            </p>
        </div>
    </div>

    <table class="invoice-table">
        <thead>
            <tr>
                <th>Item Description</th>
                <th class="text-right">Price</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $subtotal = 0;
            while ($item = $items_res->fetch_assoc()): 
                $item_total = $item['price'] * $item['quantity'];
                $subtotal += $item_total;
            ?>
            <tr>
                <td>
                    <strong><?= htmlspecialchars($item['name']) ?></strong>
                    <?php if ($item['variant']): ?>
                        <br><span style="font-size: 12px; color: #64748b;">Option: <?= htmlspecialchars($item['variant']) ?></span>
                    <?php endif; ?>
                </td>
                <td class="text-right">₹<?= number_format($item['price'], 2) ?></td>
                <td class="text-right"><?= $item['quantity'] ?></td>
                <td class="text-right">₹<?= number_format($item_total, 2) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="invoice-total">
        <div class="total-box">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>₹<?= number_format($subtotal, 2) ?></span>
            </div>
            <div class="total-row">
                <span>Shipping:</span>
                <span>₹0.00</span>
            </div>
            <div class="total-row grand-total">
                <span>Total:</span>
                <span>₹<?= number_format($order['total'], 2) ?></span>
            </div>
        </div>
    </div>

    <div class="footer-note">
        <p>Thank you for your business! If you have any questions concerning this invoice, contact our support.</p>
    </div>
</div>

</body>
</html>
