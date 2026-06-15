<?php

class CheckoutController extends BaseController
{
    // --------------------------------------------------
    // Prüfen, ob Checkout erlaubt ist
    public function checkCheckout(): void
    {
        if (!$this->requireLogin()) {
            return;
        }

        // Service erstellen und prüfen, ob der User zur Kasse gehen darf
        $checkoutService = new CheckoutService();
        $result = $checkoutService->checkCheckout();

        // Ergebnis als JSON zurückgeben
        echo json_encode($result);
        exit;
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Checkout-Daten laden
    public function getData(): void
    {
        if (!$this->requireLogin()) {
            return;
        }

        // Service erstellen und alle Daten für die Checkout-Seite laden
        $checkoutService = new CheckoutService();
        $result = $checkoutService->getCheckoutData();

        // Ergebnis als JSON zurückgeben
        echo json_encode($result);
        exit;
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Gutschein prüfen
    public function checkVoucher(): void
    {
        if (!$this->requireLogin()) {
            return;
        }

        // JSON-Daten aus dem Request lesen
        $input = json_decode(file_get_contents('php://input'), true);

        // Gutscheincode aus den Request-Daten holen
        $voucherCode = $input['voucher_code'] ?? '';

        // Service erstellen und Gutschein prüfen
        $checkoutService = new CheckoutService();
        $result = $checkoutService->checkVoucher($voucherCode);

        // Ergebnis als JSON zurückgeben
        echo json_encode($result);
        exit;
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Bestellung abschließen
    public function placeOrder(): void
    {
        if (!$this->requireLogin()) {
            return;
        }

        // JSON-Daten aus dem Request lesen
        $input = json_decode(file_get_contents('php://input'), true);

        // Service erstellen und Bestellung speichern
        $checkoutService = new CheckoutService();
        $result = $checkoutService->placeOrder($input);

        // Ergebnis als JSON zurückgeben
        echo json_encode($result);
        exit;
    }
    // --------------------------------------------------
}