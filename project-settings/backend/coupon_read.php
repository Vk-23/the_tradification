<?php
include '../../backend/db_connection.php';

header('Content-Type: application/json');

global $conn;

if (!$conn) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$sql = "SELECT id, code, type, value, min_amount, created_at FROM coupons ORDER BY created_at DESC";

$result = $conn->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Database query failed: ' . $conn->error]);
    exit;
}

$coupons = [];

while ($row = $result->fetch_assoc()) {
    $coupons[] = $row;
}

echo json_encode($coupons);
?>
