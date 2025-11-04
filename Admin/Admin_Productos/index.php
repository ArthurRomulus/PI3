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
  <link rel="stylesheet" href="../general.css">
  <script src="../../theme-toggle.js" defer></script>
</head>
<body>
<?php include '../Admin_nav_bar.php'; ?>

<div class="content">
  <div class="top-bar">
    <?php include "../AdminProfileSesion.php"; ?>
  </div>

  <h1>Blackwood Coffee</h1>
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
    <a href="index.php"><button>Todo</button></a>
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
  </div>

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
        while ($row = $result->fetch_assoc()){
            $sabor_texto = '';
            switch($row['sabor']){
                case 1: $sabor_texto = 'Pequeño'; break;
                case 2: $sabor_texto = 'Mediano'; break;
                case 3: $sabor_texto = 'Grande'; break;
                default: $sabor_texto = 'Desconocido';
            }

            $cat_actuales = [];
            $cat_ids = [];
            $cat_query = $conn->query("SELECT c.id_categoria, c.nombrecategoria FROM producto_categorias pc 
                                        JOIN categorias c ON pc.id_categoria = c.id_categoria
                                        WHERE pc.idp = ".$row['idp']);
            while($c = $cat_query->fetch_assoc()){
                $cat_actuales[] = $c['nombrecategoria'];
                $cat_ids[] = $c['id_categoria'];
            }

            $listbox_arr = [];
            $listbox_query = $conn->query("
                SELECT l.nombre AS listbox, o.valor, o.precio
                FROM producto_opciones po
                JOIN listbox_opciones o ON po.listbox_opcion_id = o.id
                JOIN listboxes l ON o.listbox_id = l.id
                WHERE po.producto_id = ".$row['idp']."
                ORDER BY l.id, o.id
            ");

            while($lb = $listbox_query->fetch_assoc()){
                $listbox_arr[$lb['listbox']][] = ['valor' => $lb['valor'], 'precio' => $lb['precio']];
            }
            $data_listbox = htmlspecialchars(json_encode($listbox_arr), ENT_QUOTES);

            ?>
            <div class="product-card" 
                data-id="<?php echo $row['idp']; ?>" 
                data-nombre="<?php echo $row['namep']; ?>" 
                data-precio="<?php echo $row['precio']; ?>" 
                data-categorias="<?php echo implode(',', $cat_actuales); ?>" 
                data-categorias_ids="<?php echo implode(',', $cat_ids); ?>"
                data-listbox="<?php echo $data_listbox; ?>"
                data-sabor="<?php echo $row['sabor']; ?>" 
                data-status="<?php echo $row['STOCK']; ?>" 
                data-imagen="<?php echo $row['ruta_imagen']; ?>"
                data-descripcion="<?php echo htmlspecialchars($row['descripcion'], ENT_QUOTES); ?>"
                >
                
                <img src="<?php echo $row['ruta_imagen'] ? $row['ruta_imagen'] : '../../Images/default.png'; ?>" alt="<?php echo $row['namep']; ?>">
                <span class="product-name"><?php echo $row['namep']; ?></span>
                <span class="product-categoria"><?php echo htmlspecialchars($row['categorias']); ?></span>
                <span class="product-price">$<?php echo number_format($row['precio'], 2); ?></span>
                <span class="product-sabor"><?php echo $sabor_texto; ?></span>
                <span class="product-descripcion"><?php echo htmlspecialchars($row['descripcion']); ?></span>
                
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
      <textarea name="descripcion" placeholder="Descripción del producto" rows="3"></textarea>
      <input type="file" name="imagen" accept="image/*">
      <input type="number" name="precio" id="precioBase" placeholder="Precio base" required>
      <span>Precio final: $<span id="precioFinal">0.00</span></span>

      <select id="categoriaSelect" name="categoria[]" multiple required></select>

      <select name="sabor" required>
        <option value="">Selecciona tamaño</option>
        <option value="1">Pequeño</option>
        <option value="2">Mediano</option>
        <option value="3">Grande</option>
      </select>

      <h3>Opciones Personalizadas (Opcional)</h3>
      <div id="listboxContainer" style="max-height:300px; overflow-y:auto;"></div>

      <button type="submit">Guardar</button>
    </form>
  </div>
</div>

<!-- Modal Editar -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2>Editar Producto</h2>
    <form action="Editar_productos.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" id="editId" name="id">
      <input type="text" id="editName" name="name" required>
      <textarea id="editDescripcion" name="descripcion" rows="3"></textarea>
      <input type="file" id="editImage" name="imagen" accept="image/*">
      <input type="number" id="editPrice" name="precio" required>
      <span>Precio final: $<span id="editPrecioFinal">0.00</span></span>

      <select id="editCategoria" name="categoria[]" multiple required></select>

      <select id="editSabor" name="sabor" required>
        <option value="">Selecciona tamaño</option>
        <option value="1">Pequeño</option>
        <option value="2">Mediano</option>
        <option value="3">Grande</option>
      </select>

      <h3>Opciones Personalizadas</h3>
      <div id="editListboxContainer"></div>

      <button type="submit">Actualizar</button>
    </form>
  </div>
</div>

<script>
const openModalBtn = document.getElementById('openModal');
const productModal = document.getElementById('productModal');
const editModal = document.getElementById('editModal');
const closeBtns = document.querySelectorAll('.modal .close');

// Abrir modal
openModalBtn.onclick = () => {
    productModal.style.display = 'flex';
    cargarCategorias('#categoriaSelect');
    cargarListboxes('#listboxContainer');
    actualizarPrecio();
};

closeBtns.forEach(btn => btn.onclick = () => btn.closest('.modal').style.display = 'none');
window.onclick = e => { if(e.target.classList.contains('modal')) e.target.style.display = 'none'; }

// --- Selección solo con click ---
function enableFriendlyMultiSelect(select) {
    select.addEventListener('mousedown', e => {
        e.preventDefault();
        e.target.selected = !e.target.selected;
        select.dispatchEvent(new Event('change'));
    });
}

// --- Cargar categorías ---
function cargarCategorias(selectId, seleccionadas = []) {
    const select = document.querySelector(selectId);
    select.innerHTML = '';
    fetch('../../conexion.php') 
        .then(() => {
            <?php
            $cat_res = $conn->query("SELECT id_categoria, nombrecategoria FROM categorias ORDER BY nombrecategoria ASC");
            while($cat = $cat_res->fetch_assoc()) {
                echo "select.innerHTML += `<option value='{$cat['id_categoria']}'>{$cat['nombrecategoria']}</option>`;";
            }
            ?>
            enableFriendlyMultiSelect(select);

            // Marcar las categorías ya seleccionadas
            seleccionadas.forEach(id => {
                const opt = select.querySelector(`option[value='${id}']`);
                if(opt) opt.selected = true;
            });
        });
}


// --- Cargar listboxes (todos siempre visibles) ---
function cargarListboxes(containerId) {
  const container = document.querySelector(containerId);
  container.innerHTML = '';

  fetch('get_todas_opciones.php')
    .then(res => res.json())
    .then(data => {
      if (!data || !Array.isArray(data)) return;

      let index = 0;
      data.forEach(listbox => {
        const div = document.createElement('div');
        div.style.border = "1px solid #ccc";
        div.style.padding = "5px";
        div.style.marginBottom = "5px";
        div.style.display = "flex";
        div.style.flexDirection = "column";

        // Contenedor de label y checkbox
        const labelContainer = document.createElement('div');
        labelContainer.style.display = "flex";
        labelContainer.style.alignItems = "center";
        labelContainer.style.marginBottom = "5px";

        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.name = 'listbox_selected[]';
        checkbox.value = listbox.listbox_id; // ID real del listbox
        checkbox.style.marginRight = "5px";

        const label = document.createElement('label');
        label.textContent = listbox.listbox_nombre;

        labelContainer.appendChild(checkbox);
        labelContainer.appendChild(label);

        // Select de opciones
        const sel = document.createElement('select');
        sel.multiple = true;
        sel.style.width = "100%";
        sel.style.marginTop = "5px";
        sel.style.maxHeight = "120px";
        sel.style.overflowY = "auto";
        sel.disabled = true;

        // Rellenar las opciones
        listbox.opciones.forEach(op => {
          const opt = document.createElement('option');
          opt.value = op.opcion_id;
          opt.textContent = `${op.valor} (+$${op.precio})`;
          opt.dataset.precio = op.precio;
          sel.appendChild(opt);
        });

        div.appendChild(labelContainer);
        div.appendChild(sel);
        container.appendChild(div);
        index++;
      });

      // Habilitar o deshabilitar selects cuando se seleccionan los listboxes
      container.querySelectorAll('input[type="checkbox"]').forEach(cb => {
        cb.addEventListener('change', () => {
          const select = cb.closest('div').querySelector('select');
          select.disabled = !cb.checked;
          if (!cb.checked) select.selectedIndex = -1; // limpiar si se desmarca

          // Limitar máximo 2 listboxes
          const checked = container.querySelectorAll('input[type="checkbox"]:checked');
          if (checked.length > 2) {
            cb.checked = false;
            select.disabled = true;
            alert('Solo puedes seleccionar máximo 2 listboxes.');
          }
        });
      });
    })
    .catch(err => console.error("Error cargando listboxes:", err));
}




// --- Precio final ---
const precioBaseInput = document.getElementById('precioBase');
const precioFinalSpan = document.getElementById('precioFinal');
function actualizarPrecio() {
    let base = parseFloat(precioBaseInput.value) || 0;
    let adicional = 0;
    document.querySelectorAll('#listboxContainer select').forEach(sel => {
        Array.from(sel.selectedOptions).forEach(opt => adicional += parseFloat(opt.dataset.precio)||0);
    });
    precioFinalSpan.textContent = (base+adicional).toFixed(2);
}
precioBaseInput.addEventListener('input', actualizarPrecio);

// --- Editar producto ---
document.querySelectorAll('.product-card .edit').forEach(btn => {
    btn.onclick = e => {
        const card = e.target.closest('.product-card');
        document.getElementById('editId').value = card.dataset.id;
        document.getElementById('editName').value = card.dataset.nombre;
        document.getElementById('editDescripcion').value = card.dataset.descripcion;
        document.getElementById('editPrice').value = card.dataset.precio;
        // Categorías seleccionadas
        const cat_ids = card.dataset.categorias_ids ? card.dataset.categorias_ids.split(',') : [];
        cargarCategorias('#editCategoria', cat_ids);
        // Asignar tamaño actual
        const sabor = card.dataset.sabor; // 1,2 o 3
        document.getElementById('editSabor').value = sabor;

        const listboxData = card.dataset.listbox ? JSON.parse(card.dataset.listbox) : {};
        cargarListboxes('#editListboxContainer', listboxData);
        editModal.style.display = 'flex';
    };
});

// --- Eliminar producto ---
document.querySelectorAll('.product-card .delete').forEach(btn => {
    btn.onclick = e => {
        if(confirm('¿Seguro que deseas eliminar este producto?')){
            const id = e.target.closest('.product-card').dataset.id;
            window.location.href = `Eliminar_productos.php?id=${id}`;
        }
    };
});
</script>

</body>
</html>
