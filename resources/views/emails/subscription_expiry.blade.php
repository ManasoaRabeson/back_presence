<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expiration de votre abonnement sur FormaFusion.</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: #A462A4;
            color: #ffffff;
            text-align: center;
            padding: 15px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            font-size: 24px;
            font-weight: bold;
        }

        .content {
            padding: 20px;
            text-align: left;
            font-size: 16px;
            color: #333333;
        }

        .button {
            display: inline-block;
            color: #ffffff;
            padding: 12px 20px;
            border-width: 1px;
            border-radius: 5px;
            border-color: #A462A4;
            font-size: 16px;
            margin-top: 20px;
            text-align: center;
        }

        .button:hover {
            background: #d396d3;
        }

        .footer {
            text-align: center;
            padding: 15px;
            font-size: 14px;
            color: #777777;
            background: #f4f4f4;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
        }

        .footer a {
            color: #A462A4;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- En-tête -->
        <div class="header">
            FormaFusion
        </div>

        <!-- Contenu du message -->
        <div class="content">
            <p>Bonjour,</p>

            <p>Nous souhaitons vous informer que votre abonnement à <strong>FormaFusion</strong> expire dans
                <strong>{{ $daysRemaining }}</strong> jours.
            </p>

            <p>Pour éviter toute interruption de service, nous vous recommandons de renouveler votre abonnement dès
                maintenant.</p>

            <p style="text-align: center;">
                <a href="{{ url('/cfp/abonnement') }}" class="button">Renouveler mon abonnement</a>
            </p>

            <p>Si vous avez des questions ou besoin d’assistance, n’hésitez pas à contacter notre support.</p>

            <p>Merci de votre confiance,</p>
            <p><strong>L’équipe FormaFusion</strong></p>
        </div>

        <!-- Pied de page -->
        <div class="footer">
            © {{ date('Y') }} FormaFusion - Tous droits réservés.
            <br>
            <a href="{{ url('/contact') }}">Contactez-nous</a>
        </div>
    </div>

</body>

</html>
