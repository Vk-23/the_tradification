<?php
session_start();
header('Content-Type: application/json');
include 'razorpay_config.php';

require_once 'vendor/autoload.php'; // Assuming Razorpay PHP SDK is installed via composer

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

$success = false;
$error = "Payment failed";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $api = new Api(RAZORPAY_KEY_ID, RAZORPAY_KEY_SECRET);

    $razorpay_payment_id = $_POST['razorpay_payment_id'] ?? '';
    $razorpay_order_id = $_POST['razorpay_order_id'] ?? '';
    $razorpay_signature = $_POST['razorpay_signature'] ?? '';

    $attributes = [
        'razorpay_order_id' => $razorpay_order_id,
        'razorpay_payment_id' => $razorpay_payment_id,
        'razorpay_signature' => $razorpay_signature
    ];

    try {
        $api->utility->verifyPaymentSignature($attributes);
        $success = true;

        // Send emails on successful payment
        $user_email = $_SESSION['user_email'] ?? '';
        $user_name = $_SESSION['user_name'] ?? '';
        $admin_email = 'admin@example.com'; // Replace with actual admin email

        $subject_user = "Payment Confirmation - Edulerns";
        $message_user = "Dear $user_name,\n\nThank you for your payment. Your payment ID is $razorpay_payment_id.\n\nRegards,\nEdulerns Team";

        $subject_admin = "New Payment Received - Edulerns";
        $message_admin = "Payment received from $user_name ($user_email).\nPayment ID: $razorpay_payment_id\nOrder ID: $razorpay_order_id";

        $headers = "From: no-reply@edulerns.com\r\n";

        if ($user_email) {
            mail($user_email, $subject_user, $message_user, $headers);
        }
        mail($admin_email, $subject_admin, $message_admin, $headers);

    } catch (SignatureVerificationError $e) {
        $error = 'Razorpay Signature Verification Failed: ' . $e->getMessage();
    }
} else {
    $error = 'Invalid request method';
}

if ($success) {
    echo json_encode(['status' => 'success', 'message' => 'Payment verified successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => $error]);
}
?>
