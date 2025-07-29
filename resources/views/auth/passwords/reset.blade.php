<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forma-Fusion</title>
    <link rel="stylesheet" href="{{ asset('fonts/bootstrapIcons/bootstrap-icons.css') }}">
    <script src="{{ asset('js/jquery-3.6.1.min.js') }}"></script>
    <script src="{{ asset('js/tailwind.js') }}"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap');
    </style>
    <style>
        .yes {
            font-family: "Comfortaa";
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen">

    <div class="container p-8 mx-auto sm:p-12 lg:p-16">
        <div class="bg-[#f1f1f4] rounded-xl p-8 sm:p-12 flex flex-col items-center gap-6 mx-auto lg:mx-80">

            <div class="flex justify-center">
                <img src="{{ asset('img/logo/Logo_horizontal.svg') }}" alt="Logo" class="h-16 sm:h-20 lg:h-24">
            </div>

            <div class="flex items-start w-full gap-4">
                <div class="flex flex-col gap-2 px-4">
                    <p class="sm:text-2xl md:text-4xl font-bold yes text-[#a462a4]">Réinitialiser le mot de passe</p>

                    <div class="mt-2">
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">

                            <p class="text-lg text-gray-700">Saisissez votre email pour recevoir un lien de
                                réinitialisation du mot de passe.</p>
                            <div class="mt-2">
                                <x-input type="email" label="Email de récupération" name="email"
                                    value="{{ $email ?? old('email') }}" />
                                <x-input type="password" label="Nouveau mot de passe" name="password" />
                                <x-input type="password" label="Confirmer nouveau mot de passe"
                                    name="password_confirmation" />
                                <div class="flex flex-col items-center gap-2">
                                    <button type="submit"
                                        class="py-2 px-4 border rounded-full text-base hover:text-white text-white bg-[#a462a4] text-center capitalize mt-2">réinitialiser
                                        votre mot de passe</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>

</body>
<script>
    $('#log').ready(function() {
        function togglePasswordVisibility() {
            var eyeIcon = $(this);
            var targetId = eyeIcon.data("target");
            var passwordInput = $("#" + targetId);

            if (passwordInput.attr("type") === "password") {
                passwordInput.attr("type", "text");
                eyeIcon.removeClass("bi-eye-fill").addClass("bi-eye-slash-fill");
            } else {
                passwordInput.attr("type", "password");
                eyeIcon.removeClass("bi-eye-slash-fill").addClass("bi-eye-fill");
            }
        }

        // Utilisez une classe commune pour tous les éléments déclencheurs
        $(".eye-icon-toggle").click(togglePasswordVisibility);
    });
</script>

</html>
