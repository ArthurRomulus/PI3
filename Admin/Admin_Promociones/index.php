<?php
include "../../conexion.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promociones</title>
    <link rel="stylesheet" href="../Admin_nav_bar.css">
    <link rel="stylesheet" href="Admin_promociones.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../general.css">
<script src="../../theme-toggle.js" defer></script>
</head>
<body>
    <?php include '../Admin_nav_bar.php'; ?>

    <div class="content">
        <!-- Top Bar con admin -->
        <div class="top-bar">
            <?php include "../AdminProfileSesion.php"; ?>
        </div>

        <h1>Blackwood Coffee</h1>

        <!-- Botón Agregar Promoción -->
        <!-- Título del listado -->
        <h3>Promociones</h3>

        <div class="promo-header">
            <button class="btn-add-promo" id="openPromoModal">
                <i class="fas fa-plus"></i> Agregar Promoción
            </button>
        </div>
        <!-- Grid de promociones dinámico -->
        <div class="promos-container">
        <?php
        $sql = "SELECT * FROM promocion ORDER BY idPromo ASC";
        $result = $conn->query($sql);

        if($result->num_rows > 0) {
            $hoy = new DateTime(); // Fecha actual
            while($row = $result->fetch_assoc()){

                $fechaFin = new DateTime($row['fechaFin']);
                $diff = (int)$hoy->diff($fechaFin)->format("%r%a"); // Diferencia en días

                if($diff < 0){
                    $estado_texto = "Expirada";
                    $estado_clase = "expired";
                } elseif($diff == 0){
                    $estado_texto = "Último día";
                    $estado_clase = "expiring";
                } elseif($diff <= 5){
                    $estado_texto = "Por vencer";
                    $estado_clase = "expiring";
                } else {
                    $estado_texto = "Activa";
                    $estado_clase = "active";
                }
        ?>
            <div class="promo-card"
                data-id="<?php echo $row['idPromo']; ?>"
                data-nombre="<?php echo $row['nombrePromo']; ?>"
                data-fecha_inicio="<?php echo $row['fechaInicio']; ?>"
                data-fecha_final="<?php echo $row['fechaFin']; ?>"
                data-valor_descuento="<?php echo $row['valor_descuento']; ?>"
                data-tipo_descuento="<?php echo $row['tipo_descuento']; ?>"
                data-condiciones="<?php echo $row['condiciones']; ?>"
                data-imagen="<?php echo $row['imagen_url']; ?>">
                
                <img src="<?php echo $row['imagen_url'] ? $row['imagen_url'] : 'img/default.png'; ?>" alt="<?php echo $row['nombrePromo']; ?>">

                <div class="promo-info">
                    <span class="promo-status <?php echo $estado_clase; ?>">
                        <?php echo $estado_texto; ?>
                    </span>
                    <h4 class="promo-name"><?php echo $row['nombrePromo']; ?></h4>
                    <span class="promo-price">
                        <?php echo $row['tipo_descuento'] == 'porcentaje' ? $row['valor_descuento'].'%' : '$'.$row['valor_descuento']; ?>
                    </span>
                    <?php if(!empty($row['condiciones'])): ?>
                        <span class="promo-conditions"><?php echo $row['condiciones']; ?></span>
                    <?php endif; ?>
                    <span class="promo-validity">
                        Vigencia: válido hasta el <?php echo date('d M', strtotime($row['fechaFin'])); ?>
                    </span>

                    <div class="promo-actions">
                        <i class="fas fa-pen edit" title="Editar"></i>
                        <i class="fas fa-trash delete" title="Eliminar"></i>
                    </div>
                </div>
            </div>
        <?php
            }
        } else {
            echo "<p>No hay promociones registradas.</p>";
        }
        ?>
        </div>
    </div>

    <!-- Modal Agregar Promoción -->
    <div id="promoModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Agregar Promoción</h2>
            <form action="Guardar_promociones.php" method="POST" enctype="multipart/form-data">
                <input type="text" name="nombre" placeholder="Título de la promoción" required>
                <input type="file" name="imagen" accept="image/*" required>
                <input type="date" name="fecha_inicio" required>
                <input type="date" name="fecha_final" required>
                <input type="number" name="valor_descuento" placeholder="Valor del descuento" required>
                <select name="tipo_descuento" required>
                    <option value="fijo">Fijo</option>
                    <option value="porcentaje">Porcentaje</option>
                </select>
                <textarea name="condiciones" placeholder="Condiciones de la promoción"></textarea>
                <button type="submit">Guardar</button>
            </form>
        </div>
    </div>

    <!-- Modal Editar Promoción -->
    <div id="editPromoModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Editar Promoción</h2>
            <form action="Editar_promociones.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="editId" name="id">
                <input type="text" id="editNombre" name="nombre" placeholder="Título de la promoción" required>
                <input type="file" id="editImagen" name="imagen" accept="image/*">
                <input type="date" id="editFechaInicio" name="fecha_inicio" required>
                <input type="date" id="editFechaFinal" name="fecha_final" required>
                <input type="number" id="editValorDescuento" name="valor_descuento" placeholder="Valor del descuento" required>
                <select id="editTipoDescuento" name="tipo_descuento" required>
                    <option value="fijo">Fijo</option>
                    <option value="porcentaje">Porcentaje</option>
                </select>
                <textarea id="editCondiciones" name="condiciones" placeholder="Condiciones de la promoción"></textarea>
                <button type="submit">Actualizar</button>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        const promoModal = document.getElementById('promoModal');
        const openPromoBtn = document.getElementById('openPromoModal');
        const editPromoModal = document.getElementById('editPromoModal');
        const closeBtns = document.querySelectorAll('.modal .close');

        openPromoBtn.onclick = () => promoModal.style.display = 'flex';
        closeBtns.forEach(btn => {
            btn.onclick = () => btn.closest('.modal').style.display = 'none';
        });
        window.onclick = (e) => {
            if(e.target.classList.contains('modal')) e.target.style.display = 'none';
        };

        // Modal Editar
        const editButtons = document.querySelectorAll('.promo-actions .edit');
        editButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const card = btn.closest('.promo-card');
                document.getElementById('editId').value = card.dataset.id;
                document.getElementById('editNombre').value = card.dataset.nombre;
                document.getElementById('editFechaInicio').value = card.dataset.fecha_inicio;
                document.getElementById('editFechaFinal').value = card.dataset.fecha_final;
                document.getElementById('editValorDescuento').value = card.dataset.valor_descuento;
                document.getElementById('editTipoDescuento').value = card.dataset.tipo_descuento;
                document.getElementById('editCondiciones').value = card.dataset.condiciones;

                editPromoModal.style.display = 'flex';
            });
        });

        // Eliminar Promoción
        const deleteButtons = document.querySelectorAll('.promo-actions .delete');
        deleteButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const card = btn.closest('.promo-card');
                const id = card.dataset.id;
                if(confirm("¿Seguro que deseas eliminar esta promoción?")) {
                    fetch("Eliminar_promociones.php", {
                        method: "POST",
                        headers: {"Content-Type": "application/x-www-form-urlencoded"},
                        body: "id=" + encodeURIComponent(id)
                    })
                    .then(res => res.text())
                    .then(data => {
                        if(data.trim() === "success") card.remove();
                        else alert("Error al eliminar: " + data);
                    }).catch(err => alert("Error en la solicitud: " + err));
                }
            });
        });
    </script>
</body>
</html>
