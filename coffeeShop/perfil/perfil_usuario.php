<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// bloquear si no está logueado
if (empty($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: /PI3/General/login.php");
    exit;
}

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
  </head>
  <body>
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
              Perfil
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
              Editar perfil
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
              Cambiar contraseña
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
              Historial de Compras
            </a>
          </nav>

          <div class="sidebar-bottom">
            <img
              class="sidebar-logo"
              src="../../images/logocafe.png"
              alt="Coffee Shop"
            />
          </div>
        </aside>

        <!-- MAIN -->
        <main class="main">
          <div class="panel">
            <div class="inner">
              <h1>¡Hola, <?php echo htmlspecialchars($nombre); ?>!</h1>
              <p class="hello">
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
                            <span class="dot"></span>Tu cuenta Coffee-Shop
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
                        <dt>Email</dt>
                        <dd><?php echo htmlspecialchars($email); ?></dd>

                        <dt>Teléfono</dt>
                        <dd><?php echo htmlspecialchars($telefono ?: '—'); ?></dd>

                        <dt>Fecha de nacimiento</dt>
                        <dd><?php echo htmlspecialchars($fechaNacBonita); ?></dd>

                        <dt>Miembro desde</dt>
                        <dd><?php echo htmlspecialchars($miembroDesde); ?></dd>

                        <dt>Zona horaria</dt>
                        <dd><?php echo htmlspecialchars($zonaHoraria); ?></dd>
                      </dl>
                    </div>
                  </div>
                </div>

                <!-- Columna derecha -->
                <div class="col-right">
                  <div class="card">
                    <div class="body">
                      <h2>Actividad reciente</h2>
                      <ul
                        style="
                          list-style: none;
                          padding: 0;
                          margin: 0;
                          color: #3e2c24;
                          font-size: 14px;
                        "
                      >
                        <li>• Iniciaste sesión correctamente ✅</li>
                        <li>• Puedes ver tu historial de compras en "Historial de Compras"</li>
                        <li>• Puedes cambiar tu contraseña en "Cambiar contraseña"</li>
                      </ul>
                    </div>
                  </div>

                  <div class="card" style="margin-top: 18px">
                    <div class="body">
                      <h2>Acciones rápidas</h2>
                      <div class="actions">
                        <a class="btn" href="editar_perfil.php">Editar perfil</a>
                        <a class="btn" href="historial_compras.php">Ver historial</a>
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
                          Cerrar sesión
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
  </body>
</html>
