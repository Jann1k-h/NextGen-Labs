<?php

class AdminCustomerController extends BaseController
{
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