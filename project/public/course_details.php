<?php

// --------------------------------------------------
// Core
require_once __DIR__ . '/../src/Core/config.php';
require_once CORE_PATH . '/session.php';
// --------------------------------------------------


// --------------------------------------------------
// Views
include_once VIEWS_PATH . '/layouts/header.php';
include_once VIEWS_PATH . '/layouts/nav.php';

// CONTENT
$courseId = $_GET['id'] ?? null;
include_once VIEWS_PATH . '/courses/course_details_page.php';

include_once VIEWS_PATH . '/layouts/footer.php';
// --------------------------------------------------
?>