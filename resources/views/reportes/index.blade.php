@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <!-- TARJETA CON EL FORMULARIO DE FILTROS -->
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-header bg-primary text-white fw-bold d-flex align-items-center justify-content-between">
                <div>📊 Generador y Filtro de Reportes de Anatomía Patológica</div>
                <span class="badge bg-light text-primary">{{ $examenes->count() }} Resultados Encontrados</span>
            </div>
            <div class="card-body bg-white">
                <form action="{{ route('reportes.index') }}" method="GET" class="row g-3">

                    <!-- RANGO DE FECHAS -->
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold">FECHA RECEPCIÓN (DESDE)</label>
                        <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold">FECHA RECEPCIÓN (HASTA)</label>
                        <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}" class="form-control">
                    </div>

                    <!-- PATÓLOGO -->
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold">PATÓLOGO</label>
                        <select name="patologo_id" class="form-select">
                            <option value="">Todos los Patólogos</option>
                            @foreach ($patologos as $p)
                                <option value="{{ $p->id }}"
                                    {{ request('patologo_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- LABORATORIO / CLIENTE -->
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold">CLIENTE / LABORATORIO</label>
                        <select name="laboratorio_id" class="form-select">
                            <option value="">Todos los Laboratorios</option>
                            @foreach ($laboratorios as $l)
                                <option value="{{ $l->id }}"
                                    {{ request('laboratorio_id') == $l->id ? 'selected' : '' }}>
                                    {{ $l->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- TIPO DE EXAMEN -->
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">TIPO DE EXAMEN</label>
                        <select name="tipo_examen_id" class="form-select">
                            <option value="">Todos los Tipos</option>
                            @foreach ($tiposExamen as $t)
                                <option value="{{ $t->id }}"
                                    {{ request('tipo_examen_id') == $t->id ? 'selected' : '' }}>
                                    {{ $t->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- ESTADO -->
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">ESTADO DEL INFORME</label>
                        <select name="estado" class="form-select">
                            <option value="">Todos los Estados</option>
                            <option value="PENDIENTE" {{ request('estado') == 'PENDIENTE' ? 'selected' : '' }}>PENDIENTE
                            </option>
                            <option value="EN ESPERA INFORME COMPLEMENTARIO"
                                {{ request('estado') == 'EN ESPERA INFORME COMPLEMENTARIO' ? 'selected' : '' }}>EN ESPERA
                                INFORME COMPLEMENTARIO</option>
                            <option value="INFORMADO RESULTADO CRÍTICO"
                                {{ request('estado') == 'INFORMADO RESULTADO CRÍTICO' ? 'selected' : '' }}>INFORMADO
                                RESULTADO CRÍTICO</option>
                            <option value="INFORMADO" {{ request('estado') == 'INFORMADO' ? 'selected' : '' }}>INFORMADO
                            </option>
                        </select>
                    </div>

                    <!-- BOTONES DE ACCIÓN -->
                    <div class="col-md-4 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary fw-bold flex-grow-1">
                            🔍 Filtrar Reporte
                        </button>

                        <button type="submit" name="exportar" value="csv" class="btn btn-success fw-bold">
                            📥 Exportar Excel / CSV
                        </button>

                        <a href="{{ route('reportes.index') }}" class="btn btn-outline-secondary" title="Limpiar Filtros">
                            🔄
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- RESULTADO DEL REPORTE -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-secondary text-white fw-bold text-center py-2">
                DETALLE CONSOLIDADO DEL REPORTE
            </div>
            <div class="card-body p-0 bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>CORRELATIVO</th>
                                <th>F. RECEPCIÓN</th>
                                <th>PACIENTE</th>
                                <th>RUT</th>
                                <th>LABORATORIO</th>
                                <th>PATÓLOGO</th>
                                <th>TIPO EXAMEN</th>
                                <th>ESTADO</th>
                                <th>DETALLE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($examenes as $ex)
                                <tr>
                                    <td class="fw-bold text-primary">{{ $ex->numero_correlativo }}</td>
                                    <td>{{ \Carbon\Carbon::parse($ex->fecha_recepcion)->format('d/m/Y') }}</td>
                                    <td class="fw-semibold text-start ps-3">{{ $ex->paciente_nombre }}</td>
                                    <td><code>{{ $ex->paciente_rut }}</code></td>
                                    <td>{{ $ex->laboratorio->nombre ?? 'N/A' }}</td>
                                    <td>
                                        @if ($ex->patologo)
                                            <span class="badge bg-light text-dark border">{{ $ex->patologo->name }}</span>
                                        @else
                                            <span class="badge bg-secondary opacity-50">Sin asignar</span>
                                        @endif
                                    </td>
                                    <td>{{ $ex->tipoExamen->nombre ?? 'N/A' }}</td>
                                    <td>
                                        @if ($ex->estado == 'EN ESPERA INFORME COMPLEMENTARIO')
                                            <span class="badge-estado estado-espera">EN ESPERA</span>
                                        @elseif($ex->estado == 'INFORMADO RESULTADO CRÍTICO')
                                            <span class="badge-estado estado-critico">CRÍTICO</span>
                                        @elseif($ex->estado == 'INFORMADO')
                                            <span class="badge-estado estado-informado">INFORMADO</span>
                                        @else
                                            <span class="badge-estado estado-pendiente">PENDIENTE</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('examenes.show', $ex->id) }}" target="_blank"
                                            class="btn btn-sm btn-outline-dark fw-bold">Ver</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-muted py-5">No existen registros que coincidan con los
                                        filtros seleccionados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
