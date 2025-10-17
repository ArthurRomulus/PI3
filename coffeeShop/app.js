(function () {
  "use strict";

  const $ = (s, r = document) => r.querySelector(s);

  /******************************************************************
   * 1) Filtro desplegable
   ******************************************************************/
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

  /******************************************************************
   * 2) Carga de productos vía AJAX (solo si data-autoload="ajax")
   ******************************************************************/
  function initProductosAJAX() {
    const grid = document.querySelector(".hotdrinks__grid[data-autoload='ajax']");
    if (!grid) {
      console.log("[AJAX] No hay .hotdrinks__grid[data-autoload='ajax'] en este documento.");
      return;
    }

    const categoria = grid.getAttribute("data-categoria") || "bebidas_calientes";
    console.log("[AJAX] Grid detectada. Categoría:", categoria);

    function resolveImgPath(raw) {
      let s = (raw || "").toString().trim();
      if (!s) return "assest/placeholder.png";
      if (/^https?:\/\//i.test(s)) return s;
      s = s.replace(/\\/g, "/").replace(/^\.?\//, "");
      if (s.startsWith("assets/")) s = "assest/" + s.slice(7);
      if (!s.includes("/")) s = "assest/" + s;
      s = s.replace(/\/{2,}/g, "/");
      return encodeURI(s);
    }

    function renderCard(p) {
      const sku = (p.id ?? p.id_producto ?? "").toString();
      const cant = Number(p.cantidad_producto ?? p.cantidadProducto ?? 0);
      const disponible = cant > 0;
      const precio = Number(p.precio_producto || 0).toFixed(2);
      const img = resolveImgPath(p.foto_producto);

      return `
        <article class="ts-card" data-id="${sku}" data-name="${p.nombre_producto || ""}" data-price="${precio}" data-foto="${img}">
          <div class="ts-stage">
            <img src="${img}" alt="${p.nombre_producto || ""}"
                 loading="lazy" decoding="async"
                 onerror="this.onerror=null;this.src='assest/placeholder.png';">
            <div class="ts-rate"><strong>4.6</strong> ★</div>
          </div>
          <h4 class="ts-name">${p.nombre_producto || ""}
            <small style="opacity:.7">• SKU ${sku || "—"}</small>
          </h4>
          <p class="ts-desc">${p.descripcion_producto || ""}</p>
          <div class="ts-info">
            <span>${disponible ? "Disponible" : "Agotado"}</span>
            <span class="ts-price">$${precio} MXN</span>
            <button class="ts-cart" ${disponible ? "" : "disabled"}>🛒</button>
          </div>
        </article>
      `;
    }

    async function cargarCategoria(cat) {
      const url = `./get_productos.php?categoria=${encodeURIComponent(cat)}`;
      console.log("[AJAX] Fetch:", url);

      const prevHTML = grid.innerHTML;

      try {
        const res = await fetch(url, { cache: "no-store" });

        const ct = res.headers.get("content-type") || "";
        if (!ct.includes("application/json")) {
          const txt = await res.text();
          console.error("[AJAX] Respuesta NO JSON. Status:", res.status, "\n", txt);
          return; // no tocar lo existente
        }

        const data = await res.json();
        console.log("[AJAX] Respuesta JSON:", data);

        if (!data || data.ok !== true) {
          console.warn("[AJAX] ok !== true. No se altera el DOM.");
          return;
        }

        if (Array.isArray(data.items) && data.items.length) {
          grid.innerHTML = data.items.map(renderCard).join("");
        } else {
          console.warn("[AJAX] items vacío. Se deja el contenido previo.");
          grid.innerHTML = prevHTML || `
            <p style="grid-column:1/-1; text-align:center; padding:16px;">
              No hay productos para esta categoría.
            </p>`;
        }
      } catch (e) {
        console.error("[AJAX] Error en fetch/parse:", e);
        // no borrar lo existente
      }
    }

    cargarCategoria(categoria);
  }

  /******************************************************************
   * 3) Mini-carrito Drawer
   ******************************************************************/
  function initMiniCart() {
    const openBtn = $("#open-cart") || $("#navCartBtn");
    const overlay = $("#mc-overlay") || $("#mcOverlay");
    const drawer  = $("#mini-cart")   || $("#miniCart");
    const closeBtn= $("#mc-close")    || $("#mcClose");
    const list    = $("#mc-list")     || $("#mcList");
    const emptyMsg= (list ? list.querySelector(".mc__empty") : null) || $("#mcEmpty");
    const totalEl = $("#mc-total")    || $("#mcTotal");
    const clearBtn= $("#mc-clear");
    const badge   = $("#nav-cart-count") || $("#open-cart span");

    if (!overlay || !drawer || !list || !totalEl) return;

    const fmt = (n) =>
      Number(n || 0).toLocaleString("es-MX", { style: "currency", currency: "MXN" });

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
      drawer.setAttribute("aria-hidden", "false");
      overlay.hidden = false;
    }

    function closeCart() {
      drawer.classList.remove("is-open");
      drawer.setAttribute("aria-hidden", "true");
      overlay.hidden = true;
    }

    overlay.addEventListener("click", closeCart);
    closeBtn?.addEventListener("click", closeCart);
    document.addEventListener("keydown", (e) => { if (e.key === "Escape") closeCart(); });

    openBtn?.addEventListener("click", (e) => {
      e.preventDefault();
      openCart();
      loadMiniCart();
    });

    async function refreshBadge() {
      try {
        const d = await j("cart_api.php?action=count");
        if (d.ok && badge) badge.textContent = d.count ?? 0;
      } catch {/* noop */}
    }

    async function loadMiniCart() {
      const d = await j("cart_api.php?action=list");
      if (!d.ok) {
        list.innerHTML = "";
        if (emptyMsg) { emptyMsg.textContent = "No se pudo cargar el carrito."; emptyMsg.style.display = "block"; }
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

      list.innerHTML = d.items.map((p) => `
        <li class="mc-item" data-id="${p.id}">
          <img class="mc-img" src="${p.foto || "assest/placeholder.png"}"
               alt="${p.nombre || ""}"
               onerror="this.onerror=null;this.src='assest/placeholder.png';">
          <div>
            <p class="mc-name">${p.nombre || "Producto"}</p>
            <div class="mc-meta">${fmt(p.precio)} c/u</div>
            <div class="mc-qty" aria-label="Cantidad">
              <button type="button" class="qty-minus" aria-label="Quitar uno">−</button>
              <input type="text" value="${p.qty}" aria-label="Cantidad actual" readonly>
              <button type="button" class="qty-plus" aria-label="Agregar uno">+</button>
            </div>
          </div>
          <div style="display:flex; flex-direction:column; align-items:end; gap:8px;">
            <div class="mc-price">${fmt(p.subtotal)}</div>
            <button type="button" class="mc-close mc-del" title="Eliminar">🗑</button>
          </div>
        </li>
      `).join("");

      totalEl.textContent = fmt(d.total);

      list.querySelectorAll(".mc-item").forEach((li) => {
        const id = li.dataset.id;
        li.querySelector(".qty-minus")?.addEventListener("click", () => updateQty(id, -1));
        li.querySelector(".qty-plus") ?.addEventListener("click", () => updateQty(id, +1));
        li.querySelector(".mc-del")   ?.addEventListener("click", () => setQty(id, 0));
      });

      await refreshBadge();
    }

    async function setQty(id, qty) {
      const r = await j("cart_api.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ action: "update", id, qty: Math.max(0, qty) }),
      });
      if (r.ok) await loadMiniCart();
    }

    async function updateQty(id, delta) {
      const row = list.querySelector(`.mc-item[data-id="${id}"]`);
      if (!row) return;
      const cur = parseInt(row.querySelector("input").value, 10) || 1;
      await setQty(id, cur + delta);
    }

    // Añadir desde tarjetas (robusto: usa data-* o lee del DOM)
    document.addEventListener("click", async (e) => {
      const btn = e.target.closest(".ts-cart");
      if (!btn) return;

      const card = btn.closest(".ts-card");
      if (!card) return;

      const id     = card.dataset.id || card.querySelector(".ts-name")?.textContent?.trim() || "";
      const nombre = card.dataset.name || card.querySelector(".ts-name")?.textContent?.trim() || "Producto";
      const precio = parseFloat(
        (card.dataset.price || card.querySelector(".ts-price")?.textContent || "0").replace(/[^\d.]/g, "")
      ) || 0;
      const foto   = card.dataset.foto || card.querySelector(".ts-stage img")?.getAttribute("src") || "assest/placeholder.png";

      if (!id) { 
        console.warn("No hay id de producto en la tarjeta", card);
        return;
      }

      try {
        const r = await fetch("cart_api.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ action: "add", id, qty: 1, nombre, precio, foto })
        }).then(x => x.json());

        if (r?.ok) {
          await refreshBadge();
          openCart();
          await loadMiniCart();
        } else {
          alert(r?.error || "No se pudo añadir al carrito.");
        }
      } catch (err) {
        console.error(err);
        alert("Error de red al añadir al carrito.");
      }
    });

    refreshBadge();
  }

  /******************************************************************
   * Boot
   ******************************************************************/
  function boot() {
    initFiltro();
    initProductosAJAX();
    initMiniCart();
  }

  if (document.readyState !== "loading") boot();
  else document.addEventListener("DOMContentLoaded", boot);
})();
