<?php

// wird im public api serviceHandler.php eingebunden
// zentrale Datei, die alles vorbereitet, bevor Logik läuft

//
// Reihenfolge beachten, weil jede Datei nur Klassen verwenden kann, die PHP/Intelephense zu diesem Zeitpunkt bereits kennt.
//

require_once CORE_PATH . '/dbaccess.php';
require_once CORE_PATH . '/session.php';
require_once CORE_PATH . '/ServiceHandler.php';

// Models
require_once MODELS_PATH . '/User.php';

// Repositories
require_once REPOSITORIES_PATH . '/UserRepository.php';
require_once REPOSITORIES_PATH . '/CourseRepository.php';
require_once REPOSITORIES_PATH . '/CartRepository.php';
require_once REPOSITORIES_PATH . '/VoucherRepository.php';
require_once REPOSITORIES_PATH . '/OrderRepository.php';
require_once REPOSITORIES_PATH . '/AdminCourseRepository.php';

// Services
require_once SERVICES_PATH . '/AuthService.php';
require_once SERVICES_PATH . '/CartService.php';
require_once SERVICES_PATH . '/CheckoutService.php';
require_once SERVICES_PATH . '/VoucherService.php';

// Controllers
require_once CONTROLLERS_PATH . '/AuthController.php';
require_once CONTROLLERS_PATH . '/NavController.php';
require_once CONTROLLERS_PATH . '/CourseController.php';
require_once CONTROLLERS_PATH . '/CartController.php';
require_once CONTROLLERS_PATH . '/CheckoutController.php';
require_once CONTROLLERS_PATH . '/VoucherController.php';
require_once CONTROLLERS_PATH . '/AdminCourseController.php';