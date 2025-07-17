<?php
header('Content-Type: application/json');
include 'db_connection.php';

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'Course ID is required']);
    exit;
}

$id = intval($_GET['id']);

$sql = "SELECT c.*, cc.name as category_name 
        FROM courses c 
        LEFT JOIN course_categories cc ON c.category_id = cc.id 
        WHERE c.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['error' => 'Course not found']);
    exit;
}

$course = $result->fetch_assoc();

echo json_encode($course);

$stmt->close();
$conn->close();
?>
