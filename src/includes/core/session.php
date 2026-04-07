<?php
include_once __DIR__ . '/dbaccess.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Prüfen, ob der Benutzer eingeloggt ist, wenn nicht, aber ein gültiger Remember-Token vorhanden ist, automatisch einloggen
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    $pdo = getDB();

    $stmt = $pdo->prepare("
        SELECT id, username, is_admin
        FROM users
        WHERE remember_token = :token
          AND remember_token_expires IS NOT NULL
          AND remember_token_expires > NOW()
          AND is_active = 1
        LIMIT 1
    ");

    $stmt->execute(['token' => $_COOKIE['remember_token']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = $user['is_admin'];
    } else {
        setcookie('remember_token', '', time() - 3600, '/');
    }
}