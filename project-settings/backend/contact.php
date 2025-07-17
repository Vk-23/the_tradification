<?php
header('Content-Type: application/json');
require_once 'db_connection.php';

$action = $_GET['action'] ?? '';

try {
    if ($action === 'list') {
        $stmt = $conn->query("SELECT id, name, email, phone, subject, message FROM contacts ORDER BY id DESC");
        $contacts = $stmt->fetchAll();
        echo json_encode(['status' => 'success', 'contacts' => $contacts]);
        exit;
    }

    $input = json_decode(file_get_contents('php://input'), true);

    if ($action === 'add') {
        $name = trim($input['name'] ?? '');
        $email = trim($input['email'] ?? '');
        $phone = trim($input['phone'] ?? '');
        $subject = trim($input['subject'] ?? '');
        $message = trim($input['message'] ?? '');

        if ($name === '' || $email === '' || $phone === '' || $subject === '' || $message === '') {
            throw new Exception('All fields are required');
        }

        $stmt = $conn->prepare("INSERT INTO contacts (name, email, phone, subject, message) VALUES (:name, :email, :phone, :subject, :message)");
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':subject' => $subject,
            ':message' => $message,
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Contact added']);
        exit;
    }

    if ($action === 'update') {
        $id = intval($input['id'] ?? 0);
        $name = trim($input['name'] ?? '');
        $email = trim($input['email'] ?? '');
        $phone = trim($input['phone'] ?? '');
        $subject = trim($input['subject'] ?? '');
        $message = trim($input['message'] ?? '');

        if ($id <= 0 || $name === '' || $email === '' || $phone === '' || $subject === '' || $message === '') {
            throw new Exception('Invalid input');
        }

        $stmt = $conn->prepare("UPDATE contacts SET name = :name, email = :email, phone = :phone, subject = :subject, message = :message WHERE id = :id");
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':subject' => $subject,
            ':message' => $message,
            ':id' => $id,
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Contact updated']);
        exit;
    }

    if ($action === 'delete') {
        $id = intval($input['id'] ?? 0);
        if ($id <= 0) {
            throw new Exception('Invalid contact ID');
        }
        $stmt = $conn->prepare("DELETE FROM contacts WHERE id = :id");
        $stmt->execute([':id' => $id]);
        echo json_encode(['status' => 'success', 'message' => 'Contact deleted']);
        exit;
    }

    throw new Exception('Invalid action');

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit;
}
