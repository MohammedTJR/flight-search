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

    // Medidor de fortaleza y coincidencia de contraseñas (solo en el registro)
    function setupPasswordFeedback() {
        const pwd = document.getElementById('password');
        const confirm = document.getElementById('password_confirmation');
        const label = document.getElementById('strength-label');
        const bars = document.querySelectorAll('#strength-bars i');
        const match = document.getElementById('match-feedback');
        if (!pwd || !label || bars.length === 0) return;

        const reset = () => {
            bars.forEach(b => { b.className = ''; });
            label.className = 'au-hint';
        };

        function checkMatch() {
            if (!confirm || !match) return;
            if (confirm.value.length === 0) {
                match.hidden = true;
                return;
            }
            const same = pwd.value === confirm.value;
            match.hidden = false;
            match.textContent = same ? 'Coinciden' : 'No coinciden';
            match.className = 'au-hint ' + (same ? 'is-strong' : 'is-weak');
        }

        pwd.addEventListener('input', function () {
            const v = pwd.value;
            reset();
            if (v.length === 0) {
                label.textContent = 'Mínimo 8 caracteres';
                checkMatch();
                return;
            }

            let score = 0;
            if (v.length >= 8) score++;
            if (v.length >= 12) score++;
            if (/[A-Z]/.test(v) && /[0-9]/.test(v)) score++;
            if (/[^A-Za-z0-9]/.test(v)) score++;

            const level = score <= 1 ? 'is-weak' : (score === 2 ? 'is-mid' : 'is-strong');
            const text = score <= 1 ? 'Débil' : (score === 2 ? 'Media' : (score === 4 ? 'Excelente' : 'Segura'));
            for (let i = 0; i < Math.max(score, 1); i++) bars[i].className = level;
            label.textContent = 'Fortaleza: ' + text;
            label.className = 'au-hint ' + level;
            checkMatch();
        });

        if (confirm) confirm.addEventListener('input', checkMatch);
    }

    // Inicializar los toggles de contraseña
    setupPasswordToggle('togglePassword', 'password');
    setupPasswordToggle('togglePasswordConfirm', 'password_confirmation');
    setupPasswordFeedback();

    // Inicializar la validación del formulario
    setupFormValidation();
});