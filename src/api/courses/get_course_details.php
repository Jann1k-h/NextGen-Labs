<?php
include_once __DIR__ . '/../../includes/core/dbaccess.php';
include_once __DIR__ . '/../../includes/core/session.php';

$isLoggedIn = isset($_SESSION['user_id']);
$course_id = $_GET['id'] ?? null;

try {

    // Verbindung zur Datenbank herstellen
    $pdo = getDB();

    // SQL-Befehl vorbereiten
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

    // Folgenden SQL-Befehl ausführen
    $stmt = $pdo->prepare($sql);


    // SQL-Befehl ausführen
    $stmt->execute(['id' => $course_id]);

    //Einen Kurs als Array zurückgeben
    //    [
    //[
    //    "id" => 1,
    //    "name" => "Programmierung"
    //],
    //...
    //]

    $course = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$course) {
        echo json_encode([
            'success' => false,
            'message' => 'Kurs nicht gefunden'
        ]);
        exit;
    }

    echo json_encode($course);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'DB-Fehler'
    ]);
}