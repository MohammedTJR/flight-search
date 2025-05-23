<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PreferencesController extends Controller
{
    public function update(Request $request)
    {
        $user = auth()->user();

        // Validar los datos
        $validated = $request->validate([
            'preferred_class' => 'required|in:economy,premium_economy,business,first',
            'direct_flights' => 'nullable|boolean',
            'price_alerts' => 'nullable|boolean',
            'alert_frequency' => 'required_if:price_alerts,1|in:immediate,daily,weekly',
            'analytics_cookies' => 'nullable|boolean',
            'marketing_cookies' => 'nullable|boolean',
            'enable_chat' => 'nullable|boolean',
            'chat_notifications' => 'nullable|boolean',
        ]);

        // Actualizar preferencias de viaje
        $user->travel_preferences = array_merge($user->travel_preferences ?? [], [
            'preferred_class' => $validated['preferred_class'],
            'direct_flights' => $request->boolean('direct_flights'),
        ]);

        // Actualizar preferencias de notificaciones
        $user->notification_preferences = array_merge($user->notification_preferences ?? [], [
            'price_alerts' => $request->boolean('price_alerts'),
            'alert_frequency' => $validated['alert_frequency'],
            'analytics_cookies' => $request->boolean('analytics_cookies'),
            'marketing_cookies' => $request->boolean('marketing_cookies'),
            'enable_chat' => $request->boolean('enable_chat'),
            'chat_notifications' => $request->boolean('chat_notifications'),
        ]);

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Preferencias actualizadas correctamente'
        ]);
    }
}
