<?php

class AdminCourseService
{
    private AdminCourseRepository $repository;

    // --------------------------------------------------
    // Repository vorbereiten
    public function __construct()
    {
        // Repository erstellen, damit der Service auf Kursdaten zugreifen kann
        $this->repository = new AdminCourseRepository();
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Alle Kurse laden
    public function getCourses(): array
    {
        return [
            'success' => true,
            'courses' => $this->repository->findAll()
        ];
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Alle Kategorien laden
    public function getCategories(): array
    {
        return [
            'success' => true,
            'categories' => $this->repository->findCategories()
        ];
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Neuen Kurs erstellen
    public function createCourse(array $data, array $files): array
    {
        // Kursdaten prüfen
        $validation = $this->validateCourseData($data);

        if (!$validation['success']) {
            return $validation;
        }

        // Kurs in der Datenbank erstellen
        $courseId = $this->repository->create($this->buildCourseData($data));

        // Bild hochladen, falls eines mitgeschickt wurde
        $imagePath = $this->handleImageUpload($files);

        // Bild zum Kurs speichern
        if ($imagePath !== null) {
            $this->repository->createImage([
                'course_id' => $courseId,
                'image_path' => $imagePath,
                'alt_text' => $data['alt_text'] ?? null,
                'sort_order' => 0,
                'is_cover' => 1
            ]);
        }

        return [
            'success' => true,
            'message' => 'Kurs wurde erstellt.'
        ];
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kurs aktualisieren
    public function updateCourse(array $data, array $files): array
    {
        // Prüfen, ob eine Kurs-ID übergeben wurde
        if (empty($data['id'])) {
            return [
                'success' => false,
                'message' => 'Keine Kurs-ID erhalten.'
            ];
        }

        $id = (int)$data['id'];

        // Prüfen, ob der Kurs existiert
        $existingCourse = $this->repository->findById($id);

        if (!$existingCourse) {
            return [
                'success' => false,
                'message' => 'Kurs wurde nicht gefunden.'
            ];
        }

        // Kursdaten prüfen
        $validation = $this->validateCourseData($data);

        if (!$validation['success']) {
            return $validation;
        }

        // Kursdaten aktualisieren
        $this->repository->update($id, $this->buildCourseData($data));

        // Neues Bild hochladen, falls eines mitgeschickt wurde
        $imagePath = $this->handleImageUpload($files);

        if ($imagePath !== null) {
            // Altes Cover-Bild aus der Datenbank entfernen
            $this->repository->deleteCoverImage($id);

            // Neues Cover-Bild speichern
            $this->repository->createImage([
                'course_id' => $id,
                'image_path' => $imagePath,
                'alt_text' => $data['alt_text'] ?? null,
                'sort_order' => 0,
                'is_cover' => 1
            ]);
        } else {
            // Wenn kein neues Bild hochgeladen wurde, nur den Alt-Text aktualisieren
            $this->repository->updateCoverImageAltText($id, $data['alt_text'] ?? null);
        }

        return [
            'success' => true,
            'message' => 'Kurs wurde aktualisiert.'
        ];
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kurs löschen
    public function deleteCourse(?array $data): array
    {
        // Prüfen, ob eine Kurs-ID übergeben wurde
        if (!$data || empty($data['id'])) {
            return [
                'success' => false,
                'message' => 'Keine Kurs-ID erhalten.'
            ];
        }

        $id = (int)$data['id'];

        // Prüfen, ob der Kurs existiert
        $existingCourse = $this->repository->findById($id);

        if (!$existingCourse) {
            return [
                'success' => false,
                'message' => 'Kurs wurde nicht gefunden.'
            ];
        }

        // Kurs aus der Datenbank löschen
        $this->repository->delete($id);

        return [
            'success' => true,
            'message' => 'Kurs wurde gelöscht.'
        ];
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kursdaten für Repository vorbereiten
    private function buildCourseData(array $data): array
    {
        return [
            'category_id' => $data['category_id'],
            'title' => trim($data['title']),
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'rating' => $data['rating'] ?? 0,
            'stock' => $data['stock'],
            'lecturer_name' => $data['lecturer_name'] ?? null,
            'lecturer_contact' => $data['lecturer_contact'] ?? null,
            'is_active' => $data['is_active'] ?? 1
        ];
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kursdaten validieren
    private function validateCourseData(array $data): array
    {
        // Kategorie muss ausgewählt sein
        if (empty($data['category_id'])) {
            return ['success' => false, 'message' => 'Bitte wähle eine Kategorie aus.'];
        }

        // Titel darf nicht leer sein
        if (empty($data['title'])) {
            return ['success' => false, 'message' => 'Titel darf nicht leer sein.'];
        }

        // Preis darf nicht negativ sein
        if (!isset($data['price']) || (float)$data['price'] < 0) {
            return ['success' => false, 'message' => 'Preis darf nicht negativ sein.'];
        }

        // Plätze dürfen nicht negativ sein
        if (!isset($data['stock']) || (int)$data['stock'] < 0) {
            return ['success' => false, 'message' => 'Plätze dürfen nicht negativ sein.'];
        }

        // Bewertung muss zwischen 0 und 5 liegen
        if (isset($data['rating']) && $data['rating'] !== '' && ((float)$data['rating'] < 0 || (float)$data['rating'] > 5)) {
            return ['success' => false, 'message' => 'Bewertung muss zwischen 0 und 5 liegen.'];
        }

        return ['success' => true];
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kursbild hochladen
    private function handleImageUpload(array $files): ?string
    {
        // Wenn kein Bild hochgeladen wurde, nichts speichern
        if (!isset($files['course_image']) || $files['course_image']['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        // Prüfen, ob beim Upload ein Fehler aufgetreten ist
        if ($files['course_image']['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Fehler beim Hochladen des Bildes.');
        }

        // Erlaubte Bildtypen definieren
        $allowedMimeTypes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp'
        ];

        // Tatsächlichen Dateityp prüfen
        $mimeType = mime_content_type($files['course_image']['tmp_name']);

        if (!isset($allowedMimeTypes[$mimeType])) {
            throw new RuntimeException('Nur JPG, PNG und WEBP sind erlaubt.');
        }

        // Eindeutigen Dateinamen erstellen
        $extension = $allowedMimeTypes[$mimeType];
        $fileName = uniqid('course_', true) . '.' . $extension;

        // Zielordner für Kursbilder
        $uploadDir = __DIR__ . '/../../public/assets/course_images/';

        // Ordner erstellen, falls er noch nicht existiert
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        $targetPath = $uploadDir . $fileName;

        // Hochgeladene Datei in den Zielordner verschieben
        if (!move_uploaded_file($files['course_image']['tmp_name'], $targetPath)) {
            throw new RuntimeException('Bild konnte nicht gespeichert werden.');
        }

        // Pfad speichern, der später im Browser verwendet werden kann
        return '/assets/course_images/' . $fileName;
    }
    // --------------------------------------------------
}