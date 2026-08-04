<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ExamenController;
use App\Http\Controllers\LaboratorioController;
use App\Http\Controllers\TipoExamenController;
use App\Http\Controllers\ComentarioExamenController;
use App\Http\Controllers\UserController;

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', function () {
        return redirect()->route('examenes.index');
    })->name('home');

    Route::get('/', [ExamenController::class, 'index'])->name('home');
    Route::get('/examenes', [ExamenController::class, 'index'])->name('examenes.index');
    Route::post('/examenes', [ExamenController::class, 'store'])->name('examenes.store');
    Route::get('/examenes/{examen}', [ExamenController::class, 'show'])->name('examenes.show');
    Route::put('/examenes/{examen}', [ExamenController::class, 'update'])->name('examenes.update');
    Route::get('/examenes/{examen}/descargar', [ExamenController::class, 'descargarInforme'])->name('examenes.descargar');

    // Mantenedores (Admin)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/laboratorios', [LaboratorioController::class, 'index'])->name('laboratorios.index');
        Route::post('/laboratorios', [LaboratorioController::class, 'store'])->name('laboratorios.store');

        Route::get('/tipos-examen', [TipoExamenController::class, 'index'])->name('tipos.index');
        Route::post('/tipos-examen', [TipoExamenController::class, 'store'])->name('tipos.store');

        Route::get('/laboratorios', [LaboratorioController::class, 'index'])->name('laboratorios.index');
        Route::post('/laboratorios', [LaboratorioController::class, 'store'])->name('laboratorios.store');

        Route::get('/tipos-examen', [TipoExamenController::class, 'index'])->name('tipos.index');
        Route::post('/tipos-examen', [TipoExamenController::class, 'store'])->name('tipos.store');

        // RUTAS DE USUARIOS
        Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
        Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
        Route::post('examenes/{examen}/reabrir', [ExamenController::class, 'reabrir'])->name('examenes.reabrir');
    });

    // Comentarios
    Route::post('/comentarios', [ComentarioExamenController::class, 'store'])->name('comentarios.store');
});