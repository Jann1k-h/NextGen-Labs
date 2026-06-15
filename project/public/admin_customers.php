<?php
// --------------------------------------------------
// Core
require_once __DIR__ . '/../src/Core/config.php';
require_once CORE_PATH . '/session.php';
// --------------------------------------------------


// --------------------------------------------------
// Access Control
if (!isset($_SESSION['user_id']) || (int)($_SESSION['is_admin'] ?? 0) !== 1) {
    header('Location: /index.php');
    exit;
}
// --------------------------------------------------


// --------------------------------------------------
// Views
include_once VIEWS_PATH . '/layouts/header.php';
include_once VIEWS_PATH . '/layouts/nav.php';
include_once VIEWS_PATH . '/admin/admin_customers_page.php';
include_once VIEWS_PATH . '/layouts/footer.php';
// --------------------------------------------------
?>