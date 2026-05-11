<?php

// User-DB-Zugriff

class UserRepository
{

    // Rpckgabewert ist entweder ein User-Objekt oder null, wenn kein User gefunden wurde
    public function findByIdentifier(string $identifier): ?User
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            SELECT * 
            FROM users 
            WHERE username = :identifier OR email = :identifier
            LIMIT 1
        ");

        // :identifier = 'identifier' wird durch $identifier ersetzt, also entweder username oder email
        $stmt->execute(['identifier' => $identifier]);

        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$userData) {
            return null;
        }

        return new User($userData);
    }

    public function updateRememberToken(int $userId, ?string $token, ?string $expires): void
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            UPDATE users 
            SET remember_token = :token, remember_token_expires = :expires 
            WHERE id = :user_id
        ");

        $stmt->execute([
            'token' => $token,
            'expires' => $expires,
            'user_id' => $userId
        ]);
    }

    public function createUser($title, $firstname, $lastname, $username, $address, $zipcode, $city, $email, $hashedPassword, $paymentInfo): User
    {

        // Insert into sind Werte aus DB, Values sind Platzhalter, die durch execute mit den tatsächlichen Werten ersetzt werden
        $pdo = getDB();

        $stmt = $pdo->prepare("
            INSERT INTO users (title, firstname, lastname, username, address, zipcode, city, email, password, payment_info)
            VALUES (:title, :firstname, :lastname, :username, :address, :zipcode, :city, :email, :password, :payment_info)
        ");

        // erste spalte sind Platzhalter aus VALUES, 2 Spalte sind Werte, die in Funktion übergeben wurden, also z.B. $title, $firstname etc.
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

        return new User([
            'id' => (int)$pdo->lastInsertId(),
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword,
            'is_admin' => false,
            'is_active' => true,
            'remember_token' => null,
            'remember_token_expires' => null
        ]);
    }
}