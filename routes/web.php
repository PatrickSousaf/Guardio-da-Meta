<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourseMenuController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\PeriodoController;
use App\Http\Controllers\PlanilhaController;
use App\Http\Controllers\Auth\RegisteredUserController;

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
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ROTAS DE REGISTRO SIMPLIFICADO (APENAS PARA GESTORES E DIRETORES)
Route::middleware(['auth'])->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])
        ->name('register')
        ->middleware('isManagementOrDirector');

    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->name('register.store')
        ->middleware('isManagementOrDirector');
});

Route::prefix('admin')->name('admin.')->group(function () {
    // Rotas explícitas para cursos
    Route::get('/cursos', [CursoController::class, 'index'])->name('cursos.index');
    Route::get('/cursos/create', [CursoController::class, 'create'])->name('cursos.create')->middleware('isManagementOrDirector');
    Route::post('/cursos', [CursoController::class, 'store'])->name('cursos.store')->middleware('isManagementOrDirector');
    Route::get('/cursos/{curso}/edit', [CursoController::class, 'edit'])->name('cursos.edit')->middleware('isManagementOrDirector');
    Route::put('/cursos/{curso}', [CursoController::class, 'update'])->name('cursos.update')->middleware('isManagementOrDirector');
    Route::delete('/cursos/{curso}', [CursoController::class, 'destroy'])->name('cursos.destroy')->middleware('isManagementOrDirector');
    Route::post('/cursos/avancar-ano', [CursoController::class, 'avancarAno'])->name('cursos.avancar-ano')->middleware('isManagementOrDirector');
});

Route::prefix('admin')->name('admin.')->middleware('isManagementOrDirector')->group(function () {
    Route::resource('cursos', \App\Http\Controllers\CursoController::class)->except(['index', 'show']);
    Route::post('cursos/avancar-ano', [\App\Http\Controllers\CursoController::class, 'avancarAno'])
        ->name('cursos.avancar-ano');
    Route::post('cursos/voltar-ano', [\App\Http\Controllers\CursoController::class, 'voltarAno'])
        ->name('cursos.voltar-ano');

    // Rotas para PDFs
    Route::get('pdfs', [\App\Http\Controllers\PdfController::class, 'index'])
        ->name('pdfs.index');
    Route::get('pdfs/download/{filename}', [\App\Http\Controllers\PdfController::class, 'download'])
        ->name('pdfs.download');
    Route::delete('pdfs/delete/{filename}', [\App\Http\Controllers\PdfController::class, 'delete'])
        ->name('pdfs.delete');
    Route::delete('pdfs/delete-all', [\App\Http\Controllers\PdfController::class, 'deleteAll'])
        ->name('pdfs.deleteAll');
});

// JUNTE TODAS AS ROTAS DO PERIODOCONTROLLER EM UM ÚNICO GRUPO
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('cursos/{curso}/ano/{ano}/periodo/{periodo}', [PeriodoController::class, 'show'])
        ->name('periodos.show');

    Route::post('periodos/processar-planilha', [PlanilhaController::class, 'importar'])
        ->name('periodos.processar-planilha')
        ->middleware('isManagementOrDirector');

    Route::post('periodos/salvar-dados', [PeriodoController::class, 'storeData'])
        ->name('periodos.salvar-dados')
        ->middleware('isManagementOrDirector');

    // Rota para o comparativo
    Route::get('cursos/{curso}/ano/{ano}/periodo/{periodo}/comparativo', [PeriodoController::class, 'comparativo'])
        ->name('periodos.comparativo');

    // Rota para salvar metas - CORRIGIDA
    Route::post('periodos/salvar-metas', [PeriodoController::class, 'salvarMetas'])
        ->name('periodos.salvar-metas')
        ->middleware('isManagementOrDirector');

    // Rota para gerar PDF do comparativo
    Route::get('cursos/{curso}/ano/{ano}/periodo/{periodo}/pdf', [PeriodoController::class, 'gerarPdf'])
        ->name('periodos.gerar-pdf');
});

Route::post('/periodos/importar-csv', [PlanilhaController::class, 'importar'])
    ->name('admin.periodos.importar-csv')
    ->middleware('isManagementOrDirector');

require __DIR__.'/auth.php';
