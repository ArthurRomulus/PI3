// Script para cargar promociones desde la base de datos y aplicar búsqueda/filtrado
// Solo filtrado en el front-end

document.addEventListener('DOMContentLoaded', () => {
    const apiUrl = 'obtener_promociones.php';
    const grid = document.querySelector('.promotions-grid');
    
    // ----- CORREGIDO -----
    // El input de búsqueda ahora tiene la clase '.search'
    const searchInput = document.querySelector('.search'); 
    // Los botones de filtro ahora tienen la clase '.cat-btn'
    const filterButtons = document.querySelectorAll('.cat-btn');
    // ---------------------

    let promotions = [];

    function createCard(p) {
        const card = document.createElement('div');
        card.className = 'promo-card';

        // Inferir categoría simple desde tipo_descuento o condiciones si no existe
        let category = (p.tipo_descuento || '').toLowerCase();
        if (category.includes('bebida')) category = 'bebidas';
        else if (category.includes('comida')) category = 'comida';
        else if (category.includes('postre')) category = 'postres';
        else category = 'general';
        card.setAttribute('data-category', category);

        // Crear contenido
        const title = document.createElement('h3');
        title.textContent = p.nombrePromo || 'Promoción';
        const desc = document.createElement('p');
        desc.textContent = p.condiciones || '';
        if (p.imagen_url) {
            const img = document.createElement('img');
            img.src = p.imagen_url;
            img.alt = p.nombrePromo || 'Imagen promoción';
            img.className = 'promo-img';
            card.appendChild(img);
        }
        card.appendChild(title);
        card.appendChild(desc);
        
        return card;
    }

    function render(promoList) {
        grid.innerHTML = '';
        if (promoList.length === 0) {
            grid.innerHTML = '<p>No se encontraron promociones.</p>';
            return;
        }
        promoList.forEach(p => {
            grid.appendChild(createCard(p));
        });
    }

    function applyFilters() {
        const searchTerm = searchInput.value.toLowerCase();
        const activeFilter = document.querySelector('.cat-btn.active').dataset.filter || 'all';

        const filtered = promotions.filter(p => {
            let category = (p.tipo_descuento || '').toLowerCase();
            if (category.includes('bebida')) category = 'bebidas';
            else if (category.includes('comida')) category = 'comida';
            else if (category.includes('postre')) category = 'postres';
            else category = 'general';
            const matchesFilter = (activeFilter === 'all' || category === activeFilter);
            const text = `${p.nombrePromo} ${p.condiciones}`.toLowerCase();
            const matchesSearch = text.includes(searchTerm);
            return matchesFilter && matchesSearch;
        });
        render(filtered);
    }

    // Fetch promociones una sola vez
    fetch(apiUrl)
        .then(res => res.json())
        .then(json => {
            if (!json.success) throw new Error(json.error || 'Error al obtener promociones');
            promotions = json.data || [];
            render(promotions);
            
            // Enlazar eventos de filtro/búsqueda
            // (Esto fallaba antes y causaba que no se viera nada)
            filterButtons.forEach(btn => btn.addEventListener('click', (e) => {
                filterButtons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                applyFilters();
            }));
            
            // Comprobamos que searchInput no sea null antes de añadir el listener
            if (searchInput) {
                searchInput.addEventListener('input', applyFilters);
            }
        })
        .catch(err => {
            console.error('Error cargando promociones:', err);
            grid.innerHTML = '<p>Error cargando promociones. Revisa la consola.</p>';
        });
});