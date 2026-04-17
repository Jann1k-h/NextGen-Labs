<?php
include_once __DIR__ . '/../../includes/core/dbaccess.php';
include_once __DIR__ . '/../../includes/core/session.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);


$isLoggedIn = isset($_SESSION['user_id']);


$course_id = $data['course_id'];

try {

    // Verbindung zur Datenbank herstellen
    $pdo = getDB();

    if ($isLoggedIn) {

        $user_id = $_SESSION['user_id'];

        // prüfen ob schon im Warenkorb
        $stmt = $pdo->prepare("
            SELECT quantity FROM cart_items 
            WHERE user_id = :user_id AND course_id = :course_id
        ");

        // SQL-Befehl ausführen
        $stmt->execute([
            'user_id' => $user_id,
            'course_id' => $course_id
        ]);

        $existing = $stmt->fetch();

        if ($existing) {
            // erhöhen
            $stmt = $pdo->prepare("
                UPDATE cart_items 
                SET quantity = quantity + 1 
                WHERE user_id = :user_id AND course_id = :course_id
            ");
        } else {
            // neu einfügen
            $stmt = $pdo->prepare("
                INSERT INTO cart_items (user_id, course_id, quantity) 
                VALUES (:user_id, :course_id, 1)
            ");
        }

        // SQL-Befehl ausführen
        $stmt->execute([
            'user_id' => $user_id,
            'course_id' => $course_id
        ]);

    } else {
        // 🟡 SESSION CART
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$course_id])) {
            $_SESSION['cart'][$course_id]++;
        } else {
            $_SESSION['cart'][$course_id] = 1;
        }
    }

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'DB-Fehler'
    ]);
}