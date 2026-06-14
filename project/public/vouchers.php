<?php
require_once __DIR__ . '/../src/Core/config.php';
require_once CORE_PATH . '/session.php';

if (!isset($_SESSION['user_id']) || (int)($_SESSION['is_admin'] ?? 0) !== 1) {
    header('Location: /index.php');
    exit;
}

include_once VIEWS_PATH . '/layouts/header.php';
include_once VIEWS_PATH . '/layouts/nav.php';

// CONTENT
include_once VIEWS_PATH . '/admin/voucher/voucher_page.php';
//

include_once VIEWS_PATH . '/layouts/footer.php';
?>