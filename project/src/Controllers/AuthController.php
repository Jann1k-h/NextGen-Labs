<?php

// Controller liest Request und ruft den Service auf

class AuthController
{
    private AuthService $authService;

    // Funktion wird automatisch aufgerufen, sobald ein Objekt der Klasse erstellt wird, wie zb in Zeile 25 oder 46
    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function login(): void
    {

        // data enthält die Daten, die der Client im JSON-Format gesendet hat, also z.B. identifier, password und rememberMe
        $data = json_decode(file_get_contents("php://input"), true);

        $identifier = trim($data['identifier'] ?? '');
        $password = $data['password'] ?? '';
        $rememberMe = isset($data['rememberMe']) ? (bool)$data['rememberMe'] : false;

        $result = $this->authService->login($identifier, $password, $rememberMe);

        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }

    public function register(): void
    {
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

        $result = $this->authService->register($title, $firstname, $lastname, $username, $address, $zipcode, $city, $email, $password, $confirmPassword, $paymentInfo);
        
        header('Content-Type: application/json');
        echo json_encode($result);
        exit;

    }

    public function logout(): void
    {
        $result = $this->authService->logout();

        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }
}