<?php
header('Content-Type: application/json');
include 'db_connection.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = ($method === 'POST')
          ? ($_POST['action'] ?? '')
          : ($_GET['action']  ?? '');
$action = $_POST['action'] ?? '';

switch ($action) {
    case 'list':
        // Fetch all testimonials
        $sql = "SELECT id, name, message, image_url, status, created_at, updated_at FROM testimonials ORDER BY created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $testimonials = [];
        if ($result) {
            foreach ($result as $row) {
                $testimonials[] = $row;
            }
        }
        echo json_encode(['success' => true, 'testimonials' => $testimonials]);
        break;

    case 'update':
        // Update testimonial by id
        $id = $_POST['id'] ?? null;
        $name = $_POST['name'] ?? null;
        $message = $_POST['message'] ?? null;
        $status = $_POST['status'] ?? null;

        if (!$id || !$name || !$message || !$status) {
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            exit;
        }

        $stmt = $conn->prepare("UPDATE testimonials SET name = ?, message = ?, status = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bindValue(1, $name, PDO::PARAM_STR);
        $stmt->bindValue(2, $message, PDO::PARAM_STR);
        $stmt->bindValue(3, $status, PDO::PARAM_STR);
        $stmt->bindValue(4, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Testimonial updated']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update testimonial']);
        }
        $stmt->closeCursor();
        break;

    case 'delete':
        // Delete testimonial by id
        $id = $_POST['id'] ?? null;
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Missing testimonial id']);
            exit;
        }
        $stmt = $conn->prepare("DELETE FROM testimonials WHERE id = ?");
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Testimonial deleted']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete testimonial']);
        }
        $stmt->closeCursor();
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}
?>
