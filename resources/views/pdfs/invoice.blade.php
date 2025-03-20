<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture Proforma #{{ $devis->numero_devis }} - Renowall</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333333;
            font-size: 10pt;
        }
        
        .proforma-header {
            font-size: 24pt;
            color: #0066cc; /* Changer de #009970 à #0066cc (bleu) */
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        .company-header {
            width: 100%;
            display: table;
            margin-bottom: 30px;
        }
        
        .logo-container {
            display: table-cell;
            width: 40%;
            vertical-align: top;
        }
        
        .company-info {
            display: table-cell;
            width: 60%;
            vertical-align: top;
            text-align: right;
            font-size: 12pt;
        }
        
        .client-details {
            width: 100%;
            display: table;
            margin-bottom: 30px;
            border-top: 2px solid #0066cc; /* Changer de #009970 à #0066cc */
            border-bottom: 2px solid #0066cc; /* Changer de #009970 à #0066cc */
            padding: 10px 0;
        }
        
        .client-info {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        
        .proforma-info {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            text-align: right;
        }
        
        table.product-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        table.product-table th {
            background-color: #0066cc; /* Changer de #009970 à #0066cc */
            color: white;
            padding: 8px;
            text-align: left;
        }
        
        table.product-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #ddd;
            vertical-align: top;
        }
        
        .product-description {
            font-size: 9pt;
        }
        
        /* Styles modifiés pour garder le tableau des totaux sur une seule page */
        .totals-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            page-break-inside: avoid; /* Empêche la division du tableau */
            break-inside: avoid; /* Propriété moderne pour éviter les sauts de page */
        }
        
        .totals-table td {
            padding: 5px;
        }
        
        .totals-table tr:last-child {
            font-weight: bold;
            font-size: 12pt;
            background-color: #f2f2f2;
        }
        
        .totals-table tr td:first-child {
            text-align: right;
            width: 80%;
        }
        
        .footer {
            position: relative; /* Changer de fixed à relative */
            bottom: 30px;
            left: 0;
            right: 0;
            background-color: #e8f0ff; /* Changer de #e8f7ef (vert clair) à #e8f0ff (bleu clair) */
            padding: 10px;
            text-align: center;
            font-size: 9pt;
            border-top: 1px solid #ddd;
            margin-top: 20px;
        }
        
        .footer img {
            display: inline-block;
            vertical-align: middle;
            height: 30px;
        }
        
        .payment-info {
            margin-top: 40px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            page-break-before: avoid; /* Évite un saut de page avant les infos de paiement */
        }
        
        .notes {
            margin-top: 30px;
            font-style: italic;
            font-size: 9pt;
        }
        /* Ajouter ces styles au bloc CSS existant */
        
        /* Styles pour les images de produits */
        table.product-table img {
            max-width: 90px;
            max-height: 90px;
            border-radius: 4px;
            object-fit: contain;
        }

        table.product-table th:first-child,
        table.product-table td:first-child {
            width: 100px;
            text-align: center;
            vertical-align: middle;
        }

        .product-image-placeholder {
            width: 90px;
            height: 90px;
            background-color: #f5f5f5;
            border-radius: 4px;
            border: 1px solid #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 12px;
            margin: 0 auto;
        }
        
        /* Conteneur pour le tableau des totaux et les informations de paiement */
        .totals-payment-container {
            page-break-inside: avoid;
            break-inside: avoid;
        }
    </style>
</head>
<body>
    
    <div class="company-header">
        <div class="logo-container">
            <!-- Logo Renowall -->
            <img src="image/logo.png" alt="Renowall" height="150" weight= "150">
            </div>
        <div class="company-info">
            <strong>Renowall</strong><br>
            Oudesmidsestraat 20,<br>
            1700 Dilbeek<br>
            Tél: +32 479 76 04 42<br>
            Email: info@renowall.be<br>
            Web: www.renowall.be
        </div>
    </div>
    
    <div class="client-details">
        <div class="client-info">
            <strong>Client:</strong> {{ $devis->client->prenom }} {{ $devis->client->nom }}<br>
            <strong>Adresse:</strong> {{ $devis->client->adresse }}<br>
            <strong>Ville:</strong> {{ $devis->client->province ?? 'Brabant' }}, {{ $devis->client->ville }}, {{ $devis->client->code_postal }}<br>
            <strong>Tél:</strong> {{ $devis->client->telephone }}
        </div>
        <div class="proforma-info">
            <strong>Nombre:</strong> P-{{ str_replace('BC', '', $devis->numero_devis) }}<br>
            <strong>Date:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y') }}<br>
            <strong>Proforma:</strong> {{ date('d/m/Y', strtotime('+14 days')) }}
        </div>
    </div>
    
    <!-- Modification du tableau des produits avec ajout de colonne image -->
<table class="product-table">
    <thead>
        <tr>
            <th style="width: 100px;">Image</th>
            <th>Description</th>
            <th>Nombre</th>
            <th>Prix unitaire</th>
            <th>TVA</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($devis->produits as $produit)
        <tr>
            <td style="text-align: center; vertical-align: middle; padding: 10px;">
                @if($produit->image)
                    <img src="{{ asset($produit->image) }}" alt="{{ $produit->nom }}" style="max-width: 90px; max-height: 90px; object-fit: contain;">
                @else
                    <div style="width: 90px; height: 90px; background-color: #f5f5f5; display: flex; align-items: center; justify-content: center;">
                        <span style="color: #999; font-size: 12px;">Pas d'image</span>
                    </div>
                @endif
            </td>
            <td>
                <strong>{{ $produit->nom }}</strong>
                <div class="product-description">
                    {!! nl2br(e($produit->description ?? '')) !!}
                    @if($produit->pivot->commentaire)
                    <br><br>{{ $produit->pivot->commentaire }}
                    @endif
                </div>
            </td>
            <td>
                {{ $produit->pivot->quantite }}
                @if($produit->type == 'carre')
                    m²
                @elseif($produit->type == 'metre')
                    m
                @endif
            </td>
            <td>€ {{ number_format($produit->pivot->prix_unitaire, 2) }}</td>
            <td>{{ $devis->client->valeur_tva }}%</td>
            <td>€ {{ number_format($produit->pivot->total_ligne, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
    
    <!-- Conteneur qui regroupe le tableau des totaux et les informations de paiement -->
    <div class="totals-payment-container">
        <table class="totals-table">
            <tr>
                <td>Total HTVA</td>
                <td>€ {{ number_format($devis->total_ht, 2) }}</td>
            </tr>
            <tr>
                <td>Rabais</td>
                <td>€ 0,00</td>
            </tr>
            <tr>
                <td>TVA</td>
                <td>€ {{ number_format($devis->total_tva, 2) }}</td>
            </tr>
            <tr>
                <td>Total HTVA sans réduction</td>
                <td>€ {{ number_format($devis->total_ht, 2) }}</td>
            </tr>
            <tr>
                <td>Le total à payer</td>
                <td>€ {{ number_format($devis->total_ttc, 2) }}</td>
            </tr>
        </table>
        
        <div class="payment-info">
            <strong>Veuillez utiliser cette communication:</strong> +++473/1836/89012+++<br><br>
            
            <strong>Coordonnées bancaires:</strong><br>
            IBAN: BE24 0019 4626 0338<br>
        </div>
    </div>
    
    <div class="notes">
        Cette facture proforma est valable 14 jours à partir de la date d'émission. Les prix sont en euros et incluent la TVA au taux applicable.
    </div>
    
    <div class="footer">
        <p>Renowall</p>
        TVA BE 1020 695 663 | +32 479 76 04 42 | info@renowall.be | www.renowall.be
        <div style="text-align: right; margin-top: 5px;">{{ date('d/m/Y') }}</div>
    </div>
</body>
</html>