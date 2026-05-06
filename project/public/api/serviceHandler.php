<?php

// zentrale Stelle für die Bearbeitung aller API-Requests
// weiterleitung an ServiceHandler in CORE der dann die entsprechenden Services aufruft

require_once __DIR__ . '/../../src/Core/config.php';
require_once CORE_PATH . '/bootstrap.php';

$module = $_GET['module'] ?? '';
$action = $_GET['action'] ?? '';

$handler = new ServiceHandler();
$handler->handle($module, $action);