<?php


// --------------------------------------------------
// Core
require_once __DIR__ . '/../src/Core/config.php';
require_once CORE_PATH . '/bootstrap.php';


// --------------------------------------------------
// Access Control
if (!isset($_SESSION['user_id'])) {
    header('Location: /index.php');
    exit;
}
// --------------------------------------------------


// --------------------------------------------------
// Request Params
// Wenn Admin eine user_id mitgibt, wird die Historie dieses Kunden geladen
// Sonst wird die eigene Bestellhistorie geladen
$requestedUserId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;
// --------------------------------------------------


// --------------------------------------------------
// Business Logic
$controller = new CheckoutController();
$result = $controller->orderHistory($requestedUserId);
// --------------------------------------------------


// --------------------------------------------------
// Views
include_once VIEWS_PATH . '/layouts/header.php';
include_once VIEWS_PATH . '/layouts/nav.php';
include_once VIEWS_PATH . '/account/order_history_page.php';
include_once VIEWS_PATH . '/layouts/footer.php';
// --------------------------------------------------
?>