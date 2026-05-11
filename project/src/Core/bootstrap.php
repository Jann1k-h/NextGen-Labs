<?php

// wird im public api serviceHandler.php eingebunden
// zentrale Datei, die alles vorbereitet, bevor Logik läuft

require_once CORE_PATH . '/dbaccess.php';
require_once CORE_PATH . '/session.php';
require_once CORE_PATH . '/ServiceHandler.php';

require_once MODELS_PATH . '/User.php';

require_once REPOSITORIES_PATH . '/UserRepository.php';
require_once REPOSITORIES_PATH . '/CourseRepository.php';
require_once REPOSITORIES_PATH . '/CartRepository.php';

require_once SERVICES_PATH . '/AuthService.php';

require_once CONTROLLERS_PATH . '/AuthController.php';
require_once CONTROLLERS_PATH . '/NavController.php';
require_once CONTROLLERS_PATH . '/CoursesController.php';
require_once CONTROLLERS_PATH . '/CartController.php';