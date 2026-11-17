@extends('layouts.app')

@section('content')
    <style>
        /* 1. Ocultar el navbar superior de layouts/app */
        nav.navbar,
        .navbar,
        header {
            display: none !important;
        }

        /* 2. Evitar cualquier scroll horizontal en el body */
        html,
        body {
            overflow-x: hidden !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* 3. Fondo de pantalla completa inmune a los contenedores de layouts/app */
        .medical-login-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100vw;
            height: 100vh;
            z-index: 99999;
            overflow-y: auto;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            background:
                radial-gradient(circle at 10% 15%, rgba(2, 132, 199, 0.16) 0%, transparent 45%),
                radial-gradient(circle at 90% 85%, rgba(10, 46, 92, 0.12) 0%, transparent 45%),
                radial-gradient(circle at 50% 50%, rgba(0, 168, 150, 0.05) 0%, transparent 50%),
                #eef4f8;
            padding: 2rem 1rem;
            box-sizing: border-box;
        }

        /* Contenedor centrado para la tarjeta */
        .medical-login-center {
            width: 100%;
            max-width: 450px;
            margin: auto;
        }

        /* 4. Estilos de la tarjeta */
        .medical-card {
            background: #ffffff;
            border-radius: 1.25rem;
            border: 1px solid rgba(2, 132, 199, 0.15);
            box-shadow: 0 25px 50px -12px rgba(10, 46, 92, 0.12), 0 0 1px rgba(10, 46, 92, 0.2);
            overflow: hidden;
        }

        .brand-accent-bar {
            height: 5px;
            background: linear-gradient(90deg, #0a2e5c 0%, #0284c7 50%, #00a896 100%);
        }

        .medical-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background-color: #e0f2fe;
            color: #0369a1;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
            letter-spacing: 0.3px;
        }

        .pulse-dot {
            width: 7px;
            height: 7px;
            background-color: #10b981;
            border-radius: 50%;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.25);
        }

        /* Alerta de confirmación de envío de correo */
        .alert-medical-success {
            background-color: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-radius: 0.75rem;
            padding: 0.85rem 1rem;
            color: #065f46;
            font-size: 0.85rem;
        }

        .form-floating-custom {
            position: relative;
        }

        .form-floating-custom .input-icon {
            position: absolute;
            top: 50%;
            left: 14px;
            transform: translateY(-50%);
            color: #94a3b8;
            z-index: 4;
            transition: color 0.2s;
        }

        .form-floating-custom .form-control {
            padding-left: 2.75rem;
            padding-right: 1rem;
            height: 3.1rem;
            border-radius: 0.75rem;
            border: 1.5px solid #e2e8f0;
            font-size: 0.95rem;
            transition: all 0.2s ease-in-out;
        }

        .form-floating-custom .form-control:focus {
            border-color: #0284c7;
            box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.12);
        }

        .form-floating-custom .form-control:focus~.input-icon {
            color: #0284c7;
        }

        .btn-medical {
            background: linear-gradient(135deg, #0a2e5c 0%, #0284c7 100%);
            border: none;
            color: #ffffff;
            font-weight: 600;
            font-size: 0.95rem;
            letter-spacing: 0.2px;
            padding: 0.85rem;
            border-radius: 0.75rem;
            transition: all 0.25s ease;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
        }

        .btn-medical:hover {
            background: linear-gradient(135deg, #071f3d 0%, #0369a1 100%);
            color: #ffffff;
            box-shadow: 0 6px 18px rgba(2, 132, 199, 0.35);
            transform: translateY(-1px);
        }

        .medical-footer {
            border-top: 1px solid #f1f5f9;
            background-color: #fafbfc;
            padding: 1rem 1.5rem;
            font-size: 0.75rem;
            color: #64748b;
        }
    </style>

    <div class="medical-login-wrapper">
        <div class="medical-login-center">

            <div class="medical-card">
                <!-- Barra de acento tricolor -->
                <div class="brand-accent-bar"></div>

                <div class="p-4 p-sm-5 pb-4">
                    <!-- Cabecera / Identidad M&M -->
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle p-3 mb-2"
                            style="width: 64px; height: 64px; border: 2px solid #e0f2fe;">
                            <!-- Icono de Correo / Recuperación -->
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#0284c7"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="20" height="16" x="2" y="4" rx="2" />
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                            </svg>
                        </div>

                        <h4 class="fw-bold mb-1" style="color: #0a2e5c; letter-spacing: -0.3px;">
                            M&M <span style="color: #0284c7;">Servicios Médicos</span>
                        </h4>
                        <p class="text-muted small mb-2">Recuperación de Contraseña</p>

                        <div class="medical-badge mt-1">
                            <span class="pulse-dot"></span>
                            Portal Clínico Seguro
                        </div>
                    </div>

                    <!-- Mensaje de estado al enviar el correo -->
                    @if (session('status'))
                        <div class="alert-medical-success d-flex align-items-center gap-2 mb-4">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#059669"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                <polyline points="22 4 12 14.01 9 11.01" />
                            </svg>
                            <span>{{ session('status') }}</span>
                        </div>
                    @else
                        <p class="text-secondary small text-center mb-4" style="line-height: 1.4;">
                            Ingresa el correo asociado a tu cuenta médica y te enviaremos un enlace seguro para restablecer
                            tu clave.
                        </p>
                    @endif

                    <!-- Formulario -->
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <!-- Correo Electrónico -->
                        <div class="mb-4">
                            <label for="email" class="form-label text-secondary small fw-bold mb-1">
                                Correo Electrónico
                            </label>
                            <div class="form-floating-custom">
                                <span class="input-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <rect width="20" height="16" x="2" y="4" rx="2" />
                                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                                    </svg>
                                </span>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email" autofocus
                                    placeholder="ejemplo@mymserviciosmedicos.cl">
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1 d-flex align-items-center gap-1">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <circle cx="12" cy="12" r="10" />
                                        <line x1="12" y1="8" x2="12" y2="12" />
                                        <line x1="12" y1="16" x2="12.01" y2="16" />
                                    </svg>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <!-- Botón de Envío -->
                        <div class="d-grid mb-3">
                            <button type="submit"
                                class="btn btn-medical d-flex align-items-center justify-content-center gap-2">
                                <span>Enviar Enlace de Recuperación</span>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m22 2-7 20-4-9-9-4Z" />
                                    <path d="M22 2 11 13" />
                                </svg>
                            </button>
                        </div>

                        <!-- Enlace para volver al login -->
                        @if (Route::has('login'))
                            <div class="text-center">
                                <a href="{{ route('login') }}"
                                    class="text-decoration-none small text-muted d-inline-flex align-items-center gap-1 hover-primary"
                                    style="font-size: 0.85rem;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="m15 18-6-6 6-6" />
                                    </svg>
                                    Volver al inicio de sesión
                                </a>
                            </div>
                        @endif
                    </form>
                </div>

                <!-- Pie de tarjeta médico -->
                <div class="medical-footer text-center">
                    <div class="d-flex align-items-center justify-content-center gap-1 mb-1">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#00a896"
                            stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10" />
                        </svg>
                        <span class="fw-semibold">Acceso protegido y confidencial</span>
                    </div>
                    <span class="text-muted" style="font-size: 0.72rem;">
                        ¿No recuerdas tu correo? Contacta a <a href="mailto:soporte@mymserviciosmedicos.cl"
                            class="text-decoration-none" style="color: #0284c7;">soporte@mymserviciosmedicos.cl</a>
                    </span>
                </div>

            </div>

            <!-- Copyright inferior -->
            <div class="text-center mt-3">
                <small class="text-muted" style="font-size: 0.75rem;">
                    &copy; {{ date('Y') }} M&M Servicios Médicos Ltda. Todos los derechos reservados.
                </small>
            </div>

        </div>
    </div>
@endsection
