@extends('layouts.masterForm')

@push('custom_style')
    <link rel="stylesheet" href="{{ asset('css/jquery.raty.css') }}">
@endpush

@section('content')
    <div class="flex flex-col w-full h-full overflow-y-scroll">
        <div class="w-full h-full p-2 mx-auto lg:container">
            <section id="toggleFilter" class="my-4 hidden">
                <div class="flex flex-col">
                    <div class="flex flex-col gap-4">
                        <span class="inline-flex items-center justify-between w-full">
                            <h3 class="text-2xl font-semibold text-gray-700 count_card_filter"></h3>

                            <button onclick="location.reload()" class="inline-flex items-center gap-2 text-purple-500">
                                <i class="fa-solid fa-rotate-right"></i>
                                Réinitialiser le filtre
                            </button>
                        </span>
                        <div class="grid gap-4 my-2 2xl:grid-cols-5 md:grid-cols-4">
                            <div class="grid col-span-1">
                                <x-drop-filter id="statut" titre="Statut" item="Statut(s)" onClick="refresh('statut')"
                                    item="Projets">
                                    <span id="filterStatut"></span>
                                </x-drop-filter>
                            </div>
                            <div class="grid col-span-1">
                                <x-drop-filter id="entreprise" titre="Entreprise" item="Client(s)"
                                    onClick="refresh('entreprise')" item="Projets">
                                    <span id="filterEntreprise"></span>
                                </x-drop-filter>
                            </div>
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
                                <x-drop-filter id="mois" titre="Mois" item="Mois" onClick="refresh('mois')"
                                    item="Projets">
                                    <span id="filterMois"></span>
                                </x-drop-filter>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

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
    <script src="{{ asset('js/projectList/projectListForm.js') }}"></script>
    <script src="{{ asset('js/filter/filter_projets_form.js') }}"></script>
    <script src="{{ asset('js/filter/newFilter.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script src="{{ asset('js/moment.fr.min.js') }}"></script>
    <script src="{{ asset('js/jquery.raty.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

    <script>
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

        function _getProjet(callback) {
            $.ajax({
                type: "get",
                url: "/projetsForm/list",
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
                url: "/projetsForm/" + idProjet + "/repport",
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
