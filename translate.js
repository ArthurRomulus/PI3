// === translate.js ===
// Traducción automática usando Azure Translator + caché en localStorage

document.addEventListener("DOMContentLoaded", () => {
  const btnEs = document.getElementById("btn-es");
  const btnEn = document.getElementById("btn-en");

  if (!btnEs || !btnEn) return;

  // Idioma actual (guardado en cache)
  let currentLang = localStorage.getItem("lang") || "es";
  updateActiveButton(currentLang);
  applyTranslation(currentLang);

  // Eventos de botones
  btnEs.addEventListener("click", () => switchLanguage("es"));
  btnEn.addEventListener("click", () => switchLanguage("en"));

  async function switchLanguage(lang) {
    if (lang === currentLang) return;
    currentLang = lang;
    localStorage.setItem("lang", lang);
    updateActiveButton(lang);
    await applyTranslation(lang);
  }

  function updateActiveButton(lang) {
    btnEs.classList.toggle("active", lang === "es");
    btnEn.classList.toggle("active", lang === "en");
  }

  const customTranslations = {
    "Nombre:": "Name",
    "Numero empleado:": "Employee Number",
    "Cerrar Sesión":"Log Out",
    "Cerrar sesión":"Log Out",
  };

  async function applyTranslation(lang) {
    const elements = document.querySelectorAll("[data-translate], [data-translate-placeholder], [data-translate-value]");
    // Recolecta todos los textos que necesitan traducción
    const texts = [];
    elements.forEach(el => {
      if (el.dataset.translate) texts.push(el.dataset.translate);
      if (el.dataset.translatePlaceholder) texts.push(el.dataset.translatePlaceholder);
      if (el.dataset.translateValue) texts.push(el.dataset.translateValue);
    });
    // Revisa caché para evitar consumir caracteres
    const cacheKey = `translation_${lang}`;
    let cached = JSON.parse(localStorage.getItem(cacheKey) || "{}");

    const missing = texts.filter(t => !cached[t]);
    if (missing.length > 0 && lang !== "es") {
        console.log("Traduciendo nuevos textos con Azure:", missing);

      try {
        const response = await fetch("/PI3/translate.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ texts: missing, to: lang })
        });
        const data = await response.json();
        if (data.translations) {
          missing.forEach((txt, i) => cached[txt] = data.translations[i]);
          localStorage.setItem(cacheKey, JSON.stringify(cached));
        }
      } catch (err) {
        console.error("Error al traducir:", err);
      }
    }

    // Aplica traducción o texto original
    elements.forEach(el => {
      if (lang === "es") el.textContent = el.dataset.translate;
      else el.textContent = cached[el.dataset.translate] || el.dataset.translate;
    });

    // Aplica traducción o texto original
     elements.forEach(el => {
      if (lang === "es") {
        el.textContent = el.dataset.translate || el.textContent;
        if (el.dataset.translatePlaceholder) el.placeholder = el.dataset.translatePlaceholder;
        if (el.dataset.translateValue) el.value = el.dataset.translateValue;
      } else {
        const getTranslation = (key) =>
          customTranslations[key] || cached[key] || key;

        if (el.dataset.translate)
          el.textContent = getTranslation(el.dataset.translate);
        if (el.dataset.translatePlaceholder)
          el.placeholder = getTranslation(el.dataset.translatePlaceholder);
        if (el.dataset.translateValue)
          el.value = getTranslation(el.dataset.translateValue);
      }
    });
  }
  window.applyTranslation = applyTranslation;
  window.currentLang = currentLang;
});
