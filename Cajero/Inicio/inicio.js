document.addEventListener('DOMContentLoaded', () => {
    // --- URLs ---
    const carouselApiUrl = 'obtener_carouseles.php'; 
    
    // --- Selectores de Carruseles ---
    const verticalCarousel = $('#vertical-carousel'); 
    const trackPromociones = $('#carousel-promociones .carousel-track');
    const trackBebidas = $('#carousel-bebidas .carousel-track');
    const trackComidas = $('#carousel-comidas .carousel-track');
    const allHorizontalCarousels = $('.carousel-track');

    // --- LÓGICA DE PRODUCTOS ---

    function createProductCard(product) {
        const card = document.createElement('article');
        card.className = 'product-card';

        // Guardamos datos para la redirección (doble click)
        if (product.idp) { 
            card.dataset.id = product.idp;
            card.dataset.type = 'producto';
        } else if (product.idPromo) { 
            card.dataset.id = product.idPromo;
            card.dataset.type = 'promocion';
        }

        // Datos visuales
        const imgUrl = product.imagen_url || '../img/placeholder.png';
        const precioNumerico = parseFloat(product.precio).toFixed(2);

        // --- CAMBIO PRINCIPAL AQUÍ ---
        // Ya no creamos botones, solo mostramos la imagen, info y precio.
        
        card.innerHTML = `
            <div class="product-img">
                <img src="${imgUrl}" alt="${product.nombre}">
            </div>
            <div class="product-tag">
                <h3 class="product-title">${product.nombre}</h3>
                <p class="product-sub">${product.descripcion || ''}</p>
                <div class="product-footer">
                    <div class="price">$${precioNumerico}</div>
                </div>
            </div>
        `;
        
        // (Se eliminó el listener del botón '+', ya no es necesario)

        return card;
    }

    // --- LÓGICA DE CARRUSELES (Sin Cambios) ---

    const horizontalSlickOptions = {
        dots: false, infinite: true, draggable: true, speed: 500, autoplay: true, 
        autoplaySpeed: 2000, slidesToShow: 4, slidesToScroll: 1, pauseOnHover: true, variableWidth: false
    };
    
    const verticalSlickOptions = {
        vertical: true, verticalSwiping: true, slidesToShow: 1, slidesToScroll: 1, 
        arrows: false, dots: true, autoplay: true, autoplaySpeed: 6000, pauseOnHover: true, pauseOnDotsHover: true
    };

    function initHorizontalCarousels() {
        if (trackPromociones.children().length > 0) trackPromociones.slick(horizontalSlickOptions);
        if (trackBebidas.children().length > 0) trackBebidas.slick(horizontalSlickOptions);
        if (trackComidas.children().length > 0) trackComidas.slick(horizontalSlickOptions);
    }
    
    function renderProducts(container, products) {
        if ($(container).hasClass('slick-initialized')) $(container).slick('unslick');
        
        const minSlides = horizontalSlickOptions.slidesToShow + 2;
        let productsToRender = [...products]; 

        if (productsToRender.length > 0 && productsToRender.length < minSlides) {
            const originalProducts = [...productsToRender]; 
            while (productsToRender.length < minSlides) {
                productsToRender.push(...originalProducts); 
            }
        }

        container.empty(); 

        if (!productsToRender.length) {
            container.html('<p style="color: white; padding-left: 10px;">No se encontraron productos.</p>');
            return;
        }
        
        productsToRender.forEach(product => {
            container.append(createProductCard(product));
        });
    }

    async function loadCarousels() {
        try {
            const response = await fetch(carouselApiUrl);
            const data = await response.json();

            if (!data.success) throw new Error(data.error || 'Error al cargar carruseles');

            renderProducts(trackPromociones, data.promociones);
            renderProducts(trackBebidas, data.bebidas);
            renderProducts(trackComidas, data.comidas);
            
            initHorizontalCarousels();
            verticalCarousel.slick(verticalSlickOptions);
            
            verticalCarousel.on('afterChange', (event, slick, currentSlide) => {
                const currentCarousel = $(slick.$slides[currentSlide]).find('.carousel-track');
                currentCarousel.slick('slickPlay');
            });
            
            allHorizontalCarousels.slick('slickPause');
            $(verticalCarousel.slick('getSlick').$slides[0]).find('.carousel-track').slick('slickPlay');

            attachProductEvents();

        } catch (error) {
            console.error('Error:', error);
            $('#vertical-carousel').html('<p style="color: white; padding: 20px;">Error al cargar. Intente de nuevo más tarde.</p>');
        }
    }

    function attachProductEvents() {
        // Mantenemos el evento de doble click para redirigir a detalles
        document.querySelectorAll('.product-card').forEach(card => {
            card.addEventListener('dblclick', e => { 
                const id = card.dataset.id;
                const type = card.dataset.type; 

                if (type === 'producto' && id) {
                    window.location.href = `../Productos/productos.html?id=${id}`;
                } else if (type === 'promocion' && id) {
                    window.location.href = `../Promocion/promociones.html?id=${id}`;
                }
            });
        });
    }

    // Iniciar
    loadCarousels();
});