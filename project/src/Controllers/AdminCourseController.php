<?php

class AdminCourseController
{
    // --------------------------------------------------
    // Prüfen, ob User Admin ist
    private function requireAdmin(): bool
    {
        if (!isset($_SESSION['user_id']) || (int)($_SESSION['is_admin'] ?? 0) !== 1) {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'message' => 'Keine Berechtigung.'
            ]);
            return false;
        }

        return true;
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Alle Kurse für Admin laden
    public function get(): void
    {
        header('Content-Type: application/json');

        if (!$this->requireAdmin()) {
            return;
        }

        $repository = new AdminCourseRepository();
        $courses = $repository->findAll();

        echo json_encode([
            'success' => true,
            'courses' => $courses
        ]);
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kategorien laden
    public function getCategories(): void
    {
        header('Content-Type: application/json');

        if (!$this->requireAdmin()) {
            return;
        }

        $repository = new AdminCourseRepository();
        $categories = $repository->findCategories();

        echo json_encode([
            'success' => true,
            'categories' => $categories
        ]);
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kurs erstellen
    public function create(): void
    {
        header('Content-Type: application/json');

        if (!$this->requireAdmin()) {
            return;
        }

        $repository = new AdminCourseRepository();

        $validation = $this->validateCourseData($_POST);

        if (!$validation['success']) {
            echo json_encode($validation);
            return;
        }

        $imagePath = $this->handleImageUpload();

        $courseId = $repository->create([
            'category_id' => $_POST['category_id'],
            'title' => $_POST['title'],
            'description' => $_POST['description'] ?? null,
            'price' => $_POST['price'],
            'rating' => $_POST['rating'] ?? 0,
            'stock' => $_POST['stock'],
            'lecturer_name' => $_POST['lecturer_name'] ?? null,
            'lecturer_contact' => $_POST['lecturer_contact'] ?? null,
            'is_active' => $_POST['is_active'] ?? 1
        ]);

        if ($imagePath !== null) {
            $repository->createImage([
                'course_id' => $courseId,
                'image_path' => $imagePath,
                'alt_text' => $_POST['alt_text'] ?? null,
                'sort_order' => 0,
                'is_cover' => 1
            ]);
        }

        echo json_encode([
            'success' => true,
            'message' => 'Kurs wurde erstellt.'
        ]);
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kurs bearbeiten
    public function update(): void
    {
        header('Content-Type: application/json');

        if (!$this->requireAdmin()) {
            return;
        }

        if (empty($_POST['id'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Keine Kurs-ID erhalten.'
            ]);
            return;
        }

        $id = (int)$_POST['id'];
        $repository = new AdminCourseRepository();

        $existingCourse = $repository->findById($id);

        if (!$existingCourse) {
            echo json_encode([
                'success' => false,
                'message' => 'Kurs wurde nicht gefunden.'
            ]);
            return;
        }

        $validation = $this->validateCourseData($_POST);

        if (!$validation['success']) {
            echo json_encode($validation);
            return;
        }

        $repository->update($id, [
            'category_id' => $_POST['category_id'],
            'title' => $_POST['title'],
            'description' => $_POST['description'] ?? null,
            'price' => $_POST['price'],
            'rating' => $_POST['rating'] ?? 0,
            'stock' => $_POST['stock'],
            'lecturer_name' => $_POST['lecturer_name'] ?? null,
            'lecturer_contact' => $_POST['lecturer_contact'] ?? null,
            'is_active' => $_POST['is_active'] ?? 1
        ]);

        $imagePath = $this->handleImageUpload();

        if ($imagePath !== null) {
            $repository->deleteCoverImage($id);

            $repository->createImage([
                'course_id' => $id,
                'image_path' => $imagePath,
                'alt_text' => $_POST['alt_text'] ?? null,
                'sort_order' => 0,
                'is_cover' => 1
            ]);
        } else {
            $repository->updateCoverImageAltText($id, $_POST['alt_text'] ?? null);
        }

        echo json_encode([
            'success' => true,
            'message' => 'Kurs wurde aktualisiert.'
        ]);
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kurs löschen
    public function delete(): void
    {
        header('Content-Type: application/json');

        if (!$this->requireAdmin()) {
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || empty($data['id'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Keine Kurs-ID erhalten.'
            ]);
            return;
        }

        $id = (int)$data['id'];

        $repository = new AdminCourseRepository();
        $existingCourse = $repository->findById($id);

        if (!$existingCourse) {
            echo json_encode([
                'success' => false,
                'message' => 'Kurs wurde nicht gefunden.'
            ]);
            return;
        }

        $repository->delete($id);

        echo json_encode([
            'success' => true,
            'message' => 'Kurs wurde gelöscht.'
        ]);
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kursdaten validieren
    private function validateCourseData(array $data): array
    {
        if (empty($data['category_id'])) {
            return [
                'success' => false,
                'message' => 'Bitte wähle eine Kategorie aus.'
            ];
        }

        if (empty($data['title'])) {
            return [
                'success' => false,
                'message' => 'Titel darf nicht leer sein.'
            ];
        }

        if (!isset($data['price']) || (float)$data['price'] < 0) {
            return [
                'success' => false,
                'message' => 'Preis darf nicht negativ sein.'
            ];
        }

        if (!isset($data['stock']) || (int)$data['stock'] < 0) {
            return [
                'success' => false,
                'message' => 'Plätze dürfen nicht negativ sein.'
            ];
        }

        if (isset($data['rating']) && $data['rating'] !== '' && ((float)$data['rating'] < 0 || (float)$data['rating'] > 5)) {
            return [
                'success' => false,
                'message' => 'Bewertung muss zwischen 0 und 5 liegen.'
            ];
        }

        return [
            'success' => true
        ];
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Bild Upload verarbeiten
    private function handleImageUpload(): ?string
    {
        if (!isset($_FILES['course_image']) || $_FILES['course_image']['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($_FILES['course_image']['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Fehler beim Hochladen des Bildes.');
        }

        $allowedMimeTypes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp'
        ];

        $mimeType = mime_content_type($_FILES['course_image']['tmp_name']);

        if (!isset($allowedMimeTypes[$mimeType])) {
            throw new RuntimeException('Nur JPG, PNG und WEBP sind erlaubt.');
        }

        $extension = $allowedMimeTypes[$mimeType];
        $fileName = uniqid('course_', true) . '.' . $extension;

        $uploadDir = __DIR__ . '/../../public/assets/course_images/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        $targetPath = $uploadDir . $fileName;

        if (!move_uploaded_file($_FILES['course_image']['tmp_name'], $targetPath)) {
            throw new RuntimeException('Bild konnte nicht gespeichert werden.');
        }

        return '/assets/course_images/' . $fileName;
    }
    // --------------------------------------------------
}