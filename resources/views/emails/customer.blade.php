<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Invitation</title>
</head>

<body>
  <h3>INVITATION de la part de {{ $cfp->customerName }}</h3>
  <p>Veuillez créer votre compte en suivant le lien suivant
    <a href="{{ route('register.customer') }}">lgcfp.com</a>
  </p>
</body>

</html>
