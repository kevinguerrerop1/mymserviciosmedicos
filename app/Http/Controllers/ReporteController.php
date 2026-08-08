<?php

namespace App\Http\Controllers;

use App\Models\Examen;
use App\Models\Laboratorio;
use App\Models\TipoExamen;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $laboratorios = Laboratorio::orderBy('nombre', 'asc')->get();
        $tiposExamen  = TipoExamen::orderBy('nombre', 'asc')->get();
        $patologos    = User::role('patologo')->orderBy('name', 'asc')->get();

        $query = Examen::with(['laboratorio', 'patologo', 'tipoExamen']);

        // 1. Filtros de Fecha (Rango)
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_recepcion', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_recepcion', '<=', $request->fecha_hasta);
        }

        // 2. Filtro por Laboratorio / Cliente
        if ($request->filled('laboratorio_id')) {
            $query->where('laboratorio_id', $request->laboratorio_id);
        }

        // 3. Filtro por Patólogo
        if ($request->filled('patologo_id')) {
            $query->where('patologo_id', $request->patologo_id);
        }

        // 4. Filtro por Tipo de Examen
        if ($request->filled('tipo_examen_id')) {
            $query->where('tipo_examen_id', $request->tipo_examen_id);
        }

        // 5. Filtro por Estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Si se presiona el botón de Exportar CSV / Excel
        if ($request->get('exportar') === 'csv') {
            return $this->exportarCsv($query->get());
        }

        $examenes = $query->latest('fecha_recepcion')->get();

        return view('reportes.index', compact('examenes', 'laboratorios', 'tiposExamen', 'patologos'));
    }

    private function exportarCsv($examenes): StreamedResponse
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="reporte_examenes_' . date('Ymd_His') . '.csv"',
        ];

        return response()->stream(function () use ($examenes) {
            $handle = fopen('php://output', 'w');
            // BOM UTF-8 para apertura correcta en Microsoft Excel (acentos y ñ)
            fputs($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Encabezados
            fputcsv($handle, [
                'Correlativo',
                'Fecha Toma',
                'Fecha Recepción',
                'Paciente',
                'RUT',
                'Médico Solicitante',
                'Tipo Examen',
                'Laboratorio Origen',
                'Patólogo Asignado',
                'Estado'
            ], ';');

            foreach ($examenes as $item) {
                fputcsv($handle, [
                    $item->numero_correlativo,
                    $item->fecha_toma,
                    $item->fecha_recepcion,
                    $item->paciente_nombre,
                    $item->paciente_rut,
                    $item->medico_solicitante,
                    $item->tipoExamen->nombre ?? 'N/A',
                    $item->laboratorio->nombre ?? 'N/A',
                    $item->patologo->name ?? 'Sin Asignar',
                    $item->estado,
                ], ';');
            }

            fclose($handle);
        }, 200, $headers);
    }
}
