<?php

class CoursesController
{
    // --------------------------------------------------
    // Kursdetails laden
    public function getDetails(): void
    {
        // Kurs-ID aus der URL lesen
        $courseId = $_GET['id'] ?? null;

        // Prüfen, ob der User eingeloggt ist
        $isLoggedIn = isset($_SESSION['user_id']);

        // Service erstellen und Kursdetails laden
        $courseService = new CourseService();
        $result = $courseService->getDetails($courseId, $isLoggedIn);

        // Falls ein Fehler auftritt, passenden HTTP-Status setzen
        if (!$result['success']) {
            http_response_code($result['status']);
        }

        // Ergebnisdaten als JSON zurückgeben
        echo json_encode($result['data']);
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kategorien laden
    public function getCategories(): void
    {
        // Service erstellen und Kategorien laden
        $courseService = new CourseService();

        // Kategorien als JSON zurückgeben
        echo json_encode($courseService->getCategories());
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kurse laden
    public function getCourses(): void
    {
        // Prüfen, ob der User eingeloggt ist
        $isLoggedIn = isset($_SESSION['user_id']);

        // Filterwerte aus der URL lesen
        $categoryId = $_GET['category_id'] ?? null;
        $onlyFree = $_GET['free'] ?? 'false';

        // Service erstellen und Kurse mit Filter laden
        $courseService = new CourseService();

        // Kurse als JSON zurückgeben
        echo json_encode($courseService->getCourses($isLoggedIn, $categoryId, $onlyFree));
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kurse suchen
    public function search(): void
    {
        // Such- und Filterwerte aus der URL lesen
        $query = $_GET['query'] ?? '';
        $categoryId = $_GET['category_id'] ?? '';
        $onlyFree = isset($_GET['free']) && $_GET['free'] === 'true';

        // Prüfen, ob der User eingeloggt ist
        $isLoggedIn = isset($_SESSION['user_id']);

        // Service erstellen und passende Kurse suchen
        $courseService = new CourseService();

        // Suchergebnisse als JSON zurückgeben
        echo json_encode($courseService->searchCourses($query, $categoryId, $onlyFree, $isLoggedIn));
    }
    // --------------------------------------------------
}