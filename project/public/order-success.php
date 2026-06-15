<?php
// --------------------------------------------------
// Core
require_once __DIR__ . '/../src/Core/config.php';
require_once CORE_PATH . '/session.php';
require_once CORE_PATH . '/bootstrap.php';
// --------------------------------------------------


// --------------------------------------------------
// Access Control
if (!isset($_SESSION['user_id'])) {
    header('Location: /index.php');
    exit;
}
// --------------------------------------------------


// --------------------------------------------------
// Request Params
$orderId = (int)($_GET['order_id'] ?? 0);

if ($orderId <= 0) {
    die("Ungültige Bestellung");
}
// --------------------------------------------------


// --------------------------------------------------
// Business Logic
$orderService = new OrderService();
$result = $orderService->getOrderDetails($orderId, (int)$_SESSION['user_id']);
// --------------------------------------------------


// --------------------------------------------------
// Views
include_once VIEWS_PATH . '/layouts/header.php';
include_once VIEWS_PATH . '/layouts/nav.php';

// CONTENT
include_once VIEWS_PATH . '/checkout/order-success.php';

include_once VIEWS_PATH . '/layouts/footer.php';
// --------------------------------------------------
?>