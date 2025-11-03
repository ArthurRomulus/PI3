document.addEventListener('DOMContentLoaded', () => {
    const themeToggle = document.getElementById('theme-toggle');

    // 1. Revisa si hay una preferencia guardada en localStorage
    const currentTheme = localStorage.getItem('theme');
    if (currentTheme === 'dark') {
        document.body.classList.add('dark-mode');
        themeToggle.checked = true;
    }

    // 2. Añade el "listener" al interruptor
    themeToggle.addEventListener('change', () => {
        if (themeToggle.checked) {
            // Si está marcado, activa el modo oscuro
            document.body.classList.add('dark-mode');
            localStorage.setItem('theme', 'dark');
        } else {
            // Si no, desactívalo
            document.body.classList.remove('dark-mode');
            localStorage.setItem('theme', 'light');
        }
    });
});