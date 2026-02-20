<?php
require '../config/db.php';

$nome = mysqli_real_escape_string($conn, $_POST['nome']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$servico = mysqli_real_escape_string($conn, $_POST['servico']);
$valor = $_POST['valor'];
$vencimento = $_POST['vencimento'];

$stmt = $conn->prepare("INSERT INTO clientes (nome, email, servico, valor_mensal, vencimento_dia) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssdi", $nome, $email, $servico, $valor, $vencimento);

if($stmt->execute()) {
    header("Location: ../clientes.php?success=1");
} else {
    echo "Erro: " . $stmt->error;
}
?>