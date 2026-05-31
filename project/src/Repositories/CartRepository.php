<?php

class CartRepository
{
    public function isCourseAvailable(int $courseId): bool
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            SELECT stock
            FROM courses
            WHERE id = :course_id
              AND is_active = 1
            LIMIT 1
        ");

        $stmt->execute([
            'course_id' => $courseId
        ]);

        $course = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$course) {
            return false;
        }

        if ((int)$course['stock'] <= 0) {
            return false;
        }

        return true;
    }

    public function existsForUser(int $userId, int $courseId): bool
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            SELECT id
            FROM cart_items
            WHERE user_id = :user_id
              AND course_id = :course_id
            LIMIT 1
        ");

        $stmt->execute([
            'user_id' => $userId,
            'course_id' => $courseId
        ]);

        $existsInUserCart = (bool)$stmt->fetch(PDO::FETCH_ASSOC);

        if ($existsInUserCart) {
            return true;
        } else {
            return false;
        }
    }

    public function existsForGuest(string $guestToken, int $courseId): bool
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            SELECT id
            FROM cart_items
            WHERE guest_token = :guest_token
              AND course_id = :course_id
            LIMIT 1
        ");

        $stmt->execute([
            'guest_token' => $guestToken,
            'course_id' => $courseId
        ]);

        $existsInGuestCart = (bool)$stmt->fetch(PDO::FETCH_ASSOC);

        if ($existsInGuestCart) {
            return true;
        } else {
            return false;
        }
    }

    public function addForUser(int $userId, int $courseId): void
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            INSERT INTO cart_items
                (user_id, guest_token, course_id, quantity, created_at, updated_at)
            VALUES
                (:user_id, NULL, :course_id, 1, NOW(), NOW())
        ");

        $stmt->execute([
            'user_id' => $userId,
            'course_id' => $courseId
        ]);
    }

    public function addForGuest(string $guestToken, int $courseId): void
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            INSERT INTO cart_items
                (user_id, guest_token, course_id, quantity, created_at, updated_at)
            VALUES
                (NULL, :guest_token, :course_id, 1, NOW(), NOW())
        ");

        $stmt->execute([
            'guest_token' => $guestToken,
            'course_id' => $courseId
        ]);
    }

    public function getByUser(int $userId): array
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            SELECT 
                ci.id,
                ci.course_id,
                ci.quantity,
                c.title,
                c.description,
                c.price,
                c.rating,
                c.stock,
                (
                    SELECT image_path
                    FROM course_images
                    WHERE course_id = c.id
                    ORDER BY is_cover DESC, sort_order ASC, id ASC
                    LIMIT 1
                ) AS image_path
            FROM cart_items ci
            INNER JOIN courses c ON c.id = ci.course_id
            WHERE ci.user_id = :user_id
            ORDER BY ci.created_at DESC
        ");

        $stmt->execute([
            'user_id' => $userId
        ]);

        $getByUser = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$getByUser) {
            return [];
        } else {
            return $getByUser;
        }
    }

    public function getByGuest(string $guestToken): array
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            SELECT 
                ci.id,
                ci.course_id,
                ci.quantity,
                c.title,
                c.description,
                c.price,
                c.rating,
                c.stock,
                (
                    SELECT image_path
                    FROM course_images
                    WHERE course_id = c.id
                    ORDER BY is_cover DESC, sort_order ASC, id ASC
                    LIMIT 1
                ) AS image_path
            FROM cart_items ci
            INNER JOIN courses c ON c.id = ci.course_id
            WHERE ci.guest_token = :guest_token
            ORDER BY ci.created_at DESC
        ");

        $stmt->execute([
            'guest_token' => $guestToken
        ]);

        $getByGuest = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$getByGuest) {
            return [];
        } else {
            return $getByGuest;
        }
    }

    public function getGuestItems(string $guestToken): array
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            SELECT course_id
            FROM cart_items
            WHERE guest_token = :guest_token
        ");

        $stmt->execute([
            'guest_token' => $guestToken
        ]);

        $getGuestItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$getGuestItems) {
            return [];
        } else {
            return $getGuestItems;
        }
    }

    public function calculateTotalByUser(int $userId): float
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            SELECT SUM(ci.quantity * c.price) AS total
            FROM cart_items ci
            INNER JOIN courses c ON c.id = ci.course_id
            WHERE ci.user_id = :user_id
        ");

        $stmt->execute([
            'user_id' => $userId
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result || $result['total'] === null) {
            return 0.00;
        }

        return (float)$result['total'];
    }

    public function calculateTotalByGuest(string $guestToken): float
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            SELECT SUM(ci.quantity * c.price) AS total
            FROM cart_items ci
            INNER JOIN courses c ON c.id = ci.course_id
            WHERE ci.guest_token = :guest_token
        ");

        $stmt->execute([
            'guest_token' => $guestToken
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result || $result['total'] === null) {
            return 0.00;
        }

        return (float)$result['total'];
    }

    public function deleteGuestCart(string $guestToken): void
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            DELETE FROM cart_items
            WHERE guest_token = :guest_token
        ");

        $stmt->execute([
            'guest_token' => $guestToken
        ]);
    }

    public function removeForUser(int $userId, int $cartItemId): void
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            DELETE FROM cart_items
            WHERE id = :id
              AND user_id = :user_id
        ");

        $stmt->execute([
            'id' => $cartItemId,
            'user_id' => $userId
        ]);
    }

    public function removeForGuest(string $guestToken, int $cartItemId): void
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            DELETE FROM cart_items
            WHERE id = :id
              AND guest_token = :guest_token
        ");

        $stmt->execute([
            'id' => $cartItemId,
            'guest_token' => $guestToken
        ]);
    }
}