<?php
require_once __DIR__ . '/../src/Core/config.php';
require_once CORE_PATH . '/session.php';

include_once VIEWS_PATH . '/layouts/header.php';
include_once VIEWS_PATH . '/layouts/nav.php';

// CONTENT
include_once VIEWS_PATH . '/components/courses.php';
//

include_once VIEWS_PATH . '/layouts/footer.php';
?>