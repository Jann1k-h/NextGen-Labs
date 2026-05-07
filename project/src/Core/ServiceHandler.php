<?php

// zentrale Stelle für die Bearbeitung aller API-Requests

class ServiceHandler
{
    public function handle(string $module, string $action): void
    {
        switch ($module) {
            case 'auth':
                $controller = new AuthController();

                // Aufruf der entsprechenden Controller-Methode, wenn man zb von Login kommt ist action = login und dann wird die login Methode im AuthController aufgerufen
                $controller->$action();
                break;

            case 'nav':
                $controller = new NavController();

                // Aufruf der entsprechenden Controller-Methode, wenn man zb von Login kommt ist action = login und dann wird die login Methode im AuthController aufgerufen
                $controller->$action();
                break;

            case 'courses':
                $controller = new CoursesController();

                // Aufruf der entsprechenden Controller-Methode, wenn man zb von Login kommt ist action = login und dann wird die login Methode im AuthController aufgerufen
                $controller->$action();
                break;

            default:
                http_response_code(400);
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Unbekanntes Modul'
                ]);
                exit;
        }
        
    }
}