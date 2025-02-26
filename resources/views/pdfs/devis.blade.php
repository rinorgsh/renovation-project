<!DOCTYPE html>
<html>
<head>
    <title>Devis #{{ $devis->numero_devis }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .header { 
            text-align: center; 
            margin-bottom: 20px; 
            padding-bottom: 10px; 
            border-bottom: 2px solid #000; 
        }
        .client-info, .company-info { 
            margin-bottom: 20px; 
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px; 
        }
        table, th, td { 
            border: 1px solid #ddd; 
            padding: 8px; 
        }
        .totals { 
            text-align: right; 
        }
        .commentaire {
            background-color: #f9f9f9;
            font-style: italic;
            padding: 5px 8px;
            font-size: 0.9em;
            border-top: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Bon de Commande #{{ $devis->numero_devis }}</h1>
        <p>Date: {{ $devis->created_at->format('d/m/Y') }}</p>
    </div>

    <div class="client-info">
        <h2>Informations Client</h2>
        <p>
            <strong>Nom :</strong> {{ $devis->client->prenom }} {{ $devis->client->nom }}<br>
            <strong>Email :</strong> {{ $devis->client->email }}<br>
            <strong>Téléphone :</strong> {{ $devis->client->telephone }}<br>
            <strong>Adresse :</strong> {{ $devis->client->adresse }}, {{ $devis->client->code_postal }} {{ $devis->client->ville }}
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Prix Unitaire</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($devis->produits as $produit)
            <tr>
                <td>{{ $produit->nom }}</td>
                <td>{{ $produit->pivot->quantite }}</td>
                <td>{{ number_format($produit->pivot->prix_unitaire, 2) }}€</td>
                <td>{{ number_format($produit->pivot->total_ligne, 2) }}€</td>
            </tr>
            @if($produit->pivot->commentaire)
            <tr>
                <td colspan="4" class="commentaire">
                    <strong>Commentaire:</strong> {{ $produit->pivot->commentaire }}
                </td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <p><strong>Total HT :</strong> {{ number_format($devis->total_ht, 2) }}€</p>
        <p><strong>TVA :</strong> {{ number_format($devis->total_tva, 2) }}€</p>
        <p><strong>Total TTC :</strong> {{ number_format($devis->total_ttc, 2) }}€</p>
    </div>
</body>
</html>