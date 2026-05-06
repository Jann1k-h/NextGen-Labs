<?php

// zentrale Stelle für die Bearbeitung aller API-Requests

class ServiceHandler
{
    public function handle(string $module, string $action): void
    {
        switch ($module) {
            case 'auth':
                $controller = new AuthController();
                break;

            case 'nav':
                $controller = new NavController();
                break;

            case 'courses':
                $controller = new CoursesController();
                break;

            default:
                Response::json([
                    'success' => false,
                    'message' => 'Unbekanntes Modul'
                ], 404);
        }

        // Aufruf der entsprechenden Controller-Methode, wenn man zb von Login kommt ist action = login und dann wird die login Methode im AuthController aufgerufen
        $controller->$action();
    }
}