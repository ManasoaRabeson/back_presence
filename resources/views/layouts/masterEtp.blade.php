<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description"
        content="FormaFusion, un logiciel de gestion de formation professionnelle pour les centres de formation ainsi que les entreprises. Un logiciel en pleine croissance pour résoudre les problèmes liés aux formations en Afrique">
    <title>Forma-Fusion-Vue-Entreprise</title>

    {{-- Style --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('fonts/bootstrapIcons/bootstrap-icons.css') }}"> --}}
    <script src="https://kit.fontawesome.com/60196cd7a0.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('fonts/fontAwesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jQuery_wizard/smart_wizard_all.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/bs-stepper.css') }}">
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery.raty.css') }}">
    <link href="{{ asset('css/purged/daisyUi.css') }}" rel="stylesheet" type="text/css" />
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

    <div class="flex flex-col min-h-screen bg-white">
        @include('layouts.navbars.navbarEntreprise', ['index' => $isIndex ?? false])
        <div class="w-full min-h-screen mt-2">
            @yield('content')
        </div>
        @include('layouts.footerContent')

        {{-- Content Drawer --}}
        <span id="drawer_content_detail"></span>
        <span id="modal_content_master"></span>
    </div>
    
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
                    <a class="ml-3 btn btn-primary hover:text-white" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Oui, je confirme</a>
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

    <script>
        function __addDrawer() {
            let container = $('#drawer_content_detail');
            container.html('');

            var content = `
            <div class="offcanvas offcanvas-end !w-[80em]" tabindex="-1" id="offcanvasAddProject" aria-labelledby="offcanvasAddProject">
                <div class="flex flex-col w-full h-full">
                    <div class="inline-flex items-center justify-between w-full px-4 py-2 bg-gray-100">
                        <p class="text-lg font-medium text-gray-500">Créer un nouveau projet</p>
                        <a data-bs-toggle="offcanvas" href="#offcanvasAddProject"
                            class="flex items-center justify-center w-10 h-10 duration-200 rounded-md cursor-pointer hover:bg-gray-200">
                            <i class="text-gray-500 fa-solid fa-xmark"></i>
                        </a>
                    </div>
                    <div class="w-full h-full">
                        <div class="h-full p-3 mt-2 overflow-y-scroll modal-body">
                            @include('ETP.projets.create')
                        </div>
                    </div>
                </div>
            </div>`;

            container.append(content);

            container.ready(function() {
                $('#smartwizard').smartWizard({
                    theme: 'arrows',
                    toolbar: {
                        position: 'bottom', // none|top|bottom|both
                        showNextButton: false, // show/hide a Next button
                        showPreviousButton: false, // show/hide a Previous button
                    },
                    anchor: {
                        enableNavigation: true, // Enable/Disable anchor navigation 
                        enableNavigationAlways: false, // Activates all anchors clickable always
                        enableDoneState: true, // Add done state on visited steps
                        markPreviousStepsAsDone: true, // When a step selected by url hash, all previous steps are marked done
                        unDoneOnBackNavigation: false, // While navigate back, done state will be cleared
                        enableDoneStateNavigation: true // Enable/Disable the done state navigation
                    },
                });

                $('#smartwizard-intra').smartWizard({
                    theme: 'arrows',
                    toolbar: {
                        position: 'bottom', // none|top|bottom|both
                        showNextButton: false, // show/hide a Next button
                        showPreviousButton: false, // show/hide a Previous button
                    },
                    anchor: {
                        enableNavigation: true, // Enable/Disable anchor navigation 
                        enableNavigationAlways: false, // Activates all anchors clickable always
                        enableDoneState: true, // Add done state on visited steps
                        markPreviousStepsAsDone: true, // When a step selected by url hash, all previous steps are marked done
                        unDoneOnBackNavigation: false, // While navigate back, done state will be cleared
                        enableDoneStateNavigation: true // Enable/Disable the done state navigation
                    },
                });

                $('#smartwizard-intra').smartWizard("reset");
                $('#smartwizard').smartWizard("reset");

                // Bouton Next
                var btnNextProject = $('#main_next_btn_project');
                btnNextProject.on('click', function() {
                    mainStoreProject(1);
                });

                // Bouton Next Inter
                var btnNextProjectInter = $('#main_next_btn_project_inter');
                btnNextProjectInter.on('click', function() {
                    mainStoreProject(2);
                });

                var btnNextEtp = $('#main_next_btn_etp');
                var main_project_get_id = $('#main_project_get_id').val();
                btnNextEtp.on('click', function() {
                    mainGetFirstModules($('#main_project_get_id').val(), 1);

                    $('#smartwizard-intra').smartWizard("next");
                });

                var btnNextLieu = $('#main_next_btn_lieu_inter');
                btnNextLieu.on('click', function() {
                    $('#smartwizard').smartWizard("next");
                    getVille();
                });

                var btnNextDate = $('#main_next_btn_date_inter');
                btnNextDate.on('click', function() {
                    $('#smartwizard').smartWizard("next");
                    getDossierStepper();
                });

                var btnNextDossier = $('#main_next_btn_dossier_inter');
                btnNextDossier.on('click', function() {
                    $('#smartwizard').smartWizard("next");
                    getModalite(2);
                });


                var btnNextCours = $('#main_next_btn_cours');
                btnNextCours.on('click', function() {
                    $('#smartwizard-intra').smartWizard("next");
                    getDossierStepper();
                });

                var btnNextDossier = $('#main_next_btn_dossier');
                btnNextDossier.on('click', function() {
                    $('#smartwizard-intra').smartWizard("next");
                    getModalite(1);
                });

                // Bouton Prev
                $('.prevBtn').on('click', function() {
                    $('#smartwizard-intra').smartWizard("prev");
                });
                // Entreprise
                $('#ajoutEtp').click(function(e) {
                    e.preventDefault();
                    $('#formEtp').toggleClass(`h-max`, `h-0`);
                });

                // Cours
                $('#ajoutModule').click(function(e) {
                    e.preventDefault();
                    $('#formModule').toggleClass(`h-max`, `h-0`);
                });

                // Formateur
                $('#ajoutForm').click(function(e) {
                    e.preventDefault();
                    $('#formFormateur').toggleClass(`h-max`, `h-0`);
                });
            });


            let offcanvasId = $('#offcanvasAddProject')
            var bsOffcanvas = new bootstrap.Offcanvas(offcanvasId);
            bsOffcanvas.show();
        }

        function mainLoadVille() {
            $.ajax({
                type: "get",
                url: "/etp/salles/loadVille",
                dataType: "json",
                success: function(res) {
                    var villes = $('#main_salle_idVille');
                    villes.html('');
                    villes.append(`<option value="0" selected disabled>--selectionnez une ville--</option>`);
                    $.each(res.villes, function(key, val) {
                        villes.append(`<option value="` + val.idVille + `">` + val.ville + `</option>`);
                    });
                }
            });

            var offcanvasElement = document.getElementById('offcanvasAddApprenant');

            if (offcanvasElement) {
                var bsOffcanvas = new bootstrap.Offcanvas(offcanvasElement);
                bsOffcanvas.show();
            }
        }

    </script>

    {{-- Script for all page --}}
    <script src="{{ asset('js/sidebar-drawer.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/jQuery_wizard/jquery.smartWizard.min.js') }}"></script>
    <script defer type="module" src="{{ asset('js/tabs.js') }}"></script>


    {{-- bs-stepper script --}}

    {{-- modal nouveau --}}
    <script src="{{ asset('js/stepper/window-script-etp.js') }}"></script>
    {{-- fin modal nouveau --}}
    <script src="{{ asset('js/stepper/stepper-etp-script.js') }}"></script>
    <script src="{{ asset('js/stepper/bs-stepper.min.js') }}"></script>

    <script src="{{ asset('js/jquery.mask.min.js') }}"></script>

    <script src="{{ asset('js/main_cours/main_cours_internes.js') }}"></script>
    <script src="{{ asset('js/main_employes/main-employe.js') }}"></script>
    <script src="{{ asset('js/main_referents/main-referent.js') }}"></script>

    <script src="{{ asset('js/invitations/cfp.js') }}"></script>

    <script src="{{ asset('js/main_etp_projet/main_etp_projet.js') }}"></script>
    <script src="{{ asset('js/main_salles/main_salles_etp.js') }}"></script>
    <script src="{{ asset('js/main_formateurs_internes/main_formateurs_internes.js') }}"></script>


    {{-- JS for Drawer ETP details --}}
    <script src="{{ asset('js/global_js.js') }}"></script>
    <script src="{{ asset('js/customer-sidebar-etp.js') }}"></script>
    <script src="{{ asset('js/jquery.raty.js') }}"></script>


    <script>
        $(document).ready(function() {
            getEtpType();
        });

        function getEtpType() {
            $.ajax({
                type: "get",
                url: "/etp/employes/add/getEtpType",
                dataType: "json",
                success: function(res) {
                    var main_append_etp = $('.main_append_etp');
                    main_append_etp.empty();

                    if (res.etp.idTypeEtp == 2) {
                        main_append_etp.append(`<div class="flex flex-col w-full gap-1">
                                      <label for="Entreprise" class="text-gray-600 after:content-['*'] after:ml-0.5 after:text-red-500">Entreprise</label>
                                      <select id="main_append_etp"
                                        class="outline-none bg-white w-full bg-transparent pl-2 h-10 border-[1px] border-gray-200 rounded-md hover:border-purple-300 focus:border-purple-300 focus:ring-2 focus:ring-purple-100 duration-200 text-gray-400"></select>
                                    </div>`);

                        $('#main_append_etp').empty();
                        if (res.etpGrps.length <= 0) {
                            $('#main_append_etp').append(
                                `<option value="0" selected disabled>--Veuillez selectionner--</option>`);
                        } else {
                            $('#main_append_etp').append(
                                `<option value="0" selected disabled>--Veuillez selectionner--</option>`);
                            $.each(res.etpGrps, function(i, v) {
                                $('#main_append_etp').append(`<option value="` + v.idEntreprise + `">` +
                                    v.etp_name +
                                    `</option>`);
                            });
                        }
                    } else if (res.error) {
                        console.log(res.error);
                    }
                }
            });
        }
    </script>

    @yield('script')
    <script>
        function loadBsTooltip() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        };

        loadBsTooltip();

        var endpoint = "{{ $endpoint }}";
        var bucket = "{{ $bucket }}";

        var digitalOcean = endpoint + '/' + bucket;
    </script>

</body>

</html>
