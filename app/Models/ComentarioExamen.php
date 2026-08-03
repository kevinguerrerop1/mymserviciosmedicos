<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComentarioExamen extends Model
{
    protected $table = 'comentario_examenes';
    protected $fillable = ['examen_id', 'user_id', 'comentario'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}