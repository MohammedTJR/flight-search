<?php

namespace App\Http\Controllers;

use App\Mail\UserRegisteredMail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Mail;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Mail::to($user->email)->send(new UserRegisteredMail($user));

        return redirect()->route('verification.notice')
            ->with('success', '¡Registro exitoso! Por favor, verifica tu correo electrónico.');
    }
}
