<!DOCTYPE html>
<html>
<head>
    <title>Bon de Commande #{{ $devis->numero_devis }} - Renowall</title>
    <style>
        body { 
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 20px;
            font-size: 12pt;
        }
        
        /* En-tête */
        .header {
            width: 100%;
            margin-bottom: 30px;
            overflow: hidden;
        }
        
        .header-left {
            width: 60%;
            float: left;
        }
        
        .header-right {
            width: 40%;
            float: right;
            text-align: right;
        }
        
        .logo {
            max-width: 200px;
            height: auto;
            margin-bottom: 10px;
        }
        
        .company-info {
            line-height: 1.4;
        }
        
        .devis-box {
            background-color: #f0f0f0;
            padding: 10px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
            display: inline-block;
        }
        
        .devis-box h2 {
            margin: 0 0 10px 0;
            font-size: 16pt;
        }
        
        .devis-box p {
            margin: 5px 0;
        }
        
        /* Informations client */
        .client-section {
            width: 100%;
            margin-bottom: 30px;
            overflow: hidden;
        }
        
        .client-box {
            width: 45%;
            float: left;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        
        .project-box {
            width: 45%;
            float: right;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        
        .section-title {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 14pt;
            border-bottom: 2px solid #333;
            padding-bottom: 5px;
        }
        
        /* Tableau des produits */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        th {
            background-color: #333;
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }
        
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        
        .commentaire {
            background-color: #fffacd;
            font-style: italic;
            padding: 5px 8px;
        }
        
        /* Totaux */
        .totals {
            width: 300px;
            float: right;
            margin-bottom: 30px;
        }
        
        .totals p {
            text-align: right;
            margin: 5px 0;
        }
        
        .total-ttc {
            font-weight: bold;
            font-size: 14pt;
            border-top: 2px solid #333;
            padding-top: 5px;
        }
        
        /* Conditions et signature */
        .conditions {
            clear: both;
            margin: 30px 0;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        
        .signature-section {
            width: 100%;
            margin-top: 50px;
            overflow: hidden;
        }
        
        .signature-box {
            width: 45%;
            float: left;
            border-top: 1px solid #000;
            padding-top: 10px;
        }
        
        .signature-box.right {
            float: right;
        }
        
        /* Pied de page */
        .footer {
            margin-top: 50px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10pt;
            color: #666;
            clear: both;
        }

        /* Clearfix */
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>
    <!-- En-tête -->
    <div class="header clearfix">
        <div class="header-left">
       <!-- <img src="/public/image/logo.png" alt="Renowall" class="logo"> -->
            <div class="company-info">
                <p>
                    Oudesmidestraat 20,<br>
                    1700 Dilbeek<br>
                    Tél: 01 23 45 67 89<br>
                    Email: info@renowall.be<br>
                    Web: www.renowall.be
                </p>
            </div>
        </div>
        
        <div class="header-right">
            <div class="devis-box">
                <h2>BON DE COMMANDE</h2>
                <p><strong>N° :</strong> {{ $devis->numero_devis }}</p>
                
                
            </div>
        </div>
    </div>

    <!-- Informations client et projet -->
    <div class="client-section clearfix">
        <div class="client-box">
            <h3 class="section-title">Informations Client</h3>
            <p><strong>Nom :</strong> {{ $devis->client->prenom }} {{ $devis->client->nom }}</p>
            <p><strong>Email :</strong> {{ $devis->client->email }}</p>
            <p><strong>Téléphone :</strong> {{ $devis->client->telephone }}</p>
            <p><strong>Adresse :</strong> {{ $devis->client->adresse }}, <br>{{ $devis->client->code_postal }} {{ $devis->client->ville }}</p>
        </div>
        
        <div class="project-box">
            <h3 class="section-title">Détails du Projet</h3>
            <p><strong>Type de projet :</strong> Rénovation</p>
            <p><strong>Date :</strong> {{ $devis->created_at->format('d/m/Y') }}</p>
            <p><strong>Conseiller en énergie:</strong> Dimi Renowall</p>
            
        </div>
    </div>

    <!-- Tableau des produits -->
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
            <td>
                {{ $produit->pivot->quantite }}
                @switch($produit->type)
                    @case('carre')
                        m²
                        @break
                    @case('metre')
                        m
                        @break
                    @default
                        <!-- Ne rien afficher pour les unités unitaires -->
                @endswitch
            </td>
            <td>{{ number_format($produit->pivot->prix_unitaire, 2) }}€</td>
            <td>{{ number_format($produit->pivot->total_ligne, 2) }}€</td>
        </tr>
        @if($produit->pivot->commentaire)
        <tr>
            <td colspan="4" class="commentaire">
                <strong>Note :</strong> {{ $produit->pivot->commentaire }}
            </td>
        </tr>
        @endif
        @endforeach
    </tbody>
</table>

    <!-- Totaux -->
    <div class="totals">
        <p><strong>Total HT :</strong> {{ number_format($devis->total_ht, 2) }}€</p>
        <p><strong>TVA ({{ $devis->client->valeur_tva }}%) :</strong> {{ number_format($devis->total_tva, 2) }}€</p>
        <p class="total-ttc"><strong>Total TTC :</strong> {{ number_format($devis->total_ttc, 2) }}€</p>
    </div>

    <!-- Conditions -->
    <div class="conditions clearfix">
        <h3>Conditions de vente</h3>
        <p>Le présent bon de commande est soumis à nos conditions générales de vente. Les travaux débuteront après réception d'un acompte de 30% du montant total TTC.</p>
        <p>Mode de paiement : virement bancaire, chèque ou espèces.</p>
        <p>Délai de livraison : à convenir avec le client après validation du bon de commande.</p>
    </div>

<!-- Signatures -->
<div class="signature-section clearfix">
    <div class="signature-box">
        <p><strong>Pour Renowall</strong><br>
        Date : {{ $devis->created_at->format('d/m/Y') }}</p>
        <!-- Espace vide pour signature manuelle -->
        <div style="height: 100px; border-bottom: 1px dashed #ccc;"></div>
    </div>
    <div class="signature-box right">
        <p><strong>Bon pour accord (client)</strong><br>
        Date : {{ $devis->created_at->format('d/m/Y') }}</p>
        @if(!empty($signature_url))
            <img src="{{ $signature_url }}" alt="Signature Client" style="max-width: 200px; max-height: 100px;">
        @else
            <!-- Espace vide pour signature manuelle -->
            <div style="height: 100px; border-bottom: 1px dashed #ccc;"></div>
        @endif
    </div>
</div>

    <!-- Pied de page -->
    <div class="footer">
        <p><strong>Renowall</strong> - SIRET: 123 456 789 00010 - TVA Intracommunautaire: FR12345678900</p>
        <p>Merci pour votre confiance. Pour toute question concernant ce bon de commande, n'hésitez pas à nous contacter.</p>
    </div>
</body>
</html>