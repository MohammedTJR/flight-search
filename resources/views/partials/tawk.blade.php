{{-- Chat en vivo (Tawk.to): compartido por el centro de ayuda y las preguntas frecuentes --}}
<script>
    var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();

    // Abre o cierra el chat sin fallar si el widget aún no ha cargado
    function openChat() {
        if (window.Tawk_API && typeof Tawk_API.toggle === 'function') {
            Tawk_API.toggle();
        }
    }

    @auth
        Tawk_API.onLoad = function () {
            Tawk_API.setAttributes({
                'name': @json(Auth::user()->name),
                'email': @json(Auth::user()->email),
                'hash': @json(hash_hmac('sha256', Auth::user()->email, config('app.key'))),
                'user_id': @json(Auth::id()),
                'plan': @json(Auth::user()->plan ?? 'Free')
            }, function (error) { });

            Tawk_API.addEvent('onChatMaximized', function () {
                Tawk_API.sendMessage('¡Hola ' + @json(Auth::user()->name) + '! ¿En qué podemos ayudarte hoy?');
            });
        };
    @endauth

    (function () {
        var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
        s1.async = true;
        s1.src = 'https://embed.tawk.to/{{ env("TAWKTO_PROPERTY_ID") }}/{{ env("TAWKTO_WIDGET_ID") }}';
        s1.charset = 'UTF-8';
        s1.setAttribute('crossorigin', '*');
        s0.parentNode.insertBefore(s1, s0);
    })();
</script>
