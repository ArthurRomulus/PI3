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
  <h3 data-translate="Categorías">Categorías</h3>
  <form method="GET" action="index.php">
      <input type="search" name="buscar" placeholder="Buscar..." data-translate-placeholder="Buscar..."
       value="<?php echo isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : ''; ?>"
       onkeypress="if(event.key === 'Enter'){this.form.submit();}">

      <?php if(isset($_GET['categoria'])): ?>
          <input type="hidden" name="categoria" value="<?php echo htmlspecialchars($_GET['categoria']); ?>">
      <?php endif; ?>
  </form>
  <div class="button-group">
    <a href="index.php"><button data-translate="Todo">Todo</button></a>
    <button id="openListboxModal" class="add-listbox-btn">
      <i class="fas fa-list"></i> <span data-translate="Agregar lista de opciones">Agregar lista de opciones</span>
    </button>
    <?php
    $categoria_query = "SELECT nombrecategoria FROM categorias ORDER BY nombrecategoria ASC";
    $categoria_result = $conn->query($categoria_query);

    if ($categoria_result->num_rows > 0) {
        while($cat = $categoria_result->fetch_assoc()) {
            $cat_nombre = htmlspecialchars($cat['nombrecategoria']);
            echo '<a href="index.php?categoria=' . urlencode($cat_nombre) . '">
                    <button><span data-translate="' . $cat_nombre . '">' . $cat_nombre . '</span></button>
                  </a>';
        }
    }
    ?>
     <!--  Botón para agregar nueva categoría -->
    <button 
        class="btn btn-success" 
        style="margin-left:10px;" 
        onclick="mostrarFormularioCategoria()"
    >
     <i class="fas fa-plus"></i>  <span data-translate="Nueva categoría">Nueva categoría</span>
    </button>
  </div>


  <h3>Productos</h3>
  <div class="products-container">
    <!-- Cuadro para agregar producto -->
    <div class="product-card add-product" id="openModal">
      <i class="fas fa-plus"></i>
      <span data-translate="Agregar producto">Agregar Producto</span>
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
                data-stock="<?php echo $row['STOCK']; ?>" 
                data-imagen="<?php echo $row['ruta_imagen']; ?>"
                data-descripcion="<?php echo htmlspecialchars($row['descripcion'], ENT_QUOTES); ?>"
                >
                
                <img src="<?php echo $row['ruta_imagen'] ? $row['ruta_imagen'] : '../../Images/default.png'; ?>" alt="<?php echo $row['namep']; ?>">
                <span class="product-name" data-translate="<?php echo htmlspecialchars($row['namep']); ?>">
                    <?php echo htmlspecialchars($row['namep']); ?>
                </span>
                 <span class="product-categoria" data-translate="<?php echo htmlspecialchars(implode(', ', $cat_actuales)); ?>">
                    <?php echo htmlspecialchars(implode(', ', $cat_actuales)); ?>
                </span>
                <span class="product-price">$<?php echo number_format($row['precio'], 2); ?></span>
                <span class="product-stock">Stock: <?php echo htmlspecialchars($row['STOCK']); ?></span>
                <span class="product-sabor" data-translate="<?php echo $sabor_texto; ?>">
                    <?php echo $sabor_texto; ?>
                </span>
                <span class="product-descripcion" data-translate="<?php echo htmlspecialchars($row['descripcion']); ?>">
                    <?php echo htmlspecialchars($row['descripcion']); ?>
                </span>
                
                <div class="product-actions">
                    <i class="fas fa-pen edit" title="Editar"></i>
                    <i class="fas fa-trash delete" title="Eliminar"></i>
                </div>
            </div>
            <?php
        }
    } else {
        echo '<p data-translate="No hay productos registrados.">No hay productos registrados.</p>';
    }
    ?>
  </div>
</div>

<!-- Modal Agregar Producto -->
<div id="productModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2 data-translate="Agregar producto">Agregar Producto</h2>
    <form action="Guardar_producto.php" method="POST" enctype="multipart/form-data">
      <input type="text" name="name" placeholder="Nombre del producto" 
      data-translate-placeholder="Nombre del producto" required>
      <textarea name="descripcion" rows="3" placeholder="Descripción del producto"
          data-translate-placeholder="Descripción del producto"></textarea>
      <input type="file" name="imagen" accept="image/*">
      <input type="number" name="precio" id="precioBase" placeholder="Precio base"
       data-translate-placeholder="Precio base" required>
      <input type="number" name="stock" placeholder="Stock disponible"
        data-translate-placeholder="Stock" min="0" required>
      <span data-translate="Precio final:">Precio final: $<span id="precioFinal">0.00</span></span>

      <select id="categoriaSelect" name="categoria[]" multiple required></select>

      <select name="sabor" required>
        <option value="" data-translate="Selecciona tamaño">Selecciona tamaño</option>
        <option value="1" data-translate="Pequeño">Pequeño</option>
        <option value="2" data-translate="Mediano">Mediano</option>
        <option value="3" data-translate="Grande">Grande</option>
      </select>

      <h3 data-translate="Opciones Personalizadas (Opcional)">Opciones Personalizadas (Opcional)</h3>
      <div id="listboxContainer" style="max-height:300px; overflow-y:auto;"></div>

      <button type="submit" data-translate="Guardar">Guardar</button>
    </form>
  </div>
</div>

<!-- Modal Editar -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2 data-translate="Editar Producto">Editar Producto</h2>
    <form action="Editar_productos.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" id="editId" name="id">
      <input type="text" id="editName" name="name" required>
      <textarea id="editDescripcion" name="descripcion" rows="3"></textarea>
      <input type="file" id="editImage" name="imagen" accept="image/*">
      <input type="number" id="editPrice" name="precio" required>
      <label for="editStock" data-translate="Stock disponible">Stock disponible</label>
      <input type="number" id="editStock" name="stock" min="0" required>
      <span data-translate="Precio final:">Precio final: $<span id="editPrecioFinal">0.00</span></span>

      <select id="editCategoria" name="categoria[]" multiple required></select>

      <select id="editSabor" name="sabor" required>
        <option value="" data-translate="Selecciona tamaño">Selecciona tamaño</option>
        <option value="1" data-translate="Pequeño">Pequeño</option>
        <option value="2" data-translate="Mediano">Mediano</option>
        <option value="3" data-translate="Grande">Grande</option>
      </select>

      <h3 data-translate="Opciones Personalizadas">Opciones Personalizadas</h3>
      <div id="editListboxContainer"></div>

      <button type="submit" data-translate="Actualizar">Actualizar</button>
    </form>
  </div>
</div>

<!-- Modal Agregar Listbox -->
<div id="listboxModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2 data-translate="Agregar lista de opciones">Agregar lista de opciones</h2>
    <form action="Guardar_listbox.php" method="POST">
      <input type="text" name="nombre_listbox" placeholder="Nombre de la lista de opciones" data-translate-placeholder ="Nombre de la lista de opciones" required>
      
      <h4 data-translate="Opciones">Opciones</h4>
      <div id="opcionesContainer">
        <div class="opcion-item">
          <input type="text" name="opciones_valor[]" placeholder="Valor (ej. Latte)" data-translate-placeholder ="Valor (ej. Latte)" required>
          <input type="number" step="0.01" name="opciones_precio[]" placeholder="Precio adicional" data-translate-placeholder ="Precio adicional" required>
          <button type="button" class="remove-opcion">🗑</button>
        </div>
      </div>
      <button type="button" id="addOpcion" data-translate="Agregar opción">Agregar opción</button>
      <br><br>
      <button data-translate="Guardar">Guardar</button>
    </form>
  </div>
</div>

<!-- Modal para agregar categoría -->
<div id="modalCategoria" class="modal">
  <div class="modal-content">
        <h4 data-translate="Nueva categoría">Nueva categoría</h4>
        <form method="POST" action="agregar_categoria.php">
            <input type="text" name="nombrecategoria" placeholder="Nombre de la categoría" 
                   class="form-control" data-translate-placeholder="Nombre de la categoría" required>
            <button type="submit" class="btn btn-primary" data-translate="Guardar">
                Guardar
            </button>
            <button type="button" class="btn btn-secondary" 
                    onclick="cerrarModalCategoria()" 
                    data-translate="Cancelar">
                Cancelar
            </button>
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
                echo "select.innerHTML += `<option value='{$cat['id_categoria']}' data-translate='{$cat['nombrecategoria']}'>{$cat['nombrecategoria']}</option>`;";
            }
            ?>
            enableFriendlyMultiSelect(select);

            // Marcar las categorías ya seleccionadas
            seleccionadas.forEach(id => {
                const opt = select.querySelector(`option[value='${id}']`);
                if(opt) opt.selected = true;
            });
             // Aplica traducción automáticamente
            if (typeof applyTranslation === "function") {
            const lang = localStorage.getItem("lang") || "es";
            applyTranslation(lang);
        }        
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
        label.dataset.translate = listbox.listbox_nombre;

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
          opt.dataset.translate = `${op.valor} (+$${op.precio})`;
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
      // --- APLICAR TRADUCCIÓN AQUÍ ---
      if (typeof applyTranslation === "function") {
        const lang = localStorage.getItem("lang") || "es";
        applyTranslation(lang);
        }
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
        if (card.dataset.stock !== undefined) {
        document.getElementById('editStock').value = card.dataset.stock;
    }

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

// --- Modal Agregar Listbox ---
const openListboxModalBtn = document.getElementById('openListboxModal');
const listboxModal = document.getElementById('listboxModal');
const addOpcionBtn = document.getElementById('addOpcion');
const opcionesContainer = document.getElementById('opcionesContainer');

// Abrir modal
openListboxModalBtn.onclick = () => {
  listboxModal.style.display = 'flex';
  // 🔹 Forzar traducción al abrir el modal
  if (typeof applyTranslation === "function") {
    const lang = localStorage.getItem("lang") || "es";
    applyTranslation(lang);
  }
};

// Cerrar modal
listboxModal.querySelector('.close').onclick = () => {
  listboxModal.style.display = 'none';
};
window.onclick = e => { if(e.target === listboxModal) listboxModal.style.display = 'none'; };

// Agregar más opciones
addOpcionBtn.onclick = () => {
  const div = document.createElement('div');
  div.classList.add('opcion-item');
  div.innerHTML = `
    <input type="text" name="opciones_valor[]" 
           placeholder="Valor (ej. Vainilla)" 
           data-translate-placeholder="Valor (ej. Vainilla)" required>
    <input type="number" step="0.01" name="opciones_precio[]" 
           placeholder="Precio adicional" 
           data-translate-placeholder="Precio adicional" required>
    <button type="button" class="remove-opcion" data-translate="Eliminar opción">🗑</button>
  `;
  opcionesContainer.appendChild(div);

  //  Forzar traducción en las nuevas opciones
  if (typeof applyTranslation === "function") {
    const lang = localStorage.getItem("lang") || "es";
    applyTranslation(lang);
  }
};


// Eliminar opción
opcionesContainer.addEventListener('click', e => {
  if (e.target.classList.contains('remove-opcion')) {
    e.target.parentElement.remove();
  }
});

// --- Modal Agregar Categoría ---
function mostrarFormularioCategoria() {
    document.getElementById('modalCategoria').style.display = 'flex';
}
function cerrarModalCategoria() {
    document.getElementById('modalCategoria').style.display = 'none';
}
</script>
<script src="../../translate.js"></script>
</body>
</html>
