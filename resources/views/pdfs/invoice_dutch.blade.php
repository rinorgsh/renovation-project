<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Proforma Factuur #{{ $devis->numero_devis }} - Renowall</title>
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
            color: #0066cc;
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
            border-top: 2px solid #0066cc;
            border-bottom: 2px solid #0066cc;
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
            background-color: #0066cc;
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
        
        /* Styles voor de totalen tabel op één pagina te houden */
        .totals-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            page-break-inside: avoid;
            break-inside: avoid;
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
            position: relative;
            bottom: 30px;
            left: 0;
            right: 0;
            background-color: #e8f0ff;
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
            page-break-before: avoid;
        }
        
        .notes {
            margin-top: 30px;
            font-style: italic;
            font-size: 9pt;
        }
        
        /* Styles voor productafbeeldingen */
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
        
        /* Container voor de totalen tabel en betalingsinformatie */
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
            Tel: +32 2 616 2280 <br>
            Email: info@renowall.be<br>
            Web: www.renowall.be
        </div>
    </div>
    
    <div class="client-details">
        <div class="client-info">
            <strong>Klant:</strong> {{ $devis->client->prenom }} {{ $devis->client->nom }}<br>
            <strong>Adres:</strong> {{ $devis->client->adresse }}<br>
            <strong>Plaats:</strong> {{ $devis->client->province ?? 'Brabant' }}, {{ $devis->client->ville }}, {{ $devis->client->code_postal }}<br>
            <strong>Tel:</strong> {{ $devis->client->telephone }}
        </div>
        <div class="proforma-info">
            <strong>Nummer:</strong> P-{{ str_replace('BC', '', $devis->numero_devis) }}<br>
            <strong>Datum:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y') }}<br>
            <strong>Proforma:</strong> {{ date('d/m/Y', strtotime('+14 days')) }}
        </div>
    </div>
    
    <!-- Productentabel met afbeeldingskolom -->
<table class="product-table">
    <thead>
        <tr>
            <th style="width: 100px;">Afbeelding</th>
            <th>Omschrijving</th>
            <th>Aantal</th>
            <th>Eenheidsprijs</th>
            <th>BTW</th>
            <th>Totaal</th>
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
                        <span style="color: #999; font-size: 12px;">Geen afbeelding</span>
                    </div>
                @endif
            </td>
            <td>
                <strong>{{ $produit->nom_nl }}</strong>
                <div class="product-description">
                    {!! nl2br(e($produit->description_nl ?? '')) !!}
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
    
    <!-- Container die de totalen tabel en betalingsinformatie groepeert -->
    <div class="totals-payment-container">
        <table class="totals-table">
            <tr>
                <td>Totaal excl. BTW</td>
                <td>€ {{ number_format($devis->total_ht, 2) }}</td>
            </tr>
            <tr>
                <td>Korting</td>
                <td>€ 0,00</td>
            </tr>
            <tr>
                <td>BTW</td>
                <td>€ {{ number_format($devis->total_tva, 2) }}</td>
            </tr>
            <tr>
                <td>Totaal excl. BTW zonder korting</td>
                <td>€ {{ number_format($devis->total_ht, 2) }}</td>
            </tr>
            <tr>
                <td>Totaal te betalen</td>
                <td>€ {{ number_format($devis->total_ttc, 2) }}</td>
            </tr>
        </table>
        
        <div class="payment-info">
        <strong>Vrije mededeling:</strong> Factuur Renowall -  #{{ $devis->numero_devis }}<br><br>
            
            <strong>Bankgegevens:</strong><br>
            IBAN: BE24 0019 4626 0338<br>
        </div>
    </div>
    
    <div class="notes">
        Deze proforma factuur is geldig voor 14 dagen vanaf de uitgiftedatum. Prijzen zijn in euro's en inclusief BTW tegen het geldende tarief.
    </div>
    <br>
    <br>

    <div class="footer">
        <p>Renowall</p>
        BTW BE 1020 695 663 | +32 2 616 2280 | info@renowall.be | www.renowall.be
        <div style="text-align: right; margin-top: 5px;">{{ date('d/m/Y') }}</div>
    </div>
</body>
</html>