<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description"
        content="FormaFusion, un logiciel de gestion de formation professionnelle pour les centres de formation ainsi que les entreprises. Un logiciel en pleine croissance pour résoudre les problèmes liés aux formations en Afrique">
    <title>Forma-Fusion</title>

    {{-- Style --}}
    <link href="{{ asset('css/purged/daisyUi.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('fonts/bootstrapIcons/bootstrap-icons.css') }}"> --}}
    <script src="https://kit.fontawesome.com/60196cd7a0.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('fonts/fontAwesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jQuery_wizard/smart_wizard_all.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/bs-stepper.css') }}">
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery.raty.css') }}">
    <link rel="icon" href="{{ asset('img/logo/Logo_mark.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

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

    <style>
        .notification-glow {
            box-shadow: 0 0 8px rgba(255, 0, 0, 0.6);
            /* Lueur autour du texte */
            animation: glow 1.5s infinite alternate;
        }

        @keyframes glow {
            from {
                box-shadow: 0 0 8px rgba(255, 0, 0, 0.6);
            }

            to {
                box-shadow: 0 0 16px rgba(255, 0, 0, 1);
                /* Augmentation de l'intensité de la lueur */
            }
        }
    </style>

</head>

<body>
    <div class="flex flex-col min-h-screen bg-white">
        @include('layouts.newNav', ['index' => $isIndex ?? false])
        <div class="w-full min-h-screen mt-2">
            @yield('content')
        </div>
        @include('layouts.footerContent')

        {{-- Content Drawer --}}
        <span id="drawer_content_detail"></span>
        <span id="modal_content_master"></span>
        <span id="drawer_content_export"></span>
    </div>

    {{-- deconnexion --}}
    <script>
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
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script src="{{ asset('js/moment.fr.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        var APP_LOCALE = "{{ app()->getLocale() }}";

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
                                @include('CFP.projets.create')
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
                    if (btnNextDossier.attr('data-dossier') == "true") {
                        $('#smartwizard').smartWizard("next");
                        getModalite(2);
                    } else {
                        toastr.error("Veuillez assigner ce projet à un dossier !", 'Erreur', {
                            timeOut: 1500
                        });
                    }
                });


                var btnNextCours = $('#main_next_btn_cours');
                btnNextCours.on('click', function() {
                    $('#smartwizard-intra').smartWizard("next");
                    getDossierStepper();
                });

                var btnNextDossier = $('#main_next_btn_dossier');
                btnNextDossier.on('click', function() {
                    if (btnNextDossier.attr('data-dossier') == "true") {
                        $('#smartwizard-intra').smartWizard("next");
                        getModalite(1);
                    } else {
                        toastr.error("Veuillez assigner ce projet à un dossier !", 'Erreur', {
                            timeOut: 1500
                        });
                    }

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
        }

        $(document).ready(function() {
            getSearchKey();
        });

        function getSearchKey() {
            $('#key').on('keyup', function() {
                let key = $('#key').val().trim();

                if (key.length === 0) {
                    $('#key').autocomplete("close");
                    return;
                }

                $.ajax({
                    type: "get",
                    url: "{{ route('keySuggestion') }}",
                    data: {
                        key: key
                    },
                    dataType: "json",
                    success: function(responses) {
                        console.log(responses);

                        $('#key').autocomplete({
                            source: responses,
                            minLength: 0,
                            select: function(event, ui) {
                                $('#key').val(ui.item.value);
                            }
                        }).data("ui-autocomplete")._renderItem = function(ul, item) {
                            return $("<li class='ui-autocomplete-row'>")
                                .data("item.autocomplete", item)
                                .append(item.label)
                                .appendTo(ul);
                        };
                    },
                    error: function(err) {
                        console.error("Erreur lors de la récupération des suggestions :", err);
                    }
                });
            });
        }

        function showCreateEtp() {
            var etp_create_form = $('#etp_create_form');
            etp_create_form.toggleClass('hidden');
        }

        function hideForm() {
            var etp_create_form = $('#etp_create_form');
            etp_create_form.toggleClass('hidden');
        }

        // invitation entreprise dans createProject()
        function projectInviteEntreprise() {
            $.ajax({
                type: "post",
                url: "/cfp/invites/etp/projet",
                data: {
                    etp_nif: $('.project_etp_nif').val(),
                    etp_name: $('.project_etp_name').val(),
                    etp_email: $('.project_etp_email').val()
                },
                dataType: "json",
                beforeSend: function() {
                    $('.project_main_loading_send').append(`<div id="main_img_loading" class="spinner-grow text-primary" role="status">
                                                <span class="sr-only">Loading...</span>
                                                </div>`);
                },
                complete: function() {
                    $('#main_img_loading').remove();
                },
                success: function(res) {
                    console.log(res);

                }
            });
        }
    </script>

    {{-- bs-stepper script --}}

    {{-- modal nouveau --}}
    <script src="{{ asset('js/stepper/window-script.js') }}"></script>
    {{-- fin modal nouveau --}}
    <script src="{{ asset('js/stepper/stepper-script.js') }}"></script>
    <script src="{{ asset('js/stepper/bs-stepper.min.js') }}"></script>

    <script src="{{ asset('js/global_js.js') }}"></script>
    <script src="{{ asset('js/customer-sidebar.js') }}"></script>
    <script src="{{ asset('js/jquery.raty.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.13.3/jquery-ui.min.js"
        integrity="sha256-sw0iNNXmOJbQhYFuC9OF2kOlD5KQKe1y5lfBn4C9Sjg=" crossorigin="anonymous"></script>

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
