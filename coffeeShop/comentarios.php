<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Coffee-Shop</title>
    <link rel="stylesheet" href="css/Style.css" />
    <link rel="icon" href="/assest/logotipocafes.png" />
    <link rel="stylesheet" href="css/comentarios.css" />
  </head>
  <body>
    <footer class="site-footer">
      <div class="footer-container">
        <!-- LOGO -->
        <div class="footer-logo">
          <a href="index.php">
            <img src="assest/logocafe.png" alt="Coffee Shop logo" />
          </a>
          <span>COFFEE SHOP</span>
        </div>

       <!-- MENÚ -->
        <nav class="footer-menu">
          <a href="index.php">Inicio</a>
          <a href="catalogo.php">Catálogo</a>
          <a href="comentarios.php">Comentarios</a>
          <a href="acercade.php">Acerca de</a>
        </nav>

        <!-- ACCIONES -->
        <div class="footer-actions">
          <a href="#" class="icon-btn" aria-label="Cuenta">👤</a>
          <a href="#" class="icon-btn" aria-label="Carrito">🛒</a>
          <span class="lang">ESP | ING</span>
        </div>
      </div>
    </footer>

    <!--seccion comentarios-->
<section class="panel-comentarios">
  <div class="panel">
    <h2>Calificacion de nuestros Servicios</h2>

    <!-- Estrellas + contador -->
    <div class="fila-top">
      <div class="estrellas" id="estrellas" aria-label="Calificación">
        <span class="star" data-val="1">☆</span>
        <span class="star" data-val="2">☆</span>
        <span class="star" data-val="3">☆</span>
        <span class="star" data-val="4">☆</span>
        <span class="star" data-val="5">☆</span>
      </div>
      <div class="contador"><span id="score">0</span>/5</div>
    </div>

    <!-- Caja de comentario -->
    <div class="caja-comentario">
      <textarea id="comentario" placeholder="Escribe un comentario..."></textarea>
      <img class="ico-bean" src="assest/iconcofe.png" alt="bean coffe" aria-hidden="true" />
      <span class="ico-emoji" aria-hidden="true">☺</span>
    </div>

    <!-- Subir foto + Etiquetas + acciones -->
    <div class="fila-subida">
      <!-- 1) Subir Foto -->
      <label class="subir-foto" for="fileInput">
        <span class="ico-cam">
          <svg viewBox="0 0 24 24" width="38" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 8h16a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2z"/>
            <path d="M9 8l1.5-2h3L15 8"/>
            <circle cx="12" cy="13" r="3.5"/>
          </svg>
        </span>
        <span>Subir Foto</span>
      </label>
      <input id="fileInput" type="file" accept="image/*" hidden />

      <!-- 2) Botón Etiquetas -->
      <button type="button" class="btn-etiquetas" id="toggleTags">🏷 Etiquetas</button>

      <!-- 3) Acciones -->
      <div class="acciones">
        <button type="button" class="btn btn-outline" id="btnBorrar">Borrar</button>
        <button type="button" class="btn btn-solid" id="btnSubir">Subir</button>
      </div>

      <!-- Panel de etiquetas (oculto por defecto) -->
      <div class="tags-panel" id="tagsPanel" aria-label="Etiquetas disponibles">
        <div class="tags-grid" id="tagsGrid">
          <!-- 50 chips -->
          <span class="tag-chip">#Coffee</span>
          <span class="tag-chip">#CaféDeAltura</span>
          <span class="tag-chip">#Espresso</span>
          <span class="tag-chip">#Americano</span>
          <span class="tag-chip">#Capuchino</span>
          <span class="tag-chip">#Latte</span>
          <span class="tag-chip">#Mocha</span>
          <span class="tag-chip">#Macchiato</span>
          <span class="tag-chip">#FlatWhite</span>
          <span class="tag-chip">#CaféDeOlla</span>
          <span class="tag-chip">#ColdBrew</span>
          <span class="tag-chip">#Frappe</span>
          <span class="tag-chip">#Affogato</span>
          <span class="tag-chip">#V60</span>
          <span class="tag-chip">#Chemex</span>
          <span class="tag-chip">#PrensaFrancesa</span>
          <span class="tag-chip">#Descafeinado</span>
          <span class="tag-chip">#LecheEntera</span>
          <span class="tag-chip">#LecheAvena</span>
          <span class="tag-chip">#LecheAlmendra</span>
          <span class="tag-chip">#SinAzúcar</span>
          <span class="tag-chip">#ConCanela</span>
          <span class="tag-chip">#Caramelo</span>
          <span class="tag-chip">#Vainilla</span>
          <span class="tag-chip">#Chocolate</span>
          <span class="tag-chip">#Matcha</span>
          <span class="tag-chip">#Chai</span>
          <span class="tag-chip">#PanDulce</span>
          <span class="tag-chip">#Galletas</span>
          <span class="tag-chip">#Croissant</span>
          <span class="tag-chip">#Bagel</span>
          <span class="tag-chip">#Sandwich</span>
          <span class="tag-chip">#Desayuno</span>
          <span class="tag-chip">#Snack</span>
          <span class="tag-chip">#TakeAway</span>
          <span class="tag-chip">#Delivery</span>
          <span class="tag-chip">#BuenServicio</span>
          <span class="tag-chip">#Rápido</span>
          <span class="tag-chip">#Amable</span>
          <span class="tag-chip">#Ambiente</span>
          <span class="tag-chip">#Relax</span>
          <span class="tag-chip">#WorkFriendly</span>
          <span class="tag-chip">#WiFi</span>
          <span class="tag-chip">#PetFriendly</span>
          <span class="tag-chip">#MúsicaSuave</span>
          <span class="tag-chip">#Limpio</span>
          <span class="tag-chip">#Recomendado</span>
          <span class="tag-chip">#PrecioJusto</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- JS mínimo -->
<script>
  // estrellas
  const stars = document.querySelectorAll('.star');
  const scoreEl = document.getElementById('score');
  stars.forEach(s=>{
    s.addEventListener('click', ()=>{
      const val = +s.dataset.val;
      stars.forEach(it=>it.textContent = (+it.dataset.val <= val) ? '★' : '☆');
      scoreEl.textContent = val;
    });
  });

  // abrir/cerrar panel de etiquetas
  const toggleBtn = document.getElementById('toggleTags');
  const panel = document.getElementById('tagsPanel');
  toggleBtn.addEventListener('click', ()=> panel.classList.toggle('open'));

  // seleccionar chips
  document.getElementById('tagsGrid').addEventListener('click', (e)=>{
    const chip = e.target.closest('.tag-chip');
    if(!chip) return;
    chip.classList.toggle('is-active');
  });

  // borrar: limpia comentario, estrellas, chips y oculta panel
  document.getElementById('btnBorrar').addEventListener('click', ()=>{
    document.getElementById('comentario').value = '';
    stars.forEach(it=>it.textContent='☆');
    scoreEl.textContent = '0';
    document.querySelectorAll('.tag-chip.is-active').forEach(c=>c.classList.remove('is-active'));
    panel.classList.remove('open');
  });
</script>

    <!-- ============ RATING / OPINIONES ============ -->
    <section class="rating" aria-labelledby="rating-title">
      <div class="rating__wrap">
        <!-- Columna izquierda: promedio -->
        <div class="rating__summary">
          <h2 id="rating-title" class="sr-only">Opiniones de clientes</h2>

          <div class="rating__score">4.7</div>
          <!-- estrellas del promedio (0–5 con decimales) -->
          <div
            class="stars stars--avg"
            style="--value: 4.1"
            aria-label="4.7 de 5"
          ></div>

          <p class="rating__count"><strong>405 opiniones</strong></p>

          <!-- imagen decorativa -->
          <img
            class="rating__bag"
            src="assest/sacodecafe.png"
            alt=""
            aria-hidden="true"
          />
        </div>

        <!-- Columna derecha: desglose por estrellas -->
        <div
          class="rating__breakdown"
          role="list"
          aria-label="Desglose por estrellas"
        >
          <!-- Ajusta --p (0–100%) según tus datos -->
          <div class="row" role="listitem" aria-label="5 estrellas">
            <div class="stars" style="--value: 5" aria-hidden="true"></div>
            <div class="bar" aria-hidden="true">
              <span style="--p: 92%"></span>
            </div>
          </div>
          <div class="row" role="listitem" aria-label="4 estrellas">
            <div class="stars" style="--value: 4"></div>
            <div class="bar"><span style="--p: 78%"></span></div>
          </div>
          <div class="row" role="listitem" aria-label="3 estrellas">
            <div class="stars" style="--value: 3"></div>
            <div class="bar"><span style="--p: 56%"></span></div>
          </div>
          <div class="row" role="listitem" aria-label="2 estrellas">
            <div class="stars" style="--value: 2"></div>
            <div class="bar"><span style="--p: 32%"></span></div>
          </div>
          <div class="row" role="listitem" aria-label="1 estrella">
            <div class="stars" style="--value: 1"></div>
            <div class="bar"><span style="--p: 18%"></span></div>
          </div>
        </div>
      </div>
    </section>

    <!-- ============ OPINIONES DE LOS CLIENTES ============ -->
    <!-- ======= COMENTARIOS (HTML) ======= -->
    <section class="reviews" aria-labelledby="reviews-title">
      <div class="reviews__wrap">
        <!-- Encabezado -->
        <header class="reviews__header">
          <h2 id="reviews-title">Comentarios</h2>

          <div class="reviews__controls">
            <div class="pill-group">
              <button class="pill pill--ghost">Más recientes</button>
              <button class="pill pill--ghost">Con foto</button>
            </div>
          </div>
        </header>

        <!-- Sub-controles -->
        <div class="reviews__subhead">
          <span class="score-pill"> 4.7 </span>

          <div class="select-pill" role="listbox" aria-label="Clasificación">
            <span>Clasificación</span>
            <svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true">
              <path fill="currentColor" d="M7 10l5 5 5-5z" />
            </svg>
          </div>
        </div>

        <!-- Grid de tarjetas -->
        <div class="reviews__grid">
          <!-- ===== Card 1 ===== -->
          <article class="card">
            <header class="card__head">
              <img
                class="avatar"
                src="assest/icon-avatar.webp"
                alt="Ángel Gudiño"
              />
              <div class="meta">
                <div class="name">Ángel Gudiño</div>
                <div class="time">2 semanas ago</div>
              </div>
              <div class="stars" aria-label="5 estrellas">
                <!-- 5 estrellas -->
                  <path
                    d="M12 2.2l2.9 5.9 6.5 1-4.7 4.6 1.1 6.5L12 17.8 6.2 20.2l1.1-6.5L2.6 9.1l6.5-1L12 2.2z"
                  />
                </svg>
              </div>
            </header>

            <p class="card__text">
              Para pasar un rato agradable en familia o en trabajos etc:3
            </p>

            <figure class="card__media">
              <img
                src="assest/opinion2.jfif"
                alt="Mesa con bebidas y postres"
              />
            </figure>

            <div class="chips">
              <span class="chip">#Coffee</span>
              <span class="chip">Relaxing</span>
            </div>

            <footer class="card__footer">
              <button class="act">
                <svg viewBox="0 0 24 24">
                  <path
                    d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 6 4 4 6.5 4c1.74 0 3.41 1 4.22 2.44C11.09 5 12.76 4 14.5 4 17 4 19 6 19 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"
                  />
                </svg>
                Me gusta
              </button>
              <button class="act">
                <svg viewBox="0 0 24 24">
                  <path
                    d="M21 6h-2v9H7v2a1 1 0 0 0 1.7.7L12.4 15H21a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1zM17 11V3a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v12l4-4h10a1 1 0 0 0 1-1z"
                  />
                </svg>
                Responder
              </button>
              <button class="act">Rep</button>
            </footer>
          </article>

          <!-- ===== Card 2 ===== -->
          <article class="card">
            <header class="card__head">
              <img
                class="avatar"
                src="assest/icon-avatar2.jpg"
                alt="Manuel Andrade"
              />
              <div class="meta">
                <div class="name">Faraon Love Shady</div>
                <div class="time">2 weeks ago</div>
              </div>
              <div class="stars" aria-label="5 estrellas">
                  <path
                    d="M12 2.2l2.9 5.9 6.5 1-4.7 4.6 1.1 6.5L12 17.8 6.2 20.2l1.1-6.5L2.6 9.1l6.5-1L12 2.2z"
                  />
                </svg>
              </div>
            </header>

            <p class="card__text">
              Instrucciones muy limpias amable y muy atentos con el cliente
            </p>

            <figure class="card__media">
              <img src="assest/opinion3.jpg" alt="Barista preparando café" />
            </figure>

            <div class="chips">
              <span class="chip">#Friendly</span>
              <span class="chip">Great Service</span>
            </div>

            <footer class="card__footer">
              <button class="act">
                <svg viewBox="0 0 24 24">
                  <path
                    d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 6 4 4 6.5 4c1.74 0 3.41 1 4.22 2.44C11.09 5 12.76 4 14.5 4 17 4 19 6 19 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"
                  />
                </svg>
                Me gusta
              </button>
              <button class="act">
                <svg viewBox="0 0 24 24">
                  <path
                    d="M21 6h-2v9H7v2a1 1 0 0 0 1.7.7L12.4 15H21a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1zM17 11V3a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v12l4-4h10a1 1 0 0 0 1-1z"
                  />
                </svg>
                Responder
              </button>
              <button class="act">Rep</button>
            </footer>
          </article>

          <!-- ===== Card 3 (igual a 1) ===== -->
          <article class="card">
            <header class="card__head">
              <img
                class="avatar"
                src="assest/icon-avatar3.webp"
                alt="Ángel Gudiño"
              />
              <div class="meta">
                <div class="name">Ángel Gudiño</div>
                <div class="time">4 semanas ago</div>
              </div>
              <div class="stars" aria-label="5 estrellas">
                  <path
                    d="M12 2.2l2.9 5.9 6.5 1-4.7 4.6 1.1 6.5L12 17.8 6.2 20.2l1.1-6.5L2.6 9.1l6.5-1L12 2.2z"
                  />
                </svg>
              </div>
            </header>

            <p class="card__text">
              Para pasar un rato agradable en familia o en trabajos etc:3
            </p>

            <figure class="card__media">
              <img
                src="assest/varistacoffee.jpeg"
                alt="Tazas con arte latte"
              />
            </figure>

            <div class="chips">
              <span class="chip">#Coffee</span>
              <span class="chip">Relaxing</span>
            </div>

            <footer class="card__footer">
              <button class="act">
                <svg viewBox="0 0 24 24">
                  <path
                    d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 6 4 4 6.5 4c1.74 0 3.41 1 4.22 2.44C11.09 5 12.76 4 14.5 4 17 4 19 6 19 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"
                  />
                </svg>
                Me gusta
              </button>
              <button class="act">
                <svg viewBox="0 0 24 24">
                  <path
                    d="M21 6h-2v9H7v2a1 1 0 0 0 1.7.7L12.4 15H21a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1zM17 11V3a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v12l4-4h10a1 1 0 0 0 1-1z"
                  />
                </svg>
                Responder
              </button>
              <button class="act">Rep</button>
            </footer>
          </article>

          <!-- ===== Card 4 (igual a 2) ===== -->
          <article class="card">
            <header class="card__head">
              <img
                class="avatar"
                src="assest/avatar-icon4.jpg"
                alt="Manuel Andrade"
              />
              <div class="meta">
                <div class="name">Manuel Andrade</div>
                <div class="time">4 weeks ago</div>
              </div>
              <div class="stars" aria-label="5 estrellas">
                  <path
                    d="M12 2.2l2.9 5.9 6.5 1-4.7 4.6 1.1 6.5L12 17.8 6.2 20.2l1.1-6.5L2.6 9.1l6.5-1L12 2.2z"
                  />
                </svg>
              </div>
            </header>

            <p class="card__text">
              Instrucciones muy limpias amable y muy atentos con el cliente
            </p>

            <figure class="card__media">
              <img src="assest/negroscofee.jpeg" alt="Cliente en cafetería" />
            </figure>

            <div class="chips">
              <span class="chip">#Friendly</span>
              <span class="chip">Great Service</span>
            </div>

            <footer class="card__footer">
              <button class="act">
                <svg viewBox="0 0 24 24">
                  <path
                    d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 6 4 4 6.5 4c1.74 0 3.41 1 4.22 2.44C11.09 5 12.76 4 14.5 4 17 4 19 6 19 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"
                  />
                </svg>
                Me gusta
              </button>
              <button class="act">
                <svg viewBox="0 0 24 24">
                  <path
                    d="M21 6h-2v9H7v2a1 1 0 0 0 1.7.7L12.4 15H21a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1zM17 11V3a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v12l4-4h10a1 1 0 0 0 1-1z"
                  />
                </svg>
                Responder
              </button>
              <button class="act">Rep</button>
            </footer>
          </article>
        </div>
      </div>
    </section>
    <!-- ================== SEPARADOR CAFÉ ================== -->
<div class="coffee-separator">
  <span class="line"></span>
  <img src="assest/tazaicon.png" alt="Icono café" class="coffee-icon" />
  <span class="line"></span>
</div>

        <!-- ===================== FOOTER ===================== -->
<footer class="cs-footer" aria-labelledby="footer-title">
  <h2 id="footer-title" class="sr-only">Información del sitio</h2>

  <div class="cs-footer__wrap">
    <!-- Marca -->
    <aside class="cs-brand">
      <img class="cs-brand__logo" src="assest/logocafe.png" alt="Coffee Shop">
    </aside>

    <!-- Tarjetas -->
    <div class="cs-cards">
      <!-- Newsletter / Pago -->
      <section class="cs-card">
  <h3>News & updates</h3>
  <form class="cs-news" action="#" method="post">
          <label class="sr-only" for="newsletter">Correo electrónico</label>
          <input id="newsletter" type="email" placeholder="correo electrónico" required>
          <button type="submit" class="cs-btn">Suscribir</button>
        </form>
      </section>
      <!-- Contacto -->
      <section class="cs-card">
        <h3>Contáctanos</h3>
        <ul class="cs-list">
          <li>
            <span class="cs-ico" aria-hidden="true">
              <!-- mail -->
              <svg viewBox="0 0 24 24"><path d="M20 4H4a2 2 0 0 0-2 2v12a2
              2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2
              0 0 0-2-2Zm0 4-8 5L4 8V6l8 5 8-5Z" fill="currentColor"/></svg>
            </span>
            <a href="mailto:coffee_shop@gmail.com">coffee_shop@gmail.com</a>
          </li>
          <li>
            <span class="cs-ico" aria-hidden="true">
              <!-- phone -->
              <svg viewBox="0 0 24 24"><path d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2a1.5 1.5 0 0 1 1.6-.36 12.3 12.3 0 0 0 3.8.6 1.5 1.5 0 0 1 1.5 1.5V20a1.5 1.5 0 0 1-1.5 1.5A18.5 18.5 0 0 1 3 7.5 1.5 1.5 0 0 1 4.5 6H7a1.5 1.5 0 0 1 1.5 1.5c0 1.3.2 2.6.6 3.8a1.5 1.5 0 0 1-.36 1.6Z" fill="currentColor"/></svg>
            </span>
            <a href="tel:+523141495067">+52 314 149 5067</a>
          </li>
          <li>
            <span class="cs-ico" aria-hidden="true">
              <!-- pin -->
              <svg viewBox="0 0 24 24"><path d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7Zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5Z" fill="currentColor"/></svg>
            </span>
            <span>Manzanillo, Col. • Campus Naranjo</span>
          </li>
        </ul>
      </section>

      <!-- Enlaces -->
      <nav class="cs-card" aria-label="Conoce más">
        <h3>Conoce más</h3>
        <ul class="cs-links">
          <li><a href="index.php"><span class="chev" aria-hidden="true">›</span> Inicio</a></li>
          <li><a href="catalogo.php"><span class="chev" aria-hidden="true">›</span> Catálogo</a></li>
          <li><a href="comentarios.php"><span class="chev" aria-hidden="true">›</span> Comentarios</a></li>
          <li><a href="acercade.php"><span class="chev" aria-hidden="true">›</span> Acerca de</a></li>
        </ul>
      </nav>

      <!-- Redes + Horarios -->
      <section class="cs-card">
        <h3>Síguenos</h3>
        <div class="cs-social">
          <a href="https://facebook.com" aria-label="Facebook" class="circle">
            <svg viewBox="0 0 24 24"><path d="M13 22v-9h3l1-4h-4V7a1 1 0 0 1 1-1h3V2h-3a5 5 0 0 0-5 5v2H6v4h3v9h4Z" fill="currentColor"/></svg>
          </a>
          <a href="https://instagram.com" aria-label="Instagram" class="circle">
            <svg viewBox="0 0 24 24"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5Zm5 5a5 5 0 1 0 0 10 5 5 0 0 0 0-10Zm6.5-.9a1.1 1.1 0 1 0 0 2.2 1.1 1.1 0 0 0 0-2.2Z" fill="currentColor"/></svg>
          </a>
        </div>

        <div class="cs-hours">
          <h4>Horarios</h4>
          <p>Lun–Vier: 9:00–21:00</p>
          <p>Sab–Dom: 10:00–20:00</p>
        </div>
      </section>

    </div>

    <!-- Línea inferior -->
    <div class="cs-bottom">
      <span class="cs-line"></span>
      <span class="cs-bean" aria-hidden="true">
        <img src="assest/iconcofe.png" alt="icono café" style="width:150px; height:32px; object-fit:contain;" />
      </span>
      <span class="cs-line"></span>
    </div>

    <div class="cs-legal">
  </body>
</html>
