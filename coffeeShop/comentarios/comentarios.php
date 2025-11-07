<?php
// Inicia sesión si no existe (importante para detectar si el usuario ya inició)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificamos si hay sesión activa
$usuarioLogueado = !empty($_SESSION['logueado']) && $_SESSION['logueado'] === true;

// Si hay sesión, podemos leer algunos datos
if ($usuarioLogueado) {
    $userid   = $_SESSION['userid']        ?? null;
    $username = $_SESSION['username']      ?? 'Usuario';
    $email    = $_SESSION['email']         ?? '';
    $avatar   = $_SESSION['profilescreen'] ?? null;
} else {
    // Si no hay sesión, inicializamos vacíos para evitar errores
    $userid = $username = $email = $avatar = null;
}
?>


<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Blackwood Coffee - Comentarios</title>
    
    <link rel="stylesheet" href="../Style.css" /> 
    <link rel="stylesheet" href="comentarios.css" />
    <link rel="icon" href="/images/logo.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../general.css" />
  </head>
  
  <body>
    <script src="../../theme-toggle.js" defer></script>
    <?php include "../nav_bar.php"; ?>
    <main class="panel-comentarios">
      
      <section class="panel" id="panel-formulario">
        <h2 data-translate="¡Déjanos tu opinión!">¡Déjanos tu opinión!</h2>
        
        <form id="form-comentario" class="form-comentario-nuevo" data-username="<?php echo htmlspecialchars($username); ?>" enctype="multipart/form-data">
            
            <div class="form-grupo-rating-central">
                <label data-translate="Calificación de nuestros servicios:">Calificación de nuestros servicios:</label>
                <div class="rating-estrellas">
                    <input type="radio" id="estrella5" name="calificacion" value="5" required><label for="estrella5" title="5 estrellas">★</label>
                    <input type="radio" id="estrella4" name="calificacion" value="4"><label for="estrella4" title="4 estrellas">★</label>
                    <input type="radio" id="estrella3" name="calificacion" value="3"><label for="estrella3" title="3 estrellas">★</label>
                    <input type="radio" id="estrella2" name="calificacion" value="2"><label for="estrella2" title="2 estrellas">★</label>
                    <input type="radio" id="estrella1" name="calificacion" value="1"><label for="estrella1" title="1 estrella">★</label>
                </div>
            </div>

            <div class="form-grupo-grande">
                <textarea id="comentario" name="comentario" rows="5" placeholder="Escribe aquí tu comentario..." required></textarea>
            </div>

            <div class="form-fila-media">
                <div class="form-grupo-upload">
                    <label for="imagen" class="btn-upload">
                        <i class="fas fa-camera"></i>  <span data-translate="Subir Foto"> Subir Foto</span>
                    </label>
                    <input type="file" id="imagen" name="imagen" accept="image/png, image/jpeg">
                    <span id="file-name" class="file-name-display" data-translate="Ningún archivo">Ningún archivo</span>
                </div>

                <div class="form-grupo-tags">
                    <label for="etiquetas" class="label-listbox" data-translate="Selecciona etiquetas:">Selecciona etiquetas:</label>
                    <select id="etiquetas" name="etiquetas[]" multiple>
                        <option value="café" data-translate="Café">Café</option>
                        <option value="postre" data-translate="Postre">Postre</option>
                        <option value="comida" data-translate="Comida">Comida</option>
                        <option value="servicio" data-translate="Servicio">Servicio</option>
                        <option value="ambiente" data-translate="Ambiente">Ambiente</option>
                        <option value="bueno" data-translate="Bueno">Bueno</option>
                        <option value="malo" data-translate="Malo">Malo</option>
                    </select>
                </div>
                </div>

            <div class="form-fila-botones">
                <button type="reset" class="btn-cancelar" data-translate="Borrar">Borrar</button>
                <button type="submit" class="btn-enviar" data-translate="Subir">Subir</button>
            </div>
            
            <p id="form-mensaje"></p> 
            
          </form> </section> <section class="panel-resena-general">
          <div class="resena-container">
              <div class="resena-promedio">
                  <div class="promedio-numero" id="promedio-numero-display">0.0</div>
                  <div class="promedio-estrellas" id="promedio-estrellas-display"></div>
                  <div class="promedio-total" id="promedio-total-display">
                  (<span id="total-reviews-count">0</span> <span data-translate="reseñas">reseñas</span>)
                </div>
              </div>
              <div class="resena-desglose" id="desglose-barras-container"></div>
            </div>
        </section> 
        <div class="coffee-separator"></div>


      <section class="reviews" aria-labelledby="reviews-title">
        <div class="reviews__wrap">
          <header class="reviews__header">
            <h2 id="reviews-title" data-translate="Comentarios">Comentarios</h2>
            <div class="reviews__controls">
              <div class="pill-group">
                <button class="pill pill--ghost" data-translate="Más recientes">Más recientes</button>
                <button class="pill pill--ghost"data-translate="Con foto">Con foto</button>
              </div>
            </div>
          </header>

          <div class="reviews__subhead">
            <span class="score-pill" id="review-score-pill">0.0</span> <div class="select-pill" role="listbox" aria-label="Clasificación">
              <span data-translate="Clasificación">Clasificación</span>
              <svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M7 10l5 5 5-5z" /></svg>
            </div>
          </div>

          <div class="reviews__grid" id="reviews-grid-container">
            <p data-translate="Cargando comentarios...">Cargando comentarios...</p>
          </div>
          
        </div>
      </section>
      </main> 
<?php include "../footer.php"; ?>

    <script src="comentarios.js" defer></script>
    <script src="../../translate.js"></script>

  </body>
</html>
