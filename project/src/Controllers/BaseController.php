<?php

class BaseController
{
    // --------------------------------------------------
    // Prüfen, ob User eingeloggt ist
    protected function requireLogin(): bool
    {
        // Wenn keine User-ID in der Session vorhanden ist, ist der User nicht eingeloggt
        if (!isset($_SESSION['user_id'])) {
            // HTTP-Status 401 = nicht authentifiziert
            http_response_code(401);

            // Fehlermeldung als JSON zurückgeben
            echo json_encode([
                'success' => false,
                'message' => 'Bitte anmelden.'
            ]);

            return false;
        }

        return true;
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Prüfen, ob User Admin ist
    protected function requireAdmin(): bool
    {
        // Wenn keine User-ID vorhanden ist oder is_admin nicht 1 ist, hat der User keine Adminrechte
        if (!isset($_SESSION['user_id']) || (int)($_SESSION['is_admin'] ?? 0) !== 1) {
            // HTTP-Status 403 = keine Berechtigung
            http_response_code(403);

            // Fehlermeldung als JSON zurückgeben
            echo json_encode([
                'success' => false,
                'message' => 'Keine Berechtigung.'
            ]);

            return false;
        }

        return true;
    }
    // --------------------------------------------------
}