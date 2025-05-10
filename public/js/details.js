function toggleFavorite(button) {
    const form = button.closest('form');
    const url = form.action;
    let method = form.method;

    // Mostrar indicador de carga
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';

    // Configurar la solicitud según el método
    const requestOptions = {
        method: method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    };

    // Solo incluir body para POST/PUT/PATCH
    if (method !== 'GET' && method !== 'HEAD') {
        const formData = Object.fromEntries(new FormData(form));
        requestOptions.body = JSON.stringify(formData);
    }

    fetch(url, requestOptions)
        .then(async response => {
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.error || 'Error en la solicitud');
            }

            // Verificar si estamos en la vista de detalles de favoritos
            const isFavoriteDetailsPage = window.location.pathname.includes('/favorites/') && 
                                window.location.pathname.includes('/details');

            if (isFavoriteDetailsPage && !data.is_favorite) {
                // Si estamos en detalles de favorito y acabamos de eliminarlo, redirigir
                window.location.href = '/favorites';
                return;
            }

            // Actualizar estado del botón y formulario
            if (data.is_favorite) {
                button.classList.remove('btn-outline-primary');
                button.classList.add('btn-danger');
                button.innerHTML = '<i class="fas fa-heart"></i> Quitar de favoritos';
                form.method = 'DELETE';
                form.action = `/favorites/${data.id}`;
            } else {
                button.classList.remove('btn-danger');
                button.classList.add('btn-outline-primary');
                button.innerHTML = '<i class="far fa-heart"></i> Guardar como favorito';
                form.method = 'POST';
                form.action = '/favorites';
            }

            showToast(data.message || 'Operación exitosa');

            // Solo recargar si no es una eliminación desde detalles de favorito
            if (!isFavoriteDetailsPage) {
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast(error.message, true);
        })
        .finally(() => {
            button.disabled = false;
        });
}

function showToast(message, isError = false) {
    const toastEl = document.querySelector('.toast');
    if (!toastEl) return;

    const toastBody = toastEl.querySelector('.toast-body');
    toastBody.textContent = message;

    toastEl.classList.remove(isError ? 'bg-success' : 'bg-danger');
    toastEl.classList.add(isError ? 'bg-danger' : 'bg-success');

    const toast = new bootstrap.Toast(toastEl);
    toast.show();
}

// Función para mostrar notificaciones que funciona con o sin Bootstrap
function showNotification(message, isError = false) {
    // Intentar usar Bootstrap Toast si está disponible
    if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
        const toastEl = document.querySelector('.toast');
        if (toastEl) {
            const toastBody = toastEl.querySelector('.toast-body');
            if (toastBody) toastBody.textContent = message;

            // Cambiar color si es error
            if (isError) {
                toastEl.classList.remove('bg-success');
                toastEl.classList.add('bg-danger');
            } else {
                toastEl.classList.remove('bg-danger');
                toastEl.classList.add('bg-success');
            }

            new bootstrap.Toast(toastEl).show();
            return;
        }
    }

    // Fallback a alert básico si Bootstrap no está disponible
    alert(message);
}