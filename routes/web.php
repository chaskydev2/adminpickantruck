<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CargaController;
use App\Http\Controllers\RutaController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\UserDocumentController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Artisan;


Route::get('/refresh', function(){
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    Artisan::call('route:clear ');
    Artisan::call('optimize:clear');
    $link = public_path('storage');
    $target = storage_path('app/public');
     Artisan::call('storage:link');
    if (File::exists($link)) {
        return "El enlace simbólico ya existe.";
    }
    try {
        Artisan::call('storage:link');
        return "Enlace simbólico creado con éxito.";
    } catch (\Exception $e) {
        return "Error al crear el enlace simbólico: " . $e->getMessage();
    }
    return "Cleared!";
});

// Ruta de inicio redirige al login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas
Route::middleware(['auth'])->group(function () {
    // Perfil de administrador
    Route::prefix('perfil')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/editar', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::get('/cambiar-contrasena', [ProfileController::class, 'showChangePassword'])->name('change-password');
        Route::put('/cambiar-contrasena', [ProfileController::class, 'updatePassword'])->name('update-password');
    });
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Buscador
    Route::match(['get', 'options'], '/search/users', [SearchController::class, 'searchUsers'])->name('search.users');
    Route::match(['get', 'options'], '/search/cargas', [SearchController::class, 'searchCargas'])->name('search.cargas');
    Route::match(['get', 'options'], '/search/rutas', [SearchController::class, 'searchRutas'])->name('search.rutas');
    Route::match(['get', 'options'], '/search/documents', [SearchController::class, 'searchDocuments'])->name('search.documents');
    Route::match(['get', 'options'], '/search/bids', [SearchController::class, 'searchBids'])->name('search.bids');
    Route::match(['get', 'options'], '/search/global', [SearchController::class, 'searchGlobal'])->name('search.global');
    Route::options('/search/users', [SearchController::class, 'options']);
    Route::options('/search/cargas', [SearchController::class, 'options']);
    Route::options('/search/rutas', [SearchController::class, 'options']);
    Route::options('/search/documents', [SearchController::class, 'options']);
    Route::options('/search/bids', [SearchController::class, 'options']);
    Route::options('/search/global', [SearchController::class, 'options']);

    // Rutas de usuarios
    Route::prefix('usuarios')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::post('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{user}/toggle-verification', [UserController::class, 'toggleVerification'])->name('toggle-verification');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

    // Cargas
    Route::prefix('cargas')->group(function () {
        Route::get('/', [CargaController::class, 'index'])->name('cargas.index');
        Route::get('/crear', [CargaController::class, 'create'])->name('cargas.create');
        Route::post('/', [CargaController::class, 'store'])->name('cargas.store');
        Route::get('/{carga}', [CargaController::class, 'show'])->name('cargas.show');
        Route::get('/{carga}/editar', [CargaController::class, 'edit'])->name('cargas.edit');
        Route::put('/{carga}', [CargaController::class, 'update'])->name('cargas.update');
        Route::delete('/{carga}', [CargaController::class, 'destroy'])->name('cargas.destroy');
        Route::get('/usuario/{userId}', [CargaController::class, 'porUsuario'])->name('cargas.por-usuario');
    });

    // Rutas de Pujas
    Route::prefix('pujas')->group(function () {
        Route::get('/', [BidController::class, 'index'])->name('bids.index');
        Route::get('/{bid}', [BidController::class, 'show'])->name('bids.show');
        Route::patch('/{bid}/status', [BidController::class, 'updateStatus'])->name('bids.update-status');
        Route::post('/{bid}/confirm/{tipoUsuario}', [BidController::class, 'confirmarAceptacion'])->name('bids.confirm');
    });

    // Rutas de Chat
    Route::prefix('chats')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('chats.index');
        Route::get('/{chat}', [ChatController::class, 'show'])->name('chats.show');
        Route::post('/{chat}/message', [ChatController::class, 'sendMessage'])->name('chats.message.send');
        Route::get('/{chat}/messages', [ChatController::class, 'getMessages'])->name('chats.messages');
        Route::post('/{chat}/read', [ChatController::class, 'markAsRead'])->name('chats.markAsRead');
    });

    // Rutas de Ofertas de Ruta
    Route::prefix('rutas')->group(function () {
        Route::get('/', [RutaController::class, 'index'])->name('rutas.index');
        Route::get('/crear', [RutaController::class, 'create'])->name('rutas.create');
        Route::post('/', [RutaController::class, 'store'])->name('rutas.store');
        Route::get('/{ruta}', [RutaController::class, 'show'])->name('rutas.show');
        Route::get('/{ruta}/editar', [RutaController::class, 'edit'])->name('rutas.edit');
        Route::put('/{ruta}', [RutaController::class, 'update'])->name('rutas.update');
        Route::delete('/{ruta}', [RutaController::class, 'destroy'])->name('rutas.destroy');
        Route::get('/usuario/{userId}', [RutaController::class, 'porUsuario'])->name('rutas.por-usuario');
    });

    // Rutas para documentos requeridos
    Route::prefix('documentos')->group(function () {
        Route::get('/', [DocumentController::class, 'index'])->name('documents.index');
        Route::get('/crear', [DocumentController::class, 'create'])->name('documents.create');
        Route::post('/', [DocumentController::class, 'store'])->name('documents.store');
        Route::get('/{document}/editar', [DocumentController::class, 'edit'])->name('documents.edit');
        Route::put('/{document}', [DocumentController::class, 'update'])->name('documents.update');
        Route::delete('/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    });

    // Rutas para documentos subidos por usuarios
    Route::prefix('documentos-usuarios')->group(function () {
        Route::get('/', [UserDocumentController::class, 'index'])->name('user-documents.index');
        Route::get('/{document}/editar', [UserDocumentController::class, 'edit'])->name('user-documents.edit');
        Route::put('/{document}', [UserDocumentController::class, 'update'])->name('user-documents.update');
        Route::get('/{document}', [UserDocumentController::class, 'show'])->name('user-documents.show');
        Route::delete('/{document}', [UserDocumentController::class, 'destroy'])->name('user-documents.destroy');
    });

});