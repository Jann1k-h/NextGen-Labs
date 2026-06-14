<?php

// zentrale Stelle für die Bearbeitung aller API-Requests

class ServiceHandler
{
    public function handle(string $module, string $action): void
    {
        header('Content-Type: application/json');

        // Aufruf der entsprechenden Controller-Methode, wenn man zb von Login kommt ist action = login und dann wird die login Methode im AuthController aufgerufen
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

            case 'cart':
                $controller = new CartController();
                break;

            case 'checkout':
                $controller = new CheckoutController();
                break;

            case 'voucher':
                $controller = new VoucherController();
                break;

            default:
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Unbekanntes Modul'
                ]);
                exit;
        }

        if (!method_exists($controller, $action)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Unbekannte Aktion'
            ]);
            exit;
        }

        $controller->$action();
    }
}