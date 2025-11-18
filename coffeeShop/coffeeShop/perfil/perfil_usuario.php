<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// bloquear si no está logueado
if (empty($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: /PI3/General/login.php");
    exit;
}
$usuarioLogueado = isset($_SESSION['logueado']) && $_SESSION['logueado'] === true;

// DATOS DE SESIÓN (que ya guardamos en login.php)
$nombre        = $_SESSION['username']        ?? 'Usuario';
$email         = $_SESSION['email']           ?? '—';
$avatar        = $_SESSION['profilescreen']   ?? null;
$telefono      = $_SESSION['telefono']        ?? '—';
$fechaNacRaw   = $_SESSION['fecha_nac']       ?? '';
$zonaHoraria   = $_SESSION['zona_horaria']    ?? '(UTC -06:00) Guadalajara, CDMX';

// formatear fecha de nacimiento a dd/mm/YYYY si viene tipo 2025-10-31
$fechaNacBonita = '—';
if (!empty($fechaNacRaw) && $fechaNacRaw !== '0000-00-00') {
    $ts = strtotime($fechaNacRaw);
    if ($ts !== false) {
        $fechaNacBonita = date("d/m/Y", $ts);
    }
}

// "Miembro desde": si quieres algo real, puedes guardarlo en la BD (ej: created_at).
// Por ahora lo dejamos fijo o en blanco elegante:
$miembroDesde = '—';
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Perfil — Coffee Shop</title>
    <link rel="stylesheet" href="perfil.css" />
    <link rel="stylesheet" href="../general.css" />
    
  </head>
  <body>
    
    <?php include "../nav_bar.php"; ?>
    <div class="shell">
      <div class="app">
        <!-- SIDEBAR -->
        <aside class="sidebar">
          <div class="brand">
            <?php if (!empty($avatar)): ?>
              <img
                class="avatar"
                src="<?php echo htmlspecialchars($avatar); ?>"
                alt="Avatar de <?php echo htmlspecialchars($nombre); ?>"
              />
            <?php else: ?>
              <img
                class="avatar"
                src="https://ui-avatars.com/api/?name=<?php echo urlencode($nombre); ?>&background=DCC0B9&color=531607"
                alt="Avatar"
              />
            <?php endif; ?>
          </div>

          <nav class="nav">
            <a class="active" href="perfil_usuario.php">
              <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
              >
                <circle cx="12" cy="7" r="4" />
                <path d="M5.5 21a6.5 6.5 0 0 1 13 0" />
              </svg>
              <span data-translate="Perfil">Perfil</span>
            </a>

            <a href="editar_perfil.php">
              <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
              >
                <circle cx="12" cy="12" r="10" />
                <path d="M7 12h10M7 8h4M7 16h6" />
              </svg>
              <span data-translate="Editar perfil">Editar perfil</span>
            </a>

            <a href="cambiar_pass.php">
              <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
              >
                <path
                  d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"
                />
              </svg>
              <span data-translate="Cambiar contraseña">Cambiar contraseña</span>
            </a>

            <a href="historial_compras.php">
              <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
              >
                <rect x="3" y="4" width="18" height="16" rx="2" />
                <path d="M7 8h10M7 12h10M7 16h6" />
              </svg>
              <span data-translate="Historial de Compras">Historial de Compras</span>
            </a>
          </nav>

          <div class="sidebar-bottom">
            <img
              class="sidebar-logo"
              src="../../images/logo.png"
              alt="Coffee Shop"
            />
          </div>
        </aside>

        <!-- MAIN -->
        <main class="main">
          <div class="panel">
            <div class="inner">
              <h1>¡Hola, <?php echo htmlspecialchars($nombre); ?>!</h1>
              <p class="hello" data-translate="Bienvenido a tu perfil. Aquí puedes consultar tu información
                personal y actividad dentro del sistema Coffee-Shop ☕">
                Bienvenido a tu perfil. Aquí puedes consultar tu información
                personal y actividad dentro del sistema Coffee-Shop ☕
              </p>

              <div class="content-grid">
                <div class="col-left">
                  <!-- Tarjeta principal -->
                  <div class="card">
                    <div class="body">
                      <div class="profile-mini">
                        <?php if (!empty($avatar)): ?>
                          <img
                            src="<?php echo htmlspecialchars($avatar); ?>"
                            alt="Foto de perfil"
                          />
                        <?php else: ?>
                          <img
                            src="https://ui-avatars.com/api/?name=<?php echo urlencode($nombre); ?>&background=DCC0B9&color=531607"
                            alt="Foto de perfil"
                          />
                        <?php endif; ?>
                        <div>
                          <div class="name">
                            <?php echo htmlspecialchars($nombre); ?>
                          </div>
                          <div class="meta">
                            <span class="dot"></span> <span data-translate="Tu cuenta Coffee-Shop">Tu cuenta Coffee-Shop</span>
                          </div>
                        </div>
                      </div>

                      <hr
                        style="
                          border: 1px solid var(--coffee);
                          opacity: 0.3;
                          margin: 16px 0;
                        "
                      />

                      <dl class="data">
                        <dt data-translate="Correo electrónico">Correo electrónico</dt>
                        <dd><?php echo htmlspecialchars($email); ?></dd>

                        <dt data-translate="Teléfono">Teléfono</dt>
                        <dd><?php echo htmlspecialchars($telefono ?: '—'); ?></dd>

                        <dt data-translate="Fecha de nacimiento">Fecha de nacimiento</dt>
                        <dd><?php echo htmlspecialchars($fechaNacBonita); ?></dd>

                        <dt data-translate="Miembro desde">Miembro desde</dt>
                        <dd><?php echo htmlspecialchars($miembroDesde); ?></dd>

                        <dt data-translate="Zona horaria">Zona horaria</dt>
                        <dd><?php echo htmlspecialchars($zonaHoraria); ?></dd>
                      </dl>
                    </div>
                  </div>
                </div>

                <!-- Columna derecha -->
                <div class="col-right">
                  <div class="card">
                    <div class="body">
                      <h2 data-translate="Actividad reciente">Actividad reciente</h2>
                      <ul
                        style="
                          list-style: none;
                          padding: 0;
                          margin: 0;
                          color: #3e2c24;
                          font-size: 14px;
                        "
                      >
                        <li data-translate="• Iniciaste sesión correctamente ✅">• Iniciaste sesión correctamente ✅</li>
                        <li data-translate="• Puedes ver tu historial de compras en Historial de Compras">• Puedes ver tu historial de compras en Historial de Compras</li>
                        <li data-translate="• Puedes cambiar tu contraseña en Cambiar contraseña">• Puedes cambiar tu contraseña en Cambiar contraseña</li>
                      </ul>
                    </div>
                  </div>

                  <div class="card" style="margin-top: 18px">
                    <div class="body">
                      <h2 data-translate="Acciones rápidas">Acciones rápidas</h2>
                      <div class="actions">
                        <a class="btn" href="editar_perfil.php" data-translate="Editar perfil">Editar perfil</a>
                        <a class="btn" href="historial_compras.php" data-translate="Ver historial">Ver historial</a>
                        <a href="/PI3/General/logout.php"
                          style="
                            display:inline-block;
                            background:#531607;
                            color:#fff;
                            text-decoration:none;
                            font-weight:700;
                            padding:10px 16px;
                            border-radius:10px;
                            box-shadow:0 8px 16px rgba(83,22,7,.4);
                          ">
                          <span data-translate="Cerrar sesión">Cerrar sesión</span>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /content-grid -->
            </div>
          </div>
        </main>
      </div>
    </div>
<?php include "../footer.php"; ?>
<!-- === OVERLAY & DRAWER MINI-CARRITO === -->
    <div class="mc-overlay" id="mcOverlay" hidden></div>

    <aside
      class="mini-cart"
      id="miniCart"
      aria-hidden="true"
      aria-labelledby="mcTitle"
      role="dialog"
    >
      <header class="mc-header">
        <h3 id="mcTitle" data-translate="Tu carrito">Tu carrito</h3>
        <button class="mc-close" id="mcClose" aria-label="Cerrar carrito">
          ✕
        </button>
      </header>

      <div class="mc-body">
        <ul class="mc-list" id="mcList">
          <!-- items por JS -->
        </ul>
        <div class="mc-empty" id="mcEmpty" data-translate="Tu carrito está vacío.">Tu carrito está vacío.</div>
      </div>

      <footer class="mc-footer">
        <div class="mc-total">
          <span data-translate="Total">Total</span>
          <strong id="mcTotal">$0.00 MXN</strong>
        </div>
        <a href="../catalogo/carrito.php" class="mc-btn" data-translate="Ir a pagar">Ir a pagar</a>
      </footer>
    </aside>
<script>
  window.CART_API_URL = '../catalogo/cart_api.php';
</script>
<script src="../catalogo/app.js"></script>
<script src="../../translate.js"></script>  
  </body>
</html>
