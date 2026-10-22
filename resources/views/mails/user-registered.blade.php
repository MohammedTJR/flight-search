@extends('mails.layout')

@section('title', 'Bienvenido a FlyLow')
@section('badge', 'Confirmación de registro')
@section('heading', '¡Bienvenido a bordo, ' . $user->name . '!')
@section('subtitle', 'Tu cuenta de FlyLow ya está creada.')

@section('content')
    <p>
        Gracias por registrarte en <span class="brand">FlyLow</span>. Estamos encantados de tenerte en nuestra
        comunidad de viajeros: a partir de ahora puedes comparar tarifas, seguir vuelos y guardar tus favoritos.
    </p>

    <div class="panel">
        <p class="panel-title">Resumen de tu cuenta</p>
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
            <tr class="row">
                <td class="k">Correo registrado</td>
                <td class="v">{{ $user->email }}</td>
            </tr>
            <tr class="row">
                <td class="k">Fecha de registro</td>
                <td class="v">{{ $user->created_at->timezone('Europe/Madrid')->format('d/m/Y H:i') }}</td>
            </tr>
            <tr class="row">
                <td class="k">ID de usuario</td>
                <td class="v"><span class="brand">FLY</span>-{{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}</td>
            </tr>
        </table>
    </div>

    <div style="text-align: center; padding: 4px 0 18px;">
        <p style="margin-bottom: 14px;">Ya puedes explorar las mejores ofertas y planificar tu próximo viaje.</p>
        <a href="{{ route('home') }}" class="btn">Explorar vuelos disponibles</a>
    </div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin: 4px 0 22px;">
        <tr>
            <td width="33%" valign="top" style="padding-right: 6px;">
                <div style="padding: 12px 8px; text-align: center; background-color: #eff6ff; border: 1px solid #dbeafe; border-radius: 12px;">
                    <div style="font-size: 20px;">✈️</div>
                    <div style="font-size: 12px; font-weight: 700; color: #0f172a;">Comparador</div>
                    <div style="font-size: 11px; color: #64748b;">Tarifas de vuelos</div>
                </div>
            </td>
            <td width="33%" valign="top" style="padding: 0 3px;">
                <div style="padding: 12px 8px; text-align: center; background-color: #eff6ff; border: 1px solid #dbeafe; border-radius: 12px;">
                    <div style="font-size: 20px;">📡</div>
                    <div style="font-size: 12px; font-weight: 700; color: #0f172a;">Radar en vivo</div>
                    <div style="font-size: 11px; color: #64748b;">Seguimiento de vuelos</div>
                </div>
            </td>
            <td width="33%" valign="top" style="padding-left: 6px;">
                <div style="padding: 12px 8px; text-align: center; background-color: #eff6ff; border: 1px solid #dbeafe; border-radius: 12px;">
                    <div style="font-size: 20px;">🔔</div>
                    <div style="font-size: 12px; font-weight: 700; color: #0f172a;">Alertas de precio</div>
                    <div style="font-size: 11px; color: #64748b;">En tus favoritos</div>
                </div>
            </td>
        </tr>
    </table>

    <div class="note note--amber">
        <strong>¿Necesitas ayuda?</strong> Nuestro equipo de soporte está disponible 24/7 para asistirte en cualquier
        consulta.
    </div>
@endsection

@section('legal')
    <p>Si no realizaste este registro, contacta con nuestro equipo de soporte.</p>
@endsection
