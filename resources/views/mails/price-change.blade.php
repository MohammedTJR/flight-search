@extends('mails.layout')

@php
    $sube = $changeType === 'aumentado';
    $porcentaje = $oldPrice > 0 ? round($changeAmount / $oldPrice * 100) : null;
    $signo = $sube ? '+' : '-';

    // Datos del vuelo guardado (respuesta de la API de vuelos)
    $detalles = $flight->flight_details ?? [];
    $tramos = $detalles['flights'] ?? [];
    $primero = $tramos[0] ?? [];
    $ultimo = $tramos ? $tramos[count($tramos) - 1] : [];
    $escalas = count($detalles['layovers'] ?? []);
    $duracion = $detalles['total_duration'] ?? null;
    $nombreOrigen = $primero['departure_airport']['name'] ?? null;
    $nombreDestino = $ultimo['arrival_airport']['name'] ?? null;
@endphp

@section('title', 'Cambio de precio')
@section('badge', 'Alerta de precio')
@section('heading', '¡El precio de tu vuelo ha cambiado!')
@section('subtitle', 'Hemos detectado una variación en la tarifa de tu vuelo favorito.')

@section('content')
    <h2>Hola {{ $user->name }},</h2>
    <p>Te avisamos de que una de tus alertas activas ha registrado un movimiento en la tarifa aérea de tu viaje
        planificado.</p>

    {{-- Detalles del vuelo --}}
    <div class="panel">
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 14px;">
            <tr>
                <td class="panel-title" style="padding: 0 0 10px; margin: 0;">Detalles del vuelo</td>
                <td class="panel-title" align="right" style="padding: 0 0 10px; margin: 0;">
                    <span class="pill" style="color: #1d4ed8; background-color: #eff6ff; border: 1px solid #bfdbfe; text-transform: none; letter-spacing: 0;">{{ $escalas === 0 ? 'Directo' : $escalas . ($escalas === 1 ? ' escala' : ' escalas') }}</span>
                </td>
            </tr>
        </table>

        <div style="padding: 16px; background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="38%" valign="top">
                        <div style="font-size: 11px; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; color: #94a3b8;">Origen</div>
                        <div style="font-size: 28px; font-weight: 800; line-height: 1.15; color: #0f172a;">{{ $flight->origin }}</div>
                        @if($nombreOrigen)<div style="font-size: 12px; color: #64748b;">{{ $nombreOrigen }}</div>@endif
                    </td>
                    <td width="24%" align="center" valign="middle" style="color: #1d4ed8;">
                        <div style="font-size: 18px;">✈</div>
                        <div style="width: 48px; height: 2px; margin: 4px auto; background-color: #bfdbfe;"></div>
                        @if($duracion)<div style="font-size: 11px; font-weight: 600; color: #64748b;">{{ intdiv($duracion, 60) }}h {{ str_pad($duracion % 60, 2, '0', STR_PAD_LEFT) }}m</div>@endif
                    </td>
                    <td width="38%" align="right" valign="top">
                        <div style="font-size: 11px; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; color: #94a3b8;">Destino</div>
                        <div style="font-size: 28px; font-weight: 800; line-height: 1.15; color: #0f172a;">{{ $flight->destination }}</div>
                        @if($nombreDestino)<div style="font-size: 12px; color: #64748b;">{{ $nombreDestino }}</div>@endif
                    </td>
                </tr>
            </table>

            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top: 14px; padding-top: 14px; border-top: 1px solid #f1f5f9;">
                <tr>
                    <td width="50%" valign="top" style="padding-right: 6px;">
                        <div style="padding: 10px 12px; background-color: #f8fafc; border: 1px solid #f1f5f9; border-radius: 10px;">
                            <div style="font-size: 11px; font-weight: 600; text-transform: uppercase; color: #94a3b8;">Fecha</div>
                            <div style="font-size: 14px; font-weight: 700; color: #1e293b;">{{ $flight->departure_date->format('d/m/Y') }}</div>
                        </div>
                    </td>
                    <td width="50%" valign="top" style="padding-left: 6px;">
                        <div style="padding: 10px 12px; background-color: #f8fafc; border: 1px solid #f1f5f9; border-radius: 10px;">
                            <div style="font-size: 11px; font-weight: 600; text-transform: uppercase; color: #94a3b8;">Aerolínea</div>
                            <div style="font-size: 14px; font-weight: 700; color: #1e293b;">{{ $flight->airline }}</div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Cambio de precio --}}
    <div class="note {{ $sube ? 'note--amber' : 'note--green' }}" style="padding: 18px 20px;">
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 12px;">
            <tr>
                <td style="font-size: 15px; font-weight: 800; color: #0f172a;">
                    {{ $sube ? '¡Atención al cambio de precio!' : '¡Buenas noticias, el precio ha bajado!' }}
                </td>
                @if($porcentaje !== null)
                    <td align="right">
                        <span class="pill" style="{{ $sube ? 'color: #be123c; background-color: #ffe4e6; border: 1px solid #fecdd3;' : 'color: #047857; background-color: #d1fae5; border: 1px solid #a7f3d0;' }}">{{ $signo }}{{ number_format($changeAmount, 2) }} € ({{ $signo }}{{ $porcentaje }}%)</span>
                    </td>
                @endif
            </tr>
        </table>

        <div style="padding: 14px 16px; background-color: #ffffff; border: 1px solid {{ $sube ? '#fde68a' : '#a7f3d0' }}; border-radius: 12px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td valign="middle">
                        <div style="font-size: 11px; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; color: #94a3b8;">Tarifa previa</div>
                        <div style="font-size: 19px; font-weight: 700; color: #f43f5e; text-decoration: line-through;">{{ number_format($oldPrice, 2) }}&nbsp;€</div>
                    </td>
                    <td width="40" align="center" valign="middle" style="font-size: 20px; color: #94a3b8;">→</td>
                    <td valign="middle">
                        <div style="font-size: 11px; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; color: #1d4ed8;">Nueva tarifa</div>
                        <div style="font-size: 24px; font-weight: 800; color: #0f172a; white-space: nowrap;">{{ number_format($newPrice, 2) }}&nbsp;€</div>
                    </td>
                </tr>
            </table>
            <div style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #f1f5f9; font-size: 13px; color: #475569;">
                El precio ha {{ $changeType }} <strong style="color: {{ $sube ? '#e11d48' : '#047857' }};">{{ $signo }}{{ number_format($changeAmount, 2) }} €</strong>
            </div>
        </div>
    </div>

    <div style="text-align: center; padding: 6px 0 4px;">
        <a href="{{ route('favorites.details', $flight->id) }}" class="btn">Ver detalles del vuelo →</a>
        <p style="margin: 14px 0 0; font-size: 13px;">
            <a href="{{ route('home') }}" style="font-weight: 700; color: #1d4ed8;">Buscar otros vuelos</a>
        </p>
    </div>
@endsection

@section('legal')
    <p>Recibes este correo porque tienes activadas las alertas de precios para tus vuelos favoritos.</p>
@endsection
