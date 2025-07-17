<?php
include 'db_connection.php';

header('Content-Type: application/json');

$webinar_id = $_GET['webinar_id'] ?? null;

if (!$webinar_id) {
    echo json_encode(['error' => 'Missing webinar id']);
    exit;
}

$sql = "SELECT id, name, email, comment, profile_picture, created_at FROM webinar_comments WHERE webinar_id = ? ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['error' => $conn->error]);
    exit;
}

$stmt->bind_param("i", $webinar_id);
$stmt->execute();
$result = $stmt->get_result();

$comments = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($comments);
?>
