<?php
include "../../conexion.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Productos</title>
  <link rel="stylesheet" href="../Admin_nav_bar.css">
  <link rel="stylesheet" href="Admin_productos.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <?php include '../Admin_nav_bar.php'; ?>

  <div class="content">
    <div class="top-bar">
    <?php include "../AdminProfileSesion.php"; ?>

    </div>

    <h1>Coffee Shop</h1>
    <h3>Categorías</h3>
    <form method="GET" action="index.php">
        <input type="search" name="buscar" placeholder="Buscar..." 
              value="<?php echo isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : ''; ?>"
              onkeypress="if(event.key === 'Enter'){this.form.submit();}">
        <?php if(isset($_GET['categoria'])): ?>
            <input type="hidden" name="categoria" value="<?php echo htmlspecialchars($_GET['categoria']); ?>">
        <?php endif; ?>
    </form>

    <div class="button-group">
    <?php
    $categoria_query = "SELECT nombrecategoria FROM categorias ORDER BY nombrecategoria ASC";
    $categoria_result = $conn->query($categoria_query);

    if ($categoria_result->num_rows > 0) {
        while($cat = $categoria_result->fetch_assoc()) {
            $cat_nombre = $cat['nombrecategoria'];
            echo '<a href="index.php?categoria=' . urlencode($cat_nombre) . '">
                    <button>' . htmlspecialchars($cat_nombre) . '</button>
                  </a>';
        }
    }
    ?>
    <a href="index.php"><button>Categorías</button></a> <!-- Muestra todo -->
    </div>

    <!-- Sección de productos -->
    <h3>Productos</h3>
    <div class="products-container">
      <?php
      $categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';
      $buscar = isset($_GET['buscar']) ? $_GET['buscar'] : '';

      if ($categoria && $buscar) {
          $sql = "SELECT * FROM productos WHERE categoria = ? AND namep LIKE ?";
          $stmt = $conn->prepare($sql);
          $buscarParam = "%$buscar%";
          $stmt->bind_param("ss", $categoria, $buscarParam);
          $stmt->execute();
          $result = $stmt->get_result();
      } elseif ($categoria) {
          $sql = "SELECT * FROM productos WHERE categoria = ?";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("s", $categoria);
          $stmt->execute();
          $result = $stmt->get_result();
      } elseif ($buscar) {
          $sql = "SELECT * FROM productos WHERE namep LIKE ?";
          $stmt = $conn->prepare($sql);
          $buscarParam = "%$buscar%";
          $stmt->bind_param("s", $buscarParam);
          $stmt->execute();
          $result = $stmt->get_result();
      } else {
          $sql = "SELECT * FROM productos";
          $result = $conn->query($sql);
      }

      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            // Convertir el valor de sabor en texto
            $sabor_texto = '';
            switch($row['sabor']){
                case 1: $sabor_texto = 'Pequeño'; break;
                case 2: $sabor_texto = 'Mediano'; break;
                case 3: $sabor_texto = 'Grande'; break;
                default: $sabor_texto = 'Desconocido';
            }
              ?>
              <div class="product-card" 
                  data-id="<?php echo $row['idp']; ?>" 
                  data-nombre="<?php echo $row['namep']; ?>" 
                  data-precio="<?php echo $row['precio']; ?>" 
                  data-categoria="<?php echo $row['categoria']; ?>" 
                  data-sabor="<?php echo $row['sabor']; ?>" 
                  data-status="<?php echo $row['STOCK']; ?>" 
                  data-imagen="<?php echo $row['ruta_imagen']; ?>">
                  
                  <img src="<?php echo $row['ruta_imagen'] ? $row['ruta_imagen'] : '../img/default.png'; ?>" alt="<?php echo $row['namep']; ?>">
                  <span class="product-name"><?php echo $row['namep']; ?></span>
                  <span class="product-categoria"><?php echo $row['categoria']; ?></span>
                  <span class="product-price">$<?php echo number_format($row['precio'], 2); ?></span>
                  <span class="product-sabor"><?php echo $sabor_texto; ?></span>
                  
                  <div class="product-actions">
                      <i class="fas fa-pen edit" title="Editar"></i>
                      <i class="fas fa-trash delete" title="Eliminar"></i>
                  </div>
              </div>
              <?php
          }
      } else {
          echo "<p>No hay productos registrados.</p>";
      }
      ?>

      <!-- Cuadro para agregar producto (solo uno al final del grid) -->
      <div class="product-card add-product" id="openModal">
        <i class="fas fa-plus"></i>
        <span>Agregar Producto</span>
      </div>   
    </div>
  </div>

  <!-- Modal flotante para agregar producto -->
  <!-- Modal flotante para agregar producto -->
<div id="productModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2>Agregar Producto</h2>
    <form action="Guardar_producto.php" method="POST" enctype="multipart/form-data">
      <input type="text" name="name" placeholder="Nombre del producto" required>
      <input type="file" name="imagen" accept="image/*">
      <input type="number" name="precio" placeholder="Precio" required>
      
      <!-- Select para categoría -->
      <select name="categoria" required>
        <option value="">Selecciona categoría</option>
        <?php
        $categoria_result = $conn->query("SELECT nombrecategoria FROM categorias ORDER BY nombrecategoria ASC");
        if ($categoria_result->num_rows > 0) {
            while($cat = $categoria_result->fetch_assoc()) {
                echo '<option value="' . htmlspecialchars($cat['nombrecategoria']) . '">' . htmlspecialchars($cat['nombrecategoria']) . '</option>';
            }
        }
        ?>
      </select>

      <!-- Select para sabor/tamaño -->
      <select name="sabor" required>
        <option value="">Selecciona tamaño</option>
        <option value="1">Pequeño</option>
        <option value="2">Mediano</option>
        <option value="3">Grande</option>
      </select>

      <button type="submit">Guardar</button>
    </form>
  </div>
</div>
  <!-- Modal flotante para editar producto -->
  <div id="editModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Editar Producto</h2>
      <form action="Editar_productos.php" method="POST" enctype="multipart/form-data">
        <!-- Campo oculto para el ID del producto -->
        <input type="hidden" id="editId" name="id">

        <input type="text" id="editName" name="name" placeholder="Nombre del producto" required>
        <input type="file" id="editImage" name="imagen" accept="image/*">
        <input type="number" id="editPrice" name="precio" placeholder="Precio" required>
        
        <select id="editCategoria" name="categoria" required>
          <option value="">Selecciona categoría</option>
          <?php
          $categoria_result = $conn->query("SELECT nombrecategoria FROM categorias ORDER BY nombrecategoria ASC");
          if ($categoria_result->num_rows > 0) {
              while($cat = $categoria_result->fetch_assoc()) {
                  echo '<option value="' . htmlspecialchars($cat['nombrecategoria']) . '">' . htmlspecialchars($cat['nombrecategoria']) . '</option>';
              }
          }
          ?>
        </select>

        <select id="editSabor" name="sabor" required>
          <option value="">Selecciona tamaño</option>
          <option value="1">Pequeño</option>
          <option value="2">Mediano</option>
          <option value="3">Grande</option>
        </select>

        <button type="submit">Actualizar</button>
      </form>
    </div>
  </div>

  <!-- Scripts -->
  <script>
    // --- Modal de Agregar ---
    const productModal = document.getElementById('productModal');
    const openModalBtn = document.getElementById('openModal');
    const closeBtns = document.querySelectorAll('.modal .close');

    openModalBtn.onclick = () => productModal.style.display = 'flex';

    // Cerrar cualquier modal
    closeBtns.forEach(btn => {
      btn.onclick = () => btn.parentElement.parentElement.style.display = 'none';
    });

    window.onclick = (e) => {
      if (e.target.classList.contains('modal')) {
        e.target.style.display = 'none';
      }
    };

    // --- Modal de Editar ---
    const editModal = document.getElementById('editModal');
    const editButtons = document.querySelectorAll('.product-actions .edit');

    editButtons.forEach(btn => {
      btn.addEventListener('click', () => {
        const card = btn.closest('.product-card');

        // Usamos dataset (data-*) directamente
        const id = card.dataset.id;
        const name = card.dataset.nombre;
        const price = card.dataset.precio;
        const categoria = card.dataset.categoria;
        const sabor = card.dataset.sabor;

        // Rellenamos el modal con los datos del producto
        document.getElementById('editId').value = id;
        document.getElementById('editName').value = name;
        document.getElementById('editPrice').value = price;
        document.getElementById('editCategoria').value = categoria;
        document.getElementById('editSabor').value = sabor;

        // Mostramos el modal
        editModal.style.display = 'flex';
      });
    });

    // --- Eliminar producto ---
    const deleteButtons = document.querySelectorAll('.product-actions .delete');

    deleteButtons.forEach(btn => {
      btn.addEventListener('click', () => {
        const card = btn.closest('.product-card');
        const id = card.dataset.id;

        if (confirm("¿Seguro que deseas eliminar este producto?")) {
          // Petición AJAX para eliminar
          fetch("Eliminar_productos.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded",
            },
            body: "id=" + encodeURIComponent(id),
          })
          .then(res => res.text())
          .then(data => {
            if (data.trim() === "success") {
              alert("Producto eliminado correctamente");
              card.remove(); // Eliminar del DOM sin recargar
            } else {
              alert("Error al eliminar: " + data);
            }
          })
          .catch(err => {
            alert("Error en la solicitud: " + err);
          });
        }
      });
    });
  </script>
</body>
</html>
