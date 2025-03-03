<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\DevisController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\BonCommandeController;
use App\Http\Controllers\DashboardController;



// Routes publiques
Route::get('/', function () {
    return Inertia::render('Auth/Login');
})->middleware('guest')->name('login.page');

// Routes protégées par auth
Route::middleware(['auth'])->group(function () {
    // Route d'accueil
    Route::get('/home', [DashboardController::class, 'index'])->name('home');


    // Routes des devis
    Route::get('/devis', [DevisController::class, 'index'])->name('devis.index');
    Route::get('/devis/create', [DevisController::class, 'create'])->name('devis.create');
    Route::post('/devis', [DevisController::class, 'store'])->name('devis.store');

    Route::get('/devis/{devis}', [DevisController::class, 'show'])->name('devis.show');

    Route::get('/devis/{devis}/download', [DevisController::class, 'downloadPDF'])->name('devis.download');
    Route::get('/devis/{devis}/download-invoice', [DevisController::class, 'downloadInvoice'])->name('devis.download-invoice');
    Route::post('/devis/{devis}/send-pdf', [DevisController::class, 'sendPDF'])->name('devis.send-pdf');
    Route::get('/devis/{devis}/edit', [DevisController::class, 'edit'])->name('devis.edit');
    Route::put('/devis/{devis}', [DevisController::class, 'update'])->name('devis.update');
    Route::post('/devis/{devis}/update-status', [DevisController::class, 'updateStatus'])->name('devis.update-status');
    Route::delete('/devis/{devis}', [DevisController::class, 'destroy'])->name('devis.destroy');


    // Routes des clients
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/{client}', [ClientController::class, 'show'])->name('clients.show');

    // Routes des produits
    Route::get('/produits', [ProduitController::class, 'index'])->name('produits.index');
    Route::get('/bon-commande', [BonCommandeController::class, 'index'])->name('bon-commande.index');

    
});

require __DIR__.'/auth.php';