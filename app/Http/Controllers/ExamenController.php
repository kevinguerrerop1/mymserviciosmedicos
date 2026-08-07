<?php

namespace App\Http\Controllers;

use App\Models\Examen;
use App\Models\Laboratorio;
use App\Models\TipoExamen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\ComentarioExamen;
use Illuminate\Support\Facades\DB;
use App\Models\Medico;

class ExamenController extends Controller
{
    // Listado general filtrado según el rol
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Examen::with(['laboratorio', 'patologo', 'tipoExamen']);

        // Aislamiento por Rol
        if ($user->hasRole('patologo')) {
            $query->where('patologo_id', $user->id);
        } elseif ($user->hasRole('laboratorio')) {
            $query->where('laboratorio_id', $user->laboratorio_id);
        }

        // Filtros individuales por columna (Slot)
        if ($request->filled('correlativo'))  $query->where('numero_correlativo', 'LIKE', "%{$request->correlativo}%");
        if ($request->filled('fecha_toma'))   $query->whereDate('fecha_toma', $request->fecha_toma);
        if ($request->filled('paciente'))     $query->where('paciente_nombre', 'LIKE', "%{$request->paciente}%");
        if ($request->filled('rut'))          $query->where('paciente_rut', 'LIKE', "%{$request->rut}%");
        if ($request->filled('patologo_id')) $query->where('patologo_id', $request->patologo_id); // <--- AGREGAR ESTA LÍNEA
        if ($request->filled('estado'))       $query->where('estado', $request->estado);

        $examenes = $query->latest()->get();
        $laboratorios = Laboratorio::all();
        $tiposExamen = TipoExamen::all();
        $patologos = User::role('patologo')->get();
        $medicos = Medico::orderBy('nombre', 'asc')->get();

        return view('examenes.index', compact('examenes', 'laboratorios', 'tiposExamen', 'patologos', 'medicos'));
    }

    // El Administrador crea y asigna el examen
    public function store(Request $request)
    {
        $request->validate([
            'fecha_toma'         => 'required|date',
            'fecha_recepcion'    => 'required|date',
            'paciente_nombre'    => 'required|string|max:255',
            'paciente_rut'       => 'required|string|max:20',
            'medico_solicitante' => 'required|string|max:255',
            'tipo_examen_id'     => 'required|exists:tipo_examenes,id',
            'laboratorio_id'     => 'required|exists:laboratorios,id',
        ]);

        // Ejecutamos dentro de una transacción para garantizar integridad de datos
        $examen = DB::transaction(function () use ($request) {
            
            // 1. Obtener los 2 dígitos del año actual (ej: "26" para el año 2026)
            $anioActual = date('y'); 
            $prefijo    = $anioActual . '-';

            // 2. Buscar el último examen registrado en este mismo año
            $ultimoExamenAnio = Examen::where('numero_correlativo', 'LIKE', "{$prefijo}%")
                ->latest('id')
                ->first();

            if ($ultimoExamenAnio) {
                // Extraer el número después del guión y sumar 1
                $partes = explode('-', $ultimoExamenAnio->numero_correlativo);
                $siguienteNumero = ((int) end($partes)) + 1;
            } else {
                // Si es el primer examen registrado en el año actual, inicia en 1
                $siguienteNumero = 1;
            }

            // 3. Formatear el correlativo compuesto (ej: "26-1")
            $correlativoFormateado = $prefijo . $siguienteNumero;

            // 4. Crear el examen asignando el correlativo formateado
            $nuevoExamen = Examen::create([
                'numero_correlativo' => $correlativoFormateado,
                'fecha_toma'         => $request->fecha_toma,
                'fecha_recepcion'    => $request->fecha_recepcion,
                'paciente_nombre'    => $request->paciente_nombre,
                'paciente_rut'       => $request->paciente_rut,
                'medico_solicitante' => $request->medico_solicitante,
                'tipo_examen_id'     => $request->tipo_examen_id,
                'laboratorio_id'     => $request->laboratorio_id,
                'patologo_id'        => $request->patologo_id ?? null,
                'estado'             => 'PENDIENTE',
            ]);

            // 5. Registro inicial automático en la trazabilidad
            ComentarioExamen::create([
                'examen_id'  => $nuevoExamen->id,
                'user_id'    => auth()->id(),
                'comentario' => "📥 Registro de examen creado e ingresado con correlativo #{$correlativoFormateado}.",
                'tipo'       => 'sistema',
            ]);

            return $nuevoExamen;
        });

        return redirect()->back()->with('success', 'Examen registrado correctamente con el correlativo #' . $examen->numero_correlativo);
    }

    // Detalle completo del Examen (2da Imagen)
    public function show(Examen $examen)
    {
        $this->validarAcceso($examen);
        $examen->load(['comentarios.user', 'laboratorio', 'patologo', 'tipoExamen']);
        return view('examenes.show', compact('examen'));
    }

    // El Patólogo o Admin actualiza el estado y sube el informe o galería
    public function update(Request $request, Examen $examen)
    {
        $this->validarAcceso($examen);

        // 1. Bloqueo si el examen ya está en estado finalizado
        if (in_array($examen->estado, ['INFORMADO', 'INFORMADO RESULTADO CRÍTICO'])) {
            return redirect()->back()->with('error', 'Este examen ya se encuentra finalizado e informado, por lo que no se puede modificar.');
        }

        // 2. Validación formal del request
        $request->validate([
            'estado'          => 'required|in:PENDIENTE,EN ESPERA INFORME COMPLEMENTARIO,INFORMADO RESULTADO CRÍTICO,INFORMADO',
            'archivo_informe' => 'nullable|mimes:pdf|max:10240',
            'imagenes.*'      => 'nullable|image|max:5120'
        ]);

        // 3. Validación de regla de negocio: Requerir PDF para finalizar
        $quiereFinalizar   = in_array($request->estado, ['INFORMADO', 'INFORMADO RESULTADO CRÍTICO']);
        $tienePdfExistente = !empty($examen->archivo_informe);
        $subiendoNuevoPdf  = $request->hasFile('archivo_informe');

        if ($quiereFinalizar && !$tienePdfExistente && !$subiendoNuevoPdf) {
            return redirect()->back()->with('error', 'No es posible cambiar el estado a INFORMADO sin adjuntar el informe diagnóstico en PDF.');
        }

        // Guardamos el estado anterior para comparar
        $estadoAnterior = $examen->estado;
        $data           = ['estado' => $request->estado];

        if ($request->filled('fecha_entrega')) {
            $data['fecha_entrega'] = $request->fecha_entrega;
        }

        // Subida del PDF de Informe
        $informeSubido = false;
        if ($subiendoNuevoPdf) {
            $data['archivo_informe'] = $request->file('archivo_informe')->store('informes');
            $informeSubido          = true;
        }

        // Subida de Galería de imágenes
        $imagenesSubidas = 0;
        if ($request->hasFile('imagenes')) {
            $rutasImagenes = $examen->galeria_imagenes ?? [];
            foreach ($request->file('imagenes') as $img) {
                $rutasImagenes[] = $img->store('galeria_examenes');
                $imagenesSubidas++;
            }
            $data['galeria_imagenes'] = $rutasImagenes;
        }

        // Actualizamos el examen en la base de datos
        $examen->update($data);

        // -------------------------------------------------------------
        // REGISTRO AUTOMÁTICO EN LA LÍNEA DE TIEMPO (TIPO: SISTEMA)
        // -------------------------------------------------------------

        // A) Registro por cambio de estado
        if ($estadoAnterior !== $request->estado) {
            \App\Models\ComentarioExamen::create([
                'examen_id'  => $examen->id,
                'user_id'    => auth()->id(),
                'comentario' => "Estado actualizado de '{$estadoAnterior}' a '{$request->estado}'.",
                'tipo'       => 'sistema',
            ]);
        }

        // B) Registro por subida de informe PDF
        if ($informeSubido) {
            \App\Models\ComentarioExamen::create([
                'examen_id'  => $examen->id,
                'user_id'    => auth()->id(),
                'comentario' => "Se adjuntó el Informe Diagnóstico Oficial (PDF).",
                'tipo'       => 'sistema',
            ]);
        }

        // C) Registro por subida de imágenes
        if ($imagenesSubidas > 0) {
            \App\Models\ComentarioExamen::create([
                'examen_id'  => $examen->id,
                'user_id'    => auth()->id(),
                'comentario' => "Se agregaron {$imagenesSubidas} nueva(s) imagen(es) al expediente.",
                'tipo'       => 'sistema',
            ]);
        }

        return redirect()->back()->with('success', 'Examen actualizado y registrado en la línea de tiempo con éxito.');
    }

    

    // Descarga directa del Informe
    public function descargarInforme(Examen $examen)
    {
        $this->validarAcceso($examen);
        if (!$examen->archivo_informe) return redirect()->back()->with('error', 'Sin informe cargado.');
        return Storage::download($examen->archivo_informe);
    }

    private function validarAcceso(Examen $examen)
    {
        $user = Auth::user();
        if ($user->hasRole('patologo') && $examen->patologo_id !== $user->id) abort(403);
        if ($user->hasRole('laboratorio') && $examen->laboratorio_id !== $user->laboratorio_id) abort(403);
    }

    public function reabrir(Request $request, Examen $examen)
    {
        $request->validate([
            'motivo' => 'required|string|min:10'
        ]);

        $estadoAnterior = $examen->estado;
        $examen->update(['estado' => 'EN ESPERA INFORME COMPLEMENTARIO']);

        // Registrar en la trazabilidad (línea de tiempo del sistema)
        \App\Models\ComentarioExamen::create([
            'examen_id'  => $examen->id,
            'user_id'    => auth()->id(),
            'comentario' => "EXPEDIENTE REABIERTO por Administrador. Estado devuelto a 'EN ESPERA INFORME COMPLEMENTARIO'. Motivo: {$request->motivo}",
            'tipo'       => 'sistema',
        ]);

        return redirect()->back()->with('success', 'El expediente ha sido reabierto exitosamente.');
    }
}