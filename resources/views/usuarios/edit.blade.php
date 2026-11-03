@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white fw-bold d-flex justify-content-between align-items-center">
                        <span>✏️ Modificar Usuario: {{ $usuario->name }}</span>
                        <a href="{{ route('usuarios.index') }}" class="btn btn-sm btn-light text-primary fw-bold">Volver</a>
                    </div>
                    <div class="card-body bg-white p-4">
                        <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">NOMBRE COMPLETO</label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $usuario->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">CORREO ELECTRÓNICO</label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $usuario->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">NUEVA CONTRASEÑA (OPCIONAL)</label>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Dejar en blanco para mantener la contraseña actual">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">ROL</label>
                                <select name="role" id="role_select_edit" class="form-select" required
                                    onchange="toggleLaboratorio(this.value, 'lab_edit_full')">
                                    @foreach ($roles as $r)
                                        <option value="{{ $r->name }}"
                                            {{ $usuario->hasRole($r->name) ? 'selected' : '' }}>
                                            {{ strtoupper($r->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4 {{ $usuario->hasRole('laboratorio') ? '' : 'd-none' }}" id="lab_edit_full">
                                <label class="form-label text-muted small fw-bold">LABORATORIO ASOCIADO</label>
                                <select name="laboratorio_id" class="form-select">
                                    <option value="">Seleccione laboratorio...</option>
                                    @foreach ($laboratorios as $l)
                                        <option value="{{ $l->id }}"
                                            {{ $usuario->laboratorio_id == $l->id ? 'selected' : '' }}>
                                            {{ $l->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="d-flex justify-content-between pt-2">
                                <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary px-4">Cancelar</a>
                                <button type="submit" class="btn btn-primary fw-bold px-4">Guardar Cambios</button>
                            </div>
                        </form>
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
