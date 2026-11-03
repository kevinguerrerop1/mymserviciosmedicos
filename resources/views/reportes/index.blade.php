@extends('layouts.app')

@section('content')
    <div class="container-fluid py-2 px-md-4">

        <!-- ESTILOS VISUALES MEJORADOS -->
        <style>
            .stat-card {
                border: 1px solid #e2e8f0;
                border-radius: 12px;
                background: #ffffff;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .stat-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.07);
            }

            .card-custom {
                border: 1px solid #e2e8f0;
                border-radius: 14px;
                background: #ffffff;
                box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
                overflow: hidden;
            }

            .form-floating-custom label {
                font-size: 0.75rem;
                font-weight: 700;
                color: #64748b;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin-bottom: 5px;
                display: flex;
                align-items: center;
                gap: 6px;
            }

            .input-group-custom .form-control,
            .input-group-custom .form-select {
                border-radius: 9px !important;
                border: 1px solid #cbd5e1;
                padding: 0.58rem 0.85rem;
                font-size: 0.885rem;
                color: #1e293b;
                transition: all 0.2s ease;
            }

            .input-group-custom .form-control:focus,
            .input-group-custom .form-select:focus {
                border-color: #2563eb;
                box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
            }

            .table-custom thead th {
                background: #f8fafc;
                color: #475569;
                font-size: 0.74rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.6px;
                padding: 12px 14px;
                border-bottom: 1px solid #e2e8f0;
            }

            .table-custom tbody td {
                padding: 14px;
                vertical-align: middle;
                font-size: 0.875rem;
                border-bottom: 1px solid #f1f5f9;
            }

            .table-custom tbody tr:hover {
                background-color: #f8fafc;
            }

            /* BADGES DE ESTADO MODERNOS */
            .badge-estado {
                display: inline-flex;
                align-items: center;
                gap: 5px;
                padding: 5px 10px;
                font-size: 0.72rem;
                font-weight: 700;
                border-radius: 20px;
                letter-spacing: 0.3px;
            }

            .badge-estado::before {
                content: '';
                width: 6px;
                height: 6px;
                border-radius: 50%;
                display: inline-block;
            }

            .estado-informado {
                background-color: #ecfdf5;
                color: #065f46;
                border: 1px solid #a7f3d0;
            }

            .estado-informado::before {
                background-color: #10b981;
            }

            .estado-critico {
                background-color: #fff1f2;
                color: #9f1239;
                border: 1px solid #fecdd3;
                animation: pulse-border 2s infinite;
            }

            .estado-critico::before {
                background-color: #e11d48;
            }

            .estado-espera {
                background-color: #fffbeb;
                color: #92400e;
                border: 1px solid #fde68a;
            }

            .estado-espera::before {
                background-color: #f59e0b;
            }

            .estado-pendiente {
                background-color: #f1f5f9;
                color: #334155;
                border: 1px solid #cbd5e1;
            }

            .estado-pendiente::before {
                background-color: #64748b;
            }

            @keyframes pulse-border {
                0% {
                    box-shadow: 0 0 0 0 rgba(225, 29, 72, 0.4);
                }

                70% {
                    box-shadow: 0 0 0 6px rgba(225, 29, 72, 0);
                }

                100% {
                    box-shadow: 0 0 0 0 rgba(225, 29, 72, 0);
                }
            }
        </style>

        <!-- ENCABEZADO DISCRETO Y CORPORATIVO -->
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2 border-bottom pb-3">
            <div>
                <h4 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-bar-graph text-primary"></i>
                    Generador y Filtro de Reportes
                </h4>
                <p class="text-muted small mb-0">Consulta consolidada de diagnósticos, estado de muestras y trazabilidad de
                    pacientes.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-light text-secondary border px-3 py-2 fw-semibold">
                    Total Registros: <strong class="text-dark">{{ $examenes->count() }}</strong>
                </span>
            </div>
        </div>

        <!-- TARJETAS DE RESUMEN KPI (MÉTRICAS RÁPIDAS) -->
        @php
            $totalCriticos = $examenes->where('estado', 'INFORMADO RESULTADO CRÍTICO')->count();
            $totalInformados = $examenes->where('estado', 'INFORMADO')->count();
            $totalEspera = $examenes->where('estado', 'EN ESPERA INFORME COMPLEMENTARIO')->count();
            $totalPendientes = $examenes->where('estado', 'PENDIENTE')->count();
        @endphp
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="stat-card p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-bold d-block">INFORMADOS</span>
                        <h4 class="fw-bold text-success mb-0">{{ $totalInformados }}</h4>
                    </div>
                    <div class="bg-success bg-opacity-10 p-2.5 rounded-3 text-success">
                        <i class="bi bi-check2-circle fs-5"></i>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="stat-card p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-bold d-block">CRÍTICOS</span>
                        <h4 class="fw-bold text-danger mb-0">{{ $totalCriticos }}</h4>
                    </div>
                    <div class="bg-danger bg-opacity-10 p-2.5 rounded-3 text-danger">
                        <i class="bi bi-exclamation-triangle fs-5"></i>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="stat-card p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-bold d-block">EN ESPERA</span>
                        <h4 class="fw-bold text-warning mb-0">{{ $totalEspera }}</h4>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-2.5 rounded-3 text-warning">
                        <i class="bi bi-clock-history fs-5"></i>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="stat-card p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-bold d-block">PENDIENTES</span>
                        <h4 class="fw-bold text-secondary mb-0">{{ $totalPendientes }}</h4>
                    </div>
                    <div class="bg-secondary bg-opacity-10 p-2.5 rounded-3 text-secondary">
                        <i class="bi bi-hourglass-split fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- TARJETA DE FILTROS -->
        <div class="card-custom mb-4">
            <div class="p-3 px-4 border-bottom bg-light d-flex justify-content-between align-items-center">
                <div class="fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-funnel text-primary"></i>
                    Filtros de Búsqueda y Parámetros
                </div>
                @if (request()->anyFilled(['fecha_desde', 'fecha_hasta', 'patologo_id', 'laboratorio_id', 'tipo_examen_id', 'estado']))
                    <span
                        class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 small">
                        Filtros activos aplicados
                    </span>
                @endif
            </div>

            <div class="p-4">
                <form action="{{ route('reportes.index') }}" method="GET">
                    <div class="row g-3">

                        <!-- FECHA DESDE -->
                        <div class="col-md-3 form-floating-custom input-group-custom">
                            <label>
                                <i class="bi bi-calendar-event"></i>
                                F. Recepción (Desde)
                            </label>
                            <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}"
                                class="form-control">
                        </div>

                        <!-- FECHA HASTA -->
                        <div class="col-md-3 form-floating-custom input-group-custom">
                            <label>
                                <i class="bi bi-calendar-check"></i>
                                F. Recepción (Hasta)
                            </label>
                            <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}"
                                class="form-control">
                        </div>

                        <!-- PATÓLOGO -->
                        <div class="col-md-3 form-floating-custom input-group-custom">
                            <label>
                                <i class="bi bi-person-badge"></i>
                                Médico Patólogo
                            </label>
                            <select name="patologo_id" class="form-select">
                                <option value="">Todos los Patólogos</option>
                                @foreach ($patologos as $p)
                                    <option value="{{ $p->id }}"
                                        {{ request('patologo_id') == $p->id ? 'selected' : '' }}>
                                        Dr(a). {{ $p->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- LABORATORIO / CLIENTE -->
                        <div class="col-md-3 form-floating-custom input-group-custom">
                            <label>
                                <i class="bi bi-building"></i>
                                Cliente / Laboratorio
                            </label>
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
                        <div class="col-md-4 form-floating-custom input-group-custom">
                            <label>
                                <i class="bi bi-virus"></i>
                                Tipo de Examen
                            </label>
                            <select name="tipo_examen_id" class="form-select">
                                <option value="">Todos los Tipos de Examen</option>
                                @foreach ($tiposExamen as $t)
                                    <option value="{{ $t->id }}"
                                        {{ request('tipo_examen_id') == $t->id ? 'selected' : '' }}>
                                        {{ $t->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- ESTADO -->
                        <div class="col-md-4 form-floating-custom input-group-custom">
                            <label>
                                <i class="bi bi-tag"></i>
                                Estado del Informe
                            </label>
                            <select name="estado" class="form-select">
                                <option value="">Todos los Estados</option>
                                <option value="PENDIENTE" {{ request('estado') == 'PENDIENTE' ? 'selected' : '' }}>
                                    PENDIENTE</option>
                                <option value="EN ESPERA INFORME COMPLEMENTARIO"
                                    {{ request('estado') == 'EN ESPERA INFORME COMPLEMENTARIO' ? 'selected' : '' }}>EN
                                    ESPERA INFORME COMPLEMENTARIO</option>
                                <option value="INFORMADO RESULTADO CRÍTICO"
                                    {{ request('estado') == 'INFORMADO RESULTADO CRÍTICO' ? 'selected' : '' }}>INFORMADO
                                    RESULTADO CRÍTICO</option>
                                <option value="INFORMADO" {{ request('estado') == 'INFORMADO' ? 'selected' : '' }}>
                                    INFORMADO</option>
                            </select>
                        </div>

                        <!-- BOTONES DE ACCIÓN -->
                        <div class="col-md-4 d-flex align-items-end gap-2">
                            <button type="submit"
                                class="btn btn-primary fw-semibold px-3 py-2 flex-grow-1 d-flex align-items-center justify-content-center gap-2 shadow-sm">
                                <i class="bi bi-search"></i>
                                Filtrar
                            </button>

                            <button type="submit" name="exportar" value="csv"
                                class="btn btn-success fw-semibold px-3 py-2 d-flex align-items-center gap-1.5 shadow-sm"
                                title="Descargar como CSV / Excel">
                                <i class="bi bi-file-earmark-excel"></i>
                                Exportar
                            </button>

                            <a href="{{ route('reportes.index') }}" class="btn btn-outline-secondary px-3 py-2"
                                title="Limpiar todos los filtros">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- RESULTADO DEL REPORTE / TABLA -->
        <div class="card-custom">
            <div class="p-3 px-4 border-bottom bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <span class="fw-bold text-dark fs-6">Detalle Consolidado del Reporte</span>
                    <span class="text-muted small ms-2">({{ $examenes->count() }} exámenes listados)</span>
                </div>
                <div class="text-muted small">
                    Ordenado por fecha de recepción más reciente
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-custom table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">N° Correlativo</th>
                            <th>F. Recepción</th>
                            <th>Paciente</th>
                            <th>RUT</th>
                            <th>Laboratorio</th>
                            <th>Patólogo Asignado</th>
                            <th>Tipo Examen</th>
                            <th class="text-center">Estado</th>
                            <th class="text-end pe-4">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($examenes as $ex)
                            <tr>
                                <!-- CORRELATIVO -->
                                <td class="ps-4">
                                    <span class="fw-bold text-primary font-monospace bg-primary-subtle px-2 py-1 rounded">
                                        {{ $ex->numero_correlativo }}
                                    </span>
                                </td>

                                <!-- FECHA -->
                                <td class="text-secondary small">
                                    {{ \Carbon\Carbon::parse($ex->fecha_recepcion)->format('d/m/Y') }}
                                </td>

                                <!-- PACIENTE (DIRECTO, SIN AVATAR) -->
                                <td>
                                    <div class="fw-semibold text-dark">
                                        {{ $ex->paciente_nombre }}
                                    </div>
                                </td>

                                <!-- RUT -->
                                <td>
                                    <span class="badge bg-light text-secondary border font-monospace px-2 py-1">
                                        {{ $ex->paciente_rut }}
                                    </span>
                                </td>

                                <!-- LABORATORIO -->
                                <td class="text-secondary">
                                    {{ $ex->laboratorio->nombre ?? 'N/A' }}
                                </td>

                                <!-- PATÓLOGO -->
                                <td>
                                    @if ($ex->patologo)
                                        <span class="text-dark fw-medium">
                                            {{ $ex->patologo->name }}
                                        </span>
                                    @else
                                        <span class="badge bg-light text-muted border border-dashed px-2 py-1">
                                            Sin asignar
                                        </span>
                                    @endif
                                </td>

                                <!-- TIPO DE EXAMEN -->
                                <td>
                                    <span
                                        class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2 py-1">
                                        {{ $ex->tipoExamen->nombre ?? 'N/A' }}
                                    </span>
                                </td>

                                <!-- ESTADO -->
                                <td class="text-center">
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

                                <!-- ACCIONES -->
                                <td class="text-end pe-4">
                                    <a href="{{ route('examenes.show', $ex->id) }}" target="_blank"
                                        class="btn btn-sm btn-outline-primary fw-semibold px-2.5 py-1 d-inline-flex align-items-center gap-1">
                                        <i class="bi bi-eye"></i>
                                        Ver
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="py-4">
                                        <div class="mb-3 text-muted">
                                            <i class="bi bi-folder-x fs-1 opacity-25"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark">No se encontraron exámenes coincidentes</h6>
                                        <p class="text-muted small mb-3">Prueba ajustando el rango de fechas o limpiando
                                            los filtros seleccionados.</p>
                                        <a href="{{ route('reportes.index') }}"
                                            class="btn btn-sm btn-outline-primary px-3">
                                            Restablecer Filtros
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINACIÓN AUTOMÁTICA -->
            @if (method_exists($examenes, 'links') && $examenes->hasPages())
                <div class="p-3 border-top bg-light d-flex justify-content-center">
                    {{ $examenes->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
