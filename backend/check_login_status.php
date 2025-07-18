<?php
session_start();

header('Content-Type: application/json');

if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    // Assuming you store user name in session as well
    $userName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';

    echo json_encode([
        'logged_in' => true,
        'userName' => $userName
    ]);
} else {
    echo json_encode([
        'logged_in' => false
    ]);
}
?>
