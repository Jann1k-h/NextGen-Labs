<?php

class CartService
{
    public function addToCart(int $courseId): array
    {
        $cartRepository = new CartRepository();

        // **************************************************
        // Überprüfen, ob der Kurs noch verfügbar ist
        if ($cartRepository->isCourseAvailable($courseId) == false) {
            return [
                'success' => false,
                'message' => 'Der Kurs ist nicht mehr verfügbar'
            ];
        }
        // **************************************************

        // **************************************************
        // Überprüfen, ob der Kurs bereits im User-Warenkorb ist (wenn es einen User gibt)
        if (isset($_SESSION['user_id'])) {
            $userId = (int)$_SESSION['user_id'];

            if ($cartRepository->existsForUser($userId, $courseId)) {
                return [
                    'success' => false,
                    'message' => 'Dieser Kurs ist bereits im Warenkorb'
                ];
            }

            $cartRepository->addForUser($userId, $courseId);
        }
        // **************************************************
        
        // **************************************************
        // Überprüfen, ob der Kurs bereits im Gast-Warenkorb ist (wenn es keinen User gibt)
        if (!isset($_SESSION['user_id'])) {

            // $this ruft Funktion aus der aktuellen Klasse auf
            $guestToken = $this->getOrCreateGuestToken();

            if ($cartRepository->existsForGuest($guestToken, $courseId)) {
                return [
                    'success' => false,
                    'message' => 'Dieser Kurs ist bereits im Warenkorb'
                ];
            }

            $cartRepository->addForGuest($guestToken, $courseId);
        }
        // **************************************************

        return [
            'success' => true,
            'message' => 'Kurs zum Warenkorb hinzugefügt'
        ];
    }

    public function getCart(): array
    {
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
    }

    public function removeFromCart(int $cartItemId): array
    {
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
        // Überprüfen, ob bereits ein Gast-Token existiert
        if (isset($_COOKIE['guest_cart_token']) && $_COOKIE['guest_cart_token'] !== '') {
            return $_COOKIE['guest_cart_token'];
        }

        // **************************************************
        // Neues Gast-Token generieren und setzen
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
        // **************************************************

        return $token;
    }
}