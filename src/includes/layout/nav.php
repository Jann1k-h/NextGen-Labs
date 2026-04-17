<nav class="navbar sticky-top border-bottom px-4 py-2 bg-body">
  <a class="navbar-brand fw-semibold fs-5" href="/">NextGen Labs</a>
  <div id="user-area" class="d-flex align-items-center gap-2 ms-auto">
    <?php include __DIR__ . '/../partials/nav_user_area.php'; ?>
  </div>

  <div class="form-check form-switch ms-3">
    <input class="form-check-input" type="checkbox" id="theme-toggle">
    <label class="form-check-label small" id="theme-toggle-label">☀️</label>
  </div>

</nav>

<?php include __DIR__ . '/../ui/alerts.php'; ?>
<?php include __DIR__ . '/../modals/login_modal.php'; ?>
<?php include __DIR__ . '/../modals/register_modal.php'; ?>