// Sistema de diálogos y avisos de FlyLow: sustituye alert(), confirm(), prompt() y SweetAlert.
//
//   const ok = await FlyLowDialog.confirm({ title, message, confirmText, cancelText, variant: "destructive" });
//   await FlyLowDialog.alert({ title, message, variant: "warning" });
//   const valor = await FlyLowDialog.prompt({ title, label, value });          // texto o null
//   FlyLowToast.success({ title, description, undoLabel, onUndo });
//
// En formularios basta con atributos: <form data-confirm="¿Seguro?" data-confirm-title="..."
// data-confirm-ok="Sí" data-confirm-cancel="No" data-confirm-variant="destructive"
// data-confirm-ctx-title="..." data-confirm-ctx-sub="...">; al enviarlo se pide confirmación.
(function () {
    "use strict";

    // ------------------------------------------------------------------ Diálogos
    const VARIANTS = {
        destructive: { icon: "fa-trash-can", eyebrow: "Confirmación crítica" },
        primary: { icon: "fa-circle-question", eyebrow: "Confirmación" },
        info: { icon: "fa-circle-info", eyebrow: "Información" },
        success: { icon: "fa-circle-check", eyebrow: "Todo listo" },
        warning: { icon: "fa-triangle-exclamation", eyebrow: "Atención" },
        danger: { icon: "fa-circle-exclamation", eyebrow: "Ha ocurrido un error" }
    };

    let chain = Promise.resolve();
    let uid = 0;

    function el(tag, className, text) {
        const node = document.createElement(tag);
        if (className) node.className = className;
        if (text !== undefined && text !== null) node.textContent = text;
        return node;
    }

    function button(label, className, icon) {
        const btn = el("button", "fd-btn " + className);
        btn.type = "button";
        if (icon) {
            const i = el("i", "fas " + icon);
            i.setAttribute("aria-hidden", "true");
            btn.appendChild(i);
        }
        btn.appendChild(document.createTextNode(label));
        return btn;
    }

    // Abre un diálogo y devuelve una promesa con su resultado. Si ya hay uno abierto, espera su turno.
    function open(config) {
        const run = chain.then(function () { return render(config); });
        chain = run.catch(function () { /* un fallo no debe bloquear los siguientes */ });
        return run;
    }

    function render(config) {
        return new Promise(function (resolve) {
            const variant = VARIANTS[config.variant] ? config.variant : "info";
            const meta = VARIANTS[variant];
            const id = "fd-" + (++uid);
            const previousFocus = document.activeElement;

            const root = el("div", "fd-root");
            const backdrop = el("div", "fd-backdrop");
            const dialog = el("div", "fd-dialog");
            dialog.setAttribute("role", config.kind === "alert" ? "alertdialog" : "dialog");
            dialog.setAttribute("aria-modal", "true");
            dialog.setAttribute("aria-labelledby", id + "-title");
            dialog.tabIndex = -1;

            // Cabecera: icono, etiqueta y título
            const head = el("div", "fd-head");
            const icon = el("span", "fd-icon fd-icon--" + variant);
            const glyph = el("i", "fas " + (config.icon || meta.icon));
            glyph.setAttribute("aria-hidden", "true");
            icon.appendChild(glyph);
            const titles = el("div", "fd-titles");
            titles.appendChild(el("span", "fd-eyebrow fd-eyebrow--" + variant, config.eyebrow || meta.eyebrow));
            const title = el("h3", "fd-title", config.title || "");
            title.id = id + "-title";
            titles.appendChild(title);
            head.appendChild(icon);
            head.appendChild(titles);
            dialog.appendChild(head);

            // Mensaje (texto plano: respeta saltos de línea y nunca interpreta HTML)
            if (config.message) {
                const message = el("p", "fd-message", config.message);
                message.id = id + "-msg";
                dialog.setAttribute("aria-describedby", message.id);
                dialog.appendChild(message);
            }

            // Tarjeta de contexto opcional (p. ej. el vuelo afectado)
            if (config.context && (config.context.title || config.context.subtitle)) {
                const ctx = el("div", "fd-context");
                const ctxIcon = el("span", "fd-context__icon");
                const ctxGlyph = el("i", "fas " + (config.context.icon || "fa-plane"));
                ctxGlyph.setAttribute("aria-hidden", "true");
                ctxIcon.appendChild(ctxGlyph);
                const ctxText = el("div", "fd-context__text");
                if (config.context.title) ctxText.appendChild(el("strong", "", config.context.title));
                if (config.context.subtitle) ctxText.appendChild(el("span", "", config.context.subtitle));
                ctx.appendChild(ctxIcon);
                ctx.appendChild(ctxText);
                dialog.appendChild(ctx);
            }

            // Campo de texto (prompt)
            let input = null;
            if (config.kind === "prompt") {
                const field = el("div", "fd-field");
                if (config.label) {
                    const label = el("label", "", config.label);
                    label.htmlFor = id + "-input";
                    field.appendChild(label);
                }
                input = el("input");
                input.id = id + "-input";
                input.type = config.inputType || "text";
                input.value = config.value !== undefined ? config.value : "";
                if (config.placeholder) input.placeholder = config.placeholder;
                field.appendChild(input);
                dialog.appendChild(field);
            }

            // Botones
            const actions = el("div", "fd-actions");
            let cancelBtn = null;
            let okBtn;
            const okClass = variant === "destructive" ? "fd-btn--danger" : "fd-btn--primary";

            if (config.kind === "alert") {
                okBtn = button(config.okText || "Entendido", "fd-btn--primary", config.okIcon || "fa-check");
            } else {
                cancelBtn = button(config.cancelText || "Cancelar", "fd-btn--ghost");
                okBtn = button(config.confirmText || "Aceptar", okClass, config.confirmIcon || (variant === "destructive" ? "fa-trash-can" : "fa-check"));
                actions.appendChild(cancelBtn);
            }
            actions.appendChild(okBtn);
            dialog.appendChild(actions);

            root.appendChild(backdrop);
            root.appendChild(dialog);
            document.body.appendChild(root);
            document.documentElement.classList.add("fd-open");
            requestAnimationFrame(function () { root.classList.add("is-open"); });

            // Foco inicial: en las acciones destructivas, el botón seguro; en un prompt, el campo
            const initial = input || (variant === "destructive" && cancelBtn ? cancelBtn : okBtn);
            setTimeout(function () {
                initial.focus();
                if (input) input.select();
            }, 30);

            let closed = false;
            function close(value) {
                if (closed) return;
                closed = true;
                document.removeEventListener("keydown", onKey, true);
                root.classList.remove("is-open");
                setTimeout(function () {
                    root.remove();
                    if (!document.querySelector(".fd-root")) document.documentElement.classList.remove("fd-open");
                    if (previousFocus && typeof previousFocus.focus === "function") previousFocus.focus();
                    resolve(value);
                }, 220);
            }

            const cancelValue = config.kind === "confirm" ? false : (config.kind === "prompt" ? null : true);
            const okValue = function () {
                return config.kind === "prompt" ? input.value : true;
            };

            function onKey(e) {
                if (e.key === "Escape") {
                    e.preventDefault();
                    close(cancelValue);
                    return;
                }
                if (e.key === "Enter" && input && document.activeElement === input) {
                    e.preventDefault();
                    close(okValue());
                    return;
                }
                if (e.key !== "Tab") return;

                // Trampa de foco dentro del diálogo
                const focusables = Array.prototype.slice.call(
                    dialog.querySelectorAll("button, input, select, textarea, [href]")
                ).filter(function (n) { return !n.disabled; });
                if (!focusables.length) return;
                const first = focusables[0];
                const last = focusables[focusables.length - 1];
                if (e.shiftKey && document.activeElement === first) {
                    e.preventDefault();
                    last.focus();
                } else if (!e.shiftKey && document.activeElement === last) {
                    e.preventDefault();
                    first.focus();
                }
            }

            document.addEventListener("keydown", onKey, true);
            backdrop.addEventListener("click", function () { close(cancelValue); });
            okBtn.addEventListener("click", function () { close(okValue()); });
            if (cancelBtn) cancelBtn.addEventListener("click", function () { close(cancelValue); });
        });
    }

    window.FlyLowDialog = {
        confirm: function (options) { return open(Object.assign({ kind: "confirm", variant: "primary" }, options)); },
        alert: function (options) { return open(Object.assign({ kind: "alert", variant: "info" }, options)); },
        prompt: function (options) { return open(Object.assign({ kind: "prompt", variant: "primary" }, options)); }
    };

    // ------------------------------------------------------------------ Avisos (toasts)
    const TOAST_ICONS = {
        success: "fa-circle-check",
        info: "fa-circle-info",
        warning: "fa-triangle-exclamation",
        danger: "fa-circle-exclamation"
    };
    const MAX_TOASTS = 5;
    let viewport = null;

    function getViewport() {
        if (!viewport) {
            viewport = el("div", "ft-viewport");
            viewport.setAttribute("aria-live", "polite");
            document.body.appendChild(viewport);
        }
        return viewport;
    }

    function removeToast(node) {
        if (!node || !node.parentNode) return;
        node.classList.remove("is-in");
        node.classList.add("is-out");
        setTimeout(function () { if (node.parentNode) node.remove(); }, 260);
    }

    function showToast(type, options) {
        options = options || {};
        const kind = TOAST_ICONS[type] ? type : "info";
        const host = getViewport();

        // Cola: como mucho MAX_TOASTS a la vez; el más antiguo se retira primero
        const live = host.querySelectorAll(".ft-toast:not(.is-out)");
        for (let i = 0; i <= live.length - MAX_TOASTS; i++) removeToast(live[i]);

        const toast = el("div", "ft-toast ft-toast--" + kind);
        toast.setAttribute("role", kind === "danger" ? "alert" : "status");

        const icon = el("span", "ft-icon");
        const glyph = el("i", "fas " + TOAST_ICONS[kind]);
        glyph.setAttribute("aria-hidden", "true");
        icon.appendChild(glyph);

        const text = el("div", "ft-text");
        text.appendChild(el("strong", "", options.title || ""));
        if (options.description) text.appendChild(el("span", "", options.description));

        const tools = el("div", "ft-tools");
        if (options.undoLabel) {
            const undo = el("button", "ft-undo", options.undoLabel);
            undo.type = "button";
            undo.addEventListener("click", function () {
                if (typeof options.onUndo === "function") options.onUndo();
                removeToast(toast);
            });
            tools.appendChild(undo);
        }
        const close = el("button", "ft-close");
        close.type = "button";
        close.setAttribute("aria-label", "Cerrar notificación");
        const closeGlyph = el("i", "fas fa-xmark");
        closeGlyph.setAttribute("aria-hidden", "true");
        close.appendChild(closeGlyph);
        close.addEventListener("click", function () { removeToast(toast); });
        tools.appendChild(close);

        toast.appendChild(icon);
        toast.appendChild(text);
        toast.appendChild(tools);
        host.appendChild(toast);
        requestAnimationFrame(function () { toast.classList.add("is-in"); });

        // Se retira solo; con el ratón encima se pausa
        const duration = options.duration || 5000;
        let timer = setTimeout(function () { removeToast(toast); }, duration);
        toast.addEventListener("mouseenter", function () { clearTimeout(timer); });
        toast.addEventListener("mouseleave", function () {
            timer = setTimeout(function () { removeToast(toast); }, 2000);
        });

        return toast;
    }

    window.FlyLowToast = {
        show: showToast,
        success: function (o) { return showToast("success", o); },
        info: function (o) { return showToast("info", o); },
        warning: function (o) { return showToast("warning", o); },
        danger: function (o) { return showToast("danger", o); }
    };

    // ------------------------------------------------------------------ Formularios con data-confirm
    document.addEventListener("submit", function (e) {
        const form = e.target;
        if (!(form instanceof HTMLFormElement) || !form.dataset.confirm || e.defaultPrevented) return;

        e.preventDefault();

        const data = form.dataset;
        FlyLowDialog.confirm({
            title: data.confirmTitle || "¿Seguro que quieres continuar?",
            message: data.confirm,
            confirmText: data.confirmOk || "Sí, continuar",
            cancelText: data.confirmCancel || "Cancelar",
            variant: data.confirmVariant || "primary",
            context: data.confirmCtxTitle || data.confirmCtxSub
                ? { title: data.confirmCtxTitle, subtitle: data.confirmCtxSub, icon: data.confirmCtxIcon }
                : null
        }).then(function (accepted) {
            // submit() no vuelve a lanzar el evento "submit", así que no hay bucle
            if (accepted) HTMLFormElement.prototype.submit.call(form);
        });
    });
})();
