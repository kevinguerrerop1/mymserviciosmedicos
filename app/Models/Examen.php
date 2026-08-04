<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    protected $table = 'examenes';
    
    protected $fillable = [
    'numero_correlativo',
    'fecha_toma',
    'fecha_recepcion',
    'paciente_nombre',
    'paciente_rut',
    'medico_solicitante',
    'tipo_examen_id',
    'laboratorio_id',
    'patologo_id', // <--- Asegurar que esté en el fillable
    'estado',
    'archivo_informe',
    'galeria_imagenes'
];

    protected $casts = [
        'galeria_imagenes' => 'array',
    ];

    public function laboratorio()
    {
        return $this->belongsTo(Laboratorio::class);
    }

    public function patologo()
    {
        return $this->belongsTo(User::class, 'patologo_id');
    }

    public function tipoExamen()
    {
        return $this->belongsTo(TipoExamen::class, 'tipo_examen_id');
    }

    public function comentarios()
    {
        return $this->hasMany(ComentarioExamen::class)->latest();
    }
}