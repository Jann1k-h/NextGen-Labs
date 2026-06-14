<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">Gutscheine verwalten</h1>
            <p class="text-muted mb-0">Erstelle, bearbeite und lösche Gutscheincodes.</p>
        </div>
    </div>

    <!-- Gutschein Formular Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Gutschein erstellen / bearbeiten</h5>
        </div>

        <div class="card-body">
            <form id="voucher-form">
                <input type="hidden" id="voucher-id">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="voucher-code" class="form-label">Code</label>
                        <input type="text" id="voucher-code" class="form-control" placeholder="z. B. SOMMER20" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="voucher-name" class="form-label">Name</label>
                        <input type="text" id="voucher-name" class="form-control" placeholder="z. B. Sommeraktion" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="voucher-discount-type" class="form-label">Rabatt-Typ</label>
                        <select id="voucher-discount-type" class="form-select" required>
                            <option value="percent">Prozent</option>
                            <option value="fixed">Fixbetrag</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="voucher-discount-value" class="form-label">Rabatt-Wert</label>
                        <input type="number" step="0.01" id="voucher-discount-value" class="form-control" placeholder="z. B. 20" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="voucher-usage-limit" class="form-label">Nutzungslimit</label>
                        <input type="number" id="voucher-usage-limit" class="form-control" placeholder="Optional">
                    </div>
                </div>

                <div class="row align-items-end">
                    <div class="col-md-6 mb-3">
                        <label for="voucher-valid-until" class="form-label">Gültig bis</label>
                        <input type="datetime-local" id="voucher-valid-until" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="form-check mt-4">
                            <input type="checkbox" id="voucher-is-active" class="form-check-input" checked>
                            <label for="voucher-is-active" class="form-check-label">Gutschein ist aktiv</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" id="voucher-submit-btn" class="btn btn-primary">
                        Gutschein erstellen
                    </button>

                    <button type="button" id="voucher-reset-btn" class="btn btn-outline-secondary">
                        Zurücksetzen
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Gutschein Liste Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Bestehende Gutscheine</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Typ</th>
                            <th>Wert</th>
                            <th>Gültig bis</th>
                            <th>Limit</th>
                            <th>Genutzt</th>
                            <th>Aktiv</th>
                            <th>Aktionen</th>
                        </tr>
                    </thead>

                    <tbody id="voucher-list"></tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script src="/assets/js/api/voucher-api.js"></script>
<script src="/assets/js/voucher.js"></script>