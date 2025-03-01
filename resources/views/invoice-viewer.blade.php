<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualiseur de facture</title>
    
    <!-- Styles Laravel si nécessaire -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <!-- Inclusion du contenu HTML de la facture -->
    {!! $content !!}
    
    <!-- Scripts Laravel si nécessaire -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>