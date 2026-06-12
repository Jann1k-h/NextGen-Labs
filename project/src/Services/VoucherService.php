<?php

class VoucherService
{
    // --------------------------------------------------
    // Alle Gutscheine laden
    public function getAllVouchers(): array
    {
        $voucherRepository = new VoucherRepository();
        return $voucherRepository->findAll();
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Gutschein für Checkout prüfen
    public function checkVoucherForCheckout(string $code): array
    {
        $voucherRepository = new VoucherRepository();
        $voucher = $voucherRepository->findByCode($code);

        if (!$voucher) {
            return [
                'success' => false,
                'message' => 'Gutschein wurde nicht gefunden.'
            ];
        }

        if ((int)$voucher['is_active'] !== 1) {
            return [
                'success' => false,
                'message' => 'Dieser Gutschein ist nicht aktiv.'
            ];
        }

        if (!empty($voucher['valid_until']) && strtotime($voucher['valid_until']) < time()) {
            return [
                'success' => false,
                'message' => 'Dieser Gutschein ist abgelaufen.'
            ];
        }

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

        $checkoutService = new CheckoutService();
        $checkoutData = $checkoutService->getCheckoutData();

        if (!$checkoutData['success']) {
            return $checkoutData;
        }

        $subtotal = (float)$checkoutData['total'];

        if ($voucher['discount_type'] === 'percent') {
            $discountAmount = $subtotal * ((float)$voucher['discount_value'] / 100);
        } else {
            $discountAmount = (float)$voucher['discount_value'];
        }

        if ($discountAmount > $subtotal) {
            $discountAmount = $subtotal;
        }

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
        $validation = $this->validateVoucherData($data);

        if (!$validation['success']) {
            return $validation;
        }

        $voucherRepository = new VoucherRepository();

        if ($voucherRepository->existsByCode($data['code'])) {
            return [
                'success' => false,
                'message' => 'Dieser Gutscheincode existiert bereits.'
            ];
        }

        $voucherData = [
            'code' => strtoupper(trim($data['code'])),
            'name' => trim($data['name']),
            'discount_type' => $data['discount_type'],
            'discount_value' => (float)$data['discount_value'],
            'valid_until' => $data['valid_until'] ?? null,
            'usage_limit' => $data['usage_limit'] ?? null,
            'is_active' => isset($data['is_active']) ? (int)$data['is_active'] : 1
        ];

        $voucherRepository->create($voucherData);

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
        $voucherRepository = new VoucherRepository();
        $existingVoucher = $voucherRepository->findById($id);

        if (!$existingVoucher) {
            return [
                'success' => false,
                'message' => 'Gutschein wurde nicht gefunden.'
            ];
        }

        $validation = $this->validateVoucherData($data);

        if (!$validation['success']) {
            return $validation;
        }

        if ($voucherRepository->existsByCodeExceptId($data['code'], $id)) {
            return [
                'success' => false,
                'message' => 'Dieser Gutscheincode wird bereits verwendet.'
            ];
        }

        $voucherData = [
            'code' => strtoupper(trim($data['code'])),
            'name' => trim($data['name']),
            'discount_type' => $data['discount_type'],
            'discount_value' => (float)$data['discount_value'],
            'valid_until' => $data['valid_until'] ?? null,
            'usage_limit' => $data['usage_limit'] ?? null,
            'is_active' => isset($data['is_active']) ? (int)$data['is_active'] : 1
        ];

        $voucherRepository->update($id, $voucherData);

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
        $voucherRepository = new VoucherRepository();
        $existingVoucher = $voucherRepository->findById($id);

        if (!$existingVoucher) {
            return [
                'success' => false,
                'message' => 'Gutschein wurde nicht gefunden.'
            ];
        }

        $voucherRepository->delete($id);

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
        if (empty($data['code'])) {
            return [
                'success' => false,
                'message' => 'Gutscheincode darf nicht leer sein.'
            ];
        }

        if (empty($data['name'])) {
            return [
                'success' => false,
                'message' => 'Name darf nicht leer sein.'
            ];
        }

        if (empty($data['discount_type']) || !in_array($data['discount_type'], ['percent', 'fixed'])) {
            return [
                'success' => false,
                'message' => 'Ungültiger Rabatt-Typ.'
            ];
        }

        if (!isset($data['discount_value']) || (float)$data['discount_value'] <= 0) {
            return [
                'success' => false,
                'message' => 'Rabatt-Wert muss größer als 0 sein.'
            ];
        }

        if ($data['discount_type'] === 'percent' && (float)$data['discount_value'] > 100) {
            return [
                'success' => false,
                'message' => 'Prozent-Rabatt darf maximal 100 sein.'
            ];
        }

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