<?php
session_start();
header('Content-Type: application/json');
include 'db_connection.php';
include 'razorpay_config.php';

require_once 'vendor/autoload.php'; // Assuming Razorpay PHP SDK is installed via composer

use Razorpay\Api\Api;

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

// Get amount from POST (in rupees)
$amount = isset($_POST['amount']) ? (int)$_POST['amount'] : 0;

if ($amount <= 0) {
    echo json_encode(['error' => 'Invalid amount']);
    exit;
}

$api = new Api(RAZORPAY_KEY_ID, RAZORPAY_KEY_SECRET);

$orderData = [
    'receipt'         => 'order_rcptid_' . time(),
    'amount'          => $amount * 100, // amount in paise
    'currency'        => 'INR',
    'payment_capture' => 1 // auto capture
];

try {
    $razorpayOrder = $api->order->create($orderData);
    echo json_encode([
        'order_id' => $razorpayOrder['id'],
        'amount' => $amount,
        'currency' => 'INR',
        'key' => RAZORPAY_KEY_ID
    ]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
