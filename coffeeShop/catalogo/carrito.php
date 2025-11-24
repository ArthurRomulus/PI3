<?php
// carrito.php
session_start();
include "../../conexion.php"; // Asegúrate que este incluye conecta bien
$usuarioLogueado = !empty($_SESSION['logueado']) && $_SESSION['logueado'] === true;
include "../nav_bar.php";

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Carrito • Coffee-Shop</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="../inicio/Style.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script src="https://js.stripe.com/v3/"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    .cart-wrap{max-width:900px;margin:24px auto;padding:0 12px}
    .cart-table{width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 6px 16px rgba(0,0,0,.08)}
    .cart-table th,.cart-table td{padding:12px;border-bottom:1px solid #eee;text-align:left}
    .cart-table th{background:#f8f4f2}
    .qty{width:64px}
    .right{text-align:right}
    .cart-top{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px}
    .btn{padding:8px 12px;border:none;border-radius:8px;font-weight:700;cursor:pointer}
    .btn-dark{background:#6a2b16;color:#fff}
    .badge{background:#ffede0;padding:2px 8px;border-radius:999px;margin-left:6px}

    /* Estilos del Modal de Stripe (Adaptado para que no rompa tu diseño) */
    #payment-modal-overlay {
        display: none; /* Oculto por defecto */
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.5); z-index: 9999;
        justify-content: center; align-items: center;
    }
    #payment-modal {
        background: #fff; padding: 24px; border-radius: 12px; width: 90%; max-width: 400px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }
    #card-element {
        padding: 12px; border: 1px solid #ddd; border-radius: 6px; margin: 15px 0;
    }
    #card-errors { color: #dc3545; font-size: 14px; margin-top: 8px; }
    .modal-btns { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; }
  </style>
</head>
<body>
  <div class="cart-wrap">
    <div class="cart-top">
  <h1>
    <span data-translate="Tu carrito">Tu carrito</span>
    <span id="cart-count" class="badge">0</span>
  </h1>
  <button id="btn-clear" class="btn" data-translate="Vaciar">Vaciar</button>
</div>

    <table class="cart-table">
      <thead>
        <tr><th data-translate="Producto">Producto</th><th data-translate="Precio">Precio</th><th data-translate="Cantidad">Cantidad</th><th class="right" data-translate="Subtotal">Subtotal</th></tr>
      </thead>
      <tbody id="cart-body"><tr><td colspan="4">Cargando…</td></tr></tbody>
      <tfoot>
        <tr><th colspan="3" class="right">Total:</th><th class="right" id="cart-total">$0.00 MXN</th></tr>
      </tfoot>
    </table>

    <div style="margin-top:16px; text-align:right;">
      <a href="../catalogo/catalogo.php" class="btn" data-translate="Seguir comprando">Seguir comprando</a>
      <button id="btn-pagar" class="btn btn-dark" data-translate="Pagar">Pagar</button>
    </div>
  </div>

  <div id="payment-modal-overlay">
      <div id="payment-modal">
          <h2 style="margin-top:0; font-size: 1.2rem;" data-translate="Pago con Tarjeta">Pago con Tarjeta</h2>
          <p>
            <span data-translate="Total a pagar:">Total a pagar:</span>
            <strong id="modal-total-amount">$0.00</strong>
        </p>

          
          <div id="card-element"></div>
          <div id="card-errors" role="alert"></div>

          <div class="modal-btns">
              <button id="btn-cancel-modal" class="btn" data-translate="Cancelar">Cancelar</button>
              <button id="btn-confirm-payment" class="btn btn-dark" data-translate="Confirmar Pago">Confirmar Pago</button>
          </div>
      </div>
  </div>
<style>
    /* --- Footer Principal (de index.php) --- */
body.dark-mode .site-footer {
    background-color: #1a1a1a;
    color: #b0b0b0;
    border-top: 1px solid #333;
}

body.dark-mode .footer-logo a,
body.dark-mode .footer-links a {
    color: #e0e0e0;
}

body.dark-mode .footer-links a:hover {
    color: #fff;
}

body.dark-mode .footer-bottom p {
    color: #888;
}

body.dark-mode .footer-menu a {
    color: #ffffff;
}

body.dark-mode .footer-logo {
    color: #ffffff;
}

body.dark-mode .cs-bottom .cs-line {
  background: #ffffff; /* Un marrón claro/visible */
}
body.dark-mode .site-footer::after{
    background: #ffffff;
}

/* Modo oscuro para botón "Pagar" en carrito */
body.dark-mode #btn-pagar {
    background-color: #888; /* color claro que resalte sobre fondo oscuro */
    color: #252525;            /* texto oscuro */
    border: 1px solid #b8a081; /* borde sutil */
    transition: background 0.3s, color 0.3s;
}

body.dark-mode #btn-pagar:hover {
    background-color: #e0d5d2; /* aclarar al pasar el mouse */
    color: #252525;
}

</style>
<script>
// ==========================================
// 1. FUNCIÓN HELPER "J" CON SWEETALERT
// ==========================================
async function j(url, opt) {
    try {
        const r = await fetch(url, opt);
        const text = await r.text();
        let json;
        try { json = JSON.parse(text); } catch (err) { return { ok: false, error: "Error del servidor" }; }

        // --- AQUÍ SE DETECTA EL ERROR DE STOCK Y SE PONE BONITO ---
        if (json.ok === false) {
            const msg = json.error || "";
            
            // Detectar si el error es de Stock
            if (msg.toLowerCase().includes('stock') || msg.toLowerCase().includes('disponibles')) {
                // Sacar el número del mensaje (ej: "10")
                const num = msg.match(/(\d+)/);
                const stock = num ? num[0] : "";

                Swal.fire({
                    icon: 'warning',
                    title: '¡Stock Limitado!',
                    html: `<div>No podemos agregar esa cantidad.</div>
                           <div style="font-size:1.2em; margin-top:5px;">
                               Solo quedan <b style="color:#d9534f; font-size:1.4em;">${stock}</b> unidades.
                           </div>`,
                    confirmButtonColor: '#6a2b16',
                    confirmButtonText: 'Entendido',
                    background: '#fffbf7'
                });
            } else {
                // Otros errores
                Swal.fire({ icon: 'error', title: 'Oops...', text: msg, confirmButtonColor: '#6a2b16' });
            }
            return { ok: false };
        }
        return json;
    } catch (e) { return { ok: false }; }
}

// ==========================================
// 2. LÓGICA DE CARGA DEL CARRITO
// ==========================================
async function loadCart(){
  const d = await j('cart_api.php?action=list'); 
  const body = document.getElementById('cart-body');
  const c = document.getElementById('cart-count'); 
  const t = document.getElementById('cart-total');
  
  window.cartTotalValue = d.total || 0; 
  window.cartHasItems = d.items && d.items.length > 0;

  if(!d.ok){ body.innerHTML=`<tr><td colspan="4">Error al cargar</td></tr>`; return; }
  
  c.textContent = d.items ? d.items.reduce((a,b)=>a+b.qty,0) : 0;
  
  if(!d.items || !d.items.length){
      body.innerHTML=`<tr><td colspan="4" style="text-align:center; padding:20px;" data-translate="Tu carrito está vacío ☕">Tu carrito está vacío ☕</td></tr>`;
      t.textContent='$0.00 MXN';
      return;
  }
  
  body.innerHTML=d.items.map(it=>`
    <tr data-id="${it.id}">
      <td>
          <div style="display:flex;gap:10px;align-items:center">
             <img src="${it.foto||'assest/placeholder.png'}" style="width:48px;height:48px;object-fit:cover;border-radius:8px">
             <div><strong>${it.nombre}</strong></div>
          </div>
      </td>
      <td>$${Number(it.precio).toFixed(2)} MXN</td>
      <td><input class="qty" type="number" min="1" value="${it.qty}" style="width:60px; padding:5px;"></td>
      <td class="right">$${Number(it.subtotal).toFixed(2)} MXN</td>
    </tr>`).join('');
  t.textContent=`$${Number(d.total).toFixed(2)} MXN`;
}

document.addEventListener('change', async e => {
  if(!e.target.classList.contains('qty')) return;
  const tr = e.target.closest('tr'); 
  const id = tr.dataset.id; 
  const qty = parseInt(e.target.value||'0', 10);
  
  const r = await j('cart_api.php',{
      method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({action:'update',id,qty})
  });
  // Siempre recargamos para corregir el número visual si hubo error
  loadCart(); 
});

document.getElementById('btn-clear').addEventListener('click', async() => {
    const r = await j('cart_api.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({action:'clear'})}); 
    if(r.ok) loadCart();
});

loadCart();

// ==========================================
// 3. LÓGICA DE PAGO (STRIPE)
// ==========================================
(function() {
    const stripe = Stripe('pk_test_51SSiVI6xsnAsFl7HGfd0lPd7bm5TLSTDuZS4MdGMHLkIXFz2O0SfJMe1V7SgzObmSWdXN0PinoRnCKfVuGrFYSgi003W0zORcA'); 
    const elements = stripe.elements();
    const card = elements.create('card', { style: { base: { fontSize: '16px', color: '#32325d' } } });
    card.mount('#card-element');

    const modalOverlay = document.getElementById('payment-modal-overlay');
    const btnPagar = document.getElementById('btn-pagar');
    const btnCancel = document.getElementById('btn-cancel-modal');
    const btnConfirm = document.getElementById('btn-confirm-payment');
    const errorDiv = document.getElementById('card-errors');

    btnPagar.addEventListener('click', () => {
        if (!window.cartHasItems) {
            Swal.fire({ icon: 'info', title: 'Carrito vacío', confirmButtonColor: '#6a2b16' });
            return;
        }
        document.getElementById('modal-total-amount').textContent = `$${window.cartTotalValue.toFixed(2)} MXN`;
        modalOverlay.style.display = 'flex';
    });

    btnCancel.addEventListener('click', () => {
        modalOverlay.style.display = 'none';
        card.clear();
        errorDiv.textContent = '';
    });

    btnConfirm.addEventListener('click', async () => {
        btnConfirm.disabled = true;
        btnConfirm.textContent = 'Procesando...';
        const {token, error} = await stripe.createToken(card);

        if (error) {
            errorDiv.textContent = error.message;
            btnConfirm.disabled = false;
            btnConfirm.textContent = 'Confirmar Pago';
        } else {
            procesarPedidoServidor(token.id);
        }
    });

    async function procesarPedidoServidor(tokenStripe) {
        try {
            const response = await fetch('registrar_pedido_cliente.php', {
                method: 'POST', headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ token: tokenStripe })
            });
            const data = await response.json();

            if (data.success) {
                modalOverlay.style.display = 'none';
                
                // --- ALERTA DE PAGO EXITOSO CON DISEÑO ---
                Swal.fire({
                    icon: 'success',
                    title: '¡Pago exitoso!',
                    html: `Tu pedido es el <b>#${data.nuevoPedidoId}</b>`,
                    confirmButtonColor: '#6a2b16',
                    allowOutsideClick: false
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        await j('cart_api.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({action:'clear'})});
                        loadCart(); 
                        card.clear();
                    }
                });
            } else {
                errorDiv.textContent = "Error: " + (data.error || "Desconocido");
                btnConfirm.disabled = false;
                btnConfirm.textContent = 'Confirmar Pago';
            }
        } catch (err) {
            console.error(err);
            errorDiv.textContent = "Error de conexión.";
            btnConfirm.disabled = false;
            btnConfirm.textContent = 'Confirmar Pago';
        }
    }
})();
</script>
<script src="../../translate.js"></script>
</body>
</html>