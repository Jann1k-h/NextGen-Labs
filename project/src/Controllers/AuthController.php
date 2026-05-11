<?php

// Controller liest Request und ruft den Service auf

class AuthController
{
    public function login(): void
    {

        // data enthält die Daten, die der Client im JSON-Format gesendet hat, also z.B. identifier, password und rememberMe
        $data = json_decode(file_get_contents("php://input"), true);

        $identifier = trim($data['identifier'] ?? '');
        $password = $data['password'] ?? '';
        $rememberMe = isset($data['rememberMe']) ? (bool)$data['rememberMe'] : false;

        $authService = new AuthService();
        $result = $authService->login($identifier, $password, $rememberMe);

        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }

    public function register(): void
    {

        // data enthält die Daten, die der Client im JSON-Format gesendet hat, also z.B. identifier, password und rememberMe
        $data = json_decode(file_get_contents("php://input"), true);

        $title = trim($data['title'] ?? '');
        $firstname = trim($data['firstname'] ?? '');
        $lastname = trim($data['lastname'] ?? '');
        $username = trim($data['username'] ?? '');
        $address = trim($data['address'] ?? '');
        $zipcode = trim($data['zipcode'] ?? '');
        $city = trim($data['city'] ?? '');
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';
        $confirmPassword = $data['confirmPassword'] ?? '';
        $paymentInfo = trim($data['paymentInfo'] ?? '');

        $authService = new AuthService();
        $result = $authService->register($title, $firstname, $lastname, $username, $address, $zipcode, $city, $email, $password, $confirmPassword, $paymentInfo);
        
        header('Content-Type: application/json');
        echo json_encode($result);
        exit;

    }

    public function logout(): void
    {
        $authService = new AuthService();
        $result = $authService->logout();

        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }
}