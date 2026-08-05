<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Trazabilidad Anatomía Patológica</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
    /* Estilos Temáticos - Clínica / Salud */
    :root {
        --clinical-blue: #0f4c81;
        --clinical-light-blue: #eef5fc;
        --clinical-dark: #1b2a4a;
        --clinical-accent: #2563eb;
        --clinical-border: #e2e8f0;
    }

    body {
        background-color: #f4f7fa !important;
        font-family: 'Nunito', system-ui, -apple-system, sans-serif;
        color: #334155;
    }

    /* Navbar Estilizado */
    .navbar {
        background-color: #ffffff !important;
        border-bottom: 2px solid var(--clinical-light-blue);
    }
    .navbar-brand {
        color: var(--clinical-blue) !important;
        letter-spacing: 0.5px;
    }
    .nav-link {
        color: #475569 !important;
        font-weight: 600;
        transition: color 0.2s ease;
    }
    .nav-link:hover, .nav-link.active {
        color: var(--clinical-accent) !important;
    }

    /* Tarjetas Médicas */
    .card {
        border: 1px solid var(--clinical-border) !important;
        border-radius: 10px !important;
        box-shadow: 0 4px 12px rgba(15, 76, 129, 0.04) !important;
        overflow: hidden;
    }
    .card-header {
        background-color: var(--clinical-blue) !important;
        color: #ffffff !important;
        font-weight: 700;
        letter-spacing: 0.3px;
        padding: 0.85rem 1.25rem;
        border-bottom: none !important;
    }
    .card-header.bg-secondary {
        background-color: var(--clinical-dark) !important;
    }
    .card-header.bg-warning {
        background-color: #f59e0b !important;
        color: #ffffff !important;
    }

    /* Tablas de Trazabilidad */
    .table-responsive {
        border-radius: 0 0 10px 10px;
    }
    .table thead th {
        background-color: var(--clinical-light-blue) !important;
        color: var(--clinical-dark);
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #cbd5e1 !important;
    }
    .table td {
        vertical-align: middle;
        font-size: 0.92rem;
    }

    /* Badges de Estado Limpios */
    .badge-estado {
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.78rem;
        display: inline-block;
    }
    .estado-pendiente { background-color: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
    .estado-espera { background-color: #f0f9ff; color: #0284c7; border: 1px solid #bae6fd; }
    .estado-critico { background-color: #fff1f2; color: #be123c; border: 1px solid #fecdd3; animation: pulse-critico 2s infinite; }
    .estado-informado { background-color: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }

    /* Botones Estilizados */
    .btn-primary {
        background-color: var(--clinical-blue) !important;
        border-color: var(--clinical-blue) !important;
        border-radius: 6px;
        font-weight: 600;
    }
    .btn-primary:hover {
        background-color: #0b3c66 !important;
    }
    .btn-outline-dark {
        border-color: #cbd5e1;
        color: var(--clinical-dark);
        border-radius: 6px;
    }
    .btn-outline-dark:hover {
        background-color: var(--clinical-light-blue);
        color: var(--clinical-blue);
        border-color: var(--clinical-blue);
    }

    /* Inputs y Selects */
    .form-control, .form-select {
        border-radius: 6px;
        border: 1px solid #cbd5e1;
        font-size: 0.9rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--clinical-accent);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    @keyframes pulse-critico {
        0% { box-shadow: 0 0 0 0 rgba(190, 18, 60, 0.4); }
        70% { box-shadow: 0 0 0 6px rgba(190, 18, 60, 0); }
        100% { box-shadow: 0 0 0 0 rgba(190, 18, 60, 0); }
    }
</style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm py-2">
            <div class="container">
                <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ url('/') }}">
                    <span>🏥</span>
                    <span>{{ config('app.name', 'Laravel') }}</span>
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar (ENLACES DEL SISTEMA) -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('examenes.index') }}">
                                    <i class="bi bi-search me-1"></i>Búsqueda Exámenes
                                </a>
                            </li>
                            @hasrole('admin')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('usuarios.index') }}">Usuarios</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('laboratorios.index') }}">Laboratorios</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('tipos.index') }}">Tipos de Examen</a>
                                </li>
                            @endhasrole
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto align-items-center">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                @php
                                    // Detección flexible del nombre del Rol (Spatie, propiedad directa o relación)
                                    $rolNombre = method_exists(Auth::user(), 'getRoleNames') 
                                        ? Auth::user()->getRoleNames()->first() 
                                        : (Auth::user()->role ?? Auth::user()->roles->first()->name ?? null);
                                @endphp

                                <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center gap-2 py-1" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <!-- Avatar / Icono de Usuario -->
                                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 0.85rem; background-color: var(--clinical-light-blue); color: var(--clinical-blue);">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>

                                    <div class="d-flex flex-column text-start" style="line-height: 1.1;">
                                        <span class="fw-bold text-dark" style="font-size: 0.88rem;">{{ Auth::user()->name }}</span>
                                        @if($rolNombre)
                                            <span class="badge rounded-pill text-uppercase mt-1" style="font-size: 0.65rem; background-color: var(--clinical-light-blue); color: var(--clinical-blue); border: 1px solid #bae6fd; width: fit-content;">
                                                {{ $rolNombre }}
                                            </span>
                                        @endif
                                    </div>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" aria-labelledby="navbarDropdown">
                                    <div class="dropdown-header text-muted small fw-semibold">
                                        Sesión iniciada como: <br>
                                        <strong class="text-dark">{{ Auth::user()->email }}</strong>
                                    </div>
                                    <div class="dropdown-divider"></div>
                                    
                                    <a class="dropdown-item text-danger fw-semibold" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                        🚪 {{ __('Cerrar Sesión') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                <!-- Alertas de Sistema -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>