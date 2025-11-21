/*
  CARRITO MODULAR DE BLACKWOOD COFFEE
  (Con Validación de Stock, SweetAlert2 en Todo y Rutas Corregidas)
*/
(function() {
    // --- 1. VARIABLES GLOBALES ---
    let cartList = [];
    let totalGeneral = 0;
    let isOrderLoaded = false; 
    const IVA_RATE = 0.16;
    
    // MAPA DE INVENTARIO: { id_producto: cantidad_stock }
    let inventarioGlobal = {}; 

    // --- Variables de Stripe ---
    const stripePublicKey = 'pk_test_51SSiVI6xsnAsFl7HGfd0lPd7bm5TLSTDuZS4MdGMHLkIXFz2O0SfJMe1V7SgzObmSWdXN0PinoRnCKfVuGrFYSgi003W0zORcA';
    let stripe, cardElement, paymentModalOverlay, paymentModalTotalEl, submitPaymentBtn, cancelPaymentModalBtn, cardErrorsEl;
    
    // Selectores UI
    let cartElement, cartContent, summarySubtotal, summaryIVA, summaryTotal, payButton, orderNumberEl, clearCartButton, searchIcon, statusPill;
    let payEfectivoButton, payTarjetaButton, cancelPayButton;

    // --- 2. LÓGICA DE ALMACENAMIENTO ---
    function guardarCarrito() {
        if (!isOrderLoaded) localStorage.setItem('blackwoodCart', JSON.stringify(cartList));
    }

    function leerCarrito() {
        const cartGuardado = localStorage.getItem('blackwoodCart');
        return cartGuardado ? JSON.parse(cartGuardado) : [];
    }
    
    function getOrderNumber() {
        let num = localStorage.getItem('blackwoodOrderNum');
        if (!num) {
            num = Math.floor(Math.random() * (999 - 100 + 1)) + 100;
            localStorage.setItem('blackwoodOrderNum', num);
        }
        return num;
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
        let estadoClass = '', estadoTexto = '', pagoTexto = '';
        
        if (pedido.estado.toLowerCase() === 'terminado') { estadoClass = 'status-terminado'; estadoTexto = 'Terminado'; } 
        else { estadoClass = 'status-proceso'; estadoTexto = 'Proceso'; }
        
        if (pedido.metodo_pago === 'Tarjeta') { estadoClass = 'status-pagado'; pagoTexto = 'Pago con Tarjeta'; } 
        else { pagoTexto = 'Pagar en Efectivo'; }
        
        if (pedido.estado.toLowerCase() === 'terminado') { pagoTexto = pedido.metodo_pago; estadoClass = 'status-terminado'; }

        statusPill.className = 'cart-status-pill';
        statusPill.classList.add(estadoClass);
        statusPill.innerHTML = `<span class="status-label">${estadoTexto}</span> | <span class="payment-label">${pagoTexto}</span>`;
        statusPill.style.display = 'block';
    }

    // --- 4. FUNCIONES DE MANIPULACIÓN ---
    
    function agregarProducto(producto) {
        if (isOrderLoaded) vaciarCarrito();
        
        let stockMaximo = 9999; 
        if (inventarioGlobal[producto.idp] !== undefined) {
            stockMaximo = inventarioGlobal[producto.idp];
        }

        const existingProduct = cartList.find(item => item.cartKey === producto.cartKey);
        let cantidadEnCarrito = existingProduct ? existingProduct.qty : 0;
        
        // VALIDACIÓN CON SWEETALERT
        if ((cantidadEnCarrito + producto.qty) > stockMaximo) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: `No puedes agregar más. Solo hay ${stockMaximo} unidades en stock.`,
                    confirmButtonColor: '#c68644'
                });
            } else {
                alert(`Stock Insuficiente: Solo quedan ${stockMaximo}`);
            }
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
        const idx = parseInt(index);
        const item = cartList[idx];

        if (accion === 'plus') {
            let stockMaximo = 9999;
            if (inventarioGlobal[item.idp] !== undefined) {
                stockMaximo = inventarioGlobal[item.idp];
            }

            if ((item.qty + 1) > stockMaximo) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: `Has alcanzado el límite. Solo quedan ${stockMaximo} en stock.`,
                        confirmButtonColor: '#c68644'
                    });
                } else {
                    alert(`Límite alcanzado. Stock: ${stockMaximo}`);
                }
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
        localStorage.removeItem('blackwoodOrderNum');
        if (orderNumberEl) orderNumberEl.textContent = getOrderNumber();
        if (statusPill) statusPill.style.display = 'none';
        if (cartElement) cartElement.classList.remove('order-loaded');
        setPaymentMode(false);
    }
    
    function setPaymentMode(isChoosing) {
        if (isChoosing) cartElement.classList.add('payment-active');
        else cartElement.classList.remove('payment-active');
    }
    
    function procesarPago(metodoPago, tokenStripe = null) {
        if (cartList.length === 0) { 
            if(typeof Swal !== 'undefined') Swal.fire("Carrito Vacío", "Agrega productos antes de pagar.", "info");
            else alert("El carrito está vacío."); 
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
            } else {
                if(typeof Swal !== 'undefined') Swal.fire({ icon: "error", title: "Error", text: data.error });
                else alert("Error: " + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if(typeof Swal !== 'undefined') Swal.fire({ icon: "error", title: "Error", text: "Error de conexión." });
            else alert("Error de conexión.");
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
        const pedidoId = prompt("Número de pedido:");
        if (!pedidoId || pedidoId.trim() === '') return;

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
                if(typeof Swal !== 'undefined') Swal.fire({ icon: "error", title: "No encontrado", text: data.error });
                else alert("Error: " + data.error);
            }
        })
        .catch(error => { 
            console.error(error); 
            if(typeof Swal !== 'undefined') Swal.fire({ icon: "error", title: "Error", text: "Error de conexión." });
            else alert("Error de conexión.");
        });
    }

    async function cargarInventarioDesdeAPI() {
        try {
            const response = await fetch('../Productos/api.php'); 
            const data = await response.json();
            
            if (data.success && data.productos) {
                data.productos.forEach(prod => {
                    inventarioGlobal[prod.idp] = parseInt(prod.STOCK);
                });
                console.log("Inventario cargado:", inventarioGlobal);
            }
        } catch (error) {
            console.error("No se pudo cargar el inventario:", error);
        }
    }

    function cargarSweetAlert() {
        return new Promise((resolve, reject) => {
            if (typeof Swal !== 'undefined') {
                resolve();
            } else {
                const script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
                script.onload = resolve;
                script.onerror = () => {
                    console.error("No se pudo cargar SweetAlert2.");
                    resolve(); 
                };
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
            cardElement.on('change', (event) => {
                cardErrorsEl.textContent = event.error ? event.error.message : '';
            });
        } catch (error) {
            console.error("Stripe error:", error);
            if(payTarjetaButton) { payTarjetaButton.disabled = true; payTarjetaButton.textContent = "Error Stripe"; }
        }
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
        await cargarSweetAlert(); // Asegurar que cargue

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
            orderNumberEl = document.querySelector('#carrito-placeholder .order-number');
            clearCartButton = document.querySelector('#carrito-placeholder .cart-clear-all');
            paymentChoiceDiv = document.querySelector('#carrito-placeholder .payment-choice');
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
            if(orderNumberEl) orderNumberEl.textContent = getOrderNumber();
            
            await cargarInventarioDesdeAPI();

            if (submitPaymentBtn && typeof Stripe === 'function') inicializarStripe();

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
                    if(typeof Swal !== 'undefined') Swal.fire("Carrito vacío", "", "info");
                    else alert("Carrito vacío");
                    return; 
                }
                setPaymentMode(true); 
            });
            
            if (payEfectivoButton) payEfectivoButton.addEventListener('click', () => procesarPago('Efectivo'));
            if (payTarjetaButton) payTarjetaButton.addEventListener('click', mostrarModalPago);
            if (cancelPayButton) cancelPayButton.addEventListener('click', (e) => { e.preventDefault(); setPaymentMode(false); });
            if (cancelPaymentModalBtn) cancelPaymentModalBtn.addEventListener('click', (e) => { e.preventDefault(); ocultarModalPago(); });
            if (submitPaymentBtn) submitPaymentBtn.addEventListener('click', manejarSubmitPago);
            
            // --- AQUÍ ESTÁ LA MODIFICACIÓN PARA "ELIMINAR TODO" CON ANIMACIÓN ---
            if (clearCartButton) clearCartButton.addEventListener('click', (e) => {
                e.preventDefault();
                // Verificamos si el carrito tiene algo
                if (cartList.length > 0 || isOrderLoaded) {
                    // Usamos SweetAlert en lugar de confirm()
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: '¿Estás seguro?',
                            text: "Se eliminarán todos los productos del carrito.",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#c68644', // Tu color de marca
                            cancelButtonColor: '#777',
                            confirmButtonText: 'Sí, limpiar',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                vaciarCarrito();
                                Swal.fire({
                                    title: "¡Limpio!",
                                    text: "El carrito ha sido vaciado.",
                                    icon: "success",
                                    confirmButtonColor: '#c68644',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            }
                        });
                    } else {
                        // Fallback por si acaso
                        if (confirm("¿Estás seguro de que quieres limpiar la pantalla?")) {
                            vaciarCarrito();
                        }
                    }
                }
            });

        } catch (error) {
            console.error('Error init carrito:', error);
            placeholder.innerHTML = "<p>Error cargando carrito.</p>";
        }
    }

    window.CarritoAPI = {
        agregar: agregarProducto,
        vaciar: vaciarCarrito
    };

    document.addEventListener('DOMContentLoaded', init);
})();