<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourseMenuController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\PeriodoController;
use App\Http\Controllers\PlanilhaController;

// Página inicial
Route::get('/', function () {
    return view('welcome');
});

// Dashboard original do Laravel
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware(['auth', 'verified']);

// Rotas de perfil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->name('admin.')->group(function () {
    // Rotas explícitas para cursos
    Route::get('/cursos', [CursoController::class, 'index'])->name('cursos.index');
    Route::get('/cursos/create', [CursoController::class, 'create'])->name('cursos.create');
    Route::post('/cursos', [CursoController::class, 'store'])->name('cursos.store');
    Route::get('/cursos/{curso}/edit', [CursoController::class, 'edit'])->name('cursos.edit');
    Route::put('/cursos/{curso}', [CursoController::class, 'update'])->name('cursos.update');
    Route::delete('/cursos/{curso}', [CursoController::class, 'destroy'])->name('cursos.destroy');
    Route::post('/cursos/avancar-ano', [CursoController::class, 'avancarAno'])->name('cursos.avancar-ano');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('cursos', \App\Http\Controllers\CursoController::class);
    Route::post('cursos/avancar-ano', [\App\Http\Controllers\CursoController::class, 'avancarAno'])
        ->name('cursos.avancar-ano');
    Route::post('cursos/voltar-ano', [\App\Http\Controllers\CursoController::class, 'voltarAno'])
        ->name('cursos.voltar-ano'); // ← NOVA ROTA
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('cursos/{curso}/ano/{ano}/periodo/{periodo}', [PeriodoController::class, 'show'])
        ->name('periodos.show');
    
    Route::post('periodos/processar-planilha', [PlanilhaController::class, 'importar'])
        ->name('periodos.processar-planilha');
    
    Route::post('periodos/salvar-dados', [PeriodoController::class, 'storeData'])
        ->name('periodos.salvar-dados');
});

require __DIR__.'/auth.php';
