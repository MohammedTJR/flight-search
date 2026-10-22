// Añadir vuelo a seguimiento: botón para borrar el número escrito.
document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("flight_number");
    const clear = document.getElementById("clearFlight");
    if (!input || !clear) return;

    const sync = () => { clear.hidden = input.value.trim() === ""; };

    input.addEventListener("input", sync);
    clear.addEventListener("click", function () {
        input.value = "";
        sync();
        input.focus();
    });
    sync();
});
