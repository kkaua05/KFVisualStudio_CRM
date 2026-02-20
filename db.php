<?php
$host = 'localhost';
$user = 'root';
$pass = ''; // Senha padrão do XAMPP é vazia
$db   = 'kf_visual_db';

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Falha na conexão: " . mysqli_connect_error());
}

// Configurar charset para evitar problemas com acentos
mysqli_set_charset($conn, "utf8mb4");
?>