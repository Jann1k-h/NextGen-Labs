<?php

class CheckoutService
{
    public function checkCheckoutAllowed(): array
    {
        if (!isset($_SESSION['user_id'])) {
            return [
                'success' => false,
                'message' => 'Bitte melden Sie sich an, um zur Kasse zu gehen'
            ];
        }

        $userId = (int) $_SESSION['user_id'];

        $cartRepository = new CartRepository();
        $items = $cartRepository->getByUser($userId);

        if (count($items) === 0) {
            return [
                'success' => false,
                'message' => 'Ihr Warenkorb ist leer'
            ];
        }

        return [
            'success' => true,
            'message' => 'Weiterleitung zur Kasse'
        ];
    }

    public function getCheckoutData(): array
    {
        if (!isset($_SESSION['user_id'])) {
            return [
                'success' => false,
                'message' => 'Bitte melden Sie sich zuerst an'
            ];
        }

        $userId = (int) $_SESSION['user_id'];

        $userRepository = new UserRepository();
        $cartRepository = new CartRepository();

        $user = $userRepository->getCheckoutDataById($userId);
        $items = $cartRepository->getByUser($userId);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Benutzer wurde nicht gefunden'
            ];
        }

        if (count($items) === 0) {
            return [
                'success' => false,
                'message' => 'Ihr Warenkorb ist leer'
            ];
        }

        $total = 0;

        foreach ($items as $item) {
            $total += (float) $item['price'] * (int) $item['quantity'];
        }

        return [
            'success' => true,
            'user' => $user,
            'items' => $items,
            'total' => $total
        ];
    }
}