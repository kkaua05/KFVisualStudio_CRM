<?php
require 'config/db.php';
include 'includes/header.php';

// Consultas Dashboard
$total_clientes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(*) as total FROM clientes"))['total'];

$mes_atual = date('Y-m');
$total_recebido = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(valor_pago) as total FROM pagamentos WHERE mes_referencia = '$mes_atual'"))['total'] ?? 0;

// Lógica complexa de status
$query_status = "SELECT id, vencimento_dia FROM clientes";
$result_status = mysqli_query($conn, $query_status);
$atrasados = 0;
$vencendo_hoje = 0;

while($row = mysqli_fetch_assoc($result_status)) {
    $dia_venc = $row['vencimento_dia'];
    $hoje = date('j');
    $id = $row['id'];
    
    // Verifica se pagou este mês
    $check_pag = mysqli_query($conn, "SELECT id FROM pagamentos WHERE cliente_id = $id AND mes_referencia = '$mes_atual'");
    
    if(mysqli_num_rows($check_pag) == 0) {
        if($hoje > $dia_venc) $atrasados++;
        if($hoje == $dia_venc) $vencendo_hoje++;
    }
}
?>

<div class="dashboard-grid">
    <div class="card">
        <h3>Total Clientes</h3>
        <div class="value"><?= $total_clientes ?></div>
        <i class="ri-user-line icon"></i>
    </div>
    <div class="card">
        <h3>Recebido (Mês)</h3>
        <div class="value">R$ <?= number_format($total_recebido, 2, ',', '.') ?></div>
        <i class="ri-wallet-3-line icon" style="color: var(--success)"></i>
    </div>
    <div class="card">
        <h3>Clientes Atrasados</h3>
        <div class="value" style="color: var(--danger)"><?= $atrasados ?></div>
        <i class="ri-alert-line icon"></i>
    </div>
    <div class="card">
        <h3>Vencendo Hoje</h3>
        <div class="value" style="color: var(--warning)"><?= $vencendo_hoje ?></div>
        <i class="ri-time-line icon"></i>
    </div>
</div>

<div class="card" style="height: 400px;">
    <h3>Fluxo Financeiro (Últimos 6 meses)</h3>
    <canvas id="financeChart"></canvas>
</div>

<script>
    const ctx = document.getElementById('financeChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'], // Idealmente dinâmico via PHP
            datasets: [{
                label: 'Receita',
                data: [1200, 1900, 3000, 2500, 2800, <?= $total_recebido ?>],
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.2)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#94a3b8' } },
                x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
            }
        }
    });
</script>

<?php include 'includes/footer.php'; ?>