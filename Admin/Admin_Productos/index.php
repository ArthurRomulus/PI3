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
  <a href="index.php"><button>Categorías</button></a>
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

            // Obtener opciones actuales del producto
            $listbox_query = $conn->query("SELECT opciones FROM producto_opciones WHERE idp = ".$row['idp']);
            $listbox_arr = [];
            while($lb = $listbox_query->fetch_assoc()){
                $listbox_arr[] = $lb['opciones'];
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

<!-- Modal Editar Producto -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2>Editar Producto</h2>
    <form action="Editar_productos.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" id="editId" name="id">
        <input type="text" id="editName" name="name" placeholder="Nombre del producto" required>
        <textarea id="editDescripcion" name="descripcion" placeholder="Descripción del producto" rows="3"></textarea>
        <input type="file" id="editImage" name="imagen" accept="image/*">
        <input type="number" id="editPrice" name="precio" placeholder="Precio" required>
        <span>Precio final: $<span id="editPrecioFinal">0.00</span></span>

        <select id="editCategoria" name="categoria[]" multiple required>
          <?php
          $categoria_result = $conn->query("SELECT id_categoria, nombrecategoria FROM categorias ORDER BY nombrecategoria ASC");
          if ($categoria_result->num_rows > 0) {
              while($cat = $categoria_result->fetch_assoc()) {
                  echo '<option value="' . $cat['id_categoria'] . '">' . htmlspecialchars($cat['nombrecategoria']) . '</option>';
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

        <h3>Opciones Personalizadas</h3>
        <div id="editListboxContainer"></div>

        <button type="submit">Actualizar</button>
    </form>
  </div>
</div>

<script>
const productModal = document.getElementById('productModal');
const openModalBtn = document.getElementById('openModal');
const closeBtns = document.querySelectorAll('.modal .close');
const categoriaSelect = document.getElementById('categoriaSelect');
const listboxContainer = document.getElementById('listboxContainer');
const precioBaseInput = document.getElementById('precioBase');
const precioFinalSpan = document.getElementById('precioFinal');

// Abrir modal Agregar
openModalBtn.onclick = () => {
    productModal.style.display = 'flex';
    cargarListboxesBase();
    actualizarPrecio();
};

// Cerrar modales
closeBtns.forEach(btn => btn.onclick = () => btn.parentElement.parentElement.style.display = 'none');
window.onclick = e => { if(e.target.classList.contains('modal')) e.target.style.display = 'none'; }

// Hacer multi-select amigable
function enableFriendlyMultiSelect(select) {
  select.addEventListener('mousedown', function (e) {
    e.preventDefault();
    const option = e.target;
    option.selected = !option.selected;
    select.dispatchEvent(new Event('change', { bubbles: true }));
  });
}

enableFriendlyMultiSelect(categoriaSelect);

// Crear listbox
function crearListboxBase(index, categoriaId, categoriaNombre) {
    const listboxDiv = document.createElement('div');
    listboxDiv.classList.add('listbox-item');
    listboxDiv.innerHTML = `<hr>
        <h4>${categoriaNombre} (Opcional)</h4>
        <div class="opciones-container"></div>
    `;
    listboxContainer.appendChild(listboxDiv);
    const container = listboxDiv.querySelector('.opciones-container');

    fetch(`get_opciones_categoria.php?id_categoria=${categoriaId}`)
        .then(res => res.json())
        .then(data => {
            if (!data || data.length === 0) return;
            const grupos = {};
            data.forEach(op => {
                const key = op.nombre_opcion || 'Opciones';
                if (!grupos[key]) grupos[key] = [];
                grupos[key].push(op);
            });

            let grupoIndex = 0;
            Object.keys(grupos).forEach(nombre_opcion => {
                const grupoDiv = document.createElement('div');
                grupoDiv.classList.add('grupo-opcion');
                grupoDiv.innerHTML = `<label>${nombre_opcion}</label>
                                      <select name="listbox[${index}][opciones][${grupoIndex}][]" multiple></select>`;
                container.appendChild(grupoDiv);

                const select = grupoDiv.querySelector('select');
                grupos[nombre_opcion].forEach(op => {
                    const option = document.createElement('option');
                    option.value = op.valor;
                    option.textContent = `${op.valor} (+$${parseFloat(op.precio).toFixed(2)})`;
                    option.dataset.precio = op.precio;
                    select.appendChild(option);
                });

                select.addEventListener('change', actualizarPrecio);
                enableFriendlyMultiSelect(select);
                grupoIndex++;
            });
        });
}

// Cargar listboxes según categorías seleccionadas
function cargarListboxesBase() {
    const selected = Array.from(categoriaSelect.selectedOptions).map(o => ({id:o.value, nombre:o.textContent}));
    listboxContainer.innerHTML = '';
    selected.forEach((cat, idx) => crearListboxBase(idx+1, cat.id, cat.nombre));
}

// Calcular precio final
function actualizarPrecio() {
    let base = parseFloat(precioBaseInput.value) || 0;
    let adicional = 0;
    document.querySelectorAll('#listboxContainer .listbox-item select').forEach(select => {
        Array.from(select.selectedOptions).forEach(option => {
            adicional += parseFloat(option.dataset.precio) || 0;
        });
    });
    precioFinalSpan.textContent = (base + adicional).toFixed(2);
}

precioBaseInput.addEventListener('input', actualizarPrecio);
categoriaSelect.addEventListener('change', cargarListboxesBase);

// --- Editar producto ---
const editModal = document.getElementById('editModal');
const editId = document.getElementById('editId');
const editName = document.getElementById('editName');
const editDescripcion = document.getElementById('editDescripcion');
const editPrice = document.getElementById('editPrice');
const editCategoria = document.getElementById('editCategoria');
const editSabor = document.getElementById('editSabor');
const editListboxContainer = document.getElementById('editListboxContainer');
const editPrecioFinal = document.getElementById('editPrecioFinal');

enableFriendlyMultiSelect(editCategoria);

function crearListboxEditar(index, categoriaId, categoriaNombre, selectedOptions = []) {
    const listboxDiv = document.createElement('div');
    listboxDiv.classList.add('listbox-item');
    listboxDiv.innerHTML = `<hr>
        <h4>${categoriaNombre} (Opcional)</h4>
        <div class="opciones-container"></div>
    `;
    editListboxContainer.appendChild(listboxDiv);
    const container = listboxDiv.querySelector('.opciones-container');

    fetch(`get_opciones_categoria.php?id_categoria=${categoriaId}`)
        .then(res => res.json())
        .then(data => {
            if (!data || data.length === 0) return;

            const grupos = {};
            data.forEach(op => {
                const key = op.nombre_opcion || 'Opciones';
                if (!grupos[key]) grupos[key] = [];
                grupos[key].push(op);
            });

            let grupoIndex = 0;
            Object.keys(grupos).forEach(nombre_opcion => {
                const grupoDiv = document.createElement('div');
                grupoDiv.classList.add('grupo-opcion');
                grupoDiv.innerHTML = `<label>${nombre_opcion}</label>
                                      <select name="listbox[${index}][opciones][${grupoIndex}][]" multiple></select>`;
                container.appendChild(grupoDiv);

                const select = grupoDiv.querySelector('select');
                grupos[nombre_opcion].forEach(op => {
                    const option = document.createElement('option');
                    option.value = op.valor;
                    option.textContent = `${op.valor} (+$${parseFloat(op.precio).toFixed(2)})`;
                    option.dataset.precio = op.precio;
                    if (selectedOptions.includes(op.valor)) option.selected = true;
                    select.appendChild(option);
                });

                select.addEventListener('change', actualizarPrecioEditar);
                enableFriendlyMultiSelect(select);
                grupoIndex++;
            });
        });
}

function cargarListboxesEditar() {
    const selected = Array.from(editCategoria.selectedOptions).map(o => ({id:o.value, nombre:o.textContent}));
    const productCard = document.querySelector(`.product-card[data-id="${editId.value}"]`);
    let selectedValues = [];
    if (productCard) {
        selectedValues = JSON.parse(productCard.dataset.listbox || '[]');
    }

    editListboxContainer.innerHTML = '';
    selected.forEach((cat, idx) => crearListboxEditar(idx+1, cat.id, cat.nombre, selectedValues));
    actualizarPrecioEditar();
}

function actualizarPrecioEditar() {
    let base = parseFloat(editPrice.value) || 0;
    let adicional = 0;
    document.querySelectorAll('#editListboxContainer .listbox-item select').forEach(select => {
        Array.from(select.selectedOptions).forEach(option => {
            adicional += parseFloat(option.dataset.precio) || 0;
        });
    });
    editPrecioFinal.textContent = (base + adicional).toFixed(2);
}

editCategoria.addEventListener('change', cargarListboxesEditar);
editPrice.addEventListener('input', actualizarPrecioEditar);

document.querySelectorAll('.product-card .edit').forEach(btn => {
    btn.addEventListener('click', e => {
        const card = e.target.closest('.product-card');
        editId.value = card.dataset.id;
        editName.value = card.dataset.nombre;
        editDescripcion.value = card.dataset.descripcion;
        editPrice.value = card.dataset.precio;
        editSabor.value = card.dataset.sabor;

        const catIds = card.dataset.categorias_ids.split(',').map(id => id.trim());
        Array.from(editCategoria.options).forEach(opt => {
            opt.selected = catIds.includes(opt.value);
        });

        cargarListboxesEditar();
        editModal.style.display = 'flex';
    });
});

// Eliminar producto
document.querySelectorAll('.product-card .delete').forEach(btn => {
    btn.addEventListener('click', e => {
        if(confirm('¿Seguro que deseas eliminar este producto?')){
            const id = e.target.closest('.product-card').dataset.id;
            window.location.href = `Eliminar_productos.php?id=${id}`;
        }
    });
});
</script>

</body>
</html>
