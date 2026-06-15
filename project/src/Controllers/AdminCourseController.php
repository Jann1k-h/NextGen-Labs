<?php

class AdminCourseController
{
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

    public function get(): void
    {
        if (!$this->requireAdmin()) {
            return;
        }

        $service = new AdminCourseService();

        echo json_encode($service->getCourses());
    }

    public function getCategories(): void
    {

        if (!$this->requireAdmin()) {
            return;
        }

        $service = new AdminCourseService();

        echo json_encode($service->getCategories());
    }

    public function create(): void
    {
        if (!$this->requireAdmin()) {
            return;
        }

        try {
            $service = new AdminCourseService();
            echo json_encode($service->createCourse($_POST, $_FILES));
        } catch (RuntimeException $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(): void
    {
        if (!$this->requireAdmin()) {
            return;
        }

        try {
            $service = new AdminCourseService();
            echo json_encode($service->updateCourse($_POST, $_FILES));
        } catch (RuntimeException $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function delete(): void
    {
        if (!$this->requireAdmin()) {
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        $service = new AdminCourseService();

        echo json_encode($service->deleteCourse($data));
    }
}