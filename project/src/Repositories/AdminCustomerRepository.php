<?php

class AdminCustomerRepository
{
    // --------------------------------------------------
    // Alle Kunden laden
    public function findAllCustomers(): array
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
            ORDER BY id DESC
        ");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kunden anhand ID finden
    public function findCustomerById(int $id): ?array
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

        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        return $customer ?: null;
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Prüfen, ob Benutzername bei anderem Kunden existiert
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
    // Prüfen, ob E-Mail bei anderem Kunden existiert
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
    // Kunde bearbeiten
    public function updateCustomer(int $id, array $data): bool
    {
        $pdo = getDB();

        if ($data['password'] !== null) {
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
                    password = :password,
                    is_admin = :is_admin,
                    is_active = :is_active
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
                'payment_info' => $data['payment_info'],
                'password' => $data['password'],
                'is_admin' => $data['is_admin'],
                'is_active' => $data['is_active']
            ]);
        }

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
                is_admin = :is_admin,
                is_active = :is_active
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
            'payment_info' => $data['payment_info'],
            'is_admin' => $data['is_admin'],
            'is_active' => $data['is_active']
        ]);
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kunden deaktivieren
    public function deactivateCustomer(int $id): bool
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            UPDATE users
            SET is_active = 0
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id
        ]);
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Bestellungen eines Kunden laden
    public function findOrdersByCustomerId(int $customerId): array
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            SELECT
                id,
                user_id,
                billing_title,
                billing_firstname,
                billing_lastname,
                billing_address,
                billing_zipcode,
                billing_city,
                billing_email,
                billing_payment_info,
                status,
                total_amount,
                voucher_id,
                voucher_code,
                discount_amount,
                created_at,
                updated_at
            FROM orders
            WHERE user_id = :user_id
            ORDER BY id DESC
        ");

        $stmt->execute([
            'user_id' => $customerId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // --------------------------------------------------
}