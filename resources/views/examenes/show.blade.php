@extends('layouts.app')

@section('content')

    <!-- ESTILOS VISUALES AVANZADOS PARA LA FICHA Y LÍNEA DE TIEMPO -->
    <style>
        .trazabilidad-card {
            border-radius: 14px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
        }

        /* TIMELINE TRACK */
        .timeline-track {
            position: relative;
            padding-left: 36px;
        }

        .timeline-track::before {
            content: '';
            position: absolute;
            top: 15px;
            bottom: 25px;
            left: 17px;
            width: 2px;
            background: #cbd5e1;
        }

        .timeline-step {
            position: relative;
            padding-bottom: 1.75rem;
        }

        .timeline-step:last-child {
            padding-bottom: 0;
        }

        .timeline-icon {
            position: absolute;
            left: -36px;
            top: 2px;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            z-index: 2;
            box-shadow: 0 0 0 4px #ffffff;
        }

        .timeline-icon-latest {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #ffffff;
            box-shadow: 0 0 0 4px #ffffff, 0 0 0 7px rgba(37, 99, 235, 0.25);
            animation: pulse-ring 2.2s infinite;
        }

        .timeline-icon-history {
            background-color: #f1f5f9;
            color: #64748b;
            border: 2px solid #cbd5e1;
        }

        .timeline-content-card {
            border-radius: 12px;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            transition: all 0.2s ease-in-out;
        }

        .timeline-content-card:hover {
            border-color: #94a3b8;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
            transform: translateY(-1px);
        }

        .timeline-step:first-child .timeline-content-card {
            background-color: #f8fafc;
            border-left: 4px solid #2563eb;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.06);
        }

        @keyframes pulse-ring {
            0% {
                box-shadow: 0 0 0 4px #ffffff, 0 0 0 6px rgba(37, 99, 235, 0.35);
            }

            50% {
                box-shadow: 0 0 0 4px #ffffff, 0 0 0 11px rgba(37, 99, 235, 0.1);
            }

            100% {
                box-shadow: 0 0 0 4px #ffffff, 0 0 0 6px rgba(37, 99, 235, 0.35);
            }
        }

        /* BADGES DE ESTADO MODERNOS */
        .badge-estado {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            font-size: 0.75rem;
            font-weight: 700;
            border-radius: 30px;
            letter-spacing: 0.4px;
        }

        .badge-estado::before {
            content: '';
            width: 7px;
            height: 7px;
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

        /* GALERÍA DE IMÁGENES */
        .sample-thumb {
            width: 110px;
            height: 110px;
            object-fit: cover;
            border-radius: 10px;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .sample-thumb:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.15);
        }

        /* TARJETAS GENERALES */
        .card-custom {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            background: #ffffff;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }

        .info-label {
            font-size: 0.72rem;
            text-transform: uppercase;
            font-weight: 700;
            color: #64748b;
            letter-spacing: 0.5px;
        }
    </style>

    <div class="container-fluid py-3 px-md-4">

        <!-- BARRA SUPERIOR DE ACCIONES -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
            <div class="d-flex align-items-center gap-2">
                <span
                    class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1.5 fw-bold rounded-pill">
                    Expediente Clínico
                </span>
                <span class="text-secondary small fw-semibold">| Módulo de Anatomía Patológica</span>
            </div>
            <a href="{{ route('examenes.index') }}"
                class="btn btn-sm btn-outline-secondary px-3 py-1.5 rounded-pill shadow-sm d-inline-flex align-items-center gap-1.5 fw-semibold">
                <svg width="15" height="15" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                </svg>
                Volver a Búsqueda
            </a>
        </div>

        <div class="row g-4">

            <!-- COLUMNA 1: FICHA DE INFORMACIÓN DEL EXAMEN -->
            <div class="col-lg-7">
                <div class="card-custom h-100">
                    <!-- CABECERA -->
                    <div class="p-3 px-4 bg-light border-bottom d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <span class="fs-5">📋</span>
                            <span class="fw-bold text-dark">Información del Examen</span>
                        </div>
                        <span class="badge bg-primary font-monospace fs-6 px-3 py-1 rounded-pill shadow-sm">
                            #{{ $examen->numero_correlativo }}
                        </span>
                    </div>

                    <!-- DATOS DEL EXAMEN -->
                    <div class="p-4">
                        <!-- PERFIL DEL PACIENTE -->
                        <div
                            class="p-3 mb-4 rounded-3 bg-light border border-slate-200 d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <span class="info-label d-block mb-1">PACIENTE ASOCIADO</span>
                                <div class="h5 fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                                    {{ $examen->paciente_nombre }}
                                </div>
                            </div>
                            <div>
                                <span class="badge bg-white text-dark border font-monospace px-3 py-2 fs-6 shadow-sm">
                                    RUT: {{ $examen->paciente_rut }}
                                </span>
                            </div>
                        </div>

                        <!-- TABLA DE DETALLES -->
                        <div class="table-responsive">
                            <table class="table table-borderless align-middle mb-0">
                                <tbody>
                                    <tr class="border-bottom">
                                        <td class="info-label py-2.5" style="width: 38%;">
                                            <span class="d-flex align-items-center gap-1.5">🔬 Tipo de Examen</span>
                                        </td>
                                        <td class="fw-bold text-primary py-2.5">
                                            {{ $examen->tipoExamen->nombre ?? 'N/A' }}
                                        </td>
                                    </tr>

                                    <tr class="border-bottom">
                                        <td class="info-label py-2.5">
                                            <span class="d-flex align-items-center gap-1.5">🏥 Laboratorio Origen</span>
                                        </td>
                                        <td class="text-dark fw-semibold py-2.5">
                                            {{ $examen->laboratorio->nombre ?? 'N/A' }}
                                        </td>
                                    </tr>

                                    <tr class="border-bottom">
                                        <td class="info-label py-2.5">
                                            <span class="d-flex align-items-center gap-1.5">📅 Fecha Recepción</span>
                                        </td>
                                        <td class="fw-semibold text-secondary py-2.5">
                                            {{ \Carbon\Carbon::parse($examen->fecha_recepcion)->format('d/m/Y') }}
                                        </td>
                                    </tr>

                                    <tr class="border-bottom">
                                        <td class="info-label py-2.5">
                                            <span class="d-flex align-items-center gap-1.5">👨‍⚕️ Médico Solicitante</span>
                                        </td>
                                        <td class="text-dark py-2.5">
                                            {{ $examen->medico_solicitante ?? 'Sin especificar' }}
                                        </td>
                                    </tr>

                                    <tr class="border-bottom">
                                        <td class="info-label py-2.5">
                                            <span class="d-flex align-items-center gap-1.5">🧪 Muestras / Fragmentos</span>
                                        </td>
                                        <td class="py-2.5">
                                            <span
                                                class="badge bg-secondary-subtle text-secondary-emphasis border px-2.5 py-1.5 rounded-pill">
                                                {{ $examen->cantidad_muestras }} Muestra(s)
                                            </span>
                                            @if ($examen->numero_fragmentos)
                                                <span
                                                    class="badge bg-light text-dark border px-2.5 py-1.5 rounded-pill ms-1">
                                                    {{ $examen->numero_fragmentos }} Fragmento(s)
                                                </span>
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="info-label py-2.5">
                                            <span class="d-flex align-items-center gap-1.5">⚡ Estado Actual</span>
                                        </td>
                                        <td class="py-2.5">
                                            @if ($examen->estado == 'EN ESPERA INFORME COMPLEMENTARIO')
                                                <span class="badge-estado estado-espera">EN ESPERA INFORME
                                                    COMPLEMENTARIO</span>
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
            </div>

            <!-- COLUMNA 2: GALERÍA DE MUESTRAS MACRO / MICROSCÓPICAS -->
            <div class="col-lg-5">
                <div class="card-custom h-100">
                    <div class="p-3 px-4 bg-light border-bottom d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <span class="fs-5">📸</span>
                            <span class="fw-bold text-dark">Muestras Macro / Micro</span>
                        </div>
                        <span class="badge bg-light text-dark border rounded-pill px-2.5 py-1 small">
                            {{ $examen->galeria_imagenes ? count($examen->galeria_imagenes) : 0 }} Imágenes
                        </span>
                    </div>

                    <div class="p-4 d-flex flex-wrap gap-2.5 justify-content-center align-items-center"
                        style="min-height: 250px;">
                        @if ($examen->galeria_imagenes && count($examen->galeria_imagenes) > 0)
                            @foreach ($examen->galeria_imagenes as $img)
                                <a href="{{ asset('storage/' . $img) }}" target="_blank"
                                    class="rounded-3 overflow-hidden border p-1 bg-white shadow-sm d-inline-block position-relative"
                                    title="Clic para ampliar imagen">
                                    <img src="{{ asset('storage/' . $img) }}" class="sample-thumb">
                                </a>
                            @endforeach
                        @else
                            <div class="text-center text-muted py-5">
                                <svg width="42" height="42" fill="currentColor" class="opacity-25 mb-2"
                                    viewBox="0 0 16 16">
                                    <path d="M10.5 8.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z" />
                                    <path
                                        d="M2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4H2zm.5 2a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1zm9 2.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0z" />
                                </svg>
                                <p class="mb-0 small text-secondary">Sin fotografías macro o microscópicas adjuntas en el
                                    expediente.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- GESTIÓN DEL PATÓLOGO / ADMIN -->
            @hasrole('patologo|admin')
                <div class="col-12">
                    @if (in_array($examen->estado, ['INFORMADO', 'INFORMADO RESULTADO CRÍTICO']))
                        <!-- ESTADO FINALIZADO / BLOQUEADO -->
                        <div class="card-custom border-start border-4 border-success p-4">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-success bg-opacity-10 text-success p-3 rounded-circle">
                                        <svg width="28" height="28" fill="currentColor" viewBox="0 0 16 16">
                                            <path
                                                d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center gap-2">
                                            <h5 class="fw-bold text-dark mb-0">Expediente Clínico Finalizado</h5>
                                            <span class="badge-estado estado-informado">{{ $examen->estado }}</span>
                                        </div>
                                        <p class="mb-0 text-muted small mt-1">
                                            Este examen ya ha sido marcado como <strong>{{ $examen->estado }}</strong> y se
                                            encuentra bloqueado para resguardar la validez jurídica del informe.
                                        </p>
                                    </div>
                                </div>

                                @hasrole('admin')
                                    <div>
                                        <button type="button"
                                            class="btn btn-outline-danger btn-sm px-3 py-2 fw-semibold shadow-sm d-flex align-items-center gap-2"
                                            data-bs-toggle="modal" data-bs-target="#modalReabrirExamen">
                                            <svg width="15" height="15" fill="currentColor" viewBox="0 0 16 16">
                                                <path
                                                    d="M11 1a2 2 0 0 0-2 2v4a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h5V3a3 3 0 0 1 6 0v4a.5.5 0 0 1-1 0V3a2 2 0 0 0-2-2z" />
                                            </svg>
                                            Reabrir Expediente (Admin)
                                        </button>
                                    </div>
                                @endhasrole
                            </div>
                        </div>
                    @else
                        <!-- FORMULARIO DE GESTIÓN ACTIVO -->
                        <div class="card-custom border-start border-4 border-warning">
                            <div class="p-3 px-4 bg-light border-bottom d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-dark d-flex align-items-center gap-2">
                                    ⚙️ Gestión Diagnóstica (Patólogo / Administrador)
                                </span>
                                <span
                                    class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-2.5 py-1 small">
                                    En Edición Activa
                                </span>
                            </div>

                            <div class="p-4">
                                <form action="{{ route('examenes.update', $examen->id) }}" method="POST"
                                    enctype="multipart/form-data" class="row g-3">
                                    @csrf
                                    @method('PUT')

                                    @php
                                        $tieneInforme = !empty($examen->archivo_informe);
                                    @endphp

                                    <div class="col-md-4">
                                        <label class="info-label mb-2 d-block">Cambiar Estado</label>
                                        <select name="estado" id="selectEstado" class="form-select rounded-3 shadow-sm">
                                            <option value="PENDIENTE" {{ $examen->estado == 'PENDIENTE' ? 'selected' : '' }}>
                                                PENDIENTE
                                            </option>
                                            <option value="EN ESPERA INFORME COMPLEMENTARIO"
                                                {{ $examen->estado == 'EN ESPERA INFORME COMPLEMENTARIO' ? 'selected' : '' }}>
                                                EN ESPERA INFORME COMPLEMENTARIO
                                            </option>
                                            <option value="INFORMADO RESULTADO CRÍTICO"
                                                {{ $examen->estado == 'INFORMADO RESULTADO CRÍTICO' ? 'selected' : '' }}
                                                {{ !$tieneInforme ? 'disabled' : '' }} class="opcion-requiere-pdf">
                                                INFORMADO RESULTADO CRÍTICO {{ !$tieneInforme ? '(Requiere PDF)' : '' }}
                                            </option>
                                            <option value="INFORMADO" {{ $examen->estado == 'INFORMADO' ? 'selected' : '' }}
                                                {{ !$tieneInforme ? 'disabled' : '' }} class="opcion-requiere-pdf">
                                                INFORMADO {{ !$tieneInforme ? '(Requiere PDF)' : '' }}
                                            </option>
                                        </select>

                                        @if (!$tieneInforme)
                                            <div class="form-text text-danger mt-1 small d-flex align-items-center gap-1"
                                                id="alertaPdf">
                                                ⚠️ Debe adjuntar un informe PDF para habilitar estados finalizados.
                                            </div>
                                        @endif
                                    </div>

                                    <div class="col-md-4">
                                        <label class="info-label mb-2 d-block">Adjuntar Informe (PDF)</label>
                                        <input type="file" name="archivo_informe" id="inputPdf"
                                            class="form-control rounded-3 shadow-sm" accept=".pdf">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="info-label mb-2 d-block">Subir Imágenes Muestra</label>
                                        <input type="file" name="imagenes[]" class="form-control rounded-3 shadow-sm"
                                            multiple accept="image/*">
                                    </div>

                                    <div class="col-12 text-end pt-2">
                                        <button type="submit"
                                            class="btn btn-warning fw-bold px-4 py-2 rounded-pill shadow-sm">
                                            💾 Guardar Cambios y Notificar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            @endhasrole

            <!-- BANNER DE DESCARGA DE INFORME OFICIAL -->
            <div class="col-12">
                @if ($examen->archivo_informe)
                    <div class="card-custom bg-gradient p-4 text-center border-success shadow-sm"
                        style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                            <div class="text-md-start">
                                <span class="badge bg-success px-3 py-1 rounded-pill mb-2">Documento Validado</span>
                                <h5 class="fw-bold text-success-emphasis mb-1">Informe Diagnóstico Oficial Disponible</h5>
                                <p class="text-muted small mb-0">El archivo PDF se encuentra registrado en el sistema y
                                    listo para descarga.</p>
                            </div>
                            <a href="{{ route('examenes.descargar', $examen->id) }}"
                                class="btn btn-success btn-lg px-4 py-2.5 rounded-pill fw-bold shadow-sm d-inline-flex align-items-center gap-2">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                    <path
                                        d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z" />
                                    <path
                                        d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z" />
                                </svg>
                                Descargar Informe Diagnóstico Oficial (PDF)
                            </a>
                        </div>
                    </div>
                @else
                    <div class="card-custom p-3 bg-light text-center border-dashed">
                        <div class="d-flex align-items-center justify-content-center gap-2 text-muted py-2">
                            <span class="fs-5">⏳</span>
                            <span class="fw-semibold small">Informe Diagnóstico Oficial aún no disponible para
                                descarga.</span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- TARJETA PRINCIPAL: TRAZABILIDAD Y CAMBIOS DE ESTADO (TIMELINE) -->
            <div class="col-12">
                <div class="card trazabilidad-card overflow-hidden">
                    <!-- CABECERA -->
                    <div
                        class="card-header bg-primary text-white py-3 px-4 d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="fs-4">📍</span>
                            <div>
                                <h6 class="mb-0 fw-bold text-white text-uppercase" style="letter-spacing: 0.5px;">
                                    Trazabilidad e Historial de Estado
                                </h6>
                                <small class="text-white-50 d-block" style="font-size: 0.78rem;">
                                    Registro cronológico inmutable del flujo de ingreso y cambios del sistema
                                </small>
                            </div>
                        </div>

                        @php
                            $notasManuales = $examen->comentarios->where('tipo', 'nota');
                            $eventosSistema = $examen->comentarios->where('tipo', 'sistema');
                        @endphp

                        <!-- BOTÓN MODAL COMENTARIOS -->
                        <button type="button"
                            class="btn btn-sm btn-light fw-bold text-primary shadow-sm rounded-pill px-3 py-2 d-flex align-items-center gap-2"
                            data-bs-toggle="modal" data-bs-target="#modalNotasExamen">
                            <svg width="15" height="15" fill="currentColor" viewBox="0 0 16 16">
                                <path
                                    d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z" />
                            </svg>
                            <span>Comentarios y Notas</span>
                            @if ($notasManuales->count() > 0)
                                <span class="badge bg-danger text-white rounded-pill ms-1" style="font-size: 0.72rem;">
                                    {{ $notasManuales->count() }}
                                </span>
                            @endif
                        </button>
                    </div>

                    <!-- CUERPO DE LA LÍNEA DE TIEMPO -->
                    <div class="card-body p-4 p-md-5">
                        @if ($eventosSistema->count() > 0)
                            <div class="timeline-track">
                                @foreach ($eventosSistema as $hito)
                                    <div class="timeline-step">
                                        <!-- NODO -->
                                        <div
                                            class="timeline-icon {{ $loop->first ? 'timeline-icon-latest' : 'timeline-icon-history' }}">
                                            @if ($loop->first)
                                                ✓
                                            @else
                                                •
                                            @endif
                                        </div>

                                        <!-- CONTENIDO DEL HITO -->
                                        <div class="timeline-content-card p-3 p-md-3.5">
                                            <div
                                                class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                                                <!-- EMISOR Y STATUS -->
                                                <div class="d-flex align-items-center gap-2">
                                                    <span
                                                        class="badge {{ $loop->first ? 'bg-primary text-white' : 'bg-light text-dark border' }} rounded-pill px-2.5 py-1 font-monospace fw-semibold"
                                                        style="font-size: 0.75rem;">
                                                        👤 {{ $hito->user->name ?? 'Sistema' }}
                                                    </span>

                                                    @if ($loop->first)
                                                        <span
                                                            class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-0.5"
                                                            style="font-size: 0.7rem;">
                                                            Estado Actual
                                                        </span>
                                                    @endif
                                                </div>

                                                <!-- TIMESTAMP -->
                                                <div class="text-secondary small d-flex align-items-center gap-1.5"
                                                    style="font-size: 0.8rem;">
                                                    <svg width="13" height="13" class="text-muted"
                                                        fill="currentColor" viewBox="0 0 16 16">
                                                        <path
                                                            d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z" />
                                                        <path
                                                            d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z" />
                                                    </svg>
                                                    <span
                                                        class="fw-bold text-dark">{{ $hito->created_at->format('d/m/Y') }}</span>
                                                    <span>{{ $hito->created_at->format('H:i') }} hrs</span>
                                                    <span
                                                        class="badge bg-light text-muted border ms-1 font-monospace">{{ $hito->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>

                                            <!-- MENSAJE DE EVENTO -->
                                            <p class="mb-0 text-dark fw-medium text-break"
                                                style="line-height: 1.5; font-size: 0.9rem;">
                                                {{ $hito->comentario }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-5">
                                <span class="fs-1 d-block mb-2">⏱️</span>
                                <h6 class="fw-bold text-dark">No hay registros de trazabilidad aún</h6>
                                <p class="mb-0 small text-secondary">Los cambios de estado y acciones médicas aparecerán
                                    listados aquí cronológicamente.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- MODAL: HISTORIAL Y REGISTRO DE COMENTARIOS / NOTAS -->
            <div class="modal fade" id="modalNotasExamen" tabindex="-1" aria-labelledby="modalNotasExamenLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content card-custom border-0 shadow">
                        <div class="modal-header bg-light border-bottom p-3 px-4">
                            <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2 fs-6"
                                id="modalNotasExamenLabel">
                                <span>💬</span> Comentarios y Observaciones del Examen
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="modal-body bg-white p-4">
                            <!-- LISTADO DE NOTAS -->
                            <div class="mb-4">
                                <span class="info-label mb-3 d-block">HISTORIAL DE OBSERVACIONES</span>

                                @forelse($notasManuales as $nota)
                                    <div class="p-3 bg-light rounded-3 mb-2 border border-slate-200">
                                        <div class="d-flex justify-content-between align-items-center mb-1.5">
                                            <span class="fw-bold text-dark small d-inline-flex align-items-center gap-1">
                                                👤 {{ $nota->user->name ?? 'Usuario' }}
                                            </span>
                                            <span class="text-muted small" style="font-size: 0.78rem;">
                                                {{ $nota->created_at->format('d/m/Y H:i') }}
                                            </span>
                                        </div>
                                        <p class="mb-0 text-secondary small" style="line-height: 1.45;">
                                            {{ $nota->comentario }}</p>
                                    </div>
                                @empty
                                    <div class="text-center text-muted py-4 bg-light rounded-3 border border-dashed">
                                        <p class="mb-0 small">No hay comentarios u observaciones registradas para este
                                            examen.</p>
                                    </div>
                                @endforelse
                            </div>

                            <hr class="my-4 text-muted opacity-25">

                            <!-- FORMULARIO DE NUEVA OBSERVACIÓN -->
                            <form action="{{ route('comentarios.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="examen_id" value="{{ $examen->id }}">

                                <div class="mb-3">
                                    <label for="comentario" class="info-label mb-2 d-block">Nueva Observación</label>
                                    <textarea name="comentario" id="comentario" class="form-control rounded-3" rows="3"
                                        placeholder="Escriba aquí cualquier aclaración sobre la muestra o notas del equipo médico..." required></textarea>
                                </div>

                                <div class="text-end">
                                    <button type="submit"
                                        class="btn btn-primary btn-sm px-4 py-2 fw-semibold rounded-pill shadow-sm">
                                        Guardar Comentario
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="modal-footer bg-light py-2 px-4 border-top">
                            <button type="button" class="btn btn-secondary btn-sm px-3 rounded-pill"
                                data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODAL DE REAPERTURA DE EXPEDIENTE (SOLO ADMIN) -->
            @hasrole('admin')
                <div class="modal fade" id="modalReabrirExamen" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content card-custom border-0 shadow">
                            <div class="modal-header bg-danger text-white p-3 px-4">
                                <h6 class="modal-title fw-bold d-flex align-items-center gap-2 mb-0">
                                    <span>⚠️</span> Reabrir Expediente Clínico
                                </h6>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('examenes.reabrir', $examen->id) }}" method="POST">
                                @csrf
                                <div class="modal-body bg-white p-4">
                                    <p class="small text-muted mb-3">
                                        Esta acción devolverá el examen a estado <strong>EN ESPERA INFORME
                                            COMPLEMENTARIO</strong> para permitir rectificaciones, reemplazo de PDF o carga de
                                        imágenes.
                                    </p>
                                    <div class="mb-3">
                                        <label for="motivo" class="info-label mb-2 d-block text-dark">
                                            Motivo o Justificación (Auditoría)
                                        </label>
                                        <textarea name="motivo" id="motivo" class="form-control rounded-3" rows="3"
                                            placeholder="Ej: Corregir informe adjunto / Reemplazar PDF por error tipográfico..." required minlength="10"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer bg-light py-2 px-4 border-top">
                                    <button type="button" class="btn btn-secondary btn-sm rounded-pill"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit"
                                        class="btn btn-danger btn-sm fw-bold px-3 rounded-pill shadow-sm">Confirmar
                                        Reapertura</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endhasrole

        </div>
    </div>

    <!-- SCRIPT JS PARA HABILITAR DINÁMICAMENTE LOS ESTADOS AL ADJUNTAR UN PDF -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputPdf = document.getElementById('inputPdf');
            const opcionesRequierePdf = document.querySelectorAll('.opcion-requiere-pdf');
            const alertaPdf = document.getElementById('alertaPdf');

            if (inputPdf) {
                inputPdf.addEventListener('change', function() {
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
