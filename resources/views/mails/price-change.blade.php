@extends('mails.layout')

@php
    $sube = $changeType === 'aumentado';
@endphp

@section('title', 'Cambio de precio')
@section('badge', 'Alerta de precio')
@section('heading', '¡El precio de tu vuelo ha cambiado!')
@section('subtitle', $flight->origin . ' → ' . $flight->destination)

@section('content')
    <div class="panel" style="margin-top: 0;">
        <p class="panel-title">Detalles del vuelo</p>
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
            <tr class="row">
                <td class="k">Origen</td>
                <td class="v">{{ $flight->origin }}</td>
            </tr>
            <tr class="row">
                <td class="k">Destino</td>
                <td class="v">{{ $flight->destination }}</td>
            </tr>
            <tr class="row">
                <td class="k">Fecha</td>
                <td class="v">{{ $flight->departure_date->format('d/m/Y') }}</td>
            </tr>
            <tr class="row">
                <td class="k">Aerolínea</td>
                <td class="v">{{ $flight->airline }}</td>
            </tr>
        </table>
    </div>

    <div class="note {{ $sube ? 'note--red' : 'note--green' }}" style="text-align: center;">
        <div style="font-size: 12px; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;">
            {{ $sube ? 'El precio ha subido' : 'El precio ha bajado' }}
        </div>
        <div style="margin: 6px 0;">
            <span style="font-size: 16px; color: #94a3b8; text-decoration: line-through;">{{ number_format($oldPrice, 2) }}€</span>
            &nbsp;→&nbsp;
            <span style="font-size: 26px; font-weight: 800;">{{ number_format($newPrice, 2) }}€</span>
        </div>
        <div style="font-size: 13px;">
            El precio ha {{ $changeType }} {{ number_format($changeAmount, 2) }}€
        </div>
    </div>

    <div style="text-align: center; padding: 6px 0 10px;">
        <a href="{{ route('favorites.details', $flight->id) }}" class="btn">Ver detalles del vuelo</a>
    </div>
@endsection

@section('legal')
    <p>Recibes este correo porque tienes activadas las alertas de precios para tus vuelos favoritos.</p>
@endsection
