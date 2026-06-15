<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">Gutscheine verwalten</h1>
            <p class="text-body-secondary mb-0">Erstelle, bearbeite und lösche Gutscheincodes.</p>
        </div>

        <button type="button" id="open-create-voucher-modal-btn" class="btn btn-primary rounded-pill">
            Gutschein erstellen
        </button>
    </div>

    <!-- Gutschein Liste Card -->
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Bestehende Gutscheine</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
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


<!-- Gutschein Modal -->
<div class="modal fade" id="voucherModal" tabindex="-1" aria-labelledby="voucherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content rounded-4">

            <div class="modal-header">
                <h5 class="modal-title" id="voucherModalLabel">Gutschein erstellen</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Schließen"></button>
            </div>

            <form id="voucher-form">
                <div class="modal-body">
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
                </div>

                <div class="modal-footer">
                    <button type="button" id="voucher-reset-btn" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Abbrechen
                    </button>

                    <button type="submit" id="voucher-submit-btn" class="btn btn-primary">
                        Gutschein erstellen
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>