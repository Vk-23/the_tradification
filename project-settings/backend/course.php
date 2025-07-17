<?php
header('Content-Type: application/json');
require_once 'db_connection.php';

$action = $_GET['action'] ?? '';

try {
    if ($action === 'list') {
        // List all courses with category name
        $stmt = $conn->query("SELECT c.*, cat.name AS category_name FROM courses c LEFT JOIN course_categories cat ON c.category_id = cat.id ORDER BY c.id DESC");
        $courses = $stmt->fetchAll();
        echo json_encode(['status' => 'success', 'courses' => $courses]);
        exit;
    }

    // For add, update, delete, update_status actions, handle multipart/form-data or JSON
    if (strpos($_SERVER['CONTENT_TYPE'] ?? '', 'multipart/form-data') !== false) {
        // Handle multipart/form-data
        $input = $_POST;
    } else {
        // Handle JSON input
        $input = json_decode(file_get_contents('php://input'), true);
    }

    if ($action === 'add') {
        $category_id = intval($input['category_id'] ?? 0);
        $name = trim($input['name'] ?? '');
        if ($category_id <= 0 || $name === '') {
            throw new Exception('Category and name are required');
        }

        $short_description = trim($input['short_description'] ?? null);
        $long_description = trim($input['long_description'] ?? null);
        $lectures = intval($input['lectures'] ?? 0);
        $level = trim($input['level'] ?? null);
        $video_link = trim($input['video_link'] ?? null);

        // Handle thumbnail upload
        $thumbnail = null;
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $tmpName = $_FILES['thumbnail']['tmp_name'];
            $fileName = basename($_FILES['thumbnail']['name']);
            $targetFile = $uploadDir . time() . '_' . $fileName;

            if (move_uploaded_file($tmpName, $targetFile)) {
                $thumbnail = basename($targetFile);
            } else {
                throw new Exception('Failed to upload thumbnail');
            }
        }

        $stmt = $conn->prepare("INSERT INTO courses (category_id, name, thumbnail, short_description, long_description, lectures, level, video_link) VALUES (:category_id, :name, :thumbnail, :short_description, :long_description, :lectures, :level, :video_link)");
        $stmt->execute([
            ':category_id' => $category_id,
            ':name' => $name,
            ':thumbnail' => $thumbnail,
            ':short_description' => $short_description,
            ':long_description' => $long_description,
            ':lectures' => $lectures,
            ':level' => $level,
            ':video_link' => $video_link,
        ]);
        echo json_encode(['status' => 'success', 'message' => 'Course added']);
        exit;
    }

    if ($action === 'update') {
        $id = intval($input['id'] ?? 0);
        $category_id = intval($input['category_id'] ?? 0);
        $name = trim($input['name'] ?? '');
        if ($id <= 0 || $category_id <= 0 || $name === '') {
            throw new Exception('Invalid ID, category or name');
        }

        $short_description = trim($input['short_description'] ?? null);
        $long_description = trim($input['long_description'] ?? null);
        $lectures = intval($input['lectures'] ?? 0);
        $level = trim($input['level'] ?? null);

        // Handle thumbnail upload
        $thumbnail = null;
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $tmpName = $_FILES['thumbnail']['tmp_name'];
            $fileName = basename($_FILES['thumbnail']['name']);
            $targetFile = $uploadDir . time() . '_' . $fileName;

            if (move_uploaded_file($tmpName, $targetFile)) {
                $thumbnail = basename($targetFile);
            } else {
                throw new Exception('Failed to upload thumbnail');
            }
        }

        // If no new thumbnail uploaded, keep existing thumbnail
        if ($thumbnail === null) {
            $stmt = $conn->prepare("SELECT thumbnail FROM courses WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $existing = $stmt->fetch();
            $thumbnail = $existing['thumbnail'] ?? null;
        }

        $stmt = $conn->prepare("UPDATE courses SET category_id = :category_id, name = :name, thumbnail = :thumbnail, short_description = :short_description, long_description = :long_description, lectures = :lectures, level = :level, video_link = :video_link WHERE id = :id");
        $stmt->execute([
            ':category_id' => $category_id,
            ':name' => $name,
            ':thumbnail' => $thumbnail,
            ':short_description' => $short_description,
            ':long_description' => $long_description,
            ':lectures' => $lectures,
            ':level' => $level,
            ':video_link' => trim($input['video_link'] ?? null),
            ':id' => $id,
        ]);
        echo json_encode(['status' => 'success', 'message' => 'Course updated']);
        exit;
    }

    if ($action === 'delete') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
            $id = intval($_POST['id'] ?? 0);
        } else {
            $id = intval($input['id'] ?? 0);
        }
        if ($id <= 0) {
            throw new Exception('Invalid course ID');
        }
        $stmt = $conn->prepare("DELETE FROM courses WHERE id = :id");
        $stmt->execute([':id' => $id]);
        echo json_encode(['status' => 'success', 'message' => 'Course deleted']);
        exit;
    }

    if ($action === 'update_status') {
        $id = intval($input['id'] ?? 0);
        if ($id <= 0) {
            throw new Exception('Invalid course ID');
        }
        $banner_active = isset($input['banner_active']) ? intval($input['banner_active']) : null;
        if ($banner_active === null) {
            throw new Exception('banner_active is required');
        }
        $stmt = $conn->prepare("UPDATE courses SET banner_active = :banner_active WHERE id = :id");
        $stmt->execute([':banner_active' => $banner_active, ':id' => $id]);
        echo json_encode(['status' => 'success', 'message' => 'Banner active status updated']);
        exit;
    }

    throw new Exception('Invalid action');

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit;
}
?>
