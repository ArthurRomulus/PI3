// Archivo: barraNav.js
function cargarBarraNav() {
    const barraNavHTML = `
        <style>

/* ========== VARIABLES ========== */
:root {
  --sidebar-bg: #d6a05c;
  --accent: #c68644;
  --page-bg: #e9bb7b;
  --card-bg: #f6e9db;
  --search-bg: #f3e6c1;
  --text-dark: #332a23;
  --muted: #6a584a;
  --white: #fff;
  --shadow: 0 2px 8px rgba(0,0,0,0.07);
}

            .sidebar {
                background: var(--sidebar-bg); color: var(--white);
                /* --- CAMBIOS AQUÍ --- */
                position: relative; /* Contenedor para el logo absoluto */
                padding: 75px 12px 1px 12px; /* Aumentamos el padding superior para dar espacio al logo */
                width: 90px;          /* Le damos un ancho fijo */
                flex-shrink: 0;       /* Evitamos que se encoja */
                display: flex;
                flex-direction: column; align-items: center; gap: 2px; border-radius: 16px; height: 100%;
            }
            .logo {
                /* --- CAMBIOS AQUÍ --- */
                position: absolute; /* Sacamos el logo del flujo */
                top: 0px; /* Lo posicionamos arriba */
                left: 50%;
                transform: translateX(-50%); /* Lo centramos horizontalmente */
                z-index: 10; /* Nos aseguramos que esté por encima */

                display: flex; flex-direction: column; align-items: center; gap: 8px;
            }
            .logo img {
                width: 90px; /* Puedes ajustar el tamaño sin afectar los botones */
                height: 90px;
                margin-bottom: 0px;
            }
            .nav {
                /* --- CAMBIO AQUÍ --- */
                margin-top: 0; /* Eliminamos el margen superior que ya no es necesario */
                width: 100%; display: flex; flex-direction: column; gap: 0px; align-items: center; width: 90px; box-sizing: border-box;
            }
            .nav-link {
                display: flex; flex-direction: column; align-items: center; gap: 4px; padding: 10px 60px;
                color: var(--white); text-decoration: none; border-radius: 14px; 
                
                /* --- CORRECCIÓN 1 AQUÍ --- */
                transition: background-color .18s; /* Cambiado de 'background' a 'background-color' */
                
                font-weight: 600; font-size: 15px; width: 100%;
            }
            .nav-link .icon { display: flex; align-items: center; justify-content: center; width: 90px; box-sizing: border-box;}
            
            /* --- CORRECCIÓN 2 AQUÍ --- */
            .nav-link:not(.active):hover { 
                background-color: rgba(255, 255, 255, 0.13); /* Cambiado de 'background' a 'background-color' */
                width: 90px;
                box-sizing: border-box; 
            }

            .nav-link.active { background: var(--page-bg); color: #ffffffff; font-weight: 700; width: 90px; box-sizing: border-box; }
            .nav-link.active .icon svg { stroke: #ffffffff; }
        </style>
        
        <aside class="sidebar">
            <div class="logo">
                <img src="../../Images/logo.png" alt="Logo de Coffee Shop">
            </div>
            
            <nav class="nav">
                <a href="../Inicio/inicio.html" class="nav-link">
                    <span class="icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    </span>
                    <span class="nav-text">Inicio</span>
                </a>
                <a href="../Productos/productos.html" class="nav-link">
                    <span class="icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8h1a4 4 0 0 1 0 8h-1"></path><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path><line x1="6" y1="1" x2="6" y2="4"></line><line x1="10" y1="1" x2="10" y2="4"></line><line x1="14" y1="1" x2="14" y2="4"></line></svg>
                    </span>
                    <span class="nav-text">Productos</span>
                </a>
                <a href="../Promocion/promociones.html" class="nav-link">
                    <span class="icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                    </span>
                    <span class="nav-text">Promociones</span>
                </a>
                <a href="../ControlCaja/controlcaja.html" class="nav-link">
                    <span class="icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                    </span>
                    <span class="nav-text">Control</span>
                </a>
                <a href="../login_admin_cajero/admin.html" class="nav-link">
                    <span class="icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </span>
                    <span class="nav-text">Admin</span>
                </a>
                <a href="../Perfil/perfil.html" class="nav-link">
                    <span class="icon">
                         <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </span>
                    <span class="nav-text">Perfil</span>
                </a>
            </nav>
        </aside>
    `;

    const placeholder = document.getElementById('navbar-placeholder');
    if (placeholder) {
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = barraNavHTML.trim();
        placeholder.replaceWith(...tempDiv.childNodes);
    }
    
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        const linkPath = new URL(link.href).pathname;
        if (currentPath.includes(linkPath.substring(linkPath.lastIndexOf('/') + 1))) {
            link.classList.add('active');
        }
    });
}