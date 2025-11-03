document.addEventListener('DOMContentLoaded', () => {
    // --- URLs y Selectores Principales ---
    const carouselApiUrl = 'obtener_carouseles.php'; 
    
    // --- Selectores de Carruseles ---
    const verticalCarousel = $('#vertical-carousel'); // Contenedor padre
    const trackPromociones = $('#carousel-promociones .carousel-track');
    const trackBebidas = $('#carousel-bebidas .carousel-track');
    const trackComidas = $('#carousel-comidas .carousel-track');
    const allHorizontalCarousels = $('.carousel-track');

    // --- Selectores del Carrito ---
    const cartList = [];
    const cartContent = document.querySelector('.cart-content');
    const summarySubtotal = document.querySelector('.summary-subtotal');
    const summaryIVA = document.querySelector('.summary-iva');
    const summaryTotal = document.querySelector('.summary-total');

    // --- LÓGICA DEL CARRITO ---

    function getProductData(card) {
        return {
            name: card.querySelector('.product-title').textContent,
            desc: card.querySelector('.product-sub').textContent,
            price: parseFloat(card.querySelector('.price').textContent.replace('$', '')),
            img: card.querySelector('.product-img').innerHTML
        };
    }

    function renderCart() {
        cartContent.innerHTML = '';
        let subtotal = 0;
        cartList.forEach((item, idx) => {
            subtotal += item.price * item.qty;
            const div = document.createElement('div');
            div.className = 'cart-item';
            div.innerHTML = `<div class="cart-thumb">${item.img}</div><div class="cart-info"><div class="cart-title">${item.name}</div><div class="cart-desc">${item.desc} - ${item.leche} - ${item.tamano}</div><div class="cart-price">$${item.price.toFixed(2)}</div></div><div class="cart-qty"><button class="qty-btn" data-idx="${idx}" data-action="minus">-</button><div class="qty-num">${item.qty.toString().padStart(2, '0')}</div><button class="qty-btn" data-idx="${idx}" data-action="plus">+</button></div>`;
            cartContent.appendChild(div);
        });
        summarySubtotal.textContent = `$${subtotal.toFixed(2)}`;
        const iva = subtotal * 0.16;
        summaryIVA.textContent = `$${iva.toFixed(2)}`;
        summaryTotal.textContent = `$${(subtotal + iva).toFixed(2)}`;
    }

    cartContent.addEventListener('click', e => {
        if (e.target.classList.contains('qty-btn')) {
            const idx = +e.target.dataset.idx;
            const action = e.target.dataset.action;
            if (action === 'plus') cartList[idx].qty += 1;
            if (action === 'minus') {
                cartList[idx].qty -= 1;
                if (cartList[idx].qty <= 0) cartList.splice(idx, 1);
            }
            renderCart();
        }
    });

    // --- LÓGICA DE CARGA DE PRODUCTOS ---

    // Función para crear una tarjeta de producto
    function createProductCard(product) {
        const card = document.createElement('article');
        card.className = 'product-card';

        if (product.idp) { 
            card.dataset.id = product.idp;
            card.dataset.type = 'producto';
        } else if (product.idPromo) { 
            card.dataset.id = product.idPromo;
            card.dataset.type = 'promocion';
        }

        let displayPrice = parseFloat(product.precio).toFixed(2);
        
        if (product.idPromo || product.idp) { 
             displayPrice = "Ver Más"; 
        }

        card.innerHTML = `
            <div class="product-img">
                ${product.imagen_url ? `<img src="${product.imagen_url}" alt="${product.nombre}">` : '<img src="../img/placeholder.png" alt="Sin imagen">'}
            </div>
            <div class="product-tag">
                <h3 class="product-title">${product.nombre}</h3>
                <p class="product-sub">${product.descripcion || ''}</p>
                <div class="product-footer">
                    <div class="price">${displayPrice}</div>
                </div>
            </div>
        `;
        return card;
    }

    // --- ¡LÓGICA DE CARRUSELES (TU VERSIÓN CORRECTA)! ---

    // Opciones para los 3 carruseles HIJOS (horizontales)
    const horizontalSlickOptions = {
        dots: false,
        infinite: true,
        draggable: true,       // <-- ARRASTRE FUNCIONA
        
        // --- CAMBIOS PARA ARREGLAR ARRASTRE ---
        speed: 500,          // Velocidad de transición 0.5s
        autoplay: true,
        autoplaySpeed: 2000,   // Pausa de 1 segundos (TU VALOR)
        // cssEase y autoplaySpeed: 0 eliminados

        slidesToShow: 4,         // Muestra 4
        slidesToScroll: 1,         // Mueve 1
        pauseOnHover: true,  
        
        // --- CAMBIO PARA ARREGLAR SIMETRÍA ---
        variableWidth: false   // <-- Slick controlará el ancho, no el CSS
    };
    
    // Opciones para el carrusel PADRE (vertical)
    const verticalSlickOptions = {
        vertical: true,        // Movimiento vertical
        verticalSwiping: true, // Permite arrastrar verticalmente
        slidesToShow: 1,       // Muestra 1 sección a la vez
        slidesToScroll: 1,
        arrows: false,         // Sin flechas de subir/bajar
        dots: true,            // Muestra los puntos de navegación
        autoplay: true,        // Se mueve solo
        autoplaySpeed: 6000,   // Cambia de sección cada 5 segundos (TU VALOR)
        pauseOnHover: true,    // Se detiene si el usuario interactúa
        pauseOnDotsHover: true // Se detiene si pone el mouse en los puntos
    };

    // Función que INICIA los 3 carruseles horizontales
    function initHorizontalCarousels() {
        if (trackPromociones.children().length > 0) trackPromociones.slick(horizontalSlickOptions);
        if (trackBebidas.children().length > 0) trackBebidas.slick(horizontalSlickOptions);
        if (trackComidas.children().length > 0) trackComidas.slick(horizontalSlickOptions);
    }
    
    // Función para renderizar productos (con relleno para bucle)
    function renderProducts(container, products) {
        if ($(container).hasClass('slick-initialized')) {
            $(container).slick('unslick');
        }
        
        // El relleno es necesario para el autoplay infinito
        const minSlides = horizontalSlickOptions.slidesToShow + 2; // Necesitamos más de las que se ven
        let productsToRender = [...products]; 

        if (productsToRender.length > 0 && productsToRender.length < minSlides) {
            const originalProducts = [...productsToRender]; 
            while (productsToRender.length < minSlides) {
                productsToRender.push(...originalProducts); 
            }
        }

        container.empty(); // Limpiamos

        if (!productsToRender.length) {
            container.html('<p style="color: white; padding-left: 10px;">No se encontraron productos.</p>');
            return;
        }
        
        productsToRender.forEach(product => {
            container.append(createProductCard(product));
        });
    }

    // Función principal que carga todo
    async function loadCarousels() {
        try {
            const response = await fetch(carouselApiUrl);
            const data = await response.json();

            if (!data.success) throw new Error(data.error || 'Error al cargar carruseles');

            // 1. Dibuja los productos en los carruseles hijos
            renderProducts(trackPromociones, data.promociones);
            renderProducts(trackBebidas, data.bebidas);
            renderProducts(trackComidas, data.comidas);
            
            // 2. Inicia los 3 carruseles horizontales
            initHorizontalCarousels();

            // 3. Inicia el carrusel vertical PADRE
            verticalCarousel.slick(verticalSlickOptions);
            
            // --- 4. LÓGICA DE PAUSA/PLAY (CORREGIDA) ---
            
            // EL 'beforeChange' FUE ELIMINADO
            
            // CADA VEZ QUE EL CARRUSEL VERTICAL CAMBIA DE SLIDE...
            verticalCarousel.on('afterChange', (event, slick, currentSlide) => {
                // 1. Pausa TODOS los carruseles (para detener el que se acaba de ocultar)
                
                // 2. Reanuda SOLO el carrusel horizontal que se está mostrando ahora
                const currentCarousel = $(slick.$slides[currentSlide]).find('.carousel-track');
                currentCarousel.slick('slickPlay');
            });
            
            // 5. Pausa inicial (solo el primero debe estar activo)
            allHorizontalCarousels.slick('slickPause');
            $(verticalCarousel.slick('getSlick').$slides[0]).find('.carousel-track').slick('slickPlay');

            // 6. Enlaza los eventos de doble clic
            attachProductEvents();

        } catch (error) {
            console.error('Error:', error);
            // Manejo de error si la carga falla
            $('#vertical-carousel').html('<p style="color: white; padding: 20px;">Error al cargar. Intente de nuevo más tarde.</p>');
        }
    }

    // Función que enlaza los eventos de clic
    function attachProductEvents() {
        // Evento para REDIRIGIR (con Doble Clic)
        document.querySelectorAll('.product-card').forEach(card => {
            card.addEventListener('dblclick', e => { 
                if (e.target.closest('.add-btn')) return;
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

    // --- Carga Inicial ---
    renderCart();
    loadCarousels();
});