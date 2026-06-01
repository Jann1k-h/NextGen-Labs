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



    public function placeOrder(array $input): array
    {
        if (!isset($_SESSION['user_id'])) {
            return [
                'success' => false,
                'message' => 'Bitte melden Sie sich zuerst an'
            ];
        }

        $userId = (int)$_SESSION['user_id'];

        $billingTitle = trim($input['billing_title'] ?? '');
        $billingFirstname = trim($input['billing_firstname'] ?? '');
        $billingLastname = trim($input['billing_lastname'] ?? '');
        $billingAddress = trim($input['billing_address'] ?? '');
        $billingZipcode = trim($input['billing_zipcode'] ?? '');
        $billingCity = trim($input['billing_city'] ?? '');
        $billingEmail = trim($input['billing_email'] ?? '');
        $paymentMethod = trim($input['payment_method'] ?? '');
        $voucherCode = trim($input['voucher_code'] ?? '');
        $participants = $input['participants'] ?? [];

        if (
            $billingTitle === '' ||
            $billingFirstname === '' ||
            $billingLastname === '' ||
            $billingAddress === '' ||
            $billingZipcode === '' ||
            $billingCity === '' ||
            $billingEmail === '' ||
            $paymentMethod === ''
        ) {
            return [
                'success' => false,
                'message' => 'Bitte fülle alle Rechnungsdaten aus'
            ];
        }

        if (!filter_var($billingEmail, FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'message' => 'Bitte gib eine gültige E-Mail-Adresse ein'
            ];
        }

        $pdo = getDB();

        try {
            $pdo->beginTransaction();

            $cartRepository = new CartRepository();
            $orderRepository = new OrderRepository();
            $voucherRepository = new VoucherRepository();

            $items = $cartRepository->getByUser($userId);

            if (count($items) === 0) {
                $pdo->rollBack();

                return [
                    'success' => false,
                    'message' => 'Ihr Warenkorb ist leer'
                ];
            }

            $subtotal = $cartRepository->calculateTotalByUser($userId);

            $voucher = null;
            $discountAmount = 0.00;
            $finalTotal = $subtotal;

            if ($voucherCode !== '') {
                $voucherResult = $this->checkVoucher($voucherCode);

                if (!$voucherResult['success']) {
                    $pdo->rollBack();

                    return [
                        'success' => false,
                        'message' => $voucherResult['message']
                    ];
                }

                $voucher = $voucherRepository->findByCode($voucherCode);
                $discountAmount = (float)$voucherResult['discount_amount'];
                $finalTotal = (float)$voucherResult['final_total'];
            }

            $orderId = $orderRepository->createOrder([
                'user_id' => $userId,
                'billing_title' => $billingTitle,
                'billing_firstname' => $billingFirstname,
                'billing_lastname' => $billingLastname,
                'billing_address' => $billingAddress,
                'billing_zipcode' => $billingZipcode,
                'billing_city' => $billingCity,
                'billing_email' => $billingEmail,
                'billing_payment_info' => $paymentMethod,
                'status' => 'pending',
                'total_amount' => round($finalTotal, 2),
                'voucher_id' => $voucher ? $voucher->id : null,
                'discount_amount' => round($discountAmount, 2),
                'voucher_code' => $voucher ? $voucher->code : null
            ]);

            foreach ($items as $item) {
                $courseId = (int)$item['course_id'];
                $quantity = (int)$item['quantity'];
                $price = (float)$item['price'];

                $courseFor = trim($participants[$courseId] ?? '');

                if ($courseFor === '') {
                    $pdo->rollBack();

                    return [
                        'success' => false,
                        'message' => 'Bitte gib für jeden Kurs eine teilnehmende Person an'
                    ];
                }

                $orderRepository->createOrderItem([
                    'order_id' => $orderId,
                    'course_id' => $courseId,
                    'quantity' => $quantity,
                    'price' => $price,
                    'course_for' => $courseFor
                ]);

                // Lagerbestand reduzieren
                $cartRepository->reduceCourseStock($courseId, $quantity);
            }

            if ($voucher !== null) {
                $voucherRepository->increaseUsedCount((int)$voucher->id);
            }

            $cartRepository->clearForUser($userId);

            $pdo->commit();

            return [
                'success' => true,
                'message' => 'Bestellung wurde erfolgreich erstellt',
                'order_id' => $orderId
            ];

        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return [
                'success' => false,
                'message' => 'Fehler beim Erstellen der Bestellung: ' . $e->getMessage()
            ];
        }
    }


    public function getOrderDetails(int $orderId): array
    {
        if (!isset($_SESSION['user_id'])) {
            return [
                'success' => false,
                'message' => 'Bitte melden Sie sich zuerst an'
            ];
        }

        $userId = (int)$_SESSION['user_id'];

        $orderRepository = new OrderRepository();
        $details = $orderRepository->getOrderDetailsById($orderId, $userId);

        if (!$details) {
            return [
                'success' => false,
                'message' => 'Bestellung wurde nicht gefunden'
            ];
        }

        return [
            'success' => true,
            'order' => $details['order'],
            'items' => $details['items']
        ];
    }
}