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
        <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
            
            <!-- CABECERA CON BOTÓN A MODAL -->
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                <div class="d-flex align-items-center">
                    <span class="fs-5 me-2">📍</span>
                    <h6 class="mb-0 fw-bold text-uppercase tracking-wide">Línea de Tiempo y Historial de Estado</h6>
                </div>

                @php
                    $notasManuales = $examen->comentarios->where('tipo', 'nota');
                @endphp

                <button type="button" class="btn btn-sm btn-light fw-bold text-primary shadow-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalNotasExamen">
                    💬 Ver Comentarios / Notas
                    @if($notasManuales->count() > 0)
                        <span class="badge bg-danger ms-1 rounded-circle">{{ $notasManuales->count() }}</span>
                    @endif
                </button>
            </div>

            <!-- CUERPO: EVENTOS DE SISTEMA -->
            <div class="card-body bg-white p-4">
                <div class="timeline">
                    @forelse($examen->comentarios->where('tipo', 'sistema') as $index => $hito)
                        <div class="timeline-item">
                            <div class="timeline-marker {{ $loop->first ? 'bg-success' : 'bg-primary' }}"></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold {{ $loop->first ? 'text-success' : 'text-primary' }}">
                                        {{ $hito->user->name ?? 'Sistema' }}
                                    </span>
                                    <small class="text-muted fw-bold">
                                        {{ $hito->created_at->format('d/m/Y H:i') }} hrs 
                                        <span class="fw-normal">({{ $hito->created_at->diffForHumans() }})</span>
                                    </small>
                                </div>
                                <p class="mb-0 text-dark small mt-1 fw-semibold">{{ $hito->comentario }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-3">
                            <small>No hay registros de cambios de estado ni trazabilidad aún.</small>
                        </div>
                    @endforelse
                </div>
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