<?php
require_once __DIR__ . '/../src/Core/config.php';
require_once __DIR__ . '/../src/Core/bootstrap.php';

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

$checkoutService = new CheckoutService();
$result = null;

if ($order_id > 0) {
    $result = $checkoutService->getOrderDetails($order_id);
}

include_once VIEWS_PATH . '/layouts/header.php';
include_once VIEWS_PATH . '/layouts/nav.php';

// CONTENT
include_once VIEWS_PATH . '/checkout/order-success.php';
//

include_once VIEWS_PATH . '/layouts/footer.php';
?>