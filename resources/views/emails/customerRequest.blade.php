<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Invitation</title>
</head>

<body>
  <h3>INVITATION de la part de {{ $customer_name }}</h3>
  <p>Vous avez reçus une demande de collaboration de la part de {{ $customer_name }}</p>
  <p>Veuillez suivre le lien suivant
    <a href="{{ route('login') }}">lgcfp.com</a>
    pour voir l'invitation
  </p>
  <p>Votre mot de passe : <b>1234@#</b></p>
</body>

</html>
