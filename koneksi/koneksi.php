<?php
$host = 'localhost'; // Ganti dengan host database Anda
$user = 'root';      // Ganti dengan user database Anda
$pass = '';          // Ganti dengan password database Anda
$db   = 'resepmasak'; // Ganti dengan nama database Anda

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die(json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]));
}
?>
