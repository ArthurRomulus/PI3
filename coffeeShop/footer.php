    <!-- ===================== FOOTER ===================== -->
<footer class="cs-footer" aria-labelledby="footer-title">
  <h2 id="footer-title" class="sr-only" data-translate="Información del sitio">Información del sitio</h2>

  <div class="cs-footer__wrap">
    <!-- Marca -->
    <aside class="cs-brand">
      <img class="cs-brand__logo" src="../../Images/logo.png" alt="Coffee Shop">
    </aside>

    <!-- Tarjetas -->
    <div class="cs-cards">
      <!-- Newsletter / Pago -->
      <section class="cs-card">
  <h3 data-translate="Noticias y actualizaciones">Noticias y actualizaciones</h3>
  <form class="cs-news" action="#" method="post">
          <label class="sr-only" for="newsletter" data-translate="Correo electrónico">Correo electrónico</label>
          <input id="newsletter" type="email" placeholder="correo electrónico" required data-translate="Correo electrónico"/>
          <button type="submit" class="cs-btn" data-translate="Suscribir">Suscribir</button>
        </form>
      </section>
      <!-- Contacto -->
      <section class="cs-card">
        <h3 data-translate="Contáctanos">Contáctanos</h3>
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
        <h3 data-translate="Conoce más">Conoce más</h3>
        <ul class="cs-links">
          <li><a href="../inicio/index.php" data-translate="Inicio"><span class="chev" aria-hidden="true">›</span> Inicio</a></li>
          <li><a href="../catalogo/catalogo.php" data-translate="Catálogo"><span class="chev" aria-hidden="true">›</span> Catálogo</a></li>
          <li><a href="../comentarios/comentarios.php" data-translate="Comentarios"><span class="chev" aria-hidden="true">›</span> Comentarios</a></li>
          <li><a href="../acercade/acercade.php" data-translate="Acerca de"><span class="chev" aria-hidden="true">›</span> Acerca de</a></li>
        </ul>
      </nav>

      <!-- Redes + Horarios -->
      <section class="cs-card">
        <h3 data-translate="Síguenos">Síguenos</h3>
        <div class="cs-social">
          <a href="https://facebook.com" aria-label="Facebook" class="circle">
            <svg viewBox="0 0 24 24"><path d="M13 22v-9h3l1-4h-4V7a1 1 0 0 1 1-1h3V2h-3a5 5 0 0 0-5 5v2H6v4h3v9h4Z" fill="currentColor"/></svg>
          </a>
          <a href="https://instagram.com" aria-label="Instagram" class="circle">
            <svg viewBox="0 0 24 24"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5Zm5 5a5 5 0 1 0 0 10 5 5 0 0 0 0-10Zm6.5-.9a1.1 1.1 0 1 0 0 2.2 1.1 1.1 0 0 0 0-2.2Z" fill="currentColor"/></svg>
          </a>
        </div>

        <div class="cs-hours">
          <h4 data-translate="Horarios">Horarios</h4>
          <p>Lun–Vier: 9:00–21:00</p>
          <p>Sab–Dom: 10:00–20:00</p>
        </div>
      </section>

    </div>

    <!-- Línea inferior -->
    <div class="cs-bottom">
      <span class="cs-line"></span>
      <span class="cs-bean" aria-hidden="true">
        <img src="../../images/iconcofe.png" alt="icono café" style="width:32px; height:32px; object-fit:contain;" />
      </span>
      <span class="cs-line"></span>
    </div>

    <style>
      /*FOOOOTTTEEERRR*/
/* ===================== FOOTER (CSS) ===================== */
:root{
  --coffee:#531607;
  --bg-a:#d6b6a1;
  --bg-b:#f1e4db;
  --card:#f4ebe6;
  --ink:#3d2e27;
  --muted:#7b645b;
  --stroke:#e5d6cb;
  --shadow:0 14px 30px rgba(0,0,0,.12);
  --radius:22px;
}

.cs-footer{
  background-color: #DCC0B9;/*color pqara el foooter*/
  padding:24px 16px 18px;
  color:var(--ink);
}
.cs-footer__wrap{
  width: 98%;
  background: rgba(255,255,255,.22);
  border-radius:28px;
  padding:3px 18px 8px;
  box-shadow: inset 0 1px 0 rgba(255,255,255,.35);
}

/* Marca a la izquierda */
.cs-brand{
  display:flex; align-items:center; gap:14px;
  margin:60px 0 -190px 0;
}
.cs-brand__logo{
  width:200px; height:auto; object-fit:contain;
  filter: drop-shadow(0 6px 16px rgba(0,0,0,.18));
}

/* Grid de tarjetas */
.cs-cards{
  display:grid;
  grid-template-columns: repeat(4, minmax(0,1fr));
  gap:18px;
  margin-left: 200px;
}

.cs-card{
  background: var(--card);
  border-radius: var(--radius);
  padding:18px 18px 16px;
  box-shadow: var(--shadow);
  position:relative;
}
.cs-card h3{
  margin:0 0 12px;
  font-size:1.05rem;
  color:var(--ink);
  font-weight:800;
}

/* Items de lista */
.cs-list, .cs-links{
  list-style:none; margin:0; padding:0; display:grid; gap:10px;
}
.cs-list li, .cs-links li{ display:flex; align-items:center; gap:10px; }
.cs-ico{
  width:22px; height:22px; color:var(--coffee); display:inline-flex;
}
.cs-ico svg{ width:100%; height:100%; }

.cs-links a{
  color:var(--ink); text-decoration:none; font-weight:700;
}
.cs-links a:hover{ text-decoration:underline; }
.chev{ color:var(--coffee); margin-right:6px; font-weight:900; }

/* Redes y horarios */
.cs-social{ display:flex; gap:10px; margin-bottom:10px; }
.circle{
  width:42px; height:42px; border-radius:50%;
  background:#f0e4db; color:var(--coffee);
  display:grid; place-items:center; text-decoration:none;
  box-shadow: inset 0 0 0 2px var(--stroke);
}
.circle svg{ width:22px; height:22px; }

.cs-hours h4{ margin:0 0 6px; color:var(--ink); font-weight:800; }
.cs-hours p{ margin:0; color:var(--muted); font-weight:700; }

/* Newsletter */
.cs-news{ display:grid; gap:10px; }
.cs-news input{
  height:38px; border-radius:12px;
  border:2px solid var(--stroke);
  background:#000000; padding:0 12px; color:var(--ink);
}
.cs-btn{
  height:42px; border:none; border-radius:14px;
  background:var(--coffee); color:#fff; font-weight:800; cursor:pointer;
  box-shadow:0 8px 16px rgba(0,0,0,.16);
}
.cs-pay{
  display:flex; align-items:center; gap:10px; margin-top:8px; color:var(--muted); font-weight:800;
}
.cs-pay .bean{ width:26px; height:26px; color:var(--coffee); display:inline-flex; }
.cs-pay .bean svg{ width:100%; height:100%; }

/* Línea inferior con grano al centro */
.cs-bottom{
  display:flex; align-items:center; gap:12px; margin:18px 6px 8px;
}
.cs-line{ flex:1; height:3px; background:#47312920; border-radius:999px; }
.cs-bean{ width:24px; height:24px; color:var(--coffee); display:inline-flex; }
.cs-bean svg{ width:100%; height:100%; }

/* Legal */
.cs-legal{
  display:flex; justify-content:space-between; align-items:center;
  gap:10px; padding:4px 6px 10px;
}
.cs-copy{ margin:0; color:#3d2e27; font-weight:700; }
.cs-legal__links{ display:flex; align-items:center; gap:12px; }
.cs-legal__links a{ color:#3d2e27; text-decoration:none; font-weight:700; }
.cs-legal__links a:hover{ text-decoration:underline; }
.sep{ color:#886e63; }

</style>
  </div>
