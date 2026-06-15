<?php

class CartController
{
    // --------------------------------------------------
    // Kurs zum Warenkorb hinzufügen
    public function add(): void
    {
        // JSON-Daten aus dem Request lesen
        $data = json_decode(file_get_contents("php://input"), true);

        // Kurs-ID aus den Request-Daten holen
        $courseId = $data['course_id'];

        // Service erstellen und Kurs in den Warenkorb legen
        $cartService = new CartService();
        $result = $cartService->addToCart($courseId);

        // Ergebnis als JSON zurückgeben
        echo json_encode($result);
        exit;
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Warenkorb laden
    public function get(): void
    {
        // Service erstellen und Warenkorb laden
        $cartService = new CartService();
        $result = $cartService->getCart();

        // Ergebnis als JSON zurückgeben
        echo json_encode($result);
        exit;
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kurs aus Warenkorb entfernen
    public function remove(): void
    {
        // JSON-Daten aus dem Request lesen
        $data = json_decode(file_get_contents("php://input"), true);

        // Warenkorb-Eintrag-ID aus den Request-Daten holen
        $cartItemId = $data['cart_item_id'];

        // Service erstellen und Kurs aus dem Warenkorb entfernen
        $cartService = new CartService();
        $result = $cartService->removeFromCart($cartItemId);

        // Ergebnis als JSON zurückgeben
        echo json_encode($result);
        exit;
    }
    // --------------------------------------------------
}