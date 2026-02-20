<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KF Visual Studio CRM</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Ícones -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <i class="ri-palette-line"></i>
                <span>KF Visual</span>
            </div>
            <nav class="menu">
                <a href="dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">
                    <i class="ri-dashboard-line"></i> Dashboard
                </a>
                <a href="clientes.php" class="<?= basename($_SERVER['PHP_SELF']) == 'clientes.php' ? 'active' : '' ?>">
                    <i class="ri-user-smile-line"></i> Clientes
                </a>
                <a href="financeiro.php" class="<?= basename($_SERVER['PHP_SELF']) == 'financeiro.php' ? 'active' : '' ?>">
                    <i class="ri-money-dollar-circle-line"></i> Financeiro
                </a>
            </nav>
            <div class="logout-area">
                <a href="actions/auth.php?logout=true">
                    <i class="ri-logout-box-line"></i> Sair
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="top-bar">
                <h2 id="page-title">Dashboard</h2>
                <div class="user-info">
                    <span>Admin</span>
                    <div class="avatar"><i class="ri-user-fill"></i></div>
                </div>
            </header>
            <div class="content-wrapper">