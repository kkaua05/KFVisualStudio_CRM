<?php
require 'config/db.php';
include 'includes/header.php';

$query = "SELECT p.*, c.nome as cliente_nome 
          FROM pagamentos p 
          JOIN clientes c ON p.cliente_id = c.id 
          ORDER BY p.data_pagamento DESC";
$pagamentos = mysqli_query($conn, $query);
?>

<div class="table-container">
    <h3>Histórico de Pagamentos</h3>
    <table>
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Valor</th>
                <th>Data Pagamento</th>
                <th>Mês Ref.</th>
            </tr>
        </thead>
        <tbody>
            <?php while($p = mysqli_fetch_assoc($pagamentos)): ?>
            <tr>
                <td><?= $p['cliente_nome'] ?></td>
                <td style="color: var(--success); font-weight:bold;">+ R$ <?= number_format($p['valor_pago'], 2) ?></td>
                <td><?= date('d/m/Y', strtotime($p['data_pagamento'])) ?></td>
                <td><?= $p['mes_referencia'] ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php include 'includes/footer.php'; ?>