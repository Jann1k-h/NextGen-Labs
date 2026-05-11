<?php

class CartController
{
    public function addToCart(): void
    {

        // data enthält die Daten, die der Client im JSON-Format gesendet hat, also z.B. identifier, password und rememberMe
        $data = json_decode(file_get_contents("php://input"), true);

        $courseId = trim($data['courseId'] ?? '');



        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }
}