// Filtros de la página de resultados: se aplican en el navegador sobre los vuelos ya cargados.
document.addEventListener("DOMContentLoaded", function () {
    const cards = Array.from(document.querySelectorAll("[data-flight]"));
    if (cards.length === 0) return;

    const stopBoxes = Array.from(document.querySelectorAll('input[name="stops_filter"]'));
    const airlineBoxes = Array.from(document.querySelectorAll('input[name="airline_filter"]'));
    const hourMin = document.getElementById("hourMin");
    const hourMax = document.getElementById("hourMax");
    const hoursLabel = document.getElementById("hoursLabel");
    const countTitle = document.getElementById("resultCount");
    const emptyBox = document.getElementById("filtersEmpty");

    const pad = h => String(h).padStart(2, "0");
    const stopsBucket = n => Math.min(parseInt(n, 10) || 0, 2); // 0, 1 y 2+

    // Precio mínimo por número de escalas ("desde X €") o "No disp." si no hay vuelos de ese tipo
    document.querySelectorAll("[data-stops-info]").forEach(el => {
        const bucket = parseInt(el.dataset.stopsInfo, 10);
        const prices = cards
            .filter(c => stopsBucket(c.dataset.stops) === bucket && c.dataset.price !== "")
            .map(c => parseFloat(c.dataset.price));
        const hasAny = cards.some(c => stopsBucket(c.dataset.stops) === bucket);
        const box = el.closest("label").querySelector("input");
        if (!hasAny) {
            el.textContent = "No disp.";
            box.checked = false;
            box.disabled = true;
        } else if (prices.length) {
            el.textContent = `desde ${Math.min(...prices)} €`;
        }
    });

    function apply() {
        let from = parseInt(hourMin.value, 10);
        let to = parseInt(hourMax.value, 10);
        if (from > to) { [from, to] = [to, from]; }
        hoursLabel.textContent = `${pad(from)}:00 – ${pad(to)}:59`;

        const stops = new Set(stopBoxes.filter(b => b.checked).map(b => parseInt(b.value, 10)));
        const airlines = new Set(airlineBoxes.filter(b => b.checked).map(b => b.value));

        let visible = 0;
        cards.forEach(card => {
            const hour = card.dataset.hour === "" ? null : parseInt(card.dataset.hour, 10);
            const ok = stops.has(stopsBucket(card.dataset.stops)) &&
                airlines.has(card.dataset.airline) &&
                (hour === null || (hour >= from && hour <= to));
            card.hidden = !ok;
            if (ok) visible++;
        });

        // Oculta los grupos que se quedan sin vuelos
        document.querySelectorAll("[data-group]").forEach(group => {
            group.hidden = !group.querySelector("[data-flight]:not([hidden])");
        });

        countTitle.textContent = `${visible} ${visible === 1 ? "vuelo encontrado" : "vuelos encontrados"}`;
        emptyBox.hidden = visible !== 0;
    }

    function reset() {
        stopBoxes.forEach(b => { b.checked = !b.disabled; });
        airlineBoxes.forEach(b => { b.checked = true; });
        hourMin.value = hourMin.min;
        hourMax.value = hourMax.max;
        apply();
    }

    [...stopBoxes, ...airlineBoxes].forEach(b => b.addEventListener("change", apply));
    hourMin.addEventListener("input", apply);
    hourMax.addEventListener("input", apply);
    document.getElementById("filtersReset").addEventListener("click", reset);
    document.getElementById("filtersEmptyReset").addEventListener("click", reset);

    apply();
});
