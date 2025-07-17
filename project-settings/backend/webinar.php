<?php
header('Content-Type: application/json');
require_once 'db_connection.php';

$action = $_GET['action'] ?? '';

try {
    if ($action === 'list') {
        // List all webinars with category name including banner_active and active
        $stmt = $conn->query("SELECT w.*, c.name AS category_name FROM webinars w LEFT JOIN webinar_categories c ON w.category_id = c.id ORDER BY w.id DESC");
        $webinars = $stmt->fetchAll();
        echo json_encode(['status' => 'success', 'webinars' => $webinars]);
        exit;
    }

    // For add, update, delete actions, handle multipart/form-data or JSON
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
        $webinar_date = $input['webinar_date'] ?? null;
        $webinar_time = $input['webinar_time'] ?? null;
        $hours = intval($input['hours'] ?? 0);
        $price = floatval($input['price'] ?? 0);
        $webinar_link = trim($input['webinar_link'] ?? null);

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

        $stmt = $conn->prepare("INSERT INTO webinars (category_id, name, thumbnail, short_description, long_description, webinar_date, webinar_time, hours, price, webinar_link) VALUES (:category_id, :name, :thumbnail, :short_description, :long_description, :webinar_date, :webinar_time, :hours, :price, :webinar_link)");
        $stmt->execute([
            ':category_id' => $category_id,
            ':name' => $name,
            ':thumbnail' => $thumbnail,
            ':short_description' => $short_description,
            ':long_description' => $long_description,
            ':webinar_date' => $webinar_date,
            ':webinar_time' => $webinar_time,
            ':hours' => $hours,
            ':price' => $price,
            ':webinar_link' => $webinar_link,
        ]);
        echo json_encode(['status' => 'success', 'message' => 'Webinar added']);
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
        $webinar_date = $input['webinar_date'] ?? null;
        $webinar_time = $input['webinar_time'] ?? null;
        $hours = intval($input['hours'] ?? 0);
        $price = floatval($input['price'] ?? 0);
        $webinar_link = trim($input['webinar_link'] ?? null);
        $banner_active = isset($input['banner_active']) ? intval($input['banner_active']) : 0;

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
            $stmt = $conn->prepare("SELECT thumbnail FROM webinars WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $existing = $stmt->fetch();
            $thumbnail = $existing['thumbnail'] ?? null;
        }

        $stmt = $conn->prepare("UPDATE webinars SET category_id = :category_id, name = :name, thumbnail = :thumbnail, short_description = :short_description, long_description = :long_description, webinar_date = :webinar_date, webinar_time = :webinar_time, hours = :hours, price = :price, webinar_link = :webinar_link, banner_active = :banner_active WHERE id = :id");
        $stmt->execute([
            ':category_id' => $category_id,
            ':name' => $name,
            ':thumbnail' => $thumbnail,
            ':short_description' => $short_description,
            ':long_description' => $long_description,
            ':webinar_date' => $webinar_date,
            ':webinar_time' => $webinar_time,
            ':hours' => $hours,
            ':price' => $price,
            ':webinar_link' => $webinar_link,
            ':banner_active' => $banner_active,
            ':id' => $id,
        ]);
        echo json_encode(['status' => 'success', 'message' => 'Webinar updated']);
        exit;
    }

    if ($action === 'delete') {
        // For delete, input can be JSON or form-data
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
            $id = intval($_POST['id'] ?? 0);
        } else {
            $id = intval($input['id'] ?? 0);
        }
        if ($id <= 0) {
            throw new Exception('Invalid webinar ID');
        }
        $stmt = $conn->prepare("DELETE FROM webinars WHERE id = :id");
        $stmt->execute([':id' => $id]);
        echo json_encode(['status' => 'success', 'message' => 'Webinar deleted']);
        exit;
    }

    if ($action === 'update_status') {
        $id = intval($input['id'] ?? 0);
        if ($id <= 0) {
            throw new Exception('Invalid webinar ID');
        }
        $banner_active = isset($input['banner_active']) ? intval($input['banner_active']) : null;

        $fields = [];
        $params = [':id' => $id];

        if ($banner_active !== null) {
            $fields[] = 'banner_active = :banner_active';
            $params[':banner_active'] = $banner_active;
        }

        if (empty($fields)) {
            throw new Exception('No status fields to update');
        }

        $sql = "UPDATE webinars SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);

        echo json_encode(['status' => 'success', 'message' => 'Status updated']);
        exit;
    }

    throw new Exception('Invalid action');


} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit;
}
?>
