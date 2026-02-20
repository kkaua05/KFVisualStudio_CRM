<?php
require '../config/db.php';
header('Content-Type: application/json');

$cliente_id = $_POST['cliente_id'];
$valor = $_POST['valor'];
$data = $_POST['data'];
$mes_ref = date('Y-m', strtotime($data));

$stmt = $conn->prepare("INSERT INTO pagamentos (cliente_id, valor_pago, data_pagamento, mes_referencia) VALUES (?, ?, ?, ?)");
$stmt->bind_param("idss", $cliente_id, $valor, $data, $mes_ref);

if($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}
?>