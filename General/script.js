const cup = document.getElementById('cup');
const toggleText = document.getElementById('toggleText');
const formTitle = document.getElementById('formTitle');

function toggleMode(e) {
  e.preventDefault();
  const isLogin = !cup.classList.contains('frappuccino');
  cup.classList.toggle('frappuccino');
  formTitle.textContent = isLogin ? 'Registrarse' : 'Iniciar Sesión';
  toggleText.innerHTML = isLogin
    ? '¿Ya tienes cuenta? <a href="#" id="toggleLink">Iniciar sesión</a>'
    : '¿No tienes cuenta? <a href="#" id="toggleLink">Registrarse</a>';
  document.getElementById('toggleLink').addEventListener('click', toggleMode);
}

document.getElementById('toggleLink').addEventListener('click', toggleMode);
