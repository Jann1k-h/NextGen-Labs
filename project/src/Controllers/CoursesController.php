<?php

class CoursesController
{
    private CourseRepository $courseRepository;

    public function __construct()
    {
        $this->courseRepository = new CourseRepository();
    }

    public function getDetails(): void
    {
        header('Content-Type: application/json');

        $courseId = $_GET['id'] ?? null;

        if (!$courseId) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Keine Kurs-ID angegeben'
            ]);
            exit;
        }

        $isLoggedIn = isset($_SESSION['user_id']);

        try {
            $course = $this->courseRepository->getCourseById($courseId, $isLoggedIn);

            if (!$course) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Kurs nicht gefunden'
                ]);
                exit;
            }

            echo json_encode($course);
            exit;
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'DB-Fehler'
            ]);
            exit;
        }
    }

    public function getCategories(): void
    {
        header('Content-Type: application/json');

        try {
            $categories = $this->courseRepository->getCategories();

            echo json_encode($categories);
            exit;
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'DB-Fehler'
            ]);
            exit;
        }
    }

    public function getCourses(): void
    {
        header('Content-Type: application/json');

        $isLoggedIn = isset($_SESSION['user_id']);
        $categoryId = $_GET['category_id'] ?? null;
        $onlyFree = $_GET['free'] ?? 'false';

        try {
            $courses = $this->courseRepository->getCourses($isLoggedIn, $categoryId, $onlyFree);

            echo json_encode($courses);
            exit;
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'DB-Fehler'
            ]);
            exit;
        }
    }

    // Sucht Kurse basierend auf Suchbegriff, Kategorie und "nur freie Kurse"-Filter
    public function search()
    {
        $query = $_GET['query'] ?? '';  // Suchbegriff aus Input
        $categoryId = $_GET['category_id'] ?? '';   // Kategorie-Filter
        $onlyFree = isset($_GET['free']) && $_GET['free'] === 'true';   // Filter: nur freie Kurse

        $courses = $this->courseRepository->searchCourses($query, $categoryId, $onlyFree);

        header('Content-Type: application/json');
        echo json_encode($courses);
    }
}