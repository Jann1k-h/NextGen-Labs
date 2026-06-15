<?php
// nav braucht Zugriff auf $_SESSION, obwohl bereits bei nav.php eingebunden,
// da diese Datei separat per AJAX geladen wird und das ein neuer HTTP-Request ist.

// Kein require config, da bereits in index geladen
// require_once __DIR__ . '/../../Core/config.php';
include_once CORE_PATH . '/session.php';

$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = !empty($_SESSION['is_admin']);
$username = $_SESSION['username'] ?? '';
?>

<!--
In nav nur Text, Logik in nav_user_area

✅ Nur 1 Stelle für die Logik
→ keine doppelte HTML-Erzeugung (PHP + JS)
✅ Immer korrekte UI
→ basiert auf $_SESSION (Server = Wahrheit)
✅ Kein kompletter Reload nötig
→ nur #user-area wird neu geladen
✅ Einfach wartbar
→ Änderungen nur in einer Datei
✅ Skalierbar
→ später leicht erweiterbar (Cart, Admin, Notifications)
-->

<div class="d-flex align-items-center gap-2 flex-wrap">

  <a class="btn btn-outline-primary btn-sm" href="/">
    Home
  </a>

  <a href="#"
     id="cart-button-nav"
     class="btn btn-outline-primary btn-sm position-relative"
     data-bs-toggle="offcanvas"
     data-bs-target="#cartOffcanvas">
    <i class="bi bi-cart"></i>

    <span id="cart-count"
          class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
      0
    </span>
  </a>

  <?php if (!$isLoggedIn): ?>

    <span class="text-body-secondary small">
      Nicht eingeloggt
    </span>

    <button class="btn btn-outline-primary btn-sm" id="login-button-nav" type="button">
      Login
    </button>

  <?php else: ?>

    <div class="dropdown">
      <button class="btn btn-outline-primary btn-sm dropdown-toggle"
              type="button"
              data-bs-toggle="dropdown"
              aria-expanded="false">
        Konto
      </button>

      <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item" href="orders.php">Bestellhistorie</a></li>
        <li><a class="dropdown-item" href="account.php">Konto verwalten</a></li>
      </ul>
    </div>

    <?php if ($isAdmin): ?>
      <div class="dropdown">
        <button class="btn btn-outline-primary btn-sm dropdown-toggle"
                type="button"
                data-bs-toggle="dropdown"
                aria-expanded="false">
          Admin
        </button>

        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="admin_courses.php">Kurse</a></li>
          <li><a class="dropdown-item" href="admin_customers.php">Kunden</a></li>
          <li><a class="dropdown-item" href="admin_vouchers.php">Gutscheine</a></li>
        </ul>
      </div>
    <?php endif; ?>

    <span class="text-body-secondary small">
      Name:
      <span class="fw-semibold text-body">
        <?= htmlspecialchars($username) ?>
      </span>
    </span>

    <button class="btn btn-outline-primary btn-sm" id="logout-button-nav" type="button">
      Logout
    </button>

  <?php endif; ?>

</div>