<?php
// Zugangsdaten sind Umgebungsvariablen aus System, kommen von compose.yaml
// in jeder Datei kommt dann require_once 'db.php'; und dann $pdo = getDB(); um die Verbindung zu bekommen
// Beispiel:
// $pdo = getDB();
// $stmt = $pdo->prepare("SELECT * FROM users");
// $stmt->execute();
// $user = $stmt->fetch();

function getDB() {
  static $conn;

  if (!$conn) {
      $servername = getenv('DB_HOST');
      $dbname     = getenv('DB_NAME');
      $username   = getenv('DB_USER');
      $password   = getenv('DB_PASS');

      try {
          $conn = new PDO(
              "mysql:host=$servername;dbname=$dbname;charset=utf8mb4",
              $username,
              $password
          );

          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      } catch(PDOException $e) {
          die("DB Fehler");
      }
  }

  return $conn;
}