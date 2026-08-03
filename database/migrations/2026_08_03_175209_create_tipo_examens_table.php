<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipoExamensTable extends Migration
{
    public function up()
    {
        Schema::create('tipo_examenes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Ej: Biopsia, Citología, Inmunohistoquímica
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tipo_examenes');
    }
}
