<?php 
include "../../conexion.php"; 
?>

<style>
.carousel-container {
  position: relative;
  width: 100%;
  max-width: 1200px;
  margin: 60px auto;
  overflow: hidden;
}

/* Carrusel */
.carousel-track {
  display: flex;
  transition: transform 0.5s ease;
  gap: 30px;
  padding: 15px;
}

/* Item */
.carousel-item {
  background: #2c2323;
  border-radius: 20px;
  padding: 0;
  flex: 0 0 calc((100% / 3) - 30px);
  text-align: center;
  box-shadow: 0 10px 25px rgba(0,0,0,0.4);
  overflow: hidden;
  position: relative;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.carousel-item:hover {
  transform: translateY(-6px);
  box-shadow: 0 14px 38px rgba(0,0,0,0.55);
}

/* Imagen */
.carousel-item img {
  width: 100%;
  height: 300px;
  object-fit: cover;
  display: block;
}

/* OVERLAY GRADIENT */
.overlay {
  position: absolute;
  bottom: 0;
  width: 100%;
  padding: 15px;
  background: linear-gradient(to top, rgba(0,0,0,0.85), rgba(0,0,0,0));
  color: #fff;
  text-align: left;
}

/* Nombre */
.overlay h3 {
  margin: 0;
  font-size: 20px;
  font-weight: 700;
}

/* Descuento */
.overlay .descuento {
  font-size: 16px;
  margin: 3px 0;
  font-weight: 500;
  color: #ffd26b;
}

/* Fechas */
.overlay .fechas {
  font-size: 12px;
  opacity: 0.85;
}

/* Etiqueta expira pronto */
.expira {
  position: absolute;
  top: 10px;
  left: 10px;
  background: #ff4444;
  padding: 5px 10px;
  border-radius: 10px;
  font-size: 12px;
  font-weight: 700;
  color: #fff;
}

/* Tablet */
@media (max-width: 900px) {
  .carousel-item {
    flex: 0 0 calc(50% - 20px);
  }
  .carousel-item img {
    height: 260px;
  }
}

/* Móvil */
@media (max-width: 600px) {
  .carousel-item {
    flex: 0 0 100%;
  }
  .carousel-item img {
    height: 230px;
  }
}

/* NAV SIEMPRE VISIBLE */
/* NAV siempre visible (corregido) */
.carousel-nav {
    position: absolute;
    top: 50%;
    left: 0;
    width: 100%;
    transform: translateY(-50%);
    z-index: 999999;
    pointer-events: none;
}

/* Botón — ahora 100% redondo sin deformación */
.carousel-btn {
    pointer-events: auto;
    background: rgba(0, 0, 0, 0.6);
    color: white;
    border: none;
    width: 55px;       /* tamaño fijo → NO se deforma */
    height: 55px;      /* tamaño fijo → NO se deforma */
    display: flex;     
    align-items: center;
    justify-content: center;
    font-size: 28px;
    border-radius: 50%;  /* ahora siempre círculo perfecto */
    cursor: pointer;
    transition: 0.25s ease;
    position: absolute;   /* ← evita que flex del padre los aplaste */
    top: 50%;
    transform: translateY(-50%);
}

/* hover */
.carousel-btn:hover {
    background: rgba(0, 0, 0, 0.85);
}

/* posiciones */
.carousel-btn.prev { left: 10px; }
.carousel-btn.next { right: 10px; }


/* Dots */
.carousel-dots {
  text-align: center;
  margin-top: 12px;
}

.carousel-dots span {
  width: 12px;
  height: 12px;
  background: #6c6c6c;
  display: inline-block;
  margin: 0 5px;
  cursor: pointer;
  transition: 0.25s ease;
}

.carousel-dots .active {
  background: #fff;
  transform: scale(1.35);
}

.carousel-item.pending-remove {
    opacity: 0;
    transition: opacity 0.5s ease; /* Cómo desaparecen */
    pointer-events: none; /* No molestan */
}

</style>


<div class="ts-title-line">
  <span class="ts-line"></span>
  <span class="ts-title-text">Promociones</span>
  <span class="ts-line"></span>
</div>


<!-- ===================== -->
<!--   CARRUSEL CORRECTO   -->
<!-- ===================== -->
<div class="carousel-container">

    <!-- Botones -->
    <div class="carousel-nav">
        <button class="carousel-btn prev">&#10094;</button>
        <button class="carousel-btn next">&#10095;</button>
    </div>

    <!-- Track REAL -->
    <div class="carousel-track">
    <?php 
    $hoy = date("Y-m-d");

    $c = $conn->query("
      SELECT * 
      FROM promocion
      WHERE activo = 1
      AND (fechainicio IS NULL OR fechainicio <= '$hoy')
      AND (fechaFin IS NULL OR fechaFin >= '$hoy')
    ");

    while ($r = $c->fetch_assoc()) {

        // Expira pronto
        $expiraPronto = false;
        if (!empty($r["fechaFin"])) {
            $diff = (strtotime($r["fechaFin"]) - strtotime($hoy)) / 86400;
            if ($diff <= 3) $expiraPronto = true;
        }

        echo '<div class="carousel-item">
                <img src="'.$r["imagen_url"].'" alt="promo">';

        if ($expiraPronto) {
            echo '<div class="expira">¡Expira pronto!</div>';
        }

        echo '<div class="overlay">
                <h3>'.$r["nombrePromo"].'</h3>
                <div class="descuento">'.
                  ($r["tipo_descuento"] == "porcentaje" ? $r["valor_descuento"].'% de descuento' : '$'.$r["valor_descuento"].' menos').
                '</div>
                <div class="fechas">
                   Válido: '.$r["fechaInicio"].' → '.$r["fechaFin"].'
                </div>
              </div>
            </div>';
    }
    ?>
    </div>

</div>


<div class="carousel-dots"></div>


<script>
const track = document.querySelector('.carousel-track');
let items = Array.from(document.querySelectorAll('.carousel-item'));

let itemWidth = items[0].getBoundingClientRect().width + 30;

// ===============================
// 🚀 GENERAR 3 CLONES ANTES Y 3 DESPUÉS
// ===============================
function generateClones() {
    for (let i = 0; i < 3; i++) {
        let cloneStart = items[items.length - 1 - i].cloneNode(true);
        cloneStart.classList.add("clone");
        track.insertBefore(cloneStart, track.firstChild);
    }
    for (let i = 0; i < 3; i++) {
        let cloneEnd = items[i].cloneNode(true);
        cloneEnd.classList.add("clone");
        track.appendChild(cloneEnd);
    }
}

generateClones();
items = Array.from(document.querySelectorAll('.carousel-item'));

let index = 3;
track.style.transform = `translateX(-${itemWidth * index}px)`;

// ===============================
// Función suave
// ===============================
function moveToIndex() {
    track.style.transition = "transform 0.45s ease";
    track.style.transform = `translateX(-${itemWidth * index}px)`;
}

// ===============================
// ANTI-SPAM => bloqueo real en animación
// ===============================
let canMove = true;

function delayedNext() {
    if (!canMove) return;
    canMove = false;   // Bloquea hasta que termine animación

    setTimeout(() => {
        index++;
        moveToIndex();
    }, 300);
}

function delayedPrev() {
    if (!canMove) return;
    canMove = false;

    setTimeout(() => {
        index--;
        moveToIndex();
    }, 300);
}

// ===============================
// Ajuste de clones al terminar animación
// ===============================
track.addEventListener("transitionend", () => {

    // Reinicio instantáneo para loop infinito
    if (index >= items.length - 3) {
        track.style.transition = "none";
        index = 3;
        track.style.transform = `translateX(-${itemWidth * index}px)`;
    }

    if (index < 3) {
        track.style.transition = "none";
        index = items.length - 6;
        track.style.transform = `translateX(-${itemWidth * index}px)`;
    }

    // 🔓 Animación terminó → desbloquear clicks
    setTimeout(() => {
        canMove = true;
    }, 10);
});

// ===============================
// Botones
// ===============================
document.querySelector('.carousel-btn.next').addEventListener("click", delayedNext);
document.querySelector('.carousel-btn.prev').addEventListener("click", delayedPrev);

// ===============================
// Recalcular en resize
// ===============================
window.addEventListener("resize", () => {
    itemWidth = items[0].getBoundingClientRect().width + 30;
    track.style.transition = "none";
    track.style.transform = `translateX(-${itemWidth * index}px)`;
});
</script>
