<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connection.php';

header('Content-Type: application/json');

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(['error' => 'Missing webinar id']);
    exit;
}

$sql = "SELECT w.*, c.name AS category_name
        FROM webinars w
        JOIN webinar_categories c ON w.category_id = c.id
        WHERE w.id = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['error' => $conn->error]);
    exit;
}

$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

$webinar = $result->fetch_assoc();

if (!$webinar) {
    echo json_encode(['error' => 'Webinar not found']);
    exit;
}

echo json_encode($webinar);
?>
