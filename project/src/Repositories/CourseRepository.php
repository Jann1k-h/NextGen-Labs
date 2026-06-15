<?php

class CourseRepository
{
    // --------------------------------------------------
    // Kurs anhand ID Laden
    public function getCourseById($courseId, bool $isLoggedIn): ?array
    {
        $pdo = getDB();

        $sql = "
            SELECT
                courses.id,
                courses.title,
                courses.description,
                courses.price,
                courses.rating,
                courses.stock,
        ";

        if ($isLoggedIn) { // nur mit Login Einsicht möglich
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

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'id' => $courseId
        ]);

        $course = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$course) {
            return null;
        }

        return $course;
    }
    // --------------------------------------------------

    
    // --------------------------------------------------
    // Alle Kategorien laden
    public function getCategories(): array
    {
        $pdo = getDB();

        $sql = "
            SELECT
                id,
                name
            FROM categories
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCourses(bool $isLoggedIn, $categoryId, $onlyFree): array
    {
        $pdo = getDB();

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

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Live-Suche für Kurse
    // Möglich mit Suchbegriff, Kategorie und nur freie Kurse
    public function searchCourses($query, $categoryId, $onlyFree, bool $isLoggedIn): array
    {
        $pdo = getDB();
        
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
                categories.name AS category_name,
                course_images.image_path AS course_image,
                course_images.alt_text AS course_image_alt
            FROM courses
            JOIN categories ON courses.category_id = categories.id
            LEFT JOIN course_images ON courses.id = course_images.course_id
            WHERE courses.is_active = 1
        ";

        $params = [];

        // Filter nach Suchbegriff
        if ($query !== '') {
            if ($isLoggedIn) {
                $sql .= " AND (
                    courses.title LIKE :query
                    OR courses.description LIKE :query
                    OR courses.lecturer_name LIKE :query
                )";
            } else {
                $sql .= " AND (
                    courses.title LIKE :query
                    OR courses.description LIKE :query
                )";
            }

            $params['query'] = "%$query%";
        }

        // Kategorie-Filter
        if ($categoryId !== '') {
            $sql .= " AND courses.category_id = :categoryId";
            $params['categoryId'] = $categoryId;
        }

        // Nur freie Kurse
        if ($onlyFree) {
            $sql .= " AND courses.stock > 0";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // --------------------------------------------------
}