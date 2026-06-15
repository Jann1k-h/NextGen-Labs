<?php

class AdminCustomerService
{
    private AdminCustomerRepository $customerRepository;

    // --------------------------------------------------
    // Repository vorbereiten
    public function __construct()
    {
        // Repository erstellen, damit der Service auf Kundendaten zugreifen kann
        $this->customerRepository = new AdminCustomerRepository();
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Alle Kunden laden
    public function getCustomers(): array
    {
        return [
            'success' => true,
            'customers' => $this->customerRepository->findAllCustomers()
        ];
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kundendaten aktualisieren
    public function updateCustomer(?array $data, int $currentUserId): array
    {
        // Prüfen, ob eine Kunden-ID übergeben wurde
        if (!$data || empty($data['id'])) {
            return [
                'success' => false,
                'message' => 'Keine gültige Kunden-ID erhalten.'
            ];
        }

        $customerId = (int)$data['id'];

        // Admin darf sein eigenes Konto hier nicht bearbeiten
        if ($customerId === $currentUserId) {
            return [
                'success' => false,
                'message' => 'Du kannst dein eigenes Konto hier nicht bearbeiten.'
            ];
        }

        // Prüfen, ob der Kunde existiert
        $customer = $this->customerRepository->findCustomerById($customerId);

        if (!$customer) {
            return [
                'success' => false,
                'message' => 'Kunde wurde nicht gefunden.'
            ];
        }

        // Kundendaten validieren
        $validation = $this->validateCustomerData($data, $customerId);

        if (!$validation['success']) {
            return $validation;
        }

        // Neues Passwort vorbereiten, falls eines eingegeben wurde
        $newPassword = trim($data['password'] ?? '');
        $hashedPassword = null;

        if ($newPassword !== '') {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        // Bereinigte Daten an das Repository übergeben und Kunde aktualisieren
        $this->customerRepository->updateCustomer($customerId, [
            'title' => trim($data['title']),
            'firstname' => trim($data['firstname']),
            'lastname' => trim($data['lastname']),
            'username' => trim($data['username']),
            'email' => trim($data['email']),
            'address' => trim($data['address']),
            'zipcode' => trim($data['zipcode']),
            'city' => trim($data['city']),
            'payment_info' => trim($data['payment_info'] ?? ''),
            'is_admin' => isset($data['is_admin']) ? (int)$data['is_admin'] : 0,
            'is_active' => isset($data['is_active']) ? (int)$data['is_active'] : 0,
            'password' => $hashedPassword
        ]);

        return [
            'success' => true,
            'message' => 'Kunde wurde aktualisiert.'
        ];
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kunden deaktivieren
    public function deactivateCustomer(?array $data, int $currentUserId): array
    {
        // Prüfen, ob eine Kunden-ID übergeben wurde
        if (!$data || empty($data['id'])) {
            return [
                'success' => false,
                'message' => 'Keine gültige Kunden-ID erhalten.'
            ];
        }

        $customerId = (int)$data['id'];

        // Admin darf sein eigenes Konto nicht deaktivieren
        if ($customerId === $currentUserId) {
            return [
                'success' => false,
                'message' => 'Du kannst dein eigenes Konto nicht deaktivieren.'
            ];
        }

        // Prüfen, ob der Kunde existiert
        $customer = $this->customerRepository->findCustomerById($customerId);

        if (!$customer) {
            return [
                'success' => false,
                'message' => 'Kunde wurde nicht gefunden.'
            ];
        }

        // Kunden in der Datenbank deaktivieren
        $this->customerRepository->deactivateCustomer($customerId);

        return [
            'success' => true,
            'message' => 'Kunde wurde deaktiviert.'
        ];
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Bestellungen eines Kunden laden
    public function getOrders(int $customerId): array
    {
        // Prüfen, ob eine gültige Kunden-ID übergeben wurde
        if ($customerId <= 0) {
            return [
                'success' => false,
                'message' => 'Keine gültige Kunden-ID erhalten.'
            ];
        }

        // Prüfen, ob der Kunde existiert
        $customer = $this->customerRepository->findCustomerById($customerId);

        if (!$customer) {
            return [
                'success' => false,
                'message' => 'Kunde wurde nicht gefunden.'
            ];
        }

        // Bestellungen des Kunden zurückgeben
        return [
            'success' => true,
            'orders' => $this->customerRepository->findOrdersByCustomerId($customerId)
        ];
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kundendaten validieren
    private function validateCustomerData(array $data, int $customerId): array
    {
        // Prüfen, ob alle Pflichtfelder ausgefüllt wurden
        if (
            empty($data['title']) ||
            empty($data['firstname']) ||
            empty($data['lastname']) ||
            empty($data['username']) ||
            empty($data['email']) ||
            empty($data['address']) ||
            empty($data['zipcode']) ||
            empty($data['city'])
        ) {
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

        // Prüfen, ob der Benutzername bereits von einem anderen User verwendet wird
        if ($this->customerRepository->existsUsernameExceptId($data['username'], $customerId)) {
            return [
                'success' => false,
                'message' => 'Dieser Benutzername wird bereits verwendet.'
            ];
        }

        // Prüfen, ob die E-Mail bereits von einem anderen User verwendet wird
        if ($this->customerRepository->existsEmailExceptId($data['email'], $customerId)) {
            return [
                'success' => false,
                'message' => 'Diese E-Mail-Adresse wird bereits verwendet.'
            ];
        }

        // Neues Passwort prüfen, falls eines eingegeben wurde
        $newPassword = trim($data['password'] ?? '');

        if ($newPassword !== '' && strlen($newPassword) < 6) {
            return [
                'success' => false,
                'message' => 'Das neue Passwort muss mindestens 6 Zeichen lang sein.'
            ];
        }

        return [
            'success' => true
        ];
    }
    // --------------------------------------------------
}