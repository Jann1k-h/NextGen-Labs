<?php

class CoursesController
{
    public function getDetails(): void
    {
        $courseId = $_GET['id'] ?? null;
        $isLoggedIn = isset($_SESSION['user_id']);

        $courseService = new CourseService();
        $result = $courseService->getDetails($courseId, $isLoggedIn);

        if (!$result['success']) {
            http_response_code($result['status']);
        }

        echo json_encode($result['data']);
    }

    public function getCategories(): void
    {
        $courseService = new CourseService();

        echo json_encode($courseService->getCategories());
    }

    public function getCourses(): void
    {
        $isLoggedIn = isset($_SESSION['user_id']);
        $categoryId = $_GET['category_id'] ?? null;
        $onlyFree = $_GET['free'] ?? 'false';

        $courseService = new CourseService();

        echo json_encode($courseService->getCourses($isLoggedIn, $categoryId, $onlyFree));
    }

    public function search(): void
    {
        $query = $_GET['query'] ?? '';
        $categoryId = $_GET['category_id'] ?? '';
        $onlyFree = isset($_GET['free']) && $_GET['free'] === 'true';
        $isLoggedIn = isset($_SESSION['user_id']);

        $courseService = new CourseService();

        echo json_encode($courseService->searchCourses($query, $categoryId, $onlyFree, $isLoggedIn));
    }
}