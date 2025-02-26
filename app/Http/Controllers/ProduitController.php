<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProduitController extends Controller
{
    public function index()
    {
        $produits = Produit::all();
        return response()->json($produits);
    }
}