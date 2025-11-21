// Script para cargar promociones desde la base de datos y aplicar búsqueda/filtrado
document.addEventListener('DOMContentLoaded', () => {
    const apiUrl = 'obtener_promociones.php';
    const grid = document.querySelector('.promotions-grid');
    const scrollContainer = document.querySelector('.main-content'); // Contenedor de scroll
    
    const searchInput = document.querySelector('.search'); 
    const filterButtons = document.querySelectorAll('.cat-btn');
    
    let allPromos = []; // Almacena todas las promociones

    function createCard(p) {
        const card = document.createElement('article');
        card.className = 'promo-card';
        card.dataset.id = p.idPromo; // <-- ¡MUY IMPORTANTE!

        // Inferir categoría
        let category = (p.tipo_descuento || '').toLowerCase();
        if (category.includes('bebida')) category = 'bebidas';
        else if (category.includes('comida')) category = 'comida';
        else if (category.includes('postre')) category = 'postres';
        else category = 'general';
        card.setAttribute('data-category', category);

        // Crear contenido
        const title = document.createElement('h3');
        title.textContent = p.nombrePromo || 'Promoción';
        title.setAttribute('data-translate', p.nombrePromo || 'Promoción');
        const desc = document.createElement('p');
        desc.textContent = p.condiciones || '';
        desc.setAttribute('data-translate', p.condiciones || '');
        
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
        // 🔹 Llamar a traducción después de añadir los elementos al DOM
        if (window.currentLang) {
            window.applyTranslation(window.currentLang);
        }
    }

    function applyFilters() {
        const searchTerm = searchInput.value.toLowerCase();
        const activeFilter = document.querySelector('.cat-btn.active').dataset.filter || 'all';

        const filtered = allPromos.filter(p => {
            let category = (p.tipo_descuento || '').toLowerCase();
            if (category.includes('bebida')) category = 'bebidas';
            else if (category.includes('comida')) category = 'comida';
            else if (category.includes('postre')) category = 'postres';
            else category = 'general';
            
            const matchesFilter = (activeFilter === 'all' || category.includes(activeFilter));
            
            const text = `${p.nombrePromo} ${p.condiciones}`.toLowerCase();
            const matchesSearch = text.includes(searchTerm);
            
            return matchesFilter && matchesSearch;
        });
        render(filtered);
    }

    // --- LÓGICA DE HIGHLIGHT (SALTO) ---
    function highlightPromoFromURL() {
        const urlParams = new URLSearchParams(window.location.search);
        const promoId = urlParams.get('id');

        if (promoId) {
            setTimeout(() => { // Espera 100ms a que se pinten las tarjetas
                const targetCard = document.querySelector(`.promo-card[data-id="${promoId}"]`);

                if (targetCard && scrollContainer) {
                    
                    let hasAnimated = false; // Flag para evitar doble ejecución

                    // 1. Función que dispara la animación
                    const doAnimate = () => {
                        if (hasAnimated) return; // Si ya se ejecutó, no hacer nada
                        hasAnimated = true;
                        
                        // Limpia el listener
                        scrollContainer.removeEventListener('scrollend', doAnimate);

                        // Aplica el salto
                        targetCard.classList.add('promo-highlight');
                        
                        // Quita la clase después de la animación
                        setTimeout(() => {
                            targetCard.classList.remove('promo-highlight');
                        }, 600); // 1.5s
                    };

                    // 2. Escucha el evento 'scrollend'
                    scrollContainer.addEventListener('scrollend', doAnimate, { once: true });
                    
                    // 3. Inicia el scroll SUAVE
                    targetCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    
                    // 4. FALLBACK: Si el scroll no se dispara (ya está en vista)
                    setTimeout(doAnimate, 700);
                }
            }, 100);
        }
    }

    // --- FIN DE LÓGICA HIGHLIGHT ---


    // Fetch promociones una sola vez
    fetch(apiUrl)
        .then(res => res.json())
        .then(json => {
            if (!json.success) throw new Error(json.error || 'Error al obtener promociones');
            allPromos = json.data || []; // <-- Tu PHP usa 'data', esto es correcto
            render(allPromos); // Dibuja las promos
            
            // --- ¡AQUÍ SE LLAMA LA FUNCIÓN DE SALTO! ---
            highlightPromoFromURL();
            
            // Enlazar eventos de filtro/búsqueda
            filterButtons.forEach(btn => btn.addEventListener('click', (e) => {
                filterButtons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                applyFilters();
            }));
            
            if (searchInput) {
                searchInput.addEventListener('input', applyFilters);
            }
        })
        .catch(err => {
            console.error('Error cargando promociones:', err);
            grid.innerHTML = '<p>Error cargando promociones. Revisa la consola.</p>';
        });
});