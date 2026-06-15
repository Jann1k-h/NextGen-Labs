<?php

class AccountController extends BaseController
{
    // --------------------------------------------------
    // Eigene Kontodaten laden
    public function get(): void
    {
        // Prüfen, ob User eingeloggt ist
        if (!$this->requireLogin()) {
            return;
        }

        // User-ID aus der Session holen
        $userId = (int)$_SESSION['user_id'];

        // Service erstellen und Kontodaten laden
        $accountService = new AccountService();
        $result = $accountService->getAccount($userId);

        // Ergebnis als JSON zurückgeben
        echo json_encode($result);
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Eigene Kontodaten aktualisieren
    public function update(): void
    {
        // Prüfen, ob User eingeloggt ist
        if (!$this->requireLogin()) {
            return;
        }

        // User-ID aus der Session holen
        $userId = (int)$_SESSION['user_id'];

        // JSON-Daten aus dem Request lesen
        $data = json_decode(file_get_contents('php://input'), true);

        // Service erstellen und Kontodaten aktualisieren
        $accountService = new AccountService();
        $result = $accountService->updateAccount($userId, $data);

        // Username in der Session aktualisieren, falls Änderung erfolgreich war
        if ($result['success']) {
            $_SESSION['username'] = trim($data['username']);
        }

        // Ergebnis als JSON zurückgeben
        echo json_encode($result);
    }
    // --------------------------------------------------
}