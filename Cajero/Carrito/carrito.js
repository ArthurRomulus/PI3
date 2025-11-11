/*
  CARRITO MODULAR DE BLACKWOOD COFFEE
*/
(function() {
    // --- 1. VARIABLES GLOBALES DEL MÓDULO ---
    let cartList = [];
    const IVA_RATE = 0.16;
    
    // Selectores
    let cartContent, summarySubtotal, summaryIVA, summaryTotal, payButton, orderNumberEl, clearCartButton;

    // --- 2. LÓGICA DE LOCALSTORAGE ---
    function guardarCarrito() {
        localStorage.setItem('blackwoodCart', JSON.stringify(cartList));
    }

    function leerCarrito() {
        const cartGuardado = localStorage.getItem('blackwoodCart');
        if (cartGuardado) {
            return JSON.parse(cartGuardado);
        }
        return [];
    }
    
    function getOrderNumber() {
        let num = localStorage.getItem('blackwoodOrderNum');
        if (!num) {
            num = Math.floor(Math.random() * (999 - 100 + 1)) + 100;
            localStorage.setItem('blackwoodOrderNum', num);
        }
        return num;
    }

    // --- 3. FUNCIONES DEL CARRITO (Renderizado) ---
    function updateSummary() {
        if (!summarySubtotal) return; 

        const subtotal = cartList.reduce((sum, item) => sum + (item.price * item.qty), 0);
        const iva = subtotal * IVA_RATE;
        const total = subtotal + iva;
        
        summarySubtotal.textContent = `$${subtotal.toFixed(2)}`;
        summaryIVA.textContent = `$${iva.toFixed(2)}`;
        summaryTotal.textContent = `$${(total).toFixed(2)}`;
    }

    // --- (MODIFICADA) renderCart ---
    function renderCart() {
        if (!cartContent) return; 

        cartContent.innerHTML = '';
        if (cartList.length === 0) {
            cartContent.innerHTML = '<p class="empty-cart">El carrito está vacío</p>';
        } else {
            cartList.forEach((item, idx) => {
                const div = document.createElement('div');
                div.className = 'cart-item';
                
                let optionsDesc = `${item.size}, ${item.desc}`;

                div.innerHTML = `
                    <div class="cart-thumb"><img src="${item.imgSrc}" alt="${item.name}"></div>
                    <div class="cart-info">
                        <div class="cart-title">${item.name}</div>
                        <div class="cart-desc">${optionsDesc}</div>
                        <div class="cart-price">$${(item.price * item.qty).toFixed(2)}</div> 
                    </div>
                    <div class="cart-qty">
                        <div class="cart-qty-controls">
                            <button class="qty-btn-cart" data-idx="${idx}" data-action="minus">-</button>
                            <div class="qty-num">${item.qty.toString().padStart(2, '0')}</div>
                            <button class="qty-btn-cart" data-idx="${idx}" data-action="plus">+</button>
                        </div>
                        
                        <a href="#" class="cart-item-delete" data-idx="${idx}">Eliminar</a>
                    </div>`;
                cartContent.appendChild(div);
            });
        }
        updateSummary();
    }

    // --- 4. FUNCIONES DE MANIPULACIÓN (LA "API" interna) ---
    
    function agregarProducto(producto) {
        const existingProduct = cartList.find(item => item.cartKey === producto.cartKey);
        
        if (existingProduct) {
            existingProduct.qty += producto.qty;
        } else {
            cartList.push(producto);
        }
        guardarCarrito();
        renderCart();
    }
    
    function eliminarProducto(index) {
        const idx = parseInt(index);
        cartList.splice(idx, 1);
        guardarCarrito();
        renderCart();
    }

    function modificarCantidad(index, accion) {
        const idx = parseInt(index);
        if (accion === 'plus') {
            cartList[idx].qty += 1;
        } else if (accion === 'minus') {
            cartList[idx].qty -= 1;
            if (cartList[idx].qty <= 0) {
                eliminarProducto(idx);
                return;
            }
        }
        guardarCarrito();
        renderCart();
    }
    
    function vaciarCarrito() {
        cartList = [];
        guardarCarrito();
        renderCart();
        localStorage.removeItem('blackwoodOrderNum');
        if (orderNumberEl) {
            orderNumberEl.textContent = getOrderNumber();
        }
    }

    // --- 5. INICIALIZACIÓN Y EVENT LISTENERS ---
    
    async function init() {
        const placeholder = document.getElementById('carrito-placeholder');
        if (!placeholder) {
            console.error("No se encontró #carrito-placeholder. El carrito no se cargará.");
            return;
        }

        try {
            const response = await fetch('../Carrito/carrito.html');
            const html = await response.text();
            placeholder.innerHTML = html;
            
            cartContent = document.querySelector('#carrito-placeholder .cart-content');
            summarySubtotal = document.querySelector('#carrito-placeholder .summary-subtotal');
            summaryIVA = document.querySelector('#carrito-placeholder .summary-iva');
            summaryTotal = document.querySelector('#carrito-placeholder .summary-total');
            payButton = document.querySelector('#carrito-placeholder .pay');
            orderNumberEl = document.querySelector('#carrito-placeholder .order-number');
            clearCartButton = document.querySelector('#carrito-placeholder .cart-clear-all');

            cartList = leerCarrito();
            renderCart();
            
            if(orderNumberEl) {
                orderNumberEl.textContent = getOrderNumber();
            }

            cartContent.addEventListener('click', e => {
                if (e.target.classList.contains('qty-btn-cart')) {
                    modificarCantidad(e.target.dataset.idx, e.target.dataset.action);
                }
                
                if (e.target.classList.contains('cart-item-delete')) {
                    e.preventDefault();
                    eliminarProducto(e.target.dataset.idx);
                }
            });

            payButton.addEventListener('click', () => {
                if (cartList.length === 0) {
                    alert("El carrito está vacío.");
                    return;
                }
                alert('Iniciando proceso de pago...');
            });

            clearCartButton.addEventListener('click', (e) => {
                e.preventDefault();
                if (cartList.length > 0) {
                    if (confirm("¿Estás seguro de que quieres vaciar todo el carrito?")) {
                        vaciarCarrito();
                    }
                }
            });

        } catch (error) {
            console.error('Error al cargar el HTML del carrito:', error);
            placeholder.innerHTML = "<p>Error al cargar carrito.</p>";
        }
    }

    // --- 6. EXPONER LA API GLOBAL ---
    window.CarritoAPI = {
        agregar: agregarProducto,
        vaciar: vaciarCarrito
    };

    // --- 7. INICIO AUTOMÁTICO ---
    document.addEventListener('DOMContentLoaded', init);

})();