@extends('layouts.app')

@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white font-weight-bold">Nuevo Laboratorio</div>
            <div class="card-body">
                <form action="{{ route('laboratorios.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nombre del Laboratorio</label>
                        <input type="text" name="nombre" class="form-control" required placeholder="Ej: Laboratorio Clinico Maule">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">RUT</label>
                        <input type="text" name="rut" class="form-control" required placeholder="76.123.456-7">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="direccion" class="form-control" required placeholder="Av. Libertad 123">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email de Contacto</label>
                        <input type="email" name="email" class="form-control" placeholder="contacto@lab.cl">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Guardar Laboratorio</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white font-weight-bold">Laboratorios Registrados</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>RUT</th>
                                <th>Dirección</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($laboratorios as $l)
                            <tr>
                                <td class="fw-bold">{{ $l->nombre }}</td>
                                <td>{{ $l->rut }}</td>
                                <td>{{ $l->direccion }}</td>
                                <td>{{ $l->email ?? 'N/A' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">No hay laboratorios registrados.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection