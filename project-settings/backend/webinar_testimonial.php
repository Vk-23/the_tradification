<?php
header('Content-Type: application/json');
require_once 'db_connection.php';

$action = $_GET['action'] ?? '';

try {
    if ($action === 'list') {
        $webinar_id = intval($_GET['webinar_id'] ?? 0);
        if ($webinar_id <= 0) {
            throw new Exception('Invalid webinar ID');
        }
        $stmt = $conn->prepare("SELECT id, name, image FROM webinar_testimonials WHERE webinar_id = :webinar_id ORDER BY id DESC");
        $stmt->execute([':webinar_id' => $webinar_id]);
        $testimonials = $stmt->fetchAll();
        echo json_encode(['status' => 'success', 'testimonials' => $testimonials]);
        exit;
    }

    $input = json_decode(file_get_contents('php://input'), true);

    if ($action === 'add') {
        $webinar_id = intval($input['webinar_id'] ?? 0);
        $name = trim($input['name'] ?? '');
        $message = trim($input['message'] ?? '');
        $rating = intval($input['rating'] ?? 0);
        // Handle image upload
        $image = '';
        if (!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $tmpName = $_FILES['image']['tmp_name'];
            $fileName = basename($_FILES['image']['name']);
            $targetFile = $uploadDir . $fileName;
            if (move_uploaded_file($tmpName, $targetFile)) {
                $image = $fileName;
            } else {
                throw new Exception('Failed to upload image');
            }
        }

        if ($webinar_id <= 0 || $name === '') {
            throw new Exception('Invalid input');
        }

        $stmt = $conn->prepare("INSERT INTO webinar_testimonials (webinar_id, name, image, message, rating) VALUES (:webinar_id, :name, :image, :message, :rating)");
        $stmt->execute([
            ':webinar_id' => $webinar_id,
            ':name' => $name,
            ':image' => $image,
            ':message' => $message,
            ':rating' => $rating,
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Testimonial added']);
        exit;
    }

    if ($action === 'update') {
        $id = intval($input['id'] ?? 0);
        $webinar_id = intval($input['webinar_id'] ?? 0);
        $name = trim($input['name'] ?? '');
        // Handle image upload
        $image = '';
        if (!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $tmpName = $_FILES['image']['tmp_name'];
            $fileName = basename($_FILES['image']['name']);
            $targetFile = $uploadDir . $fileName;
            if (move_uploaded_file($tmpName, $targetFile)) {
                $image = $fileName;
            } else {
                throw new Exception('Failed to upload image');
            }
        } else {
            // If no new image uploaded, keep existing image
            $stmt = $conn->prepare("SELECT image FROM webinar_testimonials WHERE id = :id AND webinar_id = :webinar_id");
            $stmt->execute([':id' => $id, ':webinar_id' => $webinar_id]);
            $row = $stmt->fetch();
            $image = $row ? $row['image'] : '';
        }

        if ($id <= 0 || $webinar_id <= 0 || $name === '') {
            throw new Exception('Invalid input');
        }

        $stmt = $conn->prepare("UPDATE webinar_testimonials SET name = :name, image = :image, message = :message, rating = :rating WHERE id = :id AND webinar_id = :webinar_id");
        $stmt->execute([
            ':name' => $name,
            ':image' => $image,
            ':message' => $input['message'] ?? '',
            ':rating' => intval($input['rating'] ?? 0),
            ':id' => $id,
            ':webinar_id' => $webinar_id,
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Testimonial updated']);
        exit;
    }

    if ($action === 'delete') {
        $id = intval($input['id'] ?? 0);
        $webinar_id = intval($input['webinar_id'] ?? 0);
        if ($id <= 0 || $webinar_id <= 0) {
            throw new Exception('Invalid input');
        }
        $stmt = $conn->prepare("DELETE FROM webinar_testimonials WHERE id = :id AND webinar_id = :webinar_id");
        $stmt->execute([':id' => $id, ':webinar_id' => $webinar_id]);
        echo json_encode(['status' => 'success', 'message' => 'Testimonial deleted']);
        exit;
    }

    throw new Exception('Invalid action');

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit;
}
