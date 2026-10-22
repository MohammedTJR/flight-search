@extends('mails.layout')

@section('title', 'Restablecer contraseña')
@section('badge', 'Solicitud de seguridad')
@section('heading', 'Restablece tu contraseña')
@section('subtitle', 'Servicio de autenticación oficial de FlyLow')

@section('content')
    <h2>Hola {{ $user->name }},</h2>
    <p>
        Hemos recibido una solicitud para restablecer la contraseña de tu cuenta en <span class="brand">FlyLow</span>.
        Si fuiste tú, crea una nueva contraseña segura con el siguiente botón.
    </p>

    <div class="panel">
        <p class="panel-title">Detalles de la solicitud</p>
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
            <tr class="row">
                <td class="k">Solicitado por</td>
                <td class="v">{{ $user->email }}</td>
            </tr>
            <tr class="row">
                <td class="k">Fecha y hora</td>
                <td class="v">{{ now()->timezone('Europe/Madrid')->format('d/m/Y H:i') }} (hora peninsular)</td>
            </tr>
        </table>
    </div>

    <div class="note note--red">
        Por razones de seguridad, este enlace <strong>expirará en 60 minutos</strong>.
    </div>

    <div style="text-align: center; padding: 6px 0 10px;">
        <a href="{{ $resetUrl }}" class="btn">Restablecer contraseña</a>
        <p class="small" style="margin: 10px 0 0;">¿Problemas con el botón? Copia y pega el enlace en tu navegador.</p>
    </div>

    <div class="note note--grey">
        <strong>¿No reconoces esta actividad?</strong> Si no solicitaste el cambio de contraseña, no hay nada que
        hacer: tu cuenta sigue protegida y no se aplicará ningún cambio. Puedes ignorar este mensaje o contactar con
        nuestro equipo de soporte.
        <br><br>
        Para tu seguridad, nunca compartas este enlace con nadie.
    </div>

    <span class="small" style="display: block;">Enlace completo:</span>
    <span class="link-box">{{ $resetUrl }}</span>
@endsection
