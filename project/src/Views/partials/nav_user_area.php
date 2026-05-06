<?php
// nav braucht Zugriff auf $_SESSION, obwohl bereits bei nav.php eingebunden, da diese datei aber separat per AJAX gelade wird und das ein neuer HTTP-Request ist bracuht mans

// keine requore config, da bereits in index geladen
// require_once __DIR__ . '/../../Core/config.php';
include_once CORE_PATH . '/session.php';
?>

<!--
In nav nur text, logik in nav_user_area

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

<a class="btn btn-outline-primary btn-sm" href="/">Home</a>
<a class="btn btn-outline-primary btn-sm" href="/kurse.php">Kurse</a>

<a href="#" 
   class="btn btn-outline-primary btn-sm" 
   data-bs-toggle="offcanvas" 
   data-bs-target="#cartOffcanvas">
  <i class="bi bi-cart"></i>
</a>

<?php if (!isset($_SESSION['user_id'])): ?>
  <span class="text-muted small">Nicht eingeloggt</span>

  <button class="btn btn-outline-success btn-sm" id="login-button-nav" type="button">
    Login
  </button>
<?php endif; ?>

<?php if (isset($_SESSION['user_id'])): ?>
  <span class="fw-semibold small">
    Name: <?= $_SESSION['username'] ?>
  </span>

  <a href="/profile.php" class="btn btn-outline-primary btn-sm">
    <i class="bi bi-person"></i>
  </a>

  <?php if (!empty($_SESSION['is_admin'])): ?>
    <div class="dropdown">
      <button class="btn btn-outline-danger btn-sm dropdown-toggle" data-bs-toggle="dropdown">
        Admin
      </button>

      <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item" href="/admin/courses.php">Kurse</a></li>
        <li><a class="dropdown-item" href="/admin/customers.php">Kunden</a></li>
        <li><a class="dropdown-item" href="/admin/vouchers.php">Gutscheine</a></li>
      </ul>
    </div>
  <?php endif; ?>

  <button class="btn btn-outline-success btn-sm" id="logout-button-nav" type="button">
    Logout
  </button>

<?php endif; ?>