<?php

class CheckoutController
{
    public function check(): void
    {
        header('Content-Type: application/json');

        $checkoutService = new CheckoutService();
        $result = $checkoutService->checkCheckoutAllowed();

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
}