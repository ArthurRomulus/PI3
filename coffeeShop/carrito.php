<?php
// carrito.php
session_start();
require_once __DIR__ . '/db.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Carrito • Coffee-Shop</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="Style.css">
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
  </style>
</head>
<body>
  <div class="cart-wrap">
    <div class="cart-top">
      <h1>Tu carrito <span id="cart-count" class="badge">0</span></h1>
      <button id="btn-clear" class="btn">Vaciar</button>
    </div>

    <table class="cart-table">
      <thead>
        <tr><th>Producto</th><th>Precio</th><th>Cantidad</th><th class="right">Subtotal</th></tr>
      </thead>
      <tbody id="cart-body"><tr><td colspan="4">Cargando…</td></tr></tbody>
      <tfoot>
        <tr><th colspan="3" class="right">Total:</th><th class="right" id="cart-total">$0.00 MXN</th></tr>
      </tfoot>
    </table>

    <div style="margin-top:16px; text-align:right;">
      <a href="index.php" class="btn">Seguir comprando</a>
      <button class="btn btn-dark">Pagar</button>
    </div>
  </div>

<script>
async function j(url,opt){const r=await fetch(url,opt);return r.json();}
async function loadCart(){
  const d=await j('cart_api.php?action=list'); const body=document.getElementById('cart-body');
  const c=document.getElementById('cart-count'); const t=document.getElementById('cart-total');
  if(!d.ok){body.innerHTML=`<tr><td colspan="4">Error</td></tr>`;return;}
  c.textContent=d.items.reduce((a,b)=>a+b.qty,0);
  if(!d.items.length){body.innerHTML=`<tr><td colspan="4">Tu carrito está vacío.</td></tr>`;t.textContent='$0.00 MXN';return;}
  body.innerHTML=d.items.map(it=>`
    <tr data-id="${it.id}">
      <td><div style="display:flex;gap:10px;align-items:center">
        <img src="${it.foto||'assest/placeholder.png'}" style="width:48px;height:48px;object-fit:cover;border-radius:8px">
        <strong>${it.nombre}</strong></div></td>
      <td>$${Number(it.precio).toFixed(2)} MXN</td>
      <td><input class="qty" type="number" min="0" value="${it.qty}"></td>
      <td class="right">$${Number(it.subtotal).toFixed(2)} MXN</td>
    </tr>`).join('');
  t.textContent=`$${Number(d.total).toFixed(2)} MXN`;
}
document.addEventListener('input', async e=>{
  if(!e.target.classList.contains('qty'))return;
  const tr=e.target.closest('tr'); const id=tr.dataset.id; const qty=parseInt(e.target.value||'0',10);
  const r=await j('cart_api.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({action:'update',id,qty})});
  if(r.ok) loadCart();
});
document.getElementById('btn-clear').addEventListener('click',async()=>{const r=await j('cart_api.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({action:'clear'})}); if(r.ok) loadCart();});
loadCart();
</script>
</body>
</html>
