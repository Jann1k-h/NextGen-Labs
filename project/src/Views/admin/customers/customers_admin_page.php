<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">Kunden verwalten</h1>
            <p class="text-muted mb-0">Kundenkonten anzeigen, deaktivieren und Bestellungen einsehen.</p>
        </div>
    </div>

    <!-- Kunden Liste Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Kundenliste</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>E-Mail</th>
                            <th>Adresse</th>
                            <th>Rolle</th>
                            <th>Aktiv</th>
                            <th>Erstellt am</th>
                            <th>Aktionen</th>
                        </tr>
                    </thead>

                    <tbody id="admin-customer-list"></tbody>
                </table>
            </div>
        </div>
    </div>

</div>


<!-- Bestellungen Modal -->
<div class="modal fade" id="customerOrdersModal" tabindex="-1" aria-labelledby="customerOrdersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content rounded-4">

            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="customerOrdersModalLabel">Bestellungen anzeigen</h5>
                    <div class="text-muted small" id="customer-orders-subtitle"></div>
                </div>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Schließen"></button>
            </div>

            <div class="modal-body">
                <div id="customer-orders-list"></div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Schließen
                </button>
            </div>

        </div>
    </div>
</div>


<!-- Kunde Bearbeiten Modal -->
<div class="modal fade" id="customerEditModal" tabindex="-1" aria-labelledby="customerEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content rounded-4">

            <div class="modal-header">
                <h5 class="modal-title" id="customerEditModalLabel">Kunde bearbeiten</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Schließen"></button>
            </div>

            <form id="customer-edit-form">
                <div class="modal-body">
                    <input type="hidden" id="customer-id">

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="customer-title" class="form-label">Anrede</label>
                            <select id="customer-title" class="form-select" required>
                                <option value="Herr">Herr</option>
                                <option value="Frau">Frau</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="customer-firstname" class="form-label">Vorname</label>
                            <input type="text" id="customer-firstname" class="form-control" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="customer-lastname" class="form-label">Nachname</label>
                            <input type="text" id="customer-lastname" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="customer-username" class="form-label">Username</label>
                        <input type="text" id="customer-username" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="customer-email" class="form-label">E-Mail</label>
                        <input type="email" id="customer-email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="customer-address" class="form-label">Adresse</label>
                        <input type="text" id="customer-address" class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="customer-zipcode" class="form-label">PLZ</label>
                            <input type="text" id="customer-zipcode" class="form-control" required>
                        </div>

                        <div class="col-md-8 mb-3">
                            <label for="customer-city" class="form-label">Stadt</label>
                            <input type="text" id="customer-city" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="customer-payment-info" class="form-label">Zahlungsinformation</label>
                        <select id="customer-payment-info" class="form-select">
                            <option value="">Bitte auswählen</option>
                            <option value="card">Kreditkarte</option>
                            <option value="paypal">PayPal</option>
                            <option value="bank_transfer">Überweisung</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="customer-password" class="form-label">Neues Passwort</label>
                        <input type="password" id="customer-password" class="form-control" placeholder="Leer lassen, wenn Passwort gleich bleiben soll">
                        <div class="form-text">Nur ausfüllen, wenn das Passwort geändert werden soll.</div>
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" id="customer-is-admin" class="form-check-input">
                        <label for="customer-is-admin" class="form-check-label">Kunde ist Administrator</label>
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" id="customer-is-active" class="form-check-input">
                        <label for="customer-is-active" class="form-check-label">Kunde ist aktiv</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Abbrechen
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Änderungen speichern
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>