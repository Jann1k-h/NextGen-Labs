<?php

class AdminCourseRepository
{
    // --------------------------------------------------
    // Alle Kurse für Admin laden
    public function findAll(): array
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            SELECT
                courses.id,
                courses.category_id,
                courses.title,
                courses.description,
                courses.price,
                courses.rating,
                courses.stock,
                courses.lecturer_name,
                courses.lecturer_contact,
                courses.is_active,
                courses.created_at,
                courses.updated_at,
                categories.name AS category_name,
                course_images.image_path AS course_image,
                course_images.alt_text AS course_image_alt
            FROM courses
            LEFT JOIN categories ON courses.category_id = categories.id
            LEFT JOIN course_images 
                ON courses.id = course_images.course_id
                AND course_images.is_cover = 1
            ORDER BY courses.id DESC
        ");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kurs anhand ID finden
    public function findById(int $id): ?array
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            SELECT *
            FROM courses
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute([
            'id' => $id
        ]);

        $course = $stmt->fetch(PDO::FETCH_ASSOC);

        return $course ?: null;
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kategorien laden
    public function findCategories(): array
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            SELECT id, name
            FROM categories
            ORDER BY name ASC
        ");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kurs erstellen
    public function create(array $data): int
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            INSERT INTO courses (
                category_id,
                title,
                description,
                price,
                rating,
                stock,
                lecturer_name,
                lecturer_contact,
                is_active
            ) VALUES (
                :category_id,
                :title,
                :description,
                :price,
                :rating,
                :stock,
                :lecturer_name,
                :lecturer_contact,
                :is_active
            )
        ");

        $stmt->execute([
            'category_id' => $data['category_id'],
            'title' => trim($data['title']),
            'description' => $data['description'],
            'price' => $data['price'],
            'rating' => $data['rating'],
            'stock' => $data['stock'],
            'lecturer_name' => $data['lecturer_name'],
            'lecturer_contact' => $data['lecturer_contact'],
            'is_active' => $data['is_active']
        ]);

        return (int)$pdo->lastInsertId();
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kurs bearbeiten
    public function update(int $id, array $data): bool
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            UPDATE courses
            SET
                category_id = :category_id,
                title = :title,
                description = :description,
                price = :price,
                rating = :rating,
                stock = :stock,
                lecturer_name = :lecturer_name,
                lecturer_contact = :lecturer_contact,
                is_active = :is_active
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'category_id' => $data['category_id'],
            'title' => trim($data['title']),
            'description' => $data['description'],
            'price' => $data['price'],
            'rating' => $data['rating'],
            'stock' => $data['stock'],
            'lecturer_name' => $data['lecturer_name'],
            'lecturer_contact' => $data['lecturer_contact'],
            'is_active' => $data['is_active']
        ]);
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kurs löschen
    public function delete(int $id): bool
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            UPDATE courses
            SET is_active = 0
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id
        ]);
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Kursbild erstellen
    public function createImage(array $data): bool
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            INSERT INTO course_images (
                course_id,
                image_path,
                alt_text,
                sort_order,
                is_cover
            ) VALUES (
                :course_id,
                :image_path,
                :alt_text,
                :sort_order,
                :is_cover
            )
        ");

        return $stmt->execute([
            'course_id' => $data['course_id'],
            'image_path' => $data['image_path'],
            'alt_text' => $data['alt_text'],
            'sort_order' => $data['sort_order'],
            'is_cover' => $data['is_cover']
        ]);
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Bestehendes Cover-Bild löschen
    public function deleteCoverImage(int $courseId): bool
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            DELETE FROM course_images
            WHERE course_id = :course_id
            AND is_cover = 1
        ");

        return $stmt->execute([
            'course_id' => $courseId
        ]);
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Alt-Text vom Cover-Bild aktualisieren
    public function updateCoverImageAltText(int $courseId, ?string $altText): bool
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            UPDATE course_images
            SET alt_text = :alt_text
            WHERE course_id = :course_id
            AND is_cover = 1
        ");

        return $stmt->execute([
            'course_id' => $courseId,
            'alt_text' => $altText
        ]);
    }
    // --------------------------------------------------
}