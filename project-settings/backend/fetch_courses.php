<?php
header('Content-Type: application/json');
include 'db_connection.php';


$sql = "SELECT c.*, cc.name as category_name 
        FROM courses c 
        LEFT JOIN course_categories cc ON c.category_id = cc.id 
        WHERE c.banner_active = 1 
        ORDER BY c.created_at DESC";

$result = $conn->query($sql);

$courses = [];
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

echo json_encode($courses);
$conn->close();
?>
