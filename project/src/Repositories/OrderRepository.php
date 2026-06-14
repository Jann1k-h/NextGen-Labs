<?php

class OrderRepository
{
    public function createOrder(array $data): int
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            INSERT INTO orders
            (
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
                discount_amount,
                voucher_code,
                created_at,
                updated_at
            )
            VALUES
            (
                :user_id,
                :billing_title,
                :billing_firstname,
                :billing_lastname,
                :billing_address,
                :billing_zipcode,
                :billing_city,
                :billing_email,
                :billing_payment_info,
                :status,
                :total_amount,
                :voucher_id,
                :discount_amount,
                :voucher_code,
                NOW(),
                NOW()
            )
        ");

        $stmt->execute([
            'user_id' => $data['user_id'],
            'billing_title' => $data['billing_title'],
            'billing_firstname' => $data['billing_firstname'],
            'billing_lastname' => $data['billing_lastname'],
            'billing_address' => $data['billing_address'],
            'billing_zipcode' => $data['billing_zipcode'],
            'billing_city' => $data['billing_city'],
            'billing_email' => $data['billing_email'],
            'billing_payment_info' => $data['billing_payment_info'],
            'status' => $data['status'],
            'total_amount' => $data['total_amount'],
            'voucher_id' => $data['voucher_id'],
            'discount_amount' => $data['discount_amount'],
            'voucher_code' => $data['voucher_code']
        ]);

        return (int)$pdo->lastInsertId();
    }

    public function createOrderItem(array $data): void
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            INSERT INTO order_items
            (
                order_id,
                course_id,
                quantity,
                price,
                course_for,
                created_at,
                updated_at
            )
            VALUES
            (
                :order_id,
                :course_id,
                :quantity,
                :price,
                :course_for,
                NOW(),
                NOW()
            )
        ");

        $stmt->execute([
            'order_id' => $data['order_id'],
            'course_id' => $data['course_id'],
            'quantity' => $data['quantity'],
            'price' => $data['price'],
            'course_for' => $data['course_for']
        ]);
    }

    public function getOrderDetailsById(int $orderId, int $userId): ?array
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            SELECT *
            FROM orders
            WHERE id = :order_id
              AND user_id = :user_id
            LIMIT 1
        ");

        $stmt->execute([
            'order_id' => $orderId,
            'user_id' => $userId
        ]);

        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            return null;
        }

        $stmt = $pdo->prepare("
            SELECT
                oi.id,
                oi.order_id,
                oi.course_id,
                oi.quantity,
                oi.price,
                oi.course_for,
                c.title,
                c.description,
                c.lecturer_name,
                c.lecturer_contact,
                (
                    SELECT image_path
                    FROM course_images
                    WHERE course_id = c.id
                    ORDER BY is_cover DESC, sort_order ASC, id ASC
                    LIMIT 1
                ) AS image_path
            FROM order_items oi
            INNER JOIN courses c ON c.id = oi.course_id
            WHERE oi.order_id = :order_id
            ORDER BY oi.id ASC
        ");

        $stmt->execute([
            'order_id' => $orderId
        ]);

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'order' => $order,
            'items' => $items
        ];
    }

    public function getOrdersByUserId(int $userId): array
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
        SELECT
            id,
            status,
            total_amount,
            discount_amount,
            voucher_code,
            created_at
        FROM orders
        WHERE user_id = :user_id
        ORDER BY created_at DESC
    ");

        $stmt->execute([
            'user_id' => $userId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
