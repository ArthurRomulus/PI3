/*
  CARRITO MODULAR DE BLACKWOOD COFFEE
  (Con Input de Pedido Editable y Búsqueda Integrada)
*/
(function() {
    // --- 1. VARIABLES GLOBALES ---
    let cartList = [];
    let totalGeneral = 0;
    let isOrderLoaded = false; 
    const IVA_RATE = 0.16;
    
    // MAPA DE INVENTARIO
    let inventarioGlobal = {}; 

    // Variables de Stripe
    const stripePublicKey = 'pk_test_51SSiVI6xsnAsFl7HGfd0lPd7bm5TLSTDuZS4MdGMHLkIXFz2O0SfJMe1V7SgzObmSWdXN0PinoRnCKfVuGrFYSgi003W0zORcA';
    let stripe, cardElement, paymentModalOverlay, paymentModalTotalEl, submitPaymentBtn, cancelPaymentModalBtn, cardErrorsEl;
    
    // Selectores UI (orderNumberEl ahora será el INPUT)
    let cartElement, cartContent, summarySubtotal, summaryIVA, summaryTotal, payButton, orderInputEl, clearCartButton, searchIcon, statusPill;
    let payEfectivoButton, payTarjetaButton, cancelPayButton;

    // --- 2. LÓGICA DE ALMACENAMIENTO ---
    function guardarCarrito() {
        if (!isOrderLoaded) localStorage.setItem('blackwoodCart', JSON.stringify(cartList));
    }

    function leerCarrito() {
        const cartGuardado = localStorage.getItem('blackwoodCart');
        return cartGuardado ? JSON.parse(cartGuardado) : [];
    }
    
    function generarNuevoNumeroPedido() {
        // Solo generamos uno nuevo si no estamos viendo uno cargado
        let num = Math.floor(Math.random() * (999 - 100 + 1)) + 100;
        // Guardamos temporalmente por si recargan la página
        localStorage.setItem('blackwoodOrderNum', num);
        return num;
    }

    function getStoredOrderNumber() {
        return localStorage.getItem('blackwoodOrderNum') || generarNuevoNumeroPedido();
    }

    // --- 3. FUNCIONES DE RENDERIZADO ---
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
        if (paymentModalTotalEl) paymentModalTotalEl.textContent = `$${(total).toFixed(2)}`;
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
                            <button class="qty-btn-cart" data-idx="${idx}" data-action="minus" ${isOrderLoaded ? 'disabled' : ''}>-</button>
                            <div class="qty-num">${item.qty.toString().padStart(2, '0')}</div>
                            <button class="qty-btn-cart" data-idx="${idx}" data-action="plus" ${isOrderLoaded ? 'disabled' : ''}>+</button>
                        </div>
                        ${!isOrderLoaded ? `<a href="#" class="cart-item-delete" data-idx="${idx}">Eliminar</a>` : ''}
                    </div>`;
                cartContent.appendChild(div);
            });
        }
        updateSummary();
    }
    
    function mostrarEstadoPedido(pedido) {
        if (!statusPill) return;
        let estadoClass = '', estadoTexto = '', pagoTexto = '';
        
        // Normalización básica
        const estado = pedido.estado ? pedido.estado.toLowerCase() : '';
        const metodo = pedido.metodo_pago ? pedido.metodo_pago.toLowerCase() : '';

        if (estado.includes('terminado') || estado.includes('completado')) { 
            estadoClass = 'status-terminado'; 
            estadoTexto = 'Terminado'; 
        } else { 
            estadoClass = 'status-proceso'; 
            estadoTexto = 'Proceso'; 
        }
        
        if (metodo.includes('tarjeta')) { 
            pagoTexto = 'Pago con Tarjeta'; 
        } else { 
            pagoTexto = 'Pago en Efectivo'; 
        }
        
        // Si ya está pagado (terminado), mostramos verde
        if (estado.includes('terminado') || estado.includes('completado')) {
             estadoClass = 'status-terminado'; 
        }

        statusPill.className = 'cart-status-pill';
        statusPill.classList.add(estadoClass);
        statusPill.innerHTML = `<span class="status-label">${estadoTexto}</span> | <span class="payment-label">${pagoTexto}</span>`;
        statusPill.style.display = 'block';
    }

    // --- 4. FUNCIONES DE MANIPULACIÓN ---
    
    function agregarProducto(producto) {
        if (isOrderLoaded) {
             // Si hay un pedido cargado y el usuario agrega algo nuevo, asumimos que quiere empezar una orden nueva
             if(confirm("Estás viendo un pedido pasado. ¿Quieres iniciar una nueva venta?")) {
                 vaciarCarrito();
             } else {
                 return;
             }
        }
        
        let stockMaximo = 9999; 
        if (inventarioGlobal[producto.idp] !== undefined) {
            stockMaximo = inventarioGlobal[producto.idp];
        }

        const existingProduct = cartList.find(item => item.cartKey === producto.cartKey);
        let cantidadEnCarrito = existingProduct ? existingProduct.qty : 0;
        
        if ((cantidadEnCarrito + producto.qty) > stockMaximo) {
            alerta("error", "Oops...", `No puedes agregar más. Solo hay ${stockMaximo} unidades en stock.`);
            return; 
        }

        if (existingProduct) {
            existingProduct.qty += producto.qty;
        } else {
            cartList.push(producto);
        }
        guardarCarrito();
        renderCart();
    }
    
    function eliminarProducto(index) {
        cartList.splice(index, 1);
        guardarCarrito();
        renderCart();
    }

    function modificarCantidad(index, accion) {
        if (isOrderLoaded) return; // No editar pedidos cargados

        const idx = parseInt(index);
        const item = cartList[idx];

        if (accion === 'plus') {
            let stockMaximo = 9999;
            if (inventarioGlobal[item.idp] !== undefined) {
                stockMaximo = inventarioGlobal[item.idp];
            }

            if ((item.qty + 1) > stockMaximo) {
                alerta("error", "Límite alcanzado", `Solo quedan ${stockMaximo} en stock.`);
                return; 
            }
            item.qty += 1;

        } else if (accion === 'minus') {
            item.qty -= 1;
            if (item.qty <= 0) {
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
        
        // Generar nuevo número de pedido fresco
        const nuevoNum = generarNuevoNumeroPedido();
        if (orderInputEl) orderInputEl.value = nuevoNum;
        
        if (statusPill) statusPill.style.display = 'none';
        if (cartElement) cartElement.classList.remove('order-loaded');
        setPaymentMode(false);
    }
    
    function setPaymentMode(isChoosing) {
        if (isChoosing) cartElement.classList.add('payment-active');
        else cartElement.classList.remove('payment-active');
    }
    
    // --- BÚSQUEDA DE PEDIDO ---
    function buscarPedido() {
        // Leer valor del INPUT
        const pedidoId = orderInputEl.value.trim();
        
        if (!pedidoId) {
            alerta("warning", "Atención", "Por favor ingresa un número de pedido.");
            return;
        }

        // UI Feedback
        searchIcon.classList.add('fa-spin'); // Si usas FontAwesome, esto lo hace girar

        fetch('../Carrito/buscar_pedido.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ pedido_id: pedidoId })
        })
        .then(res => res.json())
        .then(data => {
            searchIcon.classList.remove('fa-spin');
            
            if (data.success) {
                const pedido = data.pedido;
                isOrderLoaded = true;
                
                // Actualizar UI
                cartElement.classList.add('order-loaded');
                
                // Cargar datos
                totalGeneral = pedido.total;
                renderCart(pedido.items); // Renderizará los items, pero sin botones de eliminar por el flag isOrderLoaded
                
                mostrarEstadoPedido(pedido);
                setPaymentMode(false);
                
                // Notificación suave
                const Toast = Swal.mixin({
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 2000, timerProgressBar: true
                });
                Toast.fire({ icon: 'success', title: `Pedido #${pedidoId} cargado` });

            } else {
                alerta("error", "No encontrado", data.error);
                // Si falla, podemos limpiar el input o dejarlo para que el usuario corrija
            }
        })
        .catch(error => { 
            searchIcon.classList.remove('fa-spin');
            console.error(error); 
            alerta("error", "Error", "Error de conexión al buscar el pedido.");
        });
    }

    function procesarPago(metodoPago, tokenStripe = null) {
        // ... (Código igual al anterior, solo cambia alertas)
        if (cartList.length === 0) { 
            alerta("info", "Carrito Vacío", "Agrega productos antes de pagar.");
            return; 
        }

        payEfectivoButton.disabled = true;
        payTarjetaButton.disabled = true;
        payEfectivoButton.textContent = "Procesando...";

        const datosPedido = {
            carrito: cartList,
            total: totalGeneral,
            metodo: metodoPago,
            token: tokenStripe,
            // Enviamos el ID que está en el input, aunque el backend generará el autoincrement real.
            // Esto es solo visual por si quisieras guardarlo como referencia manual.
            id_visual: orderInputEl.value 
        };

        fetch('../Carrito/registrar_pedido.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(datosPedido)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if(typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: "success",
                        title: "¡Pedido Registrado!",
                        text: "El pedido #" + data.nuevoPedidoId + " ha sido procesado.",
                        confirmButtonColor: '#28a745'
                    });
                } else {
                    alert("¡Gracias por tu compra! Pedido #" + data.nuevoPedidoId);
                }
                cargarInventarioDesdeAPI(); 
                vaciarCarrito(); 
                // Después de vaciar, ponemos el NUEVO ID generado para el siguiente
                if(orderInputEl) orderInputEl.value = generarNuevoNumeroPedido();
            } else {
                alerta("error", "Error", data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alerta("error", "Error", "Error de conexión.");
        })
        .finally(() => {
            payEfectivoButton.disabled = false;
            payTarjetaButton.disabled = false;
            payEfectivoButton.textContent = "Efectivo";
            setPaymentMode(false);
            ocultarModalPago();
        });
    }

    // Helper para alertas
    function alerta(icono, titulo, texto) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: icono, title: titulo, text: texto, confirmButtonColor: '#c68644' });
        } else {
            alert(texto);
        }
    }

    async function cargarInventarioDesdeAPI() {
        try {
            const response = await fetch('../Productos/api.php'); 
            const data = await response.json();
            if (data.success && data.productos) {
                data.productos.forEach(prod => { inventarioGlobal[prod.idp] = parseInt(prod.STOCK); });
            }
        } catch (error) { console.error("Error inventario:", error); }
    }

    function cargarSweetAlert() {
        return new Promise((resolve) => {
            if (typeof Swal !== 'undefined') resolve();
            else {
                const script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
                script.onload = resolve;
                script.onerror = resolve;
                document.head.appendChild(script);
            }
        });
    }

    // --- STRIPE ---
    function inicializarStripe() {
        try {
            stripe = Stripe(stripePublicKey);
            const elements = stripe.elements();
            const style = { base: { color: '#332a23', fontFamily: '"Poppins", sans-serif', fontSize: '16px' } };
            cardElement = elements.create('card', { style: style });
            cardElement.mount('#card-element');
            cardElement.on('change', (event) => { cardErrorsEl.textContent = event.error ? event.error.message : ''; });
        } catch (error) { console.error("Stripe error:", error); }
    }
    
    function mostrarModalPago() { if(paymentModalOverlay) { updateSummary(); paymentModalOverlay.classList.remove('payment-modal-hidden'); } }
    function ocultarModalPago() { if(paymentModalOverlay) { paymentModalOverlay.classList.add('payment-modal-hidden'); if(cardElement) cardElement.clear(); } }
    
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

    // --- INICIO ---
    async function init() {
        await cargarSweetAlert(); 

        const placeholder = document.getElementById('carrito-placeholder');
        if (!placeholder) return;

        try {
            const cacheBuster = "?v=" + new Date().getTime();
            const response = await fetch('../Carrito/carrito.html' + cacheBuster);
            const html = await response.text();
            placeholder.innerHTML = html;
            
            cartElement = document.querySelector('#carrito-placeholder .cart');
            cartContent = document.querySelector('#carrito-placeholder .cart-content');
            summarySubtotal = document.querySelector('#carrito-placeholder .summary-subtotal');
            summaryIVA = document.querySelector('#carrito-placeholder .summary-iva');
            summaryTotal = document.querySelector('#carrito-placeholder .summary-total');
            payButton = document.querySelector('#carrito-placeholder .pay');
            
            // REFERENCIA AL NUEVO INPUT
            orderInputEl = document.querySelector('#carrito-placeholder .order-input');
            
            clearCartButton = document.querySelector('#carrito-placeholder .cart-clear-all');
            payEfectivoButton = document.querySelector('#carrito-placeholder .btn-efectivo');
            payTarjetaButton = document.querySelector('#carrito-placeholder .btn-tarjeta');
            cancelPayButton = document.querySelector('#carrito-placeholder .cart-cancel-payment');
            searchIcon = document.querySelector('#carrito-placeholder .order-search-icon');
            statusPill = document.querySelector('#carrito-placeholder .cart-status-pill');
            
            paymentModalOverlay = document.querySelector('#carrito-placeholder #payment-modal-overlay');
            paymentModalTotalEl = document.querySelector('#carrito-placeholder #payment-modal-total');
            submitPaymentBtn = document.querySelector('#carrito-placeholder #submit-payment-btn');
            cancelPaymentModalBtn = document.querySelector('#carrito-placeholder #cancel-payment-modal-btn');
            cardErrorsEl = document.querySelector('#carrito-placeholder #card-errors');
            
            cartList = leerCarrito();
            renderCart();
            
            // Inicializar el input con número guardado o nuevo
            if(orderInputEl) orderInputEl.value = getStoredOrderNumber();
            
            await cargarInventarioDesdeAPI();

            if (submitPaymentBtn && typeof Stripe === 'function') inicializarStripe();

            // EVENTOS DE BÚSQUEDA
            if (searchIcon) searchIcon.addEventListener('click', buscarPedido);
            
            // Escuchar tecla ENTER en el input
            if (orderInputEl) {
                orderInputEl.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault(); // Evitar submit de forms si los hubiera
                        buscarPedido();
                    }
                });
            }
            
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
                    alerta("info", "Carrito vacío", "Agrega productos antes de continuar.");
                    return; 
                }
                setPaymentMode(true); 
            });
            
            if (payEfectivoButton) payEfectivoButton.addEventListener('click', () => procesarPago('Efectivo'));
            if (payTarjetaButton) payTarjetaButton.addEventListener('click', mostrarModalPago);
            if (cancelPayButton) cancelPayButton.addEventListener('click', (e) => { e.preventDefault(); setPaymentMode(false); });
            if (cancelPaymentModalBtn) cancelPaymentModalBtn.addEventListener('click', (e) => { e.preventDefault(); ocultarModalPago(); });
            if (submitPaymentBtn) submitPaymentBtn.addEventListener('click', manejarSubmitPago);
            
            if (clearCartButton) clearCartButton.addEventListener('click', (e) => {
                e.preventDefault();
                if (cartList.length > 0 || isOrderLoaded) {
                     if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: '¿Limpiar todo?',
                            text: "Se eliminarán los productos actuales.",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#c68644',
                            cancelButtonColor: '#777',
                            confirmButtonText: 'Sí, limpiar'
                        }).then((result) => { if (result.isConfirmed) vaciarCarrito(); });
                    } else {
                        if (confirm("¿Limpiar carrito?")) vaciarCarrito();
                    }
                }
            });

        } catch (error) {
            console.error('Error init carrito:', error);
        }
    }

    window.CarritoAPI = {
        agregar: agregarProducto,
        vaciar: vaciarCarrito
    };

    document.addEventListener('DOMContentLoaded', init);
})();