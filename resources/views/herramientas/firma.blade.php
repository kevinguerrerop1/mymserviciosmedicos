@extends('layouts.app')

@section('content')
    <div class="container-fluid py-2 px-md-4">

        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2 border-bottom pb-3">
            <div>
                <h4 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                    <i class="bi bi-pen text-primary"></i> Generador de Firma Institucional
                </h4>
                <p class="text-muted small mb-0">Logo original estático con datos y tipografía médica dinámicos.</p>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary fw-bold d-flex align-items-center gap-2 shadow-sm"
                    onclick="descargarComoImagen()">
                    <i class="bi bi-download"></i> Descargar Imagen (PNG)
                </button>
                <button type="button" class="btn btn-success fw-bold d-flex align-items-center gap-2 shadow-sm"
                    onclick="copiarFirma()">
                    <i class="bi bi-clipboard-check"></i> Copiar para Correo
                </button>
            </div>
        </div>

        <div class="row g-4">
            <!-- FORMULARIO DE EDICIÓN -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white fw-bold d-flex align-items-center gap-2 py-2">
                        <i class="bi bi-person-gear"></i> Datos de la Firma
                    </div>
                    <div class="card-body bg-white p-3">
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">IMAGEN DEL LOGO (OPCIONAL)</label>
                            <input type="file" id="input_logo_file" class="form-control form-control-sm" accept="image/*"
                                onchange="cargarLogoPersonalizado(this)">
                            <small class="text-muted" style="font-size: 0.72rem;">Por defecto usa
                                <code>/img/logo-mym.png</code></small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">NOMBRE COMPLETO</label>
                            <input type="text" id="input_nombre" class="form-control form-control-sm"
                                value="Eduardo Esteban Moyano Villalobos" oninput="actualizarFirma()">
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">CARGO</label>
                            <input type="text" id="input_cargo" class="form-control form-control-sm"
                                value="Gerente General y Representante Legal" oninput="actualizarFirma()">
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">UNIDAD / ÁREA</label>
                            <input type="text" id="input_area" class="form-control form-control-sm"
                                value="Laboratorio de Anatomía Patológica" oninput="actualizarFirma()">
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">TELÉFONO</label>
                            <input type="text" id="input_telefono" class="form-control form-control-sm"
                                value="+56988909438" oninput="actualizarFirma()">
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">EMAIL</label>
                            <input type="email" id="input_correo" class="form-control form-control-sm"
                                value="edomoyano@gmail.com" oninput="actualizarFirma()">
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">DIRECCIÓN</label>
                            <input type="text" id="input_direccion" class="form-control form-control-sm"
                                value="Manuel Rodriguez #565, Curicó. Chile" oninput="actualizarFirma()">
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">SITIO WEB</label>
                            <input type="text" id="input_web" class="form-control form-control-sm"
                                value="mymserviciosmedicos.cl" oninput="actualizarFirma()">
                        </div>
                    </div>
                </div>
            </div>

            <!-- CONTENEDOR VISTA PREVIA -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header bg-secondary text-white fw-bold py-2 px-3">
                        <i class="bi bi-eye"></i> Vista Previa de la Firma
                    </div>
                    <div class="card-body bg-white p-4 d-flex justify-content-center align-items-center"
                        style="overflow-x: auto;">

                        <!-- RECUADRO EXACTO DE LA FIRMA -->
                        <div id="contenedor-firma"
                            style="background-color: #ffffff; padding: 20px 30px; display: inline-block;">
                            <table cellpadding="0" cellspacing="0" border="0"
                                style="font-family: 'Segoe UI', Arial, Helvetica, sans-serif; background-color: #ffffff; border-collapse: collapse;">
                                <tr>
                                    <!-- LOGO COMO IMAGEN ESTÁTICA ORIGINAL -->
                                    <td valign="middle"
                                        style="padding-right: 35px; vertical-align: middle; text-align: center;">
                                        <img id="img_logo" src="{{ asset('img/logo-mym.png') }}"
                                            alt="M+M Servicios Médicos"
                                            style="display: block; width: 170px; max-width: 170px; height: auto;"
                                            onerror="this.onerror=null; this.src='https://i.ibb.co/vzZJqS2/logo-mym-sample.png';">
                                    </td>

                                    <!-- CONTENIDO DERECHO DINÁMICO -->
                                    <td valign="middle" style="vertical-align: middle; padding-left: 5px;">
                                        <table cellpadding="0" cellspacing="0" border="0" style="width: 100%;">
                                            <!-- Nombre -->
                                            <tr>
                                                <td id="preview_nombre"
                                                    style="font-family: 'Segoe UI', Arial, sans-serif; font-size: 19px; font-weight: bold; color: #3d7a99; line-height: 1.2; padding-bottom: 3px;">
                                                    Eduardo Esteban Moyano Villalobos
                                                </td>
                                            </tr>
                                            <!-- Cargo -->
                                            <tr>
                                                <td id="preview_cargo"
                                                    style="font-family: 'Segoe UI', Arial, sans-serif; font-size: 14px; font-weight: 600; color: #64748b; line-height: 1.2; padding-bottom: 8px;">
                                                    Gerente General y Representante Legal
                                                </td>
                                            </tr>

                                            <!-- Línea Divisoria Horizontal Exacta -->
                                            <tr>
                                                <td style="padding-bottom: 10px;">
                                                    <div style="height: 2px; background-color: #6496ae; width: 100%;">
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Área -->
                                            <tr>
                                                <td id="preview_area"
                                                    style="font-family: 'Segoe UI', Arial, sans-serif; font-size: 14.5px; font-weight: bold; color: #3d7a99; line-height: 1.2; padding-bottom: 8px;">
                                                    Laboratorio de Anatomía Patológica
                                                </td>
                                            </tr>

                                            <!-- Filas de Contacto con Iconos Circulares -->
                                            <tr>
                                                <td>
                                                    <table cellpadding="0" cellspacing="0" border="0"
                                                        style="font-family: 'Segoe UI', Arial, sans-serif; font-size: 13px;">
                                                        <!-- Teléfono -->
                                                        <tr>
                                                            <td
                                                                style="padding: 2px 8px 2px 0; vertical-align: middle; width: 26px;">
                                                                <svg width="20" height="20" viewBox="0 0 24 24"
                                                                    fill="none">
                                                                    <circle cx="12" cy="12" r="10.5"
                                                                        stroke="#47819e" stroke-width="1.5"
                                                                        fill="#ffffff" />
                                                                    <circle cx="12" cy="12" r="8.5"
                                                                        stroke="#47819e" stroke-width="0.8" />
                                                                    <path
                                                                        d="M8.5 9.5C8.5 12 11.5 15 14 15L15 13.5C15 13.5 14.5 13 14 12.5C13.5 12 13 12.5 13 12.5C12.5 12.5 11 11 11 10.5C11 10.5 11.5 10 11 9.5C10.5 9 10 8.5 10 8.5L8.5 9.5Z"
                                                                        fill="#47819e" />
                                                                </svg>
                                                            </td>
                                                            <td id="preview_telefono"
                                                                style="vertical-align: middle; color: #64748b; font-weight: 500;">
                                                                +56988909438
                                                            </td>
                                                        </tr>
                                                        <!-- Correo -->
                                                        <tr>
                                                            <td
                                                                style="padding: 2px 8px 2px 0; vertical-align: middle; width: 26px;">
                                                                <svg width="20" height="20" viewBox="0 0 24 24"
                                                                    fill="none">
                                                                    <circle cx="12" cy="12" r="10.5"
                                                                        stroke="#47819e" stroke-width="1.5"
                                                                        fill="#ffffff" />
                                                                    <circle cx="12" cy="12" r="8.5"
                                                                        stroke="#47819e" stroke-width="0.8" />
                                                                    <rect x="7.5" y="9" width="9" height="6"
                                                                        rx="1" stroke="#47819e"
                                                                        stroke-width="1.2" />
                                                                    <path d="M7.5 9.5L12 12.5L16.5 9.5" stroke="#47819e"
                                                                        stroke-width="1.2" />
                                                                </svg>
                                                            </td>
                                                            <td id="preview_correo"
                                                                style="vertical-align: middle; color: #64748b; font-weight: 500;">
                                                                edomoyano@gmail.com
                                                            </td>
                                                        </tr>
                                                        <!-- Dirección -->
                                                        <tr>
                                                            <td
                                                                style="padding: 2px 8px 2px 0; vertical-align: middle; width: 26px;">
                                                                <svg width="20" height="20" viewBox="0 0 24 24"
                                                                    fill="none">
                                                                    <circle cx="12" cy="12" r="10.5"
                                                                        stroke="#47819e" stroke-width="1.5"
                                                                        fill="#ffffff" />
                                                                    <circle cx="12" cy="12" r="8.5"
                                                                        stroke="#47819e" stroke-width="0.8" />
                                                                    <path
                                                                        d="M12 7C10.3 7 9 8.3 9 10C9 12.3 12 15.5 12 15.5C12 15.5 15 12.3 15 10C15 8.3 13.7 7 12 7ZM12 11.2C11.3 11.2 10.8 10.7 10.8 10C10.8 9.3 11.3 8.8 12 8.8C12.7 8.8 13.2 9.3 13.2 10C13.2 10.7 12.7 11.2 12 11.2Z"
                                                                        fill="#47819e" />
                                                                </svg>
                                                            </td>
                                                            <td id="preview_direccion"
                                                                style="vertical-align: middle; color: #64748b; font-weight: 500;">
                                                                Manuel Rodriguez #565, Curicó. Chile
                                                            </td>
                                                        </tr>
                                                        <!-- Web -->
                                                        <tr>
                                                            <td
                                                                style="padding: 2px 8px 2px 0; vertical-align: middle; width: 26px;">
                                                                <svg width="20" height="20" viewBox="0 0 24 24"
                                                                    fill="none">
                                                                    <circle cx="12" cy="12" r="10.5"
                                                                        stroke="#47819e" stroke-width="1.5"
                                                                        fill="#ffffff" />
                                                                    <circle cx="12" cy="12" r="8.5"
                                                                        stroke="#47819e" stroke-width="0.8" />
                                                                    <circle cx="12" cy="12" r="4.5"
                                                                        stroke="#47819e" stroke-width="1" />
                                                                    <line x1="7.5" y1="12" x2="16.5"
                                                                        y2="12" stroke="#47819e"
                                                                        stroke-width="1" />
                                                                    <path
                                                                        d="M12 7.5C13.2 9 13.5 10.5 13.5 12C13.5 13.5 13.2 15 12 16.5C10.8 15 10.5 13.5 10.5 12C10.5 10.5 10.8 9 12 7.5Z"
                                                                        stroke="#47819e" stroke-width="0.8" />
                                                                </svg>
                                                            </td>
                                                            <td id="preview_web"
                                                                style="vertical-align: middle; color: #64748b; font-weight: 500;">
                                                                mymserviciosmedicos.cl
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>

                    </div>
                </div>

                <div class="alert alert-light border shadow-sm d-flex align-items-center gap-3">
                    <i class="bi bi-info-circle text-primary fs-5"></i>
                    <span class="small text-muted">
                        Usa <strong>"Descargar Imagen (PNG)"</strong> para obtener la imagen final lista para adjuntar, o
                        <strong>"Copiar para Correo"</strong> para pegarla en Gmail con <kbd>Ctrl + V</kbd>.
                    </span>
                </div>
            </div>
        </div>

    </div>

    <!-- Librería html2canvas para exportar en PNG sin distorsiones -->
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

    <script>
        function actualizarFirma() {
            document.getElementById('preview_nombre').innerText = document.getElementById('input_nombre').value ||
                'Nombre Completo';
            document.getElementById('preview_cargo').innerText = document.getElementById('input_cargo').value || 'Cargo';
            document.getElementById('preview_area').innerText = document.getElementById('input_area').value ||
                'Área o Servicio';
            document.getElementById('preview_telefono').innerText = document.getElementById('input_telefono').value || '';
            document.getElementById('preview_correo').innerText = document.getElementById('input_correo').value || '';
            document.getElementById('preview_direccion').innerText = document.getElementById('input_direccion').value || '';
            document.getElementById('preview_web').innerText = document.getElementById('input_web').value || '';
        }

        function cargarLogoPersonalizado(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('img_logo').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function copiarFirma() {
            const contenedor = document.getElementById('contenedor-firma');
            const range = document.createRange();
            range.selectNode(contenedor);
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(range);

            try {
                const exitoso = document.execCommand('copy');
                if (exitoso) {
                    alert('¡Firma copiada correctamente! Puedes pegarla con Ctrl + V en tu gestor de correo.');
                } else {
                    alert('Por favor selecciona el recuadro y presiona Ctrl + C.');
                }
            } catch (err) {
                alert('Error al copiar: ' + err);
            }
            window.getSelection().removeAllRanges();
        }

        function descargarComoImagen() {
            const contenedor = document.getElementById('contenedor-firma');
            const nombreUsuario = document.getElementById('input_nombre').value.trim() || 'firma';
            const nombreArchivo = `firma_${nombreUsuario.toLowerCase().replace(/[^a-z0-9]/g, '_')}.png`;

            html2canvas(contenedor, {
                scale: 2,
                backgroundColor: '#ffffff',
                useCORS: true
            }).then(canvas => {
                const enlace = document.createElement('a');
                enlace.download = nombreArchivo;
                enlace.href = canvas.toDataURL('image/png');
                enlace.click();
            });
        }
    </script>
@endsection
