<?php

class AccountService
{
    private AccountRepository $accountRepository;

    public function __construct()
    {
        $this->accountRepository = new AccountRepository();
    }

    public function getAccount(int $userId): array
    {
        $user = $this->accountRepository->findUserById($userId);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Benutzer wurde nicht gefunden.'
            ];
        }

        return [
            'success' => true,
            'user' => $user
        ];
    }

    public function updateAccount(int $userId, ?array $data): array
    {
        if (!$data) {
            return [
                'success' => false,
                'message' => 'Keine gültigen Daten erhalten.'
            ];
        }

        $user = $this->accountRepository->findUserWithPasswordById($userId);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Benutzer wurde nicht gefunden.'
            ];
        }

        if (!$this->hasRequiredFields($data)) {
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

        if (!password_verify($data['current_password'], $user['password'])) {
            return [
                'success' => false,
                'message' => 'Das eingegebene Passwort ist falsch.'
            ];
        }

        if ($this->accountRepository->existsUsernameExceptId($data['username'], $userId)) {
            return [
                'success' => false,
                'message' => 'Dieser Benutzername wird bereits verwendet.'
            ];
        }

        if ($this->accountRepository->existsEmailExceptId($data['email'], $userId)) {
            return [
                'success' => false,
                'message' => 'Diese E-Mail-Adresse wird bereits verwendet.'
            ];
        }

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

        return [
            'success' => true,
            'message' => 'Kontodaten wurden aktualisiert.'
        ];
    }

    private function hasRequiredFields(array $data): bool
    {
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
}