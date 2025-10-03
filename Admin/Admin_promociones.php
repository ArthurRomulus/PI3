<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promociones</title>
    <link rel="stylesheet" href="Admin_nav_bar.css">
    <link rel="stylesheet" href="Admin_promociones.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'Admin_nav_bar.php'; ?>

    <div class="content">
        <!-- Top Bar con admin -->
        <div class="top-bar">
            <div class="admin-profile">
                <span class="admin-name">Admin - Luis Perez Ponzo</span>
                <img src="img/admin.png" alt="Perfil Admin">
            </div>
        </div>

        <h1>Coffee Shop</h1>

        <!-- Botón Agregar Promoción -->
        <div class="promo-header">
            <button class="btn-add-promo" id="openPromoModal">
                <i class="fas fa-plus"></i> Agregar Promoción
            </button>
        </div>

        <!-- Título del listado -->
        <h3>Promociones</h3>

        <!-- Grid de promociones -->
        <div class="promos-container">
            <div class="promo-card">
                <img src="img/Cafe_Americano.png" alt="Promo 1">

                <div class="promo-info">
                    <span class="promo-status active">Activa</span>
                    <h4 class="promo-name">Frappé 2x1</h4>
                    <span class="promo-validity">Vigencia: 30 Sep</span>

                    <div class="promo-actions">
                        <i class="fas fa-pen edit"></i>
                        <i class="fas fa-trash delete"></i>
                    </div>
                </div>
            </div>

            <div class="promo-card">
                <img src="img/Cafe_Americano.png" alt="Promo 1">

                <div class="promo-info">
                    <span class="promo-status expiring">Por vencer</span>
                    <h4 class="promo-name">Frappé 2x1</h4>
                    <span class="promo-validity">Vigencia: 30 Sep</span>

                    <div class="promo-actions">
                        <i class="fas fa-pen edit"></i>
                        <i class="fas fa-trash delete"></i>
                    </div>
                </div>
            </div>

            <div class="promo-card">
                <img src="img/Cafe_Americano.png" alt="Promo 1">

                <div class="promo-info">
                    <span class="promo-status active">Activa</span>
                    <h4 class="promo-name">Frappé 2x1</h4>
                    <span class="promo-validity">Vigencia: 30 Sep</span>

                    <div class="promo-actions">
                        <i class="fas fa-pen edit"></i>
                        <i class="fas fa-trash delete"></i>
                    </div>
                </div>
            </div>

            <div class="promo-card">
                <img src="img/Cafe_Americano.png" alt="Promo 1">

                <div class="promo-info">
                    <span class="promo-status expiring">Por vencer</span>
                    <h4 class="promo-name">Frappé 2x1</h4>
                    <span class="promo-validity">Vigencia: 30 Sep</span>

                    <div class="promo-actions">
                        <i class="fas fa-pen edit"></i>
                        <i class="fas fa-trash delete"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear promoción -->
    <div id="promoModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Agregar Promoción</h2>
            <input type="text" placeholder="Título de la promoción">
            <textarea placeholder="Descripción de la promoción"></textarea>
            <input type="file" accept="image/*">
            <input type="date" placeholder="Fecha de inicio">
            <input type="date" placeholder="Fecha de fin">
            <button>Guardar Promoción</button>
        </div>
    </div>

    <!-- Modal para editar promoción -->
    <div id="editPromoModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Editar Promoción</h2>
            <input type="text" placeholder="Título de la promoción">
            <textarea placeholder="Descripción de la promoción"></textarea>
            <input type="file" accept="image/*">
            <input type="date" placeholder="Fecha de inicio">
            <input type="date" placeholder="Fecha de fin">
            <button>Guardar Cambios</button>
        </div>
    </div>

    <script>
        // Selección de todos los modales y botones de cierre
        const promoModal = document.getElementById('promoModal');
        const editModal = document.getElementById('editPromoModal');
        const openPromoBtn = document.getElementById('openPromoModal');
        const closeBtns = document.querySelectorAll('.modal .close');

        // Abrir modal de agregar
        openPromoBtn.onclick = () => promoModal.style.display = 'flex';

        // Abrir modal de editar
        const editButtons = document.querySelectorAll('.promo-actions .edit');
        editButtons.forEach(btn => {
            btn.addEventListener('click', () => {
            // Aquí puedes rellenar campos del modal si quieres
            editModal.style.display = 'flex';
            });
        });

        // Cerrar cualquier modal
        closeBtns.forEach(btn => {
            btn.onclick = () => btn.closest('.modal').style.display = 'none';
        });

        // Cerrar al hacer clic fuera
        window.onclick = (e) => {
            if (e.target.classList.contains('modal')) {
            e.target.style.display = 'none';
            }
        };
    </script>
</body>
</html>
