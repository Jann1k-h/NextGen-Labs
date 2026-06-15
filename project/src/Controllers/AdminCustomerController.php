<?php

class AdminCustomerController
{
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

    public function get(): void
    {
        if (!$this->requireAdmin()) {
            return;
        }

        $service = new AdminCustomerService();

        echo json_encode($service->getCustomers());
    }

    public function update(): void
    {
        if (!$this->requireAdmin()) {
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        $service = new AdminCustomerService();

        echo json_encode($service->updateCustomer($data, (int)$_SESSION['user_id']));
    }

    public function deactivate(): void
    {
        if (!$this->requireAdmin()) {
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        $service = new AdminCustomerService();

        echo json_encode($service->deactivateCustomer($data, (int)$_SESSION['user_id']));
    }

    public function getOrders(): void
    {
        if (!$this->requireAdmin()) {
            return;
        }

        $customerId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        $service = new AdminCustomerService();

        echo json_encode($service->getOrders($customerId));
    }
}