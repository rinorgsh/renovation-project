<!DOCTYPE html>
<html>
<head>
    <title>Bestelbon #{{ $devis->numero_devis }} - Renowall</title>
    <style>
       
/* Style CSS voor Bestelbon (geïnspireerd door Proforma Factuur) */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    color: #333333;
    font-size: 10pt;
}

/* Header */
.header {
    width: 100%;
    display: table;
    margin-bottom: 30px;
}

.header-left {
    display: table-cell;
    width: 40%;
    vertical-align: top;
}

.header-right {
    display: table-cell;
    width: 60%;
    vertical-align: top;
    text-align: right;
    font-size: 12pt;
}

.company-info {
    margin-top: 10px;
    font-size: 10pt;
}

.devis-box {
    display: inline-block;
    border-bottom: 2px solid #0066cc;
    padding-bottom: 10px;
}

.devis-box h2 {
    font-size: 24pt;
    color: #0066cc;
    font-weight: bold;
    margin: 0 0 10px 0;
}

/* Klantgegevens en projectinformatie */
.client-section {
    width: 100%;
    display: table;
    margin-bottom: 30px;
    border-top: 2px solid #0066cc;
    border-bottom: 2px solid #0066cc;
    padding: 10px 0;
}

.client-box {
    display: table-cell;
    width: 50%;
    vertical-align: top;
    padding-right: 15px;
}

.project-box {
    display: table-cell;
    width: 50%;
    vertical-align: top;
    text-align: right;
}

.section-title {
    color: #0066cc;
    font-weight: bold;
    font-size: 12pt;
    margin-top: 0;
    margin-bottom: 8px;
}

/* Producttabel */
.product-table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}

.product-table th {
    background-color: #0066cc;
    color: white;
    padding: 8px;
    text-align: left;
}

.product-table td {
    padding: 6px 8px;
    border-bottom: 1px solid #ddd;
    vertical-align: top;
}

/* Aanpassing van kolombreedtes */
.product-table th:first-child,
.product-table td:first-child {
    width: 90px;
    text-align: center;
    vertical-align: middle;
}

.product-table th:nth-child(2) {
    width: 35%;
}

.product-table th:nth-child(3),
.product-table th:nth-child(5) {
    width: 8%;
}

.product-table th:nth-child(4),
.product-table th:nth-child(6) {
    width: 18%;
}

/* Stijl voor numerieke cellen (rechts uitgelijnd) */
.product-table td:nth-child(3),
.product-table td:nth-child(4),
.product-table td:nth-child(5),
.product-table td:nth-child(6) {
    text-align: right;
    white-space: nowrap;
    padding-right: 10px;
}

/* Stijl voor afbeeldingen */
.product-table img {
    max-width: 90px;
    max-height: 90px;
    border-radius: 4px;
    object-fit: contain;
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

.product-description {
    font-size: 9pt;
    color: #555;
    margin-top: 4px;
}

/* Totalen - Aanpassing om scheiding over twee pagina's te vermijden */
.totals-container {
    width: 100%;
    clear: both;
    page-break-inside: avoid; /* Voorkom verdeling van inhoud */
    break-inside: avoid; /* Moderne eigenschap om pagina-onderbrekingen te vermijden */
}

.totals {
    width: 300px;
    float: right;
    margin-top: 20px;
    margin-bottom: 30px;
    page-break-inside: avoid; /* Zorg ervoor dat de totalen bij elkaar blijven */
    break-inside: avoid; /* Moderne eigenschap */
}

.totals p {
    text-align: right;
    margin: 5px 0;
    padding: 4px 0;
}

.total-ttc {
    font-weight: bold;
    font-size: 12pt;
    background-color: #f2f2f2;
    padding: 8px 0 !important;
    border-top: 1px solid #ddd;
}

/* Voorwaarden */
.conditions {
    clear: both;
    margin: 30px 0;
    padding: 15px;
    background-color: #e8f0ff;
    border-radius: 5px;
}

.conditions h3 {
    color: #0066cc;
    margin-top: 0;
    font-size: 12pt;
    border-bottom: 1px solid #ddd;
    padding-bottom: 8px;
}

/* Handtekeningen */
.signature-section {
    width: 100%;
    margin-top: 40px;
    margin-bottom: 30px;
    overflow: hidden;
}

.signature-box {
    width: 45%;
    float: left;
    padding-top: 10px;
}

.signature-box.right {
    float: right;
    text-align: right;
}

/* Voettekst */
.footer {
    position: relative;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: #e8f0ff;
    padding: 10px;
    text-align: center;
    font-size: 9pt;
    border-top: 1px solid #ddd;
    margin-top: 40px;
}

/* Algemene voorwaarden */
.terms-conditions {
    font-size: 9pt;
    line-height: 1.4;
}

.terms-header {
    text-align: center;
    margin-bottom: 20px;
}

.terms-header h2 {
    color: #0066cc;
    margin-bottom: 5px;
    font-size: 14pt;
}

.article {
    margin-bottom: 15px;
}

.article h3 {
    color: #0066cc;
    font-size: 11pt;
    margin-bottom: 10px;
}

/* Hulpmiddelen */
.clearfix:after {
    content: "";
    display: table;
    clear: both;
}

.page-break {
    page-break-before: always;
    height: 0;
    margin: 0;
    padding: 0;
}

/* Configuratie voor afdrukken */
@media print {
    /* Koppen herhalen op elke pagina */
    .product-table thead {
        display: table-header-group;
    }
    
    .product-table tfoot {
        display: table-footer-group;
    }
    
    /* Paginastijl */
    @page {
        margin: 10mm;
    }
    
    body {
        padding: 0;
    }
    
    /* Ongewenste pagebreaks vermijden */
    .header, 
    .client-section, 
    .totals-container,
    .totals, 
    .conditions, 
    .signature-section, 
    .footer {
        page-break-inside: avoid;
        break-inside: avoid;
    }
}

  
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header clearfix">
        <div class="header-left">
       <!-- <img src="/public/image/logo.png" alt="Renowall" class="logo"> -->
            <div class="company-info">
                <p>
                    Oudesmidsestraat 20,<br>
                    1700 Dilbeek<br>
                    Tel: +32 2 616 2280 <br>
                    Email: info@renowall.be<br>
                    Web: www.renowall.be
                </p>
            </div>
        </div>
        
        <div class="header-right">
            <div class="devis-box">
                <h2>BESTELBON</h2>
                <p><strong>Nr.:</strong> {{ $devis->numero_devis }}</p>
                
                
            </div>
        </div>
    </div>

    <!-- Klantgegevens en projectinformatie -->
    <div class="client-section clearfix">
        <div class="client-box">
            <h3 class="section-title">Klantgegevens</h3>
            <p><strong>Naam:</strong> {{ $devis->client->prenom }} {{ $devis->client->nom }}</p>
            <p><strong>Email:</strong> {{ $devis->client->email }}</p>
            <p><strong>Telefoon:</strong> {{ $devis->client->telephone }}</p>
            <p><strong>Adres:</strong> {{ $devis->client->adresse }}, <br>{{ $devis->client->code_postal }} {{ $devis->client->ville }}</p>
        </div>
        
        <div class="project-box">
            <h3 class="section-title">Projectdetails</h3>
            <p><strong>Type project:</strong> Renovatie</p>
            <p><strong>Datum:</strong> {{ $devis->created_at->format('d/m/Y') }}</p>
            <p><strong>Energieadviseur:</strong> Dimi Renowall</p>
            
        </div>
    </div>

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


    <!-- Totalen - Met container om scheiding te voorkomen -->
    <div class="totals-container">
        <div class="totals">
            <p><strong>Totaal excl. BTW:</strong> {{ number_format($devis->total_ht, 2) }}€</p>
            <p><strong>BTW ({{ $devis->client->valeur_tva }}%):</strong> {{ number_format($devis->total_tva, 2) }}€</p>
            <p class="total-ttc"><strong>Totaal incl. BTW:</strong> {{ number_format($devis->total_ttc, 2) }}€</p>
        </div>
    </div>

    <!-- Voorwaarden -->
    <div class="conditions clearfix">
        <h3>Verkoopvoorwaarden</h3>
        <p>Deze bestelbon is onderworpen aan onze algemene verkoopvoorwaarden. De werkzaamheden beginnen na ontvangst van een aanbetaling van 50% van het totaalbedrag inclusief BTW.</p>
        <p>Betalingswijze: bankoverschrijving, cheque of contant.</p>
        <p>Levertijd: in overleg met de klant na goedkeuring van de bestelbon.</p>
    </div>

<!-- Handtekeningen -->
<div class="signature-section clearfix">
    <div class="signature-box">
        <p><strong>Voor Renowall</strong><br>
        Datum: {{ $devis->created_at->format('d/m/Y') }}</p>
        <!-- Lege ruimte voor handmatige handtekening -->
        <div style="height: 100px; border-bottom: 1px dashed #ccc;"></div>
    </div>
    <div class="signature-box right">
        <p><strong>Voor akkoord (klant)</strong><br>
        Datum: {{ $devis->created_at->format('d/m/Y') }}</p>
        @if(!empty($signature_url))
            <img src="{{ $signature_url }}" alt="Handtekening Klant" style="max-width: 200px; max-height: 100px;">
        @else
            <!-- Lege ruimte voor handmatige handtekening -->
            <div style="height: 100px; border-bottom: 1px dashed #ccc;"></div>
        @endif
    </div>
</div>

    <!-- Voettekst -->
    <div class="footer">
        <p><strong>Renowall</strong> </p>
        <p> +32 2 616 2280 </p>
        <p>BTW BE 1020 695 663</p>
        <p>Bedankt voor uw vertrouwen. Voor vragen over deze bestelbon kunt u contact met ons opnemen.</p>
    </div>

    <!-- Pagina-einde voor algemene voorwaarden -->
    <div class="page-break"></div>

    <!-- Begin algemene voorwaarden -->
    <div class="terms-conditions">
        <div class="terms-header">
            <h2>ALGEMENE VOORWAARDEN</h2>
            <p>www.renowall.be</p>
        </div>

        <div class="article">
            <h3>Artikel 1: Geldende algemene voorwaarden en definities</h3>
            <p>Behoudens tegenbewijs zijn deze algemene voorwaarden altijd van toepassing op alle overeenkomsten, offertes en bestellingen, evenals op alle leveringen, verkopen en diensten die daaruit voortvloeien en door Renowall aan de klant worden geleverd.</p>
            <p>De volgende definities zijn van toepassing op deze algemene voorwaarden.</p>
            <p>a) <strong>Renowall</strong>: Renowall, met maatschappelijke zetel te Oudesmidsestraat 20, 1700 Dilbeek, e-mailadres: info@renowall.be, onder de handelsnaam Renowall.</p>
            <p>b) <strong>Koper</strong>: de natuurlijke of rechtspersoon die de producten en/of diensten van Renowall gebruikt of wenst te gebruiken, of de persoon die op enigerlei wijze zijn/haar belangstelling voor de producten en/of diensten van Renowall heeft getoond.</p>
            <p>c) <strong>Partijen</strong>: de Koper en Renowall.</p>
            <p>d) <strong>Overeenkomst</strong>: elke overeenkomst tussen de Koper en Renowall, elke aanvulling of wijziging daarop en alle rechtshandelingen die daaruit voortvloeien voor de voorbereiding en uitvoering van de overeenkomst.</p>
            <p>e) <strong>Prijs</strong>: De tussen de Klant en Renowall overeengekomen prijs en elke aanvulling of wijziging daarop.</p>
            <p>f) <strong>Dakwerkzaamheden</strong>: De uitvoering van de overeengekomen dakwerkzaamheden op het tussen Partijen overeengekomen adres.</p>
            <p>g) <strong>Goederen</strong>: alle goederen, zowel voor de dienst Zonnepanelen als voor de dienst Dakwerken, die door Renowall aan de Koper worden geleverd.</p>
            <p>h) <strong>Adres</strong>: plaats van levering en plaats waar de werkzaamheden worden uitgevoerd.</p>
        </div>

        <div class="article">
            <h3>Artikel 2: Levering</h3>
            <p>De leveringsdatum wordt overeengekomen tussen de Partijen. De overeengekomen leveringsdatum wordt vermeld op de bestelbon en/of op de Overeenkomst. In geval van te late levering is Renowall aan de Klant een vergoeding verschuldigd van 0,5% (berekend over de op de betreffende factuur vermelde prijs) per week vertraging met een maximum van 5%.</p>
        </div>

        <div class="article">
            <h3>Artikel 3: Plaats van levering</h3>
            <p>De levering vindt plaats op het tussen partijen overeengekomen adres, zoals vermeld in de bestelbon en/of in de overeenkomst.</p>
        </div>

        <div class="article">
            <h3>Artikel 4: Offerte</h3>
            <p>Een vertegenwoordiger van Renowall zal naar het Adres komen en u een bestelbon overhandigen. Deze bestelbon is 14 dagen geldig. Als de Koper dakwerkzaamheden door Renowall wil laten uitvoeren, bevat deze bestelbon al een kostenraming van de dakwerkzaamheden. In geval van dakwerkzaamheden zal een technisch adviseur na verzending van het bestelformulier ter plaatse komen om de definitieve offerte op te stellen. Deze offerte is 14 dagen geldig.</p>
        </div>

        <div class="article">
            <h3>Artikel 5: Extra werkzaamheden / Extra kosten</h3>
            <p>Elke wijziging, toevoeging of weglating ten opzichte van de werkzaamheden zoals beschreven in de bestelbon/offerte/overeenkomst moet schriftelijk worden overeengekomen. Als deze schriftelijke overeenkomst niet beschikbaar is, zal er een onweerlegbaar vermoeden van instemming zijn met de uitvoering van deze werkzaamheden door de eenvoudige realisatie ervan. Als de klant niet binnen drie dagen na verzending reageert op de schriftelijk door de aannemer voorgestelde wijzigingen, worden deze nieuwe wijzigingen geacht te zijn aanvaard. Artikel 1793 van het Burgerlijk Wetboek is niet van toepassing als de toepassing ervan onverenigbaar zou zijn met deze clausule.</p>
            <p>Onder extra werkzaamheden wordt verstaan elke wijziging van de Overeenkomst of de uitvoeringsvoorwaarden, elke handeling, levering, werkzaamheden of wijziging van hoeveelheden die niet in de offerte zijn beschreven.</p>
            <p>Onverminderd art. 1793 BW, kunnen extra werkzaamheden worden bewezen met alle wettelijke middelen. Extra werkzaamheden op verzoek van de klant worden gefactureerd tegen kostprijs of tegen een forfaitaire prijs. Bij gebreke van een forfait wordt per geval gefactureerd.</p>
            <p>Indien de Klant een opdracht geeft tot het uitvoeren van werkzaamheden zonder dat Renowall ten tijde van de offerte kennis had van alle nuttige informatie, verbindt de Klant zich ertoe Renowall alle extra werkzaamheden te vergoeden die voortvloeien uit de latere kennisname van deze informatie. Alle extra werkzaamheden die voortvloeien uit deze relevante informatie zonder precedent worden gefactureerd op basis van de verhoogde prijs.</p>
        </div>

        <div class="article">
            <h3>Artikel 6: Zichtbare gebreken</h3>
            <p><strong>6.1 Zonnepanelen</strong><br>
            De Koper moet alle leveringen van Zonnepanelen controleren (of laten controleren) op zichtbare gebreken bij de levering van deze Zonnepanelen. Als de Koper een consument is, moet hij zichtbare gebreken zo snel mogelijk en uiterlijk binnen twee (2) maanden na levering aan Renowall melden; niet-consumenten moeten zichtbare gebreken binnen vierentwintig (24) uur na levering van de Goederen melden.</p>
            <p><strong>6.2 Dakwerkzaamheden</strong><br>
            Alle zichtbare gebreken moeten uiterlijk bij de oplevering worden gemeld (overeenkomstig artikel 10).</p>
        </div>

    </div>


    <div class="terms-conditions">
        

        <div class="article">
            <h3>Artikel 7: Verborgen gebreken</h3>
            <p><strong>7.1.</strong> Renowall is verantwoordelijk voor verborgen gebreken van de Zonnepanelen en de dakwerkzaamheden gedurende een periode van twee (2) jaar na de levering van de betreffende Zonnepanelen. De Koper moet Renowall per aangetekende brief binnen twee (2) maanden na ontdekking van het gebrek op de hoogte stellen.</p>
            <p><strong>7.2.</strong> Dakwerkzaamheden die onder de reikwijdte van artikel 1972 van het oude Burgerlijk Wetboek vallen, worden gedekt door de tienjarige aansprakelijkheid. De termijn begint vanaf de oplevering zoals hieronder gedefinieerd.</p>
        </div>

        <div class="article">
            <h3>Artikel 8: Wettelijke garantie betreffende conformiteit voor consumenten</h3>
            <p>Als de Koper een consument is, is Renowall jegens de Koper verantwoordelijk, overeenkomstig artikel 1649quater (oud) van het Burgerlijk Wetboek, voor elk gebrek aan overeenstemming (d.w.z. niet-conformiteit) dat bestaat op het moment van levering van de goederen en dat zich manifesteert binnen een termijn van twee (2) jaar vanaf voornoemde levering.</p>
        </div>


        <div class="article">
            <h3>Artikel 10: Voltooiing van dakwerkzaamheden</h3>
            <p>Als de Koper Renowall inschakelt om dakwerkzaamheden uit te voeren, vindt de oplevering plaats bij voltooiing van de dakwerkzaamheden. Kleine onvolkomenheden of onvoltooide werkzaamheden, waarvan de waarde minder dan 10% van het contractbedrag bedraagt, kunnen niet worden ingeroepen om de voorlopige oplevering te weigeren.</p>
            <p>Renowall zal binnen 2 weken na voltooiing van de werkzaamheden een uitnodiging aan de Koper sturen om tot oplevering over te gaan. Als de koper niet binnen 2 weken reageert, of niet aanwezig is op het afgesproken tijdstip en niet geldig wordt vertegenwoordigd, wordt de aanvaarding geacht te zijn verkregen aan het einde van deze 2 weken of op het afgesproken tijdstip.</p>
            <p>De oplevering impliceert de goedkeuring door de koper van de dakwerkzaamheden en sluit voor hem elk verhaal wegens zichtbare gebreken uit. Als bij de oplevering onvolkomenheden of non-conformiteiten worden vastgesteld, worden deze genoteerd. Renowall zal de nodige maatregelen nemen om deze binnen een redelijke termijn op te lossen, waarna een nieuwe oplevering zal plaatsvinden, volgens het hierboven beschreven schema.</p>
        </div>

        <div class="article">
            <h3>Artikel 11: Verplichtingen van de Koper</h3>
            <p><strong>11.1</strong> De koper moet op eigen kosten het volgende leveren uiterlijk op de overeengekomen leveringsdatum:</p>
            <p>1) Zorg voor een vervangende dakbedekking (minimaal 1 dakpan/paneel of 1 lei/paneel). In het geval van dakpannen moet de koper 25 extra dakpannen per paneel leveren.</p>
            <p>2) De nodige aansluitingen van alle apparaten, nutsvoorzieningen en/of andere eventuele installaties die nodig zijn voor de inrichting van de te monteren goederen:</p>
            <p>(a) De aardingsweerstand moet minder dan of gelijk aan 30 ohm zijn.</p>
            <p>(b) De aardingsschakelaar moet aanwezig zijn.</p>
            <p>(c) De verbinding tussen de zekeringenkast en de aardingsschakelaar en tussen de aardingsschakelaar en de aarding moet worden uitgevoerd met een geel-groene aardingskabel met een doorsnede van 16mm2.</p>
            <p>(d) De differentieel moet van het type A zijn met een gevoeligheid van 300mA.</p>
            <p><strong>11.2</strong> De inspectie van de installatie is inbegrepen in de prijs. De datum van deze controle wordt in onderling overleg met de koper vastgesteld. Als de koper de inspecteur op de afgesproken datum geen toegang geeft tot de installatie om de inspectie uit te voeren, draagt de koper alle kosten in verband met de verplaatsing van de inspecteur. In dit geval wordt geen nieuwe afspraak gemaakt en is de koper verantwoordelijk voor de inspectie en draagt hij de volledige kosten, behalve in geval van overmacht in zijn geval.</p>
        </div>

        <div class="article">
            <h3>Artikel 12: Betaling</h3>
            <p><strong>12.1</strong> Als er geen vervaldatum op de factuur staat, is de factuur betaalbaar binnen dertig (30) dagen na de datum van ontvangst van de factuur door de koper.</p>
            <p><strong>12.2</strong> Niet betaalde facturen op de vervaldatum worden verhoogd met:</p>
            <p>1. een verwijlintrest gelijk aan de rentevoet als bedoeld in artikel 5, lid 2 van de wet van 2 augustus 2002 betreffende de bestrijding van betalingsachterstand bij handelstransacties, verhoogd met 8%.</p>
            <p>2. een forfaitaire vergoeding van</p>
            <p style="padding-left: 15px">a. € 20 als het saldo minder is dan € 150</p>
            <p style="padding-left: 15px">b. 30 € + 10 % van het bedrag dat € 150 overschrijdt als het saldo tussen € 150 en € 500 ligt</p>
            <p style="padding-left: 15px">c. 65 € + 5 % van het bedrag dat € 500 overschrijdt, met een maximum van 2000 €, als het saldo meer dan € 500 bedraagt.</p>
            <p><strong>12.3</strong> Voor aannemers worden bovengenoemde verhogingen van rechtswege en zonder voorafgaande kennisgeving toegepast vanaf de vervaldatum van de factuur.</p>
            <p><strong>12.4</strong> Het niet betalen van een of meer facturen, geheel of gedeeltelijk, op de vervaldatum geeft Renowall het recht om de uitvoering van alle lopende Overeenkomsten met de Koper op te schorten. Voor eventuele terugbetalingen door Renowall is de wet van 2 augustus 2002 betreffende de bestrijding van betalingsachterstand bij facturen van de Staat van toepassing.</p>
        </div>

        <div class="article">
            <h3>Artikel 13: Eigendomsvoorbehoud</h3>
            <p>Alle (verkochte) goederen blijven eigendom van Renowall totdat de Koper al zijn betalingsverplichtingen heeft voldaan. De Koper mag de Goederen die niet volledig zijn betaald niet vervreemden, incorporeren, onroerend door bestemming maken, verpanden of met enig ander recht of zekerheid bezwaren. Indien de Koper toch Goederen verkoopt die niet (volledig) zijn betaald, wordt de vordering van de Koper op de derde-koper van rechtswege aan Renowall overgedragen (onverminderd andere rechtsmiddelen die Renowall tegen de Koper en/of de derde-koper zou kunnen uitoefenen), met dien verstande dat deze overdracht de Koper op geen enkele wijze bevrijdt.</p>
        </div>

        <div class="article">
            <h3>Artikel 14: Aansprakelijkheid</h3>
            <p>Als de Koper geen consument is, is Renowall alleen aansprakelijk in geval van niet-nakoming van zijn essentiële verplichtingen, ernstige fout of fraude. In geen geval is Renowall aansprakelijk voor indirecte, immateriële of gevolgschade, zoals winstderving.</p>
            <p>Renowall is niet aansprakelijk voor indirecte, immateriële of gevolgschade, zoals verlies van winst of inkomsten, financiële schade, commerciële schade, verlies van informatie, schade aan de Goederen of schade aan personen. In alle gevallen is de aansprakelijkheid van Renowall beperkt tot de prijs van het deel van de bestelling dat aan de oorsprong van het schadelijke feit ligt.</p>
            <p>Als er schade is waarvoor Renowall, derden en/of de koper (gezamenlijk) aansprakelijk zijn, is Renowall alleen aansprakelijk voor zover zijn fout heeft bijgedragen aan de schade.</p>
        </div>
        <div class="article">
            <h3>Artikel 15: Herroepingsrecht</h3>
            <p>Als de koper een consument is, heeft hij het recht om deze overeenkomst binnen 14 (veertien) dagen zonder opgave van redenen te herroepen. De herroepingstermijn verstrijkt overeenkomstig artikel VI.47 van het WER.</p>
            <p>De koper moet Renowall schriftelijk (per e-mail: <strong>info@renowall.be</strong>; en per post) op de hoogte stellen van zijn beslissing om de overeenkomst te beëindigen voordat de herroepingstermijn verstrijkt (zie het niet-bindende standaardformulier: bijgevoegd bij het KBO). Een kopie van het modelformulier kan op eerste verzoek bij Renowall worden verkregen.</p>
            <p>Als de Koper de Overeenkomst herroept, zal de Koper van Renowall alle betalingen (inclusief de aanbetaling) die de Koper tot dat moment heeft gedaan, inclusief de leveringskosten (met uitzondering van de extra kosten als de Koper voor een andere leveringswijze heeft gekozen dan de goedkoopste standaardlevering die door Renowall wordt aangeboden) uiterlijk 14 (veertien) dagen nadat Renowall op de hoogte is gesteld van de beslissing van de Koper om de Overeenkomst te herroepen, terugbetalen. Voor deze terugbetaling worden geen extra kosten in rekening gebracht.</p>
            <p>De koper moet het geleverde goed en de verpakking ervan met zorg behandelen. De koper is alleen aansprakelijk voor de waardevermindering van de Goederen die het gevolg is van een gebruik van de Goederen dat verder gaat dan nodig is om de aard, de kenmerken en de werking van de Goederen vast te stellen.</p>
        </div>
        <div class="article">
            <h3>Artikel 16: Prijsherziening</h3>
            <p>Renowall heeft het recht om de overeengekomen prijs te indexeren op basis van de evolutie van lonen, sociale lasten, materiaalprijzen of transport, op basis van de volgende formule:</p>
            <p>P1 = P0 x (0,2 + (L1/L0) x 0,4 + (M1/M0) x 0,4), waarbij:</p>
            <p>- P1 = nieuwe prijs</p>
            <p>- P0 = oorspronkelijke prijs (basisjaar)</p>
            <p>- L1 = arbeidskosten voor een bepaald jaar (d.w.z. het jaar van indexering)</p>
            <p>- L0 = oorspronkelijke arbeidskosten (basisjaar - loonindex die geldt in de maand voorafgaand aan de bestelbon)</p>
            <p>- M1 = materiaalkosten voor een bepaald jaar (d.w.z. het jaar van indexering)</p>
            <p>- M0 = oorspronkelijke materiaalkosten (basisjaar - prijs die geldt in de maand voorafgaand aan de bestelbon)</p>
        </div>

        <div class="article">
            <h3>Artikel 17: Verwerking van persoonsgegevens</h3>
            <p>Voor de verwerking van persoonsgegevens in het kader van het gebruik van de website wordt verwezen naar het privacy- en cookiebeleid dat beschikbaar is op www.renowall.be.</p>
        </div>
        <div class="article">
            <h3>Artikel 18: Toepasselijk recht en bevoegde rechtbank</h3>
            <p>Het Belgische recht is van toepassing. Als de koper een consument is, zijn alleen de rechtbanken van het arrondissement van de woonplaats van de verweerder en/of die van de plaats waar de verplichtingen (of een van hen) waarover het geschil gaat zijn ontstaan of zijn, zijn geweest of moeten worden uitgevoerd, bevoegd. Als de koper geen consument is, zijn de rechtbanken van het arrondissement van de maatschappelijke zetel van Renowall bevoegd.</p>
        </div>