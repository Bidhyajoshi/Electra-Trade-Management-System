<?php
require_once 'includes/db.php';

header('Content-Type: application/json');

$query = isset($_GET['q']) ? trim($_GET['q']) : '';

if (strlen($query) > 0) {
    $stmt = $conn->prepare("SELECT id, name FROM products WHERE name LIKE ? OR category LIKE ? LIMIT 5");
    $searchTerm = '%' . $query . '%';
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $suggestions = [];
    while ($row = $result->fetch_assoc()) {
        $suggestions[] = $row;
    }
    
    echo json_encode($suggestions);
} else {
    echo json_encode([]);
}
