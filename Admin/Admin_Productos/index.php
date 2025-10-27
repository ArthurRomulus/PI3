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
      <!-- Cuadro para agregar producto -->
      <div class="product-card add-product" id="openModal">
        <i class="fas fa-plus"></i>
        <span>Agregar Producto</span>
      </div>   
      <?php
      $categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';
      $buscar = isset($_GET['buscar']) ? $_GET['buscar'] : '';

      // Consulta con múltiples categorías
      $sql = "SELECT p.*, GROUP_CONCAT(c.nombrecategoria SEPARATOR ', ') AS categorias
              FROM productos p
              LEFT JOIN producto_categorias pc ON p.idp = pc.idp
              LEFT JOIN categorias c ON pc.id_categoria = c.id_categoria";

      $params = [];
      $types = "";
      $conditions = [];

      if ($buscar) {
          $conditions[] = "p.namep LIKE ?";
          $params[] = "%$buscar%";
          $types .= "s";
      }

      if ($categoria) {
          $conditions[] = "c.nombrecategoria = ?";
          $params[] = $categoria;
          $types .= "s";
      }

      if ($conditions) {
          $sql .= " WHERE " . implode(" AND ", $conditions);
      }

      $sql .= " GROUP BY p.idp";

      $stmt = $conn->prepare($sql);
      if ($params) {
          $stmt->bind_param($types, ...$params);
      }
      $stmt->execute();
      $result = $stmt->get_result();
      

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

            // Obtener categorías del producto (para el modal de editar)
            $cat_actuales = [];
            $cat_query = $conn->query("SELECT c.nombrecategoria FROM producto_categorias pc 
                                        JOIN categorias c ON pc.id_categoria = c.id_categoria
                                        WHERE pc.idp = ".$row['idp']);
            while($c = $cat_query->fetch_assoc()){
                $cat_actuales[] = $c['nombrecategoria'];
            }
              ?>
              <div class="product-card" 
                  data-id="<?php echo $row['idp']; ?>" 
                  data-nombre="<?php echo $row['namep']; ?>" 
                  data-precio="<?php echo $row['precio']; ?>" 
                  data-categorias="<?php echo implode(',', $cat_actuales); ?>" 
                  data-sabor="<?php echo $row['sabor']; ?>" 
                  data-status="<?php echo $row['STOCK']; ?>" 
                  data-imagen="<?php echo $row['ruta_imagen']; ?>">
                  
                  <img src="<?php echo $row['ruta_imagen'] ? $row['ruta_imagen'] : '../../Images/default.png'; ?>" alt="<?php echo $row['namep']; ?>">
                  <span class="product-name"><?php echo $row['namep']; ?></span>
                  <span class="product-categoria"><?php echo htmlspecialchars($row['categorias']); ?></span>
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
    </div>
  </div>

  <!-- Modal Agregar Producto -->
<div id="productModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2>Agregar Producto</h2>
    <form action="Guardar_producto.php" method="POST" enctype="multipart/form-data">
      <input type="text" name="name" placeholder="Nombre del producto" required>
      <input type="file" name="imagen" accept="image/*">
      <input type="number" name="precio" placeholder="Precio" required>
      
      <!-- Select múltiple para categorías -->
      <select id="categoriaSelect" name="categoria[]" multiple required>
        <?php
        $categoria_result = $conn->query("SELECT id_categoria, nombrecategoria FROM categorias ORDER BY nombrecategoria ASC");
        if ($categoria_result->num_rows > 0) {
            while($cat = $categoria_result->fetch_assoc()) {
                echo '<option value="' . $cat['id_categoria'] . '">' . htmlspecialchars($cat['nombrecategoria']) . '</option>';
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

<!-- Modal Editar Producto -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2>Editar Producto</h2>
    <form action="Editar_productos.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" id="editId" name="id">
        <input type="text" id="editName" name="name" placeholder="Nombre del producto" required>
        <input type="file" id="editImage" name="imagen" accept="image/*">
        <input type="number" id="editPrice" name="precio" placeholder="Precio" required>
        
        <!-- Select múltiple para categorías -->
        <select id="editCategoria" name="categoria[]" multiple required>
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

<script>
  // --- Modal Agregar ---
  const productModal = document.getElementById('productModal');
  const openModalBtn = document.getElementById('openModal');
  const closeBtns = document.querySelectorAll('.modal .close');

  openModalBtn.onclick = () => productModal.style.display = 'flex';
  closeBtns.forEach(btn => btn.onclick = () => btn.parentElement.parentElement.style.display = 'none');
  window.onclick = e => { if(e.target.classList.contains('modal')) e.target.style.display = 'none'; }

  // --- Modal Editar ---
  const editModal = document.getElementById('editModal');
  const editButtons = document.querySelectorAll('.product-actions .edit');

  editButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      const card = btn.closest('.product-card');
      const id = card.dataset.id;
      const name = card.dataset.nombre;
      const price = card.dataset.precio;
      const categorias = card.dataset.categorias.split(',');
      const sabor = card.dataset.sabor;

      document.getElementById('editId').value = id;
      document.getElementById('editName').value = name;
      document.getElementById('editPrice').value = price;
      document.getElementById('editSabor').value = sabor;

      // Marcar las categorías seleccionadas
      const select = document.getElementById('editCategoria');
      Array.from(select.options).forEach(option => {
          option.selected = categorias.includes(option.value);
      });

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
        fetch("Eliminar_productos.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: "id=" + encodeURIComponent(id),
        })
        .then(res => res.text())
        .then(data => {
          if (data.trim() === "success") {
            alert("Producto eliminado correctamente");
            card.remove();
          } else {
            alert("Error al eliminar: " + data);
          }
        })
        .catch(err => alert("Error en la solicitud: " + err));
      }
    });
  });

  // Hacer que se puedan seleccionar varias opciones solo con click (Agregar)
  const categoriaSelect = document.getElementById('categoriaSelect');
  if(categoriaSelect){
      categoriaSelect.addEventListener('mousedown', function(e) {
          e.preventDefault();
          const option = e.target;
          if (option.tagName === 'OPTION') {
              option.selected = !option.selected;
          }
      });
  }

  // Hacer que se puedan seleccionar varias opciones solo con click (Editar)
  const editCategoriaSelect = document.getElementById('editCategoria');
  if(editCategoriaSelect){
      editCategoriaSelect.addEventListener('mousedown', function(e) {
          e.preventDefault();
          const option = e.target;
          if (option.tagName === 'OPTION') {
              option.selected = !option.selected;
          }
      });
  }


</script>
</body>
</html>
