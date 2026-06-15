<?php

class AdminCourseService
{
    private AdminCourseRepository $repository;

    public function __construct()
    {
        $this->repository = new AdminCourseRepository();
    }

    public function getCourses(): array
    {
        return [
            'success' => true,
            'courses' => $this->repository->findAll()
        ];
    }

    public function getCategories(): array
    {
        return [
            'success' => true,
            'categories' => $this->repository->findCategories()
        ];
    }

    public function createCourse(array $data, array $files): array
    {
        $validation = $this->validateCourseData($data);

        if (!$validation['success']) {
            return $validation;
        }

        $courseId = $this->repository->create($this->buildCourseData($data));

        $imagePath = $this->handleImageUpload($files);

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

    public function updateCourse(array $data, array $files): array
    {
        if (empty($data['id'])) {
            return [
                'success' => false,
                'message' => 'Keine Kurs-ID erhalten.'
            ];
        }

        $id = (int)$data['id'];

        $existingCourse = $this->repository->findById($id);

        if (!$existingCourse) {
            return [
                'success' => false,
                'message' => 'Kurs wurde nicht gefunden.'
            ];
        }

        $validation = $this->validateCourseData($data);

        if (!$validation['success']) {
            return $validation;
        }

        $this->repository->update($id, $this->buildCourseData($data));

        $imagePath = $this->handleImageUpload($files);

        if ($imagePath !== null) {
            $this->repository->deleteCoverImage($id);

            $this->repository->createImage([
                'course_id' => $id,
                'image_path' => $imagePath,
                'alt_text' => $data['alt_text'] ?? null,
                'sort_order' => 0,
                'is_cover' => 1
            ]);
        } else {
            $this->repository->updateCoverImageAltText($id, $data['alt_text'] ?? null);
        }

        return [
            'success' => true,
            'message' => 'Kurs wurde aktualisiert.'
        ];
    }

    public function deleteCourse(?array $data): array
    {
        if (!$data || empty($data['id'])) {
            return [
                'success' => false,
                'message' => 'Keine Kurs-ID erhalten.'
            ];
        }

        $id = (int)$data['id'];

        $existingCourse = $this->repository->findById($id);

        if (!$existingCourse) {
            return [
                'success' => false,
                'message' => 'Kurs wurde nicht gefunden.'
            ];
        }

        $this->repository->delete($id);

        return [
            'success' => true,
            'message' => 'Kurs wurde gelöscht.'
        ];
    }

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

    private function validateCourseData(array $data): array
    {
        if (empty($data['category_id'])) {
            return ['success' => false, 'message' => 'Bitte wähle eine Kategorie aus.'];
        }

        if (empty($data['title'])) {
            return ['success' => false, 'message' => 'Titel darf nicht leer sein.'];
        }

        if (!isset($data['price']) || (float)$data['price'] < 0) {
            return ['success' => false, 'message' => 'Preis darf nicht negativ sein.'];
        }

        if (!isset($data['stock']) || (int)$data['stock'] < 0) {
            return ['success' => false, 'message' => 'Plätze dürfen nicht negativ sein.'];
        }

        if (isset($data['rating']) && $data['rating'] !== '' && ((float)$data['rating'] < 0 || (float)$data['rating'] > 5)) {
            return ['success' => false, 'message' => 'Bewertung muss zwischen 0 und 5 liegen.'];
        }

        return ['success' => true];
    }

    private function handleImageUpload(array $files): ?string
    {
        if (!isset($files['course_image']) || $files['course_image']['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($files['course_image']['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Fehler beim Hochladen des Bildes.');
        }

        $allowedMimeTypes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp'
        ];

        $mimeType = mime_content_type($files['course_image']['tmp_name']);

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

        if (!move_uploaded_file($files['course_image']['tmp_name'], $targetPath)) {
            throw new RuntimeException('Bild konnte nicht gespeichert werden.');
        }

        return '/assets/course_images/' . $fileName;
    }
}