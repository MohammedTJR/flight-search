document.addEventListener("DOMContentLoaded", function () {
    const { priceData, priceLevel, priceRange } = window.flightPrices;

    const level = priceLevel.charAt(0).toUpperCase() + priceLevel.slice(1);
    document.getElementById("priceLevel").innerText = level;
    const priceLevelElement = document.getElementById("priceLevel");
    let color;
    if (level === "Low") {
        color = "green";
        const downIcon = document.createElement("i");
        downIcon.classList.add("fa-solid", "fa-arrow-trend-down");
        downIcon.style.color = "#00ff00";
        priceLevelElement.appendChild(document.createTextNode(" "));
        priceLevelElement.appendChild(downIcon);
    } else if (level === "Typical") {
        color = "orange";
        const icon = document.createElement("i");
        icon.classList.add("fa-solid", "fa-minus");
        icon.style.color = "#ff6600";
        priceLevelElement.appendChild(document.createTextNode(" "));
        priceLevelElement.appendChild(icon);
    } else if (level === "High") {
        color = "red";
        const upIcon = document.createElement("i");
        upIcon.classList.add("fa-solid", "fa-arrow-trend-up");
        upIcon.style.color = "#ff0000";
        priceLevelElement.appendChild(document.createTextNode(" "));
        priceLevelElement.appendChild(upIcon);
    }

    document.getElementById("priceRange").innerText = priceRange.length === 2 ?
        `${priceRange[0]} - ${priceRange[1]}` : "No disponible";

    if (priceData.length === 0) {
        console.warn("No hay datos de precios.");
        return;
    }

    let currentIndex = priceData.length - 1;
    const labels = priceData.map(entry => {
        const date = new Date(entry[0] * 1000);
        return date.toLocaleDateString();
    });

    const prices = priceData.map(entry => entry[1]);

    const ctx = document.getElementById("priceChart").getContext("2d");
    let chart = new Chart(ctx, {
        type: "line",
        data: {
            labels: labels.slice(0, currentIndex + 1),
            datasets: [{
                label: "Precio Histórico (€)",
                data: prices.slice(0, currentIndex + 1),
                borderColor: color,
                backgroundColor: "rgba(0, 0, 255, 0.1)",
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: "Fecha"
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: "Precio (€)"
                    }
                }
            }
        }
    });
});
