<?php

class CheckoutService
{
    // --------------------------------------------------
    // Prüfen, ob User zur Kasse gehen darf
    public function checkCheckout(): array
    {
        // Checkout ist nur für eingeloggte User erlaubt
        if (!isset($_SESSION['user_id'])) {
            return [
                'success' => false,
                'message' => 'Bitte melden Sie sich an, um zur Kasse zu gehen'
            ];
        }

        $userId = (int) $_SESSION['user_id'];

        // Warenkorb des Users laden
        $cartRepository = new CartRepository();
        $items = $cartRepository->getByUser($userId);

        // Prüfen, ob der Warenkorb leer ist
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
    // --------------------------------------------------


    // --------------------------------------------------
    // Checkout-Daten laden
    public function getCheckoutData(): array
    {
        // Prüfen, ob der User eingeloggt ist
        if (!isset($_SESSION['user_id'])) {
            return [
                'success' => false,
                'message' => 'Bitte melden Sie sich zuerst an'
            ];
        }

        $userId = (int) $_SESSION['user_id'];

        // Repositories erstellen
        $checkoutRepository = new CheckoutRepository();
        $cartRepository = new CartRepository();

        // Userdaten und Warenkorb laden
        $user = $checkoutRepository->getCheckoutDataById($userId);
        $items = $cartRepository->getByUser($userId);

        // Prüfen, ob der User gefunden wurde
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Benutzer wurde nicht gefunden'
            ];
        }

        // Prüfen, ob der Warenkorb leer ist
        if (count($items) === 0) {
            return [
                'success' => false,
                'message' => 'Ihr Warenkorb ist leer'
            ];
        }

        // Gesamtpreis des Warenkorbs berechnen
        $total = $cartRepository->calculateTotalByUser($userId);

        // Checkout-Daten zurückgeben
        return [
            'success' => true,
            'user' => $user,
            'items' => $items,
            'total' => $total
        ];
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Gutschein prüfen
    public function checkVoucher(string $voucherCode): array
    {
        // Prüfen, ob der User eingeloggt ist
        if (!isset($_SESSION['user_id'])) {
            return [
                'success' => false,
                'message' => 'Bitte melden Sie sich zuerst an'
            ];
        }

        $userId = (int) $_SESSION['user_id'];

        // Warenkorb-Summe laden
        $cartRepository = new CartRepository();
        $cartTotal = $cartRepository->calculateTotalByUser($userId);

        // Gutschein kann nur geprüft werden, wenn der Warenkorb nicht leer ist
        if ($cartTotal <= 0) {
            return [
                'success' => false,
                'message' => 'Ihr Warenkorb ist leer'
            ];
        }

        // Gutscheincode bereinigen
        $voucherCode = trim($voucherCode);

        if ($voucherCode === '') {
            return [
                'success' => false,
                'message' => 'Bitte gib einen Gutscheincode ein.'
            ];
        }

        // Gutschein anhand des Codes suchen
        $voucherRepository = new AdminVoucherRepository();
        $voucher = $voucherRepository->findByCode($voucherCode);

        // Prüfen, ob der Gutschein existiert
        if ($voucher === null) {
            return [
                'success' => false,
                'message' => 'Ungültiger Gutscheincode'
            ];
        }

        // Prüfen, ob der Gutschein aktiv ist
        if ((int)$voucher['is_active'] !== 1) {
            return [
                'success' => false,
                'message' => 'Dieser Gutschein ist nicht aktiv'
            ];
        }

        // Prüfen, ob der Gutschein abgelaufen ist
        if ($voucher['valid_until'] !== null && strtotime($voucher['valid_until']) < time()) {
            return [
                'success' => false,
                'message' => 'Dieser Gutschein ist abgelaufen'
            ];
        }

        // Prüfen, ob das Nutzungslimit erreicht wurde
        if (
            $voucher['usage_limit'] !== null &&
            $voucher['usage_limit'] !== '' &&
            (int)$voucher['used_count'] >= (int)$voucher['usage_limit']
        ) {
            return [
                'success' => false,
                'message' => 'Dieser Gutschein wurde bereits zu oft verwendet'
            ];
        }

        // Rabattbetrag berechnen
        $discountAmount = 0;

        if ($voucher['discount_type'] === 'percent') {
            $discountAmount = $cartTotal * ((float)$voucher['discount_value'] / 100);
        } elseif ($voucher['discount_type'] === 'fixed') {
            $discountAmount = (float)$voucher['discount_value'];
        }

        // Rabatt darf nicht höher als der Warenkorbwert sein
        if ($discountAmount > $cartTotal) {
            $discountAmount = $cartTotal;
        }

        // Endbetrag berechnen
        $finalTotal = $cartTotal - $discountAmount;

        return [
            'success' => true,
            'message' => 'Gutscheincode ist gültig',
            'voucher' => [
                'id' => (int)$voucher['id'],
                'code' => $voucher['code'],
                'name' => $voucher['name'],
                'discount_type' => $voucher['discount_type'],
                'discount_value' => (float)$voucher['discount_value']
            ],
            'subtotal' => round($cartTotal, 2),
            'discount_amount' => round($discountAmount, 2),
            'final_total' => round($finalTotal, 2)
        ];
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Bestellung erstellen
    public function placeOrder(array $input): array
    {
        // Prüfen, ob der User eingeloggt ist
        if (!isset($_SESSION['user_id'])) {
            return [
                'success' => false,
                'message' => 'Bitte melden Sie sich zuerst an'
            ];
        }

        $userId = (int)$_SESSION['user_id'];

        // Rechnungsdaten aus dem Request lesen
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

        // Prüfen, ob alle Rechnungsdaten ausgefüllt wurden
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

        // E-Mail-Adresse validieren
        if (!filter_var($billingEmail, FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'message' => 'Bitte gib eine gültige E-Mail-Adresse ein'
            ];
        }

        $pdo = getDB();

        try {
            // Transaktion starten, damit Bestellung vollständig oder gar nicht gespeichert wird
            $pdo->beginTransaction();

            // Repositories erstellen
            $cartRepository = new CartRepository();
            $orderRepository = new OrderRepository();
            $voucherRepository = new AdminVoucherRepository();

            // Warenkorb laden
            $items = $cartRepository->getByUser($userId);

            // Prüfen, ob der Warenkorb leer ist
            if (count($items) === 0) {
                $pdo->rollBack();

                return [
                    'success' => false,
                    'message' => 'Ihr Warenkorb ist leer'
                ];
            }

            // Zwischensumme berechnen
            $subtotal = $cartRepository->calculateTotalByUser($userId);

            $voucher = null;
            $discountAmount = 0.00;
            $finalTotal = $subtotal;

            // Gutschein prüfen und Rabatt übernehmen, falls ein Code eingegeben wurde
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

            // Bestellung erstellen
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
                'voucher_id' => $voucher ? (int)$voucher['id'] : null,
                'discount_amount' => round($discountAmount, 2),
                'voucher_code' => $voucher ? $voucher['code'] : null
            ]);

            // Bestellpositionen aus dem Warenkorb erstellen
            foreach ($items as $item) {
                $courseId = (int)$item['course_id'];
                $quantity = (int)$item['quantity'];
                $price = (float)$item['price'];

                // Teilnehmende Person für diesen Kurs lesen
                $courseFor = trim($participants[$courseId] ?? '');

                if ($courseFor === '') {
                    $pdo->rollBack();

                    return [
                        'success' => false,
                        'message' => 'Bitte gib für jeden Kurs eine teilnehmende Person an'
                    ];
                }

                // Bestellposition speichern
                $orderRepository->createOrderItem([
                    'order_id' => $orderId,
                    'course_id' => $courseId,
                    'quantity' => $quantity,
                    'price' => $price,
                    'course_for' => $courseFor
                ]);

                // Lagerbestand des Kurses reduzieren
                $cartRepository->reduceCourseStock($courseId, $quantity);
            }

            // Gutschein-Nutzung erhöhen, falls ein Gutschein verwendet wurde
            if ($voucher !== null) {
                $voucherRepository->increaseUsedCount((int)$voucher['id']);
            }

            // Warenkorb nach erfolgreicher Bestellung leeren
            $cartRepository->clearForUser($userId);

            // Änderungen endgültig speichern
            $pdo->commit();

            return [
                'success' => true,
                'message' => 'Bestellung wurde erfolgreich erstellt',
                'order_id' => $orderId
            ];
        } catch (Throwable $e) {
            // Bei Fehlern alle Änderungen rückgängig machen
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return [
                'success' => false,
                'message' => 'Fehler beim Erstellen der Bestellung: ' . $e->getMessage()
            ];
        }
    }
    // --------------------------------------------------
}