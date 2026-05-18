<!-- Register Modal -->
<div class="modal fade" id="register-modal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border shadow-lg rounded-4 overflow-hidden bg-body">

      <!-- Header -->
      <div class="modal-header px-4 py-3 border-0 bg-body-tertiary">
        <h5 class="modal-title fw-semibold mb-0">Registrieren</h5>
        <button type="button" class="btn-close close-modal" data-bs-dismiss="modal"></button>
      </div>

      <!-- Body -->
      <div class="modal-body px-4 py-4">

        <form id="register-form">

          <div class="row g-3">

            <!-- Anrede -->
            <div class="col-md-4">
              <select id="register-title" class="form-select form-select-lg rounded-3">
                <option value="">Anrede auswählen</option>
                <option value="Herr">Herr</option>
                <option value="Frau">Frau</option>
              </select>
              <div class="error text-danger small mt-1" id="register-title-error"></div>
            </div>

            <!-- Vorname -->
            <div class="col-md-4">
              <input type="text" id="register-firstname" class="form-control form-control-lg rounded-3" placeholder="Vorname">
              <div class="error text-danger small mt-1" id="register-firstname-error"></div>
            </div>

            <!-- Nachname -->
            <div class="col-md-4">
              <input type="text" id="register-lastname" class="form-control form-control-lg rounded-3" placeholder="Nachname">
              <div class="error text-danger small mt-1" id="register-lastname-error"></div>
            </div>

            <!-- Username -->
            <div class="col-md-6">
              <input type="text" id="register-username" class="form-control form-control-lg rounded-3" placeholder="Username">
              <div class="error text-danger small mt-1" id="register-username-error"></div>
            </div>

            <!-- Email -->
            <div class="col-md-6">
              <input type="email" id="register-email" class="form-control form-control-lg rounded-3" placeholder="Emailadresse">
              <div class="error text-danger small mt-1" id="register-email-error"></div>
            </div>

            <!-- Adresse -->
            <div class="col-md-7">
              <input type="text" id="register-address" class="form-control form-control-lg rounded-3" placeholder="Adresse">
              <div class="error text-danger small mt-1" id="register-address-error"></div>
            </div>

            <!-- PLZ -->
            <div class="col-md-2">
              <input type="text" id="register-zipcode" class="form-control form-control-lg rounded-3" placeholder="PLZ">
              <div class="error text-danger small mt-1" id="register-zipcode-error"></div>
            </div>

            <!-- Ort -->
            <div class="col-md-3">
              <input type="text" id="register-city" class="form-control form-control-lg rounded-3" placeholder="Ort">
              <div class="error text-danger small mt-1" id="register-city-error"></div>
            </div>

            <!-- Passwort -->
            <div class="col-md-6">
              <input type="password" id="register-password" class="form-control form-control-lg rounded-3" placeholder="Passwort">
              <div class="error text-danger small mt-1" id="register-password-error"></div>
            </div>

            <!-- Passwort bestätigen -->
            <div class="col-md-6">
              <input type="password" id="register-password-confirm" class="form-control form-control-lg rounded-3" placeholder="Passwort bestätigen">
              <div class="error text-danger small mt-1" id="register-password-confirm-error"></div>
            </div>

            <!-- Zahlungsinfo -->
            <div class="col-md-12">
              <select id="register-payment-info" class="form-select form-select-lg rounded-3">
                <option value="">Zahlungsmethode wählen (optional)</option>
                <option value="paypal">PayPal</option>
                <option value="invoice">Rechnung</option>
                <option value="credit_card">Kreditkarte</option>
              </select>
              <!-- <div class="error" id="register-payment-info-error"></div> -->
            </div>

          </div>

        </form>

      </div>

      <!-- Footer -->
      <div class="modal-footer px-4 py-3 border-0 bg-body-tertiary d-flex justify-content-between align-items-center flex-wrap gap-2">

        <p class="mb-0 text-body-secondary">
            Bereits Mitglied? 
          <a href="javascript:void(0)" class="login-button-modal fw-semibold text-decoration-none">
            Login
          </a>
        </p>

        <button type="button" class="btn btn-success btn-lg rounded-3 fw-semibold" id="register-submit-button-modal">
          Registrieren
        </button>

      </div>

    </div>
  </div>
</div>