@extends('layouts.app')

@section('content')
    <div class="row g-4">
        <!-- Formulario para Registrar Nuevo Usuario -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-person-plus"></i> Registrar Nuevo Usuario
                </div>
                <div class="card-body bg-white">
                    <form action="{{ route('usuarios.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Nombre Completo</label>
                            <input type="text" name="name" class="form-control" required
                                placeholder="Ej: Dr. Carlos Pérez">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control" required
                                placeholder="correo@ejemplo.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Contraseña</label>
                            <input type="password" name="password" class="form-control" required placeholder="******">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Rol del Usuario</label>
                            <select name="role" id="role_select" class="form-select" required
                                onchange="toggleLaboratorio(this.value, 'laboratorio_container')">
                                <option value="">Seleccione un rol...</option>
                                @foreach ($roles as $r)
                                    <option value="{{ $r->name }}">{{ strtoupper($r->name) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Selección de Laboratorio (Solo si el rol es laboratorio) -->
                        <div class="mb-3 d-none" id="laboratorio_container">
                            <label class="form-label text-muted small fw-bold">Asociar a Laboratorio Cliente</label>
                            <select name="laboratorio_id" class="form-select">
                                <option value="">Seleccione laboratorio...</option>
                                @foreach ($laboratorios as $l)
                                    <option value="{{ $l->id }}">{{ $l->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold">
                            <i class="bi bi-check2-circle me-1"></i> Crear Usuario
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabla de Usuarios Registrados -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div
                    class="card-header bg-secondary text-white fw-bold d-flex justify-content-between align-items-center py-2 px-3">
                    <span class="d-flex align-items-center gap-2">
                        <i class="bi bi-people"></i> Usuarios del Sistema
                    </span>
                    <span class="badge bg-light text-secondary border fw-semibold">
                        {{ $usuarios->count() }} Registrados
                    </span>
                </div>
                <div class="card-body p-0 bg-white">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 text-center">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 28%;" class="text-start ps-3">Nombre</th>
                                    <th style="width: 25%;" class="text-start">Email</th>
                                    <th style="width: 15%;">Rol</th>
                                    <th style="width: 22%;" class="text-start">Laboratorio</th>
                                    <th style="width: 10%;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($usuarios as $u)
                                    <tr>
                                        <td class="text-start ps-3 fw-bold text-dark">{{ $u->name }}</td>
                                        <td class="text-start text-muted small">{{ $u->email }}</td>
                                        <td>
                                            @php $rol = $u->roles->first()->name ?? null; @endphp
                                            @if ($rol === 'admin')
                                                <span class="badge bg-danger">ADMIN</span>
                                            @elseif($rol === 'patologo')
                                                <span class="badge bg-warning text-dark">PATÓLOGO</span>
                                            @else
                                                <span class="badge bg-info text-dark">LABORATORIO</span>
                                            @endif
                                        </td>
                                        <td class="text-start">
                                            <span class="text-muted small">
                                                {{ $u->laboratorio ? $u->laboratorio->nombre : '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-inline-flex align-items-center gap-1">
                                                <!-- Botón Editar Modal -->
                                                <button type="button"
                                                    class="btn btn-sm btn-light border text-primary px-2 py-1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEditarUsuario{{ $u->id }}"
                                                    title="Editar usuario">
                                                    <i class="bi bi-pencil"></i>
                                                </button>

                                                <!-- Dar de Baja / Eliminar -->
                                                @if (auth()->id() !== $u->id)
                                                    <form action="{{ route('usuarios.destroy', $u->id) }}" method="POST"
                                                        class="m-0 p-0"
                                                        onsubmit="return confirm('¿Seguro que deseas dar de baja al usuario {{ $u->name }}?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-light border text-danger px-2 py-1"
                                                            title="Dar de baja">
                                                            <i class="bi bi-trash3"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- MODAL EDITAR USUARIO -->
                                    <div class="modal fade" id="modalEditarUsuario{{ $u->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow">
                                                <form action="{{ route('usuarios.update', $u->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header bg-light">
                                                        <h5
                                                            class="modal-title fs-6 fw-bold text-primary d-flex align-items-center gap-2">
                                                            <i class="bi bi-pencil-square"></i> Editar: {{ $u->name }}
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Cerrar"></button>
                                                    </div>
                                                    <div class="modal-body text-start">
                                                        <div class="mb-3">
                                                            <label class="form-label text-muted small fw-bold">NOMBRE
                                                                COMPLETO</label>
                                                            <input type="text" name="name" class="form-control"
                                                                value="{{ $u->name }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label text-muted small fw-bold">CORREO
                                                                ELECTRÓNICO</label>
                                                            <input type="email" name="email" class="form-control"
                                                                value="{{ $u->email }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label text-muted small fw-bold">NUEVA
                                                                CONTRASEÑA (OPCIONAL)</label>
                                                            <input type="password" name="password" class="form-control"
                                                                placeholder="Dejar vacío si no se cambia">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label text-muted small fw-bold">ROL</label>
                                                            <select name="role" class="form-select" required
                                                                onchange="toggleLaboratorio(this.value, 'lab_edit_container_{{ $u->id }}')">
                                                                @foreach ($roles as $r)
                                                                    <option value="{{ $r->name }}"
                                                                        {{ $u->hasRole($r->name) ? 'selected' : '' }}>
                                                                        {{ strtoupper($r->name) }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3 {{ $u->hasRole('laboratorio') ? '' : 'd-none' }}"
                                                            id="lab_edit_container_{{ $u->id }}">
                                                            <label class="form-label text-muted small fw-bold">LABORATORIO
                                                                ASOCIADO</label>
                                                            <select name="laboratorio_id" class="form-select">
                                                                <option value="">Seleccione laboratorio...</option>
                                                                @foreach ($laboratorios as $l)
                                                                    <option value="{{ $l->id }}"
                                                                        {{ $u->laboratorio_id == $l->id ? 'selected' : '' }}>
                                                                        {{ $l->nombre }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light">
                                                        <button type="button" class="btn btn-secondary btn-sm"
                                                            data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="submit"
                                                            class="btn btn-primary btn-sm fw-bold">Guardar Cambios</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No hay usuarios
                                            registrados.</td>
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
        function toggleLaboratorio(role, targetId) {
            const container = document.getElementById(targetId);
            if (!container) return;
            if (role === 'laboratorio') {
                container.classList.remove('d-none');
            } else {
                container.classList.add('d-none');
            }
        }
    </script>
@endsection
