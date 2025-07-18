<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include 'db_connection.php'; // Your DB connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['form_name'] ?? '';
    $email = $_POST['form_email'] ?? '';
    $phone = $_POST['form_phone'] ?? '';
    $subject = $_POST['form_subject'] ?? '';
    $message = $_POST['form_message'] ?? '';

    // ✅ Basic validation
    if ($name && $email && $phone && $subject && $message) {
        // You can also insert into a DB here
        // Or send an email using mail()

        echo json_encode([
            'status' => 'success',
            'message' => 'Thank you! Your message has been submitted successfully.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Please fill all fields.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request.'
    ]);
}
?>
