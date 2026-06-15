<?php

class AdminVoucherService
{
    private AdminVoucherRepository $adminVoucherRepository;

    // --------------------------------------------------
    // Repository vorbereiten
    public function __construct()
    {
        // Repository erstellen, damit der Service auf Gutscheindaten zugreifen kann
        $this->adminVoucherRepository = new AdminVoucherRepository();
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Alle Gutscheine laden
    public function getAllVouchers(): array
    {
        // Alle Gutscheine aus der Datenbank laden
        return $this->adminVoucherRepository->findAll();
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Gutschein für Checkout prüfen
    public function checkVoucherForCheckout(string $code): array
    {
        // Gutschein anhand des Codes suchen
        $voucher = $this->adminVoucherRepository->findByCode($code);

        // Prüfen, ob der Gutschein existiert
        if (!$voucher) {
            return [
                'success' => false,
                'message' => 'Gutschein wurde nicht gefunden.'
            ];
        }

        // Prüfen, ob der Gutschein aktiv ist
        if ((int)$voucher['is_active'] !== 1) {
            return [
                'success' => false,
                'message' => 'Dieser Gutschein ist nicht aktiv.'
            ];
        }

        // Prüfen, ob der Gutschein abgelaufen ist
        if (!empty($voucher['valid_until']) && strtotime($voucher['valid_until']) < time()) {
            return [
                'success' => false,
                'message' => 'Dieser Gutschein ist abgelaufen.'
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
                'message' => 'Dieser Gutschein wurde bereits vollständig verwendet.'
            ];
        }

        // Checkout-Daten laden, damit der Rabatt berechnet werden kann
        $checkoutService = new CheckoutService();
        $checkoutData = $checkoutService->getCheckoutData();

        if (!$checkoutData['success']) {
            return $checkoutData;
        }

        // Zwischensumme aus dem Warenkorb holen
        $subtotal = (float)$checkoutData['total'];

        // Rabatt berechnen, je nachdem ob Prozent- oder Fixbetrag
        if ($voucher['discount_type'] === 'percent') {
            $discountAmount = $subtotal * ((float)$voucher['discount_value'] / 100);
        } else {
            $discountAmount = (float)$voucher['discount_value'];
        }

        // Rabatt darf nicht höher als die Zwischensumme sein
        if ($discountAmount > $subtotal) {
            $discountAmount = $subtotal;
        }

        // Endbetrag berechnen
        $finalTotal = $subtotal - $discountAmount;

        return [
            'success' => true,
            'message' => 'Gutschein wurde angewendet.',
            'voucher_id' => (int)$voucher['id'],
            'voucher_code' => $voucher['code'],
            'discount_type' => $voucher['discount_type'],
            'discount_value' => (float)$voucher['discount_value'],
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'final_total' => $finalTotal
        ];
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Gutschein erstellen
    public function createVoucher(array $data): array
    {
        // Gutscheindaten prüfen
        $validation = $this->validateVoucherData($data);

        if (!$validation['success']) {
            return $validation;
        }

        // Prüfen, ob der Gutscheincode bereits existiert
        if ($this->adminVoucherRepository->existsByCode($data['code'])) {
            return [
                'success' => false,
                'message' => 'Dieser Gutscheincode existiert bereits.'
            ];
        }

        // Gutscheindaten für die Datenbank vorbereiten
        $voucherData = [
            'code' => strtoupper(trim($data['code'])),
            'name' => trim($data['name']),
            'discount_type' => $data['discount_type'],
            'discount_value' => (float)$data['discount_value'],
            'valid_until' => $data['valid_until'] ?? null,
            'usage_limit' => $data['usage_limit'] ?? null,
            'is_active' => isset($data['is_active']) ? (int)$data['is_active'] : 1
        ];

        // Gutschein in der Datenbank erstellen
        $this->adminVoucherRepository->create($voucherData);

        return [
            'success' => true,
            'message' => 'Gutschein wurde erstellt.'
        ];
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Gutschein bearbeiten
    public function updateVoucher(int $id, array $data): array
    {
        // Prüfen, ob der Gutschein existiert
        $existingVoucher = $this->adminVoucherRepository->findById($id);

        if (!$existingVoucher) {
            return [
                'success' => false,
                'message' => 'Gutschein wurde nicht gefunden.'
            ];
        }

        // Gutscheindaten prüfen
        $validation = $this->validateVoucherData($data);

        if (!$validation['success']) {
            return $validation;
        }

        // Prüfen, ob der Gutscheincode schon bei einem anderen Gutschein verwendet wird
        if ($this->adminVoucherRepository->existsByCodeExceptId($data['code'], $id)) {
            return [
                'success' => false,
                'message' => 'Dieser Gutscheincode wird bereits verwendet.'
            ];
        }

        // Gutscheindaten für die Datenbank vorbereiten
        $voucherData = [
            'code' => strtoupper(trim($data['code'])),
            'name' => trim($data['name']),
            'discount_type' => $data['discount_type'],
            'discount_value' => (float)$data['discount_value'],
            'valid_until' => $data['valid_until'] ?? null,
            'usage_limit' => $data['usage_limit'] ?? null,
            'is_active' => isset($data['is_active']) ? (int)$data['is_active'] : 1
        ];

        // Gutschein in der Datenbank aktualisieren
        $this->adminVoucherRepository->update($id, $voucherData);

        return [
            'success' => true,
            'message' => 'Gutschein wurde aktualisiert.'
        ];
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Gutschein löschen
    public function deleteVoucher(int $id): array
    {
        // Prüfen, ob der Gutschein existiert
        $existingVoucher = $this->adminVoucherRepository->findById($id);

        if (!$existingVoucher) {
            return [
                'success' => false,
                'message' => 'Gutschein wurde nicht gefunden.'
            ];
        }

        // Gutschein aus der Datenbank löschen
        $this->adminVoucherRepository->delete($id);

        return [
            'success' => true,
            'message' => 'Gutschein wurde gelöscht.'
        ];
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Gutschein-Daten prüfen
    private function validateVoucherData(array $data): array
    {
        // Gutscheincode darf nicht leer sein
        if (empty($data['code'])) {
            return [
                'success' => false,
                'message' => 'Gutscheincode darf nicht leer sein.'
            ];
        }

        // Name darf nicht leer sein
        if (empty($data['name'])) {
            return [
                'success' => false,
                'message' => 'Name darf nicht leer sein.'
            ];
        }

        // Rabatt-Typ muss percent oder fixed sein
        if (empty($data['discount_type']) || !in_array($data['discount_type'], ['percent', 'fixed'])) {
            return [
                'success' => false,
                'message' => 'Ungültiger Rabatt-Typ.'
            ];
        }

        // Rabatt-Wert muss größer als 0 sein
        if (!isset($data['discount_value']) || (float)$data['discount_value'] <= 0) {
            return [
                'success' => false,
                'message' => 'Rabatt-Wert muss größer als 0 sein.'
            ];
        }

        // Prozent-Rabatt darf maximal 100 sein
        if ($data['discount_type'] === 'percent' && (float)$data['discount_value'] > 100) {
            return [
                'success' => false,
                'message' => 'Prozent-Rabatt darf maximal 100 sein.'
            ];
        }

        // Nutzungslimit darf nicht negativ sein
        if (isset($data['usage_limit']) && $data['usage_limit'] !== '' && (int)$data['usage_limit'] < 0) {
            return [
                'success' => false,
                'message' => 'Nutzungslimit darf nicht negativ sein.'
            ];
        }

        return [
            'success' => true
        ];
    }
    // --------------------------------------------------
}