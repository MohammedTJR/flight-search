<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    protected $providers = ['google', 'github'];

    public function redirectToProvider($provider)
    {
        // Verificar si el proveedor es válido
        if (!in_array($provider, $this->providers)) {
            return redirect()->route('login')
                ->with('error', 'Proveedor de autenticación no soportado.');
        }

        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();

            // Buscar usuario por provider_id
            $user = User::where('provider_id', $socialUser->getId())
                ->where('provider', $provider)
                ->first();

            // Si no existe, buscar por email
            if (!$user) {
                $user = User::where('email', $socialUser->getEmail())->first();

                // Si existe un usuario con ese email, actualizamos sus datos de provider
                if ($user) {
                    $user->update([
                        'provider' => $provider,
                        'provider_id' => $socialUser->getId(),
                        'avatar' => $socialUser->getAvatar(),
                    ]);
                } else {
                    // Si no existe, crear un nuevo usuario
                    $user = User::create([
                        'name' => $socialUser->getName(),
                        'email' => $socialUser->getEmail(),
                        'password' => Hash::make(Str::random(24)),
                        'provider' => $provider,
                        'provider_id' => $socialUser->getId(),
                        'avatar' => $socialUser->getAvatar(),
                    ]);
                }
            }

            // Iniciar sesión
            Auth::login($user, true);

            return redirect()->intended(route('home'))
                ->with('success', '¡Bienvenido/a ' . $user->name . '!');

        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Error al autenticar con ' . ucfirst($provider) . '. Por favor intenta de nuevo.');
        }
    }
}