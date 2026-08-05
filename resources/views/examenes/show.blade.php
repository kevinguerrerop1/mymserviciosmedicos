@extends('layouts.app')

@section('content')

<style>
    .trazabilidad-card {
        border-radius: 12px;
        background: #ffffff;
    }
    
    .timeline-track {
        position: relative;
        padding-left: 28px;
    }
    .timeline-track::before {
        content: '';
        position: absolute;
        top: 14px;
        bottom: 14px;
        left: 13px;
        width: 2px;
        background: #e9ecef;
    }
    .timeline-step {
        position: relative;
        padding-bottom: 1.5rem;
    }
    .timeline-step:last-child {
        padding-bottom: 0;
    }
    .timeline-icon {
        position: absolute;
        left: -28px;
        top: 2px;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 700;
        z-index: 2;
        box-shadow: 0 0 0 4px #ffffff;
    }
    .timeline-icon-latest {
        background-color: #0d6efd;
        color: #ffffff;
        box-shadow: 0 0 0 4px #ffffff, 0 0 0 6px rgba(13, 110, 253, 0.25);
        animation: pulse-ring 2s infinite;
    }
    .timeline-icon-history {
        background-color: #e9ecef;
        color: #6c757d;
    }
    .timeline-content-card {
        border-radius: 10px;
        background-color: #f8f9fa;
        border: 1px solid #edf2f7;
        transition: all 0.2s ease-in-out;
    }
    .timeline-content-card:hover {
        background-color: #ffffff;
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    .timeline-step:first-child .timeline-content-card {
        background-color: #f0f7ff;
        border-color: #bae6fd;
    }
    @keyframes pulse-ring {
        0% {
            box-shadow: 0 0 0 4px #ffffff, 0 0 0 6px rgba(13, 110, 253, 0.3);
        }
        50% {
            box-shadow: 0 0 0 4px #ffffff, 0 0 0 9px rgba(13, 110, 253, 0.1);
        }
        100% {
            box-shadow: 0 0 0 4px #ffffff, 0 0 0 6px rgba(13, 110, 253, 0.3);
        }
    }
</style>

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
        @if(in_array($examen->estado, ['INFORMADO', 'INFORMADO RESULTADO CRÍTICO']))
            <!-- ESTADO FINALIZADO / BLOQUEADO -->
            <div class="card border-success shadow-sm">
                <div class="card-header bg-success text-white font-weight-bold d-flex justify-content-between align-items-center">
                    <span>🔒 Registro Finalizado</span>
                    <span class="badge bg-light text-success fs-6">{{ $examen->estado }}</span>
                </div>
                <div class="card-body bg-white text-center py-3">
                    <p class="mb-2 text-muted small">
                        Este examen ya ha sido marcado como <strong>{{ $examen->estado }}</strong>. El expediente se encuentra bloqueado para modificaciones médicas.
                    </p>

                    @hasrole('admin')
                        <button type="button" class="btn btn-outline-danger btn-sm mt-1 fw-bold" data-bs-toggle="modal" data-bs-target="#modalReabrirExamen">
                            🔓 Reabrir Expediente (Solo Administrador)
                        </button>
                    @endhasrole
                </div>
            </div>
        @else
            <!-- FORMULARIO DE GESTIÓN ACTIVO -->
            <div class="card border-warning shadow-sm">
                <div class="card-header bg-warning text-dark font-weight-bold">
                    ⚙️ Gestión Diagnóstica (Patólogo / Administrador)
                </div>
                <div class="card-body bg-white">
                    <form action="{{ route('examenes.update', $examen->id) }}" method="POST" enctype="multipart/form-data" class="row g-3">
                        @csrf
                        @method('PUT')

                        @php
                            $tieneInforme = !empty($examen->archivo_informe);
                        @endphp

                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">CAMBIAR ESTADO</label>
                            <select name="estado" id="selectEstado" class="form-select">
                                <option value="PENDIENTE" {{ $examen->estado == 'PENDIENTE' ? 'selected' : '' }}>PENDIENTE</option>
                                <option value="EN ESPERA INFORME COMPLEMENTARIO" {{ $examen->estado == 'EN ESPERA INFORME COMPLEMENTARIO' ? 'selected' : '' }}>EN ESPERA INFORME COMPLEMENTARIO</option>
                                
                                <option value="INFORMADO RESULTADO CRÍTICO" 
                                        {{ $examen->estado == 'INFORMADO RESULTADO CRÍTICO' ? 'selected' : '' }}
                                        {{ !$tieneInforme ? 'disabled' : '' }}
                                        class="opcion-requiere-pdf">
                                    INFORMADO RESULTADO CRÍTICO {{ !$tieneInforme ? '(Requiere PDF)' : '' }}
                                </option>
                                
                                <option value="INFORMADO" 
                                        {{ $examen->estado == 'INFORMADO' ? 'selected' : '' }}
                                        {{ !$tieneInforme ? 'disabled' : '' }}
                                        class="opcion-requiere-pdf">
                                    INFORMADO {{ !$tieneInforme ? '(Requiere PDF)' : '' }}
                                </option>
                            </select>
                            
                            @if(!$tieneInforme)
                                <div class="form-text text-danger mt-1" id="alertaPdf" style="font-size: 0.75rem;">
                                    ⚠️ Debe adjuntar un informe PDF para habilitar los estados finalizados.
                                </div>
                            @endif
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">ADJUNTAR INFORME (PDF)</label>
                            <input type="file" name="archivo_informe" id="inputPdf" class="form-control" accept=".pdf">
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
        @endif
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

    <!-- TARJETA PRINCIPAL: TRAZABILIDAD Y CAMBIOS DE ESTADO -->
    <div class="col-12">
        <div class="card shadow-sm border-0 trazabilidad-card overflow-hidden">
            <!-- CABECERA CON CONTRASTE ALTO -->
            <div class="card-header bg-primary text-white py-3 px-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <span class="fs-5">📍</span>
                    <div>
                        <h6 class="mb-0 fw-bold text-white text-uppercase tracking-wide" style="letter-spacing: 0.5px;">
                            Trazabilidad e Historial de Estado
                        </h6>
                        <small class="text-white-50 d-block" style="font-size: 0.78rem;">
                            Registro cronológico del flujo de ingreso y cambios del sistema
                        </small>
                    </div>
                </div>
                @php
                    $notasManuales = $examen->comentarios->where('tipo', 'nota');
                    $eventosSistema = $examen->comentarios->where('tipo', 'sistema');
                @endphp
                <!-- BOTÓN BLANCO LEGIBLE Y DESTACADO -->
                <button type="button" class="btn btn-sm btn-light fw-bold text-primary shadow-sm rounded-pill px-3 py-1.5 d-flex align-items-center gap-1.5" data-bs-toggle="modal" data-bs-target="#modalNotasExamen">
                    <span>💬 Ver Comentarios / Notas</span>
                    @if($notasManuales->count() > 0)
                        <span class="badge bg-danger text-white rounded-pill ms-1" style="font-size: 0.75rem;">{{ $notasManuales->count() }}</span>
                    @endif
                </button>
            </div>
            <!-- CUERPO: EVENTOS DE SISTEMA -->
            <div class="card-body p-4">
                @if($eventosSistema->count() > 0)
                    <div class="timeline-track">
                        @foreach($eventosSistema as $hito)
                            <div class="timeline-step">
                                <!-- NODO DE HISTORIAL -->
                                <div class="timeline-icon {{ $loop->first ? 'timeline-icon-latest' : 'timeline-icon-history' }}">
                                    @if($loop->first)
                                        ✓
                                    @else
                                        •
                                    @endif
                                </div>
                                <!-- TARJETA DEL HITO -->
                                <div class="timeline-content-card p-3">
                                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-1">
                                        
                                        <!-- USUARIO / EMISOR -->
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge {{ $loop->first ? 'bg-primary text-white' : 'bg-secondary-subtle text-dark border' }} rounded-pill px-2.5 py-1 font-monospace fw-semibold" style="font-size: 0.75rem;">
                                                👤 {{ $hito->user->name ?? 'Sistema' }}
                                            </span>
                                            @if($loop->first)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill" style="font-size: 0.7rem;">
                                                    Estado Actual
                                                </span>
                                            @endif
                                        </div>
                                        <!-- TIMESTAMP -->
                                        <div class="text-muted d-flex align-items-center gap-1" style="font-size: 0.78rem;">
                                            <span class="fw-bold text-dark">{{ $hito->created_at->format('d/m/Y') }}</span>
                                            <span>a las</span>
                                            <span class="fw-bold text-dark">{{ $hito->created_at->format('H:i') }} hrs</span>
                                            <span class="text-muted ps-1">({{ $hito->created_at->diffForHumans() }})</span>
                                        </div>
                                    </div>
                                    <!-- DESCRIPCIÓN DEL EVENTO -->
                                    <p class="mb-0 text-dark font-semibold text-break mt-1" style="line-height: 1.45; font-size: 0.88rem;">
                                        {{ $hito->comentario }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <p class="mb-0 small fw-semibold">No hay registros de cambios de estado ni trazabilidad aún.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- MODAL: HISTORIAL Y REGISTRO DE COMENTARIOS / NOTAS -->
    <div class="modal fade" id="modalNotasExamen" tabindex="-1" aria-labelledby="modalNotasExamenLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold text-dark d-flex align-items-center" id="modalNotasExamenLabel">
                        <span class="me-2">💬</span> Comentarios y Observaciones del Examen
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body bg-white p-4">
                    <div class="mb-4">
                        <h6 class="fw-bold text-muted small text-uppercase mb-3">Historial de Observaciones</h6>
                        
                        @forelse($notasManuales as $nota)
                            <div class="p-3 bg-light rounded-3 mb-2 border border-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-dark small">{{ $nota->user->name ?? 'Usuario' }}</span>
                                    <span class="text-muted style-italic" style="font-size: 0.8rem;">
                                        {{ $nota->created_at->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                                <p class="mb-0 text-secondary small">{{ $nota->comentario }}</p>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4 bg-light rounded border border-dashed">
                                <p class="mb-0 small">No hay comentarios u observaciones registradas para este examen.</p>
                            </div>
                        @endforelse
                    </div>

                    <hr class="my-4 text-muted opacity-25">

                    <form action="{{ route('comentarios.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="examen_id" value="{{ $examen->id }}">
                        
                        <div class="mb-3">
                            <label for="comentario" class="form-label fw-bold small text-muted">NUEVA OBSERVACIÓN</label>
                            <textarea name="comentario" id="comentario" class="form-control" rows="3" placeholder="Escriba aquí cualquier aclaración sobre la muestra o notas del equipo médico..." required></textarea>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary btn-sm px-4 fw-bold">Guardar Comentario</button>
                        </div>
                    </form>
                </div>

                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>
    </div>

    <!-- MODAL DE REAPERTURA DE EXPEDIENTE (SOLO ADMIN) -->
    @hasrole('admin')
    <div class="modal fade" id="modalReabrirExamen" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold">⚠️ Reabrir Expediente Clínico</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('examenes.reabrir', $examen->id) }}" method="POST">
                    @csrf
                    <div class="modal-body bg-white">
                        <p class="small text-muted">
                            Esta acción devolverá el examen a estado <strong>EN ESPERA INFORME COMPLEMENTARIO</strong> para permitir rectificaciones, reemplazo de PDF o carga de imágenes.
                        </p>
                        <div class="mb-3">
                            <label for="motivo" class="form-label fw-bold small text-dark">MOTIVO O JUSTIFICACIÓN (Obligatorio para auditoría)</label>
                            <textarea name="motivo" id="motivo" class="form-control" rows="3" placeholder="Ej: Corregir informe adjunto / Reemplazar PDF por error tipográfico..." required minlength="10"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger btn-sm fw-bold">Confirmar Reapertura</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endhasrole

</div>

<!-- SCRIPT JS PARA HABILITAR DINÁMICAMENTE LOS ESTADOS AL ADJUNTAR UN PDF -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputPdf = document.getElementById('inputPdf');
    const opcionesRequierePdf = document.querySelectorAll('.opcion-requiere-pdf');
    const alertaPdf = document.getElementById('alertaPdf');

    if (inputPdf) {
        inputPdf.addEventListener('change', function () {
            if (this.files && this.files.length > 0) {
                opcionesRequierePdf.forEach(opcion => {
                    opcion.removeAttribute('disabled');
                    opcion.text = opcion.text.replace(' (Requiere PDF)', '');
                });
                if (alertaPdf) alertaPdf.style.display = 'none';
            }
        });
    }
});
</script>
@endsection