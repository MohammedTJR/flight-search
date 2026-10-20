// Centro de ayuda: el buscador lleva a la primera guía (o categoría) que contiene el término.
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("helpSearchForm");
    const input = document.getElementById("helpSearch");
    const noResults = document.getElementById("helpNoResults");
    const guides = Array.from(document.querySelectorAll(".hp-guide"));

    // Quita tildes y pasa a minúsculas para que «mapa» o «raDar» también coincidan
    const norm = s => s.toLowerCase().normalize("NFD").replace(/[̀-ͯ]/g, "");

    form.addEventListener("submit", function (event) {
        event.preventDefault();
        const query = norm(input.value.trim());
        noResults.hidden = true;
        if (query === "") return;

        const found = guides.find(g => norm(g.textContent).includes(query));
        if (!found) {
            noResults.hidden = false;
            return;
        }

        found.scrollIntoView({ behavior: "smooth", block: "start" });
        found.classList.remove("is-found");
        void found.offsetWidth; // reinicia la animación si se busca otra vez
        found.classList.add("is-found");
    });

    input.addEventListener("input", function () {
        noResults.hidden = true;
    });
});
