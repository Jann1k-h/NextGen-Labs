<?php

class CourseRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = getDB();
    }

    public function getCourseById($courseId, bool $isLoggedIn): ?array
    {
        $sql = "
            SELECT
                courses.id,
                courses.title,
                courses.description,
                courses.price,
                courses.rating,
                courses.stock,
        ";

        if ($isLoggedIn) {
            $sql .= "
                courses.lecturer_name AS lecturer_name,
                courses.lecturer_contact AS lecturer_contact,
            ";
        } else {
            $sql .= "
                NULL AS lecturer_name,
                NULL AS lecturer_contact,
            ";
        }

        $sql .= "
                courses.created_at,
                courses.updated_at,

                categories.name AS category_name,
                categories.description AS category_description,

                course_images.image_path AS course_image,
                course_images.alt_text AS course_image_alt

            FROM courses
            JOIN categories ON courses.category_id = categories.id
            LEFT JOIN course_images ON courses.id = course_images.course_id
            WHERE courses.is_active = 1
            AND courses.id = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            'id' => $courseId
        ]);

        $course = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$course) {
            return null;
        }

        return $course;
    }

    public function getCategories(): array
    {
        $sql = "
            SELECT
                id,
                name
            FROM categories
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCourses(bool $isLoggedIn, $categoryId, $onlyFree): array
    {
        $sql = "
            SELECT
                courses.id,
                courses.title,
                courses.price,
                courses.rating,
                courses.stock,
        ";

        if ($isLoggedIn) {
            $sql .= "
                courses.lecturer_name AS lecturer_name,
                courses.lecturer_contact AS lecturer_contact,
            ";
        } else {
            $sql .= "
                NULL AS lecturer_name,
                NULL AS lecturer_contact,
            ";
        }

        $sql .= "
                categories.name AS category_name,
                course_images.image_path AS course_image,
                course_images.alt_text AS course_image_alt

            FROM courses
            JOIN categories ON courses.category_id = categories.id
            LEFT JOIN course_images ON courses.id = course_images.course_id
            WHERE courses.is_active = 1
        ";

        $params = [];

        if ($categoryId !== null && $categoryId !== '') {
            $sql .= " AND courses.category_id = :category_id";
            $params['category_id'] = $categoryId;
        }

        if ($onlyFree === 'true') {
            $sql .= " AND courses.stock > 0";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}