<?php

class VoucherController
{
    // --------------------------------------------------
    // Alle Gutscheine laden
    public function get(): void
    {
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