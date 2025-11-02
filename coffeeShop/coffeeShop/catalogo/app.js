(function () {
  "use strict";

  const $ = (s, r = document) => r.querySelector(s);

  // ============================
  // 1) Config base imágenes
  // ============================
const IMG_BASE = "../../Images/";
const IMG_PLACEHOLDER = IMG_BASE + "placeholder.png";

function resolveImgPath(raw) {
  let s = (raw || "").toString().trim();
  if (!s) return IMG_PLACEHOLDER;

  if (/^https?:\/\//i.test(s)) return s; // URL absoluta

  s = s.replace(/\\/g, "/")
       .replace(/^\.\/+/, "")
       .replace(/^(\.\.\/)+/, "")
       .replace(/^assest\//i, "")
       .replace(/^assets?\//i, "")
       .replace(/^images?\//i, "");

  return encodeURI(IMG_BASE + s);
}


  // ============================
  // 2) Filtro desplegable
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
  // 3) Carga AJAX de productos
  // ============================
  function initProductosAJAX() {
    const grid = document.querySelector(".hotdrinks__grid[data-autoload='ajax']");
    if (!grid) {
      console.log("[AJAX] No hay .hotdrinks__grid[data-autoload='ajax']");
      return;
    }

    const categoriaAttr = grid.getAttribute("data-categoria") || "";
    console.log("[AJAX] Grid detectada. Categoría:", categoriaAttr || "(todas)");

    function renderCard(p) {
  const sku = (p.idp ?? "").toString();
  const disponible = Number(p.STOCK ?? 0) > 0;
  const img = resolveImgPath(p.ruta_imagen);

  const precioBase   = Number(p.precio_base ?? p.precio ?? 0);
  const extraSabor   = Number(p?.sabor?.precio_extra ?? 0);
  const extraTam     = Number(p?.tamano?.precio_aumento ?? 0);
  const precioTotal  = Number(p.precio_total ?? (precioBase + extraSabor + extraTam));
  const precioTxt    = precioTotal.toFixed(2);

  // ya NO ponemos ts-meta en absoluto
  return `
    <article class="ts-card"
      data-id="${sku}"
      data-name="${p.namep || ""}"
      data-price="${precioTotal}"
      data-foto="${img}">
      <div class="ts-stage">
        <img src="${img}" alt="${p.namep || ""}"
             loading="lazy" decoding="async"
             onerror="this.onerror=null;this.src='${IMG_PLACEHOLDER}';">
        <div class="ts-rate"><strong>4.6</strong> ★</div>
      </div>

      <h4 class="ts-name">
        ${p.namep || ""}
        <small style="opacity:.7">• SKU ${sku || "—"}</small>
      </h4>

      <p class="ts-desc">${p.descripcion || ""}</p>

      <div class="ts-info">
        <span>${disponible ? "Disponible" : "Agotado"}</span>
        <span class="ts-price">$${precioTxt} MXN</span>
        <button class="ts-cart" ${disponible ? "" : "disabled"}>🛒</button>
      </div>
    </article>
  `;
}


    async function cargarCategoria(cat) {
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
        } else {
          grid.innerHTML = prevHTML || `
            <p style="grid-column:1/-1; text-align:center; padding:16px;">
              No hay productos para esta categoría.
            </p>`;
        }
      } catch (e) {
        console.error("[AJAX] Error en fetch:", e);
      }
    }

    cargarCategoria(categoriaAttr);
  }

  // ============================
  // 4) Mini-carrito Drawer
  // ============================
  function initMiniCart() {
    const openBtn = $("#open-cart") || $("#navCartBtn");
    const overlay = $("#mc-overlay") || $("#mcOverlay");
    const drawer = $("#mini-cart") || $("#miniCart");
    const closeBtn = $("#mc-close") || $("#mcClose");
    const list = $("#mc-list") || $("#mcList");
    const emptyMsg = (list ? list.querySelector(".mc__empty") : null) || $("#mcEmpty");
    const totalEl = $("#mc-total") || $("#mcTotal");
    const badge = $("#nav-cart-count") || $("#open-cart span");

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
      overlay.hidden = false;
    }

    function closeCart() {
      drawer.classList.remove("is-open");
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
      } catch {}
    }

    async function loadMiniCart() {
      const d = await j("cart_api.php?action=list");
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

      list.innerHTML = d.items.map((p) => `
        <li class="mc-item" data-id="${p.id}">
          <img class="mc-img" src="${p.foto || IMG_PLACEHOLDER}"
               alt="${p.nombre || ""}"
               onerror="this.onerror=null;this.src='${IMG_PLACEHOLDER}';">
          <div>
            <p class="mc-name">${p.nombre || "Producto"}</p>
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
      `).join("");

      totalEl.textContent = fmt(d.total);

      list.querySelectorAll(".mc-item").forEach((li) => {
        const id = li.dataset.id;
        li.querySelector(".qty-minus")?.addEventListener("click", () => updateQty(id, -1));
        li.querySelector(".qty-plus")?.addEventListener("click", () => updateQty(id, +1));
        li.querySelector(".mc-del")?.addEventListener("click", () => setQty(id, 0));
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

    document.addEventListener("click", async (e) => {
      const btn = e.target.closest(".ts-cart");
      if (!btn) return;

      const card = btn.closest(".ts-card");
      if (!card) return;

      const id = card.dataset.id || "";
      const nombre = card.dataset.name || "Producto";
      const precio = parseFloat(card.dataset.price || "0") || 0;
      const foto = card.dataset.foto || IMG_PLACEHOLDER;

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

  // ============================
  // 5) Boot
  // ============================
  function boot() {
    initFiltro();
    initProductosAJAX();
    initMiniCart();
  }

  if (document.readyState !== "loading") boot();
  else document.addEventListener("DOMContentLoaded", boot);
})();
