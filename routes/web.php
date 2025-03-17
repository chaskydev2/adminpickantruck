<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdministratorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RequiredDocumentController;
use App\Http\Controllers\OfertaController;
use App\Http\Controllers\TypeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rutas para administradores
    Route::resource('administrators', AdministratorController::class);
    
    // Rutas para usuarios
    Route::resource('users', UserController::class);
    Route::post('/users/{user}/verify', [UserController::class, 'verify'])->name('users.verify');
    Route::post('/users/{user}/unverify', [UserController::class, 'unverify'])->name('users.unverify');
    
    // Añadir esta ruta para verificar documentos
    Route::get('/users/{user}/check-documents', [UserController::class, 'checkPendingDocuments'])
        ->name('users.check-documents');
        
    // Añadir esta ruta para obtener detalles del usuario
    Route::get('/users/{user}/details', [UserController::class, 'details'])
        ->name('users.details');
    
    // Rutas para ver y administrar documentos de usuarios
    Route::get('/document/{id}', [UserController::class, 'showDocument'])->name('document.show');
    Route::post('/document/{id}/update-status', [UserController::class, 'updateDocumentStatus'])->name('document.update-status');
    
    // Rutas para documentos requeridos - Corregido de DocumentController a RequiredDocumentController
    Route::resource('documents', RequiredDocumentController::class);
    
    // Rutas para ofertas
    Route::get('/ofertas/cargas', [OfertaController::class, 'cargas'])->name('ofertas.cargas');
    Route::get('/ofertas/rutas', [OfertaController::class, 'rutas'])->name('ofertas.rutas');
    Route::get('/ofertas/pujas', [OfertaController::class, 'pujas'])->name('ofertas.pujas');

    // Rutas para tipos de camiones y cargas
    Route::get('/types', [TypeController::class, 'index'])->name('types.index');
    Route::post('/types', [TypeController::class, 'store'])->name('types.store');
    Route::put('/types/{id}', [TypeController::class, 'update'])->name('types.update');
    Route::delete('/types/{id}', [TypeController::class, 'destroy'])->name('types.destroy');
});

// Deshabilitar el registro público pero mantener las rutas de autenticación necesarias
Route::middleware('guest')->group(function () {
    Route::get('login', 'Auth\AuthenticatedSessionController@create')->name('login');
    Route::post('login', 'Auth\AuthenticatedSessionController@store');

    Route::get('forgot-password', 'Auth\PasswordResetLinkController@create')->name('password.request');
    Route::post('forgot-password', 'Auth\PasswordResetLinkController@store')->name('password.email');
    Route::get('reset-password/{token}', 'Auth\NewPasswordController@create')->name('password.reset');
    Route::post('reset-password', 'Auth\NewPasswordController@store')->name('password.update');
});

require __DIR__.'/auth.php';