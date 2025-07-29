<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forma-Fusion</title>
    <link rel="stylesheet" href="{{ asset('fonts/bootstrapIcons/bootstrap-icons.css') }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Comfortaa:wght@300;500;700&display=swap');
    </style>
    <script src="{{ asset('js/jquery-3.6.1.min.js') }}"></script>
    <script src="{{ asset('js/tailwind.js') }}"></script>
</head>

<body class="flex items-center justify-center min-h-screen">

    <div class="container p-8 mx-auto sm:p-12 lg:p-16">

        <div class="bg-[#f1f1f4] rounded-xl p-8 sm:p-12 flex flex-col items-center gap-6 mx-auto lg:mx-80">

            <div class="flex justify-center">
                <img src="{{ asset('img/logo/Logo_horizontal.svg') }}" alt="Logo" class="h-16 sm:h-20 lg:h-24">
            </div>

            @if (session('status'))
                <p class="text-lg font-medium text-green-600">{{ session('status') }}</p>
            @endif

            <h1 class="text-xl md:text-xl mt-4 font-extrabold text-[#A462A4] leading-tight text-center">
                Rendez-vous sur votre adresse mail pour valider votre demande.
            </h1>

            <p class="text-center text-gray-500 whitespace-pre-line text-md">
                Merci de patienter cinq minutes avant de consulter votre boîte mail.
                Si le message n’apparaît pas, pensez à vérifier votre dossier de spam.
            </p>
            <p class="text-center text-gray-500 text-md">
                Pour toute autre assistance, n'hésitez pas à contacter <a href="/contact"
                    class="text-indigo-600 underline">notre support technique</a>.
            </p>


            <div class="flex justify-between w-full pl-6 pr-6 mt-6">
                <a href="/user" class="bg-[#a462a4] text-white px-3 py-2 rounded-lg">Se connecter</i></a>
                <a href="/" class="bg-[#a462a4] text-white px-3 py-2 rounded-lg">Retour à l'accueil</a>
            </div>

        </div>
    </div>


</body>

</html>
