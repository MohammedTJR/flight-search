document.addEventListener("DOMContentLoaded", function () {
    const { priceData, priceLevel, priceRange } = window.flightPrices;

    // Nivel de precio (la API lo devuelve en inglés): etiqueta en español, color y modificador de estilo
    const niveles = {
        low: { texto: "Bajo", color: "#10b981", icono: "fa-arrow-trend-down", clase: "is-low" },
        typical: { texto: "Habitual", color: "#f59e0b", icono: "fa-minus", clase: "is-typical" },
        high: { texto: "Alto", color: "#ef4444", icono: "fa-arrow-trend-up", clase: "is-high" },
    };
    const nivel = niveles[String(priceLevel).toLowerCase()];
    let color = "#0ea5e9";

    const priceLevelElement = document.getElementById("priceLevel");
    if (priceLevelElement) {
        if (nivel) {
            color = nivel.color;
            priceLevelElement.textContent = nivel.texto + " ";
            const icon = document.createElement("i");
            icon.classList.add("fa-solid", nivel.icono);
            priceLevelElement.appendChild(icon);
            const pill = document.getElementById("priceLevelPill");
            if (pill) pill.classList.add(nivel.clase);
        } else {
            priceLevelElement.textContent = priceLevel;
        }
    }

    // Muestra el rango de precios o un mensaje si no hay datos
    const priceRangeElement = document.getElementById("priceRange");
    if (priceRangeElement) {
        priceRangeElement.textContent = priceRange.length === 2 ?
            `${priceRange[0]} - ${priceRange[1]}` : "No disponible";
    }

    const canvas = document.getElementById("priceChart");
    if (!canvas || priceData.length === 0) {
        return; // Sin gráfico o sin datos de precios
    }

    // Formatea las fechas de los datos de precios
    const labels = priceData.map(entry => {
        const date = new Date(entry[0] * 1000);
        return date.toLocaleDateString("es-ES", { day: "2-digit", month: "short" });
    });
    const prices = priceData.map(entry => entry[1]);

    const ctx = canvas.getContext("2d");
    const gradient = ctx.createLinearGradient(0, 0, 0, 260);
    gradient.addColorStop(0, "rgba(14, 165, 233, 0.25)");
    gradient.addColorStop(1, "rgba(14, 165, 233, 0)");

    new Chart(ctx, {
        type: "line",
        data: {
            labels: labels,
            datasets: [{
                label: "Precio histórico (€)",
                data: prices,
                borderColor: color,
                backgroundColor: gradient,
                borderWidth: 2,
                pointRadius: 0,
                pointHoverRadius: 5,
                tension: 0.35,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { intersect: false, mode: "index" },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: "#060d19",
                    padding: 10,
                    displayColors: false,
                    callbacks: { label: item => `${item.parsed.y} €` }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { maxTicksLimit: 8, color: "#64748b" }
                },
                y: {
                    grid: { color: "#e2e8f0" },
                    ticks: { color: "#64748b", callback: value => `${value} €` }
                }
            }
        }
    });
});
