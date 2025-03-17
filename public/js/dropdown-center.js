/**
 * Script para mejorar la posición de los dropdowns
 */
document.addEventListener('DOMContentLoaded', function() {
    // Función para ajustar la posición del dropdown
    function adjustDropdownPosition() {
        const dropdowns = document.querySelectorAll('.dropdown-container');
        
        dropdowns.forEach(dropdown => {
            // Obtener dimensiones
            const dropdownWidth = dropdown.offsetWidth;
            const windowWidth = window.innerWidth;
            const rect = dropdown.getBoundingClientRect();
            
            // Verificar si se sale del borde derecho
            if (rect.right > windowWidth) {
                dropdown.style.right = '0';
                dropdown.style.left = 'auto';
                dropdown.style.transform = 'none';
            }
            
            // Verificar si se sale del borde izquierdo
            if (rect.left < 0) {
                dropdown.style.left = '0';
                dropdown.style.right = 'auto';
                dropdown.style.transform = 'none';
            }
        });
    }
    
    // Ajustar la posición cuando se abren los dropdowns
    document.addEventListener('click', function(event) {
        if (event.target.closest('[x-data]')) {
            setTimeout(adjustDropdownPosition, 10);
        }
    });
    
    // Ajustar cuando cambia el tamaño de la ventana
    window.addEventListener('resize', adjustDropdownPosition);
});
