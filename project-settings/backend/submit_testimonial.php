<?php
// submit_testimonial.php
header('Content-Type: application/json');
include 'db_connection.php';   // must return a PDO object:  $pdo

try {
    // --------------------------------------------------
    // 1. Validate input
    // --------------------------------------------------
    $name    = trim($_POST['name']    ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || $message === '') {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Name and message are required']);
        exit;
    }

    // --------------------------------------------------
    // 2. Handle image upload (optional)
    // --------------------------------------------------
    $image_url = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $tmpPath  = $_FILES['image']['tmp_name'];
        $origName = basename($_FILES['image']['name']);
        $ext      = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
        $allowed  = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($ext, $allowed)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid image type']);
            exit;
        }

        $newName  = uniqid('testimonial_', true) . '.' . $ext;
        $destPath = $uploadDir . $newName;

        if (!move_uploaded_file($tmpPath, $destPath)) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to move uploaded file']);
            exit;
        }

        // path relative to your public folder
        $image_url = 'backend/uploads/' . $newName;
    }

    // --------------------------------------------------
    // 3. Insert into DB (conn)
    // --------------------------------------------------
    $sql = "INSERT INTO testimonials (name, message, image_url, status, created_at)
            VALUES (:name, :message, :image_url, 'pending', NOW())";

    $stmt = $conn->prepare($sql);   // $pdo comes from db_connection.php

    $stmt->execute([
        ':name'      => $name,
        ':message'   => $message,
        ':image_url' => $image_url
    ]);

    echo json_encode(['success' => true, 'message' => 'Testimonial submitted successfully']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'DB error: ' . $e->getMessage()]);
}
