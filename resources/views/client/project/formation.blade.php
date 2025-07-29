<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forma-Fusion</title>
    <script src="{{ asset('js/jquery-3.6.1.min.js') }}"></script>
    <script src="{{ asset('js/tailwind.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('fonts/fontAwesome/css/all.min.css') }}">
</head>

<body>
    <div>
        @include('layouts.navbars.landing')
    </div>
    <div class="mt-24 mb-20">
        @include('client.project.projectListByCategory')
    </div>
    @include('layouts.homeFooter')
</body>

</html>
