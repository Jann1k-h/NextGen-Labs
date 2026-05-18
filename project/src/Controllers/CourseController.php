<?php

class CoursesController
{
    public function getDetails(): void
    {
        // Daten kommen aus der URL
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

        $courseRepository = new CourseRepository();
        $course = $courseRepository->getCourseById($courseId, $isLoggedIn);

        if (!$course) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Kurs nicht gefunden'
            ]);
            exit;
        }

        echo json_encode($course);
    }

    public function getCategories(): void
    {
        header('Content-Type: application/json');

        $courseRepository = new CourseRepository();
        $categories = $courseRepository->getCategories();

        echo json_encode($categories);
    }

    public function getCourses(): void
    {
        header('Content-Type: application/json');

        $isLoggedIn = isset($_SESSION['user_id']);
        $categoryId = $_GET['category_id'] ?? null;
        $onlyFree = $_GET['free'] ?? 'false';

        $courseRepository = new CourseRepository();
        $courses = $courseRepository->getCourses($isLoggedIn, $categoryId, $onlyFree);

        echo json_encode($courses);
    }

    // Sucht Kurse basierend auf Suchbegriff, Kategorie und "nur freie Kurse"-Filter
    public function search(): void
    {
        header('Content-Type: application/json');

        $query = $_GET['query'] ?? '';
        $categoryId = $_GET['category_id'] ?? '';
        $onlyFree = isset($_GET['free']) && $_GET['free'] === 'true';
        $isLoggedIn = isset($_SESSION['user_id']);

        $courseRepository = new CourseRepository();
        $courses = $courseRepository->searchCourses($query, $categoryId, $onlyFree, $isLoggedIn);

        echo json_encode($courses);
    }
}