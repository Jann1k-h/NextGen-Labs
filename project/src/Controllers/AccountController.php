<?php

class AccountController
{
    // --------------------------------------------------
    // Prüfen, ob User eingeloggt ist
    private function requireLogin(): bool
    {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Bitte anmelden.'
            ]);
            return false;
        }

        return true;
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Eigene Benutzerdaten laden
    public function get(): void
    {
        header('Content-Type: application/json');

        if (!$this->requireLogin()) {
            return;
        }

        $userId = (int)$_SESSION['user_id'];

        $accountRepository = new AccountRepository();
        $user = $accountRepository->findUserById($userId);

        if (!$user) {
            echo json_encode([
                'success' => false,
                'message' => 'Benutzer wurde nicht gefunden.'
            ]);
            return;
        }

        echo json_encode([
            'success' => true,
            'user' => $user
        ]);
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Eigene Benutzerdaten aktualisieren
    public function update(): void
    {
        header('Content-Type: application/json');

        if (!$this->requireLogin()) {
            return;
        }

        $userId = (int)$_SESSION['user_id'];

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Keine gültigen Daten erhalten.'
            ]);
            return;
        }

        $accountRepository = new AccountRepository();
        $user = $accountRepository->findUserWithPasswordById($userId);

        if (!$user) {
            echo json_encode([
                'success' => false,
                'message' => 'Benutzer wurde nicht gefunden.'
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
            empty($data['city']) ||
            empty($data['current_password'])
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

        if (!password_verify($data['current_password'], $user['password'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Das eingegebene Passwort ist falsch.'
            ]);
            return;
        }

        if ($accountRepository->existsUsernameExceptId($data['username'], $userId)) {
            echo json_encode([
                'success' => false,
                'message' => 'Dieser Benutzername wird bereits verwendet.'
            ]);
            return;
        }

        if ($accountRepository->existsEmailExceptId($data['email'], $userId)) {
            echo json_encode([
                'success' => false,
                'message' => 'Diese E-Mail-Adresse wird bereits verwendet.'
            ]);
            return;
        }

        $accountRepository->updateUser($userId, [
            'title' => $data['title'],
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'username' => $data['username'],
            'email' => $data['email'],
            'address' => $data['address'],
            'zipcode' => $data['zipcode'],
            'city' => $data['city'],
            'payment_info' => $data['payment_info'] ?? null
        ]);

        $_SESSION['username'] = trim($data['username']);

        echo json_encode([
            'success' => true,
            'message' => 'Kontodaten wurden aktualisiert.'
        ]);
    }
    // --------------------------------------------------
}