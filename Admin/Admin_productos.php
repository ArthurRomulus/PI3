<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Productos</title>
  <link rel="stylesheet" href="Admin_nav_bar.css">
  <link rel="stylesheet" href="Admin_productos.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <?php include 'Admin_nav_bar.php'; ?>

  <div class="content">
    <div class="top-bar">
      <div class="admin-profile">
        <span class="admin-name">Admin - Luis Perez Ponzo</span>
        <img src="img/admin.png" alt="Perfil Admin">
      </div>
    </div>

    <h1>Coffee Shop</h1>
    <h3>Categorías</h3>
    <input type="search" placeholder="Buscar...">

    <div class="button-group">
      <button>Frappés</button>
      <button>Cafés</button>
      <button>Comida</button>
      <button>Postres</button>
      <button>Panes</button>
      <button>Sin café</button>
      <button>Temporada</button>
      <button>Categorías</button>
    </div>

    <!-- Sección de productos -->
    <h3>Productos</h3>
    <div class="products-container">
      <!-- Productos de ejemplo -->
      <div class="product-card">
        <img src="img/Cafe_Americano.png" alt="Producto 1">
        <span class="product-name">Café Latte</span>
        <span class="product-price">$45.00</span>
        <div class="product-actions">
          <i class="fas fa-pen edit" title="Editar"></i>
          <i class="fas fa-trash delete" title="Eliminar"></i>
        </div>
      </div>

      <div class="product-card">
        <img src="img/Cafe_Americano.png" alt="Producto 1">
        <span class="product-name">Café Latte</span>
        <span class="product-price">$45.00</span>
        <div class="product-actions">
          <i class="fas fa-pen edit" title="Editar"></i>
          <i class="fas fa-trash delete" title="Eliminar"></i>
        </div>
      </div>

      <div class="product-card">
        <img src="img/Cafe_Americano.png" alt="Producto 1">
        <span class="product-name">Café Latte</span>
        <span class="product-price">$45.00</span>
        <div class="product-actions">
          <i class="fas fa-pen edit" title="Editar"></i>
          <i class="fas fa-trash delete" title="Eliminar"></i>
        </div>
      </div>

      <div class="product-card">
        <img src="img/Cafe_Americano.png" alt="Producto 1">
        <span class="product-name">Café Latte</span>
        <span class="product-price">$45.00</span>
        <div class="product-actions">
          <i class="fas fa-pen edit" title="Editar"></i>
          <i class="fas fa-trash delete" title="Eliminar"></i>
        </div>
      </div>

      <div class="product-card">
        <img src="img/Cafe_Americano.png" alt="Producto 1">
        <span class="product-name">Café Latte</span>
        <span class="product-price">$45.00</span>
        <div class="product-actions">
          <i class="fas fa-pen edit" title="Editar"></i>
          <i class="fas fa-trash delete" title="Eliminar"></i>
        </div>
      </div>

      <div class="product-card">
        <img src="img/Cafe_Americano.png" alt="Producto 1">
        <span class="product-name">Café Latte</span>
        <span class="product-price">$45.00</span>
        <div class="product-actions">
          <i class="fas fa-pen edit" title="Editar"></i>
          <i class="fas fa-trash delete" title="Eliminar"></i>
        </div>
      </div>

      <div class="product-card">
        <img src="img/Cafe_Americano.png" alt="Producto 1">
        <span class="product-name">Café Latte</span>
        <span class="product-price">$45.00</span>
        <div class="product-actions">
          <i class="fas fa-pen edit" title="Editar"></i>
          <i class="fas fa-trash delete" title="Eliminar"></i>
        </div>
      </div>

      <div class="product-card">
        <img src="img/Cafe_Americano.png" alt="Producto 1">
        <span class="product-name">Café Latte</span>
        <span class="product-price">$45.00</span>
        <div class="product-actions">
          <i class="fas fa-pen edit" title="Editar"></i>
          <i class="fas fa-trash delete" title="Eliminar"></i>
        </div>
      </div>

      <div class="product-card">
        <img src="img/Cafe_Americano.png" alt="Producto 1">
        <span class="product-name">Café Latte</span>
        <span class="product-price">$45.00</span>
        <div class="product-actions">
          <i class="fas fa-pen edit" title="Editar"></i>
          <i class="fas fa-trash delete" title="Eliminar"></i>
        </div>
      </div>

      <!-- Cuadro para agregar producto (solo uno al final del grid) -->
      <div class="product-card add-product" id="openModal">
        <i class="fas fa-plus"></i>
        <span>Agregar Producto</span>
      </div>   
    </div>
  </div>

  <!-- Modal flotante para agregar producto -->
  <div id="productModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Agregar Producto</h2>
      <input type="text" placeholder="Nombre del producto">
      <input type="file" accept="image/*">
      <input type="number" placeholder="Precio">
      <button>Guardar</button>
    </div>
  </div>

  <!-- Modal flotante para editar producto -->
  <div id="editModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Editar Producto</h2>
      <input type="text" id="editName" placeholder="Nombre del producto">
      <input type="file" id="editImage" accept="image/*">
      <input type="number" id="editPrice" placeholder="Precio">
      <button>Actualizar</button>
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
        // Obtén datos del producto
        const card = btn.closest('.product-card');
        const name = card.querySelector('.product-name').innerText;
        const price = card.querySelector('.product-price').innerText.replace('$','');

        // Rellena el modal con los datos
        document.getElementById('editName').value = name;
        document.getElementById('editPrice').value = price;

        // Muestra modal
        editModal.style.display = 'flex';
      });
    });
  </script>
</body>
</html>
