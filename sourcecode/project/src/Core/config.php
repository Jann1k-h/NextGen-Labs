<?php

// Base Pfad definieren, 2 bedeutet, dass wir 2 Verzeichnisse nach oben gehen, um zum Root-Verzeichnis zu gelangen
define('BASE_PATH', dirname(__DIR__, 2));

// Wichtige Unterordner als Dateisystempfade
define('SRC_PATH', BASE_PATH . '/src');
define('CORE_PATH', SRC_PATH . '/Core');
define('VIEWS_PATH', SRC_PATH . '/Views');
define('PUBLIC_PATH', BASE_PATH . '/public');
define('ASSETS_PATH', PUBLIC_PATH . '/assets');
define('CONTROLLERS_PATH', SRC_PATH . '/Controllers');
define('SERVICES_PATH', SRC_PATH . '/Services');
define('REPOSITORIES_PATH', SRC_PATH . '/Repositories');
define('MODELS_PATH', SRC_PATH . '/Models');

// Basis-URL im Browser
// Für lokales Setup ggf. anpassen, z. B. '/project/public'
// Wenn dein Virtual Host direkt auf /public zeigt, dann einfach ''
define('BASE_URL', '');