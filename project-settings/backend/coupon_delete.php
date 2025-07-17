<?php
include '../../backend/db_connection.php';

header('Content-Type: application/json');

global $conn;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

$id = $_POST['id'] ?? 0;

if (!$id) {
    echo json_encode(['status' => 'error', 'message' => 'Missing coupon ID']);
    exit;
}

$stmt = $conn->prepare("DELETE FROM coupons WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Coupon deleted successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error deleting coupon: ' . $stmt->error]);
}

$stmt->close();
?>
