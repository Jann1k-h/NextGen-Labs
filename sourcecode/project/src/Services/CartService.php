<?php

class CartService
{
    // --------------------------------------------------
    // Kurs zum Warenkorb hinzufügen
    public function addToCart(int $courseId): array
    {
        // Repository erstellen, damit der Service auf Warenkorb-Daten zugreifen kann
        $cartRepository = new CartRepository();

        // Prüfen, ob der Kurs noch verfügbar ist
        if ($cartRepository->isCourseAvailable($courseId) == false) {
            return [
                'success' => false,
                'message' => 'Der Kurs ist nicht mehr verfügbar'
            ];
        }

        // Wenn ein User eingeloggt ist, Kurs in den User-Warenkorb legen
        if (isset($_SESSION['user_id'])) {
            $userId = (int)$_SESSION['user_id'];

            // Prüfen, ob der Kurs bereits im User-Warenkorb ist
            if ($cartRepository->existsForUser($userId, $courseId)) {
                return [
                    'success' => false,
                    'message' => 'Dieser Kurs ist bereits im Warenkorb'
                ];
            }

            $cartRepository->addForUser($userId, $courseId);
        }

        // Wenn kein User eingeloggt ist, Kurs in den Gast-Warenkorb legen
        if (!isset($_SESSION['user_id'])) {

            // Gast-Token holen oder neu erstellen
            $guestToken = $this->getOrCreateGuestToken();

            // Prüfen, ob der Kurs bereits im Gast-Warenkorb ist
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
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Warenkorb laden
    public function getCart(): array
    {
        // Repository erstellen, damit der Service auf Warenkorb-Daten zugreifen kann
        $cartRepository = new CartRepository();

        // Warenkorb je nach Login-Status laden
        if (isset($_SESSION['user_id'])) {
            $items = $cartRepository->getByUser((int)$_SESSION['user_id']);
        } else {
            // Wenn kein Gast-Token existiert, ist der Gast-Warenkorb leer
            if (!isset($_COOKIE['guest_cart_token']) || $_COOKIE['guest_cart_token'] === '') {
                $items = [];
            } else {
                $items = $cartRepository->getByGuest($_COOKIE['guest_cart_token']);
            }
        }

        // Gesamtpreis berechnen
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
    // --------------------------------------------------


    // --------------------------------------------------
    // Kurs aus Warenkorb entfernen
    public function removeFromCart(int $cartItemId): array
    {
        // Repository erstellen, damit der Service auf Warenkorb-Daten zugreifen kann
        $cartRepository = new CartRepository();

        // Entfernen je nach Login-Status
        if (isset($_SESSION['user_id'])) {
            $cartRepository->removeForUser((int)$_SESSION['user_id'], $cartItemId);
        } else {
            // Ohne Gast-Token gibt es keinen Gast-Warenkorb
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
    // --------------------------------------------------


    // --------------------------------------------------
    // Gast-Warenkorb nach Login in User-Warenkorb übernehmen
    public function mergeGuestCartIntoUserCart(int $userId): void
    {
        // Wenn kein Gast-Token existiert, gibt es nichts zu übernehmen
        if (!isset($_COOKIE['guest_cart_token']) || $_COOKIE['guest_cart_token'] === '') {
            return;
        }

        $guestToken = $_COOKIE['guest_cart_token'];

        // Gast-Warenkorb laden
        $cartRepository = new CartRepository();
        $guestItems = $cartRepository->getGuestItems($guestToken);

        // Jeden Gast-Warenkorb-Eintrag prüfen und in den User-Warenkorb übernehmen
        foreach ($guestItems as $item) {
            $courseId = (int)$item['course_id'];

            if (
                $cartRepository->isCourseAvailable($courseId) &&
                !$cartRepository->existsForUser($userId, $courseId)
            ) {
                $cartRepository->addForUser($userId, $courseId);
            }
        }

        // Gast-Warenkorb nach dem Übernehmen löschen
        $cartRepository->deleteGuestCart($guestToken);

        // Gast-Token-Cookie löschen
        setcookie('guest_cart_token', '', time() - 3600, '/');
        unset($_COOKIE['guest_cart_token']);
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Gast-Token holen oder erstellen
    private function getOrCreateGuestToken(): string
    {
        // Bestehenden Gast-Token verwenden, falls vorhanden
        if (isset($_COOKIE['guest_cart_token']) && $_COOKIE['guest_cart_token'] !== '') {
            return $_COOKIE['guest_cart_token'];
        }

        // Neuen zufälligen Gast-Token erstellen
        $token = bin2hex(random_bytes(32));

        // Gast-Token als Cookie speichern
        setcookie(
            'guest_cart_token',
            $token,
            time() + (30 * 24 * 60 * 60),
            '/',
            '',
            isset($_SERVER['HTTPS']),
            true
        );

        // Token auch direkt in $_COOKIE setzen, damit er im aktuellen Request verfügbar ist
        $_COOKIE['guest_cart_token'] = $token;

        return $token;
    }
    // --------------------------------------------------
}