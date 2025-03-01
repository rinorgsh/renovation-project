<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Devis;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\DevisMail;
use Illuminate\Support\Facades\Mail;

class DevisController extends Controller
{

    /**
     * Affiche la page de création de devis
     * Liste tous les clients et produits disponibles
     */
    public function index()
    {
        $clients = Client::all();
        $produits = Produit::all();

        return Inertia::render('Devis/Index', [
            'produits' => $produits,
            'clients' => $clients
        ]);
    }
    public function create()
    {
        $clients = Client::all();
        $produits = Produit::all();

        return Inertia::render('Devis/Create', [
            'produits' => $produits,
            'clients' => $clients
        ]);
    }

    /**
     * Stocke un nouveau devis avec gestion du client et des produits
     */
    public function store(Request $request)
    {
         // Validation des données
    $validatedData = $request->validate([
        'client' => 'required|array',
        'produits' => 'required|array',
        'total_ht' => 'required|numeric',
        'total_tva' => 'required|numeric',
        'total_ttc' => 'required|numeric',
        'signature_data' => 'nullable|string'
    ]);

    $clientData = $request->input('client');

    // Gestion du client (existant ou nouveau)
    if (!empty($clientData['existing_client_id'])) {
        // Si un client existant est sélectionné, utiliser cet ID
        $client = Client::findOrFail($clientData['existing_client_id']);
    } else {
        // Vérifier si un client avec cet email existe déjà
        $existingClient = Client::where('email', $clientData['email'])->first();
        
        if ($existingClient) {
            // Utiliser le client existant
            $client = $existingClient;
        } else {
            // Créer un nouveau client si aucun n'existe
            $client = Client::create($clientData);
        }
    }

    // Mise à jour des informations du client si nécessaire
    $client->update(array_filter([
        'email' => $clientData['email'] ?? $client->email,
        'telephone' => $clientData['telephone'] ?? $client->telephone,
        'pays' => $clientData['pays'] ?? $client->pays,
        'province' => $clientData['province'] ?? $client->province,
        'ville' => $clientData['ville'] ?? $client->ville,
        'code_postal' => $clientData['code_postal'] ?? $client->code_postal,
        'adresse' => $clientData['adresse'] ?? $client->adresse,
        'numero_domicile' => $clientData['numero_domicile'] ?? $client->numero_domicile,
        'numero_tva' => $clientData['numero_tva'] ?? $client->numero_tva,
    ]));

        // Gestion de la signature
        $signatureFilename = null;
        if (!empty($request->input('signature_data'))) {
            $signatureData = $request->input('signature_data');
            
            // Supprimer l'en-tête base64
            $signatureData = preg_replace('/^data:image\/\w+;base64,/', '', $signatureData);
            
            // Décoder les données base64
            $signatureDecoded = base64_decode($signatureData);
            
            // Générer un nom de fichier unique
            $signatureFilename = 'signatures/' . Str::uuid() . '.png';
            
            try {
                // Créer le client S3
                $s3Client = new S3Client([
                    'version'     => 'latest',
                    'region'      => env('AWS_DEFAULT_REGION'),
                    'credentials' => [
                        'key'    => env('AWS_ACCESS_KEY_ID'),
                        'secret' => env('AWS_SECRET_ACCESS_KEY'),
                    ],
                    'endpoint'    => env('AWS_ENDPOINT'),
                    'use_path_style_endpoint' => true,
                ]);

                // Tester la connexion
                try {
                    $result = $s3Client->listBuckets();
                    $buckets = $result['Buckets'] ?? [];
                    
                    \Log::info('Buckets disponibles:', 
                        array_map(function($bucket) { 
                            return $bucket['Name'] ?? 'Nom inconnu';
                        }, $buckets)
                    );
                } catch (\Exception $e) {
                    \Log::error('Erreur de listage des buckets : ' . $e->getMessage());
                    \Log::error('Trace : ' . $e->getTraceAsString());
                }

                // Tentative d'upload
                $result = $s3Client->putObject([
                    'Bucket' => env('AWS_BUCKET'),
                    'Key'    => $signatureFilename,
                    'Body'   => $signatureDecoded,
                    'ContentType' => 'image/png'
                ]);

                \Log::info('Upload S3 réussi', ['result' => $result]);
            } catch (\Exception $e) {
                \Log::error('Erreur détaillée S3 : ' . $e->getMessage());
                \Log::error('Trace : ' . $e->getTraceAsString());
            }
        }

        // Création du devis
        $devis = Devis::create([
            'client_id' => $client->id,
            'numero_devis' => $this->generateNumeroDevis(),
            'total_ht' => $request->input('total_ht'),
            'total_tva' => $request->input('total_tva'),
            'total_ttc' => $request->input('total_ttc'),
            'signature_path' => $signatureFilename,
            'signature_date' => now(),
            'date_validite' => now()->addMonths(1),
            'statut' => 'en_attente'
        ]);

        // Attacher les produits au devis
        foreach ($request->input('produits') as $produit) {
            $devis->produits()->attach($produit['produit']['id'], [
                'quantite' => $produit['mesure'],
                'prix_unitaire' => $produit['prix'], // utilisez le prix modifié
                'total_ligne' => $produit['prix'] * $produit['mesure'], // calculez avec le prix modifié
                'commentaire' => $produit['commentaire'] ?? null // Ajout du commentaire
            ]);
        }

        return redirect()->route('home')->with('success', 'Devis créé avec succès');
    }

    /**
     * Affiche les détails d'un devis spécifique
     */
    public function show(Devis $devis)
    {
        // Charger les relations client et produits
        $devis->load(['client', 'produits']);

        // Générer une URL temporaire pour la signature si elle existe
        $signatureUrl = null;
        if ($devis->signature_path) {
            $signatureUrl = Storage::disk('s3')->temporaryUrl(
                $devis->signature_path, 
                now()->addMinutes(30)
            );
        }

        
        return Inertia::render('Devis/Show', [
            'devis' => $devis,
            'signature_url' => $signatureUrl
        ]);
    }

    /**
     * Liste tous les devis
     */
    public function list()
    {
        $devis = Devis::with(['client'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('Devis/List', [
            'devis' => $devis
        ]);
    }

    /**
     * Génère un numéro de devis unique
     */
    private function generateNumeroDevis()
    {
        // Logique de génération de numéro de devis unique
        $lastDevis = Devis::orderBy('id', 'desc')->first();
        $newNumero = $lastDevis ? intval(substr($lastDevis->numero_devis, 2)) + 1 : 1;
        return 'BC' . str_pad($newNumero, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Supprime un devis
     */
    public function destroy(Devis $devis)
    {
        // Supprimer la signature de S3 si elle existe
        if ($devis->signature_path) {
            Storage::disk('s3')->delete($devis->signature_path);
        }

        // Détacher les produits
        $devis->produits()->detach();

        // Supprimer le devis
        $devis->delete();

        return redirect()->route('devis.list')->with('success', 'Devis supprimé avec succès');
    }

    /**
     * Récupère la signature d'un devis
     */
    public function getSignature(Devis $devis)
    {
        // Vérifier les permissions si nécessaire
        // $this->authorize('view', $devis);

        if ($devis->signature_path) {
            $signatureUrl = Storage::disk('s3')->temporaryUrl(
                $devis->signature_path, 
                now()->addMinutes(30)
            );

            return response()->json([
                'signature_url' => $signatureUrl
            ]);
        }

        return response()->json(['message' => 'Aucune signature trouvée'], 404);
    }

    public function downloadPDF(Devis $devis)
{
    // Charger les relations
    $devis->load(['client', 'produits']);

    // Générer l'URL de signature si elle existe
    $signatureUrl = null;
    if ($devis->signature_path) {
        try {
            // Récupérer l'URL temporaire du fichier stocké sur S3
            $signatureUrl = Storage::disk('s3')->temporaryUrl(
                $devis->signature_path, 
                now()->addHours(1) // Augmenter la durée de validité de l'URL
            );
            
            // Vérifier que l'URL est bien générée
            \Log::info('URL de signature générée : ' . $signatureUrl);
        } catch (\Exception $e) {
            \Log::error('Erreur de génération d\'URL de signature : ' . $e->getMessage());
            \Log::error('Trace : ' . $e->getTraceAsString());
        }
    }

    // Générer le PDF
    try {
        $pdf = PDF::loadView('pdfs.devis', [
            'devis' => $devis,
            'signature_url' => $signatureUrl
        ])->setOption('isRemoteEnabled', true);
        
        // Télécharger le PDF
        return $pdf->download("BonDeCommande-{$devis->numero_devis}.pdf");
    } catch (\Exception $e) {
        \Log::error('Erreur lors de la génération du PDF : ' . $e->getMessage());
        \Log::error('Trace : ' . $e->getTraceAsString());
        return back()->with('error', 'Impossible de générer le PDF');
    }
}

    public function sendPDF(Devis $devis)
    {
        try {
            // Charger les relations nécessaires
            $devis->load('client');

            // Envoi de l'email
            Mail::to($devis->client->email)->send(new DevisMail($devis));

            return back()->with('success', 'PDF envoyé au client avec succès');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'envoi du PDF : ' . $e->getMessage());
            return back()->with('error', 'Impossible d\'envoyer le PDF');
        }
    }
    /**
 * Affiche le formulaire d'édition pour un devis existant
 */
public function edit(Devis $devis)
{
    // Charger les relations nécessaires
    $devis->load(['client', 'produits']);
    
    // Récupérer tous les clients et produits pour les listes déroulantes
    $clients = Client::all();
    $produits = Produit::all();
    
    // Formater les produits pour l'édition
    $selectedProducts = $devis->produits->map(function ($produit) {
        return [
            'produit' => $produit,
            'prix' => $produit->pivot->prix_unitaire,
            'mesure' => $produit->pivot->quantite,
            'commentaire' => $produit->pivot->commentaire ?? ''
        ];
    })->toArray();

    return Inertia::render('Devis/Edit', [
        'devis' => $devis,
        'clientData' => $devis->client,
        'produits' => $produits,
        'clients' => $clients,
        'selectedProducts' => $selectedProducts
    ]);
}

/**
 * Met à jour un devis existant
 */
public function update(Request $request, Devis $devis)
{
    // Validation des données
    $validatedData = $request->validate([
        'client' => 'required|array',
        'produits' => 'required|array',
        'total_ht' => 'required|numeric',
        'total_tva' => 'required|numeric',
        'total_ttc' => 'required|numeric',
        'signature_data' => 'nullable|string'
    ]);

    $clientData = $request->input('client');

    // Mettre à jour ou créer le client
    if (!empty($clientData['existing_client_id'])) {
        $client = Client::findOrFail($clientData['existing_client_id']);
    } else {
        // Vérifier si un client avec cet email existe déjà
        $existingClient = Client::where('email', $clientData['email'])->first();
        
        if ($existingClient) {
            $client = $existingClient;
        } else {
            $client = Client::create($clientData);
        }
    }

    // Mise à jour des informations du client
    $client->update(array_filter([
        'email' => $clientData['email'] ?? $client->email,
        'telephone' => $clientData['telephone'] ?? $client->telephone,
        'pays' => $clientData['pays'] ?? $client->pays,
        'province' => $clientData['province'] ?? $client->province,
        'ville' => $clientData['ville'] ?? $client->ville,
        'code_postal' => $clientData['code_postal'] ?? $client->code_postal,
        'adresse' => $clientData['adresse'] ?? $client->adresse,
        'numero_domicile' => $clientData['numero_domicile'] ?? $client->numero_domicile,
        'numero_tva' => $clientData['numero_tva'] ?? $client->numero_tva,
    ]));

    // Gérer la nouvelle signature si fournie
    $signatureFilename = $devis->signature_path; // Conserver l'ancienne signature par défaut
    if (!empty($request->input('signature_data')) && $request->input('signature_data') != 'unchanged') {
        // Supprimer l'ancienne signature si elle existe
        if ($devis->signature_path) {
            Storage::disk('s3')->delete($devis->signature_path);
        }
        
        // Traiter la nouvelle signature
        $signatureData = $request->input('signature_data');
        $signatureData = preg_replace('/^data:image\/\w+;base64,/', '', $signatureData);
        $signatureDecoded = base64_decode($signatureData);
        $signatureFilename = 'signatures/' . Str::uuid() . '.png';
        
        try {
            $s3Client = new S3Client([
                'version'     => 'latest',
                'region'      => env('AWS_DEFAULT_REGION'),
                'credentials' => [
                    'key'    => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY'),
                ],
                'endpoint'    => env('AWS_ENDPOINT'),
                'use_path_style_endpoint' => true,
            ]);

            $s3Client->putObject([
                'Bucket' => env('AWS_BUCKET'),
                'Key'    => $signatureFilename,
                'Body'   => $signatureDecoded,
                'ContentType' => 'image/png'
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur S3 lors de la mise à jour : ' . $e->getMessage());
        }
    }

    // Mise à jour du devis
    $devis->update([
        'client_id' => $client->id,
        'total_ht' => $request->input('total_ht'),
        'total_tva' => $request->input('total_tva'),
        'total_ttc' => $request->input('total_ttc'),
        'signature_path' => $signatureFilename,
        'date_validite' => now()->addMonths(1),
    ]);

    // Mettre à jour les produits du devis (supprimer puis recréer les relations)
    $devis->produits()->detach();
    
    foreach ($request->input('produits') as $produit) {
        $devis->produits()->attach($produit['produit']['id'], [
            'quantite' => $produit['mesure'],
            'prix_unitaire' => $produit['prix'],
            'total_ligne' => $produit['prix'] * $produit['mesure'],
            'commentaire' => $produit['commentaire'] ?? null
        ]);
    }

    return redirect()->route('devis.show', $devis->id)->with('success', 'Devis mis à jour avec succès');
}
public function updateStatus(Request $request, Devis $devis)
{
    $validated = $request->validate([
        'status' => 'required|in:en_attente,acompte,paye'
    ]);

    $devis->update([
        'statut' => $validated['status']
    ]);

    return redirect()->back()->with('success', 'Statut mis à jour avec succès');
}
}