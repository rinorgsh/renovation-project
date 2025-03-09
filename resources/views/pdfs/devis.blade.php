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
        
        /* Page break pour les termes et conditions */
        .page-break {
            page-break-before: always;
        }
        
        /* Style spécifique pour les termes et conditions */
        .terms-conditions {
            font-size: 10pt; /* Texte plus petit pour les conditions */
            padding-top: 20px;
        }
        
        .terms-header {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .article {
            margin-bottom: 15px;
        }
        
        .article h3 {
            margin-top: 15px;
            margin-bottom: 10px;
            font-size: 11pt;
        }
        
        .checkmark {
            font-size: 12pt;
            color: #009900;
            margin-right: 5px;
        }
        
        .page-number {
            text-align: center;
            margin-top: 20px;
            font-size: 9pt;
            font-style: italic;
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
                    Oudesmidsestraat 20,<br>
                    1700 Dilbeek<br>
                    Tél: +32 479 76 04 42<br>
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
        <p>Le présent bon de commande est soumis à nos conditions générales de vente. Les travaux débuteront après réception d'un acompte de 50% du montant total TTC.</p>
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
        <p><strong>Renowall</strong> </p>
        <p>TVA BE 1020 695 663</p>
        <p>Merci pour votre confiance. Pour toute question concernant ce bon de commande, n'hésitez pas à nous contacter.</p>
    </div>

    <!-- Saut de page pour les termes et conditions -->
    <div class="page-break"></div>

    <!-- Début des termes et conditions -->
    <div class="terms-conditions">
        <div class="terms-header">
            <h2>TERMES ET CONDITIONS</h2>
            <p>www.renowall.be</p>
        </div>

        <div class="article">
            <h3>Article 1: en vigueur termes généraux et définitions</h3>
            <p>Sauf preuve contraire, les présentes conditions générales s'appliquent toujours à tous les accords, devis et commandes, ainsi qu'à toutes les livraisons, ventes et prestations qui en résultent et fournies par Renowall au client.</p>
            <p>Les définitions suivantes s'appliquent aux présentes conditions générales.</p>
            <p>a) <strong>Renowall</strong> : Renowall, dont le siège social est situé à Oudesmidsestraat 20, 1700 Dilbeek, adresse e-mail : info@renowall.be, sous le nom commercial Renowall.</p>
            <p>b) <strong>Acheteur</strong> : la personne physique ou morale qui utilise ou souhaite utiliser les produits et/ou services de Renowall, ou la personne qui a de quelque manière que ce soit manifesté son intérêt pour les produits et/ou services de Renowall.</p>
            <p>c) <strong>Parties</strong> : l'Acheteur et Renowall.</p>
            <p>d) <strong>Accord</strong> : tout accord entre l'Acheteur et Renowall, tout ajout ou modification à celui-ci et tous les actes juridiques qui en résultent pour la préparation et la mise en œuvre de l'accord.</p>
            <p>e) <strong>Prix</strong> : Le prix convenu entre le Client et Renowall et tout ajout ou modification y afférent.</p>
            <p>f) <strong>Travaux de couverture</strong> : L'exécution des travaux de couverture convenus à l'adresse convenue entre les Parties.</p>
            <p>g) <strong>Biens</strong> : tous les biens, tant pour le service Panneaux Solaires que pour le service Toiture, qui sont livrés à l'Acheteur par Renowall.</p>
            <p>h) <strong>Adresse</strong> : lieu de livraison et lieu où les travaux seront exécutés.</p>
        </div>

        <div class="article">
            <h3>Article 2 : livraison</h3>
            <p>La date de livraison est convenue entre les Parties. La date de livraison convenue est mentionnée sur le bon de commande et/ou sur le Contrat. En cas de livraison tardive, Renowall doit une compensation au Client de 0,5% (calculée sur le prix indiqué sur la facture correspondante) par semaine de retard avec un maximum de 5%.</p>
        </div>

        <div class="article">
            <h3>Article 3 : endroit par le livraison</h3>
            <p>La livraison est effectuée à l'adresse convenue entre les parties, telle qu'elle est mentionnée dans le bon de commande et/ou dans l'accord.</p>
        </div>

        <div class="article">
            <h3>Article 4 : Devis</h3>
            <p>Un représentant de Renowall se rendra à l'Adresse et vous remettra un bon de commande. Ce bon de commande est valable 14 jours. Si l'Acheteur souhaite faire réaliser des travaux de toiture par Renowall, ce bon de commande contient déjà une estimation des coûts des travaux de toiture. En cas de travaux de toiture, un conseiller technique se rendra sur place après l'envoi du formulaire de commande pour établir l'offre finale. Ce devis est valable pendant 14 jours.</p>
        </div>

        <div class="article">
            <h3>Article 5 : Travaux supplémentaires / Charge supplémentaire</h3>
            <p>Tout changement, ajout ou omission par rapport aux travaux tels que décrits dans le bon de commande/devis/contrat doit être convenu par écrit. Si cet accord écrit n'est pas disponible, il y aura une présomption irréfutable de consentement à l'exécution de ces travaux par la simple réalisation de ceux-ci. Si le client ne réagit pas aux modifications proposées par écrit par le contractant dans les trois jours suivant l'envoi, ces nouvelles modifications sont censées avoir été acceptées. L'article 1793 du Code civil ne s'applique pas si son application serait incompatible avec la présente clause.</p>
            <p>Par travaux supplémentaires, on entend toute modification du Contrat ou des conditions d'exécution, toute action, livraison, travaux ou changement de quantités qui ne sont pas décrits dans le devis.</p>
            <p>Nonobstant l'art. 1793 BW, les travaux supplémentaires peuvent être prouvés par tous les moyens légaux. Les travaux supplémentaires à la demande du client seront facturés au prix de revient ou à un prix forfaitaire. À défaut de forfait, la facturation s'appliquera au cas par cas.</p>
            <p>Si le Client passe une commande pour réaliser des travaux sans que Renowall ait eu connaissance de toutes les informations utiles au moment de l'offre, le Client s'engage à rembourser à Renowall tous les travaux supplémentaires résultant de la prise ultérieure de ces informations. Tous les travaux supplémentaires résultant de ces informations pertinentes sans précédent seront facturés sur la base du prix majoré.</p>
        </div>

        <div class="article">
            <h3>Article 6 : visible défauts</h3>
            <p><strong>6.1 Panneaux solaires</strong><br>
            L'Acheteur doit vérifier (ou faire vérifier) toutes les livraisons de Panneaux Solaires pour les défauts visibles lors de la livraison de ces Panneaux Solaires. Si l'Acheteur est un consommateur, il doit signaler les défauts visibles à Renowall dès que possible et au plus tard dans les deux (2) mois suivant la livraison ; les non-consommateurs doivent signaler les défauts visibles dans les vingt-quatre (24) heures suivant la livraison des Biens.</p>
            <p><strong>6.2 Travaux de toiture</strong><br>
            Tous les défauts visibles doivent être signalés au plus tard lors de la livraison (conformément à l'article 10).</p>
        </div>

    </div>


    <div class="terms-conditions">
        

        <div class="article">
            <h3>Article 7 : vices cachés</h3>
            <p><strong>7.1.</strong> Renowall est responsable des vices cachés des Panneaux Solaires et des travaux de toiture pendant une période de deux (2) ans après la livraison des Panneaux Solaires concernés. L'Acheteur doit notifier Renowall par lettre recommandée dans les deux (2) mois suivant la découverte du défaut.</p>
            <p><strong>7.2.</strong> Les travaux de couverture qui impliquent des travaux entrant dans le champ d'application de l'article 1972 de l'ancien code civil sont couverts par la responsabilité décennale. Le délai commence à partir de la livraison telle qu'elle est définie ci-dessous.</p>
        </div>

        <div class="article">
            <h3>Article 8 : légal Garantie concernant conformité pour consommateurs</h3>
            <p>Si l'Acheteur est un consommateur, Renowall est responsable envers l'Acheteur, conformément à l'article 1649quater (ancien) du Code civil, de tout défaut de conformité (c'est-à-dire non-conformité) qui existe au moment de la livraison des biens et qui se manifeste dans un délai de deux (2) ans à compter de la livraison susmentionnée.</p>
        </div>


        <div class="article">
            <h3>Article 10 : Achèvement des travaux de toiture</h3>
            <p>Si l'Acheteur fait appel à Renowall pour effectuer les travaux de toiture, la remise aura lieu à l'achèvement des travaux de toiture. Des imperfections mineures ou des travaux incomplets, dont la valeur est inférieure à 10% du montant du contrat, ne peuvent pas être invoqués pour refuser la réception provisoire.</p>
            <p>Renowall enverra une invitation à l'Acheteur dans les 2 semaines suivant la fin des travaux pour procéder à la livraison. Si l'acheteur ne répond pas dans les 2 semaines, ou n'est pas présent au moment convenu et n'est pas valablement représenté, l'acceptation est réputée avoir été obtenue à la fin de ces 2 semaines ou au moment convenu.</p>
            <p>La réception implique l'approbation par l'acheteur des travaux de toiture et exclut pour lui tout recours en raison de défauts visibles. Si des imperfections ou des non-conformités sont constatées lors de la livraison, elles seront notées. Renowall prendra les mesures nécessaires pour les résoudre dans un délai raisonnable, après quoi une nouvelle remise aura lieu, selon le schéma décrit ci-dessus.</p>
        </div>

        <div class="article">
            <h3>Article 11 : obligations par le Acheteur</h3>
            <p><strong>11.1</strong> L'acheteur doit, à ses frais, fournir les éléments suivants au plus tard à la date de livraison convenue :</p>
            <p>1) Fournir un revêtement de toit de rechange (au moins 1 tuile/panneau ou 1 ardoise/panneau). Dans le cas de tuiles, l'acheteur doit fournir 25 tuiles supplémentaires par panneau.</p>
            <p>2) Les connexions nécessaires de tous les appareils, services publics et/ou autres installations éventuelles nécessaires à l'agencement des marchandises à assembler :</p>
            <p>(a) La résistance de mise à la terre doit être inférieure ou égale à 30 ohms.</p>
            <p>(b) L'interrupteur de mise à la terre doit être présent.</p>
            <p>(c) La connexion entre la boîte à fusibles et l'interrupteur de mise à la terre et entre l'interrupteur de mise à la terre et la mise à la terre doit être effectué avec un câble de mise à la terre jaune-vert d'une section de 16mm2.</p>
            <p>(d) Le différentiel doit être de type A avec une sensibilité de 300mA.</p>
            <p><strong>11.2</strong> L'inspection de l'installation est comprise dans le prix. La date de ce contrôle est fixée de commun accord avec l'acheteur. Si l'acheteur ne permet pas à l'inspecteur d'accéder à l'installation pour effectuer l'inspection à la date convenue, l'acheteur supportera tous les frais liés au déplacement de l'inspecteur. Dans ce cas, aucun nouveau rendez-vous ne sera pris et l'acheteur sera responsable de l'inspection et en supportera le coût total, sauf en cas de force majeure dans son cas.</p>
        </div>

        <div class="article">
            <h3>Article 12 : paiement</h3>
            <p><strong>12.1</strong> Si aucune date d'échéance n'est indiquée sur la facture, la facture est payable dans un délai de trente (30) jours à compter de la date de réception de la facture par l'acheteur.</p>
            <p><strong>12.2</strong> Les factures non payées à l'échéance sont majorées :</p>
            <p>1. d'un intérêt de retard égal à l'intérêt au taux de référence visé à l'article 5, alinéa 2 de la loi du 2 août 2002 concernant la lutte contre le retard de paiement dans les transactions commerciales, majoré de 8%.</p>
            <p>2. une indemnité forfaitaire de</p>
            <p style="padding-left: 15px">a. € 20 si le solde est inférieur à € 150</p>
            <p style="padding-left: 15px">b. 30 € + 10 % de la somme supérieure à 150 € si le solde est compris entre 150 € et 500 €</p>
            <p style="padding-left: 15px">c. 65 € + 5 % de la somme supérieure à 500 €, avec un maximum de 2000 €, si le solde est supérieur à 500 €.</p>
            <p><strong>12.3</strong> En ce qui concerne les entrepreneurs, les augmentations susmentionnées seront appliquées de plein droit et sans préavis à compter de la date d'échéance de la facture.</p>
            <p><strong>12.4</strong> Le défaut de paiement d'une ou plusieurs factures, en tout ou en partie, à la date d'échéance autorise Renowall à suspendre l'exécution de tous les Contrats en cours avec l'Acheteur. En ce qui concerne les remboursements éventuels de Renowall, la loi du 2 août 2002 relative à la lutte contre le retard de paiement des factures de l'État est d'application.</p>
        </div>

        <div class="article">
            <h3>Article 13 : réserve de propriété</h3>
            <p>Tous les biens (vendus) restent la propriété de Renowall jusqu'à ce que l'Acheteur se soit acquitté de l'intégralité de ses obligations de paiement. L'Acheteur ne peut pas aliéner, incorporer, rendre immeuble par destination, donner en gage ou grever de tout autre droit ou garantie les Biens qui n'ont pas été payés en totalité. Si l'Acheteur vend néanmoins des Biens qui n'ont pas été payés (intégralement), la créance de l'Acheteur sur le tiers acheteur sera cédée de plein droit à Renowall (sans préjudice de tout autre recours que Renowall pourrait faire valoir contre l'Acheteur et/ou le tiers acheteur), étant entendu que cette cession ne libère en aucun cas l'Acheteur.</p>
        </div>

        <div class="article">
            <h3>Article 14 : responsabilité</h3>
            <p>Si l'Acheteur n'est pas un consommateur, Renowall ne sera responsable qu'en cas de non-respect de ses engagements essentiels, d'erreur grave ou de fraude. En aucun cas Renowall ne sera responsable des dommages indirects, intangibles ou consécutifs, tels que la perte de profit.</p>
            <p>Renowall n'est pas responsable de tout dommage indirect, immatériel ou consécutif, tel que perte de profit ou de revenu, préjudice financier, préjudice commercial, perte d'information, dommage aux Biens ou dommage aux personnes. Dans tous les cas, la responsabilité de Renowall est limitée au prix de la partie de la commande qui est à l'origine du fait dommageable.</p>
            <p>S'il y a un dommage dont Renowall, des tiers et/ou l'acheteur sont (conjointement) responsables, Renowall n'est responsable que dans la mesure où sa faute a contribué au dommage.</p>
        </div>
        <div class="article">
            <h3>Article 15 : droit de rétractation</h3>
            <p>Si l'acheteur est un consommateur, il a le droit de révoquer le présent contrat dans un délai de 14 (quatorze) jours sans donner de raisons. Le délai de rétractation expire conformément à l'article VI.47 du CDE.</p>
            <p>L'acheteur doit informer Renowall par écrit (par e-mail : <strong>info@renowall.be</strong>; et par courrier) de sa décision de résilier le contrat avant l'expiration du délai de rétractation (voir le formulaire type non contraignant : joint au BCE). Une copie du modèle de formulaire peut être obtenue auprès de Renowall à la première demande.</p>
            <p>Si l'Acheteur révoque le Contrat, l'Acheteur recevra de Renowall tous les paiements (y compris l'acompte) effectués par l'Acheteur jusqu'à ce moment, y compris les frais de livraison (à l'exception des frais supplémentaires si l'Acheteur a choisi un mode de livraison autre que la livraison standard la moins chère proposée par Renowall) au plus tard 14 (quatorze) jours après que Renowall a été informé de la décision de l'Acheteur de révoquer le Contrat. Aucun frais supplémentaire ne sera facturé pour ce remboursement.</p>
            <p>L'acheteur doit traiter le bien livré et son emballage avec soin. L'acheteur n'est responsable que de la dépréciation des Biens résultant d'une utilisation des Biens au-delà de ce qui est nécessaire pour établir la nature, les caractéristiques et le fonctionnement des Biens.</p>
        </div>
        <div class="article">
            <h3>Article 16 : Révision des prix</h3>
            <p>Renowall a le droit d'indexer le prix convenu en fonction de l'évolution des salaires, des charges sociales, des prix des matériaux ou du transport, sur la base de la formule suivante :</p>
            <p>P1 = P0 x (0,2 + (L1/L0) x 0,4 + (M1/M0) x 0,4), où :</p>
            <p>- P1 = nouveau prix</p>
            <p>- P0 = prix initial (année de base)</p>
            <p>- L1 = coût de la main-d'œuvre pour une année donnée (c'est-à-dire l'année d'indexation)</p>
            <p>- L0 = coût initial de la main-d'œuvre (année de base - indice des salaires en vigueur le mois précédant le bon de commande)</p>
            <p>- M1 = coût des matériaux pour une année donnée (c'est-à-dire l'année d'indexation)</p>
            <p>- M0 = coût original des matériaux (année de base - prix en vigueur le mois précédant le bon de commande)</p>
        </div>

        <div class="article">
            <h3>Article 17 : Traitement des données à caractère personnel</h3>
            <p>En ce qui concerne le traitement des données à caractère personnel dans le cadre de l'utilisation du site web, il est fait référence à la politique en matière de confidentialité et de cookies disponible à l'adresse www.renowall.be.</p>
        </div>
        <div class="article">
            <h3>Article 18 : Droit applicable et juridiction compétente</h3>
            <p>Le droit belge est applicable. Si l'acheteur est un consommateur, seuls les tribunaux de l'arrondissement du domicile du défendeur et/ou ceux du lieu où les obligations (ou l'une d'entre elles) en litige sont nées ou sont, ont été ou doivent être exécutées sont compétents. Si l'acheteur n'est pas un consommateur, les tribunaux de l'arrondissement du siège social de Renowall sont compétents.</p>
        </div>

    </div>
</body>
</html>