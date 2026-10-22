// Perfil: modal de preferencias, historial de búsquedas, vista previa del avatar y contraseña.
// Cada bloque comprueba que existan sus elementos, así sirve para "Mi perfil" y "Editar perfil".
document.addEventListener("DOMContentLoaded", function () {
    // ---------------------------- Mi perfil: modal de preferencias ----------------------------
    const modal = document.getElementById("preferencesModal");
    if (modal) {
        const open = () => modal.classList.add("is-open");
        const close = () => modal.classList.remove("is-open");

        document.getElementById("openPreferencesModal")?.addEventListener("click", open);
        document.getElementById("closePreferencesModal")?.addEventListener("click", close);
        document.getElementById("cancelPreferences")?.addEventListener("click", close);
        modal.addEventListener("click", e => { if (e.target === modal) close(); });
        document.addEventListener("keydown", e => { if (e.key === "Escape") close(); });

        const saveError = () => FlyLowToast.danger({
            title: "No se pudieron guardar las preferencias",
            description: "Inténtalo de nuevo en un momento."
        });

        document.getElementById("savePreferences")?.addEventListener("click", function () {
            const btn = this;
            const original = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>Guardando...';
            btn.disabled = true;

            fetch("/profile/preferences", {
                method: "POST",
                body: new FormData(document.getElementById("preferencesForm")),
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                    "X-Requested-With": "XMLHttpRequest",
                    "Accept": "application/json"
                },
                credentials: "same-origin"
            })
                .then(async response => {
                    const data = await response.json();
                    if (response.ok && data.success) {
                        close();
                        FlyLowToast.success({
                            title: "Preferencias guardadas",
                            description: "Tus cambios ya están aplicados."
                        });
                    } else if (response.status === 422 && data.errors) {
                        const lines = Object.values(data.errors).map(errors => "• " + errors.join(", "));
                        FlyLowDialog.alert({
                            variant: "warning",
                            title: "Revisa las preferencias",
                            message: lines.join("\n"),
                            okText: "Entendido"
                        });
                    } else {
                        saveError();
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    saveError();
                })
                .finally(() => {
                    btn.innerHTML = original;
                    btn.disabled = false;
                });
        });
    }

    // ---------------------------- Mi perfil: cargar más búsquedas ----------------------------
    const loadMore = document.getElementById("load-more-btn");
    if (loadMore) {
        const total = parseInt(document.getElementById("search-history").dataset.total, 10);

        loadMore.addEventListener("click", function () {
            const btn = this;
            const page = parseInt(btn.dataset.page, 10);
            const counter = document.getElementById("showing-count");
            const showing = parseInt(counter.textContent, 10);

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>Cargando...';

            fetch(`/profile/history?page=${page}`, { headers: { "X-Requested-With": "XMLHttpRequest" } })
                .then(response => response.json())
                .then(data => {
                    document.getElementById("search-history-container").insertAdjacentHTML("beforeend", data.html);

                    const now = Math.min(showing + 5, total);
                    counter.textContent = now;

                    if (now >= total) {
                        document.getElementById("load-more-container").remove();
                    } else {
                        btn.dataset.page = page + 1;
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-rotate"></i>Cargar más búsquedas';
                    }
                })
                .catch(() => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-rotate"></i>Cargar más búsquedas';
                });
        });
    }

    // ---------------------------- Editar perfil: avatar ----------------------------
    const avatar = document.getElementById("avatar");
    if (avatar) {
        avatar.addEventListener("change", function () {
            const file = avatar.files && avatar.files[0];
            if (!file) return;
            document.getElementById("avatar-name").textContent = file.name;
            const reader = new FileReader();
            reader.onload = e => { document.getElementById("avatar-preview").src = e.target.result; };
            reader.readAsDataURL(file);
        });
    }

    // ---------------------------- Editar perfil: contraseña ----------------------------
    document.querySelectorAll(".toggle-password").forEach(button => {
        button.addEventListener("click", function () {
            const input = this.previousElementSibling;
            const show = input.type === "password";
            input.type = show ? "text" : "password";
            this.innerHTML = show ? '<i class="far fa-eye-slash"></i>' : '<i class="far fa-eye"></i>';
        });
    });

    const pwd = document.getElementById("password");
    const bars = document.querySelectorAll("#strength-bars i");
    if (pwd && bars.length) {
        const label = document.getElementById("strength-label");
        const confirm = document.getElementById("password_confirmation");
        const match = document.getElementById("match-feedback");

        function checkMatch() {
            if (!confirm.value) { match.hidden = true; return; }
            const same = pwd.value === confirm.value;
            match.hidden = false;
            match.textContent = same ? "Coinciden" : "No coinciden";
            match.className = "pf-hint " + (same ? "is-strong" : "is-weak");
        }

        pwd.addEventListener("input", function () {
            const v = pwd.value;
            bars.forEach(b => { b.className = ""; });
            label.className = "pf-hint";

            if (!v) {
                label.textContent = "Mínimo 8 caracteres";
                checkMatch();
                return;
            }

            let score = 0;
            if (v.length >= 8) score++;
            if (v.length >= 12) score++;
            if (/[A-Z]/.test(v) && /[0-9]/.test(v)) score++;
            if (/[^A-Za-z0-9]/.test(v)) score++;

            const level = score <= 1 ? "is-weak" : (score === 2 ? "is-mid" : "is-strong");
            const text = score <= 1 ? "Débil" : (score === 2 ? "Media" : (score === 4 ? "Excelente" : "Segura"));
            for (let i = 0; i < Math.max(score, 1); i++) bars[i].className = level;
            label.textContent = "Fortaleza: " + text;
            label.className = "pf-hint " + level;
            checkMatch();
        });

        confirm.addEventListener("input", checkMatch);
    }
});
