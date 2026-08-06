@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- FORMULARIO REGISTRAR MÉDICO -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white fw-bold">
                    👨‍⚕️ Registrar Nuevo Médico
                </div>
                <div class="card-body bg-white">
                    <form action="{{ route('medicos.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">NOMBRE Y APELLIDO</label>
                            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" 
                                   placeholder="Ej: Dr. Roberto Gómez" value="{{ old('nombre') }}" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold">Guardar Médico</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- TABLA MANTENEDOR DE MÉDICOS -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-secondary text-white fw-bold text-center py-3">
                    MANTENEDOR DE MÉDICOS SOLICITANTES
                </div>
                <div class="card-body p-0 bg-white">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle text-center mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 10%;">#</th>
                                    <th class="text-start" style="width: 60%;">NOMBRE Y APELLIDO</th>
                                    <th style="width: 30%;">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($medicos as $medico)
                                <tr>
                                    <td class="fw-bold text-muted">{{ $loop->iteration }}</td>
                                    <td class="text-start fw-semibold text-dark">{{ $medico->nombre }}</td>
                                    <td>
                                        <!-- Botón Editar (Modal) -->
                                        <button class="btn btn-sm btn-outline-primary me-1 fw-bold" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalEditar{{ $medico->id }}">
                                            Editar
                                        </button>

                                        <!-- Formulario Eliminar -->
                                        <form action="{{ route('medicos.destroy', $medico->id) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('¿Está seguro de eliminar a este médico?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger fw-bold">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- MODAL DE EDICIÓN -->
                                <div class="modal fade" id="modalEditar{{ $medico->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <form action="{{ route('medicos.update', $medico->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header bg-light">
                                                    <h5 class="modal-title fs-6 fw-bold text-primary">Editar Registro de Médico</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-start">
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted small fw-bold">NOMBRE Y APELLIDO</label>
                                                        <input type="text" name="nombre" class="form-control" value="{{ $medico->nombre }}" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer bg-light">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-primary btn-sm fw-bold">Actualizar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-muted py-5">No existen médicos registrados.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection