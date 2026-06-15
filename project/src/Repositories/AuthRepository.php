<?php

class AuthRepository
{

    public function findByIdentifier(string $identifier): ?array
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

        return $userData ?: null;
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

    public function createUser($title, $firstname, $lastname, $username, $address, $zipcode, $city, $email, $hashedPassword, $paymentInfo): void
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            INSERT INTO users (title, firstname, lastname, username, address, zipcode, city, email, password, payment_info)
            VALUES (:title, :firstname, :lastname, :username, :address, :zipcode, :city, :email, :password, :payment_info)
        ");

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
    }
}