<?php
require_once __DIR__ . '/../src/Core/config.php';
require_once CORE_PATH . '/session.php';

include_once VIEWS_PATH . '/layouts/header.php';
include_once VIEWS_PATH . '/layouts/nav.php';

// CONTENT
$courseId = $_GET['id'] ?? null;
include_once VIEWS_PATH . '/components/course_details_view.php';
//

include_once VIEWS_PATH . '/layouts/footer.php';
?>