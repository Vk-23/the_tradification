<?php
header('Content-Type: application/json');
require_once 'db_connection.php';

$action = $_GET['action'] ?? '';

try {
    if ($action === 'list') {
        $stmt = $conn->query("SELECT id, name FROM webinar_categories ORDER BY id DESC");
        $categories = $stmt->fetchAll();
        echo json_encode(['status' => 'success', 'categories' => $categories]);
        exit;
    }

    $input = json_decode(file_get_contents('php://input'), true);

    if ($action === 'add') {
        $name = trim($input['name'] ?? '');
        if ($name === '') {
            throw new Exception('Category name is required');
        }
        $stmt = $conn->prepare("INSERT INTO webinar_categories (name) VALUES (:name)");
        $stmt->execute([':name' => $name]);
        echo json_encode(['status' => 'success', 'message' => 'Category added']);
        exit;
    }

    if ($action === 'update') {
        $id = intval($input['id'] ?? 0);
        $name = trim($input['name'] ?? '');
        if ($id <= 0 || $name === '') {
            throw new Exception('Invalid category ID or name');
        }
        $stmt = $conn->prepare("UPDATE webinar_categories SET name = :name WHERE id = :id");
        $stmt->execute([':name' => $name, ':id' => $id]);
        echo json_encode(['status' => 'success', 'message' => 'Category updated']);
        exit;
    }

    if ($action === 'delete') {
        $id = intval($input['id'] ?? 0);
        if ($id <= 0) {
            throw new Exception('Invalid category ID');
        }
        $stmt = $conn->prepare("DELETE FROM webinar_categories WHERE id = :id");
        $stmt->execute([':id' => $id]);
        echo json_encode(['status' => 'success', 'message' => 'Category deleted']);
        exit;
    }

    throw new Exception('Invalid action');

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit;
}
