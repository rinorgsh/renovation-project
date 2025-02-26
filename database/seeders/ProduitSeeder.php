<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produit;

class ProduitSeeder extends Seeder
{
    public function run(): void
    {
        $produits = [
            [
                'nom' => 'Peinture Murale Standard',
                'description' => 'Peinture acrylique pour murs intérieurs, excellente couverture',
                'type' => 'Peinture',
                'prix' => 45.50
            ],
            [
                'nom' => 'Papier Peint Décoratif',
                'description' => 'Papier peint motif géométrique, haute qualité',
                'type' => 'Revêtement Mural',
                'prix' => 29.99
            ],
            [
                'nom' => 'Carrelage Sol Premium',
                'description' => 'Carrelage grès cérame 60x60cm, résistant',
                'type' => 'Carrelage',
                'prix' => 35.00
            ],
            [
                'nom' => 'Parquet Stratifié Chêne',
                'description' => 'Parquet stratifié aspect chêne naturel, classe 32',
                'type' => 'Revêtement Sol',
                'prix' => 28.50
            ],
            [
                'nom' => 'Isolation Thermique',
                'description' => 'Panneau isolant thermique haute performance',
                'type' => 'Isolation',
                'prix' => 42.75
            ],
            [
                'nom' => 'Plaques de Plâtre',
                'description' => 'Plaque de plâtre standard 2.5x1.2m',
                'type' => 'Construction',
                'prix' => 15.99
            ],
            [
                'nom' => 'Joint Silicone',
                'description' => 'Joint silicone blanc pour sanitaires',
                'type' => 'Finition',
                'prix' => 8.50
            ],
            [
                'nom' => 'Enduit de Lissage',
                'description' => 'Enduit de finition pour murs intérieurs',
                'type' => 'Finition',
                'prix' => 19.99
            ],
            [
                'nom' => 'Lambris PVC',
                'description' => 'Lambris PVC blanc, facile à installer',
                'type' => 'Revêtement Mural',
                'prix' => 12.99
            ],
            [
                'nom' => 'Plinthe MDF',
                'description' => 'Plinthe en MDF blanche, 2m',
                'type' => 'Finition',
                'prix' => 9.99
            ]
        ];

        foreach ($produits as $produit) {
            Produit::create($produit);
        }
    }
}