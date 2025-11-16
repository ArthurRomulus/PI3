/*
  CARRITO MODULAR DE BLACKWOOD COFFEE
  (Versión final con Stripe, corrección de sintaxis, caché Y RUTAS)
*/
(function() {
    // --- 1. VARIABLES GLOBALES DEL MÓDULO ---
    let cartList = [];
    const IVA_RATE = 0.16;
    let totalGeneral = 0;
    let isOrderLoaded = false; 

    // --- Variables de Stripe ---
    const stripePublicKey = 'pk_test_51SSiVI6xsnAsFl7HGfd0lPd7bm5TLSTDuZS4MdGMHLkIXFz2O0SfJMe1V7SgzObmSWdXN0PinoRnCKfVuGrFYSgi003W0zORcA';
    let stripe;
    let cardElement;
    let paymentModalOverlay;
    let paymentModalTotalEl;
    let submitPaymentBtn;
    let cancelPaymentModalBtn;
    let cardErrorsEl;
    
    // Selectores
    let cartElement, cartContent, summarySubtotal, summaryIVA, summaryTotal;
    let payButton, orderNumberEl, clearCartButton, searchIcon, statusPill;
    let paymentChoiceDiv, payEfectivoButton, payTarjetaButton, cancelPayButton;

    // --- 2. LÓGICA DE LOCALSTORAGE ---
    function guardarCarrito() {
        if (!isOrderLoaded) {
            localStorage.setItem('blackwoodCart', JSON.stringify(cartList));
        }
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

        let subtotal, iva, total;
        
        if (isOrderLoaded) {
            total = totalGeneral;
            subtotal = total / (1 + IVA_RATE);
            iva = total - subtotal;
        } else {
            subtotal = cartList.reduce((sum, item) => sum + (item.price * item.qty), 0);
            iva = subtotal * IVA_RATE;
            total = subtotal + iva;
            totalGeneral = total; 
        }
        
        summarySubtotal.textContent = `$${subtotal.toFixed(2)}`;
        summaryIVA.textContent = `$${iva.toFixed(2)}`;
        summaryTotal.textContent = `$${(total).toFixed(2)}`;
        
        if (paymentModalTotalEl) {
            paymentModalTotalEl.textContent = `$${(total).toFixed(2)}`;
        }
    }

    function renderCart(items = cartList) {
        if (!cartContent) return; 

        cartContent.innerHTML = '';
        if (items.length === 0) {
            cartContent.innerHTML = '<p class="empty-cart">El carrito está vacío</p>';
        } else {
            items.forEach((item, idx) => {
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
    
    function mostrarEstadoPedido(pedido) {
        if (!statusPill) return;
        
        let estadoClass = '';
        let estadoTexto = '';
        let pagoTexto = '';
        
        if (pedido.estado.toLowerCase() === 'terminado') {
            estadoClass = 'status-terminado';
            estadoTexto = 'Terminado';
        } else {
            estadoClass = 'status-proceso';
            estadoTexto = 'Proceso';
        }
        
        if (pedido.metodo_pago === 'Tarjeta') {
            estadoClass = 'status-pagado';
            pagoTexto = 'Pago con Tarjeta';
        } else {
            pagoTexto = 'Pagar en Efectivo';
        }
        
        if (pedido.estado.toLowerCase() === 'terminado') {
             pagoTexto = pedido.metodo_pago;
             estadoClass = 'status-terminado';
        }

        statusPill.className = 'cart-status-pill';
        statusPill.classList.add(estadoClass);
        statusPill.innerHTML = `<span class="status-label">${estadoTexto}</span> | <span class="payment-label">${pagoTexto}</span>`;
        statusPill.style.display = 'block';
    }

    // --- 4. FUNCIONES DE MANIPULACIÓN (LA "API" interna) ---
    
    function agregarProducto(producto) {
        if (isOrderLoaded) vaciarCarrito();
        
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
        totalGeneral = 0;
        isOrderLoaded = false;
        guardarCarrito(); 
        renderCart();
        
        localStorage.removeItem('blackwoodOrderNum');
        if (orderNumberEl) {
            orderNumberEl.textContent = getOrderNumber();
        }
        if (statusPill) {
            statusPill.style.display = 'none';
        }
        if (cartElement) {
            cartElement.classList.remove('order-loaded');
        }
        setPaymentMode(false);
    }
    
    function setPaymentMode(isChoosing) {
        if (isChoosing) {
            cartElement.classList.add('payment-active');
        } else {
            cartElement.classList.remove('payment-active');
        }
    }
    
    function procesarPago(metodoPago, tokenStripe = null) {
        if (cartList.length === 0) {
            alert("El carrito está vacío.");
            return;
        }

        payEfectivoButton.disabled = true;
        payTarjetaButton.disabled = true;
        payEfectivoButton.textContent = "Procesando...";

        const datosPedido = {
            carrito: cartList,
            total: totalGeneral,
            metodo: metodoPago,
            token: tokenStripe
        };

        // Ruta corregida a la API
        fetch('../Carrito/registrar_pedido.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(datosPedido)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert("¡Gracias por tu compra! Tu pedido es el #" + data.nuevoPedidoId);
                vaciarCarrito(); 
            } else {
                alert("Hubo un error con el pago: " + data.error);
            }
        })
        .catch(error => {
            console.error('Error de fetch:', error);
            alert("Error de conexión. No se pudo procesar el pedido.");
        })
        .finally(() => {
            payEfectivoButton.disabled = false;
            payTarjetaButton.disabled = false;
            payEfectivoButton.textContent = "Efectivo";
            setPaymentMode(false);
            ocultarModalPago();
        });
    }

    function buscarPedido() {
        const pedidoId = prompt("Por favor, ingresa el número de pedido:");
        if (!pedidoId || pedidoId.trim() === '') {
            return;
        }

        // Ruta corregida a la API
        fetch('../Carrito/buscar_pedido.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ pedido_id: pedidoId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const pedido = data.pedido;
                isOrderLoaded = true;
                cartElement.classList.add('order-loaded');
                totalGeneral = pedido.total;
                renderCart(pedido.items);
                orderNumberEl.textContent = pedido.id_pedido;
                mostrarEstadoPedido(pedido);
                setPaymentMode(false);
            } else {
                alert("Error: " + data.error);
            }
        })
        .catch(error => {
            console.error('Error de fetch:', error);
            alert("Error de conexión. No se pudo buscar el pedido.");
        });
    }

    // --- FUNCIONES DEL MODAL DE STRIPE ---
    function inicializarStripe() {
        try {
            stripe = Stripe(stripePublicKey);
            const elements = stripe.elements();
            
            const style = {
                base: {
                    color: '#332a23',
                    fontFamily: '"Poppins", system-ui, sans-serif',
                    fontSize: '16px',
                    '::placeholder': {
                        color: '#bfa77a'
                    }
                },
                invalid: {
                    color: '#dc3545',
                    iconColor: '#dc3545'
                }
            };
            
            cardElement = elements.create('card', { style: style });
            cardElement.mount('#card-element');
            
            cardElement.on('change', (event) => {
                if (event.error) {
                    cardErrorsEl.textContent = event.error.message;
                } else {
                    cardErrorsEl.textContent = '';
                }
            });

        } catch (error) {
            console.error("Error al inicializar Stripe. ¿Pusiste la clave publicable?", error);
            if (payTarjetaButton) {
                payTarjetaButton.disabled = true;
                payTarjetaButton.textContent = "Error de Stripe";
            }
        }
    }
    
    function mostrarModalPago() {
        if (!paymentModalOverlay) return;
        updateSummary(); 
        paymentModalOverlay.classList.remove('payment-modal-hidden');
    }
    
    function ocultarModalPago() {
        if (!paymentModalOverlay) return;
        paymentModalOverlay.classList.add('payment-modal-hidden');
        if (cardElement) cardElement.clear(); 
        if (cardErrorsEl) cardErrorsEl.textContent = '';
    }
    
    async function manejarSubmitPago(e) {
        e.preventDefault();
        submitPaymentBtn.disabled = true;
        submitPaymentBtn.textContent = "Procesando...";

        const { token, error } = await stripe.createToken(cardElement);

        if (error) {
            cardErrorsEl.textContent = error.message;
            submitPaymentBtn.disabled = false;
            submitPaymentBtn.textContent = "Pagar " + paymentModalTotalEl.textContent;
        } else {
            cardErrorsEl.textContent = '';
            procesarPago('Tarjeta', token.id);
        }
    }

    // --- 5. INICIALIZACIÓN Y EVENT LISTENERS ---
    
    async function init() {
        const placeholder = document.getElementById('carrito-placeholder');
        if (!placeholder) {
            console.error("No se encontró #carrito-placeholder.");
            return;
        }

        try {
            // "Cache buster" para forzar la recarga del HTML
            const cacheBuster = "?v=" + new Date().getTime();
            const response = await fetch('../Carrito/carrito.html' + cacheBuster);

            const html = await response.text();
            placeholder.innerHTML = html;
            
            // Asignar selectores (todos)
            cartElement = document.querySelector('#carrito-placeholder .cart');
            cartContent = document.querySelector('#carrito-placeholder .cart-content');
            summarySubtotal = document.querySelector('#carrito-placeholder .summary-subtotal');
            summaryIVA = document.querySelector('#carrito-placeholder .summary-iva');
            summaryTotal = document.querySelector('#carrito-placeholder .summary-total');
            payButton = document.querySelector('#carrito-placeholder .pay');
            orderNumberEl = document.querySelector('#carrito-placeholder .order-number');
            clearCartButton = document.querySelector('#carrito-placeholder .cart-clear-all');
            paymentChoiceDiv = document.querySelector('#carrito-placeholder .payment-choice');
            payEfectivoButton = document.querySelector('#carrito-placeholder .btn-efectivo');
            payTarjetaButton = document.querySelector('#carrito-placeholder .btn-tarjeta');
            cancelPayButton = document.querySelector('#carrito-placeholder .cart-cancel-payment');
            searchIcon = document.querySelector('#carrito-placeholder .order-search-icon');
            statusPill = document.querySelector('#carrito-placeholder .cart-status-pill');
            
            // Selectores del Modal de Stripe
            paymentModalOverlay = document.querySelector('#carrito-placeholder #payment-modal-overlay');
            paymentModalTotalEl = document.querySelector('#carrito-placeholder #payment-modal-total');
            submitPaymentBtn = document.querySelector('#carrito-placeholder #submit-payment-btn');
            cancelPaymentModalBtn = document.querySelector('#carrito-placeholder #cancel-payment-modal-btn');
            cardErrorsEl = document.querySelector('#carrito-placeholder #card-errors');
            
            cartList = leerCarrito();
            renderCart();
            
            if(orderNumberEl) {
                orderNumberEl.textContent = getOrderNumber();
            }
            
            if (submitPaymentBtn && cardErrorsEl && typeof Stripe === 'function') {
                 inicializarStripe();
            } else {
                console.error("No se encontraron los elementos del modal de Stripe o Stripe.js no se cargó.");
                if(payTarjetaButton) payTarjetaButton.disabled = true;
            }

            // --- Asignar todos los listeners (con chequeo de existencia) ---
            
            if (searchIcon) searchIcon.addEventListener('click', buscarPedido);

            if (cartContent) cartContent.addEventListener('click', e => {
                if (isOrderLoaded) return;
                if (e.target.classList.contains('qty-btn-cart')) {
                    modificarCantidad(e.target.dataset.idx, e.target.dataset.action);
                }
                if (e.target.classList.contains('cart-item-delete')) {
                    e.preventDefault();
                    eliminarProducto(e.target.dataset.idx);
                }
            });

            if (payButton) payButton.addEventListener('click', () => {
                if (isOrderLoaded) return; 
                if (cartList.length === 0) {
                    alert("El carrito está vacío.");
                    return;
                }
                setPaymentMode(true); 
            });
            
            if (payEfectivoButton) payEfectivoButton.addEventListener('click', () => {
                procesarPago('Efectivo');
            });
            
            if (payTarjetaButton) payTarjetaButton.addEventListener('click', () => {
                mostrarModalPago();
            });
            
            if (cancelPayButton) cancelPayButton.addEventListener('click', (e) => {
                e.preventDefault();
                setPaymentMode(false);
            });
            
            if (cancelPaymentModalBtn) cancelPaymentModalBtn.addEventListener('click', (e) => {
                e.preventDefault();
                ocultarModalPago();
                setPaymentMode(false);
            });
            
            if (submitPaymentBtn) submitPaymentBtn.addEventListener('click', manejarSubmitPago);

            if (clearCartButton) clearCartButton.addEventListener('click', (e) => {
                e.preventDefault();
                if (cartList.length > 0 || isOrderLoaded) {
                    if (confirm("¿Estás seguro de que quieres limpiar la pantalla?")) {
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