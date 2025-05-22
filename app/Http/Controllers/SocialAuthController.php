<?php

namespace App\Http\Controllers;

use App\Mail\UserRegisteredMail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Mail;

class SocialAuthController extends Controller
{
    protected $providers = ['google', 'github'];

    public function redirectToProvider($provider)
    {
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

            $user = User::where('provider_id', $socialUser->getId())
                ->where('provider', $provider)
                ->first();

            if (!$user) {
                $user = User::where('email', $socialUser->getEmail())->first();

                if ($user) {
                    $user->update([
                        'provider' => $provider,
                        'provider_id' => $socialUser->getId(),
                        'avatar' => $socialUser->getAvatar(),
                    ]);
                } else {
                    $user = User::create([
                        'name' => $socialUser->getName(),
                        'email' => $socialUser->getEmail(),
                        'password' => Hash::make(Str::random(24)),
                        'provider' => $provider,
                        'provider_id' => $socialUser->getId(),
                        'avatar' => $socialUser->getAvatar(),
                    ]);
                    
                    event(new Registered($user));
                    
                    Mail::to($user->email)->send(new UserRegisteredMail($user));
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