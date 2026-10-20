@extends('plantilla')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}?v={{ filemtime(public_path('css/profile.css')) }}">
@endsection

@section('titulo_pagina', 'Editar Perfil - FlyLow')

@section('contenido')
    <div class="pf-page">
        <div class="pf-wrap pf-edit">
            <header class="pf-edit-head">
                <div>
                    <p class="pf-crumbs"><a href="{{ route('profile.show') }}">Mi cuenta</a> / <b>Ajustes</b></p>
                    <h1>Editar Perfil</h1>
                    <p>Gestiona tu información personal, tus preferencias y la seguridad de tu cuenta.</p>
                </div>
                <a href="{{ route('profile.show') }}" class="pf-btn"><i class="fas fa-arrow-left"></i>Volver al perfil</a>
            </header>

            {{-- Datos personales --}}
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="pf-panel">
                @csrf
                @method('PUT')

                <div class="pf-edit-grid">
                    <aside class="pf-edit-avatar">
                        <div class="pf-avatar-lg">
                            <img src="{{ $user->avatar ?? asset('img/default-avatar.png') }}" alt="Foto de perfil" id="avatar-preview">
                        </div>
                        <label class="pf-file" for="avatar">
                            <i class="fas fa-image"></i><span id="avatar-name">Seleccionar archivo</span>
                        </label>
                        <input type="file" id="avatar" name="avatar" accept="image/*" class="pf-file-input">
                        <small>Formatos: JPEG, PNG, JPG, GIF (máx. 2 MB)</small>
                        @error('avatar')
                            <span class="pf-error">{{ $message }}</span>
                        @enderror
                    </aside>

                    <div class="pf-edit-form">
                        <h2>Información personal</h2>

                        <div class="pf-fields">
                            <div class="pf-field">
                                <label for="name">Nombre completo</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                                    class="pf-input @error('name') is-invalid @enderror">
                                @error('name')<span class="pf-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="pf-field">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                                    class="pf-input @error('email') is-invalid @enderror">
                                @error('email')<span class="pf-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="pf-field">
                                <label for="gender">Género</label>
                                <select id="gender" name="gender" class="pf-input @error('gender') is-invalid @enderror">
                                    <option value="">Seleccionar...</option>
                                    <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Masculino</option>
                                    <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Femenino</option>
                                    <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Otro</option>
                                    <option value="prefer_not_to_say" {{ old('gender', $user->gender) == 'prefer_not_to_say' ? 'selected' : '' }}>Prefiero no decir</option>
                                </select>
                                @error('gender')<span class="pf-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="pf-field">
                                <label for="birth_date">Fecha de nacimiento</label>
                                <input type="date" id="birth_date" name="birth_date"
                                    value="{{ old('birth_date', optional($user->birth_date)->format('Y-m-d')) }}"
                                    class="pf-input @error('birth_date') is-invalid @enderror">
                                @error('birth_date')<span class="pf-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="pf-field">
                                <label for="country">País</label>
                                <select id="country" name="country" class="pf-input @error('country') is-invalid @enderror">
                                    <option value="">Seleccionar país...</option>
                                    @foreach(config('countries') as $code => $name)
                                        <option value="{{ $code }}" {{ old('country', $user->country) == $code ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('country')<span class="pf-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="pf-field">
                                <label for="phone">Teléfono</label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                                    class="pf-input @error('phone') is-invalid @enderror">
                                @error('phone')<span class="pf-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="pf-field">
                                <label for="currency">Moneda preferida</label>
                                <select id="currency" name="currency" class="pf-input @error('currency') is-invalid @enderror">
                                    <option value="">Seleccionar moneda...</option>
                                    <option value="EUR" {{ old('currency', $user->currency) == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                                    <option value="USD" {{ old('currency', $user->currency) == 'USD' ? 'selected' : '' }}>Dólar ($)</option>
                                    <option value="GBP" {{ old('currency', $user->currency) == 'GBP' ? 'selected' : '' }}>Libra (£)</option>
                                </select>
                                @error('currency')<span class="pf-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="pf-field">
                                <label for="language">Idioma preferido</label>
                                <select id="language" name="language" class="pf-input @error('language') is-invalid @enderror">
                                    <option value="">Seleccionar idioma...</option>
                                    <option value="es" {{ old('language', $user->language) == 'es' ? 'selected' : '' }}>Español</option>
                                    <option value="en" {{ old('language', $user->language) == 'en' ? 'selected' : '' }}>Inglés</option>
                                    <option value="fr" {{ old('language', $user->language) == 'fr' ? 'selected' : '' }}>Francés</option>
                                </select>
                                @error('language')<span class="pf-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="pf-field pf-field--full">
                                <label for="address">Dirección</label>
                                <input type="text" id="address" name="address" value="{{ old('address', $user->address) }}"
                                    class="pf-input @error('address') is-invalid @enderror">
                                @error('address')<span class="pf-error">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <button type="submit" class="pf-btn pf-btn--primary pf-btn--lg"><i class="fas fa-floppy-disk"></i>Guardar cambios</button>
                    </div>
                </div>
            </form>

            {{-- Contraseña --}}
            <form method="POST" action="{{ route('profile.update.password') }}" class="pf-panel pf-password" id="passwordForm">
                @csrf
                @method('PUT')

                <header class="pf-password-head">
                    <span class="ico"><i class="fas fa-lock"></i></span>
                    <div>
                        <h2>Seguridad y cambio de contraseña</h2>
                        <p>Protege tu cuenta con una contraseña segura.</p>
                    </div>
                </header>

                <div class="pf-fields pf-fields--3">
                    <div class="pf-field">
                        <label for="current_password">Contraseña actual</label>
                        <div class="pf-pass">
                            <input type="password" id="current_password" name="current_password" required
                                class="pf-input @error('current_password') is-invalid @enderror">
                            <button type="button" class="toggle-password" aria-label="Mostrar u ocultar contraseña"><i class="far fa-eye"></i></button>
                        </div>
                        @error('current_password')<span class="pf-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="pf-field">
                        <label for="password">Nueva contraseña</label>
                        <div class="pf-pass">
                            <input type="password" id="password" name="password" required minlength="8"
                                class="pf-input @error('password') is-invalid @enderror">
                            <button type="button" class="toggle-password" aria-label="Mostrar u ocultar contraseña"><i class="far fa-eye"></i></button>
                        </div>
                        <div class="pf-strength" id="strength-bars" aria-hidden="true"><i></i><i></i><i></i><i></i></div>
                        <small class="pf-hint" id="strength-label">Mínimo 8 caracteres</small>
                        @error('password')<span class="pf-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="pf-field">
                        <label for="password_confirmation">Confirmar nueva contraseña</label>
                        <div class="pf-pass">
                            <input type="password" id="password_confirmation" name="password_confirmation" required class="pf-input">
                            <button type="button" class="toggle-password" aria-label="Mostrar u ocultar contraseña"><i class="far fa-eye"></i></button>
                        </div>
                        <small class="pf-hint" id="match-feedback" hidden></small>
                    </div>
                </div>

                <button type="submit" class="pf-btn pf-btn--soft"><i class="fas fa-key"></i>Cambiar contraseña</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/profile.js') }}?v={{ filemtime(public_path('js/profile.js')) }}"></script>
@endsection
