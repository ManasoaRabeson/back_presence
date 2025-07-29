<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forma-Fusion-Apprenant</title>

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
    
</head>

<body>
    <div class="flex flex-col min-h-screen bg-white">
        @include('layouts.navbars.navbarEmploye', ['index' => $isIndex ?? false])
        <div class="w-full min-h-screen mt-2">
            @yield('content')
        </div>
        @include('layouts.footerContent')

        {{-- Content Drawer --}}
        <span id="drawer_content_detail"></span>
        <span id="modal_content_master"></span>
        <span id="drawer_content_export"></span>
    </div>

    {{-- Modal Deconnexion --}}
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

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
        
        function logoutButton() {
                let html = `
            <div class="du_modal-box">
                <h3 class="text-lg font-bold">{{__('launcher.logout')}}!</h3>
                <p class="py-4">{{__('modal.phraseDeconnexion')}}</p>
                <div class="du_modal-action">
                    <div method="dialog">
                        <button class="btn">{{__('modal.nonAnnuler')}}</button>
                        <a class="ml-3 btn btn-primary hover:text-white" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{__('modal.ouiConfirmer')}}</a>
                    </div>
                </div>
            </div>`;
                openDialog(html);
            }
    </script>


    <script src="{{ asset('js/sidebar-drawer.js') }}"></script>
    <script src="{{ asset('js/collapse.js') }}"></script>
    <script type="module" src="{{ asset('js/tabs.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/jquery.raty.js') }}"></script>
    <script src="{{ asset('js/global_js.js') }}"></script>
    <script src="{{ asset('js/customer-sidebar-app.js') }}"></script>


    @yield('script')
    <script defer src="{{ asset('js/cdn.min.js') }}"></script>
    {{-- Cropper --}}
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/cropper.js') }}"></script>
    <script>
        function loadBsTooltip() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        };

        loadBsTooltip();
    </script>

</html>
