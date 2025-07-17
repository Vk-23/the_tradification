<?php
include 'db_connection.php';

header('Content-Type: application/json');

$sql  = "SELECT id, name FROM webinar_categories ORDER BY name ASC";
$result = $conn->query($sql);

echo json_encode($result->fetch_all(MYSQLI_ASSOC));
