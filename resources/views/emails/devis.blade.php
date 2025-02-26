<!DOCTYPE html>
<html>
<head>
    <title>Votre Devis</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f4f4f4; padding: 10px; text-align: center; }
        .content { padding: 20px; }
        .footer { background-color: #f4f4f4; padding: 10px; text-align: center; font-size: 0.8em; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Devis #{{ $devis->numero_devis }}</h1>
        </div>
        
        <div class="content">
            <p>Bonjour {{ $devis->client->prenom }} {{ $devis->client->nom }},</p>
            
            <p>Nous vous prions de trouver ci-joint le devis correspondant à votre commande.</p>
            
            <p><strong>Détails du devis :</strong></p>
            <ul>
                <li>Numéro de devis : {{ $devis->numero_devis }}</li>
                <li>Date : {{ $devis->created_at->format('d/m/Y') }}</li>
                <li>Montant total TTC : {{ number_format($devis->total_ttc, 2) }}€</li>
            </ul>
            
            <p>Le PDF détaillé est joint à cet email.</p>
            
            <p>Cordialement,<br>Votre équipe</p>
        </div>
        
        <div class="footer">
            © {{ date('Y') }} Votre Entreprise
        </div>
    </div>
</body>
</html>