<?

// Braucht man, da logout.php eine eigene HTTP-Anfrage ist und nicht innerhalb von index.php mitläuft
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

session_destroy();

header('Content-Type: application/json');

echo json_encode([
    'success' => true,
    'message' => 'Logged out'
]);

exit;