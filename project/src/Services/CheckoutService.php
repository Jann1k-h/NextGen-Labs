<?php

class CheckoutService
{
    public function checkCheckout(): array
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

        /* $items ist ein Array, was folgendermaßen aufgebaut ist:
        * [
        *   [
        *     'course_id' => 1,
        *     'course_name' => 'Kurs 1',
        *     'price' => 19.99,
        *     'quantity' => 2
        *   ],
        *   [
        *     'course_id' => 2,
        *     'course_name' => 'Kurs 2',
        *     'price' => 29.99,
        *     'quantity' => 1
        *   ]
        * ]
        *
        */

        $total = $cartRepository->calculateTotalByUser($userId);

        return [
            'success' => true,
            'user' => $user,
            'items' => $items,
            'total' => $total
        ];
    }

    public function checkVoucher(string $voucherCode): array
    {
        if (!isset($_SESSION['user_id'])) {
            return [
                'success' => false,
                'message' => 'Bitte melden Sie sich zuerst an'
            ];
        }

        $userId = (int) $_SESSION['user_id'];

        $cartRepository = new CartRepository();
        $cartTotal = $cartRepository->calculateTotalByUser($userId);

        if ($cartTotal <= 0) {
            return [
                'success' => false,
                'message' => 'Ihr Warenkorb ist leer'
            ];
        }

        $voucherCode = trim($voucherCode);

        if ($voucherCode === '') {
            return [
                'success' => false,
                'message' => 'Bitte gib einen Gutscheincode ein.'
            ];
        }

        $voucherRepository = new VoucherRepository();
        $voucher = $voucherRepository->findByCode($voucherCode);

        if ($voucher === null) {
            return [
                'success' => false,
                'message' => 'Ungültiger Gutscheincode'
            ];
        }

        if (!$voucher->is_active) {
            return [
                'success' => false,
                'message' => 'Dieser Gutschein ist nicht aktiv'
            ];
        }

        if ($voucher->valid_until !== null && strtotime($voucher->valid_until) < time()) {
            return [
                'success' => false,
                'message' => 'Dieser Gutschein ist abgelaufen'
            ];
        }

        if ($voucher->usage_limit !== null && $voucher->used_count >= $voucher->usage_limit) {
            return [
                'success' => false,
                'message' => 'Dieser Gutschein wurde bereits zu oft verwendet'
            ];
        }

        $discountAmount = 0;

        if ($voucher->discount_type === 'percent') {
            $discountAmount = $cartTotal * ($voucher->discount_value / 100);
        } elseif ($voucher->discount_type === 'fixed') {
            $discountAmount = $voucher->discount_value;
        }

        if ($discountAmount > $cartTotal) {
            $discountAmount = $cartTotal;
        }

        $finalTotal = $cartTotal - $discountAmount;

        return [
            'success' => true,
            'message' => 'Gutscheincode ist gültig',
            'voucher' => [
                'id' => $voucher->id,
                'code' => $voucher->code,
                'name' => $voucher->name,
                'discount_type' => $voucher->discount_type,
                'discount_value' => $voucher->discount_value
            ],
            'subtotal' => round($cartTotal, 2),
            'discount_amount' => round($discountAmount, 2),
            'final_total' => round($finalTotal, 2)
        ];
    }
}