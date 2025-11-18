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
// datos de sesión
$userid    = $_SESSION['userid']        ?? null;
$nombre    = $_SESSION['username']      ?? 'Usuario';
$email     = $_SESSION['email']         ?? '';
$avatar    = $_SESSION['profilescreen'] ?? ''; // ej "../images/profiles/avatar_user_16.jpg"

$apellido  = $_SESSION['apellido']      ?? '';
$telefono  = $_SESSION['telefono']      ?? '';
$fechaNac  = $_SESSION['fecha_nac']     ?? '';
$zonaHorariaActual = $_SESSION['zona_horaria'] ?? '(UTC -06:00) Guadalajara, CDMX';

// función fallback si no hay avatar todavía
function getAvatarSrc($avatar, $nombre) {
    if (!empty($avatar)) {
        return htmlspecialchars($avatar);
    }
    // avatar genérico con iniciales
    return "https://ui-avatars.com/api/?name=" . urlencode($nombre) .
           "&background=DCC0B9&color=531607";
}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Ajustes de cuenta</title>
    <link rel="stylesheet" href="perfil_usuario.css" />
    <link rel="stylesheet" href="../general.css" />

    
  </head>
  <body>
<?php include "../nav_bar.php"; ?>    
    <div class="shell">
      <div class="app">

        <!-- Sidebar -->
        <aside class="sidebar">
          <!-- FOTO + CÁMARA -->
          <div class="brand">
  <div class="avatar-wrapper">
    <img
      class="avatar"
      src="<?php echo getAvatarSrc($avatar, $nombre); ?>"
      alt="Avatar de <?php echo htmlspecialchars($nombre); ?>"
    />

    <!-- Botón cámara arriba a la derecha -->
    <label
      for="nueva_foto"
      class="cam-badge"
      aria-label="Cambiar foto"
      title="Cambiar foto"
    >
      <svg
        viewBox="0 0 24 24"
        width="20"
        height="20"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
      >
        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h3l2-3h8l2 3h3a2 2 0 0 1 2 2z"/>
        <circle cx="12" cy="13" r="4"/>
      </svg>
    </label>
  </div>

  <!-- Input oculto -->
  <input
    type="file"
    id="nueva_foto"
    name="nueva_foto"
    accept="image/*"
    form="form-perfil"
    hidden
  />

            <!-- Botón cámara -->
            <label
              for="nueva_foto"
              class="cam-badge"
              aria-label="Cambiar foto"
              title="Cambiar foto"
            >
              <svg
                viewBox="0 0 24 24"
                width="22"
                height="22"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
              >
                <path
                  d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h3l2-3h8l2 3h3a2 2 0 0 1 2 2z"
                />
                <circle cx="12" cy="13" r="4" />
              </svg>
            </label>
          </div>

          <nav class="nav">
            <a href="perfil_usuario.php">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="7" r="4" />
                <path d="M5.5 21a6.5 6.5 0 0 1 13 0" />
              </svg>
              <span data-translate="Perfil">Perfil</span>
            </a>

            <a class="active" href="editar_perfil.php">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10" />
                <path d="M7 12h10M7 8h4M7 16h6" />
              </svg>
              <span data-translate="Editar perfil">Editar perfil</span>
            </a>

            <a href="cambiar_pass.php">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/>
              </svg>
              <span data-translate="Cambiar contraseña">Cambiar contraseña</span>
            </a>

            <a href="historial_compras.php">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
              alt="Logo Coffee-Shop"
            />
          </div>
        </aside>

        <!-- Main -->
        <main class="main">
          <h1 data-translate="Información del Usuario">Información del Usuario</h1>
          <div class="card">

            <div class="section">
              <p class="muted" data-translate="Actualiza tu información básica de cuenta / Edita tu perfil">
                Actualiza tu información básica de cuenta / Edita tu perfil
              </p>
            </div>

            <div class="section">
              <!-- FORMULARIO -->
              <form
                id="form-perfil"
                class="grid"
                action="guardar_perfil.php"
                method="post"
                enctype="multipart/form-data"
              >

                <!-- ID oculto -->
                <input type="hidden" name="userid"
                  value="<?php echo htmlspecialchars($userid); ?>">

                <div class="col-12">
                  <label for="email" data-translate="(Correo electrónico)">(Correo electrónico)</label>
                  <div class="field">
                    <input
                      id="email"
                      name="email"
                      type="email"
                      value="<?php echo htmlspecialchars($email); ?>"
                      placeholder="tu@email.com"
                      required
                    />
                  </div>
                </div>

                <div class="col-6">
                  <label for="fname" data-translate="Nombre">Nombre</label>
                  <input
                    id="fname"
                    name="nombre"
                    type="text"
                    value="<?php echo htmlspecialchars($nombre); ?>"
                    required
                  />
                </div>

                <div class="col-6">
                  <label for="sname" data-translate="Apellido">Apellido</label>
                  <input
                    id="sname"
                    name="apellido"
                    type="text"
                    value="<?php echo htmlspecialchars($apellido); ?>"
                  />
                </div>

                <div class="col-6">
                  <label for="lname" data-translate="Número">Número</label>
                  <input
                    id="lname"
                    name="telefono"
                    type="text"
                    value="<?php echo htmlspecialchars($telefono); ?>"
                  />
                </div>

                <div class="col-6">
                  <label for="dob" data-translate="Fecha de nacimiento">Fecha de nacimiento</label>
                  <input
                    id="dob"
                    name="fecha_nac"
                    type="date"
                    value="<?php echo htmlspecialchars($fechaNac); ?>"
                  />
                </div>

                <div class="col-12">
                  <label for="tz" data-translate="Zona horaria">Zona horaria</label>
                  <div class="select-wrap">
                    <select id="tz" name="zona_horaria">
                      <option <?php echo ($zonaHorariaActual === '(UTC -06:00) Guadalajara, CDMX') ? 'selected' : ''; ?>>
                        (UTC -06:00) Guadalajara, CDMX
                      </option>
                      <option <?php echo ($zonaHorariaActual === '(UTC -06:00) Manzanillo, Colima') ? 'selected' : ''; ?>>
                        (UTC -06:00) Manzanillo, Colima
                      </option>
                      <option <?php echo ($zonaHorariaActual === '(UTC +05:30) India Standard Time') ? 'selected' : ''; ?>>
                        (UTC +05:30) India Standard Time
                      </option>
                      <option <?php echo ($zonaHorariaActual === '(UTC +09:00) Tokyo') ? 'selected' : ''; ?>>
                        (UTC +09:00) Tokyo
                      </option>
                    </select>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                      stroke="currentColor" stroke-width="2" aria-hidden="true">
                      <path d="M6 9l6 6 6-6" />
                    </svg>
                  </div>
                  <p class="tz-hint" data-translate="Selecciona tu zona horaria para mostrar correctamente horas y fechas.">
                    Selecciona tu zona horaria para mostrar correctamente horas y fechas.
                  </p>
                </div>

                <div class="col-12 actions">
                  <button type="submit" class="btn" data-translate="Guardar">Guardar</button>
                  <button type="button" class="btn secondary" onclick="window.location='perfil_usuario.php'" data-translate="Cancelar">Cancelar</button>
                </div>

              </form>
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
