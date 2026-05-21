<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TesteCoresController;
use App\Http\Controllers\PlanoAlimentarController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Plano Alimentar (existing nutrition questionnaire)
Route::get('/planoalimentar', [PlanoAlimentarController::class, 'index'])->name('planoalimentar');
Route::post('/planoalimentar', [PlanoAlimentarController::class, 'store'])->name('planoalimentar.store');

// Teste das Cores (new color personality test)
Route::get('/adietaquefunciona', [TesteCoresController::class, 'index'])->name('teste-cores.index');
Route::get('/cidades/{estado}', [TesteCoresController::class, 'getCidades'])->name('cidades.get');
Route::post('/adietaquefunciona', [TesteCoresController::class, 'store'])->name('teste-cores.store');
Route::get('/resultado/{session}', [TesteCoresController::class, 'resultado'])->name('teste-cores.resultado');

// Admin routes (protected by auth and isAdmin middleware)
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/respostas', [AdminController::class, 'index'])->name('admin.respostas');
    Route::get('/respostas/{session}', [AdminController::class, 'show'])->name('admin.respostas.show');
    Route::post('/respostas/{session}/gerar-analise', [AdminController::class, 'generateAnalysis'])->name('admin.respostas.generate-analysis');
    Route::get('/teste-supabase', [AdminController::class, 'testeSupabase'])->name('admin.teste-supabase');
    Route::get('/formulario-geral', [AdminController::class, 'formularioGeral'])->name('admin.formulario-geral');
    Route::post('/formulario-geral/{id}/gerar-analise', [AdminController::class, 'generateFormularioGeralAnalysis'])->name('admin.formulario-geral.generate-analysis');
    Route::get('/prompts', [AdminController::class, 'editPrompts'])->name('admin.prompts.edit');
    Route::post('/prompts', [AdminController::class, 'updatePrompts'])->name('admin.prompts.update');

    // Archive/Restore routes
    Route::get('/arquivados', [AdminController::class, 'arquivados'])->name('admin.arquivados');
    Route::post('/diagnostico/{session}/delete', [AdminController::class, 'deleteDiagnostico'])->name('admin.diagnostico.delete');
    Route::post('/diagnostico/{session}/restore', [AdminController::class, 'restoreDiagnostico'])->name('admin.diagnostico.restore');
    Route::post('/formulario-geral/{id}/delete', [AdminController::class, 'deleteFormularioGeral'])->name('admin.formulario-geral.delete');
    Route::post('/formulario-geral/{id}/restore', [AdminController::class, 'restoreFormularioGeral'])->name('admin.formulario-geral.restore');
});

// Authentication routes
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserManagementController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Redirect dashboard to admin dashboard
Route::get('/dashboard', function () {
    if (Auth::check() && Auth::user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('admin.dashboard');
})->middleware(['auth'])->name('dashboard');

// User management routes
Route::middleware(['auth'])->group(function () {
    Route::post('/user/change-password', [UserManagementController::class, 'changePassword'])->name('user.change-password');
});

// Admin user management routes
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->group(function () {
    Route::get('/users', [UserManagementController::class, 'index'])->name('admin.users');
    Route::post('/users/{id}/reset-password', [UserManagementController::class, 'resetPassword'])->name('admin.users.reset-password');
    Route::post('/users/{id}/promote', [UserManagementController::class, 'promoteToAdmin'])->name('admin.users.promote');
    Route::post('/users/{id}/demote', [UserManagementController::class, 'demoteFromAdmin'])->name('admin.users.demote');
});
