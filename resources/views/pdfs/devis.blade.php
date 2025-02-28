<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bon de commande #{{ $devis->numero_devis }}</title>
    <style>
        /* Style général */
body {
    font-family: 'Helvetica Neue', Arial, sans-serif;
    font-size: 14px;
    line-height: 1.42857143;
    color: #333;
    background-color: #fff;
    margin: 0;
    padding: 20px;
}

/* En-tête */
.header {
    margin-bottom: 30px;
    border-bottom: 1px solid #ddd;
    padding-bottom: 20px;
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
}

.header-section {
    margin-bottom: 15px;
}

.logo {
    max-height: 80px;
    margin-bottom: 15px;
}

.company-info {
    color: #555;
}

.devis-info {
    text-align: right;
}

.devis-info h2 {
    color: #337ab7;
    margin-top: 0;
}

/* Information client */
.client-info {
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 4px;
    margin-bottom: 20px;
    border-left: 5px solid #337ab7;
}

.client-info h2 {
    color: #337ab7;
    margin-top: 0;
    font-size: 18px;
}

/* Tableau */
table {
    width: 100%;
    max-width: 100%;
    margin-bottom: 20px;
    border-collapse: collapse;
    border-spacing: 0;
}

table th {
    background-color: #337ab7;
    color: white;
    text-align: left;
    padding: 10px;
    border-bottom: 2px solid #ddd;
    font-weight: 600;
}

table td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
    vertical-align: middle;
}

table tr:hover {
    background-color: #f5f5f5;
}

.commentaire {
    background-color: #f8f9fa;
    font-style: italic;
    color: #666;
    padding: 8px 10px;
    font-size: 13px;
}

/* Totaux */
.totals {
    margin: 20px 0;
    padding: 15px;
    text-align: right;
    background-color: #f8f9fa;
    border-radius: 4px;
}

.totals p {
    margin: 5px 0;
}

.totals p:last-child {
    font-size: 18px;
    font-weight: bold;
    color: #337ab7;
    padding-top: 10px;
    border-top: 1px solid #ddd;
    margin-top: 10px;
}

/* Pied de page */
.footer {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #ddd;
    color: #777;
    font-size: 12px;
    text-align: center;
}

/* Responsive design */
@media print, (min-width: 768px) {
    .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    
    .company-info {
        flex: 1;
        text-align: left;
    }
    
    .devis-info {
        flex: 1;
        text-align: right;
    }
}

@media (max-width: 767px) {
    .header-section {
        width: 100%;
        text-align: center;
    }
    
    .devis-info {
        text-align: center;
    }
    
    table th, table td {
        padding: 5px;
    }
}

/* Bouton d'impression optionnel */
.print-btn {
    display: inline-block;
    padding: 6px 12px;
    margin-bottom: 15px;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.42857143;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    cursor: pointer;
    background-color: #337ab7;
    border: 1px solid transparent;
    border-radius: 4px;
    color: #fff;
    text-decoration: none;
}

.print-btn:hover {
    background-color: #286090;
}

@media print {
    .print-btn {
        display: none;
    }
}
    </style>
</head>
<body>
    <div class="container">
        
        <div class="header">
            <div class="header-section">
                <img src="/public/image/logo.png" alt="Renowall" class="logo">
            </div>
            
            <div class="header-section company-info">
                <p>
                    <strong>Renowall</strong><br>
                    123 Rue de l'Entreprise<br>
                    75000 Paris, France<br>
                    Tél: 01 23 45 67 89<br>
                    Email: contact@renowall.com<br>
                    Web: www.renowall.com
                </p>
            </div>
            
            <div class="header-section devis-info">
                <h2>Bon de commande #{{ $devis->numero_devis }}</h2>
                <p>Date: {{ $devis->created_at->format('d/m/Y') }}</p>
                <p>Validité: 30 jours</p>
            </div>
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
        
        <table class="table">
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
        
        <div class="footer">
            <p>Renowall - SIRET: 123 456 789 00010 - TVA Intracommunautaire: FR12345678900</p>
            <p>Merci pour votre confiance. Pour toute question concernant ce bon de commande, n'hésitez pas à nous contacter.</p>
        </div>
    </div>
</body>
</html>