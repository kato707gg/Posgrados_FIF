document.addEventListener('DOMContentLoaded', () => {
    // Solo aplicar a enlaces dentro del contenido principal
    document.querySelectorAll('.container-principal a').forEach(link => {
        if (link.target === '_blank') return;
        
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const destino = this.href;
            const container = document.querySelector('.container-principal');
            
            container.classList.add('fade-out');
            
            setTimeout(() => {
                window.location.href = destino;
            }, 800);
        });
    });
}); 