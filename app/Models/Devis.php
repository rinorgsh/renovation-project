<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devis extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'numero_devis',
        'total_ht',
        'total_tva',
        'total_ttc',
        'signature_path',
        'signature_date',    // dans les fillable
        'date_validite',
        'statut'
    ];

    protected $casts = [
        'date_validite' => 'date',
        'total_ht' => 'decimal:2',
        'total_tva' => 'decimal:2',
        'total_ttc' => 'decimal:2'
    ];

    // Relation avec le client
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Relation avec les produits
    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'devis_produit')
            ->withPivot('quantite', 'prix_unitaire', 'total_ligne', 'commentaire') // Ajoutez 'commentaire' ici
            ->withTimestamps();
    }

    // Méthode pour générer un numéro de devis unique
    public static function generateNumeroDevis()
    {
        $year = date('Y');
        $lastDevis = self::whereYear('created_at', $year)->latest()->first();
        $number = $lastDevis ? intval(substr($lastDevis->numero_devis, -4)) + 1 : 1;
        
        return 'DEV-' . $year . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}