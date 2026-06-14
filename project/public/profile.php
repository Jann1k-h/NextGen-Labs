<?php

require_once __DIR__ . '/../src/Core/config.php';
require_once CORE_PATH . '/bootstrap.php';

$controller = new CheckoutController();

$result = $controller->orderHistory();

include_once VIEWS_PATH . '/layouts/header.php';
include_once VIEWS_PATH . '/layouts/nav.php';

include_once VIEWS_PATH . '/account/order_history_page.php';

include_once VIEWS_PATH . '/layouts/footer.php';