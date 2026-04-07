<!-- Login Modal (Bootstrap) -->
<div class="container">
  <div class="modal fade" id="login-modal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <!-- Modal content-->
      <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

        <div class="modal-header px-4 py-3 border-0 bg-light">
          <h4 class="modal-title fw-semibold mb-0" id="loginModalLabel">Login</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body px-4 py-4">
          <form id="login-form">
            <div class="form-group mb-3">
                <!--<label for="login-identifier"><span class="glyphicon glyphicon-user"></span> Username</label>-->
                <input type="text" class="form-control form-control-lg rounded-3" id="login-identifier" placeholder="Username oder E-Mail">
                <div class="error text-danger small mt-1" id="login-identifier-error"></div>
            </div>

            <div class="form-group mb-3">
                <!--<label for="login-password"><span class="glyphicon glyphicon-eye-open"></span> Password</label>-->
                <input type="password" class="form-control form-control-lg rounded-3" id="login-password" placeholder="Enter password">
                <div class="error text-danger small mt-1" id="login-password-error"></div>
            </div>

            <div class="checkbox form-check mb-4">
                <label class="form-check-label d-flex align-items-center gap-2">
                    <input type="checkbox" class="form-check-input mt-0" id="login-remember-me" value="" checked>
                      Angemeldet bleiben
                </label>
            </div>

            <!--<button type="submit" class="btn btn-default btn-success btn-block"><span class="glyphicon glyphicon-off"></span> Login</button>-->
            <div class="d-grid">
              <button type="button" class="btn btn-success btn-lg rounded-3 fw-semibold" id="login-submit-button-modal">Login</button>
            </div>
          </form>
        </div>

        <div class="modal-footer px-4 py-3 border-0 bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
            <button type="button" class="btn btn-outline-secondary rounded-3" data-bs-dismiss="modal">
                Abbrechen
            </button>
            <p class="mb-0 text-muted">
                Noch kein Mitglied?

                <!-- javascript:void(0) verhindert, dass die Seite neu geladen wird, wenn der Link angeklickt wird -->
                <!-- wenn man zb href="#" verwendet, würde man zum Anfang der Seite springen -->
                <a href="javascript:void(0)" class="register-button-modal fw-semibold text-decoration-none">Register</a>
            </p>
        </div>
      </div>
    </div>
  </div>
</div>