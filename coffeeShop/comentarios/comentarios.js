document.addEventListener('DOMContentLoaded', () => {

    // --- CONSTANTES ---
    const form = document.getElementById('form-comentario');
    const mensajeForm = document.getElementById('form-mensaje');
    const fileInput = document.getElementById('imagen');
    const fileNameSpan = document.getElementById('file-name');
    
    // Elementos del panel de resumen
    const promedioNumEl = document.getElementById('promedio-numero-display');
    const promedioEstrellasEl = document.getElementById('promedio-estrellas-display');
    const promedioTotalEl = document.getElementById('promedio-total-display');
    const desgloseBarrasEl = document.getElementById('desglose-barras-container');

    // Elementos del panel de "Reviews"
    const listaComentariosEl = document.getElementById('reviews-grid-container');
    const reviewScorePillEl = document.getElementById('review-score-pill');
    
    // Obtenemos el nombre del usuario logueado.
    const nombreUsuarioLogueado = form && form.dataset.username ? form.dataset.username : 'Invitado';

    const API_URL = 'api_comentarios.php';

    // --- FUNCIONES DE RENDERIZADO ---

    function crearEstrellas(calificacion) {
        let estrellasHTML = '<div class="estrellas-mostradas">';
        const califNum = Math.round(parseFloat(calificacion));
        if (!califNum || califNum === 0) {
            for (let i = 1; i <= 5; i++) { estrellasHTML += `<span class="estrella">★</span>`; }
        } else {
            for (let i = 1; i <= 5; i++) {
                estrellasHTML += `<span class="estrella ${i <= califNum ? 'rellena' : ''}">★</span>`;
            }
        }
        estrellasHTML += '</div>';
        return estrellasHTML;
    }

    function renderizarEstadisticas(stats) {
        if (!stats) return;
        const promedio = parseFloat(stats.promedio).toFixed(1) || "0.0";
        const totalResenas = stats.total || 0;
        
        if (promedioNumEl) promedioNumEl.textContent = promedio;
        if (promedioEstrellasEl) promedioEstrellasEl.innerHTML = crearEstrellas(stats.promedio);
        if (promedioTotalEl) {
            const countSpan = promedioTotalEl.querySelector('#total-reviews-count');
            if (countSpan) countSpan.textContent = totalResenas;
        }
        if (reviewScorePillEl) reviewScorePillEl.textContent = promedio;
        
        if (desgloseBarrasEl) {
            desgloseBarrasEl.innerHTML = '';
            for (let i = 5; i >= 1; i--) {
                const conteo = stats.conteo[i] || 0;
                const porcentaje = (totalResenas > 0) ? (conteo / totalResenas) * 100 : 0;
                desgloseBarrasEl.innerHTML += `
                    <div class="desglose-fila">
                        <span class="desglose-label">${i} estrellas</span>
                        <div class="desglose-barra-fondo">
                            <div class="desglose-barra-relleno" style="width: ${porcentaje}%;"></div>
                        </div>
                        <span class="desglose-conteo">${conteo}</span>
                    </div>`;
            }
        }
    }

    // Renderiza un comentario y sus respuestas recursivamente
    function renderizarComentario(com, isReply = false) {
        
        // --- CORRECCIÓN CLAVE AQUÍ ---
        // Aseguramos el ID ya sea que venga como 'idr' (BD) o 'id' (Insert nuevo)
        const currentID = com.idr || com.id; 
        // -----------------------------

        const calificacion = parseFloat(com.calificacion) || 0;
        const avatarSrc = com.profilescreen ? '../images/' + com.profilescreen : 'assest/default-avatar.png';
        const nombreUsuario = com.nombre || com.nombre_usuario || 'Invitado';
        const fecha = com.fecha ? new Date(com.fecha).toLocaleDateString('es-ES', { day: 'numeric', month: 'long' }) : 'Reciente';
        const likesCount = com.likes || 0;

        // HTML de la imagen adjunta
        let imagenHTML = '';
        if (!isReply && com.imagen_url && com.imagen_url.trim() !== '') {
            imagenHTML = `
                <figure class="card__media">
                    <img src="../../${com.imagen_url}" alt="Foto del comentario" loading="lazy" />
                </figure>`;
        }

        // HTML de etiquetas
        let chipHTML = '';
        if (!isReply && com.etiquetas && com.etiquetas.trim() !== '') {
            let tags = '';
            com.etiquetas.split(',').forEach(tag => {
                if (tag.trim()) {
                    const etiquetaFormateada = tag.trim().charAt(0).toUpperCase() + tag.trim().slice(1);
                    tags += `<span class="chip">#${etiquetaFormateada}</span>`;
                }
            });
            if (tags) chipHTML = `<div class="chips">${tags}</div>`;
        }

        // Estrellas
        let estrellasHTML = '';
        if (!isReply) {
            estrellasHTML = `<div class="stars" style="--value: ${calificacion}" aria-label="${calificacion} de 5 estrellas"></div>`;
        }
        
        // Respuestas recursivas
        let respuestasHTML = '';
        if (com.respuestas && com.respuestas.length > 0) {
            com.respuestas.sort((a, b) => new Date(a.fecha) - new Date(b.fecha));
            respuestasHTML = com.respuestas.map(reply => renderizarComentario(reply, true)).join('');
        }

        const cardClass = isReply ? 'card card--reply' : 'card';
        
        // Usamos currentID en los botones
        const botonResponder = `
            <button class="act act-reply" data-id="${currentID}" data-translate="Responder">
              <svg viewBox="0 0 24 24"><path d="M21 6h-2v9H7v2a1 1 0 0 0 1.7.7L12.4 15H21a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1zM17 11V3a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v12l4-4h10a1 1 0 0 0 1-1z"/></svg>
              Responder
            </button>`;

        return `
        <article class="${cardClass}" data-idr="${currentID}">
          <header class="card__head">
            <img class="avatar" src="${avatarSrc}" alt="Avatar de ${nombreUsuario}" />
            <div class="meta">
              <div class="name">${nombreUsuario}</div>
              <div class="time">${fecha}</div>
            </div>
            ${estrellasHTML}
          </header>

          ${imagenHTML} 
          
          <p class="card__text">${com.comentario}</p>

          ${chipHTML}

          <footer class="card__footer">
            <button class="act act-like" data-id="${currentID}">
               <svg viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 6 4 4 6.5 4c1.74 0 3.41 1 4.22 2.44C11.09 5 12.76 4 14.5 4 17 4 19 6 19 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
               <span class="like-count">${likesCount}</span> <span data-translate="Me gusta">Me gusta</span>
            </button>
            ${botonResponder}
          </footer>
          
          <div class="replies-container">
            ${respuestasHTML}
          </div>
          
          <div class="reply-form-container"></div>
        </article>
        `;
    }

    async function cargarComentarios() {
        try {
            const respuesta = await fetch(API_URL);
            const json = await respuesta.json();
            if (!json.success) throw new Error(json.error);

            const comentarios = json.data.lista_completa || []; 
            const stats = json.data.stats || {};
            
            renderizarEstadisticas(stats);

            if (listaComentariosEl) {
                listaComentariosEl.innerHTML = ''; 
                if (comentarios.length > 0) {
                    comentarios.sort((a, b) => new Date(b.fecha) - new Date(a.fecha));
                    listaComentariosEl.innerHTML = comentarios.map(com => renderizarComentario(com, false)).join('');
                } else {
                    listaComentariosEl.innerHTML = '<p data-translate="¡Sé el primero en dejar un comentario!">¡Sé el primero en dejar un comentario!</p>';
                }
                if (typeof applyTranslation === 'function') {
                    const lang = localStorage.getItem('lang') || 'es';
                    applyTranslation(lang);
                }
            }
        } catch (error) {
            console.error('Error al cargar datos:', error);
            if(listaComentariosEl) listaComentariosEl.innerHTML = '<p>Error al cargar comentarios.</p>';
        }
    }

    function toggleReplyForm(idResena, container) {
        const formExistente = container.querySelector('.reply-form');
        if (formExistente) {
            formExistente.remove();
        } else {
            // Aseguramos que el ID se pasa correctamente al data-attribute
            const formHTML = `
                <form class="reply-form" data-parent-id="${idResena}">
                  <textarea name="comentario" placeholder="Escribe tu respuesta..." required></textarea>
                  <div class="reply-form-actions">
                    <button type="button" class="btn-cancelar-reply">Cancelar</button>
                    <button type="submit" class="btn-enviar-reply">Responder</button>
                  </div>
                </form>
            `;
            container.innerHTML = formHTML;
        }
    }

    // --- LISTENERS ---

    if (listaComentariosEl) {
        listaComentariosEl.addEventListener('click', async (e) => {
            
            // LIKE
            const likeButton = e.target.closest('.act-like');
            if (likeButton) {
                if (likeButton.disabled) return; 
                likeButton.disabled = true;

                const idResena = likeButton.dataset.id;
                const countSpan = likeButton.querySelector('.like-count');
                const currentCount = parseInt(countSpan.textContent, 10);
                const yaTieneLike = likeButton.classList.contains('liked');
                const action = yaTieneLike ? 'unlike' : 'like';

                const formData = new FormData();
                formData.append('action', action);
                formData.append('id_resena', idResena);

                try {
                    const respuesta = await fetch(API_URL, { method: 'POST', body: formData });
                    const json = await respuesta.json();
                    if (!respuesta.ok || !json.success) throw new Error(json.error || 'Error');
                    
                    const newCount = yaTieneLike ? currentCount - 1 : currentCount + 1;
                    countSpan.textContent = newCount < 0 ? 0 : newCount; 
                    likeButton.classList.toggle('liked'); 
                } catch (error) {
                    console.error('Error like:', error);
                } finally {
                    likeButton.disabled = false;
                }
            }
            
            // MOSTRAR FORMULARIO RESPUESTA
            const replyButton = e.target.closest('.act-reply');
            if (replyButton) {
                const idResena = replyButton.dataset.id;
                // Verificación de seguridad en consola
                console.log("ID detectado para responder:", idResena); 
                
                const card = replyButton.closest('.card');
                const formContainer = card.querySelector('.reply-form-container');
                toggleReplyForm(idResena, formContainer);
            }
            
            // CANCELAR RESPUESTA
            const cancelReplyButton = e.target.closest('.btn-cancelar-reply');
            if (cancelReplyButton) {
                cancelReplyButton.closest('.reply-form').remove();
            }
        });

        // ENVÍO DE RESPUESTA
        listaComentariosEl.addEventListener('submit', async (e) => {
            if (!e.target.matches('.reply-form')) return;
            
            e.preventDefault();
            const replyForm = e.target;
            const submitButton = replyForm.querySelector('button[type="submit"]');
            
            // Debugging
            const parentId = replyForm.dataset.parentId;
            console.log("Enviando respuesta a Parent ID:", parentId);

            if (!parentId || parentId === "undefined") {
                alert("Error: No se pudo identificar el comentario al que respondes. Por favor recarga la página.");
                return;
            }

            submitButton.disabled = true;
            submitButton.textContent = 'Enviando...';
            
            const formData = new FormData();
            formData.append('action', 'reply');
            formData.append('parent_id', parentId);
            formData.append('comentario', replyForm.querySelector('textarea').value);
            formData.append('nombre', nombreUsuarioLogueado);

            try {
                const respuesta = await fetch(API_URL, { method: 'POST', body: formData });
                const texto = await respuesta.text(); 
                
                let json;
                try { json = JSON.parse(texto); } 
                catch (err) { throw new Error('Respuesta del servidor no válida: ' + texto); }

                if (!respuesta.ok || !json.success) throw new Error(json.error || 'Error desconocido');

                const nuevaRespuestaHTML = renderizarComentario(json.data, true);
                const cardPadre = replyForm.closest('.card');
                const repliesContainer = cardPadre.querySelector('.replies-container');
                repliesContainer.innerHTML += nuevaRespuestaHTML; 
                
                replyForm.remove(); 

            } catch (error) {
                console.error('Error respuesta:', error);
                alert('No se pudo enviar la respuesta: ' + error.message);
                submitButton.disabled = false;
                submitButton.textContent = 'Responder';
            }
        });
    }

    // --- ENVÍO DEL FORMULARIO PRINCIPAL ---
    if (form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            mensajeForm.textContent = 'Enviando...';
            
            const formData = new FormData(form);
            
            formData.append('action', 'create'); 
            formData.append('nombre', nombreUsuarioLogueado);
            // IMPORTANTE: Enviamos parent_id vacío para evitar error en PHP
            formData.append('parent_id', ''); 

            // Validaciones
            if (!formData.get('calificacion')) { 
                mensajeForm.textContent = 'Por favor, selecciona una calificación.'; return; 
            }
            if (!formData.get('comentario') || formData.get('comentario').trim() === '') { 
                mensajeForm.textContent = 'Por favor, escribe un comentario.'; return; 
            }

            try {
                const respuesta = await fetch(API_URL, { method: 'POST', body: formData });
                const texto = await respuesta.text();

                let json;
                try { json = JSON.parse(texto); } 
                catch (err) { throw new Error('Respuesta inválida: ' + texto); }

                if (!respuesta.ok) throw new Error(json.error || 'Error server');
                if (!json.success) throw new Error(json.error);

                mensajeForm.textContent = '¡Comentario enviado!';
                mensajeForm.style.color = 'green';
                form.reset(); 
                if(fileNameSpan) fileNameSpan.textContent = 'Ningún archivo';
                
                // Recargamos todo para asegurar que los IDs vengan bien desde la BD
                cargarComentarios(); 
                
                setTimeout(() => { mensajeForm.textContent = ''; }, 3000);

            } catch (error) {
                console.error('Error submit principal:', error);
                mensajeForm.textContent = `Error: ${error.message}`;
                mensajeForm.style.color = 'red';
            }
        });
    }

    if (fileInput && fileNameSpan) {
        fileInput.addEventListener('change', () => { 
            fileNameSpan.textContent = fileInput.files.length > 0 ? fileInput.files[0].name : 'Ningún archivo'; 
        });
        form.addEventListener('reset', () => { 
            fileNameSpan.textContent = 'Ningún archivo'; 
        });
    }

    // Carga inicial
    cargarComentarios(); 
});