<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Seguridad: solo usuarios logueados
if (empty($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: /PI3/General/login.php");
    exit;
}

// 2. Datos básicos del usuario actual
$userid   = $_SESSION['userid']   ?? null;
$avatar   = $_SESSION['profilescreen'] ?? null;
$nombre   = $_SESSION['username'] ?? 'Usuario';

if (!$userid) {
    // si por alguna razón no hay userid en la sesión, lo mandamos a login
    header("Location: /PI3/General/login.php");
    exit;
}

// 3. Conexión BD
require_once "../../conexion.php";

// ------------------------------------------------------
// A) KPI: órdenes del MES actual y gasto total del MES
// ------------------------------------------------------
$ordenesMes   = 0;
$gastoMes     = 0.0;
$puntosTotales = 0;

// mes actual en formato YYYY-MM
$mesActual = date("Y-m"); // ej "2025-10"

// Órdenes y total del mes
$sqlMes = "
    SELECT COUNT(*) AS num_ordenes,
           COALESCE(SUM(total),0) AS total_mes
    FROM pedidos
    WHERE userid = ?
      AND DATE_FORMAT(fecha_pedido, '%Y-%m') = ?
";
$stmtMes = $conn->prepare($sqlMes);
if ($stmtMes) {
    $stmtMes->bind_param("is", $userid, $mesActual);
    $stmtMes->execute();
    $resMes = $stmtMes->get_result();
    if ($filaMes = $resMes->fetch_assoc()) {
        $ordenesMes = (int)$filaMes['num_ordenes'];
        $gastoMes   = (float)$filaMes['total_mes'];
    }
    $stmtMes->close();
}

// "Puntos acumulados":
// Aquí inventamos una fórmula simple usando TODOS sus pedidos históricos
// puntos = total_gastado * 20
$sqlPuntos = "
    SELECT COALESCE(SUM(total),0) AS total_global
    FROM pedidos
    WHERE userid = ?
";
$stmtPts = $conn->prepare($sqlPuntos);
if ($stmtPts) {
    $stmtPts->bind_param("i", $userid);
    $stmtPts->execute();
    $resPts = $stmtPts->get_result();
    if ($filaPts = $resPts->fetch_assoc()) {
        $puntosTotales = (float)$filaPts['total_global'] * 20;
    }
    $stmtPts->close();
}

// ------------------------------------------------------
// B) Historial de pedidos con sus items
//    Traemos los últimos pedidos del usuario
// ------------------------------------------------------
$pedidos = []; // array final: cada pedido => [id, fecha, sucursal, total, estado, items_text]

$sqlPedidos = "
    SELECT id_pedido, fecha_pedido, sucursal, total, estado
    FROM pedidos
    WHERE userid = ?
    ORDER BY fecha_pedido DESC
    LIMIT 50
";
$stmtPed = $conn->prepare($sqlPedidos);
if ($stmtPed) {
    $stmtPed->bind_param("i", $userid);
    $stmtPed->execute();
    $resPed = $stmtPed->get_result();

    while ($row = $resPed->fetch_assoc()) {
        $idPedido      = $row['id_pedido'];
        $fechaPedido   = $row['fecha_pedido'];
        $sucursal      = $row['sucursal'];
        $total         = (float)$row['total'];
        $estado        = $row['estado'];

        // Obtener detalle de items
        $itemsTxt = "";
        $sqlItems = "
            SELECT producto_nombre, cantidad
            FROM pedido_items
            WHERE id_pedido = ?
        ";
        $stmtItems = $conn->prepare($sqlItems);
        if ($stmtItems) {
            $stmtItems->bind_param("i", $idPedido);
            $stmtItems->execute();
            $resItems = $stmtItems->get_result();

            $parts = [];
            while ($it = $resItems->fetch_assoc()) {
                $nom = $it['producto_nombre'];
                $cant = (int)$it['cantidad'];
                // ejemplo: "Cappuccino x2"
                $parts[] = $nom . " x" . $cant;
            }
            $itemsTxt = implode(", ", $parts);
            $stmtItems->close();
        }

        // fecha bonita dd/mm/YYYY
        $fechaBonita = "";
        if (!empty($fechaPedido)) {
            $ts = strtotime($fechaPedido);
            $fechaBonita = date("d/m/Y", $ts);
        }

        $pedidos[] = [
            'folio'      => "#A-" . str_pad($idPedido, 4, "0", STR_PAD_LEFT),
            'fecha'      => $fechaBonita,
            'items'      => $itemsTxt,
            'sucursal'   => $sucursal,
            'total'      => $total,
            'estado'     => $estado,
        ];
    }

    $stmtPed->close();
}

$conn->close();

// función helper para badge CSS segun estado
function badgeClass($estado) {
    $estadoLower = mb_strtolower($estado);
    if ($estadoLower === "completado" || $estadoLower === "entregado") {
        return "ok";
    } elseif ($estadoLower === "en preparación" || $estadoLower === "preparando" || $estadoLower === "pendiente") {
        return "warn";
    } else {
        return "error"; // cancelado / fallido
    }
}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Historial de Compras — Coffee Shop</title>
    <link rel="stylesheet" href="historial_compras.css" />
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

            <a href="cambiar_pass.php">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path
                  d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"
                />
              </svg>
              Cambiar contraseña
            </a>

            <a class="active" href="historial_compras.php">
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
              <h1>Historial de Compras</h1>
              <p class="hello">
                Consulta tus pedidos, filtra por fecha o estado y descarga tus
                recibos.
              </p>

              <!-- Resumen (usa valores calculados) -->
              <div class="kpi-row">
                <div class="kpi">
                  <b><?php echo htmlspecialchars($ordenesMes); ?></b>
                  <span>Órdenes este mes</span>
                </div>
                <div class="kpi">
                  <b>$<?php echo number_format($gastoMes, 2); ?></b>
                  <span>Gasto total</span>
                </div>
                <div class="kpi">
                  <b><?php echo number_format($puntosTotales, 0); ?></b>
                  <span>Puntos acumulados</span>
                </div>
              </div>

              <!-- Filtros (solo UI de momento, sin lógica backend de filtros todavía) -->
              <div class="filters card">
                <div class="body">
                  <form class="filters-grid" action="#" method="get">
                    <div class="field">
                      <label for="q">Buscar</label>
                      <input
                        id="q"
                        name="q"
                        type="text"
                        placeholder="Bebida, folio, sucursal…"
                      />
                    </div>
                    <div class="field">
                      <label for="from">Desde</label>
                      <input id="from" name="from" type="date" />
                    </div>
                    <div class="field">
                      <label for="to">Hasta</label>
                      <input id="to" name="to" type="date" />
                    </div>
                    <div class="field">
                      <label for="status">Estado</label>
                      <select id="status" name="status">
                        <option value="">Todos</option>
                        <option>Completado</option>
                        <option>En preparación</option>
                        <option>Cancelado</option>
                      </select>
                    </div>
                    <div class="actions">
                      <button type="submit" class="btn">Filtrar</button>
                      <a class="btn secondary" href="historial_compras.php">Limpiar</a>
                    </div>
                  </form>
                </div>
              </div>

              <!-- Tabla -->
              <div class="card">
                <div class="body">
                  <div class="table-wrap">
                    <table class="orders">
                      <thead>
                        <tr>
                          <th>Folio</th>
                          <th>Fecha</th>
                          <th>Artículos</th>
                          <th>Sucursal</th>
                          <th>Total</th>
                          <th>Estado</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (empty($pedidos)): ?>
                          <tr>
                            <td colspan="6" style="text-align:center; padding:20px; opacity:.7;">
                              Aún no tienes pedidos registrados.
                            </td>
                          </tr>
                        <?php else: ?>
                          <?php foreach ($pedidos as $p): ?>
                            <tr>
                              <td><?php echo htmlspecialchars($p['folio']); ?></td>
                              <td><?php echo htmlspecialchars($p['fecha']); ?></td>
                              <td><?php echo htmlspecialchars($p['items']); ?></td>
                              <td><?php echo htmlspecialchars($p['sucursal']); ?></td>
                              <td>$<?php echo number_format($p['total'], 2); ?></td>
                              <td>
                                <?php $cls = badgeClass($p['estado']); ?>
                                <span class="badge <?php echo $cls; ?>">
                                  <?php echo htmlspecialchars($p['estado']); ?>
                                </span>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>

                  <!-- paginación estática por ahora -->
                  <div class="pager">
                    <span class="page">1</span>
                    <!-- si quieres paginación real luego hacemos LIMIT ... OFFSET ... -->
                  </div>
                </div>
              </div>

            </div>
          </div>
        </main>
      </div>
    </div>
  </body>
</html>
