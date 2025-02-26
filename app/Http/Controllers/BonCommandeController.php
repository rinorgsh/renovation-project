<?php

namespace App\Http\Controllers;

use App\Models\Devis;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BonCommandeController extends Controller
{
    public function index()
    {
        $bonsCommande = Devis::with('client')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($devis) {
                // Initialiser signature_url à null
                $devis->signature_url = null;

                // Vérifier et générer l'URL de signature
                try {
                    if ($devis->signature_path) {
                        $devis->signature_url = Storage::disk('s3')->temporaryUrl(
                            $devis->signature_path, 
                            now()->addMinutes(30)
                        );
                    }
                } catch (\Exception $e) {
                    // Log l'erreur ou gère-la silencieusement
                    \Log::error('Erreur de génération d\'URL S3: ' . $e->getMessage());
                }

                return $devis;
            });

        return Inertia::render('BonCommande/Index', [
            'bonsCommande' => $bonsCommande
        ]);
    }
}