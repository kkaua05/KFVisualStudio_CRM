// Animações de entrada
document.addEventListener('DOMContentLoaded', () => {
    const cards = document.querySelectorAll('.card');
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
    Swal.fire({ icon: 'success', title: 'Sucesso!', timer: 2000, showConfirmButton: false });
}

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
        const mesAtual = new Date().toLocaleString('pt-BR', { month: 'long' });
        const proximaData = this.getNextVencimento(day);
        
        preview.innerHTML = `
            <i class="ri-check-line"></i>
            <span>Vencimento: Dia ${day} de cada mês (${proximaData})</span>
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

// Inicializa calendário quando o modal abrir
function toggleModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal.style.display === 'flex') {
        modal.style.display = 'none';
    } else {
        modal.style.display = 'flex';
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
