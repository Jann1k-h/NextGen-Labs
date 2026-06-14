<?php

require_once __DIR__ . '/../src/Core/config.php';
require_once CORE_PATH . '/bootstrap.php';

// --------------------------------------------------
// Prüfen, ob User eingeloggt ist
if (!isset($_SESSION['user_id'])) {
    header('Location: /index.php');
    exit;
}
// --------------------------------------------------


// --------------------------------------------------
// Wenn Admin eine user_id mitgibt, wird die Historie dieses Kunden geladen
// Sonst wird die eigene Bestellhistorie geladen
$requestedUserId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;

$controller = new CheckoutController();

$result = $controller->orderHistory($requestedUserId);
// --------------------------------------------------

include_once VIEWS_PATH . '/layouts/header.php';
include_once VIEWS_PATH . '/layouts/nav.php';

include_once VIEWS_PATH . '/account/order_history_page.php';

include_once VIEWS_PATH . '/layouts/footer.php';