@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-lg border-0 rounded-3">
                    <!-- Encabezado Estilizado -->
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h5 class="mb-0 fw-bold">Acceso al Portal Medico</h5>
                        <small class="text-white-50">Sistema de Trazabilidad Patologica</small>
                    </div>

                    <div class="card-body p-4 bg-white">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Correo Electrónico -->
                            <div class="mb-3">
                                <label for="email" class="form-label text-muted small fw-bold">CORREO ELECTRÓNICO</label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email" autofocus
                                    placeholder="nombre@ejemplo.com">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Contraseña -->
                            <div class="mb-3">
                                <label for="password" class="form-label text-muted small fw-bold">CONTRASEÑA</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required
                                    autocomplete="current-password" placeholder="••••••••">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Recordarme -->
                            <div class="mb-3 form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label text-secondary small" for="remember">
                                    Recordar mi sesión
                                </label>
                            </div>

                            <!-- Botón de Ingreso -->
                            <div class="d-grid gap-2 mb-3">
                                <button type="submit" class="btn btn-primary btn-lg fw-bold fs-6">
                                    Iniciar Sesion
                                </button>
                            </div>

                            <!-- Olvidé mi contraseña -->
                            @if (Route::has('password.request'))
                                <div class="text-center">
                                    <a class="btn btn-link text-decoration-none small text-muted"
                                        href="{{ route('password.request') }}">
                                        ¿Olvidaste tu contraseña?
                                    </a>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
