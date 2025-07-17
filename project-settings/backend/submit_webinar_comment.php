<?php
include 'db_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

$webinar_id = $_POST['webinar_id'] ?? null;
$name = $_POST['form_name'] ?? null;
$email = $_POST['form_email'] ?? null;
$comment = $_POST['form_message'] ?? null;

// Basic validation
if (!$webinar_id || !$name || !$email || !$comment) {
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

// Optional: handle profile picture upload
$profile_picture_path = null;
if (isset($_FILES['form_profile_picture']) && $_FILES['form_profile_picture']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = __DIR__ . '/uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    $tmp_name = $_FILES['form_profile_picture']['tmp_name'];
    $original_name = basename($_FILES['form_profile_picture']['name']);
    $ext = pathinfo($original_name, PATHINFO_EXTENSION);
    $new_name = uniqid('profile_', true) . '.' . $ext;
    $destination = $upload_dir . $new_name;

    if (move_uploaded_file($tmp_name, $destination)) {
        $profile_picture_path = 'uploads/' . $new_name;
    }
}

// Insert comment into database with status 'pending' for admin verification
$sql = "INSERT INTO webinar_comments (webinar_id, name, email, comment, profile_picture, status, created_at) VALUES (?, ?, ?, ?, ?, 'pending', NOW())";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['error' => $conn->error]);
    exit;
}

$stmt->bind_param("issss", $webinar_id, $name, $email, $comment, $profile_picture_path);
$success = $stmt->execute();

if ($success) {
    echo json_encode(['success' => 'Comment submitted and pending approval']);
} else {
    echo json_encode(['error' => 'Failed to submit comment']);
}
?>
