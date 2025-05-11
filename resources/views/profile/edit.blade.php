@extends('plantilla')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('titulo_pagina', 'Editar Perfil - FlyLow')

@section('contenido')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Editar Perfil</h3>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <div class="profile-avatar mb-3">
                                        <img src="{{ $user->avatar ?? asset('img/default-avatar.png') }}" alt="Foto perfil"
                                            id="avatar-preview" class="img-fluid">
                                    </div>
                                    <div class="mb-3">
                                        <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*"
                                            onchange="previewImage(this)">
                                        <small class="text-muted">Formatos: JPEG, PNG, JPG (Max 2MB)</small>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="name" class="form-label">Nombre completo</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="gender" class="form-label">Género</label>
                                            <select class="form-select @error('gender') is-invalid @enderror" id="gender"
                                                name="gender">
                                                <option value="">Seleccionar...</option>
                                                <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Masculino</option>
                                                <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Femenino</option>
                                                <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Otro</option>
                                                <option value="prefer_not_to_say" {{ old('gender', $user->gender) == 'prefer_not_to_say' ? 'selected' : '' }}>Prefiero no
                                                    decir</option>
                                            </select>
                                            @error('gender')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="birth_date" class="form-label">Fecha de nacimiento</label>
                                            <input type="date"
                                                class="form-control @error('birth_date') is-invalid @enderror"
                                                id="birth_date" name="birth_date"
                                                value="{{ old('birth_date', optional($user->birth_date)->format('Y-m-d')) }}">
                                            @error('birth_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="country" class="form-label">País</label>
                                            <select class="form-select @error('country') is-invalid @enderror" id="country"
                                                name="country">
                                                <option value="">Seleccionar país...</option>
                                                @foreach(config('countries') as $code => $name)
                                                    <option value="{{ $code }}" {{ old('country', $user->country) == $code ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('country')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="phone" class="form-label">Teléfono</label>
                                            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                                id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="currency" class="form-label">Moneda preferida</label>
                                            <select class="form-select @error('currency') is-invalid @enderror"
                                                id="currency" name="currency">
                                                <option value="">Seleccionar moneda...</option>
                                                <option value="EUR" {{ old('currency', $user->currency) == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                                                <option value="USD" {{ old('currency', $user->currency) == 'USD' ? 'selected' : '' }}>Dólar ($)</option>
                                                <option value="GBP" {{ old('currency', $user->currency) == 'GBP' ? 'selected' : '' }}>Libra (£)</option>
                                                <!-- Agrega más opciones según necesites -->
                                            </select>
                                            @error('currency')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="language" class="form-label">Idioma preferido</label>
                                            <select class="form-select @error('language') is-invalid @enderror"
                                                id="language" name="language">
                                                <option value="">Seleccionar idioma...</option>
                                                <option value="es" {{ old('language', $user->language) == 'es' ? 'selected' : '' }}>Español</option>
                                                <option value="en" {{ old('language', $user->language) == 'en' ? 'selected' : '' }}>Inglés</option>
                                                <option value="fr" {{ old('language', $user->language) == 'fr' ? 'selected' : '' }}>Francés</option>
                                                <!-- Agrega más opciones según necesites -->
                                            </select>
                                            @error('language')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="address" class="form-label">Dirección</label>
                                        <input type="text" class="form-control @error('address') is-invalid @enderror"
                                            id="address" name="address" value="{{ old('address', $user->address) }}">
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Guardar Cambios
                                </button>
                            </div>
                        </form>

                        <hr class="my-4">

                        <div id="change-password">
                            <h5 class="mb-3">Cambiar Contraseña</h5>
                            <form method="POST" action="{{ route('profile.update.password') }}">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="current_password" class="form-label">Contraseña Actual</label>
                                        <input type="password"
                                            class="form-control @error('current_password') is-invalid @enderror"
                                            id="current_password" name="current_password" required>
                                        @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">Nueva Contraseña</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            id="password" name="password" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="password_confirmation" class="form-label">Confirmar Nueva
                                            Contraseña</label>
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation" required>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-outline-primary">
                                        <i class="fas fa-lock me-2"></i>Cambiar Contraseña
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('avatar-preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection