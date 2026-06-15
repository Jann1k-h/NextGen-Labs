<?php

class AdminVoucherController extends BaseController
{
    // --------------------------------------------------
    // Alle Gutscheine laden
    public function get(): void
    {
        // Prüfen, ob User Admin ist
        if (!$this->requireAdmin()) {
            return;
        }

        // Service erstellen und Gutscheine laden
        $voucherService = new AdminVoucherService();
        $vouchers = $voucherService->getAllVouchers();

        // Ergebnis als JSON zurückgeben
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
        // Prüfen, ob User Admin ist
        if (!$this->requireAdmin()) {
            return;
        }

        // JSON-Daten aus dem Request lesen
        $data = json_decode(file_get_contents('php://input'), true);

        // Prüfen, ob gültige Daten erhalten wurden
        if (!$data) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Keine gültigen Daten erhalten.'
            ]);
            return;
        }

        // Service erstellen und Gutschein erstellen
        $voucherService = new AdminVoucherService();
        $result = $voucherService->createVoucher($data);

        // Ergebnis als JSON zurückgeben
        echo json_encode($result);
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Gutschein aktualisieren
    public function update(): void
    {
        // Prüfen, ob User Admin ist
        if (!$this->requireAdmin()) {
            return;
        }

        // JSON-Daten aus dem Request lesen
        $data = json_decode(file_get_contents('php://input'), true);

        // Prüfen, ob gültige Gutschein-ID erhalten wurde
        if (!$data || empty($data['id'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Keine gültige Gutschein-ID erhalten.'
            ]);
            return;
        }

        // Gutschein-ID aus Daten holen und aus Update-Daten entfernen
        $id = (int)$data['id'];
        unset($data['id']);

        // Service erstellen und Gutschein aktualisieren
        $voucherService = new AdminVoucherService();
        $result = $voucherService->updateVoucher($id, $data);

        // Ergebnis als JSON zurückgeben
        echo json_encode($result);
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Gutschein löschen
    public function delete(): void
    {
        // Prüfen, ob User Admin ist
        if (!$this->requireAdmin()) {
            return;
        }

        // JSON-Daten aus dem Request lesen
        $data = json_decode(file_get_contents('php://input'), true);

        // Prüfen, ob gültige Gutschein-ID erhalten wurde
        if (!$data || empty($data['id'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Keine gültige Gutschein-ID erhalten.'
            ]);
            return;
        }

        // Service erstellen und Gutschein löschen
        $voucherService = new AdminVoucherService();
        $result = $voucherService->deleteVoucher((int)$data['id']);

        // Ergebnis als JSON zurückgeben
        echo json_encode($result);
    }
    // --------------------------------------------------
}