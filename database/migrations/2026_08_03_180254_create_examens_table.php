<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamensTable extends Migration
{
    public function up()
    {
        Schema::create('examenes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_correlativo')->unique(); // N° Correlativo
            $table->date('fecha_toma');
            $table->date('fecha_recepcion');
            $table->date('fecha_entrega')->nullable();
            
            // Datos del Paciente y Médico
            $table->string('paciente_nombre');
            $table->string('paciente_rut');
            $table->string('medico_solicitante');
            
            // Especificaciones de Anatomía Patológica (Según tu 2da Imagen)
            $table->integer('cantidad_muestras')->default(1); // Cassettes, placas o frascos
            $table->integer('numero_fragmentos')->nullable();
            $table->string('tincion_rutina')->nullable();
            $table->string('tecnicas_especiales')->nullable();

            // Relaciones
            $table->foreignId('tipo_examen_id')->constrained('tipo_examenes');
            $table->foreignId('laboratorio_id')->constrained('laboratorios');
            $table->foreignId('patologo_id')->nullable()->constrained('users');

            // Estado (Según los estados de tu 1ra Imagen)
            $table->enum('estado', [
                'PENDIENTE', 
                'EN ESPERA INFORME COMPLEMENTARIO', 
                'INFORMADO RESULTADO CRÍTICO', 
                'INFORMADO'
            ])->default('PENDIENTE');

            // Archivos adjuntos (Informe PDF e Imágenes)
            $table->string('archivo_informe')->nullable();
            $table->json('galeria_imagenes')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('examenes');
    }
}
