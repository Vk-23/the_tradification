<?php
include 'db_connection.php';

header('Content-Type: application/json');

global $conn;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

$code = $_POST['code'] ?? '';
$type = $_POST['type'] ?? '';
$value = $_POST['value'] ?? '';
$min_amount = $_POST['min_amount'] ?? '';

if (!$code || !$type || !$value || !$min_amount) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
    exit;
}

if (!in_array($type, ['percentage', 'amount'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid coupon type']);
    exit;
}

try {
    $stmt = $conn->prepare("INSERT INTO coupons (code, type, value, min_amount) VALUES (:code, :type, :value, :min_amount)");
    $stmt->execute([
        'code' => $code,
        'type' => $type,
        'value' => $value,
        'min_amount' => $min_amount
    ]);

    echo json_encode(['status' => 'success', 'message' => 'Coupon created successfully']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error creating coupon: ' . $e->getMessage()]);
}
?>
