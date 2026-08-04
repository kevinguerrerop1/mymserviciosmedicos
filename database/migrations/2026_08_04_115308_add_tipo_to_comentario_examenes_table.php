<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoToComentarioExamenesTable extends Migration
{
    public function up()
    {
        Schema::table('comentario_examenes', function (Blueprint $table) {
            // 'sistema' para trazabilidad automática, 'nota' para comentarios de usuarios
            $table->string('tipo')->default('nota')->after('comentario');
        });

        // Clasificar automáticamente los datos existentes según el texto
        DB::table('comentario_examenes')
            ->where('comentario', 'LIKE', '%Estado actualizado%')
            ->orWhere('comentario', 'LIKE', '%Se adjuntó%')
            ->orWhere('comentario', 'LIKE', '%Se agregaron%')
            ->orWhere('comentario', 'LIKE', '%Registro de examen%')
            ->update(['tipo' => 'sistema']);
    }

    public function down()
    {
        Schema::table('comentario_examenes', function (Blueprint $table) {
            $table->dropColumn('tipo');
        });
    }
}
