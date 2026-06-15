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
// Wenn Admin eine user_id mitgibt, wird die Historie dieses Kunden geladen.
// Normale User dürfen nur ihre eigene Bestellhistorie sehen.
$currentUserId = (int)$_SESSION['user_id'];
$isAdmin = (int)($_SESSION['is_admin'] ?? 0) === 1;

$requestedUserId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;

if ($requestedUserId !== null && !$isAdmin) {
    $requestedUserId = $currentUserId;
}

$userIdForHistory = $requestedUserId ?? $currentUserId;
// --------------------------------------------------


// --------------------------------------------------
// Business Logic
$orderService = new OrderService();
$result = $orderService->getOrderHistory($userIdForHistory);
// --------------------------------------------------


// --------------------------------------------------
// Views
include_once VIEWS_PATH . '/layouts/header.php';
include_once VIEWS_PATH . '/layouts/nav.php';

// CONTENT
include_once VIEWS_PATH . '/account/order_history_page.php';

include_once VIEWS_PATH . '/layouts/footer.php';
// --------------------------------------------------
?>