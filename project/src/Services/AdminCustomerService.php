<?php

class AdminCustomerService
{
    private AdminCustomerRepository $customerRepository;

    public function __construct()
    {
        $this->customerRepository = new AdminCustomerRepository();
    }

    public function getCustomers(): array
    {
        return [
            'success' => true,
            'customers' => $this->customerRepository->findAllCustomers()
        ];
    }

    public function updateCustomer(?array $data, int $currentUserId): array
    {
        if (!$data || empty($data['id'])) {
            return [
                'success' => false,
                'message' => 'Keine gültige Kunden-ID erhalten.'
            ];
        }

        $customerId = (int)$data['id'];

        if ($customerId === $currentUserId) {
            return [
                'success' => false,
                'message' => 'Du kannst dein eigenes Konto hier nicht bearbeiten.'
            ];
        }

        $customer = $this->customerRepository->findCustomerById($customerId);

        if (!$customer) {
            return [
                'success' => false,
                'message' => 'Kunde wurde nicht gefunden.'
            ];
        }

        $validation = $this->validateCustomerData($data, $customerId);

        if (!$validation['success']) {
            return $validation;
        }

        $newPassword = trim($data['password'] ?? '');
        $hashedPassword = null;

        if ($newPassword !== '') {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        }

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

    public function deactivateCustomer(?array $data, int $currentUserId): array
    {
        if (!$data || empty($data['id'])) {
            return [
                'success' => false,
                'message' => 'Keine gültige Kunden-ID erhalten.'
            ];
        }

        $customerId = (int)$data['id'];

        if ($customerId === $currentUserId) {
            return [
                'success' => false,
                'message' => 'Du kannst dein eigenes Konto nicht deaktivieren.'
            ];
        }

        $customer = $this->customerRepository->findCustomerById($customerId);

        if (!$customer) {
            return [
                'success' => false,
                'message' => 'Kunde wurde nicht gefunden.'
            ];
        }

        $this->customerRepository->deactivateCustomer($customerId);

        return [
            'success' => true,
            'message' => 'Kunde wurde deaktiviert.'
        ];
    }

    public function getOrders(int $customerId): array
    {
        if ($customerId <= 0) {
            return [
                'success' => false,
                'message' => 'Keine gültige Kunden-ID erhalten.'
            ];
        }

        $customer = $this->customerRepository->findCustomerById($customerId);

        if (!$customer) {
            return [
                'success' => false,
                'message' => 'Kunde wurde nicht gefunden.'
            ];
        }

        return [
            'success' => true,
            'orders' => $this->customerRepository->findOrdersByCustomerId($customerId)
        ];
    }

    private function validateCustomerData(array $data, int $customerId): array
    {
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

        if (!in_array($data['title'], ['Herr', 'Frau'])) {
            return [
                'success' => false,
                'message' => 'Ungültige Anrede.'
            ];
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'message' => 'Bitte gib eine gültige E-Mail-Adresse ein.'
            ];
        }

        if ($this->customerRepository->existsUsernameExceptId($data['username'], $customerId)) {
            return [
                'success' => false,
                'message' => 'Dieser Benutzername wird bereits verwendet.'
            ];
        }

        if ($this->customerRepository->existsEmailExceptId($data['email'], $customerId)) {
            return [
                'success' => false,
                'message' => 'Diese E-Mail-Adresse wird bereits verwendet.'
            ];
        }

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
}