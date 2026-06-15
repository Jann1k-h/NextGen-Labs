<?php

class BaseController
{
    // --------------------------------------------------
    // Prüfen, ob User eingeloggt ist
    protected function requireLogin(): bool
    {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
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
        if (!isset($_SESSION['user_id']) || (int)($_SESSION['is_admin'] ?? 0) !== 1) {
            http_response_code(403);
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