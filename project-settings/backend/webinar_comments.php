<?php
header('Content-Type: application/json');
require_once 'db_connection.php'; // PDO connection

$action = $_GET['action'] ?? '';

function respond($status, $message, $data = null) {
    $response = ['status' => $status, 'message' => $message];
    if ($data !== null) {
        $response['data'] = $data;
    }
    echo json_encode($response);
    exit;
}

switch ($action) {
    case 'list':
        $webinar_id = $_GET['webinar_id'] ?? '';
        if (!$webinar_id) {
            respond('error', 'webinar_id is required');
        }

        $stmt = $conn->prepare("SELECT id, webinar_id, name, email, comment, profile_picture, status, created_at, updated_at FROM webinar_comments WHERE webinar_id = :webinar_id AND status = 'approved' ORDER BY created_at DESC");
        $stmt->execute(['webinar_id' => $webinar_id]);
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        respond('success', 'Comments fetched', $comments);
        break;

    case 'create':
        $webinar_id = $_POST['webinar_id'] ?? '';
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $comment = $_POST['comment'] ?? '';
        if (!$webinar_id || !$name || !$email || !$comment) {
            respond('error', 'Missing required fields');
        }

        $profile_picture_path = null;
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = __DIR__ . '/uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $filename = uniqid('profile_') . '_' . basename($_FILES['profile_picture']['name']);
            $target_file = $upload_dir . $filename;
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
                $profile_picture_path = 'uploads/' . $filename;
            } else {
                respond('error', 'Failed to upload profile picture');
            }
        }

        $status = 'approved'; // Changed default status to approved for immediate visibility

        $stmt = $conn->prepare("INSERT INTO webinar_comments (webinar_id, name, email, comment, profile_picture, status, created_at, updated_at) VALUES (:webinar_id, :name, :email, :comment, :profile_picture, :status, NOW(), NOW())");
        $result = $stmt->execute([
            'webinar_id' => $webinar_id,
            'name' => $name,
            'email' => $email,
            'comment' => $comment,
            'profile_picture' => $profile_picture_path,
            'status' => $status
        ]);
        if ($result) {
            respond('success', 'Comment added');
        } else {
            respond('error', 'Failed to add comment');
        }
        break;

    case 'update':
        $id = $_POST['id'] ?? '';
        if (!$id) {
            respond('error', 'id is required');
        }

        $fields = [];
        $params = [];

        if (isset($_POST['name'])) {
            $fields[] = 'name = :name';
            $params['name'] = $_POST['name'];
        }
        if (isset($_POST['email'])) {
            $fields[] = 'email = :email';
            $params['email'] = $_POST['email'];
        }
        if (isset($_POST['comment'])) {
            $fields[] = 'comment = :comment';
            $params['comment'] = $_POST['comment'];
        }
        if (isset($_POST['status'])) {
            $fields[] = 'status = :status';
            $params['status'] = $_POST['status'];
        }

        if (empty($fields)) {
            respond('error', 'No fields to update');
        }

        $fields[] = 'updated_at = NOW()';

        $sql = "UPDATE webinar_comments SET " . implode(', ', $fields) . " WHERE id = :id";
        $params['id'] = $id;

        $stmt = $conn->prepare($sql);
        $result = $stmt->execute($params);
        if ($result) {
            respond('success', 'Comment updated');
        } else {
            respond('error', 'Failed to update comment');
        }
        break;

    case 'delete':
        $id = $_POST['id'] ?? '';
        if (!$id) {
            respond('error', 'id is required');
        }

        $stmt = $conn->prepare("DELETE FROM webinar_comments WHERE id = :id");
        $result = $stmt->execute(['id' => $id]);
        if ($result) {
            respond('success', 'Comment deleted');
        } else {
            respond('error', 'Failed to delete comment');
        }
        break;

    default:
        respond('error', 'Invalid action');
}
?>
