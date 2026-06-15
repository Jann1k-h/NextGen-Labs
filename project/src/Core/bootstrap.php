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


// Repositories
require_once REPOSITORIES_PATH . '/AccountRepository.php';
require_once REPOSITORIES_PATH . '/AdminCourseRepository.php';
require_once REPOSITORIES_PATH . '/AdminCustomerRepository.php';
require_once REPOSITORIES_PATH . '/AdminVoucherRepository.php';
require_once REPOSITORIES_PATH . '/CartRepository.php';
require_once REPOSITORIES_PATH . '/CheckoutRepository.php';
require_once REPOSITORIES_PATH . '/CourseRepository.php';
require_once REPOSITORIES_PATH . '/OrderRepository.php';
require_once REPOSITORIES_PATH . '/AuthRepository.php';

// Services
require_once SERVICES_PATH . '/AccountService.php';
require_once SERVICES_PATH . '/AdminCourseService.php';
require_once SERVICES_PATH . '/AdminCustomerService.php';
require_once SERVICES_PATH . '/AdminVoucherService.php';
require_once SERVICES_PATH . '/AuthService.php';
require_once SERVICES_PATH . '/CartService.php';
require_once SERVICES_PATH . '/CheckoutService.php';
require_once SERVICES_PATH . '/CourseService.php';

// Controllers
require_once CONTROLLERS_PATH . '/AccountController.php';
require_once CONTROLLERS_PATH . '/AdminCourseController.php';
require_once CONTROLLERS_PATH . '/AdminCustomerController.php';
require_once CONTROLLERS_PATH . '/AdminVoucherController.php';
require_once CONTROLLERS_PATH . '/AuthController.php';
require_once CONTROLLERS_PATH . '/CartController.php';
require_once CONTROLLERS_PATH . '/CheckoutController.php';
require_once CONTROLLERS_PATH . '/CourseController.php';
require_once CONTROLLERS_PATH . '/NavController.php';