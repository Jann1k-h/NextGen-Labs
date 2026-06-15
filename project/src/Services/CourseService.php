<?php

class CourseService
{
    private CourseRepository $courseRepository;

    public function __construct()
    {
        $this->courseRepository = new CourseRepository();
    }

    public function getDetails($courseId, bool $isLoggedIn): array
    {
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

        $course = $this->courseRepository->getCourseById($courseId, $isLoggedIn);

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

        return [
            'success' => true,
            'status' => 200,
            'data' => $course
        ];
    }

    public function getCategories(): array
    {
        return $this->courseRepository->getCategories();
    }

    public function getCourses(bool $isLoggedIn, $categoryId, $onlyFree): array
    {
        return $this->courseRepository->getCourses($isLoggedIn, $categoryId, $onlyFree);
    }

    public function searchCourses(string $query, $categoryId, bool $onlyFree, bool $isLoggedIn): array
    {
        return $this->courseRepository->searchCourses($query, $categoryId, $onlyFree, $isLoggedIn);
    }
}