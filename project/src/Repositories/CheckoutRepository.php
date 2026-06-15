<?php

class CheckoutRepository
{
    public function getCheckoutDataById(int $userId): ?array
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            SELECT
                id,
                username,
                title,
                firstname,
                lastname,
                address,
                zipcode,
                city,
                email,
                payment_info
            FROM users
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute([
            'id' => $userId
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

}