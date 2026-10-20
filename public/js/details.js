function showAlert(message, isError = false) {
    // Crear el elemento de alerta
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert ${isError ? 'alert-danger' : 'alert-success'} alert-dismissible fade show`;
    alertDiv.setAttribute('role', 'alert');

    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    // Insertar la alerta en la zona de avisos de la cabecera
    document.getElementById('alert-area').appendChild(alertDiv);

    // Hacer scroll hacia arriba
    window.scrollTo({ top: 0, behavior: 'smooth' });

    // Autoclose después de 3 segundos (Bootstrap no se expone como global: se quita con la transición CSS)
    setTimeout(() => {
        alertDiv.classList.remove('show');
        setTimeout(() => alertDiv.remove(), 200);
    }, 3000);
}

function toggleFavorite(button) {
    const form = button.closest('form');
    const url = form.action;
    const method = form.querySelector('input[name="_method"]') ? 'DELETE' : 'POST';

    // Mostrar indicador de carga
    button.disabled = true;
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';

    // Preparar datos del formulario
    const formData = new FormData(form);

    // Configurar la solicitud
    const requestOptions = {
        method: 'POST', // Siempre POST, Laravel maneja _method internamente
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    };

    fetch(url, requestOptions)
        .then(async response => {

            if (!response.ok) {
                const errorText = await response.text();
                console.error('Error response:', errorText);
                throw new Error(`Error ${response.status}: ${errorText}`);
            }

            const data = await response.json();

            // Verificar si estamos en la vista de detalles de favoritos
            const isFavoriteDetailsPage = window.location.pathname.includes('/favorites/') &&
                window.location.pathname.includes('/details');

            if (isFavoriteDetailsPage && !data.is_favorite) {
                // Si estamos en detalles de favorito y acabamos de eliminarlo, redirigir
                showAlert(data.message || 'Favorito eliminado', false);
                setTimeout(() => {
                    window.location.href = '/favorites';
                }, 1500);
                return;
            }

            // Mostrar mensaje de éxito
            showAlert(data.message || 'Operación exitosa', false);

            // Recargar la página para actualizar el estado
            setTimeout(() => {
                window.location.reload();
            }, 1500);

        })
        .catch(error => {
            console.error('Error completo:', error);
            showAlert('Error: ' + error.message, true);

            // Restaurar botón en caso de error
            button.innerHTML = originalContent;
            button.disabled = false;
        });
}

// Los desplegables y los avisos descartables los inicializa solo la API de datos de Bootstrap
// (data-bs-*): Bootstrap no se expone como global, así que no hace falta inicializarlos a mano.

// Función de respaldo para mostrar notificaciones
function showNotification(message, isError = false) {
    showAlert(message, isError);
}