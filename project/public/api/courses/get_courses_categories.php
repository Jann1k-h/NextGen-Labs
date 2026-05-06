<?php

require_once __DIR__ . '/../../../src/Core/config.php';
require_once CORE_PATH . '/bootstrap.php';

header("Content-Type: application/json");

try {

    // Verbindung zur Datenbank herstellen
    $pdo = getDB();

    // Folgenden SQL-Befehl ausführen
    $stmt = $pdo->prepare("
        SELECT
            id,
            name
        FROM categories
    ");

    // SQL-Befehl ausführen
    $stmt->execute();

    // Alle Kurse als Array zurückgeben
    //    [
    //[
    //    "id" => 1,
    //    "name" => "Programmierung"
    //],
    //...
    //]

    $courses_categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($courses_categories);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'DB-Fehler'
    ]);
    exit;
}