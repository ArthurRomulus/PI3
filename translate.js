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

  async function applyTranslation(lang) {
    const elements = document.querySelectorAll("[data-translate]");
    const texts = Array.from(elements).map(el => el.dataset.translate);

    // Revisa caché para evitar consumir caracteres
    const cacheKey = `translation_${lang}`;
    let cached = JSON.parse(localStorage.getItem(cacheKey) || "{}");

    const missing = texts.filter(t => !cached[t]);
    if (missing.length > 0 && lang !== "es") {
        console.log("🔄 Traduciendo nuevos textos con Azure:", missing);

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
  }
});
