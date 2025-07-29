<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forma-Fusion</title>
    <link rel="stylesheet" href="{{ asset('fonts/fontAwesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/daisyUI.min.css') }}">
    <link rel="icon" href="{{ asset('img/logo/Logo_mark.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">

    <script src="{{ asset('js/jquery-3.6.1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/tailwind.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="https://www.google.com/recaptcha/enterprise.js?render={{ env('GOOGLE_RECAPTCHA_SECRET') }}"></script>
    
    <style>
        :root {
            --custom-p: #A462A4;
            --fallback-p: var(--custom-p);
        }
    </style>
    @stack('custom_style')
</head>


<body>

    <div class="flex flex-col w-screen h-screen bg-white">

        @include('layouts.newNav')

        <div class="w-full h-full pt-10 my-4 lg:pt-32">
            @yield('content')
        </div>
        {{-- Content Drawer --}}
        <span id="drawer_content_detail"></span>
        <span id="modal_content_master"></span>
    </div>

    <script>
        $('.eye-icon-toggle').on('click', function() {
            var input = $('#password');
            var icon = $(this);

            if (input.attr('type') === 'password') {
                input.attr('type', 'text'); // Afficher le mot de passe
                icon.attr('class',
                    'absolute bi bi-eye-slash-fill top-3 right-4 eye-icon-toggle cursor-pointer'
                ); // Changer l'icône
                icon.html(
                    '<path d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7 7 0 0 0 2.79-.588M5.21 3.088A7 7 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474z"/><path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12z"/>'
                );
            } else {
                input.attr('type', 'password'); // Cacher le mot de passe
                icon.attr('class',
                    'absolute bi bi-eye-fill top-3 right-4 eye-icon-toggle cursor-pointer'
                ); // Revenir à l'icône initiale
                icon.html(
                    '<path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/><path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>'
                );
            }
        });
    </script>


    <script>
        function openDialog(html, box = "modal_content_master") {
            // Créer un nouvel élément dialog
            const dialog = $('<dialog class="du_modal"></dialog>');

            // Ajouter le contenu du modal
            dialog.html(html);

            // Ajouter le dialog au body
            var modal_content = $(`#${box}`)
            modal_content.html('');

            modal_content.append(dialog);

            // Ouvrir le modal
            dialog[0].showModal();

            // Écouter l'événement de fermeture
            dialog.find('.du_modal-action').on('click', function() {
                dialog[0].close();
                dialog.remove(); // Retirer le modal du DOM
            });
        }


        //deconnexion
        function logoutButton() {
            let html = `
            <div class="du_modal-box">
                <h3 class="text-lg font-bold">Deconnexion!</h3>
                <p class="py-4">Voulez-vous vraiment vous deconnectez ?</p>
                <div class="du_modal-action">
                    <div method="dialog">
                        <button class="btn">Non, annuler</button>
                        <a class="ml-3 btn btn-primary" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Oui, je confirme</a>
                    </div>
                </div>
            </div>`;
            openDialog(html);
        }
    </script>
    @yield('script')
    <script src="{{ asset('js/jquery.raty.js') }}"></script>
    <script src="{{ asset('js/global_js.js') }}"></script>
    <script>
        function loadBsTooltip() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        };

        loadBsTooltip();

        function raty() {
            $('.raty_notation').each(function() {
                var average = $(this).data('average');
                var elementId = $(this).attr('id');
                ratyNotation(elementId, average);
            });
        }

        raty();
    </script>
</body>

</html>
