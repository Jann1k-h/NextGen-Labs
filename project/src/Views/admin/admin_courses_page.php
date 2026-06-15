<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">Kurse verwalten</h1>
            <p class="text-body-secondary mb-0">Erstelle, bearbeite und deaktiviere Kurse im Kursangebot.</p>
        </div>

        <button type="button" id="open-create-course-modal-btn" class="btn btn-primary rounded-pill">
            Kurs erstellen
        </button>
    </div>

    <!-- Kurs Liste Card -->
    <div class="card border shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Bestehende Kurse</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Bild</th>
                            <th>Titel</th>
                            <th>Kategorie</th>
                            <th>Preis</th>
                            <th>Plätze</th>
                            <th>Aktiv</th>
                            <th>Aktionen</th>
                        </tr>
                    </thead>

                    <tbody id="admin-course-list"></tbody>
                </table>
            </div>
        </div>
    </div>

</div>


<!-- Kurs Modal -->
<div class="modal fade" id="courseModal" tabindex="-1" aria-labelledby="courseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content rounded-4">

            <div class="modal-header">
                <h5 class="modal-title" id="courseModalLabel">Kurs erstellen</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Schließen"></button>
            </div>

            <form id="admin-course-form" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="course-id" name="id">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="course-title" class="form-label">Titel</label>
                            <input type="text" id="course-title" name="title" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="course-category-id" class="form-label">Kategorie</label>
                            <select id="course-category-id" name="category_id" class="form-select" required>
                                <option value="">Kategorie auswählen</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="course-description" class="form-label">Beschreibung</label>
                        <textarea id="course-description" name="description" class="form-control" rows="4"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="course-price" class="form-label">Preis</label>
                            <input type="number" step="0.01" id="course-price" name="price" class="form-control" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="course-rating" class="form-label">Bewertung</label>
                            <input type="number" step="0.1" min="0" max="5" id="course-rating" name="rating" class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="course-stock" class="form-label">Plätze</label>
                            <input type="number" id="course-stock" name="stock" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="course-lecturer-name" class="form-label">Dozent</label>
                            <input type="text" id="course-lecturer-name" name="lecturer_name" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="course-lecturer-contact" class="form-label">Dozent Kontakt</label>
                            <input type="text" id="course-lecturer-contact" name="lecturer_contact" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="course-image" class="form-label">Kursbild</label>
                        <input type="file" id="course-image" name="course_image" class="form-control" accept="image/*">
                        <div class="form-text">
                            Beim Bearbeiten wird das bestehende Bild nur ersetzt, wenn du ein neues hochlädst.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="course-image-alt" class="form-label">Bild Alt-Text</label>
                        <input type="text" id="course-image-alt" name="alt_text" class="form-control">
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" id="course-is-active" name="is_active" class="form-check-input" checked>
                        <label for="course-is-active" class="form-check-label">Kurs ist aktiv</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" id="course-reset-btn" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Abbrechen
                    </button>

                    <button type="submit" id="course-submit-btn" class="btn btn-primary">
                        Kurs erstellen
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>