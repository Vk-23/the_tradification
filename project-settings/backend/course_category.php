<?php
header('Content-Type: application/json');
require_once 'db_connection.php'; // Make sure this file connects to your DB correctly

$action = $_GET['action'] ?? '';

try {
    if ($action === 'list') {
        $stmt = $conn->query("SELECT id, name FROM course_categories ORDER BY id DESC");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['status' => 'success', 'categories' => $categories]);
        exit;
    }

    $input = json_decode(file_get_contents('php://input'), true);

    if ($action === 'add') {
        $name = trim($input['name'] ?? '');
        if ($name === '') {
            throw new Exception('Category name is required');
        }
        $stmt = $conn->prepare("INSERT INTO course_categories (name) VALUES (:name)");
        $stmt->execute([':name' => $name]);
        echo json_encode(['status' => 'success', 'message' => 'Course category added']);
        exit;
    }

    if ($action === 'update') {
        $id = intval($input['id'] ?? 0);
        $name = trim($input['name'] ?? '');
        if ($id <= 0 || $name === '') {
            throw new Exception('Invalid category ID or name');
        }
        $stmt = $conn->prepare("UPDATE course_categories SET name = :name WHERE id = :id");
        $stmt->execute([':name' => $name, ':id' => $id]);
        echo json_encode(['status' => 'success', 'message' => 'Course category updated']);
        exit;
    }

    if ($action === 'delete') {
        $id = intval($input['id'] ?? 0);
        if ($id <= 0) {
            throw new Exception('Invalid category ID');
        }
        $stmt = $conn->prepare("DELETE FROM course_categories WHERE id = :id");
        $stmt->execute([':id' => $id]);
        echo json_encode(['status' => 'success', 'message' => 'Course category deleted']);
        exit;
    }

    throw new Exception('Invalid action');

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit;
}
