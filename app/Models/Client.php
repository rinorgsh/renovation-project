<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'personal_id',
        'pays',
        'province',
        'ville',
        'code_postal',
        'adresse',
        'numero_domicile',
        'numero_tva',
        'valeur_tva'
    ];

    protected $attributes = [
        'pays' => 'Belgique'
    ];

    // Accesseur pour obtenir le nom complet
    public function getFullNameAttribute()
    {
        return "{$this->prenom} {$this->nom}";
    }

    // Accesseur pour obtenir l'adresse complète
    public function getFullAddressAttribute()
    {
        return "{$this->adresse}, {$this->numero_domicile} - {$this->code_postal} {$this->ville}";
    }
}