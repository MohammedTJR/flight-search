// Preguntas frecuentes: búsqueda por texto, filtro por tema e índice con sección activa.
document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("faqSearch");
    const chips = Array.from(document.querySelectorAll(".faq-filters button"));
    const modules = Array.from(document.querySelectorAll(".faq-module"));
    const empty = document.getElementById("faqEmpty");
    const tocLinks = Array.from(document.querySelectorAll(".ct-toc a"));
    let topic = "all";

    // Quita tildes y pasa a minúsculas para que «contrasena» encuentre «contraseña»
    const norm = s => s.toLowerCase().normalize("NFD").replace(/[̀-ͯ]/g, "");

    function apply() {
        const query = norm(input.value.trim());
        let visible = 0;

        modules.forEach(module => {
            const inTopic = topic === "all" || module.dataset.module === topic;
            let shown = 0;

            module.querySelectorAll(".faq-item").forEach(item => {
                const match = inTopic && (query === "" || norm(item.textContent).includes(query));
                item.hidden = !match;
                if (match) shown++;
            });

            module.hidden = shown === 0;
            visible += shown;
        });

        empty.hidden = visible !== 0;
    }

    input.addEventListener("input", apply);
    chips.forEach(chip => chip.addEventListener("click", function () {
        topic = chip.dataset.filter;
        chips.forEach(c => c.classList.toggle("is-active", c === chip));
        apply();
    }));

    // Al abrir un tema desde el índice, mostrarlo aunque hubiese un filtro activo
    tocLinks.forEach(link => link.addEventListener("click", function () {
        if (topic !== "all" || input.value !== "") {
            topic = "all";
            input.value = "";
            chips.forEach(c => c.classList.toggle("is-active", c.dataset.filter === "all"));
            apply();
        }
    }));

    // Sección activa en el índice
    if ("IntersectionObserver" in window) {
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    tocLinks.forEach(l => l.classList.toggle("is-active", l.getAttribute("href") === "#" + entry.target.id));
                }
            });
        }, { rootMargin: "-20% 0px -65% 0px" });
        modules.forEach(m => observer.observe(m));
    }
});
