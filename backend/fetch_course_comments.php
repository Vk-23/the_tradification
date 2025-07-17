<?php
include 'db_connection.php';

header('Content-Type: application/json');

$course_id = $_GET['course_id'] ?? null;

if (!$course_id) {
    echo json_encode(['error' => 'Course ID is required']);
    exit;
}

$sql = "SELECT * FROM course_comments WHERE course_id = ? AND status = 'approved' ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['error' => $conn->error]);
    exit;
}

$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();

$comments = [];
while ($row = $result->fetch_assoc()) {
    $comments[] = $row;
}

echo json_encode($comments);

$stmt->close();
$conn->close();
?>
