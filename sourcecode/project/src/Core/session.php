<?php

include_once __DIR__ . '/dbaccess.php';

// Startet die Session und loggt den User automatisch ein,
// wenn ein gültiger Remember-Token im Cookie vorhanden ist.

// --------------------------------------------------
// Session starten, falls noch keine Session aktiv ist
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// --------------------------------------------------


// --------------------------------------------------
// Automatischer Login über Remember-Token
// Wenn kein User in der Session ist, aber ein Remember-Token-Cookie existiert,
// wird geprüft, ob der Token gültig ist und der User automatisch eingeloggt werden kann.
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {

    // Datenbankverbindung holen
    $pdo = getDB();

    // User anhand des Remember-Tokens suchen
    $stmt = $pdo->prepare("
        SELECT id, username, is_admin
        FROM users
        WHERE remember_token = :token
          AND remember_token_expires IS NOT NULL
          AND remember_token_expires > NOW()
          AND is_active = 1
        LIMIT 1
    ");

    // Token aus dem Cookie an die Abfrage übergeben
    $stmt->execute(['token' => $_COOKIE['remember_token']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Userdaten in die Session schreiben, damit der User als eingeloggt gilt
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = $user['is_admin'];
    } else {
        // Ungültigen oder abgelaufenen Remember-Token-Cookie löschen
        setcookie('remember_token', '', time() - 3600, '/');
    }
}
// --------------------------------------------------