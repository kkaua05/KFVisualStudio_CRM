<?php
require 'config/db.php';
include 'includes/header.php';

// Buscar clientes
$query = "SELECT * FROM clientes ORDER BY id DESC";
$clientes = mysqli_query($conn, $query);
?>

<!-- CSS ESPECÍFICO DA PÁGINA DE CLIENTES -->
<style>
/* Modal Overlay */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(5px);
    z-index: 1000;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.modal-container {
    background: var(--sidebar-bg);
    border-radius: 16px;
    border: 1px solid var(--border);
    width: 100%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    animation: modalSlideIn 0.3s ease;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-header {
    padding: 24px;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    color: var(--text-main);
    display: flex;
    align-items: center;
    gap: 10px;
}

.modal-close {
    background: none;
    border: none;
    color: var(--text-muted);
    font-size: 28px;
    cursor: pointer;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    transition: 0.2s;
}

.modal-close:hover {
    background: var(--glass);
    color: var(--text-main);
}

.modal-form {
    padding: 24px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: var(--text-muted);
    font-size: 0.875rem;
    font-weight: 500;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

/* Seção de Vencimento */
.vencimento-section {
    background: var(--glass);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 24px;
}

.section-label {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--text-main);
    font-weight: 600;
    margin-bottom: 16px;
    font-size: 0.95rem;
}

/* Quick Days */
.quick-days {
    margin-bottom: 20px;
}

.quick-label {
    display: block;
    font-size: 0.8rem;
    color: var(--text-muted);
    margin-bottom: 10px;
}

.quick-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.quick-btn {
    padding: 8px 16px;
    background: var(--glass);
    border: 1px solid var(--border);
    color: var(--text-muted);
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.875rem;
    transition: all 0.2s;
    font-weight: 500;
}

.quick-btn:hover {
    background: rgba(99, 102, 241, 0.2);
    border-color: var(--primary);
    color: var(--primary);
    transform: translateY(-2px);
}

.quick-btn.active {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
}

/* Calendário */
.calendar-wrapper {
    background: var(--bg-dark);
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 16px;
    border: 1px solid var(--border);
}

.calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.calendar-nav {
    background: var(--glass);
    border: 1px solid var(--border);
    color: var(--text-main);
    width: 32px;
    height: 32px;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: 0.2s;
}

.calendar-nav:hover {
    background: var(--primary);
    border-color: var(--primary);
}

.calendar-title {
    font-weight: 600;
    color: var(--text-main);
    text-transform: capitalize;
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 4px;
}

.calendar-weekday {
    text-align: center;
    font-size: 0.75rem;
    color: var(--text-muted);
    font-weight: 600;
    padding: 8px 0;
}

.calendar-days {
    display: contents;
}

.calendar-day {
    aspect-ratio: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s;
    color: var(--text-main);
    border: 1px solid transparent;
}

.calendar-day:hover:not(.empty):not(.selected) {
    background: var(--glass);
    border-color: var(--primary);
    color: var(--primary);
}

.calendar-day.selected {
    background: var(--primary);
    color: white;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
}

.calendar-day.today {
    border-color: var(--success);
    color: var(--success);
    font-weight: 700;
}

.calendar-day.today.selected {
    background: var(--success);
    color: white;
}

.calendar-day.empty {
    cursor: default;
}

.calendar-day.disabled {
    color: var(--text-muted);
    opacity: 0.5;
    cursor: not-allowed;
}

/* Preview do Vencimento */
.vencimento-preview {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px;
    background: rgba(99, 102, 241, 0.1);
    border-left: 3px solid var(--primary);
    border-radius: 8px;
    font-size: 0.875rem;
    color: var(--text-muted);
}

.vencimento-preview i {
    color: var(--primary);
    font-size: 1.1rem;
}

.vencimento-preview.valid {
    background: rgba(16, 185, 129, 0.1);
    border-left-color: var(--success);
    color: var(--text-main);
}

.vencimento-preview.valid i {
    color: var(--success);
}

/* Modal Actions */
.modal-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 24px;
    padding-top: 24px;
    border-top: 1px solid var(--border);
}

.btn-cancel {
    background: var(--glass);
    border: 1px solid var(--border);
    color: var(--text-muted);
    padding: 12px 24px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: 0.2s;
}

.btn-cancel:hover {
    background: var(--danger);
    border-color: var(--danger);
    color: white;
}

.btn-save {
    background: var(--primary);
    border: none;
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: 0.2s;
}

.btn-save:hover {
    background: #4f46e5;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
}

/* Botões da Tabela */
.btn-action {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 500;
    cursor: pointer;
    transition: 0.2s;
    border: none;
    margin-right: 5px;
}

.btn-pay {
    background: rgba(16, 185, 129, 0.2);
    color: var(--success);
    border: 1px solid var(--success);
}

.btn-pay:hover {
    background: var(--success);
    color: white;
}

.btn-edit {
    background: rgba(99, 102, 241, 0.2);
    color: var(--primary);
    border: 1px solid var(--primary);
}

.btn-edit:hover {
    background: var(--primary);
    color: white;
}

.btn-delete {
    background: rgba(239, 68, 68, 0.2);
    color: var(--danger);
    border: 1px solid var(--danger);
}

.btn-delete:hover {
    background: var(--danger);
    color: white;
}

/* Header Actions */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 15px;
}

.btn-new-client {
    background: var(--primary);
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: 0.2s;
}

.btn-new-client:hover {
    background: #4f46e5;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
}

/* Stats Cards */
.clients-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 24px;
}

.stat-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.stat-icon.total {
    background: rgba(99, 102, 241, 0.2);
    color: var(--primary);
}

.stat-icon.paid {
    background: rgba(16, 185, 129, 0.2);
    color: var(--success);
}

.stat-icon.pending {
    background: rgba(245, 158, 11, 0.2);
    color: var(--warning);
}

.stat-icon.late {
    background: rgba(239, 68, 68, 0.2);
    color: var(--danger);
}

.stat-info h4 {
    font-size: 0.85rem;
    color: var(--text-muted);
    margin-bottom: 5px;
}

.stat-info .number {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-main);
}

/* Responsivo */
@media (max-width: 640px) {
    .modal-container {
        max-height: 95vh;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .quick-buttons {
        justify-content: center;
    }
    
    .modal-actions {
        flex-direction: column-reverse;
    }
    
    .btn-cancel, .btn-save {
        width: 100%;
        justify-content: center;
    }
    
    .page-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    .btn-new-client {
        justify-content: center;
    }
    
    .clients-stats {
        grid-template-columns: 1fr 1fr;
    }
}
</style>

<!-- Conteúdo Principal -->
<div class="page-header">
    <div>
        <h2 style="margin-bottom: 5px;">Gerenciar Clientes</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem;">Cadastre e gerencie todos os seus clientes</p>
    </div>
    <button class="btn-new-client" onclick="toggleModal('modalCliente')">
        <i class="ri-add-line"></i> Novo Cliente
    </button>
</div>

<!-- Cards de Estatísticas -->
<div class="clients-stats">
    <?php
    // Calcular estatísticas
    $total_clientes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM clientes"))['total'];
    
    $mes_atual = date('Y-m');
    $hoje = date('j');
    
    $pagos = 0;
    $pendentes = 0;
    $atrasados = 0;
    
    $clientes_query = mysqli_query($conn, "SELECT id, vencimento_dia FROM clientes");
    while($c = mysqli_fetch_assoc($clientes_query)) {
        $check_pag = mysqli_query($conn, "SELECT id FROM pagamentos WHERE cliente_id = {$c['id']} AND mes_referencia = '$mes_atual'");
        
        if(mysqli_num_rows($check_pag) > 0) {
            $pagos++;
        } elseif ($hoje > $c['vencimento_dia']) {
            $atrasados++;
        } else {
            $pendentes++;
        }
    }
    ?>
    
    <div class="stat-card">
        <div class="stat-icon total"><i class="ri-user-line"></i></div>
        <div class="stat-info">
            <h4>Total de Clientes</h4>
            <div class="number"><?= $total_clientes ?></div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon paid"><i class="ri-check-circle-line"></i></div>
        <div class="stat-info">
            <h4>Pagos este Mês</h4>
            <div class="number"><?= $pagos ?></div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon pending"><i class="ri-time-line"></i></div>
        <div class="stat-info">
            <h4>Pendentes</h4>
            <div class="number"><?= $pendentes ?></div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon late"><i class="ri-alert-line"></i></div>
        <div class="stat-info">
            <h4>Atrasados</h4>
            <div class="number"><?= $atrasados ?></div>
        </div>
    </div>
</div>

<!-- Tabela de Clientes -->
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Serviço</th>
                <th>Email</th>
                <th>Valor</th>
                <th>Vencimento</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            mysqli_data_seek($clientes, 0); // Resetar ponteiro
            while($c = mysqli_fetch_assoc($clientes)): 
                // Cálculo de Status em Tempo Real
                $mes_ref = date('Y-m');
                $venc = $c['vencimento_dia'];
                
                $sql_pag = "SELECT id FROM pagamentos WHERE cliente_id = {$c['id']} AND mes_referencia = '$mes_ref'";
                $res_pag = mysqli_query($conn, $sql_pag);
                
                $status_class = 'bg-warning';
                $status_text = 'Pendente';
                $status_icon = 'ri-time-line';
                
                if(mysqli_num_rows($res_pag) > 0) {
                    $status_class = 'bg-success';
                    $status_text = 'Pago';
                    $status_icon = 'ri-check-circle-line';
                } elseif ($hoje > $venc) {
                    $status_class = 'bg-danger';
                    $status_text = 'Atrasado';
                    $status_icon = 'ri-alert-line';
                }
            ?>
            <tr>
                <td>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 35px; height: 35px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                            <?= strtoupper(substr($c['nome'], 0, 1)) ?>
                        </div>
                        <div>
                            <div style="font-weight: 600;"><?= $c['nome'] ?></div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);"><?= $c['telefone'] ?? 'Sem telefone' ?></div>
                        </div>
                    </div>
                </td>
                <td><?= $c['servico'] ?></td>
                <td><?= $c['email'] ?></td>
                <td style="font-weight: 600;">R$ <?= number_format($c['valor_mensal'], 2, ',', '.') ?></td>
                <td>Dia <?= $venc ?></td>
                <td>
                    <span class="badge <?= $status_class ?>" style="display: inline-flex; align-items: center; gap: 5px;">
                        <i class="<?= $status_icon ?>"></i> <?= $status_text ?>
                    </span>
                </td>
                <td>
                    <button class="btn-action btn-pay" onclick="registrarPagamento(<?= $c['id'] ?>, '<?= addslashes($c['nome']) ?>', <?= $c['valor_mensal'] ?>)" title="Registrar Pagamento">
                        <i class="ri-money-dollar-circle-line"></i>
                    </button>
                    <button class="btn-action btn-edit" onclick="editarCliente(<?= $c['id'] ?>)" title="Editar">
                        <i class="ri-edit-line"></i>
                    </button>
                    <button class="btn-action btn-delete" onclick="confirmDelete(<?= $c['id'] ?>, '<?= addslashes($c['nome']) ?>')" title="Excluir">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    
    <?php if(mysqli_num_rows($clientes) == 0): ?>
    <div style="text-align: center; padding: 40px; color: var(--text-muted);">
        <i class="ri-user-search-line" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.5;"></i>
        <p>Nenhum cliente cadastrado</p>
        <button class="btn-new-client" onclick="toggleModal('modalCliente')" style="margin: 15px auto; display: inline-flex;">
            <i class="ri-add-line"></i> Cadastrar Primeiro Cliente
        </button>
    </div>
    <?php endif; ?>
</div>

<!-- Modal Cliente -->
<div id="modalCliente" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3><i class="ri-user-add-line"></i> Novo Cliente</h3>
            <button class="modal-close" onclick="toggleModal('modalCliente')">&times;</button>
        </div>
        
        <form action="actions/cliente_add.php" method="POST" class="modal-form">
            <div class="form-group">
                <label>Nome do Cliente *</label>
                <input type="text" name="nome" placeholder="Digite o nome completo" required>
            </div>
            
            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" placeholder="email@exemplo.com" required>
            </div>
            
            <div class="form-group">
                <label>Telefone</label>
                <input type="text" name="telefone" placeholder="(00) 00000-0000">
            </div>
            
            <div class="form-group">
                <label>Serviço Contratado *</label>
                <input type="text" name="servico" placeholder="Ex: Gestão de Redes Sociais" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Valor Mensal (R$) *</label>
                    <input type="number" step="0.01" name="valor" placeholder="0,00" required>
                </div>
            </div>
            
            <!-- Seção de Vencimento Profissional -->
            <div class="vencimento-section">
                <label class="section-label">
                    <i class="ri-calendar-event-line"></i> 
                    Data de Vencimento *
                </label>
                
                <!-- Opções Rápidas -->
                <div class="quick-days">
                    <span class="quick-label">Dias comuns:</span>
                    <div class="quick-buttons">
                        <button type="button" class="quick-btn" data-day="5">Dia 05</button>
                        <button type="button" class="quick-btn" data-day="10">Dia 10</button>
                        <button type="button" class="quick-btn" data-day="15">Dia 15</button>
                        <button type="button" class="quick-btn" data-day="20">Dia 20</button>
                        <button type="button" class="quick-btn" data-day="25">Dia 25</button>
                        <button type="button" class="quick-btn" data-day="30">Dia 30</button>
                    </div>
                </div>
                
                <!-- Calendário -->
                <div class="calendar-wrapper">
                    <div class="calendar-header">
                        <button type="button" id="prevMonth" class="calendar-nav">
                            <i class="ri-arrow-left-s-line"></i>
                        </button>
                        <span id="currentMonth" class="calendar-title"></span>
                        <button type="button" id="nextMonth" class="calendar-nav">
                            <i class="ri-arrow-right-s-line"></i>
                        </button>
                    </div>
                    
                    <div class="calendar-grid">
                        <div class="calendar-weekday">Dom</div>
                        <div class="calendar-weekday">Seg</div>
                        <div class="calendar-weekday">Ter</div>
                        <div class="calendar-weekday">Qua</div>
                        <div class="calendar-weekday">Qui</div>
                        <div class="calendar-weekday">Sex</div>
                        <div class="calendar-weekday">Sáb</div>
                        <div id="calendarDays" class="calendar-days"></div>
                    </div>
                </div>
                
                <!-- Input escondido mas funcional -->
                <input type="hidden" name="vencimento" id="vencimentoInput" required>
                
                <!-- Preview do vencimento -->
                <div id="vencimentoPreview" class="vencimento-preview">
                    <i class="ri-information-line"></i>
                    <span>Selecione uma data de vencimento</span>
                </div>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" onclick="toggleModal('modalCliente')">
                    Cancelar
                </button>
                <button type="submit" class="btn btn-save">
                    <i class="ri-check-line"></i> Salvar Cliente
                </button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript para o Calendário e Funcionalidades -->
<script>
// Calendário de Vencimento
class CalendarVencimento {
    constructor() {
        this.currentDate = new Date();
        this.selectedDay = null;
        this.init();
    }
    
    init() {
        this.renderCalendar();
        this.attachEvents();
    }
    
    renderCalendar() {
        const year = this.currentDate.getFullYear();
        const month = this.currentDate.getMonth();
        
        // Atualiza título
        const monthNames = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                          'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
        document.getElementById('currentMonth').textContent = `${monthNames[month]} ${year}`;
        
        // Primeiros e últimos dias do mês
        const firstDay = new Date(year, month, 1).getDay();
        const lastDate = new Date(year, month + 1, 0).getDate();
        const today = new Date().getDate();
        
        const calendarDays = document.getElementById('calendarDays');
        calendarDays.innerHTML = '';
        
        // Dias vazios antes do primeiro dia
        for (let i = 0; i < firstDay; i++) {
            const emptyDay = document.createElement('div');
            emptyDay.className = 'calendar-day empty';
            calendarDays.appendChild(emptyDay);
        }
        
        // Dias do mês
        for (let day = 1; day <= lastDate; day++) {
            const dayElement = document.createElement('div');
            dayElement.className = 'calendar-day';
            dayElement.textContent = day;
            dayElement.dataset.day = day;
            
            // Marca dia atual
            if (day === today && month === new Date().getMonth() && year === new Date().getFullYear()) {
                dayElement.classList.add('today');
            }
            
            // Marca dia selecionado
            if (day === this.selectedDay) {
                dayElement.classList.add('selected');
            }
            
            // Evento de clique
            dayElement.addEventListener('click', () => this.selectDay(day));
            
            calendarDays.appendChild(dayElement);
        }
    }
    
    selectDay(day) {
        this.selectedDay = day;
        document.getElementById('vencimentoInput').value = day;
        
        // Atualiza UI
        document.querySelectorAll('.calendar-day').forEach(el => {
            el.classList.remove('selected');
            if (parseInt(el.dataset.day) === day) {
                el.classList.add('selected');
            }
        });
        
        // Remove active dos botões rápidos
        document.querySelectorAll('.quick-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        
        // Atualiza preview
        this.updatePreview(day);
    }
    
    updatePreview(day) {
        const preview = document.getElementById('vencimentoPreview');
        const proximaData = this.getNextVencimento(day);
        
        preview.innerHTML = `
            <i class="ri-check-line"></i>
            <span>Vencimento: Dia ${day} de cada mês (Próximo: ${proximaData})</span>
        `;
        preview.classList.add('valid');
    }
    
    getNextVencimento(day) {
        const today = new Date();
        let nextDate = new Date(today.getFullYear(), today.getMonth(), day);
        
        if (nextDate < today) {
            nextDate = new Date(today.getFullYear(), today.getMonth() + 1, day);
        }
        
        return nextDate.toLocaleDateString('pt-BR');
    }
    
    attachEvents() {
        document.getElementById('prevMonth').addEventListener('click', () => {
            this.currentDate.setMonth(this.currentDate.getMonth() - 1);
            this.renderCalendar();
        });
        
        document.getElementById('nextMonth').addEventListener('click', () => {
            this.currentDate.setMonth(this.currentDate.getMonth() + 1);
            this.renderCalendar();
        });
        
        // Botões rápidos
        document.querySelectorAll('.quick-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const day = parseInt(e.target.dataset.day);
                
                // Atualiza botões
                document.querySelectorAll('.quick-btn').forEach(b => b.classList.remove('active'));
                e.target.classList.add('active');
                
                // Seleciona dia
                this.selectDay(day);
                
                // Navega para mês atual se necessário
                this.currentDate = new Date();
                this.renderCalendar();
            });
        });
    }
}

// Funções Globais
function toggleModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal.style.display === 'flex') {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    } else {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        
        // Inicializa calendário se for o modal de cliente
        if (modalId === 'modalCliente' && !window.calendarInstance) {
            window.calendarInstance = new CalendarVencimento();
        } else if (modalId === 'modalCliente') {
            window.calendarInstance.renderCalendar();
        }
    }
}

// Fecha modal ao clicar fora
window.onclick = function(event) {
    const modal = document.getElementById('modalCliente');
    if (event.target === modal) {
        toggleModal('modalCliente');
    }
}

function confirmDelete(id, nome) {
    Swal.fire({
        title: 'Excluir Cliente?',
        html: `Tem certeza que deseja excluir <strong>${nome}</strong>?<br><br><span style="color: var(--danger)">Esta ação não pode ser desfeita e apagará todos os pagamentos registrados!</span>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar',
        background: 'var(--sidebar-bg)',
        color: 'var(--text-main)'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `actions/cliente_del.php?id=${id}`;
        }
    })
}

function registrarPagamento(id, nome, valor) {
    Swal.fire({
        title: `Registrar Pagamento`,
        html: `
            <div style="text-align: left; margin-bottom: 15px;">
                <p><strong>Cliente:</strong> ${nome}</p>
                <p><strong>Valor Sugerido:</strong> R$ ${valor.toFixed(2)}</p>
            </div>
            <input type="number" id="swalValor" class="swal2-input" value="${valor}" step="0.01" placeholder="Valor" style="margin-bottom: 10px;">
            <input type="date" id="swalData" class="swal2-input" value="${new Date().toISOString().split('T')[0]}" placeholder="Data">
        `,
        showCancelButton: true,
        confirmButtonText: 'Confirmar Pagamento',
        cancelButtonText: 'Cancelar',
        background: 'var(--sidebar-bg)',
        color: 'var(--text-main)',
        preConfirm: () => {
            const valor = document.getElementById('swalValor').value;
            const data = document.getElementById('swalData').value;
            
            if (!valor || !data) {
                Swal.showValidationMessage('Preencha todos os campos');
                return false;
            }
            
            return fetch(`actions/pagamento_add.php`, {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `cliente_id=${id}&valor=${valor}&data=${data}`
            }).then(res => res.json())
        }
    }).then((result) => {
        if(result.isConfirmed && result.value.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Pagamento Registrado!',
                timer: 2000,
                showConfirmButton: false,
                background: 'var(--sidebar-bg)',
                color: 'var(--text-main)'
            }).then(() => {
                location.reload();
            });
        } else if (result.isConfirmed) {
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Não foi possível registrar o pagamento',
                background: 'var(--sidebar-bg)',
                color: 'var(--text-main)'
            });
        }
    });
}

function editarCliente(id) {
    Swal.fire({
        icon: 'info',
        title: 'Em Desenvolvimento',
        text: 'Funcionalidade de edição será implementada em breve!',
        background: 'var(--sidebar-bg)',
        color: 'var(--text-main)'
    });
}

// Animações de entrada
document.addEventListener('DOMContentLoaded', () => {
    const cards = document.querySelectorAll('.stat-card, .table-container');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});

// Notificações via URL params
const urlParams = new URLSearchParams(window.location.search);
if(urlParams.get('success')) {
    Swal.fire({ 
        icon: 'success', 
        title: 'Cliente Cadastrado!', 
        timer: 2000, 
        showConfirmButton: false,
        background: 'var(--sidebar-bg)',
        color: 'var(--text-main)'
    });
}
</script>

<?php include 'includes/footer.php'; ?>
