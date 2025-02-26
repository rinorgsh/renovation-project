<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Produit;

Route::get('/produits', function () {
    return response()->json(Produit::all());
});