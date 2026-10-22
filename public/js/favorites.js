// Favoritos: filtro por vuelos directos y ordenación en el cliente.
document.addEventListener("DOMContentLoaded", function () {
    const grid = document.getElementById("fvGrid");
    if (!grid) return;

    const cards = Array.from(grid.querySelectorAll(".fv-card[data-price]"));
    const discover = document.getElementById("fvDiscover");
    const emptyMsg = document.getElementById("fvEmptyFilter");
    const tabs = document.querySelectorAll(".fv-tabs button");
    const sort = document.getElementById("fvSort");
    let filter = "all";

    const sorters = {
        recent: (a, b) => b.dataset.created - a.dataset.created,
        price: (a, b) => a.dataset.price - b.dataset.price,
        date: (a, b) => a.dataset.date - b.dataset.date
    };

    function render() {
        let visible = 0;
        cards.sort(sorters[sort.value]).forEach(card => {
            const show = filter === "all" || card.dataset.direct === "1";
            card.hidden = !show;
            if (show) visible++;
            grid.insertBefore(card, discover);
        });
        emptyMsg.hidden = visible > 0;
    }

    tabs.forEach(tab => tab.addEventListener("click", function () {
        filter = tab.dataset.filter;
        tabs.forEach(t => {
            const active = t === tab;
            t.classList.toggle("is-active", active);
            t.setAttribute("aria-selected", active);
        });
        render();
    }));

    sort.addEventListener("change", render);
});
