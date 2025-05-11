document.addEventListener('DOMContentLoaded', function() {
    // Función para manejar la visibilidad de la contraseña
    function setupPasswordToggle(toggleId, passwordId) {
        const toggleButton = document.getElementById(toggleId);
        const passwordInput = document.getElementById(passwordId);

        if (toggleButton && passwordInput) {
            toggleButton.addEventListener('click', function(e) {
                e.preventDefault();
                const type = passwordInput.type === 'password' ? 'text' : 'password';
                passwordInput.type = type;
                
                const icon = this.querySelector('i');
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });
        }
    }

    // Configuración de la validación del formulario
    function setupFormValidation() {
        const forms = document.querySelectorAll('.needs-validation');
        
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }

    // Inicializar los toggles de contraseña
    setupPasswordToggle('togglePassword', 'password');
    setupPasswordToggle('togglePasswordConfirm', 'password_confirmation');

    // Inicializar la validación del formulario
    setupFormValidation();
});