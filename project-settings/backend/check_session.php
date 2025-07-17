<?php
session_start();

header('Content-Type: application/json');

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    echo json_encode(['status' => 'success', 'message' => 'User is logged in']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
}
?>
