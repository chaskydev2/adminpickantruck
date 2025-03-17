document.addEventListener('DOMContentLoaded', function() {
    console.log('Truck animation script loaded');
    
    // Agregar clase para iniciar animación
    document.body.classList.add('page-loading');
    
    // Función para animación inicial
    function initAnimation() {
        const truckElements = document.querySelectorAll('.truck-animation');
        const textElements = document.querySelectorAll('.logo-text-animation');
        
        console.log('Found trucks:', truckElements.length);
        console.log('Found texts:', textElements.length);
        
        if (truckElements.length > 0) {
            truckElements.forEach(truck => {
                // Reiniciar animación
                truck.style.animation = 'none';
                void truck.offsetWidth; // Forzar recálculo
                truck.style.animation = 'truckDrop 0.8s ease-in-out forwards, truckDrive 2s ease-in-out 0.8s infinite';
                truck.classList.add('animate');
            });
        }
        
        if (textElements.length > 0) {
            textElements.forEach(text => {
                // Reiniciar animación
                text.style.animation = 'none';
                void text.offsetWidth; // Forzar recálculo
                text.style.animation = 'fadeInText 0.5s ease-in-out 0.3s forwards';
                text.classList.add('animate');
            });
        }
    }
    
    // Ejecutar animación inicial después de un pequeño retraso para asegurar que el DOM esté listo
    setTimeout(initAnimation, 100);
    
    // Aplicar animación al logo cuando aparece en el viewport
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const logo = entry.target;
                const truck = logo.querySelector('.truck-animation');
                const text = logo.querySelector('.logo-text-animation');
                
                if (truck) {
                    truck.style.animation = 'none';
                    void truck.offsetWidth;
                    truck.style.animation = 'truckDrop 0.8s ease-in-out forwards, truckDrive 2s ease-in-out 0.8s infinite';
                }
                
                if (text) {
                    text.style.animation = 'none';
                    void text.offsetWidth;
                    text.style.animation = 'fadeInText 0.5s ease-in-out 0.3s forwards';
                }
            }
        });
    }, { threshold: 0.1 });
    
    document.querySelectorAll('.logo-container, .auth-logo-container').forEach(logo => {
        observer.observe(logo);
    });
    
    // Animar el camión cuando se hace clic en el logo
    document.querySelectorAll('.logo-container, .auth-logo-container').forEach(logo => {
        logo.addEventListener('click', function(e) {
            if (e.target.closest('.logo-container') || e.target.closest('.auth-logo-container')) {
                const truck = this.querySelector('.truck-animation');
                
                if (truck) {
                    // Reiniciar animación
                    truck.style.animation = 'none';
                    void truck.offsetWidth;
                    truck.style.animation = 'truckDrop 0.8s ease-in-out forwards, truckDrive 2s ease-in-out 0.8s infinite';
                }
            }
        });
    });
});
