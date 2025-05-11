@extends('plantilla')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('titulo_pagina', 'Editar Perfil - FlyLow')

@section('contenido')
    <div class="profile-edit-container bg-light py-5 fade-in">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="display-6 fw-bold text-primary">Editar Perfil</h1>
                        <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary btn-profile">
                            <i class="fas fa-arrow-left me-2"></i> Volver
                        </a>
                    </div>

                    <div class="card shadow-sm border-0 overflow-hidden">
                        <div class="card-body p-0">
                            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row g-0">
                                    <!-- Columna de avatar -->
                                    <div class="col-md-4 bg-light p-4 border-end">
                                        <div class="text-center mb-4">
                                            <div class="profile-avatar-edit mb-3 mx-auto">
                                                <img src="{{ $user->avatar ?? asset('img/default-avatar.png') }}" 
                                                    alt="Foto perfil" id="avatar-preview" 
                                                    class="img-fluid rounded-circle shadow border border-4 border-white">
                                            </div>
                                            <div class="d-grid">
                                                <input type="file" class="form-control" id="avatar" name="avatar" 
                                                    accept="image/*" onchange="previewImage(this)">
                                                <small class="text-muted mt-2">Formatos: JPEG, PNG, JPG (Max 2MB)</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Columna de formulario -->
                                    <div class="col-md-8 p-4">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="name" class="form-label">Nombre completo</label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                    id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                    id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="gender" class="form-label">Género</label>
                                                <select class="form-select @error('gender') is-invalid @enderror" id="gender"
                                                    name="gender">
                                                    <option value="">Seleccionar...</option>
                                                    <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Masculino</option>
                                                    <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Femenino</option>
                                                    <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Otro</option>
                                                    <option value="prefer_not_to_say" {{ old('gender', $user->gender) == 'prefer_not_to_say' ? 'selected' : '' }}>Prefiero no decir</option>
                                                </select>
                                                @error('gender')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="birth_date" class="form-label">Fecha de nacimiento</label>
                                                <input type="date"
                                                    class="form-control @error('birth_date') is-invalid @enderror"
                                                    id="birth_date" name="birth_date"
                                                    value="{{ old('birth_date', optional($user->birth_date)->format('Y-m-d')) }}">
                                                @error('birth_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
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

                                            <div class="col-md-6">
                                                <label for="phone" class="form-label">Teléfono</label>
                                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                                    id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                                @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="currency" class="form-label">Moneda preferida</label>
                                                <select class="form-select @error('currency') is-invalid @enderror"
                                                    id="currency" name="currency">
                                                    <option value="">Seleccionar moneda...</option>
                                                    <option value="EUR" {{ old('currency', $user->currency) == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                                                    <option value="USD" {{ old('currency', $user->currency) == 'USD' ? 'selected' : '' }}>Dólar ($)</option>
                                                    <option value="GBP" {{ old('currency', $user->currency) == 'GBP' ? 'selected' : '' }}>Libra (£)</option>
                                                </select>
                                                @error('currency')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="language" class="form-label">Idioma preferido</label>
                                                <select class="form-select @error('language') is-invalid @enderror"
                                                    id="language" name="language">
                                                    <option value="">Seleccionar idioma...</option>
                                                    <option value="es" {{ old('language', $user->language) == 'es' ? 'selected' : '' }}>Español</option>
                                                    <option value="en" {{ old('language', $user->language) == 'en' ? 'selected' : '' }}>Inglés</option>
                                                    <option value="fr" {{ old('language', $user->language) == 'fr' ? 'selected' : '' }}>Francés</option>
                                                </select>
                                                @error('language')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-12">
                                                <label for="address" class="form-label">Dirección</label>
                                                <input type="text" class="form-control @error('address') is-invalid @enderror"
                                                    id="address" name="address" value="{{ old('address', $user->address) }}">
                                                @error('address')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-12 mt-4">
                                                <button type="submit" class="btn btn-primary btn-lg px-4 btn-profile">
                                                    <i class="fas fa-save me-2"></i>Guardar Cambios
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Sección de cambio de contraseña -->
                    <div class="card shadow-sm border-0 mt-4">
                        <div class="card-body p-4">
                            <h5 class="mb-4 text-primary"><i class="fas fa-lock me-2 profile-icon-hover"></i>Cambiar Contraseña</h5>
                            <form method="POST" action="{{ route('profile.update.password') }}">
                                @csrf
                                @method('PUT')

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="current_password" class="form-label">Contraseña Actual</label>
                                        <div class="input-group">
                                            <input type="password"
                                                class="form-control @error('current_password') is-invalid @enderror"
                                                id="current_password" name="current_password" required>
                                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @error('current_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="password" class="form-label">Nueva Contraseña</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                                id="password" name="password" required>
                                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password_confirmation"
                                                name="password_confirmation" required>
                                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-12 mt-2">
                                        <button type="submit" class="btn btn-outline-primary btn-profile">
                                            <i class="fas fa-lock me-2"></i>Cambiar Contraseña
                                        </button>
                                    </div>
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

        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.previousElementSibling;
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
            });
        });
    </script>
@endsection