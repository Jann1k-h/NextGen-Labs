<?php
include_once __DIR__ . '/includes/core/session.php';
include_once __DIR__ . '/includes/layout/header.php';
include_once __DIR__ . '/includes/layout/nav.php';


// CONTENT
$courseId = $_GET['id'] ?? null;
include_once __DIR__ . '/includes/ui/course_details_view.php';
//


include_once __DIR__ . '/includes/layout/footer.php';
?>