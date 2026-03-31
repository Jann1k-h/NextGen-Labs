<?php
// Zugangsdaten sind Umgebungsvariablen aus System, kommen von compose.yaml
// in jeder Datei kommt dann require_once 'db.php';
$servername = getenv('DB_HOST');
$dbname     = getenv('DB_NAME');
$username   = getenv('DB_USER');
$password   = getenv('DB_PASS');

try {
  $conn = new PDO("mysql:host=$servername;dbname=,$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "Connected successfully";
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
?>