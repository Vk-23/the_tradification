<?php
include 'db_connection.php';

header('Content-Type: application/json');

// ── 1. Which category? ────────────────────────────
$category = $_GET['category'] ?? 'all';

/* ── 2. Build query ───────────────────────────────
   Always JOIN the category table so we can show its name too  */
$sql = "SELECT w.*, c.name AS category_name
        FROM webinars w
        JOIN webinar_categories c ON w.category_id = c.id";

if ($category !== 'all') {
    $sql .= " WHERE w.category_id = ?";
}

$stmt = $conn->prepare($sql);
if (!$stmt) { echo json_encode(['error' => $conn->error]); exit; }

if ($category !== 'all') {
    $stmt->bind_param("i", $category);
}

$stmt->execute();
$result = $stmt->get_result();

echo json_encode($result->fetch_all(MYSQLI_ASSOC));
