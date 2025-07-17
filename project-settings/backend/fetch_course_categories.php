<?php
header('Content-Type: application/json');
include 'db_connection.php'; // this provides $conn

$sql = "SELECT id, name FROM course_categories ORDER BY name ASC";
$result = $conn->query($sql);

$categories = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

echo json_encode($categories);

// optional: $conn->close();
?>
