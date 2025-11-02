<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// si no está logueado no puede cambiar contraseña
if (empty($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {

    // si viene de AJAX, regresamos JSON en lugar de redirigir
    if (isset($_POST['ajax'])) {
        header('Content-Type: application/json');
        echo json_encode([
            'ok'    => false,
            'error' => 'No autenticado. Inicia sesión.'
        ]);
        exit;
    }

    header("Location: /PI3/General/login.php");
    exit;
}

require_once '../../conexion.php'; // AJUSTA la ruta si tu conexion.php está en otra carpeta

$userid   = $_SESSION['userid'];
$username = $_SESSION['username'] ?? 'Usuario';
$avatar   = $_SESSION['profilescreen'] ?? null;

$mensaje_ok = '';
$mensaje_error = '';

// helper para responder JSON limpio si es AJAX
function responderJSON($ok, $msgKey, $msgVal) {
    header('Content-Type: application/json');
    echo json_encode([
        'ok'    => $ok,
        $msgKey => $msgVal
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // detectar si este submit vino vía fetch
    $isAjax = isset($_POST['ajax']);

    // 1. leer campos del form
    // usa los mismos names que están en tu <form>:
    //  name="pwd_old", name="pwd_new", name="pwd_confirm"
    $pwd_actual   = $_POST['pwd_old']     ?? '';
    $pwd_nueva    = $_POST['pwd_new']     ?? '';
    $pwd_confirm  = $_POST['pwd_confirm'] ?? '';

    // 2. validaciones
    if ($pwd_nueva !== $pwd_confirm) {
        if ($isAjax) responderJSON(false, 'error', 'La nueva contraseña y la confirmación no coinciden.');
        $mensaje_error = "La nueva contraseña y la confirmación no coinciden.";
    } elseif (strlen($pwd_nueva) < 8) {
        if ($isAjax) responderJSON(false, 'error', 'La nueva contraseña debe tener mínimo 8 caracteres.');
        $mensaje_error = "La nueva contraseña debe tener mínimo 8 caracteres.";
    } else {
        // 3. sacar hash actual
        $sql = "SELECT password FROM usuarios WHERE userid = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            if ($isAjax) responderJSON(false, 'error', 'Error interno (prep stmt).');
            $mensaje_error = "Error interno (prep stmt).";
        } else {
            $stmt->bind_param("i", $userid);
            $stmt->execute();
            $res = $stmt->get_result();

            if ($res->num_rows !== 1) {
                if ($isAjax) responderJSON(false, 'error', 'Usuario no encontrado.');
                $mensaje_error = "Usuario no encontrado.";
            } else {
                $row = $res->fetch_assoc();
                $hash_actual_bd = $row['password'];

                // 4. validar contraseña actual
                if (!password_verify($pwd_actual, $hash_actual_bd)) {
                    if ($isAjax) responderJSON(false, 'error', 'Tu contraseña actual no es correcta.');
                    $mensaje_error = "Tu contraseña actual no es correcta.";
                } elseif (password_verify($pwd_nueva, $hash_actual_bd)) {
                    if ($isAjax) responderJSON(false, 'error', 'La nueva contraseña no puede ser igual a la anterior.');
                    $mensaje_error = "La nueva contraseña no puede ser igual a la anterior.";
                } else {
                    // 5. hashear nueva
                    $nuevo_hash = password_hash($pwd_nueva, PASSWORD_DEFAULT);

                    $upd = $conn->prepare("UPDATE usuarios SET password = ? WHERE userid = ?");
                    if (!$upd) {
                        if ($isAjax) responderJSON(false, 'error', 'Error interno (upd stmt).');
                        $mensaje_error = "Error interno (upd stmt).";
                    } else {
                        $upd->bind_param("si", $nuevo_hash, $userid);
                        if ($upd->execute()) {
                            if ($isAjax) responderJSON(true, 'success', 'Contraseña actualizada correctamente.');
                            $mensaje_ok = "✅ Contraseña actualizada correctamente.";
                        } else {
                            if ($isAjax) responderJSON(false, 'error', 'No se pudo actualizar la contraseña.');
                            $mensaje_error = "No se pudo actualizar la contraseña.";
                        }
                        $upd->close();
                    }
                }
            }
            $stmt->close();
        }
    }
}

// cerramos conexión al final
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cambiar contraseña — Coffee Shop</title>
    <link rel="stylesheet" href="cambiarcontraseña.css" />
    <style>
      .alert-ok{
        background:#e6ffed;
        color:#1a7f37;
        border:2px solid #1a7f37aa;
        padding:12px 16px;
        font-size:.9rem;
        border-radius:10px;
        margin-bottom:16px;
        font-weight:600;
      }
      .alert-err{
        background:#ffefef;
        color:#c62828;
        border:2px solid #c62828aa;
        padding:12px 16px;
        font-size:.9rem;
        border-radius:10px;
        margin-bottom:16px;
        font-weight:600;
      }
      /* deshabilitado visual del botón Guardar */
      .btn[disabled]{
        opacity:.5;
        pointer-events:none;
        cursor:not-allowed;
      }
    </style>
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
                alt="Avatar de <?php echo htmlspecialchars($username); ?>"
              />
            <?php else: ?>
              <img
                class="avatar"
                src="https://ui-avatars.com/api/?name=<?php echo urlencode($username); ?>&background=DCC0B9&color=531607"
                alt="Avatar genérico"
              />
            <?php endif; ?>
          </div>

          <nav class="nav">
            <a href="perfil_usuario.php">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="7" r="4" />
                <path d="M5.5 21a6.5 6.5 0 0 1 13 0" />
              </svg>
              Perfil
            </a>
            <a href="editar_perfil.php">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10" />
                <path d="M7 12h10M7 8h4M7 16h6" />
              </svg>
              Editar perfil
            </a>
            <a class="active" href="cambiar_pass.php">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/>
              </svg>
              Cambiar contraseña
            </a>
            <a href="historial_compras.php">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
              <h1>Cambiar contraseña</h1>

              <?php if (!empty($mensaje_ok)): ?>
                <div class="alert-ok"><?php echo $mensaje_ok; ?></div>
              <?php endif; ?>

              <?php if (!empty($mensaje_error)): ?>
                <div class="alert-err"><?php echo $mensaje_error; ?></div>
              <?php endif; ?>

              <div class="card">
                <div class="body">
                  <form
                    class="grid"
                    action="cambiar_pass.php"
                    method="post"
                    autocomplete="off"
                    id="formChangePwd"
                  >
                    <!-- Actual -->
                    <div class="col-12">
                      <label for="pwd-old">Contraseña actual</label>
                      <div class="field">
                        <input
                          id="pwd-old"
                          name="pwd_old"
                          type="password"
                          placeholder="••••••••"
                          required
                        />
                        <span class="eye" aria-hidden="true"></span>
                      </div>
                    </div>

                    <!-- Nueva -->
                    <div class="col-12">
                      <label for="pwd-new">Nueva contraseña</label>
                      <div class="field">
                        <input
                          id="pwd-new"
                          name="pwd_new"
                          type="password"
                          placeholder="••••••••"
                          required
                        />
                        <span class="eye" aria-hidden="true"></span>
                      </div>

                      <!-- Medidor -->
                      <div class="strength">
                        <div class="bar bar-weak"></div>
                        <span class="label">Débil</span>
                      </div>
                    </div>

                    <!-- Confirmación -->
                    <div class="col-12">
                      <label for="pwd-confirm">Confirmar contraseña</label>
                      <div class="field">
                        <input
                          id="pwd-confirm"
                          name="pwd_confirm"
                          type="password"
                          placeholder="••••••••"
                          required
                        />
                        <span class="eye" aria-hidden="true"></span>
                      </div>
                    </div>

                    <!-- Requisitos -->
                    <div class="col-12">
                      <div class="rules">
                        <p>La contraseña debe incluir:</p>
                        <ul>
                          <li>• Mínimo 8 caracteres</li>
                          <li>• Una mayúscula y una minúscula</li>
                          <li>• Un número</li>
                          <li>• Un carácter especial</li>
                        </ul>
                        <p class="hint">
                          Sugerencia: activa la autenticación de dos factores (2FA) en Seguridad.
                        </p>
                      </div>
                    </div>

                    <!-- Acciones -->
                    <div class="col-12 actions">
                      <button type="submit" class="btn">Guardar</button>
                      <a class="btn secondary" href="perfil_usuario.php">Cancelar</a>
                    </div>
                  </form>
                </div> <!-- body -->
              </div> <!-- card -->
            </div>
          </div>
        </main>
      </div>
    </div>
    <script src="cambiarcontraseña.js" defer></script>
  </body>
</html>
