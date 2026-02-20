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