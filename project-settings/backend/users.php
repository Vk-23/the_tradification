<?php
header('Content-Type: application/json');
require_once 'db_connection.php';

$action = $_GET['action'] ?? '';

try {
    if ($action === 'list') {
        $stmt = $conn->query("SELECT id, name, email, phone, address FROM users ORDER BY id DESC");
        $users = $stmt->fetchAll();
        echo json_encode(['status' => 'success', 'users' => $users]);
        exit;
    }

    $input = json_decode(file_get_contents('php://input'), true);

    if ($action === 'add') {
        $name = trim($input['name'] ?? '');
        $email = trim($input['email'] ?? '');
        $phone = trim($input['phone'] ?? '');
        $password = trim($input['password'] ?? '');
        $address = trim($input['address'] ?? '');

        if ($name === '' || $email === '' || $phone === '' || $password === '' || $address === '') {
            throw new Exception('All fields are required');
        }

        // Hash the password before storing
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password, address) VALUES (:name, :email, :phone, :password, :address)");
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':password' => $hashedPassword,
            ':address' => $address,
        ]);

        echo json_encode(['status' => 'success', 'message' => 'User added']);
        exit;
    }

    if ($action === 'update') {
        $id = intval($input['id'] ?? 0);
        $name = trim($input['name'] ?? '');
        $email = trim($input['email'] ?? '');
        $phone = trim($input['phone'] ?? '');
        $password = trim($input['password'] ?? '');
        $address = trim($input['address'] ?? '');

        if ($id <= 0 || $name === '' || $email === '' || $phone === '' || $address === '') {
            throw new Exception('Invalid input');
        }

        if ($password !== '') {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET name = :name, email = :email, phone = :phone, password = :password, address = :address WHERE id = :id");
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':phone' => $phone,
                ':password' => $hashedPassword,
                ':address' => $address,
                ':id' => $id,
            ]);
        } else {
            $stmt = $conn->prepare("UPDATE users SET name = :name, email = :email, phone = :phone, address = :address WHERE id = :id");
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':phone' => $phone,
                ':address' => $address,
                ':id' => $id,
            ]);
        }

        echo json_encode(['status' => 'success', 'message' => 'User updated']);
        exit;
    }

    if ($action === 'delete') {
        $id = intval($input['id'] ?? 0);
        if ($id <= 0) {
            throw new Exception('Invalid user ID');
        }
        $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        echo json_encode(['status' => 'success', 'message' => 'User deleted']);
        exit;
    }

    throw new Exception('Invalid action');

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit;
}
