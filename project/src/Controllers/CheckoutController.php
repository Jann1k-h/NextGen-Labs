<?php

class CheckoutController
{
    public function checkCheckout(): void
    {
        header('Content-Type: application/json');

        $checkoutService = new CheckoutService();
        $result = $checkoutService->checkCheckout();

        echo json_encode($result);
        exit;
    }

    public function getData(): void
    {
        header('Content-Type: application/json');

        $checkoutService = new CheckoutService();
        $result = $checkoutService->getCheckoutData();

        echo json_encode($result);
        exit;
    }

    public function checkVoucher(): void
    {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        $voucherCode = $input['voucher_code'] ?? '';

        $checkoutService = new CheckoutService();
        $result = $checkoutService->checkVoucher($voucherCode);

        echo json_encode($result);
        exit;
    }

    public function placeOrder(): void
    {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);

        $checkoutService = new CheckoutService();
        $result = $checkoutService->placeOrder($input);

        echo json_encode($result);
        exit;
    }
}