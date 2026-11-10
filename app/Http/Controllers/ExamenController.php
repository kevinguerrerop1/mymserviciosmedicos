<?php

namespace App\Http\Controllers;

use App\Models\Examen;
use App\Models\Laboratorio;
use App\Models\TipoExamen;
use App\Models\User;
use App\Models\Medico;
use App\Models\ComentarioExamen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ExamenController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Examen::with(['laboratorio', 'patologo', 'tipoExamen']);

        if ($user->hasRole('patologo')) {
            $query->where('patologo_id', $user->id);
        } elseif ($user->hasRole('laboratorio')) {
            $query->where('laboratorio_id', $user->laboratorio_id);
        }

        if ($request->filled('correlativo'))  $query->where('numero_correlativo', 'LIKE', "%{$request->correlativo}%");
        if ($request->filled('fecha_toma'))   $query->whereDate('fecha_toma', $request->fecha_toma);
        if ($request->filled('paciente'))     $query->where('paciente_nombre', 'LIKE', "%{$request->paciente}%");
        if ($request->filled('rut'))          $query->where('paciente_rut', 'LIKE', "%{$request->rut}%");
        if ($request->filled('patologo_id')) $query->where('patologo_id', $request->patologo_id);
        if ($request->filled('estado'))       $query->where('estado', $request->estado);

        $examenes = $query->latest()->get();
        $laboratorios = Laboratorio::all();
        $tiposExamen = TipoExamen::all();
        $patologos = User::role('patologo')->get();
        $medicos = Medico::orderBy('nombre', 'asc')->get();

        return view('examenes.index', compact('examenes', 'laboratorios', 'tiposExamen', 'patologos', 'medicos'));
    }

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

        $examen = DB::transaction(function () use ($request) {
            $anioActual = date('y');
            $prefijo    = $anioActual . '-';

            $ultimoExamenAnio = Examen::where('numero_correlativo', 'LIKE', "{$prefijo}%")
                ->latest('id')
                ->first();

            if ($ultimoExamenAnio) {
                $partes = explode('-', $ultimoExamenAnio->numero_correlativo);
                $siguienteNumero = ((int) end($partes)) + 1;
            } else {
                $siguienteNumero = 1;
            }

            $correlativoFormateado = $prefijo . $siguienteNumero;

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

            ComentarioExamen::create([
                'examen_id'  => $nuevoExamen->id,
                'user_id'    => auth()->id(),
                'comentario' => "Registro de examen creado e ingresado con correlativo #{$correlativoFormateado}.",
                'tipo'       => 'sistema',
            ]);

            return $nuevoExamen;
        });

        return redirect()->back()->with('success', 'Examen registrado correctamente con el correlativo #' . $examen->numero_correlativo);
    }

    public function show(Examen $examen)
    {
        $this->validarAcceso($examen);
        $examen->load(['comentarios.user', 'laboratorio', 'patologo', 'tipoExamen']);
        return view('examenes.show', compact('examen'));
    }

    public function update(Request $request, Examen $examen)
    {
        $this->validarAcceso($examen);

        if (in_array($examen->estado, ['INFORMADO', 'INFORMADO RESULTADO CRÍTICO'])) {
            return redirect()->back()->with('error', 'Este examen ya se encuentra finalizado e informado, por lo que no se puede modificar.');
        }

        $request->validate([
            'estado'          => 'required|in:PENDIENTE,EN ESPERA INFORME COMPLEMENTARIO,INFORMADO RESULTADO CRÍTICO,INFORMADO',
            'archivo_informe' => 'nullable|mimes:pdf|max:20480',
            'imagenes.*'      => 'nullable|image|max:5120'
        ]);

        $quiereFinalizar   = in_array($request->estado, ['INFORMADO', 'INFORMADO RESULTADO CRÍTICO']);
        $tienePdfExistente = !empty($examen->archivo_informe);
        $subiendoNuevoPdf  = $request->hasFile('archivo_informe');

        if ($quiereFinalizar && !$tienePdfExistente && !$subiendoNuevoPdf) {
            return redirect()->back()->with('error', 'No es posible cambiar el estado a INFORMADO sin adjuntar el informe diagnóstico en PDF.');
        }

        $estadoAnterior = $examen->estado;
        $data           = ['estado' => $request->estado];

        if ($request->filled('fecha_entrega')) {
            $data['fecha_entrega'] = $request->fecha_entrega;
        }

        $informeSubido = false;
        if ($subiendoNuevoPdf) {
            $archivo = $request->file('archivo_informe');
            $ext     = $archivo->getClientOriginalExtension();

            $correlativoLimpio = Str::slug($examen->numero_correlativo, '_');
            $rutLimpio         = Str::slug($examen->paciente_rut, '_');
            $pacienteLimpio    = Str::slug($examen->paciente_nombre, '_');
            $fechaRecepcion    = $examen->fecha_recepcion ? Carbon::parse($examen->fecha_recepcion)->format('dmY') : date('dmY');

            $nombreArchivo = "informe_correlativo_{$correlativoLimpio}_rut_{$rutLimpio}_{$pacienteLimpio}_{$fechaRecepcion}.{$ext}";

            if ($examen->archivo_informe && Storage::disk('public_direct')->exists($examen->archivo_informe)) {
                Storage::disk('public_direct')->delete($examen->archivo_informe);
            }

            $archivo->storeAs('', $nombreArchivo, 'public_direct');

            $data['archivo_informe'] = $nombreArchivo;
            $informeSubido           = true;
        }

        $imagenesSubidas = 0;
        if ($request->hasFile('imagenes')) {
            $rutasImagenes = $examen->galeria_imagenes ?? [];
            foreach ($request->file('imagenes') as $index => $img) {
                $extImg        = $img->getClientOriginalExtension();
                $correlativo   = Str::slug($examen->numero_correlativo, '_');
                $nombreImg     = "img_{$correlativo}_" . time() . "_{$index}.{$extImg}";

                $img->storeAs('galeria_examenes', $nombreImg, 'public_direct');
                $rutasImagenes[] = "galeria_examenes/{$nombreImg}";
                $imagenesSubidas++;
            }
            $data['galeria_imagenes'] = $rutasImagenes;
        }

        $examen->update($data);

        if ($estadoAnterior !== $request->estado) {
            ComentarioExamen::create([
                'examen_id'  => $examen->id,
                'user_id'    => auth()->id(),
                'comentario' => "Estado actualizado de '{$estadoAnterior}' a '{$request->estado}'.",
                'tipo'       => 'sistema',
            ]);
        }

        if ($informeSubido) {
            ComentarioExamen::create([
                'examen_id'  => $examen->id,
                'user_id'    => auth()->id(),
                'comentario' => "Se adjuntó el Informe Diagnóstico Oficial (PDF).",
                'tipo'       => 'sistema',
            ]);
        }

        if ($imagenesSubidas > 0) {
            ComentarioExamen::create([
                'examen_id'  => $examen->id,
                'user_id'    => auth()->id(),
                'comentario' => "Se agregaron {$imagenesSubidas} nueva(s) imagen(es) al expediente.",
                'tipo'       => 'sistema',
            ]);
        }

        return redirect()->back()->with('success', 'Examen actualizado y registrado en la línea de tiempo con éxito.');
    }

    public function descargarInforme(Examen $examen)
    {
        $this->validarAcceso($examen);

        if (!$examen->archivo_informe) {
            return redirect()->back()->with('error', 'Sin informe cargado.');
        }

        $rutaPublicDirect = Storage::disk('public_direct')->path($examen->archivo_informe);
        if (file_exists($rutaPublicDirect)) {
            return response()->download($rutaPublicDirect, $examen->archivo_informe, [
                'Content-Type' => 'application/pdf',
            ]);
        }

        $rutaStorageLocal = storage_path('app/' . $examen->archivo_informe);
        if (file_exists($rutaStorageLocal)) {
            return response()->download($rutaStorageLocal, basename($examen->archivo_informe), [
                'Content-Type' => 'application/pdf',
            ]);
        }

        return redirect()->back()->with('error', 'El archivo no existe en el servidor.');
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

        $examen->update(['estado' => 'EN ESPERA INFORME COMPLEMENTARIO']);

        ComentarioExamen::create([
            'examen_id'  => $examen->id,
            'user_id'    => auth()->id(),
            'comentario' => "EXPEDIENTE REABIERTO por Administrador. Estado devuelto a 'EN ESPERA INFORME COMPLEMENTARIO'. Motivo: {$request->motivo}",
            'tipo'       => 'sistema',
        ]);

        return redirect()->back()->with('success', 'El expediente ha sido reabierto exitosamente.');
    }
}