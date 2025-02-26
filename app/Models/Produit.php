<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'image',
        'type',
        'prix'
    ];

    // Convertir automatiquement les prix en centimes/euros
    protected $casts = [
        'prix' => 'decimal:2'
    ];

    public function devis()
    {
        return $this->belongsToMany(Devis::class, 'devis_produit')
            ->withPivot('quantite', 'prix_unitaire', 'total_ligne')
            ->withTimestamps();
    }
}