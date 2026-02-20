<?php
session_start();

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['usuario'];
    $pass = $_POST['senha'];

    // Login Hardcoded para simplicidade (Em produção, usar banco de dados)
    if ($user === 'admin' && $pass === 'admin123') {
        $_SESSION['usuario'] = $user;
        header("Location: ../dashboard.php");
    } else {
        header("Location: ../index.php?error=1");
    }
}
?>