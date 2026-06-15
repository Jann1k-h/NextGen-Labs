<?php

// --------------------------------------------------
// API-Request Dispatcher
// --------------------------------------------------
// zentrale Stelle für die Bearbeitung aller API-Requests
// weiterleitung an ServiceHandler in CORE der dann die entsprechenden Services aufruft
// --------------------------------------------------


// --------------------------------------------------
// Abhängigkeiten laden
require_once __DIR__ . '/../../src/Core/config.php';
require_once CORE_PATH . '/bootstrap.php';
// --------------------------------------------------


// --------------------------------------------------
// Request-Daten auslesen
$module = $_GET['module'] ?? '';
$action = $_GET['action'] ?? '';
// --------------------------------------------------


// --------------------------------------------------
// Request verarbeiten
$handler = new ServiceHandler();
$handler->handle($module, $action);