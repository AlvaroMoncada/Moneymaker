<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Ruta para la página de inicio. Redirige al usuario al dashboard.
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Agrupa las rutas que requieren autenticación.
Route::middleware('auth')->group(function () {
    // Pantalla Principal (Dashboard)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Ruta para la página 'About'. Renderiza la vista 'About'.
    Route::get('/about', fn () => Inertia::render('About'))->name('about');

    // Rutas para gestionar el perfil del usuario:
    // Muestra el formulario de edición del perfil.
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Actualiza el perfil del usuario.
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Elimina el perfil del usuario.
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas de recursos para gestionar ventas y clientes.
    Route::resource('sells', SellController::class);
    Route::resource('clients', ClientController::class);

});

// Requiere el archivo de rutas de autenticación.
require __DIR__.'/auth.php';
