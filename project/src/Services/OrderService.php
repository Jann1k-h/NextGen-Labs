<?php

class OrderService
{
    private OrderRepository $orderRepository;

    // --------------------------------------------------
    // Repository vorbereiten
    public function __construct()
    {
        // Repository erstellen, damit der Service auf Bestelldaten zugreifen kann
        $this->orderRepository = new OrderRepository();
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Bestelldetails laden
    public function getOrderDetails(int $orderId, int $userId): array
    {
        // Prüfen, ob eine gültige Bestell-ID übergeben wurde
        if ($orderId <= 0) {
            return [
                'success' => false,
                'message' => 'Keine gültige Bestell-ID erhalten'
            ];
        }

        // Bestelldetails für diese Bestellung und diesen User laden
        $details = $this->orderRepository->getOrderDetailsById($orderId, $userId);

        // Prüfen, ob die Bestellung gefunden wurde
        if (!$details) {
            return [
                'success' => false,
                'message' => 'Bestellung wurde nicht gefunden'
            ];
        }

        // Bestellung und Bestellpositionen zurückgeben
        return [
            'success' => true,
            'order' => $details['order'],
            'items' => $details['items']
        ];
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Bestellhistorie laden
    public function getOrderHistory(int $userId): array
    {
        // Prüfen, ob eine gültige User-ID übergeben wurde
        if ($userId <= 0) {
            return [
                'success' => false,
                'message' => 'Keine gültige Benutzer-ID erhalten.',
                'orders' => []
            ];
        }

        // Alle Bestellungen des Users laden und zurückgeben
        return [
            'success' => true,
            'orders' => $this->orderRepository->getOrdersByUserId($userId)
        ];
    }
    // --------------------------------------------------
}