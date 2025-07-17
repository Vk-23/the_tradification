<?php
include 'db_connection.php';
header('Content-Type: application/json');

// Ensure $conn is PDO
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

$coupon_code = $_POST['coupon_code'] ?? '';
$webinar_amount = floatval($_POST['webinar_amount'] ?? 0);

if (!$coupon_code) {
    echo json_encode(['status' => 'error', 'message' => 'Coupon code is required']);
    exit;
}

if ($webinar_amount <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid webinar amount']);
    exit;
}

// Fetch coupon details (using PDO syntax)
$stmt = $conn->prepare("SELECT id, code, type, value, min_amount FROM coupons WHERE code = :code");
$stmt->execute(['code' => $coupon_code]);
$coupon = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$coupon) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid coupon code']);
    exit;
}

// Check min_amount
if ($webinar_amount < floatval($coupon['min_amount'])) {
    echo json_encode(['status' => 'error', 'message' => 'Webinar amount does not meet minimum amount for this coupon']);
    exit;
}

// Calculate discount
$discount = 0;
if ($coupon['type'] === 'percentage') {
    $discount = $webinar_amount * (floatval($coupon['value']) / 100);
} elseif ($coupon['type'] === 'amount') {
    $discount = floatval($coupon['value']);
}

// Ensure discount does not exceed webinar amount
if ($discount > $webinar_amount) {
    $discount = $webinar_amount;
}

echo json_encode([
    'status' => 'success',
    'message' => 'Coupon applied successfully',
    'discount' => $discount,
    'final_amount' => $webinar_amount - $discount,
]);
?>
