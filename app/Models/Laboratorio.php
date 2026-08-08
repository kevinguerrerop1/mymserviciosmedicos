<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laboratorio extends Model
{
    protected $fillable = ['nombre', 'rut', 'direccion', 'email'];

    public function examenes()
    {
        return $this->hasMany(Examen::class);
    }
}
