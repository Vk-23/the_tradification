<?php
include 'db_connection.php'; // Ensure $conn is defined here

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

$name    = $_POST['form_name'] ?? null;
$email   = $_POST['form_email'] ?? null;
$phone   = $_POST['form_phone'] ?? null;
$subject = $_POST['form_subject'] ?? null;
$message = $_POST['form_message'] ?? null;

// Basic validation
if (!$name || !$email || !$subject || !$message) {
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

// Insert into DB
$sql = "INSERT INTO contacts (name, email, phone, subject, message, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['error' => 'Prepare failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param("sssss", $name, $email, $phone, $subject, $message);
$success = $stmt->execute();

if ($success) {
    echo json_encode(['success' => 'Contact form submitted successfully']);
} else {
    echo json_encode(['error' => 'Failed to submit contact form']);
}

$stmt->close();
$conn->close();
?>
