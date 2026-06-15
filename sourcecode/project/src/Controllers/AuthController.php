<?php

// Controller liest Request und ruft den Service auf

class AuthController
{
    // --------------------------------------------------
    // User einloggen
    public function login(): void
    {
        // JSON-Daten aus dem Request lesen
        // data enthält z.B. identifier, password und rememberMe
        $data = json_decode(file_get_contents("php://input"), true);

        // Eingaben auslesen und vorbereiten
        $identifier = trim($data['identifier'] ?? '');
        $password = $data['password'] ?? '';
        $rememberMe = isset($data['rememberMe']) ? (bool)$data['rememberMe'] : false;

        // Service erstellen und Login durchführen
        $authService = new AuthService();
        $result = $authService->login($identifier, $password, $rememberMe);

        // Ergebnis als JSON zurückgeben
        echo json_encode($result);
        exit;
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // User registrieren
    public function register(): void
    {
        // JSON-Daten aus dem Request lesen
        // data enthält die Registrierungsdaten des Users
        $data = json_decode(file_get_contents("php://input"), true);

        // Eingaben auslesen und vorbereiten
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

        // Service erstellen und Registrierung durchführen
        $authService = new AuthService();
        $result = $authService->register(
            $title,
            $firstname,
            $lastname,
            $username,
            $address,
            $zipcode,
            $city,
            $email,
            $password,
            $confirmPassword,
            $paymentInfo
        );

        // Ergebnis als JSON zurückgeben
        echo json_encode($result);
        exit;
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // User ausloggen
    public function logout(): void
    {
        // Service erstellen und Logout durchführen
        $authService = new AuthService();
        $result = $authService->logout();

        // Ergebnis als JSON zurückgeben
        echo json_encode($result);
        exit;
    }
    // --------------------------------------------------
}