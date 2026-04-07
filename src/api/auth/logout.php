<?php

include_once __DIR__ . '/../../includes/core/session.php';

header('Content-Type: application/json');

if (isset($_SESSION['user_id'])) {
    $pdo = getDB();
    $stmt = $pdo->prepare("
        UPDATE users
        SET remember_token = NULL, remember_token_expires = NULL
        WHERE id = :user_id
    ");
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
}

setcookie('remember_token', '', time() - 3600, '/');

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

echo json_encode([
    'success' => true,
    'message' => 'Logged out'
]);

exit;