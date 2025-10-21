document.getElementById('profile-picture').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const profileArea = document.querySelector('.profile-picture-area');
            // Eliminar texto "Foto de perfil" si existe
            const profileText = profileArea.querySelector('.profile-text');
            if (profileText) {
                profileText.style.display = 'none';
            }
            // Eliminar imagen anterior si existe
            const oldImg = profileArea.querySelector('img');
            if (oldImg) {
                oldImg.remove();
            }
            // Crear y añadir nueva imagen
            const img = document.createElement('img');
            img.src = e.target.result;
            profileArea.appendChild(img);
        };
        reader.readAsDataURL(file);
    }
});