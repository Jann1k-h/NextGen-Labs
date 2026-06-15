<?php

class AccountService
{
    private AccountRepository $accountRepository;

    // --------------------------------------------------
    // Repository vorbereiten
    public function __construct()
    {
        // Repository erstellen, damit der Service auf Account-Daten zugreifen kann
        $this->accountRepository = new AccountRepository();
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kontodaten laden
    public function getAccount(int $userId): array
    {
        // User anhand der ID aus der Datenbank laden
        $user = $this->accountRepository->findUserById($userId);

        // Prüfen, ob der User gefunden wurde
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Benutzer wurde nicht gefunden.'
            ];
        }

        // Userdaten zurückgeben
        return [
            'success' => true,
            'user' => $user
        ];
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kontodaten aktualisieren
    public function updateAccount(int $userId, ?array $data): array
    {
        // Prüfen, ob gültige Daten empfangen wurden
        if (!$data) {
            return [
                'success' => false,
                'message' => 'Keine gültigen Daten erhalten.'
            ];
        }

        // User inklusive Passwort laden, damit das aktuelle Passwort geprüft werden kann
        $user = $this->accountRepository->findUserWithPasswordById($userId);

        // Prüfen, ob der User gefunden wurde
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Benutzer wurde nicht gefunden.'
            ];
        }

        // Prüfen, ob alle Pflichtfelder ausgefüllt wurden
        if (!$this->hasRequiredFields($data)) {
            return [
                'success' => false,
                'message' => 'Bitte fülle alle Pflichtfelder aus.'
            ];
        }

        // Prüfen, ob die Anrede erlaubt ist
        if (!in_array($data['title'], ['Herr', 'Frau'])) {
            return [
                'success' => false,
                'message' => 'Ungültige Anrede.'
            ];
        }

        // E-Mail-Adresse validieren
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'message' => 'Bitte gib eine gültige E-Mail-Adresse ein.'
            ];
        }

        // Aktuelles Passwort prüfen, bevor die Kontodaten geändert werden
        if (!password_verify($data['current_password'], $user['password'])) {
            return [
                'success' => false,
                'message' => 'Das eingegebene Passwort ist falsch.'
            ];
        }

        // Prüfen, ob der Benutzername bereits von einem anderen User verwendet wird
        if ($this->accountRepository->existsUsernameExceptId($data['username'], $userId)) {
            return [
                'success' => false,
                'message' => 'Dieser Benutzername wird bereits verwendet.'
            ];
        }

        // Prüfen, ob die E-Mail bereits von einem anderen User verwendet wird
        if ($this->accountRepository->existsEmailExceptId($data['email'], $userId)) {
            return [
                'success' => false,
                'message' => 'Diese E-Mail-Adresse wird bereits verwendet.'
            ];
        }

        // Bereinigte Daten an das Repository übergeben und User aktualisieren
        $this->accountRepository->updateUser($userId, [
            'title' => trim($data['title']),
            'firstname' => trim($data['firstname']),
            'lastname' => trim($data['lastname']),
            'username' => trim($data['username']),
            'email' => trim($data['email']),
            'address' => trim($data['address']),
            'zipcode' => trim($data['zipcode']),
            'city' => trim($data['city']),
            'payment_info' => trim($data['payment_info'] ?? '')
        ]);

        // Erfolgreiche Aktualisierung zurückgeben
        return [
            'success' => true,
            'message' => 'Kontodaten wurden aktualisiert.'
        ];
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Pflichtfelder prüfen
    private function hasRequiredFields(array $data): bool
    {
        // Nur true zurückgeben, wenn alle Pflichtfelder vorhanden und nicht leer sind
        return !empty($data['title'])
            && !empty($data['firstname'])
            && !empty($data['lastname'])
            && !empty($data['username'])
            && !empty($data['email'])
            && !empty($data['address'])
            && !empty($data['zipcode'])
            && !empty($data['city'])
            && !empty($data['current_password']);
    }
    // --------------------------------------------------
}