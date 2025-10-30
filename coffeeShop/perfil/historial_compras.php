<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Historial de Compras — Coffee Shop</title>
    <link rel="stylesheet" href="css/historial_compras.css" />
  </head>
  <body>
    <div class="shell">
      <div class="app">
        <!-- SIDEBAR -->
        <aside class="sidebar">
          <div class="brand">
            <img
              class="avatar"
              src="https://images.unsplash.com/photo-1607746882042-944635dfe10e?q=80&w=256&auto=format&fit=crop"
              alt="Avatar"
            />
          </div>

          <nav class="nav">
            <a href="perfil_usuario.html">
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
            <a href="editar_perfil.html">
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
            <a href="cambiar_pass.html">
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
            <a class="active" href="historial_compras.html">
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
              src="assest/logocafe.png"
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

              <!-- Resumen -->
              <div class="kpi-row">
                <div class="kpi"><b>8</b><span>Órdenes este mes</span></div>
                <div class="kpi"><b>$245.00</b><span>Gasto total</span></div>
                <div class="kpi">
                  <b>5,580</b><span>Puntos acumulados</span>
                </div>
              </div>

              <!-- Filtros -->
              <div class="filters card">
                <div class="body">
                  <form class="filters-grid" action="#" method="get">
                    <div class="field">
                      <label for="q">Buscar</label>
                      <input
                        id="q"
                        type="text"
                        placeholder="Bebida, folio, sucursal…"
                      />
                    </div>
                    <div class="field">
                      <label for="from">Desde</label>
                      <input id="from" type="date" />
                    </div>
                    <div class="field">
                      <label for="to">Hasta</label>
                      <input id="to" type="date" />
                    </div>
                    <div class="field">
                      <label for="status">Estado</label>
                      <select id="status">
                        <option value="">Todos</option>
                        <option>Completado</option>
                        <option>En preparación</option>
                        <option>Cancelado</option>
                      </select>
                    </div>
                    <div class="actions">
                      <button type="submit" class="btn">Filtrar</button>
                      <a class="btn secondary" href="historial_compras.html"
                        >Limpiar</a
                      >
                    </div>
                  </form>
                </div>
              </div>

              <!-- Tabla SIN columna de acciones -->
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
                        <tr>
                          <td>#A-1024</td>
                          <td>20/10/2025</td>
                          <td>Cappuccino x2, Croissant</td>
                          <td>Centro GDL</td>
                          <td>$145.00</td>
                          <td><span class="badge ok">Completado</span></td>
                        </tr>
                        <tr>
                          <td>#A-1023</td>
                          <td>18/10/2025</td>
                          <td>Latte, Muffin</td>
                          <td>Providencia</td>
                          <td>$60.00</td>
                          <td>
                            <span class="badge warn">En preparación</span>
                          </td>
                        </tr>
                        <tr>
                          <td>#A-1022</td>
                          <td>17/10/2025</td>
                          <td>Americano x2</td>
                          <td>Centro GDL</td>
                          <td>$40.00</td>
                          <td><span class="badge error">Cancelado</span></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <!-- Paginación -->
                  <div class="pager">
                    <a class="mini" href="#">&laquo; Anterior</a>
                    <span class="page">1</span>
                    <a class="mini" href="#">2</a>
                    <a class="mini" href="#">3</a>
                    <a class="mini" href="#">Siguiente &raquo;</a>
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
