@extends('layouts.app')

@section('content')
    <div class="row g-4">
        <!-- FORMULARIO: REGISTRAR NUEVO LABORATORIO -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-building-add"></i> Registrar Laboratorio
                </div>
                <div class="card-body bg-white">
                    <form action="{{ route('laboratorios.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">NOMBRE DE LA INSTITUCIÓN / LAB</label>
                            <input type="text" name="nombre" class="form-control" required
                                placeholder="Ej: Clínica Los Andes">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">RUT INSTITUCIONAL</label>
                            <input type="text" name="rut" id="lab_rut" class="form-control" maxlength="12" required
                                placeholder="76.123.456-7">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">DIRECCIÓN</label>
                            <input type="text" name="direccion" class="form-control" required
                                placeholder="Ej: Av. San Martín 456">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">CORREO ELECTRÓNICO (CONTACTO)</label>
                            <input type="email" name="email" class="form-control" placeholder="contacto@laboratorio.cl">
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold">
                            <i class="bi bi-check2-circle me-1"></i> Guardar Laboratorio
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- LISTADO DE LABORATORIOS REGISTRADOS -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div
                    class="card-header bg-secondary text-white fw-bold d-flex justify-content-between align-items-center py-2 px-3">
                    <span class="d-flex align-items-center gap-2">
                        <i class="bi bi-building"></i> Laboratorios y Centros Derivadores
                    </span>
                    <span class="badge bg-light text-secondary border fw-semibold">
                        {{ $laboratorios->count() }} Registrados
                    </span>
                </div>
                <div class="card-body p-0 bg-white">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 text-center">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 28%;" class="text-start ps-3">Laboratorio</th>
                                    <th style="width: 17%;">RUT</th>
                                    <th style="width: 25%;" class="text-start">Dirección</th>
                                    <th style="width: 18%;" class="text-start">Email</th>
                                    <th style="width: 12%;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($laboratorios as $l)
                                    <tr>
                                        <td class="text-start ps-3 fw-bold text-dark">
                                            {{ $l->nombre }}
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-secondary border font-monospace px-2 py-1">
                                                {{ $l->rut }}
                                            </span>
                                        </td>
                                        <td class="text-start text-muted small">
                                            {{ $l->direccion }}
                                        </td>
                                        <td class="text-start text-muted small">
                                            {{ $l->email ?? '-' }}
                                        </td>
                                        <td class="text-center">
                                            <div class="d-inline-flex align-items-center gap-1">
                                                <!-- Editar -->
                                                <button type="button"
                                                    class="btn btn-sm btn-light border text-primary px-2 py-1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEditarLab{{ $l->id }}"
                                                    title="Editar laboratorio">
                                                    <i class="bi bi-pencil"></i>
                                                </button>

                                                <!-- Eliminar / Baja -->
                                                <form action="{{ route('laboratorios.destroy', $l->id) }}" method="POST"
                                                    class="m-0 p-0"
                                                    onsubmit="return confirm('¿Seguro que deseas eliminar el laboratorio {{ $l->nombre }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-light border text-danger px-2 py-1"
                                                        title="Dar de baja">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- MODAL EDITAR LABORATORIO -->
                                    <div class="modal fade" id="modalEditarLab{{ $l->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow">
                                                <form action="{{ route('laboratorios.update', $l->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header bg-light">
                                                        <h5
                                                            class="modal-title fs-6 fw-bold text-primary d-flex align-items-center gap-2">
                                                            <i class="bi bi-pencil-square"></i> Editar: {{ $l->nombre }}
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Cerrar"></button>
                                                    </div>
                                                    <div class="modal-body text-start">
                                                        <div class="mb-3">
                                                            <label class="form-label text-muted small fw-bold">NOMBRE DE LA
                                                                INSTITUCIÓN</label>
                                                            <input type="text" name="nombre" class="form-control"
                                                                value="{{ $l->nombre }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label text-muted small fw-bold">RUT
                                                                INSTITUCIONAL</label>
                                                            <input type="text" name="rut"
                                                                class="form-control input-rut-edit" maxlength="12"
                                                                value="{{ $l->rut }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label
                                                                class="form-label text-muted small fw-bold">DIRECCIÓN</label>
                                                            <input type="text" name="direccion" class="form-control"
                                                                value="{{ $l->direccion }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label text-muted small fw-bold">CORREO
                                                                ELECTRÓNICO</label>
                                                            <input type="email" name="email" class="form-control"
                                                                value="{{ $l->email }}">
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
                                        <td colspan="5" class="text-center text-muted py-4">No hay laboratorios
                                            registrados en el sistema.</td>
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
        document.addEventListener("DOMContentLoaded", function() {
            function formatearRut(input) {
                let valor = input.value.replace(/[^0-9kK]/g, '').toUpperCase();
                if (valor.length <= 1) {
                    input.value = valor;
                    return;
                }
                let cuerpo = valor.slice(0, -1);
                let dv = valor.slice(-1);
                cuerpo = cuerpo.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                input.value = `${cuerpo}-${dv}`;
            }

            const rutCrear = document.getElementById('lab_rut');
            if (rutCrear) {
                rutCrear.addEventListener('input', function() {
                    formatearRut(this);
                });
            }

            document.querySelectorAll('.input-rut-edit').forEach(function(input) {
                input.addEventListener('input', function() {
                    formatearRut(this);
                });
            });
        });
    </script>
@endsection
