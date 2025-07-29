<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande de devis</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .header {
            background-color: #f4f4f4;
            padding: 10px;
            text-align: center;
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
        }

        .content {
            margin-top: 20px;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 0.9rem;
            color: #777;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #f4f4f4;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">Demande de devis</div>
        <div class="content">
            <p>Bonjour,</p>
            <p>Voici les détails de la demande de devis :</p>
            <table>
                <tr>
                    <th>Nom de l'entreprise</th>
                    <td>{{ $etp_name }}</td>
                </tr>
                <tr>
                    <th>E-mail</th>
                    <td>{{ $etp_email }}</td>
                </tr>
                <tr>
                    <th>Téléphone</th>
                    <td>{{ $etp_phone }}</td>
                </tr>
                <tr>
                    <th>Nom du référent</th>
                    <td>{{ $ref_name }}</td>
                </tr>
                <tr>
                    <th>Prénom du référent</th>
                    <td>{{ $ref_firstname }}</td>
                </tr>
                <tr>
                    <th>Type de projet</th>
                    <td>{{ $project_type }}</td>
                </tr>
                <tr>
                    <th>Modalite du projet</th>
                    <td>{{ $modalite }}</td>
                </tr>
                <tr>
                    <th>Nombre d'apprenants</th>
                    <td>{{ $nb_appr }}</td>
                </tr>
                <tr>
                    <th>Type de financement</th>
                    <td>{{ $financement }}</td>
                </tr>
                <tr>
                    <th>Date de début</th>
                    <td>{{ $dateDeb }}</td>
                </tr>
                <tr>
                    <th>Date de fin</th>
                    <td>{{ $dateFin }}</td>
                </tr>
                <tr>
                    <th>Lieu de formation</th>
                    <td>{{ $lieu_formation }}</td>
                </tr>
                <tr>
                    <th>Notes supplémentaires</th>
                    <td>{{ $note }}</td>
                </tr>
            </table>
        </div>
        <div class="footer">
            <p>Merci d'avoir soumis votre demande. Nous reviendrons vers vous rapidement.</p>
        </div>
    </div>
</body>

</html>
