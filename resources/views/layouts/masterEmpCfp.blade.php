<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forma-Fusion</title>

    {{-- Style --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/bootstrapIcons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/fontAwesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jQuery_wizard/smart_wizard_all.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/bs-stepper.css') }}">
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery.raty.css') }}">
    <link href="{{ asset('css/daisyUI.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        :root {
            --custom-p: #A462A4;
            --fallback-p: var(--custom-p);
        }
    </style>
    @stack('custom_style')

    {{-- Minimum script --}}
    <script src="{{ asset('js/tailwind.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/jquery-3.6.1.min.js') }}"></script>
    <script src="https://www.google.com/recaptcha/enterprise.js?render={{ env('GOOGLE_RECAPTCHA_SECRET') }}"></script>
    
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
    </script>
</head>

<body>
    <div class="flex flex-col w-screen h-screen bg-slate-100">
        {{-- ['total' => "$total_number_projects"] --}}
        @include('layouts.navbars.navbarEmpCfp')
        @yield('content')

        {{-- Content Drawer --}}
        <span id="drawer_content_detail"></span>
        <span id="modal_content_master"></span>
    </div>


    @include('layouts.screenProject.cfp')

    {{-- deconnexion --}}
    <script>
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

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script src="{{ asset('js/sidebar-drawer.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/jQuery_wizard/jquery.smartWizard.min.js') }}"></script>
    <script defer type="module" src="{{ asset('js/tabs.js') }}"></script>

    {{-- bs-stepper script --}}
    <script src="{{ asset('js/stepper/window-script.js') }}"></script>
    <script src="{{ asset('js/stepper/stepper-script.js') }}"></script>
    <script src="{{ asset('js/stepper/bs-stepper.min.js') }}"></script>

    {{-- <script src="{{ asset('js/main_cours/main_cours.js') }}"></script>
    <script src="{{ asset('js/main_clients/main_clients.js') }}"></script>
    <script src="{{ asset('js/main_formateurs/main_formateurs.js') }}"></script>
    <script src="{{ asset('js/main_salles/main_salles.js') }}"></script>
    <script src="{{ asset('js/main_cfp_referents/main_cfp_referents.js') }}"></script>
    <script src="{{ asset('js/main_apprenants/main_apprenants.js') }}"></script>
    <script src="{{ asset('js/main_particuliers/main_particuliers.js') }}"></script> --}}
    <script src="{{ asset('js/global_js.js') }}"></script>
    <script src="{{ asset('js/customer-sidebar.js') }}"></script>
    <script src="{{ asset('js/jquery.raty.js') }}"></script>
    <script src="{{ asset('js/main_sub_contractors/main_sub_contractors.js') }}"></script>

    @yield('script')
    <script>
        function loadBsTooltip() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        };

        loadBsTooltip();
    </script>
</body>

</html>
