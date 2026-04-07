<?php

include_once __DIR__ . '/../../includes/core/dbaccess.php';
include_once __DIR__ . '/../../includes/core/session.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$title = trim($data['title'] ?? '');
$firstname = trim($data['firstname'] ?? '');
$lastname = trim($data['lastname'] ?? '');
$username = trim($data['username'] ?? '');
$address = trim($data['address'] ?? '');
$zipcode = trim($data['zipcode'] ?? '');
$city = trim($data['city'] ?? '');
$email = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');
$confirmPassword = trim($data['confirmPassword'] ?? '');
$paymentInfo = trim($data['paymentInfo'] ?? '');

if ($title === '' || $firstname === '' || $lastname === '' || $username === '' || $address === '' || $zipcode === '' || $city === '' || $email === '' || $password === '' || $confirmPassword === '') {
    echo json_encode([
        'success' => false,
        'message' => 'Bitte alle Felder ausfüllen'
    ]);
    exit;
}

if ($password !== $confirmPassword) {
    echo json_encode([
        'success' => false,
        'message' => 'Passwörter stimmen nicht überein'
    ]);
    exit;
}

try {

    $pdo = getDB();

    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM users
        WHERE username = :username"
    );

    $stmt->execute(['username' => $username]);

    // $countUsername = $stmt->fetchColumn() gibt die Anzahl der gefundenen Zeilen zurück
    $countUsername = $stmt->fetchColumn();

    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM users
        WHERE email = :email"
    );

    $stmt->execute(['email' => $email]);

    // $countEmail = $stmt->fetchColumn() gibt die Anzahl der gefundenen Zeilen zurück
    $countEmail = $stmt->fetchColumn();

    if ($countUsername > 0 && $countEmail > 0) {
        $message = 'Username und E-Mail sind bereits vergeben';
    } elseif ($countUsername > 0) {
        $message = 'Username ist bereits vergeben';
    } elseif ($countEmail > 0) {
        $message = 'E-Mail ist bereits vergeben';
    } else {
        $message = null;
    }

    if ($message !== null) {
        echo json_encode([
            'success' => false,
            'message' => $message
        ]);
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("
        INSERT INTO 
            users (
                title, firstname, lastname, username, address, zipcode, city, email, password, payment_info
            ) 
            VALUES (
                :title, :firstname, :lastname, :username, :address, :zipcode, :city, :email, :password, :payment_info
            )
    ");

    // :title, :firstname, ... werden durch die entsprechenden übergebenen Variablen ersetzt
    $stmt->execute([
        'title' => $title,
        'firstname' => $firstname,
        'lastname' => $lastname,
        'username' => $username,
        'address' => $address,
        'zipcode' => $zipcode,
        'city' => $city,
        'email' => $email,
        'password' => $hashedPassword,
        'payment_info' => $paymentInfo
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Registrierung erfolgreich, du kannst dich jetzt einloggen',
        'username' => $username
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Datenbankfehler: ' . $e->getMessage()
    ]);
}