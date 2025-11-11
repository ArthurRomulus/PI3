
(function () {
  "use strict";

  const $ = (s, r = document) => r.querySelector(s);
  const $$ = (s, r = document) => [...r.querySelectorAll(s)];

  // ============================
  // 0) CONFIG GLOBAL
  // ============================
  const IMG_BASE = "../../Images/";
  const IMG_PLACEHOLDER = IMG_BASE + "placeholder.png";
  const CART_API = window.CART_API_URL || "cart_api.php";

  // ⛔ Productos que NO deben mostrar "Leche"
  // (idp de la DB)
  const NO_MILK_IDS = new Set([144,146,147,148]);

  // Normaliza texto: minúsculas + sin acentos
  function norm(s){
    return String(s || "")
      .toLowerCase()
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "");
  }

  function resolveImgPath(raw) {
    let s = (raw || "").toString().trim();
    if (!s) return IMG_PLACEHOLDER;
    if (/^https?:\/\//i.test(s)) return s;

    s = s.replace(/^\/?pi3\/images\//i, "")
         .replace(/\\/g, "/")
         .replace(/^\.\/+/, "")
         .replace(/^(\.\.\/)+/, "")
         .replace(/^assest\//i, "")
         .replace(/^assets?\//i, "")
         .replace(/^images?\//i, "");

    return encodeURI(IMG_BASE + s);
  }

  const money = (n) =>
    Number(n || 0).toLocaleString("es-MX", { style: "currency", currency: "MXN" });

  // ============================
  // 1) Filtro desplegable
  // ============================
  function initFiltro() {
    const btn = $("#btn-filtrar");
    const menu = $("#menu-filtrar");
    const wrap = $("#filtro-wrap");
    if (!btn || !menu || !wrap) return;

    function toggleMenu() {
      menu.style.display = menu.style.display === "block" ? "none" : "block";
    }
    function closeMenu() { menu.style.display = "none"; }

    btn.addEventListener("click", (e) => { e.stopPropagation(); toggleMenu(); });
    document.addEventListener("click", (e) => { if (!wrap.contains(e.target)) closeMenu(); });
    document.addEventListener("keydown", (e) => { if (e.key === "Escape") closeMenu(); });
  }

  // ============================
  // 2) Carga AJAX de productos
  // ============================
  function initProductosAJAX() {
    const grid = document.querySelector('.hotdrinks__grid[data-autoload="ajax"]');
    if (!grid) return;

    const categoriaAttr = grid.getAttribute("data-categoria") || "";

    function renderCard(p) {
      const sku = (p.idp ?? "").toString();
      const disponible = Number(p.STOCK ?? 0) > 0;
      const img = resolveImgPath(p.ruta_imagen);

      const precioBase = Number(p.precio_base ?? p.precio ?? 0);
      const extraSabor = Number(p?.sabor?.precio_extra ?? 0);
      const extraTam = Number(p?.tamano?.precio_aumento ?? 0);
      const precioTot = Number(p.precio_total ?? precioBase + extraSabor + extraTam);
      const precioTxt = precioTot.toFixed(2);

      return `
        <article class="ts-card"
          data-id="${sku}"
          data-name="${p.namep || ""}"
          data-price="${precioTot}"
          data-foto="${img}"
          data-categoria-name="${p.categorias || ""}">
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
            <span class="pill ts-price">$${precioTxt} MXN</span>
            <button class="ts-cart pill" ${disponible ? "" : "disabled"} title="Agregar al carrito">🛒</button>
          </div>
        </article>`;
    }

    async function cargarCategoria(cat) {
      const u = new URL("./get_productos.php", location.href);
      if (cat) u.searchParams.set("categoria", cat);

      const url = u.toString();
      const prevHTML = grid.innerHTML;

      try {
        const res = await fetch(url, { cache: "no-store" });
        const ct = (res.headers.get("content-type") || "").toLowerCase();
        if (!ct.includes("application/json")) return;
        const data = await res.json();

        if (!data || data.ok !== true) return;
        if (Array.isArray(data.items) && data.items.length) {
          grid.innerHTML = data.items.map(renderCard).join("");
        } else {
          grid.innerHTML =
            prevHTML ||
            `<p style="grid-column:1/-1; text-align:center; padding:16px;">No hay productos para esta categoría.</p>`;
        }
      } catch (e) {
        console.error("[AJAX] Error en fetch:", e);
      }
    }

    cargarCategoria(categoriaAttr);
  }

  // ============================
  // 3) Mini-carrito Drawer
  // ============================
  function initMiniCart() {
    const openBtn  = $("#open-cart") || $("#navCartBtn");
    const overlay  = $("#mc-overlay") || $("#mcOverlay");
    const drawer   = $("#mini-cart")  || $("#miniCart");
    const closeBtn = $("#mc-close")   || $("#mcClose");
    const list     = $("#mc-list")    || $("#mcList");
    const emptyMsg = (list ? list.querySelector(".mc__empty") : null) || $("#mcEmpty");
    const totalEl  = $("#mc-total")   || $("#mcTotal");
    const badge    = $("#nav-cart-count") || $("#open-cart span");

    if (!overlay || !drawer || !list || !totalEl) return;

    drawer.setAttribute("aria-hidden", "true");
    drawer.setAttribute("inert", "");
    let lastFocus = null;

    async function j(url, opt) {
      try { const r = await fetch(url, opt); return await r.json(); }
      catch { return { ok: false }; }
    }

    function openCart() {
      lastFocus = document.activeElement instanceof HTMLElement ? document.activeElement : null;
      drawer.classList.add("is-open");
      overlay.hidden = false;
      drawer.removeAttribute("inert");
      drawer.setAttribute("aria-hidden", "false");
      (closeBtn || drawer).focus?.();
    }

    function closeCart() {
      if (drawer.contains(document.activeElement)) {
        try { document.activeElement.blur(); } catch {}
      }
      drawer.classList.remove("is-open");
      overlay.hidden = true;
      drawer.setAttribute("aria-hidden", "true");
      drawer.setAttribute("inert", "");
      if (lastFocus && document.contains(lastFocus)) {
        try { lastFocus.focus(); } catch {}
      }
      lastFocus = null;
    }

    overlay.addEventListener("click", closeCart);
    closeBtn?.addEventListener("click", closeCart);
    document.addEventListener("keydown", (e) => { if (e.key === "Escape") closeCart(); });

    openBtn?.addEventListener("click", (e) => { e.preventDefault(); openCart(); loadMiniCart(); });

    async function refreshBadge() {
      try {
        const d = await j(CART_API + "?action=count");
        if (d.ok && badge) badge.textContent = d.count ?? 0;
      } catch {}
    }

    async function loadMiniCart() {
      const d = await j(CART_API + "?action=list");
      if (!d.ok) {
        list.innerHTML = "";
        if (emptyMsg) emptyMsg.textContent = "No se pudo cargar el carrito.";
        totalEl.textContent = money(0);
        return;
      }

      if (!d.items.length) {
        list.innerHTML = "";
        if (emptyMsg) emptyMsg.style.display = "block";
        totalEl.textContent = money(0);
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
            <div class="mc-meta">${money(p.precio)} c/u${
              p.options
                ? " • " + (
                    Array.isArray(p.options.verduras)
                      ? [p.options.size?.nombre, ...(p.options.verduras.map(v=>v.nombre || ""))].filter(Boolean).join(" • ")
                      : [p.options.size?.nombre, p.options.leche?.nombre, p.options.sabor?.nombre].filter(Boolean).join(" • ")
                  )
                : ""
            }</div>
            <div class="mc-qty">
              <button type="button" class="qty-minus">−</button>
              <input type="text" value="${p.qty}" readonly>
              <button type="button" class="qty-plus">+</button>
            </div>
          </div>
          <div style="display:flex;flex-direction:column;align-items:end;gap:8px;">
            <div class="mc-price">${money(p.subtotal)}</div>
            <button type="button" class="mc-close mc-del" title="Eliminar">🗑</button>
          </div>
        </li>
      `).join("");

      totalEl.textContent = money(d.total);

      list.querySelectorAll(".mc-item").forEach((li) => {
        const id = li.dataset.id;
        li.querySelector(".qty-minus")?.addEventListener("click", () => updateQty(id, -1));
        li.querySelector(".qty-plus")?.addEventListener("click", () => updateQty(id, +1));
        li.querySelector(".mc-del")?.addEventListener("click", () => setQty(id, 0));
      });

      await refreshBadge();
    }

    async function setQty(id, qty) {
      const r = await j(CART_API, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ action: "update", id, qty: Math.max(0, qty) } ),
      });
      if (r.ok) await loadMiniCart();
    }

    async function updateQty(id, delta) {
      const row = list.querySelector(`.mc-item[data-id="${id}"]`);
      if (!row) return;
      const cur = parseInt(row.querySelector("input").value, 10) || 1;
      await setQty(id, cur + delta);
    }

    window.__miniCartOpenAndLoad__ = () => { openCart(); loadMiniCart(); };
    refreshBadge();
  }

  // ============================
  // 3.5) Personalizador (mini-modal)
  // ============================
  function initPersonalizador() {
    const modal = document.getElementById("mwkModal");
    if (!modal) return;

    modal.setAttribute("aria-hidden", "true");
    modal.setAttribute("inert", "");

    const elName = modal.querySelector("#mwkName");
    const elPrice = modal.querySelector("#mwkPrice");
    const elImg = modal.querySelector("#mwkImg");
    const segSize = modal.querySelector("#mwkSize");
    const segMilk = modal.querySelector("#mwkMilk");
    const milkNote = modal.querySelector("#mwkMilkNote");
    const btnConfirm = modal.querySelector("#mwkConfirm");

    let ctx = null;
    let lastFocus = null;

    // Defaults
    const DEFAULT_SIZES = [
      { id: 1, nombre: "Chico",   precio_extra: 0 },
      { id: 2, nombre: "Mediano", precio_extra: 5 },
      { id: 3, nombre: "Grande",  precio_extra: 10 },
    ];
    const DEFAULT_MILKS = [
      { id: 11, nombre: "Leche entera" },
      { id: 12, nombre: "Leche deslactosada" },
      { id: 13, nombre: "Leche de avena" },
      { id: 14, nombre: "Leche de almendras" },
    ];
    const DEFAULT_VERDURAS = [
      { id: 1, nombre: "Lechuga" },
      { id: 2, nombre: "Jitomate" },
      { id: 3, nombre: "Cebolla" },
      { id: 4, nombre: "Pepino" },
      { id: 6, nombre: "Aguacate" },
      { id: 7, nombre: "Queso" },
    ];
    const DEFAULT_SABORES_TE = [
      { id: 101, nombre: "Manzanilla" },
      { id: 102, nombre: "Limón" },
      { id: 103, nombre: "Camellia" },
      { id: 104, nombre: "Té verde" },
      { id: 105, nombre: "Hierbabuena" },
    ];
    const DEFAULT_SABORES_LIMONADA = [
      { id: 111, nombre: "Limón" },
      { id: 112, nombre: "Fresa" },
      { id: 113, nombre: "Piña" },
      { id: 114, nombre: "Pepino" },
    ];
    const DEFAULT_SABORES_REFRESCO = [
      { id: 121, nombre: "Coca-Cola" },
      { id: 122, nombre: "Fanta" },
      { id: 123, nombre: "Sprite" },
    ];

    // Heurística de fallback (mejorada)
    function pickFallbackGroup(catTags = "", prodName = "") {
      const cats = norm(catTags);
      const name = norm(prodName);

      const isTea     = /\bte\b|\btes\b|tea/.test(cats) || /\bte\b|\btes\b|tea/.test(name);
      const isLimo    = /limonada|limonadas/.test(cats) || /limonada|limonadas/.test(name);
      const isSoda    = /refresco|refrescos|soda|gaseosa/.test(cats) || /refresco|coca|fanta|sprite|soda|gaseosa/.test(name);
      const isWater   = /\bagua\b/.test(cats) || /\bagua\b/.test(name);
      const isCoffee  = /cafe|café|capuch?ino|latte|moch?a|moka|macchiato|espresso/.test(cats) ||
                        /cafe|café|capuch?ino|latte|moch?a|moka|macchiato|espresso/.test(name);
      const isFood    = /comida|alimento|ensalada|sandwich|s[aá]ndwich|torta|baguette|wrap|panini|hamburguesa|pizza|bagel|\bpan\b/
                          .test(cats) ||
                        /comida|alimento|ensalada|sandwich|s[aá]ndwich|torta|baguette|wrap|panini|hamburguesa|pizza|bagel|\bpan\b/
                          .test(name);

      if (isTea)     return { key: "sabor",    label: "Sabor",    type: "single", items: DEFAULT_SABORES_TE };
      if (isLimo)    return { key: "sabor",    label: "Sabor",    type: "single", items: DEFAULT_SABORES_LIMONADA };
      if (isSoda)    return { key: "sabor",    label: "Sabor",    type: "single", items: DEFAULT_SABORES_REFRESCO };
      if (isWater)   return { key: "none",     label: "",         type: "none",   items: [] };
      if (isFood)    return { key: "verduras", label: "Verduras", type: "multi",  items: DEFAULT_VERDURAS };
      if (isCoffee)  return { key: "leche",    label: "Leche",    type: "single", items: DEFAULT_MILKS };
      return { key: "leche", label: "Leche", type: "single", items: DEFAULT_MILKS };
    }

    async function fetchCustom(idp) {
      try {
        const u = new URL("get_customizacion.php", location.href);
        u.searchParams.set("idp", idp);
        const r = await fetch(u.toString(), { cache: "no-store" });
        const ct = (r.headers.get("content-type") || "").toLowerCase();
        if (!r.ok || !ct.includes("application/json")) return null;
        return await r.json();
      } catch (e) {
        console.warn("get_customizacion.php error:", e);
        return null;
      }
    }

    // Handlers selección
    function onSegClickSize(e){
      const btn = e.target.closest("#mwkSize button[data-value]");
      if (!btn) return;
      $$("#mwkSize .is-active").forEach(b => b.classList.remove("is-active"));
      btn.classList.add("is-active");
      updatePricePreview();
    }
    function onSegClickMilkSingle(e){
      const btn = e.target.closest("#mwkMilk button[data-value]");
      if (!btn) return;
      $$("#mwkMilk .is-active").forEach(b => b.classList.remove("is-active"));
      btn.classList.add("is-active");
    }
    function onSegClickMulti(e) {
      const btn = e.target.closest("#mwkMilk button[data-value]");
      if (!btn) return;
      btn.classList.toggle("is-active");
    }

    function getActiveSize() {
      const b = segSize.querySelector("button.is-active");
      if (!b) return null;
      return { id: Number(b.dataset.id), nombre: b.dataset.value, extra: Number(b.dataset.extra || 0) };
    }

    function updatePricePreview() {
      const size = getActiveSize();
      const total = (ctx?.priceBase || 0) + (size?.extra || 0);
      elPrice.textContent = money(total) + " MXN";
    }

    function renderSizes(tamanos) {
      segSize.innerHTML = "";
      (tamanos || []).forEach((t, i) => {
        const wrap = document.createElement("div");
        wrap.className = "opt";
        const b = document.createElement("button");
        b.type = "button";
        b.dataset.value = t.nombre;
        b.dataset.id = t.id;
        b.dataset.extra = t.precio_extra;
        b.textContent = t.nombre;
        const isDefault =
          norm(t.nombre) === "mediano" ||
          (i === 1 && !(tamanos || []).some(x => norm(x.nombre) === "mediano")) ||
          (i === 0 && (tamanos || []).length === 1);
        if (isDefault) b.classList.add("is-active");

        const small = document.createElement("small");
        small.className = "mwk-extra";
        small.textContent = t.precio_extra > 0 ? `+ ${money(t.precio_extra)}` : "sin extra";

        wrap.appendChild(b);
        wrap.appendChild(small);
        segSize.appendChild(wrap);
      });

      segSize.removeEventListener("click", onSegClickSize);
      segSize.addEventListener("click", onSegClickSize);
    }

    function setGroupLabel(container, labelText) {
      const labelEl = container.previousElementSibling;
      if (labelEl) labelEl.textContent = labelText || "Opciones";
    }

    function renderSingleGroup(container, group) {
      container.innerHTML = "";
      container.classList.remove("multi");
      container.dataset.groupKey = group.key || "leche";
      setGroupLabel(container, group.label || (group.key === "sabor" ? "Sabor" : "Leche"));

      (group.items || []).forEach((it, i) => {
        const b = document.createElement("button");
        b.type = "button";
        b.dataset.value = it.nombre;
        b.dataset.id = it.id;
        b.textContent = it.nombre;
        if (i === 0) b.classList.add("is-active");
        container.appendChild(b);
      });
      if (milkNote) milkNote.hidden = true;

      container.removeEventListener("click", onSegClickMilkSingle);
      container.removeEventListener("click", onSegClickMulti);
      container.addEventListener("click", onSegClickMilkSingle);
    }

    function renderMultiGroup(container, group) {
      container.innerHTML = "";
      container.classList.add("multi");
      container.dataset.groupKey = group.key || "verduras";
      setGroupLabel(container, group.label || "Verduras");

      (group.items || []).forEach((it) => {
        const b = document.createElement("button");
        b.type = "button";
        b.dataset.value = it.nombre;
        b.dataset.id = it.id;
        b.textContent = it.nombre;
        container.appendChild(b);
      });
      if (milkNote) milkNote.hidden = true;

      container.removeEventListener("click", onSegClickMilkSingle);
      container.removeEventListener("click", onSegClickMulti);
      container.addEventListener("click", onSegClickMulti);
    }

    function collectOptions() {
      const size = getActiveSize();
      const key = segMilk.dataset.groupKey || "";   // leche | verduras | sabor | none
      const options = { size };

      if (key === "none") return options;
      if (key === "verduras") {
        options.verduras = [...segMilk.querySelectorAll("button.is-active")].map((b) => ({
          id: Number(b.dataset.id), nombre: b.dataset.value
        }));
      } else if (key === "leche") {
        const b = segMilk.querySelector("button.is-active");
        if (b) options.leche = { id: Number(b.dataset.id), nombre: b.dataset.value };
      } else if (key === "sabor") {
        const b = segMilk.querySelector("button.is-active");
        if (b) options.sabor = { id: Number(b.dataset.id), nombre: b.dataset.value };
      }
      return options;
    }

    async function openModal(data) {
      lastFocus = document.activeElement instanceof HTMLElement ? document.activeElement : null;

      modal.removeAttribute("inert");
      modal.setAttribute("aria-hidden", "false");
      document.body.style.overflow = "hidden";

      ctx = { id: data.id, name: data.name, priceBase: Number(data.price || 0), foto: data.foto, cfg: null };
      elName.textContent = data.name;
      elImg.src = data.foto || IMG_PLACEHOLDER;
      elImg.alt = data.name;
      if (milkNote) milkNote.hidden = true;

      let cfg = await fetchCustom(data.id);

      if (!cfg || cfg.ok !== true) {
        cfg = {
          ok: true,
          categoriaTags: data.catTags || "",
          tamanos: DEFAULT_SIZES,
          groups: [ pickFallbackGroup(data.catTags, data.name) ]
        };
      } else if (!Array.isArray(cfg.groups) || !cfg.groups.length) {
        cfg.groups = [ pickFallbackGroup(cfg.categoriaTags || data.catTags, data.name) ];
      }

      ctx.cfg = cfg;

      renderSizes(Array.isArray(cfg.tamanos) && cfg.tamanos.length ? cfg.tamanos : DEFAULT_SIZES);

      // ---- Render grupo dinámico ----
      let g = (cfg.groups && cfg.groups[0]) || pickFallbackGroup(cfg.categoriaTags || data.catTags, data.name);

      const cats = norm(cfg.categoriaTags || data.catTags || "");
      const nm   = norm(data.name || "");

      const looksTea    = /\bte\b|\btes\b|tea/.test(cats) || /\bte\b|\btes\b|tea/.test(nm);
      const looksLimo   = /limonada|limonadas/.test(cats) || /limonada|limonadas/.test(nm);
      const looksSoda   = /refresco|refrescos|soda|gaseosa/.test(cats) || /refresco|coca|fanta|sprite|soda|gaseosa/.test(nm);
      const looksWater  = /\bagua\b/.test(cats) || /\bagua\b/.test(nm);
      const looksCoffee = /cafe|café|capuch?ino|latte|moch?a|moka|macchiato|espresso/.test(cats) ||
                          /cafe|café|capuch?ino|latte|moch?a|moka|macchiato|espresso/.test(nm);
      const looksFood   = /comida|alimento|ensalada|sandwich|s[aá]ndwich|torta|baguette|wrap|panini|hamburguesa|pizza|bagel|\bpan\b/
                          .test(cats) ||
                          /comida|alimento|ensalada|sandwich|s[aá]ndwich|torta|baguette|wrap|panini|hamburguesa|pizza|bagel|\bpan\b/
                          .test(nm);

      if (looksFood)        g = { key: "verduras", label: "Verduras", type: "multi",  items: DEFAULT_VERDURAS };
      else if (looksTea)    g = { key: "sabor",    label: "Sabor",    type: "single", items: DEFAULT_SABORES_TE };
      else if (looksLimo)   g = { key: "sabor",    label: "Sabor",    type: "single", items: DEFAULT_SABORES_LIMONADA };
      else if (looksSoda)   g = { key: "sabor",    label: "Sabor",    type: "single", items: DEFAULT_SABORES_REFRESCO };
      else if (looksWater)  g = { key: "none",     label: "",         type: "none",   items: [] };
      else if (looksCoffee) g = { key: "leche",    label: "Leche",    type: "single", items: DEFAULT_MILKS };

      // ⛔ Override por ID: NO mostrar leche (ni otro grupo)
      if (NO_MILK_IDS.has(Number(data.id))) {
        g = { key: "none", label: "", type: "none", items: [] };
      }

      if (g.type === "none") {
        const label = segMilk.previousElementSibling;
        segMilk.innerHTML = "";
        segMilk.dataset.groupKey = "none";
        segMilk.classList.remove("multi");
        if (label) label.style.display = "none";
        segMilk.style.display = "none";
      } else {
        const label = segMilk.previousElementSibling;
        if (label) label.style.display = "";
        segMilk.style.display = "";
        (g.type === "single") ? renderSingleGroup(segMilk, g) : renderMultiGroup(segMilk, g);
      }

      updatePricePreview();
      setTimeout(() => { btnConfirm?.focus(); }, 0);
    }

    function closeModal() {
      if (document.activeElement && modal.contains(document.activeElement)) {
        try { document.activeElement.blur(); } catch {}
      }
      modal.setAttribute("aria-hidden", "true");
      modal.setAttribute("inert", "");
      document.body.style.overflow = "";
      ctx = null;

      if (lastFocus && document.contains(lastFocus)) {
        try { lastFocus.focus(); } catch {}
      }
      lastFocus = null;
    }

    modal.addEventListener("click", (e) => { if (e.target.closest("[data-close]")) closeModal(); });
    document.addEventListener("keydown", (e) => {
      if (modal.getAttribute("aria-hidden") === "false" && e.key === "Escape") closeModal();
    });

    async function addToCart(payload) {
      try {
        const r = await fetch(CART_API, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(payload),
        });
        return await r.json();
      } catch {
        return { ok: false };
      }
    }

    document.addEventListener("click", (e) => {
      const btn = e.target.closest(".ts-cart");
      if (!btn) return;
      const card = btn.closest(".ts-card");
      if (!card) return;

      const id = card.dataset.id || "";
      const name = card.dataset.name || "Producto";
      const price = parseFloat(card.dataset.price || "0") || 0;
      const foto = card.dataset.foto || IMG_PLACEHOLDER;
      const cats = card.dataset.categoriaName || "";

      openModal({ id, name, price, foto, catTags: cats });
    });

    // Confirmar (incluye TAMAÑO en el nombre)
    btnConfirm.addEventListener("click", async () => {
      if (!ctx) return;

      const options = collectOptions();
      const precioTotal = (ctx.priceBase || 0) + (options.size?.extra || 0);

      const partes = [ctx.name];
      if (options.sabor?.nombre)       partes.push(options.sabor.nombre);
      else if (options.leche?.nombre)  partes.push(options.leche.nombre);
      else if (Array.isArray(options.verduras) && options.verduras.length)
        partes.push("(" + options.verduras.map(v => v.nombre).join(", ") + ")");

      if (options.size?.nombre) partes.push("— " + options.size.nombre);

      const displayName = partes.filter(Boolean).join(" ");

      const res = await addToCart({
        action: "add",
        id: ctx.id,
        qty: 1,
        nombre: displayName,
        precio: precioTotal,
        foto: ctx.foto,
        options
      });

      if (res?.ok) {
        closeModal();
        if (typeof window.__miniCartOpenAndLoad__ === "function") window.__miniCartOpenAndLoad__();
        else {
          const overlay = document.getElementById("mcOverlay");
          const drawer = document.getElementById("miniCart");
          if (overlay && drawer) { drawer.classList.add("is-open"); overlay.hidden = false; }
        }
      } else {
        alert(res?.error || "No se pudo añadir al carrito.");
      }
    });
  }

  // ============================
  // 4) Boot
  // ============================
  function boot() {
    initFiltro();
    initProductosAJAX();
    initMiniCart();
    initPersonalizador();
  }

  if (document.readyState !== "loading") boot();
  else document.addEventListener("DOMContentLoaded", boot);

  // ============================
  // CSS dinámico (bordes café + precios fuertes)
  // ============================
  (function ensurePriceCSS() {
    document.getElementById("price-pill-css")?.remove();

    const s = document.createElement("style");
    s.id = "price-pill-css";
    s.textContent = `
      :root{
        --coffee:#7a4b34;
        --coffee-700:#4b2e25;
        --latte:#f3e6d9;
        --latte-200:#ead9c9;
        --latte-300:#e4d0c0;
        --ring:rgba(122,75,52,.35);
        --text:#2b1c16;
        --text-inv:#fff;
      }

      #mwkSize{ display:flex; gap:12px; flex-wrap:wrap; align-items:flex-start; }
      #mwkSize .opt{ display:grid; grid-template-rows:auto auto; gap:6px; justify-items:center; }

      #mwkSize .opt > button{
        border:2px solid var(--coffee-700) !important;
        background:var(--latte) !important;
        color:var(--text) !important;
        padding:10px 14px; border-radius:14px; font-size:.95rem;
        transition:.15s ease; min-width:94px; cursor:pointer;
      }
      #mwkSize .opt > button:hover{ background:var(--latte-300) !important; border-color:var(--coffee-700) !important; }
      #mwkSize .opt > button.is-active{
        background:var(--coffee) !important; border-color:var(--coffee-700) !important;
        color:var(--text-inv) !important; box-shadow:0 0 0 3px var(--ring) !important;
      }

      .mwk-extra{
        font-size:.82rem; color:var(--coffee-700) !important; font-weight:700 !important;
        opacity:1 !important; line-height:1; display:block; text-align:center;
      }

      #mwkMilk{ display:grid; gap:10px; grid-template-columns:repeat(auto-fit, minmax(120px,1fr)); }
      #mwkMilk button{
        border:2px solid var(--coffee-700) !important; background:var(--latte) !important; color:var(--text) !important;
        border-radius:14px; transition:.15s ease; padding:10px 14px; cursor:pointer; font-size:.92rem;
      }
      #mwkMilk button:hover{ background:var(--latte-300) !important; border-color:var(--coffee-700) !important; }
      #mwkMilk button.is-active{
        background:var(--coffee) !important; border-color:var(--coffee-700) !important; color:var(--text-inv) !important;
        box-shadow:0 0 0 3px var(--ring) !important;
      }
      #mwkMilk.multi button{ outline:1px dashed rgba(75,46,37,.35); }

      #mwkConfirm{
        background:var(--latte) !important; color:var(--coffee-700) !important;
        border:2px solid var(--coffee-700) !important; border-radius:14px; font-weight:700;
        padding:10px 18px; transition:.15s ease; cursor:pointer;
      }
      #mwkConfirm:hover{ background:var(--latte-300) !important; box-shadow:0 0 0 3px var(--ring) !important; border-color:var(--coffee-700) !important; }

      #mwkCancel{
        background:rgba(255,255,255,0.05) !important; border:2px solid var(--coffee-700) !important;
        border-radius:14px; color:#fff; padding:10px 18px; cursor:pointer;
      }
      #mwkCancel:hover{ background:rgba(255,255,255,0.12) !important; }
    `;
    document.head.appendChild(s);
  })();

  // ============================
  // FONDO DEL PANEL (NO overlay)
  // ============================
  (function ensureModalPanelOnly(){
    document.getElementById("mwk-modal-bg")?.remove();
    document.getElementById("mwk-modal-bg-fix")?.remove();

    const s = document.createElement("style");
    s.id = "mwk-modal-bg-fix";
    s.textContent = `
      #mwkModal > .mwk-modal,
      #mwkModal > .mwk-dialog,
      #mwkModal > .modal-card,
      #mwkModal > .modal-panel,
      #mwkModal > .mwk-content,
      #mwkModal > .content,
      #mwkModal > .card,
      #mwkModal > div:not([class*="overlay"]):not([class*="backdrop"]):not([id*="overlay"]){
        background:#f3e6d9 !important;
        color:#2b1c16 !important;
        border-radius:18px !important;
        border:1px solid rgba(122,75,52,.25) !important;
      }
      #mwkModal .mwk-label, #mwkModal h3{ color:#4b2e25 !important; }
      #mwkModal hr{ border-color:rgba(122,75,52,.25) !important; }
      #mwkModal [data-close]{ color:#4b2e25 !important; background:transparent !important; }
    `;
    document.head.appendChild(s);
  })();

})();
