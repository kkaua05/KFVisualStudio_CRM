<?php
require 'config/db.php';
include 'includes/header.php';

// Buscar clientes
$query = "SELECT * FROM clientes ORDER BY id DESC";
$clientes = mysqli_query($conn, $query);
?>

<div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
    <button class="btn btn-primary" onclick="toggleModal('modalCliente')">+ Novo Cliente</button>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Serviço</th>
                <th>Valor</th>
                <th>Vencimento</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while($c = mysqli_fetch_assoc($clientes)): 
                // Cálculo de Status em Tempo Real
                $hoje = date('j');
                $mes_ref = date('Y-m');
                $venc = $c['vencimento_dia'];
                
                $sql_pag = "SELECT id FROM pagamentos WHERE cliente_id = {$c['id']} AND mes_referencia = '$mes_ref'";
                $res_pag = mysqli_query($conn, $sql_pag);
                
                $status_class = 'bg-warning';
                $status_text = 'Pendente';
                
                if(mysqli_num_rows($res_pag) > 0) {
                    $status_class = 'bg-success';
                    $status_text = 'Pago';
                } elseif ($hoje > $venc) {
                    $status_class = 'bg-danger';
                    $status_text = 'Atrasado';
                }
            ?>
            <tr>
                <td><?= $c['nome'] ?></td>
                <td><?= $c['servico'] ?></td>
                <td>R$ <?= number_format($c['valor_mensal'], 2) ?></td>
                <td>Dia <?= $venc ?></td>
                <td><span class="badge <?= $status_class ?>"><?= $status_text ?></span></td>
                <td>
                    <button class="btn btn-sm btn-primary" onclick="registrarPagamento(<?= $c['id'] ?>, '<?= $c['nome'] ?>', <?= $c['valor_mensal'] ?>)">Pagar</button>
                    <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $c['id'] ?>)">Excluir</button>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modal Simples (Inserido via JS ou HTML oculto) -->
<div id="modalCliente" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:999; justify-content:center; align-items:center;">
    <div class="card" style="width: 500px; position: relative;">
        <h3>Novo Cliente</h3>
        <form action="actions/cliente_add.php" method="POST" style="margin-top: 20px;">
            <input type="text" name="nome" placeholder="Nome do Cliente" required style="margin-bottom: 10px;">
            <input type="email" name="email" placeholder="Email" required style="margin-bottom: 10px;">
            <input type="text" name="servico" placeholder="Serviço Contratado" required style="margin-bottom: 10px;">
            <div class="form-grid">
                <input type="number" step="0.01" name="valor" placeholder="Valor Mensal" required>
                <input type="number" name="vencimento" placeholder="Dia Vencimento (1-31)" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%">Salvar</button>
            <button type="button" class="btn btn-danger" style="width:100%; margin-top:10px;" onclick="toggleModal('modalCliente')">Cancelar</button>
        </form>
    </div>
</div>

<script>
function toggleModal(id) {
    const el = document.getElementById(id);
    el.style.display = el.style.display === 'none' ? 'flex' : 'none';
}
function confirmDelete(id) {
    Swal.fire({
        title: 'Tem certeza?',
        text: "Isso apagará o cliente e seus pagamentos!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Sim, excluir!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `actions/cliente_del.php?id=${id}`;
        }
    })
}
function registrarPagamento(id, nome, valor) {
    Swal.fire({
        title: `Pagar ${nome}?`,
        html: `
            <input type="number" id="swalValor" class="swal2-input" value="${valor}" step="0.01">
            <input type="date" id="swalData" class="swal2-input" value="${new Date().toISOString().split('T')[0]}">
        `,
        showCancelButton: true,
        confirmButtonText: 'Confirmar Pagamento',
        preConfirm: () => {
            const valor = document.getElementById('swalValor').value;
            const data = document.getElementById('swalData').value;
            return fetch(`actions/pagamento_add.php`, {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `cliente_id=${id}&valor=${valor}&data=${data}`
            }).then(res => res.json())
        }
    }).then((result) => {
        if(result.isConfirmed) location.reload();
    });
}
</script>

<?php include 'includes/footer.php'; ?>