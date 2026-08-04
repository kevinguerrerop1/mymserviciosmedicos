@extends('layouts.app')

@section('content')

@hasrole('admin')
<!-- Panel para Asignar / Registrar Nuevo Examen -->
<div class="card mb-4">
    <div class="card-header">
        <i class="me-2">🏥</i> Ingresar & Asignar Nuevo Examen Patológico
    </div>
    <div class="card-body bg-white">
        <form action="{{ route('examenes.store') }}" method="POST" class="row g-3">
            @csrf
            <div class="col-md-3">
                <label class="form-label text-muted small fw-bold">FECHA TOMA</label>
                <input type="date" name="fecha_toma" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted small fw-bold">FECHA RECEPCIÓN</label>
                <input type="date" name="fecha_recepcion" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted small fw-bold">NOMBRE PACIENTE</label>
                <input type="text" name="paciente_nombre" class="form-control" required placeholder="Ej: Juan Pérez">
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted small fw-bold">RUT PACIENTE</label>
                <input type="text" name="paciente_rut" class="form-control" required placeholder="12.345.678-9">
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted small fw-bold">MÉDICO SOLICITANTE</label>
                <input type="text" name="medico_solicitante" class="form-control" required placeholder="Dr. González">
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted small fw-bold">TIPO DE EXAMEN</label>
                <select name="tipo_examen_id" class="form-select" required>
                    <option value="">Seleccione...</option>
                    @foreach($tiposExamen as $t)
                        <option value="{{ $t->id }}">{{ $t->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted small fw-bold">LABORATORIO ORIGEN</label>
                <select name="laboratorio_id" class="form-select" required>
                    <option value="">Seleccione...</option>
                    @foreach($laboratorios as $l)
                        <option value="{{ $l->id }}">{{ $l->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted small fw-bold">ASIGNAR PATÓLOGO</label>
                <select name="patologo_id" class="form-select">
                    <option value="">Pendiente...</option>
                    @foreach($patologos as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 text-end mt-3">
                <button type="submit" class="btn btn-primary px-4">Registrar Examen</button>
            </div>
        </form>
    </div>
</div>
@endhasrole

<!-- BÚSQUEDA DE EXAMEN (TABLA TIPO CLÍNICA) -->
<div class="card">
    <div class="card-header bg-secondary text-center py-3 text-white fw-bold">
        BÚSQUEDA DE EXAMEN: (FILTROS POR CADA SLOT)
    </div>
    <div class="card-body p-0 bg-white">
        <form method="GET" action="{{ route('examenes.index') }}">
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center mb-0">
                    <thead>
                        <tr>
                            <th style="width: 10%;">N° CORRELATIVO</th>
                            <th style="width: 12%;">FECHA TOMA</th>
                            <th style="width: 20%;">NOMBRE PACIENTE</th>
                            <th style="width: 13%;">RUT</th>
                            <th style="width: 18%;">PATÓLOGO</th> <!-- COLUMNA AGREGADA -->
                            <th style="width: 17%;">ESTADO</th>
                            <th style="width: 10%;">ACCIÓN</th>
                        </tr>
                        <!-- Fila de Slots -->
                        <tr class="bg-light">
                            <th class="p-2"><input type="text" name="correlativo" value="{{ request('correlativo') }}" class="form-control form-control-sm"></th>
                            <th class="p-2"><input type="date" name="fecha_toma" value="{{ request('fecha_toma') }}" class="form-control form-control-sm"></th>
                            <th class="p-2"><input type="text" name="paciente" value="{{ request('paciente') }}" class="form-control form-control-sm"></th>
                            <th class="p-2"><input type="text" name="rut" value="{{ request('rut') }}" class="form-control form-control-sm"></th>
                            
                            <!-- FILTRO SLOT PATÓLOGO -->
                            <th class="p-2">
                                <select name="patologo_id" class="form-select form-select-sm">
                                    <option value="">Todos</option>
                                    @foreach($patologos as $p)
                                        <option value="{{ $p->id }}" {{ request('patologo_id') == $p->id ? 'selected' : '' }}>
                                            {{ $p->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </th>

                            <th class="p-2">
                                <select name="estado" class="form-select form-select-sm">
                                    <option value="">Todos</option>
                                    <option value="PENDIENTE" {{ request('estado') == 'PENDIENTE' ? 'selected' : '' }}>PENDIENTE</option>
                                    <option value="EN ESPERA INFORME COMPLEMENTARIO" {{ request('estado') == 'EN ESPERA INFORME COMPLEMENTARIO' ? 'selected' : '' }}>EN ESPERA</option>
                                    <option value="INFORMADO RESULTADO CRÍTICO" {{ request('estado') == 'INFORMADO RESULTADO CRÍTICO' ? 'selected' : '' }}>CRÍTICO</option>
                                    <option value="INFORMADO" {{ request('estado') == 'INFORMADO' ? 'selected' : '' }}>INFORMADO</option>
                                </select>
                            </th>
                            <th class="p-2"><button type="submit" class="btn btn-sm btn-primary w-100">Filtrar</button></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($examenes as $ex)
                        <tr>
                            <td class="fw-bold text-primary">{{ $ex->numero_correlativo }}</td>
                            <td>{{ \Carbon\Carbon::parse($ex->fecha_toma)->format('d/m/Y') }}</td>
                            <td class="fw-semibold text-dark">{{ $ex->paciente_nombre }}</td>
                            <td><code>{{ $ex->paciente_rut }}</code></td>
                            
                            <!-- DATO PATÓLOGO ASIGNADO -->
                            <td>
                                @if($ex->patologo)
                                    <span class="badge bg-light text-dark border">
                                        {{ $ex->patologo->name }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary opacity-50">Sin asignar</span>
                                @endif
                            </td>

                            <td>
                                @if($ex->estado == 'EN ESPERA INFORME COMPLEMENTARIO')
                                    <span class="badge-estado estado-espera">EN ESPERA INFORME COMPLEMENTARIO</span>
                                @elseif($ex->estado == 'INFORMADO RESULTADO CRÍTICO')
                                    <span class="badge-estado estado-critico">INFORMADO RESULTADO CRÍTICO</span>
                                @elseif($ex->estado == 'INFORMADO')
                                    <span class="badge-estado estado-informado">INFORMADO</span>
                                @else
                                    <span class="badge-estado estado-pendiente">PENDIENTE</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('examenes.show', $ex->id) }}" class="btn btn-sm btn-outline-dark fw-bold px-3">ABRIR</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-muted py-5">No existen registros de exámenes.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>
@endsection