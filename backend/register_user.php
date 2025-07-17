<?php
header('Content-Type: application/json');
include 'db_connection.php'; // Ensure this creates $conn (mysqli connection)

// Helper function to send JSON response and exit
function send_response($success, $message) {
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_response(false, 'Invalid request method.');
}

// Get and sanitize POST inputs
$name = trim($_POST['registerName'] ?? '');
$email = trim($_POST['registerEmail'] ?? '');
$phone = trim($_POST['registerPhone'] ?? '');
$password = $_POST['registerPassword'] ?? '';
$address = trim($_POST['registerAddress'] ?? ''); // Optional, use '' if not provided

// Validate inputs
if (empty($name) || empty($email) || empty($phone) || empty($password)) {
    send_response(false, 'All fields are required.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    send_response(false, 'Invalid email format.');
}

// Check if email already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $stmt->close();
    send_response(false, 'Email is already registered.');
}
$stmt->close();

// Hash the password
$password_hash = password_hash($password, PASSWORD_DEFAULT);
$created_at = date('Y-m-d H:i:s');

// Insert user into database
$stmt = $conn->prepare("INSERT INTO users (name, email, phone, password, address, created_at) VALUES (?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    send_response(false, 'Database error: ' . $conn->error);
}
$stmt->bind_param("ssssss", $name, $email, $phone, $password_hash, $address, $created_at);

if ($stmt->execute()) {
    $stmt->close();
    send_response(true, 'Registration successful.');
} else {
    $stmt->close();
    send_response(false, 'Failed to register user.');
}
?>
