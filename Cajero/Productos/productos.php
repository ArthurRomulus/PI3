<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <meta charset="UTF-8">
  <title>BlackWood Coffe - Productos</title>
  <link rel="stylesheet" href="productos.css">
  <link rel="stylesheet" href="../Carrito/carrito.css"> 

  <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
<div class="container">
  
  <div id="navbar-placeholder"></div>

  <main class="main">
    <div class="main-scroll">
      <header class="header">
        <h1>Blackwood Coffee</h1>
        <div class="date"> </div>
      </header>
      <section class="filters">
    <input class="search" type="search" data-translate-placeholder="Buscar producto..." placeholder="Buscar producto..." />

    <div class="cats">
        <button class="cat-btn active" data-translate="Todos">Todos</button>

        <?php
        include "../../conexion.php";
        // Hacemos la consulta igual que en tu admin
        $categoria_query = "SELECT nombrecategoria FROM categorias ORDER BY nombrecategoria ASC";
        $categoria_result = $conn->query($categoria_query);

        if ($categoria_result && $categoria_result->num_rows > 0) {
            while($cat = $categoria_result->fetch_assoc()) {
                $cat_nombre = htmlspecialchars($cat['nombrecategoria']);
                
                // Generamos el botón con la clase 'cat-btn' para que el JS lo detecte.
                // No usamos <a> href=... porque en el cajero queremos filtrar sin recargar la página.
                echo '<button class="cat-btn" data-translate="' . $cat_nombre . '">' . $cat_nombre . '</button>';
            }
        }
        ?>
    </div>
</section>
      <section class="products" aria-label="Productos">
        </section>
    </div>
  </main>

  <div id="carrito-placeholder"></div> </div>

<script src="../barraNav.js"></script>
<script src="../Carrito/carrito.js"></script> <script>
document.addEventListener('DOMContentLoaded', () => {
    // Carga la barra de navegación
    const currentPage = window.location.pathname.split("/").pop();
    if (typeof cargarBarraNav === 'function') {
        cargarBarraNav(currentPage);
    }
    
    // NOTA: El carrito se inicializa automáticamente desde carrito.js

    // --- ACTUALIZAR LA FECHA EN TIEMPO REAL ---
    const dateElement = document.querySelector('.date');
    if (dateElement) {
        const dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        const meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
        const fechaActual = new Date();
        const diaSemana = dias[fechaActual.getDay()];
        const diaMes = fechaActual.getDate();
        const mes = meses[fechaActual.getMonth()];
        const anio = fechaActual.getFullYear();
        dateElement.textContent = `${diaSemana}, ${diaMes} de ${mes} de ${anio}`;
    }

    // --- LÓGICA DE PRODUCTOS (REDUCIDA) ---
    const contenedorProductos = document.querySelector('.products');
    const filterContainer = document.querySelector('.cats');
    const scrollContainer = document.querySelector('.main-scroll');
    let allProducts = [];

    // --- (NUEVO) FUNCIÓN PARA GENERAR LOS LISTBOX DINÁMICOS ---
    function generarModificadoresHtml(producto) {
        let html = '';
        
        if (producto.modificadores && producto.modificadores.length > 0) {
            producto.modificadores.forEach(grupo => {
                html += `<div class="option-select">
                           <select class="modifier-select" data-id-grupo="${grupo.id}">
                             <option value="0" data-precio-extra="0" selected disabled data-translate="${grupo.nombre}">${grupo.nombre}</option>`;
                
                grupo.opciones.forEach(opcion => {
    const precioTexto = opcion.precio > 0 ? ` (+$${parseFloat(opcion.precio).toFixed(2)})` : '';
    html += `<option value="${opcion.id}" data-precio-extra="${opcion.precio}">
    <span data-translate="${opcion.valor}">${opcion.valor}</span>
    <span data-keep=" ${precioTexto}">${precioTexto}</span>
</option>`;

});

                
                html += `</select></div>`;
            });
            
            if (producto.modificadores.length === 1) {
                 html += `<div class="option-select">
                            <select class="modifier-select" disabled>
                              <option data-translate="Sin opciones">Sin opciones</option>
                            </select>
                          </div>`;
            }
        } else {
            html = `<div class="option-select">
                        <select class="modifier-select" disabled>
                          <option data-translate="Sin opciones">Sin opciones</option>
                        </select>
                      </div>
                      <div class="option-select">
                        <select class="modifier-select" disabled>
                          <option data-translate="Sin opciones">Sin opciones</option>
                        </select>
                      </div>`;
        }
        return html;
    }

    // --- Función para mostrar productos en la página ---
    function renderProducts(productsToRender, allData) {
        if (!contenedorProductos) return;
        contenedorProductos.innerHTML = '';
        
        productsToRender.forEach(producto => {
            const { tamanos } = allData; 

            let tagHtml = ''; 
            if (producto.categorias_nombres && producto.categorias_nombres.includes('Bebidas calientes')) {
                tagHtml = '<span class="card-tag tag-caliente" data-translate="CALIENTE">CALIENTE</span>';
            } else if (producto.categorias_nombres && producto.categorias_nombres.includes('Bebidas frias')) {
                tagHtml = '<span class="card-tag tag-frio" data-translate="FRÍO">FRÍO</span>';
            }
            
            const foodCategories = ['Comida', 'Postres', 'Panes'];
            let isFoodItem = false;
            if (producto.categorias_nombres) {
                isFoodItem = producto.categorias_nombres.some(cat => foodCategories.includes(cat));
            }
            
            const modificadoresHtml = generarModificadoresHtml(producto);
            const tamanosHtml = generarTamanosHtml(tamanos, producto.tamano_defecto, isFoodItem);
            
            const tarjetaHTML = `
                <article class="product-card-new" data-id="${producto.idp}" data-base-price="${producto.precio}" data-name="${producto.namep}">
                    <div class="card-top">
                        ${tagHtml}
                        <span class="card-price">$${parseFloat(producto.precio).toFixed(2)}</span>
                    </div>
                    <div class="card-image"><img src="${producto.ruta_imagen}" alt="${producto.namep}"></div>
                    <div class="card-badge" data-translate="${producto.namep}">${producto.namep}</div>
                    <div class="card-options">
                        ${modificadoresHtml} 
                        <div class="option-row quantity-selector">
                            <button class="quantity-btn minus">-</button><span class="quantity-value">1</span><button class="quantity-btn plus">+</button>
                        </div>
                        <div class="option-row size-selector">${tamanosHtml}</div>
                    </div>
                    <textarea class="extra-requests-input"  data-translate-placeholder="Peticiones extras..." placeholder="Peticiones extras..."></textarea>
                    <button class="add-to-cart-btn" data-translate="Añadir a Carrito">Añadir a Carrito</button>
                </article>`;
            contenedorProductos.insertAdjacentHTML('beforeend', tarjetaHTML);
        });
       // 🔹 Llamar a traducción usando localStorage (que siempre tiene el valor real)
        const idiomaActual = localStorage.getItem("lang") || "es";
        if (window.applyTranslation) {
            window.applyTranslation(idiomaActual);
        }
    }

    // --- CARGA INICIAL DE PRODUCTOS DESDE API ---
    if (contenedorProductos) {
        fetch('api.php')
            .then(response => response.json())
            .then(data => {
                if (data.error || !data.success) { 
                    console.error('Error desde API:', data.error); 
                    if(data.trace) console.error('Trace:', data.trace);
                    return; 
                }
                
                allProducts = data.productos;
                renderProducts(allProducts, data); 

                // ... dentro de renderProducts o donde tienes los listeners ...

                // --- FILTROS DE CATEGORÍAS ---
                if (filterContainer) {
                    const normalizeText = (str) => {
                        if (!str) return "";
                        return str.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                    }

                    filterContainer.addEventListener('click', (event) => {
                        const target = event.target;
                        if (!target.matches('.cat-btn')) return;

                        filterContainer.querySelector('.active').classList.remove('active');
                        target.classList.add('active');

                        // CORRECCIÓN APLICADA AQUÍ:
                        // Leemos el atributo 'data-translate' (ej: "Todos" o "Frappés") 
                        // en lugar del texto visible (ej: "All" o "Frappes").
                        const rawValue = target.getAttribute('data-translate') || target.textContent;
                        const filterType = normalizeText(rawValue);
                        
                        let filteredProducts = [];

                        if (filterType === 'todos') { // Ahora 'todos' siempre coincidirá
                            filteredProducts = allProducts;
                        } else {
                            filteredProducts = allProducts.filter(p => {
                                if (!p.categorias_nombres || p.categorias_nombres.length === 0) {
                                    return false;
                                }
                                // Comparamos contra la base de datos (que está en español)
                                return p.categorias_nombres.some(catName => normalizeText(catName) === filterType);
                            });
                        }
                        
                        // Renderizamos los productos filtrados
                        renderProducts(filteredProducts, data);
                    });
                }

                // --- CÓDIGO DE HIGHLIGHT ---
                const urlParams = new URLSearchParams(window.location.search);
                const productId = urlParams.get('id');

                if (productId) {
                    setTimeout(() => { 
                        const targetCard = document.querySelector(`.product-card-new[data-id="${productId}"]`);
                        if (targetCard && scrollContainer) {
                            let hasAnimated = false; 
                            const doAnimate = () => {
                                if (hasAnimated) return;
                                hasAnimated = true;
                                scrollContainer.removeEventListener('scrollend', doAnimate);
                                targetCard.classList.add('product-highlight');
                                setTimeout(() => {
                                    targetCard.classList.remove('product-highlight');
                                }, 600); 
                            };
                            scrollContainer.addEventListener('scrollend', doAnimate, { once: true });
                            targetCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            setTimeout(doAnimate, 700);
                        }
                    }, 100); 
                }
            });
    }

    // --- MANEJADORES DE EVENTOS PARA LAS TARJETAS ---
    if (contenedorProductos) {
        contenedorProductos.addEventListener('click', (event) => {
            const target = event.target;
            const card = target.closest('.product-card-new');
            if (!card) return;

            if (target.matches('.quantity-btn')) {
                const quantitySpan = card.querySelector('.quantity-value');
                let quantity = parseInt(quantitySpan.textContent);
                if (target.matches('.plus')) quantity++;
                else if (target.matches('.minus') && quantity > 1) quantity--;
                quantitySpan.textContent = quantity;
                updateCardPrice(card);
            }

            if (target.closest('.size-btn')) {
                const sizeButton = target.closest('.size-btn');
                card.querySelectorAll('.size-btn').forEach(btn => btn.classList.remove('active'));
                sizeButton.classList.add('active');
                updateCardPrice(card);
            }

            // --- (ACTUALIZADO) AÑADIR AL CARRITO ---
            if (target.matches('.add-to-cart-btn')) {
                
                let allOptionsSelected = true;
                let optionsDesc = [];
                let optionsPrice = 0;

                const modifiers = card.querySelectorAll('.modifier-select:not(:disabled)');
                modifiers.forEach(select => {
                    if (select.selectedIndex === 0) {
                        allOptionsSelected = false;
                    }
                    const selectedOption = select.options[select.selectedIndex];
                    optionsDesc.push(selectedOption.text.split(' (')[0]);
                    optionsPrice += parseFloat(selectedOption.dataset.precioExtra || 0);
                });

                if (!allOptionsSelected && modifiers.length > 0) {
                    alert('Por favor, selecciona todas las opciones.');
                    return;
                }

                const activeSizeButton = card.querySelector('.size-btn.active');
                const sizeName = activeSizeButton ? activeSizeButton.querySelector('span').textContent : 'N/A';
                const sizePrice = activeSizeButton ? parseFloat(activeSizeButton.dataset.precioAumento) : 0;
                
                const basePrice = parseFloat(card.dataset.basePrice);
                const quantity = parseInt(card.querySelector('.quantity-value').textContent);
                const unitPrice = basePrice + sizePrice + optionsPrice;
                
                const requests = card.querySelector('.extra-requests-input').value.trim();
                if(requests) {
                    optionsDesc.push(`(${requests})`);
                }

                const productData = {
                    idp: card.dataset.id,
                    name: card.dataset.name,
                    price: unitPrice,
                    qty: quantity,
                    size: sizeName,
                    desc: optionsDesc.join(', '), 
                    imgSrc: card.querySelector('.card-image img').src,
                    cartKey: `${card.dataset.name}|${sizeName}|${optionsDesc.join(',')}` 
                };
                
                // ¡Llama a la API global del carrito!
                if (window.CarritoAPI && typeof window.CarritoAPI.agregar === 'function') {
                    window.CarritoAPI.agregar(productData);
                } else {
                    alert("Error: El carrito no está cargado.");
                }
            }
        });
        
        contenedorProductos.addEventListener('change', (event) => {
            const target = event.target;
            if (target.matches('.modifier-select') || target.matches('.size-btn')) {
                const card = target.closest('.product-card-new');
                if (card) updateCardPrice(card);
            }
        });
    }
    
    // --- FUNCIONES AUXILIARES DE LA TARJETA ---

    function updateCardPrice(card) {
        const basePrice = parseFloat(card.dataset.basePrice);
        
        let modifiersPrice = 0;
        const modifiers = card.querySelectorAll('.modifier-select:not(:disabled)');
        modifiers.forEach(select => {
            if(select.selectedIndex > 0) {
                modifiersPrice += parseFloat(select.options[select.selectedIndex].dataset.precioExtra);
            }
        });

        const sizeButton = card.querySelector('.size-btn.active');
        const sizeIncrease = sizeButton ? parseFloat(sizeButton.dataset.precioAumento) : 0;
        const quantity = parseInt(card.querySelector('.quantity-value').textContent);
        const unitPrice = basePrice + sizeIncrease + modifiersPrice;
        const finalPrice = unitPrice * quantity;
        
        card.querySelector('.card-price').textContent = `$${finalPrice.toFixed(2)}`;
    }

    function generarTamanosHtml(tamanos, tamanoDefecto, isFood = false) {
        const tamanoMap = {
            'Chico': {svg: '<svg viewBox="0 0 24 24"><path d="M4 10h16v10a2 2 0 01-2 2H6a2 2 0 01-2-2v-10zm2-4h12v2H6V6z"/></svg>'},
            'Mediano': {svg: '<svg viewBox="0 0 24 24"><path d="M3 8h18v10a3 3 0 01-3 3H6a3 3 0 01-3-3V8zm3-4h12v2H6V4z"/></svg>'},
            'Grande': {svg: '<svg viewBox="0 0 24 24"><path d="M2 7h20v10a4 4 0 01-4 4H6a4 4 0 01-4-4V7zm4-4h12v2H6V3z"/></svg>'}
        };
        const labels = isFood
            ? {'Chico': 'CH', 'Mediano': 'M', 'Grande': 'G'}
            : {'Chico': 'CH', 'Mediano': 'M', 'Grande': 'G'};

        let html = '';
        if (tamanos) {
            tamanos.forEach(tamano => {
                const displayInfo = tamanoMap[tamano.nombre_tamano] || { svg: '' };
                const texto = labels[tamano.nombre_tamano] || tamano.nombre_tamano;
                const esActivo = tamano.tamano_id == tamanoDefecto ? 'active' : '';
                
                html += `<button class="size-btn ${esActivo}" data-tamano-id="${tamano.tamano_id}" data-precio-aumento="${tamano.precio_aumento}">
                            ${displayInfo.svg}
                            <span>${texto}</span>
                        </button>`;
            });
        }
        return html;
    }
    
    // Las funciones del carrito (renderCart, updateSummary, etc.) se han movido a carrito.js

});
</script>
<script src="../../translate.js"></script>
</body>
</html>