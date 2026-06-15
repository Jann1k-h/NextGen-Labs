<?php

class AdminCustomerController extends BaseController
{
    // --------------------------------------------------
    // Alle Kunden für Admin laden
    public function get(): void
    {
        // Prüfen, ob User Admin ist
        if (!$this->requireAdmin()) {
            return;
        }

        // Service erstellen und Kunden laden
        $service = new AdminCustomerService();

        // Ergebnis als JSON zurückgeben
        echo json_encode($service->getCustomers());
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kunde aktualisieren
    public function update(): void
    {
        // Prüfen, ob User Admin ist
        if (!$this->requireAdmin()) {
            return;
        }

        // JSON-Daten aus dem Request lesen
        $data = json_decode(file_get_contents('php://input'), true);

        // Aktuelle Admin-User-ID aus der Session holen
        $currentUserId = (int)$_SESSION['user_id'];

        // Service erstellen und Kunde aktualisieren
        $service = new AdminCustomerService();
        $result = $service->updateCustomer($data, $currentUserId);

        // Ergebnis als JSON zurückgeben
        echo json_encode($result);
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kunde deaktivieren
    public function deactivate(): void
    {
        // Prüfen, ob User Admin ist
        if (!$this->requireAdmin()) {
            return;
        }

        // JSON-Daten aus dem Request lesen
        $data = json_decode(file_get_contents('php://input'), true);

        // Aktuelle Admin-User-ID aus der Session holen
        $currentUserId = (int)$_SESSION['user_id'];

        // Service erstellen und Kunde deaktivieren
        $service = new AdminCustomerService();
        $result = $service->deactivateCustomer($data, $currentUserId);

        // Ergebnis als JSON zurückgeben
        echo json_encode($result);
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Bestellungen eines Kunden laden
    public function getOrders(): void
    {
        // Prüfen, ob User Admin ist
        if (!$this->requireAdmin()) {
            return;
        }

        // Kunden-ID aus URL lesen
        $customerId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        // Service erstellen und Bestellungen laden
        $service = new AdminCustomerService();
        $result = $service->getOrders($customerId);

        // Ergebnis als JSON zurückgeben
        echo json_encode($result);
    }
    // --------------------------------------------------
}