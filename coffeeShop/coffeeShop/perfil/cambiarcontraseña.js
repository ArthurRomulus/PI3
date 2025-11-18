// cambiarcontraseña.js
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('formChangePwd');
  const oldPwd = document.getElementById('pwd-old');
  const newPwd = document.getElementById('pwd-new');
  const confirmPwd = document.getElementById('pwd-confirm');
  const strengthWrap = document.querySelector('.strength');
  const strengthBar = strengthWrap?.querySelector('.bar');
  const strengthLabel = strengthWrap?.querySelector('.label');
  const submitBtn = form.querySelector('.actions .btn[type="submit"]');
  const msgAjax = document.createElement('div');
  msgAjax.id = 'msgAjax';
  form.parentElement.prepend(msgAjax); // lo pone arriba del <form> dentro de la card

  // Relleno dinámico de la barrita de fuerza
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

  // Toggle ver/ocultar contraseña
  document.querySelectorAll('.field .eye').forEach(eye => {
    const input = eye.previousElementSibling;
    if (!(input instanceof HTMLInputElement)) return;

    const setVisible = (vis) => {
      input.type = vis ? 'text' : 'password';
      eye.classList.toggle('is-open', vis);
      eye.style.background = vis ? '#f6eee9' : '#fff';
    };

    eye.addEventListener('click', () => {
      const isPwd = input.type === 'password';
      setVisible(isPwd);
    });

    eye.addEventListener('mousedown', () => {
      if (input.type === 'password') setVisible(true);
    });
    eye.addEventListener('mouseup', () => {
      if (eye.classList.contains('is-open')) setVisible(false);
    });
    eye.addEventListener('mouseleave', () => {
      if (eye.classList.contains('is-open')) setVisible(false);
    });
  });

  function evaluateStrength(pwd) {
    let score = 0;
    const hasLen   = pwd.length >= 8;
    const hasUpper = /[A-ZÁÉÍÓÚÑ]/.test(pwd);
    const hasLower = /[a-záéíóúñ]/.test(pwd);
    const hasNum   = /\d/.test(pwd);
    const hasSym   = /[^A-Za-z0-9]/.test(pwd);

    if (hasLen)   score++;
    if (hasUpper) score++;
    if (hasLower) score++;
    if (hasNum)   score++;
    if (hasSym)   score++;

    const pctMap = ['0%','25%','45%','65%','85%','100%'];
    let pct   = pctMap[score];
    let label = 'Muy débil';
    let color = '#c56a57';

    if (score >= 2) { label = 'Débil';        color = '#c56a57'; }
    if (score >= 3) { label = 'Media';        color = '#caa061'; }
    if (score >= 4) { label = 'Fuerte';       color = '#8bbf7c'; }
    if (score === 5){ label = 'Muy fuerte';   color = '#4fae5a'; }

    return {
      score,
      pct,
      label,
      color,
      rules: { hasLen, hasUpper, hasLower, hasNum, hasSym }
    };
  }

  function paintRules(rulesState) {
    const rulesList = form.querySelector('.rules ul');
    if (!rulesList) return;

    // orden visual:
    // 1) mínimo 8
    // 2) mayus/minus
    // 3) número
    // 4) especial
    const okStates = [
      rulesState.hasLen,
      (rulesState.hasUpper && rulesState.hasLower),
      rulesState.hasNum,
      rulesState.hasSym
    ];

    [...rulesList.querySelectorAll('li')].forEach((li, i) => {
        const ok = okStates[i];
        li.style.opacity = ok ? '1' : '.6';
        li.style.color = ok ? '#2f6f3a' : '#3e2c24';
        li.style.fontWeight = ok ? '700' : '400';
    });
  }

  function updateUI() {
    const pwd = newPwd.value.trim();
    const res = evaluateStrength(pwd);

    if (strengthFill) {
      strengthFill.style.width = res.pct;
      strengthFill.style.background = res.color;
    }
    if (strengthLabel) {
      strengthLabel.textContent = res.label;
      strengthLabel.style.color = res.color;
    }
    paintRules(res.rules);

    // confirmar coincide
    const matches = pwd.length > 0 && pwd === confirmPwd.value.trim();
    confirmPwd.style.borderColor = matches ? '#1a7f37' : '#c56a57';

    // distinta de la anterior
    const differentFromOld = pwd.length > 0 && oldPwd.value.trim() !== pwd;

    // habilitar submit si cumple:
    const ok = (res.score >= 4) && matches && differentFromOld;

    if (submitBtn) {
      submitBtn.disabled = !ok;
      submitBtn.style.opacity = ok ? '1' : '.5';
      submitBtn.style.pointerEvents = ok ? 'auto' : 'none';
    }
  }

  ['input','change','keyup','blur'].forEach(evt => {
    newPwd.addEventListener(evt, updateUI);
    confirmPwd.addEventListener(evt, updateUI);
    oldPwd.addEventListener(evt, updateUI);
  });
  updateUI();

  // submit AJAX
  form.addEventListener('submit', async e => {
    e.preventDefault();
    updateUI();
    if (submitBtn.disabled) return;

    msgAjax.innerHTML = "";

    const fd = new FormData();
    fd.append('pwd_old', oldPwd.value.trim());
    fd.append('pwd_new', newPwd.value.trim());
    fd.append('pwd_confirm', confirmPwd.value.trim());
    fd.append('ajax', '1'); // <- para que PHP sepa que responda JSON

    try {
      const resp = await fetch('cambiar_pass.php', {
        method: 'POST',
        body: fd,
        credentials: 'same-origin'
      });

      const data = await resp.json();

      if (data.ok) {
        msgAjax.innerHTML = `
          <div class="alert-ok" style="
            background:#e8ffe8;
            border:2px solid #1a7f37;
            color:#1a7f37;
            font-weight:600;
            border-radius:10px;
            padding:10px 14px;
            font-family:'Montaga',serif;
            font-size:.9rem;
            margin-bottom:16px;
          ">
            ${data.success || 'Contraseña actualizada correctamente.'}
          </div>
        `;
        oldPwd.value = '';
        newPwd.value = '';
        confirmPwd.value = '';
      } else {
        msgAjax.innerHTML = `
          <div class="alert-err" style="
            background:#ffeded;
            border:2px solid #c56a57;
            color:#c56a57;
            font-weight:600;
            border-radius:10px;
            padding:10px 14px;
            font-family:'Montaga',serif;
            font-size:.9rem;
            margin-bottom:16px;
          ">
            ${data.error || 'No se pudo actualizar la contraseña.'}
          </div>
        `;
      }
    } catch (err) {
      msgAjax.innerHTML = `
        <div class="alert-err" style="
          background:#ffeded;
          border:2px solid #c56a57;
          color:#c56a57;
          font-weight:600;
          border-radius:10px;
          padding:10px 14px;
          font-family:'Montaga',serif;
          font-size:.9rem;
          margin-bottom:16px;
        ">
          Error de conexión con el servidor.
        </div>
      `;
    }

    updateUI();
  });
});
