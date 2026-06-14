<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="mb-4">
                <h1 class="mb-1">Mein Konto</h1>
                <p class="text-body-secondary mb-0">
                    Persönliche Daten anzeigen und bearbeiten.
                </p>
            </div>

            <div class="card shadow-sm">

                <div class="card-header bg-body d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Kontodaten</h5>
                </div>

                <div class="card-body">

                    <form id="account-form">

                        <div class="row">

                            <div class="col-md-4 mb-3">
                                <label for="account-title" class="form-label">Anrede</label>
                                <select id="account-title" class="form-select" required>
                                    <option value="">Bitte auswählen</option>
                                    <option value="Herr">Herr</option>
                                    <option value="Frau">Frau</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="account-firstname" class="form-label">Vorname</label>
                                <input type="text" id="account-firstname" class="form-control" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="account-lastname" class="form-label">Nachname</label>
                                <input type="text" id="account-lastname" class="form-control" required>
                            </div>

                        </div>

                        <div class="mb-3">
                            <label for="account-username" class="form-label">Username</label>
                            <input type="text" id="account-username" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="account-email" class="form-label">E-Mail</label>
                            <input type="email" id="account-email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="account-address" class="form-label">Adresse</label>
                            <input type="text" id="account-address" class="form-control" required>
                        </div>

                        <div class="row">

                            <div class="col-md-4 mb-3">
                                <label for="account-zipcode" class="form-label">PLZ</label>
                                <input type="text" id="account-zipcode" class="form-control" required>
                            </div>

                            <div class="col-md-8 mb-3">
                                <label for="account-city" class="form-label">Stadt</label>
                                <input type="text" id="account-city" class="form-control" required>
                            </div>

                        </div>

                        <div class="mb-3">
                            <label for="account-payment-info" class="form-label">Zahlungsinformation</label>
                            <select id="account-payment-info" class="form-select">
                                <option value="">Bitte auswählen</option>
                                <option value="card">Kreditkarte</option>
                                <option value="paypal">PayPal</option>
                                <option value="bank_transfer">Überweisung</option>
                            </select>
                        </div>

                        <hr class="my-4">

                        <div class="mb-3">
                            <label for="account-current-password" class="form-label">Passwort zur Bestätigung</label>
                            <input type="password" id="account-current-password" class="form-control" required>
                            <div class="form-text">
                                Zur Sicherheit muss das aktuelle Passwort eingegeben werden.
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" id="account-reset-btn" class="btn btn-outline-secondary">
                                Zurücksetzen
                            </button>

                            <button type="submit" class="btn btn-primary">
                                Änderungen speichern
                            </button>
                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>