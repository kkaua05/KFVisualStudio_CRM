<?php session_start(); if(isset($_SESSION['usuario'])) header("Location: dashboard.php"); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - KF Visual</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { background: #0f172a; color: white; font-family: 'Inter'; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: #1e293b; padding: 40px; border-radius: 16px; width: 100%; max-width: 400px; border: 1px solid rgba(255,255,255,0.1); }
        input { width: 100%; padding: 12px; margin: 10px 0; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 8px; }
        button { width: 100%; padding: 12px; background: #6366f1; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; margin-top: 10px; }
        h2 { text-align: center; margin-bottom: 20px; color: #6366f1; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>KF Visual Studio</h2>
        <form action="actions/auth.php" method="POST">
            <input type="text" name="usuario" placeholder="Usuário (admin)" required>
            <input type="password" name="senha" placeholder="Senha (admin123)" required>
            <button type="submit">Entrar</button>
        </form>
        <?php if(isset($_GET['error'])) echo "<p style='color:red; text-align:center; font-size:0.8rem; margin-top:10px;'>Credenciais inválidas</p>"; ?>
    </div>
</body>
</html>