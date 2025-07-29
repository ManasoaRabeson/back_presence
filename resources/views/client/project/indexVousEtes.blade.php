<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forma-Fusion</title>
    <script src="{{ asset('js/tailwind.js') }}"></script>


</head>

<body>

    <div>
        @include('layouts.navbars.landing')
    </div>

    <div class="mt-24">
        @yield('content')
    </div>


    @include('layouts.homeFooter')
</body>

</html>
