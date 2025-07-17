<?php
session_start();
header('Content-Type: application/json');
include 'db_connection.php';

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
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Validate inputs
if (empty($email) || empty($password)) {
    send_response(false, 'Email and password are required.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    send_response(false, 'Invalid email format.');
}

// Check if email exists
$stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    $stmt->close();
    send_response(false, 'Email not registered.');
}

$stmt->bind_result($user_id, $user_name, $password_hash);
$stmt->fetch();

// Verify password
if (!password_verify($password, $password_hash)) {
    $stmt->close();
    send_response(false, 'Incorrect password.');
}

$stmt->close();

// Set session variables
$_SESSION['user_id'] = $user_id;
$_SESSION['user_name'] = $user_name;

// You can add more session variables as needed

send_response(true, 'Login successful.');
?>
