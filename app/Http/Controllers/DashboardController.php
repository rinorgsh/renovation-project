<?php

namespace App\Http\Controllers;

use App\Models\Devis;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Récupérer tous les devis avec leur relation client
        $devis = Devis::with('client')->get();
        $user = Auth::user();
        return Inertia::render('Home', [
            'devis' => $devis
        ]);
    }
}