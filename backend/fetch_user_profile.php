<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

require_once 'db_connection.php';

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT name, email, phone, address, gender, dob, profile_picture FROM users WHERE id = ?");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database error: prepare failed']);
    exit;
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email, $phone, $address, $gender, $dob, $profile_picture);

if ($stmt->fetch()) {
    // Normalize gender to lowercase for frontend consistency
    $gender = strtolower($gender);
    // Adjust profile_picture path to be accessible from frontend
    $profile_picture_url = null;
    if ($profile_picture) {
        // Assuming backend folder is at /backend/, adjust path accordingly
        $profile_picture_url = 'backend/' . $profile_picture;
    }
    echo json_encode([
        'success' => true,
        'data' => [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'gender' => $gender,
            'dob' => $dob,
            'profile_picture' => $profile_picture_url
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'User not found']);
}

$stmt->close();
$conn->close();
?>
