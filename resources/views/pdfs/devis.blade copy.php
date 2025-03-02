<!DOCTYPE html>
<html>
<head>
    <title>Bon de Commande - Renowall</title>
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
                <p><strong>N° :</strong> {NUMERO_DEVIS}</p>
            </div>
        </div>
    </div>

    <!-- Informations client et projet -->
    <div class="client-section clearfix">
        <div class="client-box">
            <h3 class="section-title">Informations Client</h3>
            <p><strong>Nom :</strong> {NOM_CLIENT}</p>
            <p><strong>Email :</strong> {EMAIL_CLIENT}</p>
            <p><strong>Téléphone :</strong> {TELEPHONE_CLIENT}</p>
            <p><strong>Adresse :</strong> {ADRESSE_CLIENT}, <br>{CODE_POSTAL} {VILLE_CLIENT}</p>
        </div>
        
        <div class="project-box">
            <h3 class="section-title">Détails du Projet</h3>
            <p><strong>Type de projet :</strong> Rénovation</p>
            <p><strong>Date :</strong> {DATE_DEVIS}</p>
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
            <tr>
                <td>Isolation thermique du toit</td>
                <td>85 m²</td>
                <td>230,00€</td>
                <td>19.550,00€</td>
            </tr>
            <tr>
                <td colspan="4" class="commentaire">
                    <strong>Note :</strong> Pose d'isolant PIR 14cm avec valeur RD 6,35m²K/W
                </td>
            </tr>
            <tr>
                <td>Remplacement de la couverture</td>
                <td>85 m²</td>
                <td>60,00€</td>
                <td>5.100,00€</td>
            </tr>
            <tr>
                <td>EPDM toiture plate</td>
                <td>24 m²</td>
                <td>145,22€</td>
                <td>3.485,28€</td>
            </tr>
            <tr>
                <td>Façade avec isolation</td>
                <td>78 m²</td>
                <td>145,22€</td>
                <td>11.327,16€</td>
            </tr>
        </tbody>
    </table>

    <!-- Totaux -->
    <div class="totals">
        <p><strong>Total HT :</strong> 32.893,05€</p>
        <p><strong>TVA (6%) :</strong> 1.973,58€</p>
        <p class="total-ttc"><strong>Total TTC :</strong> 34.866,63€</p>
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
            Date : {DATE_DEVIS}</p>
            <!-- Espace vide pour signature manuelle -->
            <div style="height: 100px; border-bottom: 1px dashed #ccc;"></div>
        </div>
        <div class="signature-box right">
            <p><strong>Bon pour accord (client)</strong><br>
            Date : {DATE_SIGNATURE}</p>
            <!-- Espace vide pour signature manuelle -->
            <div style="height: 100px; border-bottom: 1px dashed #ccc;"></div>
        </div>
    </div>

    <!-- Pied de page -->
    <div class="footer">
        <p><strong>Renowall</strong></p>
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
            <p>a) <strong>Renowall</strong> : Renowall, dont le siège social est situé à Oudesmidestraat 20, 1700 Dilbeek, adresse e-mail : info@renowall.be, sous le nom commercial Renowall.</p>
            <p>b) <strong>Acheteur</strong> : la personne physique ou morale qui utilise ou souhaite utiliser les produits et/ou services de Renowall, ou la personne qui a de quelque manière que ce soit manifesté son intérêt pour les produits et/ou services de Renowall.</p>
            <p>c) <strong>Parties</strong> : l'Acheteur et Renowall.</p>
            <p>d) <strong>Accord</strong> : tout accord entre l'Acheteur et Renowall, tout ajout ou modification à celui-ci et tous les actes juridiques qui en résultent pour la préparation et la mise en œuvre de l'accord.</p>
            <p>e) <strong>Prix</strong> : Le prix convenu entre le Client et Renowall et tout ajout ou modification y afférent.</p>
            <p>f) <strong>Panneaux solaires</strong> : La vente, la livraison et l'installation du nombre de panneaux solaires commandés, de la structure de soutien des panneaux solaires, de l'onduleur et de la batterie ou non, à l'adresse convenue entre les parties.</p>
            <p>g) <strong>Travaux de couverture</strong> : L'exécution des travaux de couverture convenus à l'adresse convenue entre les Parties.</p>
            <p>h) <strong>Biens</strong> : tous les biens, tant pour le service Panneaux Solaires que pour le service Toiture, qui sont livrés à l'Acheteur par Renowall.</p>
            <p>i) <strong>Adresse</strong> : lieu de livraison et lieu où les travaux seront exécutés.</p>
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

        <div class="page-number">Page 1 sur 2</div>
    </div>

    <!-- Deuxième page des termes et conditions -->
    <div class="page-break"></div>

    <div class="terms-conditions">
        <div class="terms-header">
            <h2>TERMES ET CONDITIONS</h2>
            <p>www.renowall.be</p>
        </div>

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
            <h3>Article 9 : garanties commerciales pour les panneaux solaires</h3>
            <p><strong>9.1 Renowall offre à l'Acheteur les garanties commerciales suivantes :</strong></p>
            <p><span class="checkmark">✓</span> Garantie de trente (30) ans sur les panneaux solaires, à l'exception de l'onduleur ;</p>
            <p><span class="checkmark">✓</span> Garantie de vingt-cinq (25) ans sur l'onduleur.</p>
            <p>Les garanties commerciales ne s'appliquent pas si l'assurance prévoyait une couverture concernant d'éventuels dommages aux panneaux solaires et/ou à l'onduleur : l'acheteur doit fournir un certificat de non-couverture à la première demande de Renowall s'il souhaite réclamer l'une des garanties commerciales susmentionnées.</p>
            
            <p><strong>9.2 Par ailleurs, Renowall offre à l'Acheteur les garanties commerciales suivantes :</strong></p>
            <p><span class="checkmark">✓</span> Dix (10) ans de conseils personnalisés en matière d'énergie, uniquement en ce qui concerne les biens fournis ;</p>
            <p><span class="checkmark">✓</span> Garantie d'entretien de deux (2) ans, c'est-à-dire que pendant la période de deux (2) ans à compter de l'inspection, Renowall nettoiera les panneaux solaires une (1) fois par an à un moment à convenir, contrôlera l'onduleur et prendra une photo infrarouge des panneaux solaires.</p>
            <p><span class="checkmark">✓</span> Garantie de travaux de dix (10) ans : cela signifie qu'au cours des dix (10) années suivant la livraison, Renowall est prêt à enlever et à réinstaller les panneaux solaires gratuitement si cela est nécessaire pour l'acheteur, par exemple en cas d'exécution de travaux sur le toit.</p>
            
            <p><strong>9.3 Dispositions communes aux garanties commerciales.</strong> Tous les délais de garantie mentionnés ci-dessus courent à partir de l'inspection. L'acheteur n'a pas droit aux garanties susmentionnées si les factures n'ont pas été intégralement payées. Les garanties commerciales sont limitées géographiquement aux livraisons en Belgique. Les garanties commerciales ne sont pas valorisables et ne peuvent pas être converties en un paiement de Renowall à l'Acheteur. Les garanties commerciales sont accordées personnellement à l'Acheteur (aux Acheteurs) dans la première ligne et ne peuvent pas être transférées à un troisième (acheteur) dans une deuxième ligne ou une ligne successive.</p>
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
            <h3>Article 15 : droit de rétractation</h3>
            <p>Si l'acheteur est un consommateur, il a le droit de révoquer le présent contrat dans un délai de 14 (quatorze) jours sans donner de raisons. Le délai de rétractation expire conformément à l'article VI.47 du CDE.</p>
            <p>L'acheteur doit informer Renowall par écrit (par e-mail : <strong>info@renowall.be</strong>; et par courrier) de sa décision de résilier le contrat avant l'expiration du délai de rétractation (voir le formulaire type non contraignant : joint au BCE). Une copie du modèle de formulaire peut être obtenue auprès de Renowall à la première demande.</p>
            <p>Si l'Acheteur révoque le Contrat, l'Acheteur recevra de Renowall tous les paiements (y compris l'acompte) effectués par l'Acheteur jusqu'à ce moment, y compris les frais de livraison (à l'exception des frais supplémentaires si l'Acheteur a choisi un mode de livraison autre que la livraison standard la moins chère proposée par Renowall) au plus tard 14 (quatorze) jours après que Renowall a été informé de la décision de l'Acheteur de révoquer le Contrat. Aucun frais supplémentaire ne sera facturé pour ce remboursement.</p>
            <p>L'acheteur doit traiter le bien livré et son emballage avec soin. L'acheteur n'est responsable que de la dépréciation des Biens résultant d'une utilisation des Biens au-delà de ce qui est nécessaire pour établir la nature, les caractéristiques et le fonctionnement des Biens.</p>
        </div>

        <div class="article">
            <h3>Article 18 : Droit applicable et juridiction compétente</h3>
            <p>Le droit belge est applicable. Si l'acheteur est un consommateur, seuls les tribunaux de l'arrondissement du domicile du défendeur et/ou ceux du lieu où les obligations (ou l'une d'entre elles) en litige sont nées ou sont, ont été ou doivent être exécutées sont compétents. Si l'acheteur n'est pas un consommateur, les tribunaux de l'arrondissement du siège social de Renowall sont compétents.</p>
        </div>

        <div class="page-number">Page 2 sur 2</div>
    </div>
</body>
</html>