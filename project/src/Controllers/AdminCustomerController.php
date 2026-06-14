<?php

class AdminCustomerController
{
    // --------------------------------------------------
    // Prüfen, ob User Admin ist
    private function requireAdmin(): bool
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


    // --------------------------------------------------
    // Kundenliste laden
    public function get(): void
    {
        header('Content-Type: application/json');

        if (!$this->requireAdmin()) {
            return;
        }

        $customerRepository = new AdminCustomerRepository();
        $customers = $customerRepository->findAllCustomers();

        echo json_encode([
            'success' => true,
            'customers' => $customers
        ]);
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kunde bearbeiten
    public function update(): void
    {
        header('Content-Type: application/json');

        if (!$this->requireAdmin()) {
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || empty($data['id'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Keine gültige Kunden-ID erhalten.'
            ]);
            return;
        }

        $customerId = (int)$data['id'];

        if ($customerId === (int)$_SESSION['user_id']) {
            echo json_encode([
                'success' => false,
                'message' => 'Du kannst dein eigenes Konto hier nicht bearbeiten.'
            ]);
            return;
        }

        $customerRepository = new AdminCustomerRepository();
        $customer = $customerRepository->findCustomerById($customerId);

        if (!$customer) {
            echo json_encode([
                'success' => false,
                'message' => 'Kunde wurde nicht gefunden.'
            ]);
            return;
        }

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
            echo json_encode([
                'success' => false,
                'message' => 'Bitte fülle alle Pflichtfelder aus.'
            ]);
            return;
        }

        if (!in_array($data['title'], ['Herr', 'Frau'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Ungültige Anrede.'
            ]);
            return;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                'success' => false,
                'message' => 'Bitte gib eine gültige E-Mail-Adresse ein.'
            ]);
            return;
        }

        if ($customerRepository->existsUsernameExceptId($data['username'], $customerId)) {
            echo json_encode([
                'success' => false,
                'message' => 'Dieser Benutzername wird bereits verwendet.'
            ]);
            return;
        }

        if ($customerRepository->existsEmailExceptId($data['email'], $customerId)) {
            echo json_encode([
                'success' => false,
                'message' => 'Diese E-Mail-Adresse wird bereits verwendet.'
            ]);
            return;
        }

        $newPassword = trim($data['password'] ?? '');

        if ($newPassword !== '' && strlen($newPassword) < 6) {
            echo json_encode([
                'success' => false,
                'message' => 'Das neue Passwort muss mindestens 6 Zeichen lang sein.'
            ]);
            return;
        }

        $hashedPassword = null;

        if ($newPassword !== '') {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $customerRepository->updateCustomer($customerId, [
            'title' => $data['title'],
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'username' => $data['username'],
            'email' => $data['email'],
            'address' => $data['address'],
            'zipcode' => $data['zipcode'],
            'city' => $data['city'],
            'payment_info' => $data['payment_info'] ?? null,
            'is_admin' => isset($data['is_admin']) ? (int)$data['is_admin'] : 0,
            'is_active' => isset($data['is_active']) ? (int)$data['is_active'] : 0,
            'password' => $hashedPassword
        ]);

        echo json_encode([
            'success' => true,
            'message' => 'Kunde wurde aktualisiert.'
        ]);
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kunde deaktivieren
    public function deactivate(): void
    {
        header('Content-Type: application/json');

        if (!$this->requireAdmin()) {
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || empty($data['id'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Keine gültige Kunden-ID erhalten.'
            ]);
            return;
        }

        $customerId = (int)$data['id'];

        if ($customerId === (int)$_SESSION['user_id']) {
            echo json_encode([
                'success' => false,
                'message' => 'Du kannst dein eigenes Konto nicht deaktivieren.'
            ]);
            return;
        }

        $customerRepository = new AdminCustomerRepository();
        $customer = $customerRepository->findCustomerById($customerId);

        if (!$customer) {
            echo json_encode([
                'success' => false,
                'message' => 'Kunde wurde nicht gefunden.'
            ]);
            return;
        }

        $customerRepository->deactivateCustomer($customerId);

        echo json_encode([
            'success' => true,
            'message' => 'Kunde wurde deaktiviert.'
        ]);
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Bestellungen eines Kunden anzeigen
    public function getOrders(): void
    {
        header('Content-Type: application/json');

        if (!$this->requireAdmin()) {
            return;
        }

        $customerId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($customerId <= 0) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Keine gültige Kunden-ID erhalten.'
            ]);
            return;
        }

        $customerRepository = new AdminCustomerRepository();
        $customer = $customerRepository->findCustomerById($customerId);

        if (!$customer) {
            echo json_encode([
                'success' => false,
                'message' => 'Kunde wurde nicht gefunden.'
            ]);
            return;
        }

        $orders = $customerRepository->findOrdersByCustomerId($customerId);

        echo json_encode([
            'success' => true,
            'orders' => $orders
        ]);
    }
    // --------------------------------------------------
}