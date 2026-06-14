<?php

class VoucherController
{
    // --------------------------------------------------
    // Prüfen, ob User Admin ist
    private function requireAdmin(): bool
    {
        if (!isset($_SESSION['user_id']) || (int)($_SESSION['is_admin'] ?? 0) !== 1) {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'message' => 'Keine Berechtigung.'
            ]);
            return false;
        }

        return true;
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Alle Gutscheine laden
    public function get(): void
    {
        header('Content-Type: application/json');

        if (!$this->requireAdmin()) {
            return;
        }

        $voucherService = new VoucherService();
        $vouchers = $voucherService->getAllVouchers();

        echo json_encode([
            'success' => true,
            'vouchers' => $vouchers
        ]);
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Gutschein erstellen
    public function create(): void
    {
        header('Content-Type: application/json');

        if (!$this->requireAdmin()) {
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Keine gültigen Daten erhalten.'
            ]);
            return;
        }

        $voucherService = new VoucherService();
        $result = $voucherService->createVoucher($data);

        echo json_encode($result);
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Gutschein bearbeiten
    public function update(): void
    {
        header('Content-Type: application/json');

        if (!$this->requireAdmin()) {
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || empty($data['id'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Keine gültige Gutschein-ID erhalten.'
            ]);
            return;
        }

        $id = (int)$data['id'];
        unset($data['id']);

        $voucherService = new VoucherService();
        $result = $voucherService->updateVoucher($id, $data);

        echo json_encode($result);
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Gutschein löschen
    public function delete(): void
    {
        header('Content-Type: application/json');

        if (!$this->requireAdmin()) {
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || empty($data['id'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Keine gültige Gutschein-ID erhalten.'
            ]);
            return;
        }

        $voucherService = new VoucherService();
        $result = $voucherService->deleteVoucher((int)$data['id']);

        echo json_encode($result);
    }
    // --------------------------------------------------
}