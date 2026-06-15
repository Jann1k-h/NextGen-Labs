<?php

class AccountController
{
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

    public function get(): void
    {
        if (!$this->requireLogin()) {
            return;
        }

        $accountService = new AccountService();
        $result = $accountService->getAccount((int)$_SESSION['user_id']);

        echo json_encode($result);
    }

    public function update(): void
    {
        if (!$this->requireLogin()) {
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        $accountService = new AccountService();
        $result = $accountService->updateAccount((int)$_SESSION['user_id'], $data);

        if ($result['success']) {
            $_SESSION['username'] = trim($data['username']);
        }

        echo json_encode($result);
    }
}