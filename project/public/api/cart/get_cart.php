<?php

require_once __DIR__ . '/../../../src/Core/config.php';
require_once CORE_PATH . '/bootstrap.php';

header("Content-Type: application/json");


$isLoggedIn = isset($_SESSION['user_id']);

try {

    if ($isLoggedIn) {
        
        $user_id = $_SESSION['user_id'];

        // Verbindung zur Datenbank herstellen
        $pdo = getDB();

        // Folgenden SQL-Befehl ausführen
        $stmt = $pdo->prepare("
            SELECT
                course_id,
                quantity
            FROM cart_items
            WHERE user_id = :user_id
        ");

        // SQL-Befehl ausführen
        $stmt->execute(['user_id' => $user_id]);

        // Alle Kurse als Array zurückgeben
        //    [
        //[
        //    "course_id" => 1,
        //    "quantity" => 2
        //],
        //...
        //]

        $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } else {
        $cart_items = [];

        foreach ($_SESSION['cart'] as $course_id => $quantity) {
            $cart_items[] = [
                'course_id' => $course_id,
                'quantity' => $quantity
            ];
        }
    }

    echo json_encode([
        'success' => true,
        'items' => $cart_items
    ]);
    exit;

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'DB-Fehler'
    ]);
    exit;
}