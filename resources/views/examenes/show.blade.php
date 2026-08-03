@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="text-muted small fw-bold">📋 FICHA DE TRAZABILIDAD Y DIAGNÓSTICO PATOLÓGICO</div>
    <a href="{{ route('examenes.index') }}" class="btn btn-sm btn-outline-secondary">← Volver a Búsqueda</a>
</div>

<div class="row g-4">
    <!-- Tabla de Información del Examen -->
    <div class="col-md-7">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-secondary text-white text-center font-weight-bold">
                INFORMACIÓN DEL EXAMEN (CORRELATIVO: #{{ $examen->numero_correlativo }})
            </div>
            <div class="card-body p-0 bg-white">
                <table class="table table-bordered mb-0 align-middle">
                    <tbody>
                        <tr>
                            <th class="bg-light text-secondary" style="width: 45%;">PACIENTE</th>
                            <td class="fw-bold text-dark">{{ $examen->paciente_nombre }} <code class="ms-1">({{ $examen->paciente_rut }})</code></td>
                        </tr>
                        <tr>
                            <th class="bg-light text-secondary">TIPO DE EXAMEN</th>
                            <td class="fw-bold text-primary">{{ $examen->tipoExamen->nombre ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light text-secondary">LABORATORIO ORIGEN</th>
                            <td>{{ $examen->laboratorio->nombre ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light text-secondary">FECHA RECEPCIÓN</th>
                            <td class="fw-bold">{{ \Carbon\Carbon::parse($examen->fecha_recepcion)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light text-secondary">MÉDICO SOLICITANTE</th>
                            <td class="text-dark">{{ $examen->medico_solicitante }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light text-secondary">MUESTRAS / FRAGMENTOS</th>
                            <td>
                                <span class="badge bg-secondary">{{ $examen->cantidad_muestras }} Muestra(s)</span>
                                @if($examen->numero_fragmentos)
                                    <span class="badge bg-light text-dark border ms-1">{{ $examen->numero_fragmentos }} Fragmento(s)</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light text-secondary">ESTADO ACTUAL</th>
                            <td>
                                @if($examen->estado == 'EN ESPERA INFORME COMPLEMENTARIO')
                                    <span class="badge-estado estado-espera">EN ESPERA INFORME COMPLEMENTARIO</span>
                                @elseif($examen->estado == 'INFORMADO RESULTADO CRÍTICO')
                                    <span class="badge-estado estado-critico">INFORMADO RESULTADO CRÍTICO</span>
                                @elseif($examen->estado == 'INFORMADO')
                                    <span class="badge-estado estado-informado">INFORMADO</span>
                                @else
                                    <span class="badge-estado estado-pendiente">PENDIENTE</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Galería de Imágenes -->
    <div class="col-md-5">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-secondary text-white text-center font-weight-bold">
                MUESTRAS MACRO / MICROSCÓPICAS
            </div>
            <div class="card-body bg-white d-flex flex-wrap gap-2 justify-content-center align-items-center">
                @if($examen->galeria_imagenes && count($examen->galeria_imagenes) > 0)
                    @foreach($examen->galeria_imagenes as $img)
                        <a href="{{ asset('storage/' . $img) }}" target="_blank" class="shadow-sm rounded overflow-hidden border">
                            <img src="{{ asset('storage/' . $img) }}" style="width: 105px; height: 105px; object-fit: cover;">
                        </a>
                    @endforeach
                @else
                    <div class="text-center text-muted my-auto py-4">
                        <p class="mb-0 small">Sin fotografías adjuntas en el expediente.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Gestión del Patólogo / Admin -->
    @hasrole('patologo|admin')
    <div class="col-12">
        <div class="card border-warning shadow-sm">
            <div class="card-header bg-warning text-dark font-weight-bold">
                ⚙️ Gestión Diagnóstica (Patólogo / Administrador)
            </div>
            <div class="card-body bg-white">
                <form action="{{ route('examenes.update', $examen->id) }}" method="POST" enctype="multipart/form-data" class="row g-3">
                    @csrf
                    @method('PUT')
                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-muted">CAMBIAR ESTADO</label>
                        <select name="estado" class="form-select">
                            <option value="PENDIENTE" {{ $examen->estado == 'PENDIENTE' ? 'selected' : '' }}>PENDIENTE</option>
                            <option value="EN ESPERA INFORME COMPLEMENTARIO" {{ $examen->estado == 'EN ESPERA INFORME COMPLEMENTARIO' ? 'selected' : '' }}>EN ESPERA INFORME COMPLEMENTARIO</option>
                            <option value="INFORMADO RESULTADO CRÍTICO" {{ $examen->estado == 'INFORMADO RESULTADO CRÍTICO' ? 'selected' : '' }}>INFORMADO RESULTADO CRÍTICO</option>
                            <option value="INFORMADO" {{ $examen->estado == 'INFORMADO' ? 'selected' : '' }}>INFORMADO</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-muted">ADJUNTAR INFORME (PDF)</label>
                        <input type="file" name="archivo_informe" class="form-control" accept=".pdf">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-muted">SUBIR IMÁGENES MUESTRA</label>
                        <input type="file" name="imagenes[]" class="form-control" multiple accept="image/*">
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-warning fw-bold px-4">Guardar Cambios y Notificar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endhasrole

    <!-- Descarga de Informe Oficial -->
    <div class="col-12 text-center">
        <div class="p-3 bg-white border rounded shadow-sm">
            @if($examen->archivo_informe)
                <a href="{{ route('examenes.descargar', $examen->id) }}" class="btn btn-success btn-lg w-100 py-3 fw-bold">
                    📄 Descargar Informe Diagnóstico Oficial (PDF)
                </a>
            @else
                <button class="btn btn-light text-muted btn-lg w-100 py-3 border" disabled>
                    ⏳ Informe Diagnóstico aún no disponible
                </button>
            @endif
        </div>
    </div>

    <!-- LÍNEA DE TIEMPO Y TRAZABILIDAD -->
    <!-- LÍNEA DE TIEMPO Y TRAZABILIDAD -->
<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white font-weight-bold">
            📍 Línea de Tiempo y Trazabilidad del Examen
        </div>
        <div class="card-body bg-white p-4">
            <div class="timeline">
                
                @forelse($examen->comentarios as $index => $hito)
                    <div class="timeline-item">
                        <!-- El primer evento (creación) resalta en verde -->
                        <div class="timeline-marker {{ $index === 0 ? 'bg-success' : '' }}"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold {{ $index === 0 ? 'text-dark' : 'text-primary' }}">
                                    {{ $hito->user->name }}
                                </span>
                                <small class="text-muted">
                                    {{ $hito->created_at->format('d/m/Y H:i') }} ({{ $hito->created_at->diffForHumans() }})
                                </small>
                            </div>
                            <p class="mb-0 text-dark small mt-1">{{ $hito->comentario }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-2">
                        <small>Sin historial de trazabilidad registrado.</small>
                    </div>
                @endforelse

            </div>
        </div>
    </div>
</div>

    <!-- Sección opcional para Agregar Comentarios Manuales -->
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light text-dark fw-bold">
                💬 Agregar Observación o Nota Aclaratoria
            </div>
            <div class="card-body bg-white">
                <form action="{{ route('comentarios.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="examen_id" value="{{ $examen->id }}">
                    <div class="mb-2">
                        <textarea name="comentario" class="form-control" rows="2" placeholder="Escribe una observación pública..." required></textarea>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary btn-sm px-3">Publicar Nota</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection