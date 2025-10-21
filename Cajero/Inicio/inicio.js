document.addEventListener('DOMContentLoaded', () => {
    // --- URLs y Selectores Principales ---
    const apiUrl = 'obtener_productos.php';
    const searchInput = document.querySelector('.search');
    const catButtons = document.querySelectorAll('.cat-btn');
    const productsContainer = document.querySelector('.products');
    let allProducts = [];

    // --- Selectores del Carrito ---
    const cartList = [];
    const cartContent = document.querySelector('.cart-content');
    const summarySubtotal = document.querySelector('.summary-subtotal');
    const summaryIVA = document.querySelector('.summary-iva');
    const summaryTotal = document.querySelector('.summary-total');

    // --- Selectores del Modal ---
    const modalBg = document.getElementById('modal-bg');
    const modalTitle = document.getElementById('modal-title');
    const modalLeche = document.getElementById('modal-leche');
    const modalTamano = document.getElementById('modal-tamano');
    let modalProduct = null;

    // --- LÓGICA DEL CARRITO Y MODAL (Movida desde inicio.html) ---

    function getProductData(card) {
        // Función auxiliar para leer datos de una tarjeta de producto
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

    // Event listener para los botones +/- del carrito
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

    // Event listeners del modal
    modalBg.addEventListener('click', e => {
        if (e.target === modalBg) modalBg.style.display = 'none';
    });
    document.getElementById('modal-cancelar').onclick = () => modalBg.style.display = 'none';
    document.getElementById('modal-agregar').onclick = () => {
        if (!modalProduct) return;
        const leche = modalLeche.value;
        const tamano = modalTamano.value;
        const data = { ...modalProduct, leche, tamano };
        const idx = cartList.findIndex(p => p.name === data.name && p.desc === data.desc && p.leche === data.leche && p.tamano === data.tamano);
        if (idx >= 0) {
            cartList[idx].qty += 1;
        } else {
            cartList.push({ ...data, qty: 1 });
        }
        renderCart();
        modalBg.style.display = 'none';
    };

    // --- LÓGICA DE CARGA DE PRODUCTOS ---

    // Función para crear una tarjeta de producto
    function createProductCard(product) {
        const card = document.createElement('article');
        card.className = 'product-card';
        card.innerHTML = `
            <div class="product-img">
                ${product.imagen_url ? `<img src="${product.imagen_url}" width="80" height="60" alt="${product.nombre}">` : '<img src="../img/placeholder.png" width="80" height="60" alt="Sin imagen">'}
            </div>
            <h3 class="product-title">${product.nombre}</h3>
            <p class="product-sub">${product.descripcion || ''}</p>
            <div class="product-footer">
                <div class="price">$${parseFloat(product.precio).toFixed(2)}</div>
                <button class="add-btn" aria-label="Agregar al carrito">
                    <svg width="18" height="18" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2S15.9 22 17 22s2-.9 2-2-.9-2-2-2zM7.16 14l.84-2h7.45c.75 0 1.41-.41 1.75-1.03l3.24-5.88A1 1 0 0 0 20.45 4H5.21l-.94-2H1v2h2l3.6 7.59-1.35 2.44C4.52 15.37 5.48 17 7 17h12v-2H7.42c-.14 0-.25-.11-.25-.25z"/>
                    </svg>
                </button>
            </div>
        `;
        return card;
    }

    // Función para cargar productos desde el servidor (esta ya era funcional)
    async function loadProducts(categoria = '', busqueda = '') {
        try {
            let url = apiUrl;
            const params = new URLSearchParams();
            // Asegurarse de que 'categorías' se trate como 'todas'
            if (categoria && categoria !== 'categorías') params.append('categoria', categoria);
            if (busqueda) params.append('buscar', busqueda);
            if (params.toString()) url += '?' + params.toString();

            const response = await fetch(url);
            const data = await response.json();

            if (!data.success) throw new Error(data.error || 'Error al cargar productos');

            allProducts = data.data;
            renderProducts(allProducts);
            
            // ¡IMPORTANTE! Volver a enlazar eventos después de renderizar
            attachProductEvents();

        } catch (error) {
            console.error('Error:', error);
            productsContainer.innerHTML = '<p>Error al cargar productos. Por favor, intente más tarde.</p>';
        }
    }

    // Función para renderizar productos
    function renderProducts(products) {
        productsContainer.innerHTML = '';
        if (!products.length) {
            productsContainer.innerHTML = '<p>No se encontraron productos.</p>';
            return;
        }
        products.forEach(product => {
            productsContainer.appendChild(createProductCard(product));
        });
    }

    // ¡¡AQUÍ ESTÁ LA MAGIA!!
    // Esta función ahora usa la lógica del carrito/modal que movimos a este archivo.
    function attachProductEvents() {
        // Evento para ABRIR MODAL al hacer clic en la tarjeta
        document.querySelectorAll('.product-card').forEach(card => {
            card.addEventListener('click', e => {
                if (e.target.closest('.add-btn')) return; // Si se hizo clic en el +, no abrir modal
                
                // Usar la lógica del modal
                modalProduct = getProductData(card);
                modalTitle.textContent = modalProduct.name;
                modalLeche.selectedIndex = 0;
                modalTamano.selectedIndex = 0;
                modalBg.style.display = 'flex';
            });
        });

        // Evento para AÑADIR AL CARRITO con el botón '+'
        document.querySelectorAll('.add-btn').forEach(btn => {
            btn.addEventListener('click', e => {
                e.stopPropagation(); // Evitar que el clic también abra el modal
                
                // Usar la lógica de añadir al carrito
                const card = btn.closest('.product-card');
                const data = getProductData(card);
                const leche = "Entera"; // Opciones por defecto
                const tamano = "Chico";
                
                const idx = cartList.findIndex(p => p.name === data.name && p.desc === data.desc && p.leche === leche && p.tamano === tamano);
                if (idx >= 0) {
                    cartList[idx].qty += 1;
                } else {
                    cartList.push({ ...data, leche, tamano, qty: 1 });
                }
                renderCart();
            });
        });
    }

    // --- INICIALIZACIÓN Y EVENTOS DE FILTRO ---

    // Manejador de búsqueda con debounce (esto ya estaba bien)
    let searchTimeout;
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const categoria = document.querySelector('.cat-btn.active')?.textContent.toLowerCase() || '';
            loadProducts(categoria, searchInput.value);
        }, 300); // Espera 300ms después de que el usuario deja de escribir
    });

    // Manejador de categorías (esto ya estaba bien)
    catButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            catButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            loadProducts(btn.textContent.toLowerCase(), searchInput.value);
        });
    });

    // --- Carga Inicial ---
    renderCart(); // Renderizar el carrito vacío al inicio
    loadProducts(); // Cargar productos iniciales
});