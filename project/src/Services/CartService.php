<?php

class CartService
{
    public function addToCart(int $courseId): array
    {
        if ($courseId <= 0) {
            return [
                'success' => false,
                'message' => 'Ungültiger Kurs'
            ];
        }

        try {
            $cartRepository = new CartRepository();

            if (!$cartRepository->isCourseAvailable($courseId)) {
                return [
                    'success' => false,
                    'message' => 'Der Kurs ist nicht mehr verfügbar'
                ];
            }

            if (isset($_SESSION['user_id'])) {
                $userId = (int)$_SESSION['user_id'];

                if ($cartRepository->existsForUser($userId, $courseId)) {
                    return [
                        'success' => false,
                        'message' => 'Dieser Kurs ist bereits im Warenkorb'
                    ];
                }

                $cartRepository->addForUser($userId, $courseId);
            } else {
                $guestToken = $this->getOrCreateGuestToken();

                if ($cartRepository->existsForGuest($guestToken, $courseId)) {
                    return [
                        'success' => false,
                        'message' => 'Dieser Kurs ist bereits im Warenkorb'
                    ];
                }

                $cartRepository->addForGuest($guestToken, $courseId);
            }

            return [
                'success' => true,
                'message' => 'Kurs zum Warenkorb hinzugefügt'
            ];

        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'DB-Fehler'
            ];
        }
    }

    public function getCart(): array
    {
        try {
            $cartRepository = new CartRepository();

            if (isset($_SESSION['user_id'])) {
                $items = $cartRepository->getByUser((int)$_SESSION['user_id']);
            } else {
                if (!isset($_COOKIE['guest_cart_token']) || $_COOKIE['guest_cart_token'] === '') {
                    $items = [];
                } else {
                    $items = $cartRepository->getByGuest($_COOKIE['guest_cart_token']);
                }
            }

            $total = 0;

            foreach ($items as $item) {
                $total += (float)$item['price'];
            }

            return [
                'success' => true,
                'items' => $items,
                'total' => $total,
                'count' => count($items)
            ];

        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'DB-Fehler'
            ];
        }
    }

    public function removeFromCart(int $cartItemId): array
    {
        if ($cartItemId <= 0) {
            return [
                'success' => false,
                'message' => 'Ungültiger Warenkorb-Eintrag'
            ];
        }

        try {
            $cartRepository = new CartRepository();

            if (isset($_SESSION['user_id'])) {
                $cartRepository->removeForUser((int)$_SESSION['user_id'], $cartItemId);
            } else {
                if (!isset($_COOKIE['guest_cart_token']) || $_COOKIE['guest_cart_token'] === '') {
                    return [
                        'success' => false,
                        'message' => 'Kein Warenkorb gefunden'
                    ];
                }

                $cartRepository->removeForGuest($_COOKIE['guest_cart_token'], $cartItemId);
            }

            return [
                'success' => true,
                'message' => 'Kurs aus dem Warenkorb entfernt'
            ];

        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'DB-Fehler'
            ];
        }
    }

    public function mergeGuestCartIntoUserCart(int $userId): void
    {
        if (!isset($_COOKIE['guest_cart_token']) || $_COOKIE['guest_cart_token'] === '') {
            return;
        }

        $guestToken = $_COOKIE['guest_cart_token'];

        $cartRepository = new CartRepository();
        $guestItems = $cartRepository->getGuestItems($guestToken);

        foreach ($guestItems as $item) {
            $courseId = (int)$item['course_id'];

            if (
                $cartRepository->isCourseAvailable($courseId) &&
                !$cartRepository->existsForUser($userId, $courseId)
            ) {
                $cartRepository->addForUser($userId, $courseId);
            }
        }

        $cartRepository->deleteGuestCart($guestToken);

        setcookie('guest_cart_token', '', time() - 3600, '/');
        unset($_COOKIE['guest_cart_token']);
    }

    private function getOrCreateGuestToken(): string
    {
        if (isset($_COOKIE['guest_cart_token']) && $_COOKIE['guest_cart_token'] !== '') {
            return $_COOKIE['guest_cart_token'];
        }

        $token = bin2hex(random_bytes(32));

        setcookie(
            'guest_cart_token',
            $token,
            time() + (30 * 24 * 60 * 60),
            '/',
            '',
            isset($_SERVER['HTTPS']),
            true
        );

        $_COOKIE['guest_cart_token'] = $token;

        return $token;
    }
}