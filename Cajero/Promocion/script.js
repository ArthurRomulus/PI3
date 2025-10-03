// Espera a que todo el contenido del HTML se haya cargado.
document.addEventListener('DOMContentLoaded', () => {

    // 1. Seleccionar todos los elementos que necesitamos una sola vez.
    const searchInput = document.querySelector('.search-bar input');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const promoCards = document.querySelectorAll('.promo-card');

    // 2. Crear una única función para filtrar
    // Esta función se encargará de aplicar AMBOS filtros (botones y búsqueda) a la vez.
    function filterPromotions() {
        const searchTerm = searchInput.value.toLowerCase();
        const activeFilter = document.querySelector('.filter-btn.active').getAttribute('data-filter');

        // 3. Recorrer cada tarjeta y aplicar la lógica combinada
        promoCards.forEach(card => {
            const cardCategory = card.getAttribute('data-category');
            const cardText = card.textContent.toLowerCase();

            // Condiciones para que una tarjeta sea visible:
            // Condición 1: El filtro activo es 'all' O la categoría de la tarjeta coincide con el filtro.
            const categoryMatch = (activeFilter === 'all' || cardCategory === activeFilter);
            // Condición 2: El texto de la tarjeta incluye el término de búsqueda.
            const searchMatch = cardText.includes(searchTerm);

            // La tarjeta solo se muestra si CUMPLE AMBAS CONDICIONES
            if (categoryMatch && searchMatch) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // 4. Asignar los eventos a los elementos
    
    // Evento para los botones de filtro
    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Cambia el botón activo
            filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            
            // Llama a la función principal de filtrado
            filterPromotions();
        });
    });

    // Evento para la barra de búsqueda
    searchInput.addEventListener('input', filterPromotions);

});