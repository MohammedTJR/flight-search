<?php

namespace App\Support;

class PasswordMessages
{
    /**
     * Mensajes en español para los estados del broker de contraseñas
     * (la app no tiene ficheros de idioma y su locale es "en").
     */
    public static function es(string $status): string
    {
        return match ($status) {
            'passwords.sent' => 'Hemos enviado el enlace de restablecimiento a tu correo electrónico. Revisa tu bandeja de entrada o la carpeta de spam.',
            'passwords.reset' => 'Tu contraseña se ha restablecido. Ya puedes iniciar sesión.',
            'passwords.user' => 'No encontramos ninguna cuenta con ese correo electrónico.',
            'passwords.token' => 'El enlace de restablecimiento no es válido o ha caducado. Solicita uno nuevo.',
            'passwords.throttled' => 'Espera un momento antes de volver a intentarlo.',
            default => __($status),
        };
    }
}
