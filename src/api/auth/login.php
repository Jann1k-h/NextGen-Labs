<?php

include_once __DIR__ . '/../../includes/core/dbaccess.php';
include_once __DIR__ . '/../../includes/core/session.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$identifier = trim($data['identifier'] ?? '');
$password = trim($data['password'] ?? '');
$rememberMe = isset($data['rememberMe']) ? (bool)$data['rememberMe'] : false;

if ($identifier === '') {
    echo json_encode([
        'success' => false,
        'message' => 'Bitte E-Mail oder Username eingeben'
    ]);
    exit;
}

if ($password === '') {
    echo json_encode([
        'success' => false,
        'message' => 'Bitte Passwort eingeben'
    ]);
    exit;
}

try {

    $pdo = getDB();

// $stmt = $pdo->prepare("SELECT * FROM users");
// $stmt->execute();
// $user = $stmt->fetch();
    $stmt = $pdo->prepare("
        SELECT * 
        FROM users 
        WHERE username = :identifier OR email = :identifier
        LIMIT 1
    ");

    // :identifier = 'identiefier' wird durch $identifier ersetzt, also entweder username oder email
    $stmt->execute(['identifier' => $identifier]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode([
            'success' => false,
            'message' => 'User nicht gefunden'
        ]);
        exit;
    }

    if (!$user['is_active']) {
        echo json_encode([
            'success' => false,
            'message' => 'Account ist deaktiviert'
        ]);
        exit;
    }

    // password_verify() vergleicht das eingegebene Passwort mit dem in der DB gespeicherten Hasht Passwort
    if (!password_verify($password, $user['password'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Falsches Passwort'
        ]);
        exit;
    }

    if ($rememberMe) {
        // Generiere einen zufälligen Token
        $token = bin2hex(random_bytes(16));
        $expires = date('Y-m-d H:i:s', time() + (30 * 24 * 60 * 60)); // 30 Tage

        // Speichere den Token in der DB
        $stmt = $pdo->prepare("
            UPDATE users 
            SET remember_token = :token, remember_token_expires = :expires 
            WHERE id = :user_id
        ");
        $stmt->execute([
            'token' => $token,
            'expires' => $expires,
            'user_id' => $user['id']
        ]);

        // Setze den Token als Cookie
        setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), "/");
    } else {
        // Wenn "Remember Me" nicht ausgewählt ist, lösche den Token aus der DB und dem Cookie
        $stmt = $pdo->prepare("
            UPDATE users 
            SET remember_token = NULL, remember_token_expires = NULL 
            WHERE id = :user_id
        ");
        $stmt->execute(['user_id' => $user['id']]);

        setcookie('remember_token', '', time() - 3600, "/");
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['is_admin'] = $user['is_admin'];

    echo json_encode([
        'success' => true,
        'message' => 'Erfolgreich eingeloggt',
        'username' => $user['username'],
        'is_admin' => (bool)$user['is_admin']
    ]);
    exit;

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'DB-Fehler'
    ]);
    exit;
}