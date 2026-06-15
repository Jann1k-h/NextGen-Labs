<?php

class AccountRepository
{
    // --------------------------------------------------
    // Benutzer anhand ID laden
    public function findUserById(int $id): ?array
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            SELECT
                id,
                title,
                firstname,
                lastname,
                username,
                address,
                zipcode,
                city,
                email,
                payment_info,
                is_admin,
                is_active,
                created_at,
                updated_at
            FROM users
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute([
            'id' => $id
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Benutzer mit Passwort anhand ID laden
    public function findUserWithPasswordById(int $id): ?array
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            SELECT
                id,
                title,
                firstname,
                lastname,
                username,
                address,
                zipcode,
                city,
                email,
                payment_info,
                password,
                is_admin,
                is_active,
                created_at,
                updated_at
            FROM users
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute([
            'id' => $id
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Prüfen, ob Benutzername bei anderem Benutzer existiert
    public function existsUsernameExceptId(string $username, int $id): bool
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            SELECT COUNT(*)
            FROM users
            WHERE username = :username
            AND id != :id
        ");

        $stmt->execute([
            'username' => trim($username),
            'id' => $id
        ]);

        return (int)$stmt->fetchColumn() > 0;
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Prüfen, ob E-Mail bei anderem Benutzer existiert
    public function existsEmailExceptId(string $email, int $id): bool
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            SELECT COUNT(*)
            FROM users
            WHERE email = :email
            AND id != :id
        ");

        $stmt->execute([
            'email' => trim($email),
            'id' => $id
        ]);

        return (int)$stmt->fetchColumn() > 0;
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Eigene Benutzerdaten aktualisieren
    public function updateUser(int $id, array $data): bool
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            UPDATE users
            SET
                title = :title,
                firstname = :firstname,
                lastname = :lastname,
                username = :username,
                address = :address,
                zipcode = :zipcode,
                city = :city,
                email = :email,
                payment_info = :payment_info,
                updated_at = NOW()
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'title' => $data['title'],
            'firstname' => trim($data['firstname']),
            'lastname' => trim($data['lastname']),
            'username' => trim($data['username']),
            'address' => trim($data['address']),
            'zipcode' => trim($data['zipcode']),
            'city' => trim($data['city']),
            'email' => trim($data['email']),
            'payment_info' => $data['payment_info']
        ]);
    }
    // --------------------------------------------------
}