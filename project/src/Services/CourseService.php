<?php

class CourseService
{
    private CourseRepository $courseRepository;

    // --------------------------------------------------
    // Repository vorbereiten
    public function __construct()
    {
        // Repository erstellen, damit der Service auf Kursdaten zugreifen kann
        $this->courseRepository = new CourseRepository();
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kursdetails laden
    public function getDetails($courseId, bool $isLoggedIn): array
    {
        // Prüfen, ob eine Kurs-ID übergeben wurde
        if (!$courseId) {
            return [
                'success' => false,
                'status' => 400,
                'data' => [
                    'success' => false,
                    'message' => 'Keine Kurs-ID angegeben'
                ]
            ];
        }

        // Kurs anhand der ID laden
        $course = $this->courseRepository->getCourseById($courseId, $isLoggedIn);

        // Prüfen, ob der Kurs gefunden wurde
        if (!$course) {
            return [
                'success' => false,
                'status' => 404,
                'data' => [
                    'success' => false,
                    'message' => 'Kurs nicht gefunden'
                ]
            ];
        }

        // Kursdaten zurückgeben
        return [
            'success' => true,
            'status' => 200,
            'data' => $course
        ];
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kategorien laden
    public function getCategories(): array
    {
        // Kategorien aus der Datenbank laden
        return $this->courseRepository->getCategories();
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kurse laden
    public function getCourses(bool $isLoggedIn, $categoryId, $onlyFree): array
    {
        // Kurse mit möglichen Filtern aus der Datenbank laden
        return $this->courseRepository->getCourses($isLoggedIn, $categoryId, $onlyFree);
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kurse suchen
    public function searchCourses(string $query, $categoryId, bool $onlyFree, bool $isLoggedIn): array
    {
        // Kurse anhand von Suchbegriff und Filtern suchen
        return $this->courseRepository->searchCourses($query, $categoryId, $onlyFree, $isLoggedIn);
    }
    // --------------------------------------------------
}