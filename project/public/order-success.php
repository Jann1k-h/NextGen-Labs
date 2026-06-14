<?php

require_once __DIR__ . '/../src/Core/config.php';
require_once CORE_PATH . '/session.php';
require_once CORE_PATH . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /index.php');
    exit;
}

$orderId = (int)($_GET['order_id'] ?? 0);

if ($orderId <= 0) {
    die("Ungültige Bestellung");
}

$service = new CheckoutService();
$result = $service->getOrderDetails($orderId);

include_once VIEWS_PATH . '/layouts/header.php';
include_once VIEWS_PATH . '/layouts/nav.php';

include_once VIEWS_PATH . '/checkout/order-success.php';

include_once VIEWS_PATH . '/layouts/footer.php';