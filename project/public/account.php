<?php

require_once __DIR__ . '/../src/Core/config.php';
require_once CORE_PATH . '/session.php';

// --------------------------------------------------
// Prüfen, ob User eingeloggt ist
if (!isset($_SESSION['user_id'])) {
    header('Location: /index.php');
    exit;
}
// --------------------------------------------------

include_once VIEWS_PATH . '/layouts/header.php';
include_once VIEWS_PATH . '/layouts/nav.php';

include_once VIEWS_PATH . '/account/account_page.php';

include_once VIEWS_PATH . '/layouts/footer.php';

?>