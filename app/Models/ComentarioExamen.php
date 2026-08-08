<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComentarioExamen extends Model
{
    use HasFactory;

    protected $table = 'comentario_examenes';

    protected $fillable = [
        'examen_id',
        'user_id',
        'comentario',
        'tipo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function examen()
    {
        return $this->belongsTo(Examen::class);
    }
}
