// cambiar_contrasena.js
document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('form.grid');
  const oldPwd = document.getElementById('pwd-old');
  const newPwd = document.getElementById('pwd-new');
  const confirmPwd = document.getElementById('pwd-confirm');
  const strengthWrap = document.querySelector('.strength');
  const strengthBar = strengthWrap?.querySelector('.bar');
  const strengthLabel = strengthWrap?.querySelector('.label');
  const submitBtn = form.querySelector('.actions .btn[type="submit"], .actions button[type="submit"]') || form.querySelector('.actions .btn');
  
  // Crear un "relleno" dentro de la barra si no existe (para controlar el % vía JS)
  if (strengthBar && !strengthBar.querySelector('.fill')) {
    const fill = document.createElement('div');
    fill.className = 'fill';
    fill.style.height = '100%';
    fill.style.width = '0%';
    fill.style.borderRadius = '999px';
    fill.style.transition = 'width .25s ease';
    strengthBar.appendChild(fill);
  }
  const strengthFill = strengthBar?.querySelector('.fill');

  // --- Mostrar/Ocultar contraseña con el icono .eye ---
  // El HTML tiene <span class="eye"></span> dentro de .field
  document.querySelectorAll('.field .eye').forEach(eye => {
    const input = eye.previousElementSibling; // el <input> justo antes del span.eye
    if (!(input instanceof HTMLInputElement)) return;

    const toggle = () => {
      const isPwd = input.type === 'password';
      input.type = isPwd ? 'text' : 'password';
      eye.classList.toggle('is-open', isPwd);
      // estética rápida: cambiar fondo cuando está abierto
      eye.style.background = isPwd ? '#f6eee9' : '#fff';
    };

    // click para alternar; mousedown para “ver mientras presionas” (opcional)
    eye.addEventListener('click', toggle);
    eye.addEventListener('mousedown', () => { if (input.type === 'password') { input.type = 'text'; eye.classList.add('is-open'); }});
    eye.addEventListener('mouseup', () => { if (eye.classList.contains('is-open')) { input.type = 'password'; eye.classList.remove('is-open'); }});
    eye.addEventListener('mouseleave', () => { if (eye.classList.contains('is-open')) { input.type = 'password'; eye.classList.remove('is-open'); }});
  });

  // --- Evaluación de fuerza de contraseña ---
  function evaluateStrength(pwd) {
    let score = 0;
    const hasLen = pwd.length >= 8;
    const hasUpper = /[A-ZÁÉÍÓÚÑ]/.test(pwd);
    const hasLower = /[a-záéíóúñ]/.test(pwd);
    const hasNum = /\d/.test(pwd);
    const hasSym = /[^A-Za-z0-9]/.test(pwd);

    score += hasLen ? 1 : 0;
    score += hasUpper ? 1 : 0;
    score += hasLower ? 1 : 0;
    score += hasNum ? 1 : 0;
    score += hasSym ? 1 : 0;

    // porcentaje y etiqueta/color
    let pct = ['0%','25%','45%','65%','85%','100%'][score];
    let label = 'Muy débil';
    let color = '#c56a57'; // rojo café

    if (score >= 2) { label = 'Débil'; color = '#c56a57'; }
    if (score >= 3) { label = 'Media'; color = '#caa061'; }
    if (score >= 4) { label = 'Fuerte'; color = '#8bbf7c'; }
    if (score === 5){ label = 'Muy fuerte'; color = '#4fae5a'; }

    return { score, pct, label, color, rules: { hasLen, hasUpper, hasLower, hasNum, hasSym } };
  }

  // --- Señalar requisitos cumplidos dentro de la lista (si decides marcarlos) ---
  const rulesList = form.querySelector('.rules ul');
  function paintRules(r) {
    if (!rulesList) return;
    const items = rulesList.querySelectorAll('li');
    // Espera el orden: len, mayus/minus, número, especial
    const states = [r.hasLen, (r.hasUpper && r.hasLower), r.hasNum, r.hasSym];
    items.forEach((li, i) => {
      li.style.opacity = states[i] ? '1' : '.6';
      li.style.color = states[i] ? '#2f6f3a' : '#3e2c24';
      li.style.fontWeight = states[i] ? '700' : '400';
    });
  }

  // --- Validación general + actualización de UI ---
  function updateUI() {
    const pwd = newPwd.value.trim();
    const res = evaluateStrength(pwd);

    // Actualiza barra y etiqueta
    if (strengthFill) {
      strengthFill.style.width = res.pct;
      strengthFill.style.background = res.color;
    }
    if (strengthLabel) {
      strengthLabel.textContent = res.label;
      strengthLabel.style.color = res.color;
    }
    paintRules(res.rules);

    // Coincidencia con confirmación
    const matches = pwd.length > 0 && pwd === confirmPwd.value.trim();
    confirmPwd.style.borderColor = matches ? '#1a7f37' : '#c56a57';

    // No permitir la misma que la anterior
    const differentFromOld = pwd.length > 0 && oldPwd.value.trim() !== pwd;

    // Habilitar Guardar solo si:
    // - cumple >=4 reglas (fuerte o mejor), 
    // - coincide confirmación, 
    // - es distinta de la anterior
    const ok = (res.score >= 4) && matches && differentFromOld;
    if (submitBtn) {
      submitBtn.disabled = !ok;
      submitBtn.style.opacity = ok ? '1' : '.6';
      submitBtn.style.pointerEvents = ok ? 'auto' : 'none';
    }
  }

  ['input','change','keyup','blur'].forEach(evt => {
    newPwd.addEventListener(evt, updateUI);
    confirmPwd.addEventListener(evt, updateUI);
    oldPwd.addEventListener(evt, updateUI);
  });
  updateUI();

  // --- Submit (demo): evita envío real y muestra confirmación ---
  form.addEventListener('submit', (e) => {
    e.preventDefault();
    // Seguridad básica: última validación
    updateUI();
    if (submitBtn?.disabled) return;

    // Aquí harías tu petición fetch/AJAX a tu endpoint PHP.
    // Ejemplo visual:
    alert('✅ Contraseña actualizada correctamente.');
    // Limpia campos
    oldPwd.value = '';
    newPwd.value = '';
    confirmPwd.value = '';
    updateUI();
  });
});
