// Pantalla de carga con frases al azar: al buscar vuelos y al abrir el detalle de un vuelo,
// desde cualquier vista (formularios y enlaces hacia /flights, /vuelo/... y /favorites/{id}/details).
(function () {
    "use strict";

    const PHRASES = {
        search: [
            "Preguntando a los pilotos por sus mejores precios…",
            "Persiguiendo ofertas a 900 km/h…",
            "Calentando motores…",
            "Comparando cientos de aerolíneas sin que se enfaden…",
            "Convenciendo al avión de que sea barato…",
            "Negociando con las turbulencias…",
            "Ajustando el cinturón de tu presupuesto…",
            "Revisando la pista en busca de chollos…",
            "Despegando de las tarifas más caras…",
            "Contando escalas para que sean pocas…",
            "Consultando a la torre de control…",
            "Rebuscando entre las tarifas más bajas…",
            "Hinchando el chaleco salvavidas de tu cartera…",
            "Sobrevolando el mapa de ofertas…"
        ],
        details: [
            "Abriendo la puerta del avión…",
            "Sacando tu tarjeta de embarque…",
            "Preguntando al capitán qué tal el trayecto…",
            "Desplegando el mapa de la ruta…",
            "Colocando tu equipaje de mano en el compartimento…",
            "Mirando por la ventanilla los últimos detalles…",
            "Revisando que el avión tenga alas (siempre ayuda)…",
            "Pidiendo permiso a la torre de control…",
            "Ordenando los horarios y las escalas…",
            "Abrochando los cinturones de los datos…"
        ]
    };

    const TITLES = {
        search: "Buscando vuelos",
        details: "Preparando tu vuelo"
    };

    let overlay = null;
    let ticker = null;
    let order = [];
    let cursor = 0;

    function shuffle(list) {
        const copy = list.slice();
        for (let i = copy.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [copy[i], copy[j]] = [copy[j], copy[i]];
        }
        return copy;
    }

    function build() {
        overlay = document.createElement("div");
        overlay.className = "fl-loader";
        overlay.hidden = true;
        overlay.setAttribute("role", "status");
        overlay.setAttribute("aria-live", "polite");
        overlay.innerHTML =
            '<div class="fl-loader__card">' +
            '  <div class="fl-loader__sky" aria-hidden="true">' +
            '    <span class="fl-loader__cloud fl-loader__cloud--a"></span>' +
            '    <span class="fl-loader__cloud fl-loader__cloud--b"></span>' +
            '    <span class="fl-loader__trail"></span>' +
            '    <i class="fas fa-plane fl-loader__plane"></i>' +
            '  </div>' +
            '  <h2 class="fl-loader__title"></h2>' +
            '  <p class="fl-loader__context" hidden></p>' +
            '  <p class="fl-loader__phrase"></p>' +
            '  <div class="fl-loader__bar" aria-hidden="true"><span></span></div>' +
            "</div>";
        document.body.appendChild(overlay);
    }

    function nextPhrase() {
        const phrase = order[cursor % order.length];
        cursor++;
        return phrase;
    }

    function show(kind, context) {
        if (!overlay) build();
        if (!overlay.hidden) return;

        order = shuffle(PHRASES[kind]);
        cursor = 0;

        overlay.querySelector(".fl-loader__title").textContent = TITLES[kind];

        const ctx = overlay.querySelector(".fl-loader__context");
        ctx.textContent = context || "";
        ctx.hidden = !context;

        const phrase = overlay.querySelector(".fl-loader__phrase");
        phrase.textContent = nextPhrase();
        phrase.classList.remove("is-out");

        overlay.hidden = false;
        document.documentElement.classList.add("fl-loading");

        clearInterval(ticker);
        ticker = setInterval(function () {
            phrase.classList.add("is-out");
            setTimeout(function () {
                phrase.textContent = nextPhrase();
                phrase.classList.remove("is-out");
            }, 280);
        }, 2600);
    }

    function hide() {
        if (!overlay) return;
        clearInterval(ticker);
        overlay.hidden = true;
        document.documentElement.classList.remove("fl-loading");
    }

    function routeText(from, to) {
        return from && to ? from.toUpperCase() + "  →  " + to.toUpperCase() : "";
    }

    // Formularios de búsqueda (inicio y "Buscar de nuevo" del historial del perfil)
    document.addEventListener("submit", function (e) {
        if (e.defaultPrevented) return;
        const form = e.target;
        if (!(form instanceof HTMLFormElement)) return;

        let action;
        try {
            action = new URL(form.getAttribute("action") || location.href, location.href);
        } catch (err) {
            return;
        }
        if (action.origin !== location.origin || action.pathname !== "/flights") return;

        // querySelector por nombre: en el inicio hay un campo de texto con id "departure"
        // y otro oculto con name "departure", y form.elements.departure devolvería ambos.
        const field = function (name) {
            const input = form.querySelector('[name="' + name + '"]');
            return input ? input.value : "";
        };
        show("search", routeText(field("departure"), field("arrival")));
    });

    // Enlaces: detalle de un vuelo y cambio de fecha en los resultados
    document.addEventListener("click", function (e) {
        if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;

        const link = e.target.closest && e.target.closest("a[href]");
        if (!link || link.target === "_blank" || link.hasAttribute("download")) return;

        let url;
        try {
            url = new URL(link.href, location.href);
        } catch (err) {
            return;
        }
        if (url.origin !== location.origin) return;

        const path = url.pathname;
        if (path.indexOf("/vuelo/") === 0) {
            let number = "";
            try {
                number = decodeURIComponent(path.split("/")[2] || "");
            } catch (err) { /* número con caracteres raros: se omite */ }
            show("details", number ? "Vuelo " + number : "");
        } else if (/^\/favorites\/\d+\/details$/.test(path)) {
            show("details", "");
        } else if (path === "/flights") {
            show("search", routeText(url.searchParams.get("departure"), url.searchParams.get("arrival")));
        }
    });

    // Al volver con el botón "atrás" el navegador restaura la página tal cual: se quita la carga
    window.addEventListener("pageshow", function (e) {
        if (e.persisted) hide();
    });
})();
