@extends('mails.layout')

@section('title', 'Verifica tu email')
@section('badge', 'Verificación de cuenta')
@section('heading', 'Verifica tu dirección de email')
@section('subtitle', 'Confirma tu cuenta para activar tus alertas de precio y tu radar de vuelos.')

@section('content')
    <h2>¡Ya casi estás listo, {{ $user->name }}!</h2>
    <p>
        Para completar tu registro en <span class="brand">FlyLow</span> y empezar a usar el comparador, las alertas de
        precio y el radar en vivo, confirma tu dirección de correo electrónico.
    </p>

    <div class="panel">
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
            <tr class="row">
                <td class="k">Email a verificar</td>
                <td class="v">{{ $user->email }} <span class="pill pill--amber">Pendiente</span></td>
            </tr>
            <tr class="row">
                <td class="k">ID de usuario</td>
                <td class="v"><span class="brand">FLY</span>-{{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}</td>
            </tr>
        </table>
    </div>

    <div class="note note--green">
        <strong>Por tu seguridad:</strong> este enlace expirará en
        <strong>{{ config('auth.verification.expire', 60) }} minutos</strong>. Comprueba que la dirección de email de
        tu perfil sea la correcta antes de continuar.
    </div>

    <div style="text-align: center; padding: 6px 0 10px;">
        <a href="{{ $verificationUrl }}" class="btn">Verificar mi email</a>
    </div>

    <div class="note note--grey">
        <strong>¿El botón no responde?</strong> Copia y pega este enlace en tu navegador:
        <span class="link-box">{{ $verificationUrl }}</span>
    </div>

    <p class="small" style="font-size: 13px !important;">
        Si no creaste una cuenta en FlyLow, puedes ignorar este mensaje o contactar con nuestro equipo de soporte.
    </p>
@endsection

@section('legal')
    <p>FlyLow protege tus datos conforme al RGPD y la normativa europea vigente.</p>
@endsection
