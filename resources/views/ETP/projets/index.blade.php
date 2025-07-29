@extends('layouts.masterEtp')

@push('custom_style')
    <link rel="stylesheet" href="{{ asset('css/jquery.raty.css') }}">
@endpush


@section('content')
    <div class="flex flex-col w-full h-full">
        <div class="w-full h-full p-2 mx-auto lg:container">
            <div id="toggleFilter" class="hidden in">
                <section id="filterSection" class="my-12">
                    <div class="flex flex-col">

                        {{-- Filtre --}}
                        <div class="flex flex-col gap-4 mt-12">
                            <span class="inline-flex items-center justify-between w-full">
                                <h3 class="text-2xl font-semibold text-gray-700 count_card_filter"></h3>

                                <button onclick="location.reload()" class="inline-flex items-center gap-2 text-purple-500">
                                    <i class="fa-solid fa-rotate-right"></i>
                                    Réinitialiser le filtre
                                </button>
                            </span>
                            <div class="grid gap-4 my-2 2xl:grid-cols-5 md:grid-cols-4">
                                <div class="grid col-span-1">
                                    <x-drop-filter id="type" titre="Type de projet" item="Type(s)"
                                        onClick="refresh('type')" item="Projets">
                                        <span id="filterType"></span>
                                    </x-drop-filter>
                                </div>
                                <div class="grid col-span-1">
                                    <x-drop-filter id="periode" titre="Période de formation" item="Période(s)"
                                        onClick="refresh('periode')" item="Projets">
                                        <span id="filterPeriode"></span>
                                    </x-drop-filter>
                                </div>
                                <div class="grid col-span-1">
                                    <x-drop-filter id="cours" titre="Cours" item="Cours" onClick="refresh('cours')"
                                        item="Projets">
                                        <span id="filterModule"></span>
                                    </x-drop-filter>
                                </div>
                                <div class="grid col-span-1">
                                    <x-drop-filter id="ville" titre="Ville" item="Ville" onClick="refresh('ville')"
                                        item="Projets">
                                        <span id="filterVille"></span>
                                    </x-drop-filter>
                                </div>
                                <div class="grid col-span-1">
                                    <x-drop-filter id="financement" titre="Type de financement" item="Financement"
                                        onClick="refresh('financement')" item="Projets">
                                        <span id="filterFinancement"></span>
                                    </x-drop-filter>
                                </div>
                                <div class="grid col-span-1">
                                    <x-drop-filter id="formateur" titre="Formateur" item="Formateur"
                                        onClick="refresh('formateur')" item="Projets">
                                        <span id="filterFormateur"></span>
                                    </x-drop-filter>
                                </div>
                                <div class="grid col-span-1">
                                    <x-drop-filter id="mois" titre="Mois" item="Mois"
                                        onClick="refresh('mois')" item="Projets">
                                        <span id="filterMois"></span>
                                    </x-drop-filter>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="flex flex-col" id="headDate">
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script>
        function lineProgress() {
            var lineProgress = '';

            lineProgress =
                `
            <div id="progress-container" class="w-full h-1 bg-gray-200 rounded-full">
                <div id="progress-bar" class="h-1 bg-[#a462a4] rounded-full"></div>
            </div>
            `;

            return lineProgress;
        }
    </script>
    <script src="{{ asset('js/filter/filter_projets_etp.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('js/filter/newFilter.js') }}"></script>
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script src="{{ asset('js/moment.fr.min.js') }}"></script>

    <script src="{{ asset('js/projectListEtp.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

    <script>
        // $(document).ready(function() {
        //     getDropdownItem();

        //     var RatingNote = $(`.raty_notation_id`);

        //     $.each(RatingNote, function(i, v) {
        //         ratyNotation($(this).attr('id'), parseFloat($(this).attr('data-val')));
        //         console.log($(this).attr('data-val'));
        //     });
        // });

        $(document).ready(function() {
            _getProjet(function(data) {
                _showProjet(data);
            });
            getDropdownItem();
            $("#filterButton").click(function() {
                $("#toggleFilter").toggle(); // toggle collapse
            });
        });
        var endpoint = "{{ $endpoint }}";
        var bucket = "{{ $bucket }}";

        var digitalOcean = endpoint + '/' + bucket;

        function _getProjet(callback) {
            $.ajax({
                type: "get",
                url: "/etp/projets/list",
                dataType: "json",
                beforeSend: function() {
                    var content_grid_project = $('#headDate');
                    content_grid_project.html('');
                    content_grid_project.append(lineProgress());
                    const $progressBar = $('#progress-bar');
                    let progress = 0;
                    const interval = setInterval(() => {
                        if (progress >= 98) {
                            clearInterval(interval);
                        } else {
                            progress += 1;
                            $progressBar.css('width', `${progress}%`);
                        }
                    }, 8); //8ms
                },
                success: function(res) {
                    callback(res);
                }
            });
        }

        function repportProject(idProjet) {
            let dateDebut = $('.dateDebutProjetDetail_' + idProjet).val();
            let dateFin = $('.dateFinProjetDetail_' + idProjet).val();

            console.log(dateDebut, dateFin);

            $.ajax({
                type: "patch",
                url: "/etp/projets/" + idProjet + "/repport",
                data: {
                    _token: '{!! csrf_token() !!}',
                    dateDebut: dateDebut,
                    dateFin: dateFin
                },
                dataType: "json",
                success: function(res) {
                    if (res.success) {
                        toastr.success(res.success, 'Opération effectuée avec succès', {
                            timeOut: 1500
                        });
                        location.reload();
                    } else {
                        console.log(res.error);
                        toastr.error(res.error, 'Erreur', {
                            timeOut: 1500
                        });
                    }
                }
            });
        };

        function formatNumber(number, decimals, dec_point, thousands_sep) {
            // Limiter à 'decimals' chiffres après la virgule
            let n = number.toFixed(decimals);

            // Remplacer le point par la virgule pour la partie décimale
            n = n.replace('.', dec_point);

            // Séparer les parties entière et décimale
            let parts = n.split(dec_point);
            let integerPart = parts[0];
            let decimalPart = parts.length > 1 ? dec_point + parts[1] : '';

            // Ajouter les séparateurs de milliers
            integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, thousands_sep);

            return integerPart + decimalPart;
        }


        $(function() {
            $('#daterangeProjet').daterangepicker({
                opens: 'center',
                showCustomRangeLabel: true,
                locale: {
                    format: 'DD/MM/YYYY',
                    separator: ' - ',
                    applyLabel: 'Valider',
                    cancelLabel: 'Annuler',
                    fromLabel: 'De',
                    toLabel: 'À',
                    customRangeLabel: 'Personnalisé',
                    weekLabel: 'S',
                    daysOfWeek: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
                    monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août',
                        'Septembre',
                        'Octobre', 'Novembre', 'Décembre'
                    ],
                    firstDay: 1
                }
            });
        });

        var acc = document.getElementsByClassName("accordion");
        var i;

        for (i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function() {
                /* Toggle between adding and removing the "active" class,
                to highlight the button that controls the panel */
                this.classList.toggle("active");

                /* Toggle between hiding and showing the active panel */
                var panel = this.nextElementSibling;
                if (panel.style.display === "flex") {
                    panel.style.display = "none";
                } else {
                    panel.style.display = "flex";
                }
            });
        }

        function showModalConfirmation(idProjet, type) {
            var modal_confirmation = $('#modal_confirmation');
            modal_confirmation.html('');

            var content = ``;

            switch (type) {
                case 'Valider':
                    content = `<x-modal-session onClick="manageProject('patch', '/etp/projets/${idProjet}/confirm')"
                id="${idProjet}" titre="Confirmation"
                description="Voulez-vous vraiment programmer ce projet ?" />`;
                    break;

                case 'Supprimer':
                    content = `
                        <div class="du_modal-box">
                            <h3 class="text-lg font-bold">Supprimer!</h3>
                            <div class="flex flex-col items-center modal-body">
                                <p class="text-lg text-gray-500">Voulez-vous vraiment supprimer ce projet ?</p>
                                <p class="text-gray-400">Projet : <span class="font-medium text-gray-500 projet_modal_${idProjet}"></span></p>
                                <p class="text-gray-400">Entreprise(s) : <span class="font-medium text-gray-500 etp_modal_${idProjet}"></span></p>
                            </div>
                            <div class="du_modal-action">
                                <form method="dialog">
                                    <button class="btn">Non, annuler</button>
                                    <button class="ml-3 btn btn-primary" onClick="manageProject('delete', '/etp/projets/${idProjet}/destroy')">Oui, je confirme</button>
                                </form>
                            </div>
                        </div>`;
                    break;

                case 'Dupliquer':
                    content = `<x-modal-session onClick="manageProject('post', '/etp/projets/${idProjet}/duplicate')"
                id="${idProjet}" titre="Dupliquer"
                description="Voulez-vous vraiment dupliquer ce projet ?" />`;

                    break;
                case 'Annuler':
                    content = `<x-modal-session onClick="manageProject('patch', '/etp/projets/${idProjet}/cancel')"
                id="${idProjet}" titre="Annuler"
                description="Voulez-vous vraiment annuler ce projet ?" />`;

                    break;
                case 'Cloturer':
                    content = `<x-modal-session onClick="manageProject('patch', '/etp/projets/${idProjet}/close')"
                id="${idProjet}" titre="Clôturer le projet"
                description="Voulez-vous vraiment clôturer ce projet ?" />`;

                    break;
                case 'RendrePublic':
                    content = `<x-modal-session onClick="manageProject('patch', '/etp/projets/${idProjet}/updatePrivacy')"
                id="${idProjet}" titre="Mettre sur le marcher"
                description="Voulez-vous vraiment rendre ce projet publique ?" addNbPlace="on"/>`;
                    break;

                case 'RendrePrivee':
                    content = `<x-modal-session onClick="manageProject('patch', '/etp/projets/${idProjet}/updatePrivacy')"
                id="${idProjet}" titre="Retirer sur le marcher"
                description="Voulez-vous vraiment rendre ce projet privé ?" />`;
                    break;

                case 'Reporter':
                    content = `
                            <div class="du_modal-box">
                                <h3 class="text-lg font-bold">A reporter le</h3>
                                <div class="inline-flex items-center gap-2 mb-4">
                                    <x-input type="date" label="Début" name="dateDebutProjetDetail_${idProjet}"
                                    screen="lg" />
                                    <x-input type="date" label="Fin" name="dateFinProjetDetail_${idProjet}" screen="lg" />
                                </div>
                                <div class="du_modal-action">
                                    <form method="dialog">
                                        <button class="btn">Non, annuler</button>
                                        <button class="ml-3 btn btn-primary" onclick="repportProject(${idProjet})">Oui, je confirme</button>
                                    </form>
                                </div>
                            </div>`;
                    break;

                default:
                    break;
            }

            openDialog(content);
        }

        function manageProject(type, route) {
            $.ajax({
                type: type,
                url: route,
                data: {
                    _token: '{!! csrf_token() !!}',
                    nbPlace: $("#get_place").val()
                },
                dataType: "json",
                success: function(res) {
                    console.log('Manage project-->', res)
                    if (res.success) {
                        toastr.success(res.success, 'Opération effectuée avec succès', {
                            timeOut: 1500
                        });
                        location.reload();
                    } else {
                        toastr.error(res.error, 'Erreur', {
                            timeOut: 1500
                        });
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        };

        function repportProject(idProjet) {
            let dateDebut = $('.dateDebutProjetDetail_' + idProjet).val();
            let dateFin = $('.dateFinProjetDetail_' + idProjet).val();

            console.log(dateDebut, dateFin);

            $.ajax({
                type: "patch",
                url: "/etp/projets/" + idProjet + "/repport",
                data: {
                    _token: '{!! csrf_token() !!}',
                    dateDebut: dateDebut,
                    dateFin: dateFin
                },
                dataType: "json",
                success: function(res) {
                    if (res.success) {
                        toastr.success(res.success, 'Opération effectuée avec succès', {
                            timeOut: 1500
                        });
                        location.reload();
                    } else {
                        console.log(res.error);
                        toastr.error(res.error, 'Erreur', {
                            timeOut: 1500
                        });
                    }
                }
            });
        };
    </script>
@endsection
