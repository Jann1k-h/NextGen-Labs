<?php

include_once __DIR__ . '/../../includes/core/dbaccess.php';
include_once __DIR__ . '/../../includes/core/session.php';

header("Content-Type: application/json");

$isLoggedIn = isset($_SESSION['user_id']);
$categoryId = $_GET['category_id'] ?? null;

try {

    // Verbindung zur Datenbank herstellen
    $pdo = getDB();

    // SQL-Befehl vorbereiten
    $sql = "
        SELECT
            courses.id,
            courses.title,
            courses.price,
            courses.rating,
            courses.stock,
            " . ($isLoggedIn ? 'courses.lecturer_name AS lecturer_name' : 'NULL AS lecturer_name') . ",
            " . ($isLoggedIn ? 'courses.lecturer_contact AS lecturer_contact' : 'NULL AS lecturer_contact') . ",
            categories.name AS category_name,
            course_images.image_path AS course_image,
            course_images.alt_text AS course_image_alt
        FROM courses
        JOIN categories ON courses.category_id = categories.id
        LEFT JOIN course_images ON courses.id = course_images.course_id
        WHERE courses.is_active = 1
    ";

    if ($categoryId !== null && $categoryId !== '') {
        $sql .= " AND courses.category_id = :category_id";
    }

    // Folgenden SQL-Befehl ausführen
    $stmt = $pdo->prepare($sql);

    // SQL-Befehl ausführen in Abhängigkeit davon, ob eine Kategorie ausgewählt wurde oder nicht
    if ($categoryId !== null && $categoryId !== '') {
        $stmt->execute([
            'category_id' => $categoryId
        ]);
    } else {
        $stmt->execute();
    }


    // Alle Kurse als Array zurückgeben
    //    [
    //[
    //    "id" => 1,
    //    "title" => "Java Kurs",
    //    "price" => "99.00",
    //    "rating" => "4.5"
    //    "stock" => 10,
    //    Wenn $isLoggedIn true ist:
    //    "lecturer_name" => "Max Mustermann",
    //    "lecturer_contact" => "max.mustermann@example.com",
    //
    //    "category_name" => "Programmierung"
    //    "course_image" => "path/to/image.jpg"
    //    "course_image_alt" => "Alternativer Text für das Kursbild"
    //],
    //...
    //]
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($courses);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'DB-Fehler'
    ]);
    exit;
}