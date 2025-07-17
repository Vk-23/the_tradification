<?php
include 'db_connection.php'; // Your DB connection

header('Content-Type: application/json');

// Validate method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

// Collect input
$name    = $_POST['form_name'] ?? '';
$email   = $_POST['form_email'] ?? '';
$phone   = $_POST['form_phone'] ?? '';
$subject = $_POST['form_subject'] ?? '';
$message = $_POST['form_message'] ?? '';

// Basic validation
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    echo json_encode(['error' => 'Please fill in all required fields.']);
    exit;
}

// Insert into database
$sql = "INSERT INTO contacts (name, email, phone, subject, message, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['error' => 'Database error: ' . $conn->error]);
    exit;
}

$stmt->bind_param("sssss", $name, $email, $phone, $subject, $message);
if ($stmt->execute()) {
    echo json_encode(['success' => 'Contact form submitted successfully.']);
} else {
    echo json_encode(['error' => 'Failed to submit contact form.']);
}

$stmt->close();
$conn->close();
?>
