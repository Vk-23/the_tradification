<?php
session_start();
header('Content-Type: application/json');

require_once 'vendor/autoload.php'; // Composer autoload for PHPMailer and Razorpay SDK
include 'db_connection.php';
include 'razorpay_config.php';

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$success = false;
$error = "Payment failed";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $api = new Api(RAZORPAY_KEY_ID, RAZORPAY_KEY_SECRET);

    $razorpay_payment_id = $_POST['razorpay_payment_id'] ?? '';
    $razorpay_order_id = $_POST['razorpay_order_id'] ?? '';
    $razorpay_signature = $_POST['razorpay_signature'] ?? '';
    $webinar_id = $_POST['webinar_id'] ?? null;

    $attributes = [
        'razorpay_order_id' => $razorpay_order_id,
        'razorpay_payment_id' => $razorpay_payment_id,
        'razorpay_signature' => $razorpay_signature
    ];

    try {
        $api->utility->verifyPaymentSignature($attributes);
        $success = true;

        // Save payment details to database
        $user_id = $_SESSION['user_id'] ?? null;
        if ($user_id) {
            if ($webinar_id === null) {
                $webinar_id = 0; // or handle as appropriate for your DB schema
            }
            $stmt = $conn->prepare("INSERT INTO payments (user_id, webinar_id, razorpay_payment_id, razorpay_order_id, payment_status, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $status = 'success';
            $stmt->bind_param("iisss", $user_id, $webinar_id, $razorpay_payment_id, $razorpay_order_id, $status);
            $stmt->execute();
            $stmt->close();
        }

        // Fetch webinar details for email content
        $webinar_name = '';
        $webinar_image = '';
        $webinar_date = '';
        $webinar_time = '';
        $webinar_link = '';
        $webinar_short_desc = '';

        if ($webinar_id) {
            $stmt = $conn->prepare("SELECT name, thumbnail, webinar_date, webinar_time, webinar_link, short_description FROM webinars WHERE id = ?");
            $stmt->bind_param("i", $webinar_id);
            $stmt->execute();
            $stmt->bind_result($webinar_name, $webinar_image, $webinar_date, $webinar_time, $webinar_link, $webinar_short_desc);
            $stmt->fetch();
            $stmt->close();
        }

        // Send emails on successful payment
        $user_email = $_SESSION['user_email'] ?? '';
        $user_name = $_SESSION['user_name'] ?? '';
        $user_phone = $_SESSION['user_phone'] ?? '';
        $admin_email = 'darwairavina2002@gmail.com'; // Replace with actual admin email

        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'darwairavina2002@gmail.com'; // Your Gmail
            $mail->Password = 'jvff pltk phxz yxwf'; // Gmail App Password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            //Recipients
            $mail->setFrom('no-reply@edulerns.com', 'Edulerns');
            if ($user_email) {
                $mail->addAddress($user_email, $user_name);
            }
            $mail->addAddress($admin_email);

            // Content for user
            if ($user_email) {
                $user_body = "Dear $user_name,\n\nThank you for your payment. Your payment ID is $razorpay_payment_id.\n\nWebinar Details:\n";
                $user_body .= "Name: $webinar_name\nDate: $webinar_date\nTime: $webinar_time\nLink: $webinar_link\nDescription: $webinar_short_desc\n\nRegards,\nEdulerns Team";

                $mail->Subject = "Payment Confirmation - Edulerns";
                $mail->Body    = $user_body;
                $mail->send();
                $mail->clearAddresses();
                $mail->addAddress($admin_email);
            }

            // Content for admin
            $admin_body = "Payment received from $user_name ($user_email, $user_phone).\n";
            $admin_body .= "Webinar Selected: $webinar_name\nPayment ID: $razorpay_payment_id\nOrder ID: $razorpay_order_id";

            $mail->Subject = "New Payment Received - Edulerns";
            $mail->Body    = $admin_body;
            $mail->send();

        } catch (Exception $e) {
            // Log email sending error but do not fail payment verification
            error_log("Mailer Error: " . $mail->ErrorInfo);
        }

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
