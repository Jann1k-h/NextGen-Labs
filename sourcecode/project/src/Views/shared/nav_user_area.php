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
Nur 1 Stelle für die Logik → keine doppelte HTML-Erzeugung (PHP + JS)
Immer korrekte UI → basiert auf $_SESSION (Server = Wahrheit)
Kein kompletter Reload nötig → nur #user-area wird neu geladen
Einfach wartbar → Änderungen nur in einer Datei
Skalierbar → später leicht erweiterbar (Cart, Admin, Notifications)
-->
<div class="d-flex align-items-center gap-2 flex-wrap">

  <a class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1" href="/">
    <i class="bi bi-house"></i>
    <span>Home</span>
  </a>

  <button class="btn btn-sm btn-outline-secondary position-relative"
          id="cart-button-nav"
          type="button"
          data-bs-toggle="offcanvas"
          data-bs-target="#cartOffcanvas"
          aria-label="Warenkorb öffnen">
    <i class="bi bi-cart2"></i>
    <span id="cart-count"
          class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
          style="font-size: 0.6rem;">
      0
    </span>
  </button>

  <div class="vr" style="height: 20px; opacity: 0.2;"></div>

  <?php if (!$isLoggedIn): ?>

    <span class="text-muted small d-flex align-items-center gap-1">
      <i class="bi bi-person-slash"></i>
      Nicht eingeloggt
    </span>

    <button class="btn btn-sm btn-primary d-flex align-items-center gap-1"
            id="login-button-nav"
            type="button">
      <i class="bi bi-box-arrow-in-right"></i>
      <span>Login</span>
    </button>

  <?php else: ?>

    <div class="dropdown">
      <button class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1 dropdown-toggle"
              type="button"
              data-bs-toggle="dropdown"
              aria-expanded="false">
        <i class="bi bi-person-circle"></i>
        <span><?= htmlspecialchars($username) ?></span>
      </button>
      <ul class="dropdown-menu dropdown-menu-end shadow-sm">
        <li><h6 class="dropdown-header text-muted small">Konto</h6></li>
        <li>
          <a class="dropdown-item d-flex align-items-center gap-2" href="orders.php">
            <i class="bi bi-clock-history text-muted" style="font-size: 0.85rem;"></i>
            Bestellhistorie
          </a>
        </li>
        <li>
          <a class="dropdown-item d-flex align-items-center gap-2" href="account.php">
            <i class="bi bi-gear text-muted" style="font-size: 0.85rem;"></i>
            Konto verwalten
          </a>
        </li>
      </ul>
    </div>

    <?php if ($isAdmin): ?>
      <div class="dropdown">
        <button class="btn btn-sm btn-outline-warning d-flex align-items-center gap-1 dropdown-toggle"
                type="button"
                data-bs-toggle="dropdown"
                aria-expanded="false">
          <i class="bi bi-shield-lock"></i>
          <span>Admin</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
          <li><h6 class="dropdown-header text-muted small">Verwaltung</h6></li>
          <li>
            <a class="dropdown-item d-flex align-items-center gap-2" href="admin_courses.php">
              <i class="bi bi-journal-bookmark text-muted" style="font-size: 0.85rem;"></i>
              Kurse
            </a>
          </li>
          <li>
            <a class="dropdown-item d-flex align-items-center gap-2" href="admin_customers.php">
              <i class="bi bi-people text-muted" style="font-size: 0.85rem;"></i>
              Kunden
            </a>
          </li>
          <li>
            <a class="dropdown-item d-flex align-items-center gap-2" href="admin_vouchers.php">
              <i class="bi bi-ticket-perforated text-muted" style="font-size: 0.85rem;"></i>
              Gutscheine
            </a>
          </li>
        </ul>
      </div>
    <?php endif; ?>

    <div class="vr" style="height: 20px; opacity: 0.2;"></div>

    <button class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1"
            id="logout-button-nav"
            type="button">
      <i class="bi bi-box-arrow-right"></i>
      <span>Logout</span>
    </button>

  <?php endif; ?>

</div>