<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

require_once 'db_connection.php';

$user_id = $_SESSION['user_id'];

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$address = $_POST['address'] ?? '';
$gender = $_POST['gender'] ?? '';
// Normalize gender to capitalize first letter to match DB enum
if ($gender) {
    $gender = ucfirst(strtolower($gender));
}
$dob = $_POST['dob'] ?? '';
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';

// Validate required fields
if (empty($name) || empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Name and email are required']);
    exit;
}

// Check if email is already used by another user
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
$stmt->bind_param("si", $email, $user_id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Email is already in use by another account']);
    $stmt->close();
    $conn->close();
    exit;
}
$stmt->close();

// Handle profile picture upload if exists
$profile_picture_path = null;
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = __DIR__ . '/uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    $tmp_name = $_FILES['profile_picture']['tmp_name'];
    $original_name = basename($_FILES['profile_picture']['name']);
    $ext = pathinfo($original_name, PATHINFO_EXTENSION);
    $new_name = uniqid('profile_', true) . '.' . $ext;
    $destination = $upload_dir . $new_name;
    if (move_uploaded_file($tmp_name, $destination)) {
        $profile_picture_path = 'uploads/' . $new_name;
    }
}

// If password change requested, verify current password
if (!empty($current_password) || !empty($new_password)) {
    if (empty($current_password) || empty($new_password)) {
        echo json_encode(['success' => false, 'message' => 'Both current and new password are required to change password']);
        $conn->close();
        exit;
    }

    // Get current password hash from DB
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($password_hash);
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'User not found']);
        $stmt->close();
        $conn->close();
        exit;
    }
    $stmt->close();

    // Verify current password
    if (!password_verify($current_password, $password_hash)) {
        echo json_encode(['success' => false, 'message' => 'Current password is incorrect']);
        $conn->close();
        exit;
    }

    // Validate new password length
    if (strlen($new_password) < 8) {
        echo json_encode(['success' => false, 'message' => 'New password must be at least 8 characters long']);
        $conn->close();
        exit;
    }

    // Hash new password
    $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

    if ($profile_picture_path) {
$stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ?, address = ?, gender = ?, dob = ?, password = ?, profile_picture = ? WHERE id = ?");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    $conn->close();
    exit;
}
$stmt->bind_param("sssssssssi", $name, $email, $phone, $address, $gender, $dob, $new_password_hash, $profile_picture_path, $user_id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ?, address = ?, gender = ?, dob = ?, password = ? WHERE id = ?");
        $stmt->bind_param("sssssssi", $name, $email, $phone, $address, $gender, $dob, $new_password_hash, $user_id);
    }
} else {
    if ($profile_picture_path) {
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ?, address = ?, gender = ?, dob = ?, profile_picture = ? WHERE id = ?");
        $stmt->bind_param("sssssssi", $name, $email, $phone, $address, $gender, $dob, $profile_picture_path, $user_id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ?, address = ?, gender = ?, dob = ? WHERE id = ?");
        $stmt->bind_param("ssssssi", $name, $email, $phone, $address, $gender, $dob, $user_id);
    }
}

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update profile']);
}

$stmt->close();
$conn->close();
?>
