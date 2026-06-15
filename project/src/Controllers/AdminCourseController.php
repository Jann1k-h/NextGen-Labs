<?php

class AdminCourseController extends BaseController
{
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