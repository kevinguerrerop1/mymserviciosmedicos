<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('img/logo-mym.png') }}">
    <link rel="shortcut icon" href="{{ asset('img/logo-mym.png') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('img/logo-mym.png') }}">

    <title>{{ config('app.name', 'M&M') }} - Trazabilidad Anatomía Patológica</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        :root {
            --mym-navy: #0a2e5c;
            --mym-blue: #0f4c81;
            --mym-cyan: #0284c7;
            --mym-teal: #00a896;
            --mym-light-blue: #f0f7ff;
            --mym-border: #e2e8f0;
            --mym-bg: #f8fafc;
        }

        body {
            background-color: var(--mym-bg) !important;
            font-family: 'Nunito', system-ui, -apple-system, sans-serif;
            color: #334155;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        #app {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* ==========================================================
           NAVBAR CORPORATIVO M&M
           ========================================================== */
        .medical-navbar {
            background: #ffffff !important;
            border-bottom: 1px solid var(--mym-border);
            box-shadow: 0 4px 20px -5px rgba(10, 46, 92, 0.06);
            position: relative;
        }

        /* Línea de acento tricolor superior */
        .medical-navbar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #0a2e5c 0%, #0284c7 50%, #00a896 100%);
        }

        .navbar-brand-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .navbar-brand-logo {
            height: 38px;
            width: auto;
            object-fit: contain;
        }

        .navbar-brand-title {
            color: var(--mym-navy);
            font-weight: 800;
            font-size: 1.05rem;
            line-height: 1.1;
            letter-spacing: -0.3px;
        }

        .navbar-brand-subtitle {
            font-size: 0.65rem;
            color: #64748b;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* Enlaces de Navegación */
        .navbar-nav .nav-link {
            color: #475569 !important;
            font-weight: 600;
            font-size: 0.88rem;
            padding: 0.5rem 0.9rem !important;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            transition: all 0.2s ease;
            margin: 0 2px;
        }

        .navbar-nav .nav-link i {
            font-size: 1rem;
            color: #64748b;
            transition: color 0.2s;
        }

        .navbar-nav .nav-link:hover {
            color: var(--mym-cyan) !important;
            background-color: var(--mym-light-blue);
        }

        .navbar-nav .nav-link:hover i {
            color: var(--mym-cyan);
        }

        /* Enlace activo */
        .navbar-nav .nav-link.active,
        .navbar-nav .show>.nav-link {
            color: var(--mym-cyan) !important;
            background-color: #e0f2fe;
            font-weight: 700;
        }

        .navbar-nav .nav-link.active i,
        .navbar-nav .show>.nav-link i {
            color: var(--mym-cyan);
        }

        /* Botón de Perfil de Usuario */
        .user-profile-btn {
            background-color: #ffffff;
            border: 1px solid var(--mym-border);
            border-radius: 30px;
            padding: 4px 12px 4px 6px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .user-profile-btn:hover,
        .show>.user-profile-btn {
            border-color: #bae6fd;
            background-color: #f8fafc;
            box-shadow: 0 2px 8px rgba(2, 132, 199, 0.1);
        }

        .user-avatar-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0a2e5c 0%, #0284c7 100%);
            color: #ffffff;
            font-weight: 700;
            font-size: 0.82rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Menús Desplegables */
        .dropdown-menu {
            border-radius: 12px;
            border: 1px solid var(--mym-border);
            box-shadow: 0 10px 25px -5px rgba(10, 46, 92, 0.1);
            padding: 6px;
            margin-top: 8px !important;
        }

        .dropdown-item {
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #475569;
            padding: 8px 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.15s;
        }

        .dropdown-item:hover {
            background-color: var(--mym-light-blue);
            color: var(--mym-navy);
        }

        .dropdown-item.text-danger:hover {
            background-color: #fef2f2;
            color: #dc2626 !important;
        }

        /* ==========================================================
           TABLAS, CARDS Y BADGES (Mantenedores / Vistas)
           ========================================================== */
        .card {
            border: 1px solid var(--mym-border) !important;
            border-radius: 12px !important;
            box-shadow: 0 4px 16px rgba(10, 46, 92, 0.04) !important;
            overflow: hidden;
            background-color: #ffffff;
        }

        .card-header {
            background-color: var(--mym-navy) !important;
            color: #ffffff !important;
            font-weight: 700;
            padding: 0.9rem 1.25rem;
            border-bottom: none !important;
        }

        .table thead th {
            background-color: #f1f5f9 !important;
            color: #475569;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            border-bottom: 2px solid var(--mym-border) !important;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0a2e5c 0%, #0284c7 100%) !important;
            border: none !important;
            border-radius: 8px;
            font-weight: 600;
            padding: 0.5rem 1rem;
            box-shadow: 0 2px 6px rgba(2, 132, 199, 0.2);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #071f3d 0%, #0369a1 100%) !important;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);
        }

        .badge-estado {
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.75rem;
            display: inline-block;
        }

        .estado-pendiente {
            background-color: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .estado-espera {
            background-color: #f0f9ff;
            color: #0284c7;
            border: 1px solid #bae6fd;
        }

        .estado-critico {
            background-color: #fff1f2;
            color: #be123c;
            border: 1px solid #fecdd3;
            animation: pulse-critico 2s infinite;
        }

        .estado-informado {
            background-color: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        @keyframes pulse-critico {
            0% {
                box-shadow: 0 0 0 0 rgba(190, 18, 60, 0.4);
            }

            70% {
                box-shadow: 0 0 0 6px rgba(190, 18, 60, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(190, 18, 60, 0);
            }
        }
    </style>
</head>

<body>
    <div id="app">

        {{-- 
            IMPORTANTE: El Navbar SOLO se renderiza si el usuario tiene una sesión iniciada.
            En Login y Recuperación de Contraseña no se carga, permitiendo que el fondo 
            cubra el 100% de la ventana sin interferencias.
        --}}
        @auth
            <nav class="navbar navbar-expand-lg navbar-light medical-navbar sticky-top py-2">
                <div class="container-fluid px-lg-5">

                    <!-- Brand con Logo de M&M -->
                    <a class="navbar-brand-wrapper" href="{{ url('/') }}">
                        <img src="{{ asset('img/logo-mym.png') }}" alt="M&M Servicios Médicos" class="navbar-brand-logo"
                            onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=MM&background=0a2e5c&color=fff&rounded=true';">
                        <div class="d-flex flex-column">
                            <span class="navbar-brand-title">M&M <span style="color: var(--mym-cyan);">Servicios
                                    Médicos</span></span>
                            <span class="navbar-brand-subtitle">Trazabilidad Patológica</span>
                        </div>
                    </a>

                    <!-- Toggle Móvil -->
                    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Navegación">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">

                        <!-- Menú Izquierdo (Módulos Clínicos) -->
                        <ul class="navbar-nav me-auto ms-lg-4 align-items-lg-center">

                            <!-- Búsqueda de Exámenes -->
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('examenes.*') ? 'active' : '' }}"
                                    href="{{ route('examenes.index') }}">
                                    <i class="bi bi-clipboard2-pulse"></i> Búsqueda Exámenes
                                </a>
                            </li>

                            @hasrole('admin')
                                <!-- Reportes -->
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('reportes.*') ? 'active' : '' }}"
                                        href="{{ route('reportes.index') }}">
                                        <i class="bi bi-bar-chart-line"></i> Reportes
                                    </a>
                                </li>

                                <!-- Dropdown Mantenedores de Catálogo -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle {{ request()->routeIs(['usuarios.*', 'laboratorios.*', 'tipos.*', 'medicos.*', 'herramientas.*']) ? 'active' : '' }}"
                                        href="#" id="mantenedoresDropdown" role="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="bi bi-sliders"></i> Mantenedores
                                    </a>
                                    <ul class="dropdown-menu shadow-sm" aria-labelledby="mantenedoresDropdown">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('usuarios.index') }}">
                                                <i class="bi bi-people text-muted"></i> Usuarios
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('laboratorios.index') }}">
                                                <i class="bi bi-building text-muted"></i> Laboratorios
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('tipos.index') }}">
                                                <i class="bi bi-virus text-muted"></i> Tipos de Examen
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider my-1">
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('medicos.index') }}">
                                                <i class="bi bi-person-badge text-muted"></i> Médicos
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider my-1">
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('herramientas.firma') }}">
                                                <i class="bi bi-pen text-muted"></i> Generador de Firma
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endhasrole
                        </ul>

                        <!-- Menú Derecho (Perfil de Usuario y Sesión) -->
                        <ul class="navbar-nav ms-auto align-items-lg-center">
                            @php
                                $rolNombre = method_exists(Auth::user(), 'getRoleNames')
                                    ? Auth::user()->getRoleNames()->first()
                                    : Auth::user()->role ?? (Auth::user()->roles->first()->name ?? null);
                            @endphp

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link p-0 text-decoration-none dropdown-toggle"
                                    href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">

                                    <div class="user-profile-btn">
                                        <!-- Avatar con Inicial -->
                                        <div class="user-avatar-circle">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>

                                        <div class="d-flex flex-column text-start me-1" style="line-height: 1.15;">
                                            <span class="fw-bold text-dark" style="font-size: 0.85rem;">
                                                {{ Auth::user()->name }}
                                            </span>
                                            @if ($rolNombre)
                                                <span class="text-muted text-uppercase"
                                                    style="font-size: 0.65rem; font-weight: 700; color: var(--mym-cyan) !important;">
                                                    {{ $rolNombre }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="navbarDropdown"
                                    style="min-width: 220px;">
                                    <div class="px-3 py-2 border-bottom">
                                        <small class="text-muted d-block" style="font-size: 0.7rem;">Sesión iniciada
                                            con:</small>
                                        <span
                                            class="fw-bold text-dark small text-truncate d-block">{{ Auth::user()->email }}</span>
                                    </div>

                                    <div class="pt-1">
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#modalCambiarPassword">
                                            <i class="bi bi-key text-secondary"></i> Cambiar Contraseña
                                        </a>

                                        <hr class="dropdown-divider my-1">

                                        <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </div>
                            </li>
                        </ul>

                    </div>
                </div>
            </nav>
        @endauth

        <!-- Contenedor Principal -->
        <main class="{{ Auth::check() ? 'py-4 flex-grow-1' : 'p-0 m-0 flex-grow-1' }}">
            @auth
                <div class="container-fluid px-lg-5">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 border-0 shadow-sm"
                            role="alert">
                            <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                            <div>{{ session('success') }}</div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 border-0 shadow-sm"
                            role="alert">
                            <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
                            <div>{{ session('error') }}</div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            @else
                {{-- En vistas de login o recuperación, el yield se renderiza limpio sin contenedor que lo encajone --}}
                @yield('content')
            @endauth
        </main>
    </div>

    @auth
        <!-- Modal Cambiar Contraseña Personal -->
        <div class="modal fade" id="modalCambiarPassword" tabindex="-1" aria-labelledby="modalCambiarPasswordLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <form action="{{ route('perfil.password.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="modal-header bg-light">
                            <h5 class="modal-title fs-6 fw-bold d-flex align-items-center gap-2"
                                id="modalCambiarPasswordLabel" style="color: var(--mym-navy);">
                                <i class="bi bi-shield-lock text-primary"></i> Modificar Mi Contraseña
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">CONTRASEÑA ACTUAL</label>
                                <input type="password" name="current_password" class="form-control"
                                    placeholder="••••••••" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">NUEVA CONTRASEÑA</label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Mínimo 6 caracteres" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">CONFIRMAR NUEVA CONTRASEÑA</label>
                                <input type="password" name="password_confirmation" class="form-control"
                                    placeholder="Repite la nueva contraseña" required>
                            </div>
                        </div>

                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary btn-sm"
                                data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary btn-sm fw-bold">Guardar Nueva
                                Contraseña</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endauth
</body>

</html>
