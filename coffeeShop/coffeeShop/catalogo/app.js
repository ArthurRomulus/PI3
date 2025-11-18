(function () {
  "use strict";

  const $ = (s, r = document) => r.querySelector(s);

  // ============================
  // 0) CONFIG GLOBAL
  // ============================
  // Ruta base para imágenes de productos
  const IMG_BASE = "../../Images/";
  const IMG_PLACEHOLDER = IMG_BASE + "placeholder.png";

  // Ruta base para el API del carrito.
  // Cada página puede definir window.CART_API_URL antes de  este script.
  // Ejemplo en catalogo.php:
  //   window.CART_API_URL = 'cart_api.php';
  // Ejemplo en comentarios.php:
  //   window.CART_API_URL = '../catalogo/cart_api.php';
  const CART_API = window.CART_API_URL || "cart_api.php";

  function resolveImgPath(raw) {
    let s = (raw || "").toString().trim();
    if (!s) return IMG_PLACEHOLDER;

    // URL absoluta
    if (/^https?:\/\//i.test(s)) return s;

    // Normalizamos rutas locales
    s = s
      .replace(/\\/g, "/")
      .replace(/^\.\/+/, "")
      .replace(/^(\.\.\/)+/, "")
      .replace(/^assest\//i, "")
      .replace(/^assets?\//i, "")
      .replace(/^Images?\//i, "");

    return encodeURI(IMG_BASE + s);
  }

  // Utilidad para escapar caracteres peligrosos en texto
function escapeHtml(text = "") {
  return text
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}


  // ============================
  // 1) Filtro desplegable de categorías (catálogo)
  // ============================
  function initFiltro() {
    const btn = $("#btn-filtrar");
    const menu = $("#menu-filtrar");
    const wrap = $("#filtro-wrap");
    if (!btn || !menu || !wrap) return;

    function toggleMenu() {
      menu.style.display = menu.style.display === "block" ? "none" : "block";
    }
    function closeMenu() {
      menu.style.display = "none";
    }

    btn.addEventListener("click", (e) => {
      e.stopPropagation();
      toggleMenu();
    });

    document.addEventListener("click", (e) => {
      if (!wrap.contains(e.target)) closeMenu();
    });

    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") closeMenu();
    });
  }

  // ============================
  // 2) Carga AJAX de productos (catálogo)
  // ============================
  function initProductosAJAX() {
    const grid = document.querySelector(
      '.hotdrinks__grid[data-autoload="ajax"]'
    );

    // Si esta página no tiene grid (por ejemplo comentarios.php), no hacemos nada
    if (!grid) {
      console.log(
        "[AJAX] No hay .hotdrinks__grid[data-autoload='ajax'] en esta página (normal)"
      );
      return;
    }

    const categoriaAttr = grid.getAttribute("data-categoria") || "";
    console.log(
      "[AJAX] Grid detectada. Categoría:",
      categoriaAttr || "(todas)"
    );

    function renderCard(p) {
      const sku = (p.idp ?? "").toString();
      const disponible = Number(p.STOCK ?? 0) > 0;
      const img = resolveImgPath(p.ruta_imagen);

      const precioTotal = Number(p.precio_total ?? p.precio_base ?? 0).toFixed(2);

      return `
        <article class="ts-card"
          data-id="${sku}"
          data-name="${p.namep || ""}"
          data-price="${precioTotal}"
          data-foto="${img}"
          data-listboxes='${JSON.stringify(p.listboxes || [])}'>
          
          <div class="ts-stage">
            <img src="${img}" alt="${p.namep || ""}" onerror="this.onerror=null;this.src='${IMG_PLACEHOLDER}';" />
          </div>

          <h4 class="ts-name" data-translate="${p.namep || ""}">
            ${p.namep || ""}
            <small style="opacity:.7">• SKU ${sku || "—"}</small>
          </h4>

          <p class="ts-desc" data-translate="${p.descripcion || ""}">
            ${p.descripcion || ""}
          </p>

          <!-- CATEGORÍAS -->
          <p class="ts-categoria" data-translate="${ (p.categorias || []).map(c => c.nombre).join(', ') || 'Sin categoría' }">
            ${ (p.categorias || []).map(c => c.nombre).join(', ') || 'Sin categoría' }
          </p>

          <!-- STOCK -->
          <p class="ts-stock">
            <strong data-translate="Stock">Stock:</strong> ${p.STOCK ?? 0}
          </p>

          <div class="ts-info">
            <span data-translate="${disponible ? 'Disponible' : 'No disponible'}">
              ${disponible ? 'Disponible' : 'No disponible'}
            </span>
            <span class="ts-price">$${precioTotal} MXN</span>
            <button class="ts-cart" ${disponible ? "" : "disabled"}>🛒</button>
          </div>
      </article>
      `;
    }

    async function cargarCategoria(cat) {
      // IMPORTANTE: esta URL es relativa al archivo que contiene este script,
      // o sea catalogo/*.php. Para comentarios no se ejecuta porque no hay grid.
      const u = new URL("./get_productos.php", location.href);
      if (cat) u.searchParams.set("categoria", cat);

      const url = u.toString();
      console.log("[AJAX] Fetch:", url);
      const prevHTML = grid.innerHTML;

      try {
        const res = await fetch(url, { cache: "no-store" });
        const ct = res.headers.get("content-type") || "";

        if (!ct.includes("application/json")) {
          const txt = await res.text();
          console.error("[AJAX] Respuesta NO JSON:", res.status, txt);
          return;
        }

        const data = await res.json();
        console.log("[AJAX] Respuesta JSON:", data);

        if (!data || data.ok !== true) {
          console.warn("[AJAX] ok !== true. No se altera el DOM.");
          return;
        }

        if (Array.isArray(data.items) && data.items.length) {
          grid.innerHTML = data.items.map(renderCard).join("");

           if (window.currentLang && typeof applyTranslation === "function") {
        applyTranslation(window.currentLang);
    }
        } else {
          grid.innerHTML =
            prevHTML ||
            `
            <p style="grid-column:1/-1; text-align:center; padding:16px;">
              No hay productos para esta categoría.
            </p>`;

            if (window.currentLang && typeof applyTranslation === "function") {
        applyTranslation(window.currentLang);
    }
        }
      } catch (e) {
        console.error("[AJAX] Error en fetch:", e);
      }
    }

    cargarCategoria(categoriaAttr);
  }
// ============================================
// 3) Mini-carrito Drawer + Modal opciones
// ============================================
(function () {

  function initMiniCart() {
    // soporta tus dos variantes de IDs/clases según las páginas
    const openBtn  = $("#open-cart") || $("#navCartBtn");
    const overlay  = $("#mc-overlay") || $("#mcOverlay");
    const drawer   = $("#mini-cart")  || $("#miniCart");
    const closeBtn = $("#mc-close")   || $("#mcClose");
    const list     = $("#mc-list")    || $("#mcList");
    const emptyMsg =
      (list ? list.querySelector(".mc__empty") : null) || $("#mcEmpty");
    const totalEl  = $("#mc-total")   || $("#mcTotal");

    // ⛔ Si esta página NO tiene el HTML del mini-carrito, salimos
    if (!overlay || !drawer || !list || !totalEl) {
      return;
    }

    const fmt = (n) =>
      Number(n || 0).toLocaleString("es-MX", {
        style: "currency",
        currency: "MXN",
      });

    async function j(url, opt) {
      try {
        const r = await fetch(url, opt);
        return await r.json();
      } catch {
        return { ok: false };
      }
    }

    function openCart() {
      drawer.classList.add("is-open");
      overlay.hidden = false;
    }

    function closeCart() {
      drawer.classList.remove("is-open");
      overlay.hidden = true;
    }

    // ====== REFRESH BADGE (contador del carrito en el header) ======
    async function refreshBadge() {
      const badge =
        document.querySelector("#nav-cart-count") ||
        document.querySelector("#open-cart span");

      if (!badge) return;

      try {
        const r = await fetch(CART_API + "?action=count");
        const d = await r.json();
        if (d.ok && typeof d.count !== "undefined") {
          badge.textContent = d.count;
        }
      } catch (err) {
        console.error("Error al refrescar badge del carrito:", err);
      }
    }

    // ====== Cargar contenido del mini-carrito ======
    async function loadMiniCart() {
      const d = await j(CART_API + "?action=list");
      if (!d.ok) {
        list.innerHTML = "";
        if (emptyMsg) emptyMsg.textContent = "No se pudo cargar el carrito.";
        totalEl.textContent = fmt(0);
        return;
      }

      if (!d.items.length) {
        list.innerHTML = "";
        if (emptyMsg) emptyMsg.style.display = "block";
        totalEl.textContent = fmt(0);
        await refreshBadge();
        return;
      }

      if (emptyMsg) emptyMsg.style.display = "none";

      list.innerHTML = d.items
        .map(
          (p) => `
        <li class="mc-item" data-id="${p.id}">
          <img class="mc-img" src="${
            p.foto || IMG_PLACEHOLDER
          }" alt="${p.nombre || ""}"
               onerror="this.onerror=null;this.src='${IMG_PLACEHOLDER}';">
          <div>
            <p class="mc-name" data-translate="${p.nombre || "Producto"}">
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
            <button type="button" class="mc-close mc-del" title="Eliminar">🗑</button>
          </div>
        </li>
      `
        )
        .join("");

      if (window.currentLang && typeof applyTranslation === "function") {
        applyTranslation(window.currentLang);
      }

      totalEl.textContent = fmt(d.total);

      // botones +/-, eliminar
      list.querySelectorAll(".mc-item").forEach((li) => {
        const id = li.dataset.id;
        li
          .querySelector(".qty-minus")
          ?.addEventListener("click", () => updateQty(id, -1));
        li
          .querySelector(".qty-plus")
          ?.addEventListener("click", () => updateQty(id, +1));
        li
          .querySelector(".mc-del")
          ?.addEventListener("click", () => setQty(id, 0));
      });

      await refreshBadge();
    }

    // ====== Helpers de cantidad ======
    async function setQty(id, qty) {
      const r = await j(CART_API, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          action: "update",
          id,
          qty: Math.max(0, qty),
        }),
      });
      if (r.ok) await loadMiniCart();
    }

    async function updateQty(id, delta) {
      const row = list.querySelector(`.mc-item[data-id="${id}"]`);
      if (!row) return;
      const cur = parseInt(row.querySelector("input").value, 10) || 1;
      await setQty(id, cur + delta);
    }

    // ====== Eventos del drawer ======
    overlay.addEventListener("click", closeCart);
    if (closeBtn) {
      closeBtn.addEventListener("click", closeCart);
    }

    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") closeCart();
    });

    if (openBtn) {
      openBtn.addEventListener("click", (e) => {
        e.preventDefault();
        openCart();
        loadMiniCart();
      });
    }

    // ====== MODAL de personalización ======
    const btnAgregarModal = document.getElementById("btnAgregarModal");
    if (btnAgregarModal) {
      btnAgregarModal.addEventListener("click", async () => {
        const prod = window.__productoSeleccionado;
        if (!prod) return;

        const selects = document.querySelectorAll("#modalOpcionesWrap select");

        let extraNombre = selects.length
          ? " (" + [...selects].map((s) => s.value).join(", ") + ")"
          : "";

        const tamañoSelect = document.getElementById("selectTamaño");
        const tamaño = tamañoSelect ? tamañoSelect.value : "Normal";

        const nombreFinal = `${prod.nombre}${extraNombre} - ${tamaño}`;

        const res = await fetch(CART_API, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({
            action: "add",
            id: prod.id,
            qty: 1,
            nombre: nombreFinal,
            precio: prod.precio,
            foto: prod.foto,
            tamano: tamaño,
          }),
        }).then((r) => r.json());

        if (res.ok) {
          document.getElementById("modalOpciones").style.display = "none";
          await refreshBadge();
          openCart();
          await loadMiniCart();
        } else {
          alert("Error al agregar al carrito");
        }
      });
    }

    const btnCerrarModal = document.getElementById("btnCerrarModal");
    if (btnCerrarModal) {
      btnCerrarModal.addEventListener("click", () => {
        document.getElementById("modalOpciones").style.display = "none";
      });
    }

    // click "🛒" en tarjeta producto => abrir modal
    document.addEventListener("click", (e) => {
      const btn = e.target.closest(".ts-cart");
      if (!btn) return;

      const card = btn.closest(".ts-card");
      if (!card) return;

      const p = (window.__productoSeleccionado = {
        id: card.dataset.id,
        nombre: card.dataset.name,
        precio: parseFloat(card.dataset.price || 0),
        foto: card.dataset.foto,
        listboxes: card.dataset.listboxes
          ? JSON.parse(card.dataset.listboxes)
          : [],
      });

      const opcionesWrap = document.getElementById("modalOpcionesWrap");
      opcionesWrap.innerHTML = "";

      (p.listboxes || []).forEach((lb) => {
        const wrapper = document.createElement("div");
        wrapper.className = "modal-field";

        wrapper.innerHTML = `
          <label data-translate="${lb.nombre}">${lb.nombre}</label>
          <select data-lbid="${lb.id}">
            ${lb.opciones
              .map(
                (opt) => `
              <option value="${opt.opcion}" data-opid="${opt.id}" data-translate="${opt.opcion}">
                ${opt.opcion}
              </option>
            `
              )
              .join("")}
          </select>
        `;

        opcionesWrap.appendChild(wrapper);
      });

      document.getElementById("modalProductoNombre").textContent =
        "Personalizar " + p.nombre;

      document.getElementById("modalOpciones").style.display = "flex";
      console.log("Array de opciones del producto seleccionado:", p.listboxes);

      if (window.currentLang && typeof applyTranslation === "function") {
        applyTranslation(window.currentLang);
      }
    });

    // llamada inicial
    refreshBadge();
  }

function cargarTopVendidos() {
  const grid = document.getElementById("topSellingGrid");
  if (!grid) return;

  fetch("get_top_selling.php")
    .then((res) => res.json())
    .then((json) => {
      console.log("JSON top vendidos:", json);

      if (!json.success) {
        console.error("Error en la API de más vendidos:", json.error);
        grid.innerHTML =
          '<p style="color:#fff; font-size:14px;">No se pudieron cargar los productos.</p>';
        return;
      }

      grid.innerHTML = "";

      json.data.forEach((prod) => {
        // Campos que vienen del PHP
        let imgSrc       = prod.imagen || prod.ruta_imagen || "";
        const nombre     = prod.nombre || prod.namep || "";
        const descLinea2 = prod.descripcion_corta || prod.descripcion || "";
        const categorias = prod.categorias || prod.categoria || "";
        const stock      = prod.STOCK || prod.stock || 0;
        const precio     = Number(prod.precio || 0);
        const idp        = prod.id || prod.idp || "";

        if (!imgSrc) {
          imgSrc = "../../Images/placeholder.png";
        }

        const card = document.createElement("article");
        card.className = "mv2-card";

        card.innerHTML = `
          <div class="mv2-peach"></div>

          <div class="mv2-img-wrap">
            <img src="${imgSrc}" alt="${escapeHtml(nombre)}">
          </div>

          <div class="mv2-body">
            <h3 class="mv2-title">${escapeHtml(nombre)}</h3>

            <p class="mv2-sub">
              ${descLinea2 ? escapeHtml(descLinea2) : ""}
            </p>

            <p class="mv2-cat">
              ${categorias ? escapeHtml(categorias) : ""}
            </p>

            <p class="mv2-stock">
              <span>Stock:</span> ${stock}
            </p>

            <div class="mv2-footer">
              <div class="mv2-price-pill">
                Precio $${precio.toFixed(2)} MXN
              </div>
              <button class="mv2-cart-btn" data-idp="${idp}">
                🛒
              </button>
            </div>
          </div>
        `;

        grid.appendChild(card);
      });
    })
    .catch((err) => {
      console.error("Error fetch más vendidos:", err);
    });
}






  // ============================================
  // 5) Boot
  // ============================================
  function boot() {
    initFiltro?.();          // si existen
    initProductosAJAX?.();
    initMiniCart();
    cargarTopVendidos();
  }

  if (document.readyState !== "loading") boot();
  else document.addEventListener("DOMContentLoaded", boot);

})()})();
