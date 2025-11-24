(function () {
  "use strict";

  const $ = (s, r = document) => r.querySelector(s);

  // ============================
  // 0) CONFIG GLOBAL
  // ============================
  const IMG_BASE = "../../Images/";
  const IMG_PLACEHOLDER = IMG_BASE + "placeholder.png";
  const CART_API = window.CART_API_URL || "cart_api.php";

  function resolveImgPath(raw) {
    let s = (raw || "").toString().trim();
    if (!s) return IMG_PLACEHOLDER;
    if (/^https?:\/\//i.test(s)) return s;
    s = s.replace(/\\/g, "/").replace(/^\.\/+/, "").replace(/^(\.\.\/)+/, "").replace(/^assest\//i, "").replace(/^assets?\//i, "").replace(/^Images?\//i, "");
    return encodeURI(IMG_BASE + s);
  }

  function escapeHtml(text = "") {
    return text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
  }

  // ============================
  // 1) Filtro
  // ============================
  function initFiltro() {
    const btn = $("#btn-filtrar");
    const menu = $("#menu-filtrar");
    const wrap = $("#filtro-wrap");
    if (!btn || !menu || !wrap) return;
    function toggleMenu() { menu.style.display = menu.style.display === "block" ? "none" : "block"; }
    function closeMenu() { menu.style.display = "none"; }
    btn.addEventListener("click", (e) => { e.stopPropagation(); toggleMenu(); });
    document.addEventListener("click", (e) => { if (!wrap.contains(e.target)) closeMenu(); });
    document.addEventListener("keydown", (e) => { if (e.key === "Escape") closeMenu(); });
  }

  // ============================
  // 2) Carga AJAX
  // ============================
  function initProductosAJAX() {
    const grid = document.querySelector('.hotdrinks__grid[data-autoload="ajax"]');
    if (!grid) return;
    const categoriaAttr = grid.getAttribute("data-categoria") || "";

    function renderCard(p) {
  const sku = (p.idp ?? "").toString();
  const disponible = Number(p.STOCK ?? 0) > 0;
  const img = resolveImgPath(p.ruta_imagen);
  const precioTotal = Number(p.precio_total ?? p.precio_base ?? 0).toFixed(2);
  
  // ✅ AGREGADO: Variables que faltaban
  const nombreProducto = escapeHtml(p.namep ?? "Producto");
  const descripcion = escapeHtml(p.descripcion ?? "");
  const categorias = Array.isArray(p.categorias) 
    ? p.categorias.map(c => c.nombre || c).join(', ') 
    : (p.categorias ?? "Sin categoría");
  const stockText = disponible ? "Disponible" : "Agotado";
  
  // ✅ AGREGADO: Serializar listboxes correctamente
  const listboxesJSON = JSON.stringify(p.listboxes || [])
    .replace(/'/g, '&apos;')
    .replace(/"/g, '&quot;');

  return `
    <article class="ts-card" 
         data-id="${sku}" 
         data-name="${nombreProducto}" 
         data-price="${precioTotal}" 
         data-foto="${img}" 
         data-listboxes='${listboxesJSON}'>
  
      <div class="ts-stage">
        <img src="${img}" 
             alt="${nombreProducto}" 
             onerror="this.onerror=null;this.src='${IMG_PLACEHOLDER}';" />
      </div>

      <h4 class="ts-name" data-translate="${nombreProducto}">
        ${nombreProducto} <small style="opacity:.7">• SKU ${sku || "—"}</small>
      </h4>

      <p class="ts-desc" data-translate="${descripcion}">
        ${descripcion}
      </p>

      <p class="ts-categoria" data-translate="${categorias}">
        ${categorias}
      </p>

      <p class="ts-stock">
        <strong data-translate="Stock">Stock:</strong> ${p.STOCK ?? 0}
      </p>

      <div class="ts-info">
        <span data-translate="${stockText}">${stockText}</span>
        <span class="ts-price">
          <span data-translate="Precio">Precio:</span> $${precioTotal} MXN
        </span>
        <button class="ts-cart" ${disponible ? "" : "disabled"}>🛒</button>
      </div>
    </article>`;
}

    async function cargarCategoria(cat) {
      const u = new URL("./get_productos.php", location.href);
      if (cat) u.searchParams.set("categoria", cat);
      try {
        const res = await fetch(u.toString(), { cache: "no-store" });
        const data = await res.json();
        if (data && data.ok === true && Array.isArray(data.items)) {
          grid.innerHTML = data.items.map(renderCard).join("");
        } else {
          grid.innerHTML = '<p style="grid-column:1/-1;text-align:center;padding:16px;">No hay productos.</p>';
        }
      } catch (e) { console.error(e); }
    }
    cargarCategoria(categoriaAttr);
  }

  // ============================================
  // 3) Mini-carrito
  // ============================================
  (function () {
    function initMiniCart() {
      const openBtn = $("#open-cart") || $("#navCartBtn");
      const overlay = $("#mc-overlay") || $("#mcOverlay");
      const drawer = $("#mini-cart") || $("#miniCart");
      const closeBtn = $("#mc-close") || $("#mcClose");
      const list = $("#mc-list") || $("#mcList");
      const emptyMsg = (list ? list.querySelector(".mc__empty") : null) || $("#mcEmpty");
      const totalEl = $("#mc-total") || $("#mcTotal");

      if (!overlay || !drawer || !list || !totalEl) return;

      const fmt = (n) => Number(n || 0).toLocaleString("es-MX", { style: "currency", currency: "MXN" });

      // --- HELPER J MEJORADO: Captura errores de PHP y Stock ---
// --- HELPER J MEJORADO CON DETECCIÓN DE STOCK ---
 // --- HELPER J MEJORADO: DISEÑO PERSONALIZADO PARA STOCK ---
// --- HELPER J (ACTUALIZADO PARA LOS 2 MENSAJES DE STOCK) ---
      async function j(url, opt) {
        try {
          const r = await fetch(url, opt);
          const text = await r.text();
          let json;
          
          try {
              json = JSON.parse(text);
          } catch (err) {
              console.error("Error PHP:", text);
              if (typeof Swal !== 'undefined') {
                  Swal.fire({ icon: 'error', title: 'Error', text: 'Error inesperado del servidor.' });
              } else {
                  alert("Error del servidor.");
              }
              return { ok: false };
          }

          // Si el backend dice ok: false
          if (json.ok === false) {
              if (typeof Swal !== 'undefined') {
                  const msg = json.error || "";
                  let stockEncontrado = null;

                  // 1. Detectar el primer tipo de mensaje: "Límite de stock alcanzado (10)."
                  // La expresión regular busca: texto "Límite...", paréntesis, un número (\d+), paréntesis.
                  const match1 = msg.match(/Límite de stock alcanzado \((\d+)\)/);

                  // 2. Detectar el segundo tipo de mensaje: "No puedes agregar más. Solo hay 10 disponibles."
                  // La expresión regular busca: texto "Solo hay", un número (\d+), texto "disponibles".
                  const match2 = msg.match(/Solo hay (\d+) disponibles/);

                  // Si hubo coincidencia en alguno de los dos, guardamos el número
                  if (match1) {
                      stockEncontrado = match1[1];
                  } else if (match2) {
                      stockEncontrado = match2[1];
                  }

                  if (stockEncontrado !== null) {
                      // --- ESTILO PERSONALIZADO PARA STOCK ---
                      Swal.fire({
                          icon: 'warning',
                          title: '¡Ups! Stock limitado',
                          html: `
                              <div style="margin-bottom: 10px;">Ya no podemos agregar esa cantidad.</div>
                              <div style="font-size: 1.1rem; color: #555;">
                                  Solo quedan <b style="color: #d9534f; font-size: 1.5rem;">${stockEncontrado}</b> unidades.
                              </div>
                          `,
                          confirmButtonText: 'Entendido',
                          confirmButtonColor: '#6a2b16',
                          background: '#fff'
                      });
                  } else {
                      // --- CUALQUIER OTRO ERROR ---
                      Swal.fire({ 
                          icon: 'error', 
                          title: 'Error', 
                          text: msg, 
                          confirmButtonColor: '#6a2b16' 
                      });
                  }
              } else {
                  alert(json.error);
              }
              return { ok: false };
          }
          return json;
        } catch (e) {
          console.error("Error de red:", e);
          return { ok: false };
        }
      }

      function openCart() { drawer.classList.add("is-open"); overlay.hidden = false; }
      function closeCart() { drawer.classList.remove("is-open"); overlay.hidden = true; }

      async function refreshBadge() {
        const badge = document.querySelector("#nav-cart-count") || document.querySelector("#open-cart span");
        if (!badge) return;
        const d = await j(CART_API + "?action=count");
        if (d.ok) badge.textContent = d.count;
      }

      async function loadMiniCart() {
  const d = await j(CART_API + "?action=list");
  if (!d || !d.ok) return; 

  if (!d.items.length) {
    list.innerHTML = "";
    if (emptyMsg) emptyMsg.style.display = "block";
    totalEl.textContent = fmt(0);
    await refreshBadge();
    return;
  }

  if (emptyMsg) emptyMsg.style.display = "none";
  
  // ✅ AGREGADO: Generar HTML con data-translate
  list.innerHTML = d.items.map(p => `
    <li class="mc-item" data-id="${p.id}">
      <img class="mc-img" 
           src="${p.foto || IMG_PLACEHOLDER}" 
           onerror="this.onerror=null;this.src='${IMG_PLACEHOLDER}';">
      <div>
        <!-- ✅ Nombre del producto traducible -->
        <p class="mc-name" data-translate="${escapeHtml(p.nombre || 'Producto')}">
          ${p.nombre || "Producto"}
        </p>
        
        <div class="mc-meta">${fmt(p.precio)} c/u</div>
        
        <div class="mc-qty">
          <button type="button" class="qty-minus">−</button>
          <input type="text" value="${p.qty}" readonly>
          <button type="button" class="qty-plus">+</button>
        </div>
      </div>
      <div style="display:flex;flex-direction:column;align-items:end;gap:8px;">
        <div class="mc-price">${fmt(p.subtotal)}</div>
        <button type="button" class="mc-close mc-del">🗑</button>
      </div>
    </li>
  `).join("");

  totalEl.textContent = fmt(d.total);

  // Eventos de botones (igual)
  list.querySelectorAll(".mc-item").forEach((li) => {
    const id = li.dataset.id;
    li.querySelector(".qty-minus")?.addEventListener("click", () => updateQty(id, -1));
    li.querySelector(".qty-plus")?.addEventListener("click", () => updateQty(id, +1));
    li.querySelector(".mc-del")?.addEventListener("click", () => setQty(id, 0));
  });
  
  await refreshBadge();

  // ✅ AGREGADO: Aplicar traducciones después de renderizar
  if (typeof applyTranslation === 'function' && typeof window.currentLang !== 'undefined') {
    applyTranslation(window.currentLang);
  }
}

      async function setQty(id, qty) {
        const r = await j(CART_API, { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify({ action: "update", id, qty: Math.max(0, qty) }) });
        if (r.ok) await loadMiniCart();
      }

      async function updateQty(id, delta) {
        const row = list.querySelector(`.mc-item[data-id="${id}"]`);
        if (!row) return;
        const cur = parseInt(row.querySelector("input").value, 10) || 1;
        await setQty(id, cur + delta);
      }

      overlay.addEventListener("click", closeCart);
      if (closeBtn) closeBtn.addEventListener("click", closeCart);
      if (openBtn) openBtn.addEventListener("click", (e) => { e.preventDefault(); openCart(); loadMiniCart(); });

      // ====== MODAL ======
      const btnAgregarModal = document.getElementById("btnAgregarModal");
      if (btnAgregarModal) {
        btnAgregarModal.addEventListener("click", async () => {
          const prod = window.__productoSeleccionado;
          if (!prod) {
              console.error("Error: No hay producto seleccionado");
              return;
          }
          
          const selects = document.querySelectorAll("#modalOpcionesWrap select");
          let extraNombre = selects.length ? " (" + [...selects].map((s) => s.value).join(", ") + ")" : "";
          const tamaño = document.getElementById("selectTamaño") ? document.getElementById("selectTamaño").value : "Normal";
          const nombreFinal = `${prod.nombre}${extraNombre} - ${tamaño}`;

          // Aquí se llama a j(), que a su vez llamará a cart_api.php
          const res = await j(CART_API, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ action: "add", id: prod.id, qty: 1, nombre: nombreFinal, precio: prod.precio, foto: prod.foto, tamano: tamaño }),
          });

          document.getElementById("modalOpciones").style.display = "none";

          // Si todo OK
          if (res.ok) {
            await refreshBadge();
            openCart();
            await loadMiniCart();
          }
          // Si falló, j() ya mostró la alerta.
        });
      }

      const btnCerrarModal = document.getElementById("btnCerrarModal");
      if (btnCerrarModal) btnCerrarModal.addEventListener("click", () => { document.getElementById("modalOpciones").style.display = "none"; });

document.addEventListener("click", async (e) => {
  const btn = e.target.closest(".ts-cart");
  if (!btn) return;

  const card = btn.closest(".ts-card");
  if (!card) return;

  const p = (window.__productoSeleccionado = {
    id: card.dataset.id,
    nombre: card.dataset.name,
    precio: parseFloat(card.dataset.price || 0),
    foto: card.dataset.foto,
    listboxes: card.dataset.listboxes ? JSON.parse(card.dataset.listboxes) : [],
  });

  const opcionesWrap = document.getElementById("modalOpcionesWrap");
  if (!opcionesWrap) return;

  opcionesWrap.innerHTML = "";

  // Generar las listboxes
  (p.listboxes || []).forEach((lb) => {
    const opts = (lb.opciones || []).map((opt) => {
      const val = opt.opcion || opt.nombre;
      return `<option value="${val}" data-translate="${val}">${val}</option>`;
    }).join("");

    opcionesWrap.innerHTML += `
      <div class="modal-field">
        <label data-translate="${lb.nombre}">${lb.nombre}</label>
        <select>${opts}</select>
      </div>`;
  });

  document.getElementById("modalProductoNombre").textContent = "Personalizar " + p.nombre;
  document.getElementById("modalOpciones").style.display = "flex";

  // ✅ AGREGADO: Aplicar traducciones al contenido del modal
  if (typeof applyTranslation === 'function' && typeof window.currentLang !== 'undefined') {
    applyTranslation(window.currentLang);
  }
});


      refreshBadge();
    }

    function boot() {
      initFiltro?.();
      initProductosAJAX?.();
      initMiniCart();
    }

    if (document.readyState !== "loading") boot();
    else document.addEventListener("DOMContentLoaded", boot);
  })();
})();