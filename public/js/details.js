function showAlert(message, isError = false) {
    // Crear el elemento de alerta
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert ${isError ? 'alert-danger' : 'alert-success'} alert-dismissible fade show`;
    alertDiv.setAttribute('role', 'alert');

    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    // Insertar la alerta al principio del contenedor
    const container = document.querySelector('.container');
    const header = container.querySelector('header');
    container.insertBefore(alertDiv, header);

    // Hacer scroll hacia arriba
    window.scrollTo({ top: 0, behavior: 'smooth' });

    // Autoclose después de 3 segundos
    setTimeout(() => {
        const bsAlert = new bootstrap.Alert(alertDiv);
        bsAlert.close();
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

    console.log('Enviando solicitud a:', url);
    console.log('Método:', method);
    console.log('FormData:', Object.fromEntries(formData));

    fetch(url, requestOptions)
        .then(async response => {
            console.log('Respuesta recibida:', response.status);

            if (!response.ok) {
                const errorText = await response.text();
                console.error('Error response:', errorText);
                throw new Error(`Error ${response.status}: ${errorText}`);
            }

            const data = await response.json();
            console.log('Datos recibidos:', data);

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

// Inicialización cuando el DOM está listo
document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM listo, inicializando componentes...');

    // Inicializar dropdowns
    const dropdownElementList = document.querySelectorAll('.dropdown-toggle');
    console.log('Dropdowns encontrados:', dropdownElementList.length);

    if (typeof bootstrap !== 'undefined' && bootstrap.Dropdown) {
        dropdownElementList.forEach(dropdownToggleEl => {
            try {
                new bootstrap.Dropdown(dropdownToggleEl);
                console.log('Dropdown inicializado correctamente');
            } catch (e) {
                console.error('Error inicializando dropdown:', e);
            }
        });
    } else {
        console.error('Bootstrap no está disponible');
    }

    // Inicializar tooltips si existen
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    if (tooltipTriggerList.length > 0 && typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        tooltipTriggerList.forEach(tooltipTriggerEl => {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});

// Función de respaldo para mostrar notificaciones
function showNotification(message, isError = false) {
    showAlert(message, isError);
}