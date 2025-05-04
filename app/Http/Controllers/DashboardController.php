<?php

namespace App\Http\Controllers;

use App\Models\Devis;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Récupérer seulement les devis de l'utilisateur connecté avec leur relation client
        $devis = Devis::with('client')
                      ->where('user_id', Auth::id())
                      ->get();
        
        $user = Auth::user();
        
        return Inertia::render('Home', [
            'devis' => $devis
        ]);
    }
}