@extends('layouts.app')

@section('content')
    <div class="row g-4">
        <!-- Formulario para Registrar Nuevo Usuario -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white font-weight-bold">Registrar Nuevo Usuario</div>
                <div class="card-body">
                    <form action="{{ route('usuarios.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nombre Completo</label>
                            <input type="text" name="name" class="form-control" required
                                placeholder="Ej: Dr. Carlos Pérez">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control" required
                                placeholder="correo@ejemplo.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="password" class="form-control" required placeholder="******">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rol del Usuario</label>
                            <select name="role" id="role_select" class="form-select" required
                                onchange="toggleLaboratorio(this.value)">
                                <option value="">Seleccione un rol...</option>
                                @foreach ($roles as $r)
                                    <option value="{{ $r->name }}">{{ strtoupper($r->name) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Selección de Laboratorio (Solo si el rol es laboratorio) -->
                        <div class="mb-3 d-none" id="laboratorio_container">
                            <label class="form-label">Asociar a Laboratorio Cliente</label>
                            <select name="laboratorio_id" class="form-select">
                                <option value="">Seleccione laboratorio...</option>
                                @foreach ($laboratorios as $l)
                                    <option value="{{ $l->id }}">{{ $l->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Crear Usuario</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabla de Usuarios Registrados -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white font-weight-bold">Usuarios del Sistema</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Rol</th>
                                    <th>Laboratorio Asociado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($usuarios as $u)
                                    <tr>
                                        <td class="fw-bold">{{ $u->name }}</td>
                                        <td>{{ $u->email }}</td>
                                        <td>
                                            @foreach ($u->roles as $r)
                                                <span
                                                    class="badge bg-{{ $r->name == 'admin' ? 'danger' : ($r->name == 'patologo' ? 'warning text-dark' : 'info') }}">
                                                    {{ strtoupper($r->name) }}
                                                </span>
                                            @endforeach
                                        </td>
                                        <td>
                                            {{ $u->laboratorio ? $u->laboratorio->nombre : '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">No hay usuarios registrados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleLaboratorio(role) {
            const container = document.getElementById('laboratorio_container');
            if (role === 'laboratorio') {
                container.classList.remove('d-none');
            } else {
                container.classList.add('d-none');
            }
        }
    </script>
@endsection
