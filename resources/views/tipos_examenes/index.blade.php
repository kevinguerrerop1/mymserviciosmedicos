@extends('layouts.app')

@section('content')
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white font-weight-bold">Nuevo Tipo de Examen</div>
                <div class="card-body">
                    <form action="{{ route('tipos.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nombre del Examen</label>
                            <input type="text" name="nombre" class="form-control"
                                placeholder="Ej: Biopsia Gástrica, Citología" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Guardar Tipo de Examen</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white font-weight-bold">Tipos de Examen Disponibles</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 20%;">ID</th>
                                    <th>Nombre del Tipo de Examen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tipos as $t)
                                    <tr>
                                        <td>{{ $t->id }}</td>
                                        <td class="fw-bold">{{ $t->nombre }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted py-3">No hay tipos de examen
                                            configurados.</td>
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
