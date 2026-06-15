<?php

class AdminCourseController extends BaseController
{
    // --------------------------------------------------
    // Alle Kurse für Admin laden
    public function get(): void
    {
        // Prüfen, ob User Admin ist
        if (!$this->requireAdmin()) {
            return;
        }

        // Service erstellen und Kurse laden
        $service = new AdminCourseService();

        // Ergebnis als JSON zurückgeben
        echo json_encode($service->getCourses());
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kategorien für Kursformular laden
    public function getCategories(): void
    {
        // Prüfen, ob User Admin ist
        if (!$this->requireAdmin()) {
            return;
        }

        // Service erstellen und Kategorien laden
        $service = new AdminCourseService();

        // Ergebnis als JSON zurückgeben
        echo json_encode($service->getCategories());
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Neuen Kurs erstellen
    public function create(): void
    {
        // Prüfen, ob User Admin ist
        if (!$this->requireAdmin()) {
            return;
        }

        try {
            // Service erstellen und Kurs mit Formular- und Bilddaten erstellen
            $service = new AdminCourseService();
            $result = $service->createCourse($_POST, $_FILES);

            // Ergebnis als JSON zurückgeben
            echo json_encode($result);
        } catch (RuntimeException $e) {
            // Fehler beim Bild-Upload oder Speichern zurückgeben
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kurs aktualisieren
    public function update(): void
    {
        // Prüfen, ob User Admin ist
        if (!$this->requireAdmin()) {
            return;
        }

        try {
            // Service erstellen und Kurs mit Formular- und Bilddaten aktualisieren
            $service = new AdminCourseService();
            $result = $service->updateCourse($_POST, $_FILES);

            // Ergebnis als JSON zurückgeben
            echo json_encode($result);
        } catch (RuntimeException $e) {
            // Fehler beim Bild-Upload oder Speichern zurückgeben
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kurs deaktivieren
    public function delete(): void
    {
        // Prüfen, ob User Admin ist
        if (!$this->requireAdmin()) {
            return;
        }

        // JSON-Daten aus dem Request lesen
        $data = json_decode(file_get_contents('php://input'), true);

        // Service erstellen und Kurs deaktivieren
        $service = new AdminCourseService();
        $result = $service->deleteCourse($data);

        // Ergebnis als JSON zurückgeben
        echo json_encode($result);
    }
    // --------------------------------------------------
}