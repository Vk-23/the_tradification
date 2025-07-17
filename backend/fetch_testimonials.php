<?php
// fetch_testimonials.php
// Fetches testimonials with status 'approved' from database

header('Content-Type: application/json');
include 'db_connection.php';


$sql = "SELECT name, message, image_url FROM testimonials WHERE status = 'approved' ORDER BY created_at DESC";
$result = $conn->query($sql);

$testimonials = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $testimonials[] = $row;
    }
}

echo json_encode(['success' => true, 'testimonials' => $testimonials]);

$conn->close();
?>
