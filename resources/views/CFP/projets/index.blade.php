@extends('layouts.master')

@php
    $bg = '';
    $nb = 0;
    $label = '';
@endphp

@push('custom_style')
    <link rel="stylesheet" href="{{ asset('css/jquery.raty.css') }}">
    <link rel="stylesheet" href="{{ asset('css/clusterize.css')}}">
@endpush

@section('isIndex', true)

@section('content')
    <div class="flex flex-col w-full h-full">
        <div class="w-full bg-white">
            {{-- Text Result Search --}}
            <div class="container flex flex-col justify-between gap-4 p-4 lg:flex-row">
                <span class="flex flex-col items-center gap-3 lg:flex-row">
                </span>
            </div>
        </div>

        <div role="tabpanel" data-content="projets" class="w-full h-full p-2 mx-auto tab-content-project">
            <div id="toggleFilter" class="hidden in">
                <section id="filterSection" class="mb-4">
                    <div class="flex flex-col">
                        <div class="flex flex-col gap-4">
                            <span class="inline-flex items-center justify-between w-full">
                                <h3 class="text-2xl font-semibold text-gray-700 count_card_filter"></h3>

                                <button onclick="location.reload()"
                                    class="inline-flex items-center gap-2 text-purple-500">
                                    <i class="fa-solid fa-rotate-right"></i>
                                    Réinitialiser le filtre
                                </button>
                            </span>
                            <div class="grid gap-4 my-2 2xl:grid-cols-5 md:grid-cols-4">
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
                                    <x-drop-filter id="cours" titre="Cours" item="Cours"
                                        onClick="refresh('cours')" item="Projets">
                                        <span id="filterModule"></span>
                                    </x-drop-filter>
                                </div>
                                <div class="grid col-span-1">
                                    <x-drop-filter id="ville" titre="Ville" item="Ville"
                                        onClick="refresh('ville')" item="Projets">
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

            <div class="flex flex-col mt-6 h-full" id="scrollArea">
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
    <script src="{{asset('js/lang/lang.js')}}"></script>

    <script src="{{ asset('js/clusterize.min.js') }}"></script>

    <!-- MANIPULATION POUR L'AUTHENTIFICATION OAUTH2 DE GOOGLE -->
    <script src="{{ asset('js/gapi_loading.js') }}"></script>
    <script src="{{ asset('js/filter/filter_projets.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('js/filter/newFilter.js') }}"></script>
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script src="{{ asset('js/moment.fr.min.js') }}"></script>
    <script src="{{ asset('js/projectList.js') }}"></script>
    <script src="{{ asset('js/jquery.raty.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

    <script src="{{ asset('js/daypilot-pro-javascript/daypilot-javascript.min.js') }}"></script>
    <script src="{{ asset('js/agendas/CFP/planning.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>

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

        function view_click() {
            var checkbox = $('input[name="view_check"]');

            if (checkbox.prop('checked')) {
                checkbox.prop('checked', false); // Si coché, décocher
                sessionStorage.setItem('view_project', 'carte');
            } else {
                checkbox.prop('checked', true); // Si pas coché, cocher
                sessionStorage.setItem('view_project', 'list');
            }
        }

        // Fonction pour gérer l'état de l'input
        function toggleView() {
            // Récupérer l'élément input et les deux sections
            var checkbox = $('input[name="view_check"]');
            var section1 = $(`section[data-view="carte"]`);
            var section2 = $(`section[data-view="list"]`);
            var swap_1 = $('#swap_1');
            var swap_2 = $('#swap_2');
            var view_project_session = sessionStorage.getItem('view_project');

            if (view_project_session == 'list') {
                // Si la checkbox est cochée, afficher la section "list_${head.headDate}" et cacher l'autre
                section2.removeClass('hidden');
                section1.addClass('hidden');
                swap_2.removeClass('hidden');
                swap_1.addClass('hidden');
                checkbox.prop('checked', true); // Si pas coché, cocher
            } else {
                // Si la checkbox est décochée, afficher la section "${head.headDate}" et cacher l'autre
                section1.removeClass('hidden');
                section2.addClass('hidden');
                swap_1.removeClass('hidden');
                swap_2.addClass('hidden');
                checkbox.prop('checked', false);

            }

        }

        var endpoint = "{{ $endpoint }}";
        var bucket = "{{ $bucket }}";

        function _getProjet(callback) {
            $.ajax({
                type: "get",
                url: "/cfp/projets/list",
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

        function showModalConfirmation(idProjet, type) {
            var modal_confirmation = $('#modal_confirmation');
            modal_confirmation.html('');

            var content = ``;

            switch (type) {
                case 'Valider':
                    content = `<x-modal-session onClick="manageProject('patch', '/cfp/projets/${idProjet}/confirm')"
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
                                    <button class="ml-3 btn btn-primary" onClick="manageProject('delete', '/cfp/projets/${idProjet}/destroy')">Oui, je confirme</button>
                                </form>
                            </div>
                        </div>`;
                    break;

                case 'Dupliquer':
                    content = `<x-modal-session onClick="manageProject('post', '/cfp/projets/${idProjet}/duplicate')"
                id="${idProjet}" titre="Dupliquer"
                description="Voulez-vous vraiment dupliquer ce projet ?" />`;

                    break;
                case 'Annuler':
                    content = `<x-modal-session onClick="manageProject('patch', '/cfp/projets/${idProjet}/cancel')"
                id="${idProjet}" titre="Annuler"
                description="Voulez-vous vraiment annuler ce projet ?" />`;

                    break;
                case 'Cloturer':
                    content = `<x-modal-session onClick="manageProject('patch', '/cfp/projets/${idProjet}/close')"
                id="${idProjet}" titre="Clôturer le projet"
                description="Voulez-vous vraiment clôturer ce projet ?" />`;

                    break;
                case 'RendrePublic':
                    content = `<x-modal-session onClick="manageProject('patch', '/cfp/projets/${idProjet}/updatePrivacy'); updateNbPlace(${idProjet})"
                id="${idProjet}" titre="Mettre sur le marcher"
                description="Voulez-vous vraiment rendre ce projet publique ?" addNbPlace="on"/>`;
                    break;

                case 'RendrePrivee':
                    content = `<x-modal-session onClick="manageProject('patch', '/cfp/projets/${idProjet}/updatePrivacy')"
                id="${idProjet}" titre="Retirer sur le marcher"
                description="Voulez-vous vraiment rendre ce projet privé ?" />`;
                    break;

                case 'Archiver':
                    content = `<x-modal-session onClick="manageProject('patch', '/cfp/projets/${idProjet}/archive')"
                id="${idProjet}" titre="Archiver"
                description="Voulez-vous vraiment archiver ce projet ?" />`;
                    break;

                case 'Restaurer':
                    content = `<x-modal-session onClick="manageProject('patch', '/cfp/projets/${idProjet}/restoreArchive')"
                id="${idProjet}" titre="Restaurer"
                description="Voulez-vous vraiment restaurer ce projet ?" />`;
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
                dataType: "json",
                success: function(res) {
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

        function updateNbPlace(idProjet) {

            $.ajax({
                type: "PATCH",
                url: "/cfp/projets/" + idProjet + "/updateNbPlace",
                data: {
                    _token: '{!! csrf_token() !!}',
                    nbPlace: $(`#nbPlace_${idProjet}`).val()
                },
                dataType: "json",
                success: function(res) {
                    if (res.success) {
                        console.log(res.success);
                    } else {
                        console.log(res.error);
                    }
                }
            });
        }

        function repportProject(idProjet) {
            let dateDebut = $('.dateDebutProjetDetail_' + idProjet).val();
            let dateFin = $('.dateFinProjetDetail_' + idProjet).val();

            $.ajax({
                type: "patch",
                url: "/cfp/projets/" + idProjet + "/repport",
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


        $('input[role="tab"]').click(function() {
            switch ($(this).attr('data-tab')) {
                case 'projets':
                    $('.tab-content-project').hide();
                    $('div[data-content="projets"]').show();
                    $('span[data-head="filter"]').show();
                    break;

                case 'archives':
                    $('.tab-content-project').hide();
                    $('div[data-content="archives"]').show();
                    $('span[data-head="filter"]').hide();
                    break;

                case 'corbeilles':
                    $('.tab-content-project').hide();
                    $('div[data-content="corbeilles"]').show();
                    $('span[data-head="filter"]').hide();
                    break;

                default:
                    break;
            }

        });

        function getTotalSeance(idProjet) {
            $.ajax({
                type: "get",
                url: "/cfp/seances/" + idProjet + "/getSeanceAndTotalTime",
                dataType: "json",
                success: function(res) {
                    $('#head_session').text(
                        `Vous avez ${res.nbSeance} sessions d'une durée total de ${res.totalSession.sumHourSession}`
                    );
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        }


        function editDrawer(drawer, idProjet, idEtp = null, idCfp_inter = null) {

            switch (drawer) {
                case 'apprenants':
                    var container = $('#listApprDrawer');
                    container.empty();

                    var content = `
                            <div class="grid grid-cols-2 gap-2">
                                <div class="grid w-full col-span-1 h-max">
                                    <table class="table w-full caption-top">
                                        <caption>Liste de tous les apprenants</caption>
                                        <tbody>
                                            <tr class="w-full">
                                                <td class="w-full" colspan="2">
                                                    <span class="flex flex-col gap-1">
                                                        <span class="inline-flex items-center justify-between gap-2">
                                                            <input name="idProjet_drawer" type="hidden" value="${idProjet}">
                                                            <input id="main_search_appr_projet" placeholder="Chercher un apprenant"
                                                            onkeyup="mainSearch('main_search_appr_projet', 'all_apprenant')"
                                                            class="w-full bg-white input input-bordered" />
                                                            <button onclick="newAppr()" class="btn btn-square btn-outline opacity-70">
                                                                <i class="fa-solid fa-plus"></i>
                                                            </button>
                                                        </span>
                                                        <span id="select_appr_project"></span>    
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tbody id="all_apprenant">
                                        </tbody>
                                    </table>
                                </div>
                                <div class="grid w-full col-span-1 h-max">
                                    <table class="table caption-top">
                                        <caption>Liste des apprenants selectionnés</caption>
                                        <tbody id="all_apprenant_selected">
                                        </tbody>
                                    </table>
                                </div>
                            </div>`;


                    container.append(content);

                    container.ready(function() {
                        if (idCfp_inter == null) {
                            var select_appr_project = $('#select_appr_project');
                            select_appr_project.html('');

                            select_appr_project.append(`<div class="inline-flex items-center w-full gap-2">
                                        <label class="text-gray-600">Entreprise</label>
                                        <select name="" id="etp_list"
                                        class="mt-2 border-[1px] border-gray-200 rounded-md p-2 outline-none w-full bg-white">
                                        </select>
                                    </div>`);

                            select_appr_project.ready(function() {
                                getApprenantProjets(idEtp, idProjet);
                                getApprenantAdded(idProjet);
                            });
                        } else {
                            var select_appr_project = $('#select_appr_project');
                            select_appr_project.html('');

                            select_appr_project.append(`<div class="inline-flex items-center w-full gap-2">
                                        <label class="text-gray-600">Entreprise</label>
                                        <select name="" id="etp_list_inter"
                                        class="mt-2 border-[1px] border-gray-200 rounded-md p-2 outline-none w-full bg-white">
                                        </select>
                                    </div>`);

                            getApprenantProjetInter(idProjet);

                            getApprenantAddedInter(idProjet);
                        }
                    });
                    break;

                case 'formateurs':
                    var container = $('#listFormateurDrawer');
                    container.empty();

                    var content = `
                            <div class="grid grid-cols-2 gap-2">
                                <div class="grid col-span-1">
                                    <table class="table caption-top">
                                        <caption>Liste de tous les formateurs</caption>
                                        <tbody>
                                            <tr>
                                                <td colspan="2">
                                                    <span class="flex flex-col gap-1">
                                                        <input id="main_search_form_projet" placeholder="Chercher un formateur"
                                                        onkeyup="mainSearch('main_search_form_projet', 'all_form_drawer')"
                                                        class="w-full bg-white input input-bordered" />
                                                        <span id="select_appr_project"></span>    
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tbody id="all_form_drawer">
                                        </tbody>
                                    </table>
                                </div>
                                <div class="grid col-span-1 h-max">
                                    <table class="table caption-top">
                                        <caption>Liste des formateurs selectionnés</caption>
                                        <tbody id="form_drawer_added">
                                        </tbody>
                                    </table>
                                </div>
                            </div>`;


                    container.append(content);

                    getAllForms(idProjet);
                    getFormAdded(idProjet);
                    break;


                case 'sessions':
                    var container = $('#sessions_edit');
                    container.empty();
                    getTotalSeance(idProjet);
                    var content = `
                <div class="flex flex-col w-full gap-2">
                    <div class="inline-flex items-center justify-between w-full px-4 py-2 bg-gray-100">
                        <p class="text-lg font-medium text-gray-500" id="head_session">Ajouter des sessions</p>
                        <a data-bs-toggle="offcanvas" href="#offcanvasSession"
                            class="flex items-center justify-center w-10 h-10 duration-200 rounded-md cursor-pointer hover:bg-gray-200">
                            <i class="text-gray-500 fa-solid fa-xmark"></i>
                        </a>
                    </div>
                    <div class="w-full p-3 flex flex-col overflow-y-auto gap-2 h-[100vh] pb-6">
                        <div class="inline-flex min-w-[900px] overflow-x-scroll items-center gap-2 justify-between w-full">
                            <div class="inline-flex items-center gap-2">
                            <input type="hidden" id="project_id_hidden" value="${idProjet}">
                            <div class="flex items-center justify-center px-3 py-2 bg-gray-200 rounded-md cursor-pointer w-max group/nav">
                                <a id="dp_today" onclick="  ">
                                Aujourd'hui
                                </a>
                            </div>
                            <div class="flex items-center justify-center w-8 h-8 bg-gray-200 rounded-full cursor-pointer group/nav">
                                <a id="dp_yesterday" onclick="">
                                <i class="fa-solid fa-chevron-left"></i>
                                </a>
                            </div>
                            <span class="flex items-center justify-center text-xl font-semibold text-gray-500">   
                                <select id="monthSelectorWeek" class="px-3 py-2 text-base border border-gray-500 rounded-md dropdown-content menu">
                                        <option value="1">
                                            Janvier
                                        </option>
                                        <option value="2">
                                            Février
                                        </option>
                                        <option value="3">
                                            Mars
                                        </option>
                                        <option value="4">
                                            Avril
                                        </option>
                                        <option value="5">
                                            Mai
                                        </option>
                                        <option value="6">
                                            Juin
                                        </option>
                                        <option value="7">
                                            Juillet
                                        </option>
                                        <option value="8">
                                            Août
                                        </option>
                                        <option value="9">
                                            Septembre
                                        </option>
                                        <option value="10">
                                            Octobre
                                        </option>
                                        <option value="11">
                                            Novembre
                                        </option>
                                        <option value="12">
                                            Décembre
                                        </option>                                         
                                </select>
                            </span>
                            <div class="flex items-center justify-center w-8 h-8 bg-gray-200 rounded-full cursor-pointer group/nav">
                                <a id="dp_tomorrow" onclick="">
                                <i class="fa-solid fa-chevron-right"></i>
                                </a>
                            </div>
 
                            <div class="flex items-center justify-center w-8 h-8 ml-32 space-x-4">                    
                                <button class="btn btn-primary" id="authorize_button" onclick="handleAuthClick()"
                                ${localStorage.getItem('ACCESS_TOKEN')? 'disabled':'enabled'}
                                > Sync with GOOGLE</button>
                                <button class="btn btn-danger" id="signout_button" onclick="handleSignoutClick()">Sign Out</button>
                            </div>

                            </div>
                            <div class="inline-flex items-center gap-2">
                                <div class="inline-flex items-center justify-start w-full gap-2">
                                    <button class="btn btn-primary hover:!text-white" data-bs-toggle="offcanvas" href="#offcanvasSession" onclick="getSeanceCount(${idProjet})">Sauvegarder les modifications</button>
                                
                                </div>
                            </div>
                        </div>

                        <div class="w-full relative min-w-[900px] overflow-x-scroll">
                            <div class="w-14 h-8 bg-gray-100 absolute top-[1px] left-[1px] z-10"></div>
                            <div id="dp_session">
                            </div>
                        </div>
                    </div>
                </div>    `;

                    container.append(content);

                    container.ready(function() {
                        openSession("dp_session");
                        var head = $('#head_session');
                    });
                    break;

                case 'client':
                    var container = $('#client_edit');
                    var btn_client_edit = $('#btn_client_edit');

                    btn_client_edit.hide();
                    container.empty();

                    var content = `
                    <div class="flex h-full p-4 mt-12">
                        <div class="flex flex-col h-full items-start mr-4 w-1/2 border-r-[1px] border-gray-200">
                            <div class="flex flex-row gap-2 px-3 py-1 bg-gray-50 ">
                                <div class="w-1 h-6 bg-red-400"></div>
                                <label class="text-xl font-normal text-gray-500">Liste de tous les entreprises
                                    clients</label>
                            </div>
                            <div class="w-full h-full mt-2 overflow-y-auto bg-gray-50">
                                <span>
                                    <input id="main_search_client_project" placeholder="Chercher un client"
                                        onkeyup="mainSearch('main_search_client_project', 'all_etp_drawer')"
                                        class="w-full bg-white input input-bordered" />
                                </span>
                                <div class="p-4 mt-2 overflow-x-auto">
                                    <table class="table">
                                        <tbody id="all_etp_drawer">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col items-start w-1/2 h-full">
                            <div class="inline-flex items-center gap-2 w-max">
                                <div class="flex flex-row gap-2 px-3 py-1 bg-gray-50 ">
                                    <div class="w-1 h-6 bg-green-400"></div>
                                    <label class="text-xl font-normal text-gray-500">Le client sélectionné pour ce
                                        projet</label>
                                </div>
                            </div>
                            <div class="w-full p-4 mt-2 overflow-x-auto">
                                <table class="table">
                                    <tbody id="etp_drawer_added">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>`;


                    container.append(content);

                    container.ready(function() {
                        getEtpAssigned(idProjet, idCfp_inter);
                        getAllEtps(idCfp_inter, idProjet);
                        showClient(idProjet, idCfp_inter);
                    });
                    break;

                default:
                    break;
            }

        }

        function getApprenantProjetInter(idProjet, idEtp = null) {
            $.ajax({
                type: "get",
                url: "/cfp/projet/etpInter/getApprenantProjetInter/" + idProjet,
                dataType: "json",
                success: function(res) {
                    var all_apprenant = $('#all_apprenant');
                    all_apprenant.html('');

                    var etp_list_inter = $('#etp_list_inter');
                    etp_list_inter.html('');

                    let etp_option = null;
                    var selected = "";

                    if (res.apprs.length <= 0) {
                        all_apprenant.append(`<x-no-data texte="Aucun résultat"></x-no-data>`);
                    } else {

                        // Remplir la liste des apprenants initiale
                        fillApprenantList(res.apprs, idProjet);
                        if (res.etps.length > 0) {
                            $.each(res.etps, function(i, v) {
                                if (v.idEtp == idEtp) {
                                    selected = "selected";
                                }

                                etp_list_inter.append(
                                    `<option id="${i}" value="${v.idEtp}" ${selected}>${v.etp_name}</option>`
                                );

                                if (i == 0) {
                                    if (idEtp != null) {
                                        filterApprenantList(idEtp);
                                    } else {
                                        filterApprenantList($(`#${i}`).val());
                                    }
                                }
                            });
                        } else {
                            etp_list_inter.hide();
                        }

                        // Écouter le changement dans le select
                        $(etp_list_inter).change(function(e) {
                            e.preventDefault();
                            etp_option = etp_list_inter.val();
                            filterApprenantList(etp_option);
                        });
                    }

                }
            });
        }

        function fillApprenantList(apprs, idProjet) {

            var all_apprenant = $('#all_apprenant');
            all_apprenant.html('');

            $.each(apprs, function(key, val) {
                all_apprenant.append(`
                    <tr class="list list_${val.idEtp}">
                        <td class="capitalize">
                            <span class="inline-flex items-center">
                                <div class="mr-3 avatar">
                                    <div class="w-12 rounded-full">
                                        ${ val.emp_photo ? ` <img src="{{ $endpoint }}/{{ $bucket }}/img/employes/${val.emp_photo}" />` : `<span class="flex items-center justify-center w-full h-full text-xl uppercase text-slate-600 bg-slate-200">${val.emp_initial_name ?? 'I'}</span>`
                                        }
                                    </div>
                                </div>
                                <span class="flex flex-col">
                                    <span class="mr-1 uppercase">${val.emp_name ?? '' }</span> ${val.emp_firstname ?? '' }
                                </span>
                            </span>
                        </td>
                        <td class='text-right'>
                            <button onclick="manageApprenantInter('post', ${idProjet}, ${val.idEmploye}, ${val.idEtp})" class="rounded-full btn btn-success btn-outline"><i class="text-xl fa-solid fa-plus"></i></button>    
                        </td>
                    </tr>`);
            });
        }

        // Fonction pour filtrer la liste des apprenants par entreprise sélectionnée
        function filterApprenantList(etp_option) {
            $('.list').each(function() {
                if ($(this).hasClass('list_' + etp_option)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }


        // NEW FUNCTIONS
        function getApprenantProjets(idEtp, idProjet) {
            $.ajax({
                type: "get",
                url: "/cfp/projet/apprenants/getApprenantProjets/" + idEtp,
                dataType: "json",
                success: function(res) {
                    var all_apprenant = $('#all_apprenant');
                    all_apprenant.html('');


                    var etp_list = $('#etp_list');
                    etp_list.html('');


                    if (res.etps.length > 0) {
                        $.each(res.etps, function(i, v) {
                            etp_list.append(`<option id="` + i + `" value="` + v.idEtp +
                                `">` + v
                                .etp_name +
                                `</option>`);

                            if (i == 0) {
                                filterApprenantList($('#' + i).val());
                            }
                        });
                    } else {
                        etp_list.hide();
                    }

                    // Écouter le changement dans le select
                    $(etp_list).change(function(e) {
                        e.preventDefault();
                        etp_option = etp_list.val();
                        filterApprenantList(etp_option);
                    });

                    let etp_option = null;
                    if (res.apprs && Array.isArray(res.apprs)) {
                        if (res.apprs.length <= 0) {
                            all_apprenant.append(`<x-no-data texte="Aucun résultat"></x-no-data>`);
                        } else {

                            // Remplir la liste des apprenants initiale
                            fillApprenantListIntra(res.apprs, idProjet);
                        }

                    }
                }
            });
        }

        function fillApprenantListIntra(apprs, idProjet) {

            var all_apprenant = $('#all_apprenant');
            all_apprenant.html('');

            $.each(apprs, function(key, val) {
                let firstName = val.emp_firstname != null ? val.emp_firstname : '';

                all_apprenant.append(`
                    <tr class="list list_${val.idEtp}">
                        <td class="capitalize">
                            <span class="inline-flex items-center">
                                <div class="mr-3 avatar">
                                    <div class="w-12 rounded-full">
                                        ${ val.emp_photo ? ` <img src="{{ $endpoint }}/{{ $bucket }}/img/employes/${val.emp_photo}" />` : `<span class="flex items-center justify-center w-full h-full text-xl uppercase text-slate-600 bg-slate-200">${val.emp_name[0]}</span>`
                                        }
                                    </div>
                                </div>
                                <span class="flex flex-col">
                                    <span class="mr-1 uppercase">${val.emp_name}</span> ${val.emp_firstname ?? '' }
                                </span>
                            </span>
                        </td>
                        <td class='text-right'>
                            <button onclick="manageApprenant('post', ${idProjet}, ${val.idEmploye})" class="rounded-full btn btn-success btn-outline"><i class="text-xl fa-solid fa-plus"></i></button>    
                        </td>
                    </tr>`);
            });
        }

        function manageApprenantInter(type, idProjet, idApprenant, idEtp) {
            $.ajax({
                type: type,
                url: "/cfp/projet/etpInter/" + idProjet + "/" + idApprenant + "/" +
                    idEtp,
                data: {
                    _token: '{!! csrf_token() !!}'
                },
                dataType: "json",
                success: function(res) {
                    if (res.success) {
                        toastr.success(res.success, 'Succès', {
                            timeOut: 1500
                        });
                        getApprenantAddedInter(idProjet);
                        showApprDrawer(idProjet);
                    } else if (res.error) {
                        toastr.error(res.error, 'Erreur', {
                            timeOut: 1500
                        });
                    }
                }
            });
        }


        function manageApprenant(type, idProjet, idApprenant) {
            $.ajax({
                type: type,
                url: "/cfp/projet/apprenants/" + idProjet + "/" + idApprenant,
                data: {
                    _token: '{!! csrf_token() !!}'
                },
                dataType: "json",
                success: function(res) {
                    if (res.success) {
                        toastr.success(res.success, 'Succès', {
                            timeOut: 1500
                        });
                        getApprenantAdded(idProjet);
                        showApprDrawer(idProjet);
                    } else if (res.error) {
                        toastr.error(res.error, 'Erreur', {
                            timeOut: 1500
                        });
                    }
                }
            });
        }

        function showApprDrawer(id) {
            let p_apprs = $(`.apprs_${id}`);
            p_apprs.empty();

            $.ajax({
                type: "GET",
                url: `/cfp/projets/${id}/getApprListProjet`,
                dataType: "json",
                success: function(res) {
                    if (res.length > 0 && res.length < 4) {
                        $.each(res, function(i_ap, v_ap) {
                            if (v_ap.emp_photo != null) {
                                p_apprs.append(`
                                    <div class="avatar">
                                        <div class="w-8">
                                            <img src="${endpoint}/${bucket}/img/employes/${v_ap.emp_photo}"/>
                                        </div>
                                    </div>`);
                            } else {
                                p_apprs.append(`
                                    <div class="cursor-pointer avatar placeholder">
                                        <div class="w-8 rounded-full bg-slate-200 text-slate-600">
                                            <span class="text-xl">${v_ap.emp_initial_name}</span>
                                        </div>
                                    </div>
                                `);
                            }
                        });
                    } else if (res.length >= 4) {
                        const totalApprentices = res.length;
                        const remainingApprentices = totalApprentices - 3;
                        const baseNumber = Math.floor(totalApprentices / 10);

                        for (let i = 0; i < 3; i++) {
                            if (res[i].emp_photo != null) {
                                p_apprs.append(`
                                <div class="avatar">
                                    <div class="w-8">
                                        <img src="${endpoint}/${bucket}/img/employes/${res[i].emp_photo}"/>
                                    </div>
                                </div>`);
                            } else {
                                p_apprs.append(`
                                <div class="cursor-pointer avatar placeholder">
                                    <div class="w-8 rounded-full bg-slate-200 text-slate-600">
                                        <span class="text-xl">${res[i].emp_initial_name}</span>
                                    </div>
                                </div>
                            `);
                            }
                        }

                        p_apprs.append(`
                            <div class="cursor-pointer avatar placeholder">
                                <div class="bg-neutral !opacity-100 text-white w-8 rounded-full">
                                <span class="text-md">+${remainingApprentices}</span>
                            </div>
                        `);
                    } else {
                        for (let i = 0; i < 4; i++) {

                            p_apprs.append(`
                  <div class="avatar">
                      <div class="flex items-center justify-center inline-block w-8 h-8 font-bold uppercase rounded-full ring-2 ring-white text-slate-600 bg-slate-200"></div>
                  </div>
              `);
                        }
                    }
                }
            });
        }

        // Fonction pour filtrer la liste des apprenants par entreprise sélectionnée
        function filterApprenantList(etp_option) {
            $('.list').each(function() {
                if ($(this).hasClass('list_' + etp_option)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        //Apprenant AJAX
        function getApprenantAdded(idProjet) {
            var appr_table_div = $('#appr_table');

            $.ajax({
                type: "get",
                url: "/cfp/projet/apprenants/getApprenantAdded/" + idProjet,
                dataType: "json",
                beforeSend: function() {
                    appr_table_div.append(`<span class="flex flex-col items-center w-full gap-2 p-4">
                        <div class="loader"></div>
                        <p class="text-lg text-slate-700">Veuillez patientez, nous chargeons vos données !</p>
                        </span>`)
                },
                success: function(res) {
                    appr_drawer_intra(res, idProjet);
                }
            });
        }

        function appr_drawer_intra(data, idProjet) {
            var all_apprenant_selected = $('#all_apprenant_selected')
            all_apprenant_selected.html('');

            if (data.getEtps.length <= 0) {
                all_apprenant_selected.append(`<x-no-data texte="Aucun apprenant"/>`)
            } else {
                $.each(data.getEtps, function(i, etp) {
                    all_apprenant_selected.append(`
                    <div class="etp_apprenant">
                        <label class="mb-2 text-xl text-slate-700">${etp.etp_name}</label>
                        <table class="table">
                            <tbody id="drawer_participant_${etp.idEtp}"></tbody>
                        </table>
                    </div>`)

                    all_apprenant_selected.ready(function() {
                        var data_participant = $(`#drawer_participant_${etp.idEtp}`);

                        $.each(data.apprs, function(i, appr) {
                            if (etp.etp_name == appr.etp_name) {
                                data_participant.append(
                                    `
                                <tr>
                                    <td class="capitalize">
                                        <span class="inline-flex items-center">
                                            <div class="mr-3 avatar">
                                                <div class="w-12 rounded-full">
                                                    ${ appr.emp_photo ? ` <img src="{{ $endpoint }}/{{ $bucket }}/img/employes/${appr.emp_photo}" />` : `<span class="flex items-center justify-center w-full h-full text-xl uppercase text-slate-600 bg-slate-200">${appr.emp_initial_name ?? 'I'}</span>`
                                                    }
                                                </div>
                                            </div>
                                            <span class="flex flex-col">
                                                <span class="mr-1 uppercase">${appr.emp_name}</span> ${appr.emp_firstname ?? '' }
                                            </span>
                                        </span>
                                    </td>
                                    <td class='text-right'>
                                        <button onclick="manageApprenant('delete', ${idProjet}, ${appr.idEmploye})" class="rounded-full btn btn-error btn-outline"><i class="text-xl fa-solid fa-minus"></i></button>    
                                    </td>
                                </tr>`
                                );
                            }
                        });

                    });
                });
            }
        }

        // Apprenant Inter AJAX
        function getApprenantAddedInter(idProjet) {
            var appr_table_div = $('#appr_table');
            $.ajax({
                type: "get",
                url: "/cfp/projet/etpInter/getApprenantAddedInter/" + idProjet,
                dataType: "json",
                beforeSend: function() {
                    appr_table_div.append(`<span class="flex flex-col items-center w-full gap-2 p-4">
                        <div class="loader"></div>
                        <p class="text-lg text-slate-700">Veuillez patientez, nous chargeons vos données !</p>
                        </span>`)
                },
                complete: function() {
                    $('.initialLoading').remove();
                },
                success: function(res) {
                    appr_drawer(res, idProjet);
                }
            });
        }

        function appr_drawer(data, idProjet) {
            var all_apprenant_selected = $('#all_apprenant_selected')
            all_apprenant_selected.html('');

            if (data.getEtps.length <= 0) {
                all_apprenant_selected.append(`<x-no-data texte="Aucun apprenant"/>`)
            } else {
                $.each(data.getEtps, function(i, etp) {
                    all_apprenant_selected.append(`
                    <div class="etp_apprenant">
                        <label class="mb-2 text-xl text-slate-700">${etp.etp_name}</label>
                        <table class="table">
                            <tbody id="drawer_participant_${etp.idEtp}"></tbody>
                        </table>
                    </div>`)

                    all_apprenant_selected.ready(function() {
                        var data_participant = $(`#drawer_participant_${etp.idEtp}`);

                        $.each(data.apprs, function(i, appr) {
                            if (etp.etp_name == appr.etp_name) {
                                data_participant.append(
                                    `
                                <tr>
                                    <td class="capitalize">
                                        <span class="inline-flex items-center">
                                            <div class="mr-3 avatar">
                                                <div class="w-12 rounded-full">
                                                    ${ appr.emp_photo ? ` <img src="{{ $endpoint }}/{{ $bucket }}/img/employes/${appr.emp_photo}" />` : `<span class="flex items-center justify-center w-full h-full text-xl uppercase text-slate-600 bg-slate-200">${appr.emp_initial_name ?? 'I'}</span>`
                                                    }
                                                </div>
                                            </div>
                                            <span class="flex flex-col">
                                                <span class="mr-1 uppercase">${appr.emp_name}</span> ${appr.emp_firstname ?? '' }
                                            </span>
                                        </span>
                                    </td>
                                    <td class='text-right'>
                                        <button onclick="manageApprenantInter('delete', ${idProjet}, ${appr.idEmploye})" class="rounded-full btn btn-error btn-outline"><i class="text-xl fa-solid fa-minus"></i></button>    
                                    </td>
                                </tr>`
                                );
                            }
                        });

                    });
                });
            }
        }


        // All Formateur AJAX
        function getAllForms(idProjet) {
            $.ajax({
                type: "get",
                url: "/cfp/forms/getAllForms",
                dataType: "json",
                success: function(res) {
                    all_form_drawer(res.forms, idProjet)
                }
            });
        }

        function all_form_drawer(data, idProjet) {
            var form_drawer = $('#all_form_drawer');
            form_drawer.html('');

            if (data.length <= 0) {
                form_drawer.append(`<x-no-data texte="Pas de formateur"/>`);
            } else {
                $.each(data, function(i, form) {
                    form_drawer.append(`
                            <tr>
                                <td class="capitalize">
                                    <span class="inline-flex items-center">
                                        <div class="mr-3 avatar">
                                            <div class="w-12 rounded-full">
                                                ${ form.form_photo ? ` <img src="{{ $endpoint }}/{{ $bucket }}/img/formateurs/${form.form_photo}" />` : `<span class="flex items-center justify-center w-full h-full text-xl uppercase text-slate-600 bg-slate-200">${form.form_initial_name ?? 'I'}</span>`
                                                }
                                            </div>
                                        </div>
                                        <span class="flex flex-col">
                                            <span class="mr-1 uppercase">${form.form_name}</span> ${form.form_first_name ?? '' }
                                        </span>
                                    </span>
                                </td>
                                <td class='text-right'>
                                    <button onclick="manageForm('post', ${idProjet}, ${form.idFormateur})" class="rounded-full btn btn-success btn-outline"><i class="text-xl fa-solid fa-plus"></i></button>    
                                </td>
                            </tr>
                `);
                });
            }
        }

        function form_drawer_added(data, idProjet) {
            var form_drawer = $('#form_drawer_added');
            form_drawer.html('');

            if (data.length <= 0) {
                form_drawer.append(`<x-no-data texte="Pas de formateur"/>`);
            } else {
                $.each(data, function(i, form) {
                    form_drawer.append(`
                            <tr>
                                <td class="capitalize">
                                    <span class="inline-flex items-center">
                                        <div class="mr-3 avatar">
                                            <div class="w-12 rounded-full">
                                                ${ form.form_photo ? ` <img src="{{ $endpoint }}/{{ $bucket }}/img/formateurs/${form.form_photo}" />` : `<span class="flex items-center justify-center w-full h-full text-xl uppercase text-slate-600 bg-slate-200">${form.form_initial_name ?? 'I'}</span>`
                                                }
                                            </div>
                                        </div>
                                        <span class="flex flex-col">
                                            <span class="mr-1 uppercase">${form.form_name}</span> ${form.form_firstname ?? '' }
                                        </span>
                                    </span>
                                </td>
                                <td class='text-right'>
                                    <button onclick="manageForm('delete', ${idProjet}, ${form.idFormateur})" class="rounded-full btn btn-error btn-outline"><i class="text-xl fa-solid fa-minus"></i></button>    
                                </td>
                            </tr>
                `);
                });
            }
        }

        function manageForm(type, idProjet, idFormateur) {
            $.ajax({
                type: type,
                url: "/cfp/projets/" + idProjet + "/" + idFormateur + "/form/assign",
                data: {
                    _token: '{!! csrf_token() !!}'
                },
                dataType: "json",
                success: function(res) {
                    if (res.success) {
                        toastr.success(res.success, 'Succès', {
                            timeOut: 1500
                        });

                        /********************Ajout et suppression d'un Formateur coté FRONT **********************/
                        let idCustomer = sessionStorage.getItem('ID_CUSTOMER');
                        let resources = JSON.parse(sessionStorage.getItem('ACCESS_EVENTS_RESOURCE_' +
                            idCustomer)) || [];
                        let prenom_form = $('#prenom_form_' + idFormateur).val();

                        resources.map(item => {
                            if (item.idProjet === idProjet) {
                                let tabprenom = [...item.prenom_form];
                                const existe = tabprenom.some(obj => obj.idFormateur == idFormateur);
                                if (!existe) {
                                    tabprenom.push({
                                        idFormateur: idFormateur,
                                        prenom: prenom_form
                                    });
                                    item.prenom_form = tabprenom;
                                } else {
                                    tabprenom = tabprenom.filter(obj => obj.idFormateur !==
                                        idFormateur);
                                    item.prenom_form = tabprenom;
                                }
                            }
                            return item;
                        });
                        sessionStorage.setItem('ACCESS_EVENTS_RESOURCE_' + idCustomer, JSON.stringify(
                            resources));

                        let details = JSON.parse(sessionStorage.getItem('ACCESS_EVENTS_DETAILS_' +
                            idCustomer)) || [];
                        details.map(item => {
                            if (item.idProjet === idProjet) {
                                let tabprenom = [...item.prenom_form];

                                const existe = tabprenom.some(obj => obj.idFormateur == idFormateur);
                                if (!existe) {
                                    tabprenom.push({
                                        idFormateur: idFormateur,
                                        prenom: prenom_form
                                    });
                                    item.prenom_form = tabprenom;
                                } else {
                                    tabprenom = tabprenom.filter(obj => obj.idFormateur !==
                                        idFormateur);
                                    item.prenom_form = tabprenom;
                                }
                            }
                            return item;
                        });
                        sessionStorage.setItem('ACCESS_EVENTS_DETAILS_' + idCustomer, JSON.stringify(
                            details));

                        /*****************************************************************************************/


                        getFormAdded(idProjet);
                        showFormDrawer(idProjet);
                    } else if (res.error) {
                        toastr.error(res.error, 'Erreur', {
                            timeOut: 1500
                        });
                    }
                }
            });
        }

        function showFormDrawer(id) {
            let p_form = $(`.form_${id}`);
            p_form.empty();

            $.ajax({
                type: "GET",
                url: `/cfp/projets/${id}/getFormProject`,
                dataType: "json",
                success: function(res) {
                    if (res.length > 0) {
                        $.each(res, function(i_f, v_f) {
                            if (v_f.form_photo != null) {
                                p_form.append(`
                                                <img onclick="viewMiniCV(${v_f.idFormateur}, ${id})" class="inline-block w-8 h-8 rounded-full cursor-pointer ring-2 ring-white"
                                                src="${endpoint}/${bucket}/img/formateurs/${v_f.form_photo}"
                                                alt="" />
                                                    `);
                            } else {
                                p_form.append(`
                                            <div onclick="viewMiniCV(${v_f.idFormateur}, ${id})" class="flex items-center justify-center inline-block w-8 h-8 font-bold uppercase rounded-full cursor-pointer ring-2 ring-white text-slate-600 bg-slate-100">${v_f.form_initial_name[0]}</div>
                                            `);
                            }
                        });
                    } else {
                        p_form.append(`
                                    <div class="flex items-center justify-center inline-block w-8 h-8 font-bold uppercase rounded-full ring-2 ring-white text-slate-600 bg-slate-200"></div>
                                    `);
                    }
                }
            });
        }

        // Formateur AJAX
        function getFormAdded(idProjet) {
            $.ajax({
                type: "get",
                url: "/cfp/projets/" + idProjet + "/getFormAdded",
                dataType: "json",
                success: function(res) {
                    form_drawer_added(res.forms, idProjet);
                }
            });
        }

        function form_drawer_added(data, idProjet) {
            var form_drawer = $('#form_drawer_added');
            form_drawer.html('');

            if (data.length <= 0) {
                form_drawer.append(`<x-no-data texte="Pas de formateur"/>`);
            } else {
                $.each(data, function(i, form) {
                    form_drawer.append(`
                            <tr>
                                <td class="capitalize">
                                    <span class="inline-flex items-center">
                                        <div class="mr-3 avatar">
                                            <div class="w-12 rounded-full">
                                                ${ form.form_photo ? ` <img src="{{ $endpoint }}/{{ $bucket }}/img/formateurs/${form.form_photo}" />` : `<span class="flex items-center justify-center w-full h-full text-xl uppercase text-slate-600 bg-slate-200">${form.form_initial_name ?? 'I'}</span>`
                                                }
                                            </div>
                                        </div>
                                        <span class="flex flex-col">
                                            <span class="mr-1 uppercase">${form.form_name}</span> ${form.form_firstname ?? '' }
                                        </span>
                                    </span>
                                </td>
                                <td class='text-right'>
                                    <button onclick="manageForm('delete', ${idProjet}, ${form.idFormateur})" class="rounded-full btn btn-error btn-outline"><i class="text-xl fa-solid fa-minus"></i></button>    
                                </td>
                            </tr>
                `);
                });
            }
        }

        function newAppr() {
            // Sélectionner le tbody où ajouter la nouvelle ligne
            var tbody = $('#all_apprenant');

            // Créer une nouvelle ligne <tr> avec deux <input> et un bouton d'enregistrement
            var newRow = `
            <tr>
                <td>
                    <span class="flex flex-col gap-1">
                        <input type="text" name="nom" placeholder="Nom" class="w-full input input-sm input-bordered" />
                        <input type="text" name="prenom" placeholder="Prénom" class="w-full input input-sm input-bordered" />    
                    </span>
                </td>
                <td>
                    <button onclick="saveRow(this)" class="btn btn-success btn-square btn-outline btn-sm"><i class="fa-solid fa-plus"></i></button>
                    <button onclick="deleteRow(this)" class="btn btn-error btn-square btn-outline btn-sm"><i class="fa-solid fa-xmark"></i></button>
                </td>
            </tr>
        `;

            // Ajouter la ligne au tableau
            tbody.prepend(newRow);
        }

        function deleteRow(button) {
            // Trouver la ligne <tr> contenant le bouton et la supprimer
            $(button).closest('tr').remove();
        }

        function saveRow(button) {
            // Trouver la ligne <tr> contenant le bouton
            var row = $(button).closest('tr');

            // Récupérer les valeurs des <input>
            var nom = row.find('input[name="nom"]').val();
            var prenom = row.find('input[name="prenom"]').val();
            var idProjet = $('input[name="idProjet_drawer"]').val();

            $.ajax({
                type: "POST",
                url: "/cfp/apprenants",
                data: {
                    emp_name: nom,
                    emp_firstname: prenom,
                    idEntreprise: $('#etp_list').val() ?? $('#etp_list_inter').val(),
                    idProjet: idProjet,
                },
                success: function(res) {
                    if (res.idCfp_inter != null) {
                        getApprenantProjetInter(idProjet, $('#etp_list_inter').val());
                    } else {
                        getApprenantProjets($('#etp_list').val(), idProjet);
                    }
                }
            });
        }

        function getSeanceCount(id) {
            $.ajax({
                type: "GET",
                url: `/cfp/projets/${id}/getSessionProject`,
                dataType: "json",
                success: function(res) {
                    var session = $(`#session_${id}`);
                    session.empty();

                    session.text(res);
                }
            });
        }

        function drawerClient(idProjet, idCfp_inter = null) {
            var __offcanvas = "offcanvasClient";
            let __global_drawer = $('#drawer_content_detail');
            __global_drawer.html('');

            __global_drawer.append(`<x-drawer-client></x-drawer-client>`);

            if (idCfp_inter == null) {
                getEtpAssigned(idProjet, idCfp_inter);
                getAllEtps(idCfp_inter, idProjet);
            } else {
                getEtpAdded(idProjet, idCfp_inter);
                getAllEtps(idCfp_inter, idProjet);
            }

            let offcanvasId = $('#' + __offcanvas)
            var bsOffcanvas = new bootstrap.Offcanvas(offcanvasId);
            bsOffcanvas.show();
        }

        function getEtpAssigned(idProjet, idCfp_inter) {
            $.ajax({
                type: "get",
                url: "/cfp/projets/" + idProjet + "/etp/assign",
                dataType: "json",
                success: function(res) {
                    etp_drawer_intra(res.etp);
                }
            });
        }

        function etp_drawer_intra(data) {

            var etp_drawer_added = $('#etp_drawer_added');
            etp_drawer_added.html('');

            if (data == null) {
                etp_drawer_added.append(`<x-no-data texte="Pas d'entreprise"/>`);
            } else {
                etp_drawer_added.append(`<tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar">
                                        <div class="w-24 h-16 rounded-xl">
                                            ${data.etp_logo ? `<img src="{{ $endpoint }}/{{ $bucket }}/img/entreprises/${data.etp_logo}" class="object-cover w-20 h-auto" alt="${data.etp_name ?? "Entreprise"}" />` : 
                                            `<span class="flex items-center justify-center w-full h-full text-xl uppercase text-slate-600 bg-slate-200">${data.etp_initial_name}</span>`}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-bold uppercase">${data.etp_name ?? ''}</div>
                                        <div class="text-sm text-slate-500">${data.etp_email ?? ''}</div>
                                    </div>
                                </div>
                        </tr>
                        `);
            }
        }

        function getAllEtps(idCfp_inter, idProjet) {
            $.ajax({
                type: "get",
                url: "/cfp/invites/etp/getAllEtps",
                dataType: "json",
                success: function(res) {
                    var all_etp_drawer = $('#all_etp_drawer');
                    all_etp_drawer.html('');

                    if (res.etps.length <= 0) {
                        all_etp_drawer.append(
                            `<x-no-data texte="Pas d'apprenant pour cette entreprise"></x-no-data>`);
                    } else {
                        $.each(res.etps, function(key, etp) {
                            if (idCfp_inter == null) {
                                all_etp_drawer.append(`<tr>
                                        <td>
                                            <div class="flex items-center gap-3">
                                                <div class="avatar">
                                                    <div class="w-24 h-16 rounded-xl">
                                                        ${etp.etp_logo ? `<img src="{{ $endpoint }}/{{ $bucket }}/img/entreprises/${etp.etp_logo}" class="object-cover w-20 h-auto" alt="${etp.etp_name ?? "Entreprise"}" />` : 
                                                        `<span class="flex items-center justify-center w-full h-full text-xl uppercase text-slate-600 bg-slate-200">${etp.etp_initial_name}</span>`}
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="font-bold uppercase">${etp.etp_name ?? ''}</div>
                                                    <div class="text-sm text-slate-500">${etp.etp_email ?? ''}</div>
                                                </div>
                                            </div>
                                        </td>
                                            <td class="text-right">
                                            <button onclick="etpAssign(${idProjet}, ${etp.idEtp}, ${idCfp_inter})" class="btn btn-outline btn-success"><i class="fa-solid fa-plus"></i></button>
                                        </td>
                                    </tr>
                                    `);
                            } else if (idCfp_inter != null) {
                                all_etp_drawer.append(`<tr>
                                        <td>
                                            <div class="flex items-center gap-3">
                                                <div class="avatar">
                                                    <div class="w-24 h-16 rounded-xl">
                                                        ${etp.etp_logo ? `<img src="{{ $endpoint }}/{{ $bucket }}/img/entreprises/${etp.etp_logo}" class="object-cover w-20 h-auto" alt="${etp.etp_name ?? "Entreprise"}" />` : 
                                                        `<span class="flex items-center justify-center w-full h-full text-xl uppercase text-slate-600 bg-slate-200">${etp.etp_initial_name}</span>`}
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="font-bold uppercase">${etp.etp_name ?? ''}</div>
                                                    <div class="text-sm opacity-50">${etp.mail ?? ''}</div>
                                                </div>
                                            </div>
                                        </td>
                                            <td class="text-right">
                                            <button onclick="etpAssignInter(${idProjet}, ${etp.idEtp}, ${idCfp_inter})" class="btn btn-outline btn-success"><i class="fa-solid fa-plus"></i></button>
                                        </td>
                                    </tr>
                                    `);
                            }
                        });
                    }
                }
            });
        }

        function etpAssignInter(idProjet, idEtp, idCfp_inter) {
            $.ajax({
                type: "post",
                url: "/cfp/projets/" + idProjet + '/' + idEtp,
                data: {
                    idProjet: idProjet,
                    idEtp: idEtp,
                },
                dataType: "json",
                success: function(res) {
                    if (res.success) {
                        toastr.success("Succès", res.success, {
                            timeOut: 1500
                        });
                        getEtpAdded(idProjet);
                        getAllEtps(idCfp_inter, idProjet);
                        showClient(idProjet, idCfp_inter);
                    } else {
                        toastr.error("Erreur", res.error, {
                            timeOut: 1500
                        });
                    }
                }
            });
        }

        function etpAssign(idProjet, idEtp, idCfp_inter) {
            $.ajax({
                type: "patch",
                url: "/cfp/projets/" + idProjet + "/" + idEtp + "/etp/assign",
                data: {
                    _token: '{!! csrf_token() !!}'
                },
                dataType: "json",
                success: function(res) {
                    if (res.success) {
                        toastr.success(res.success, 'Succès', {
                            timeOut: 1500
                        });
                        getEtpAssigned(idProjet, idCfp_inter);
                        getAllEtps(idCfp_inter, idProjet);
                        showClient(idProjet, idCfp_inter);
                    }
                }
            });
        }

        function getEtpAdded(idProjet, idCfp_inter) {
            $.ajax({
                type: "get",
                url: "/cfp/projet/etpInter/getEtpAdded/" + idProjet,
                dataType: "json",
                success: function(res) {
                    etp_drawer(res.etps, idProjet, idCfp_inter);
                }
            });
        }

        function etp_drawer(data, idProjet, idCfp_inter) {

            var etp_drawer_added = $('#etp_drawer_added');
            etp_drawer_added.html('');

            if (data.length <= 0) {
                etp_drawer_added.append(`<x-no-data texte="Pas d'entreprise"/>`);
            } else {
                $.each(data, function(i, etp) {
                    etp_drawer_added.append(`<tr>
                <td>
                    <div class="flex items-center gap-3">
                        <div class="avatar">
                            <div class="w-24 h-16 rounded-xl">
                                ${etp.etp_logo ? `<img src="{{ $endpoint }}/{{ $bucket }}/img/entreprises/${etp.etp_logo}" class="object-cover w-20 h-auto" alt="${etp.etp_name ?? "Entreprise"}" />` : 
                                `<span class="flex items-center justify-center w-full h-full text-xl uppercase text-slate-600 bg-slate-200">${etp.etp_initial_name}</span>`}
                            </div>
                        </div>
                        <div>
                            <div class="font-bold uppercase">${etp.etp_name ?? ''}</div>
                            <div class="text-sm text-slate-500">${etp.etp_email ?? ''}</div>
                        </div>
                    </div>
                    </td>
                    <td class="text-right">
                        <button onclick="removeEtpInter('delete', ${idProjet}, ${etp.idEtp}, ${idCfp_inter})" class="btn btn-outline btn-error"><i class="fa-solid fa-minus"></i></button>
                    </td>
            </tr>
            `);
                });
            }
        }

        function removeEtpInter(type, idProjet, idEtp, idCfp_inter) {
            $.ajax({
                type: type,
                url: "/cfp/projet/etpInter/" + idProjet + "/" + idEtp,
                data: {
                    _token: '{!! csrf_token() !!}'
                },
                dataType: "json",
                success: function(res) {
                    if (res.success) {
                        toastr.success(res.success, 'Succès', {
                            timeOut: 1500
                        });
                        getEtpAdded(idProjet, idCfp_inter);
                        getAllEtps(idCfp_inter, idProjet);
                        showClient(idProjet, idCfp_inter);
                    } else if (res.error) {
                        toastr.error(res.error, 'Erreur', {
                            timeOut: 1500
                        });
                    }
                }
            });
        }

        function showClient(idProjet, idCfp_inter = null) {
            var p_etp_client = $(`.etp_client_${idProjet}`);
            p_etp_client.empty();

            $.ajax({
                type: "GET",
                url: `/cfp/projets/getEtpClient/${idProjet}/${idCfp_inter}`,
                success: function(res) {
                    if (res.length > 0) {
                        if (idCfp_inter == null || idCfp_inter == 'null') {
                            $.each(res, function(i_etp, v_etp) {
                                if (v_etp.etp_logo != null) {
                                    p_etp_client.append(`
                                                    <img onclick="showCustomer(${v_etp.idEtp}, '/cfp/etp-drawer/', ${idProjet})" class="inline-block w-20 h-10 duration-200 cursor-pointer grayscale hover:grayscale-0 rounded-xl ring-2 ring-white"
                                                        src="${endpoint}/${bucket}/img/entreprises/${v_etp.etp_logo}"
                                                        alt="" />
                                                        `);
                                } else {
                                    p_etp_client.append(`
                                                <div onclick="showCustomer(${v_etp.idEtp}, '/cfp/etp-drawer/', ${idProjet})" class="flex items-center justify-center inline-block w-20 h-10 font-bold uppercase cursor-pointer rounded-xl ring-2 ring-white text-slate-600 bg-slate-100">${v_etp.etp_name}</div>
                                                `);
                                }
                            });
                        } else {
                            $.each(res, function(i_etp, v_etp) {
                                if (v_etp.etp_logo != null) {
                                    p_etp_client.append(`
                                                    <img onclick="drawerClient(${idProjet}, ${idCfp_inter})" class="inline-block w-20 h-10 duration-200 cursor-pointer grayscale hover:grayscale-0 rounded-xl ring-2 ring-white"
                                                        src="${endpoint}/${bucket}/img/entreprises/${v_etp.etp_logo}"
                                                        alt="" />
                                                        `);
                                } else {
                                    p_etp_client.append(`
                                                <div onclick="drawerClient(${idProjet}, ${idCfp_inter})" class="flex items-center justify-center inline-block w-20 h-10 font-bold uppercase cursor-pointer rounded-xl ring-2 ring-white text-slate-600 bg-slate-100">${v_etp.etp_name}</div>
                                                `);
                                }
                            });
                        }
                    } else {
                        p_etp_client.append(`
                                    <div onclick="drawerClient(${idProjet}, ${idCfp_inter})" data-bs-toggle="tooltip" title="Entreprise Client" class="flex items-center justify-center inline-block w-20 h-10 font-bold uppercase rounded-xl ring-2 ring-white text-slate-600 bg-slate-200"></div>
                                    `);
                    }
                }
            });
        }


        // ============= DRAWER PRESENCE =============
        function drawerPresence(idProjet, idCfp_inter = null) {
            let __global_drawer = $('#drawer_content_detail').empty();
            __global_drawer.append(`<x-drawer-presence idProjet="${idProjet}"></x-drawer-presence>`);

            if (idCfp_inter == null) {
                getAllApprPresence(idProjet);
            } else {
                getAllApprPresenceInter(idProjet);
            }

            let offcanvasId = $('#offcanvasPresence')
            var bsOffcanvas = new bootstrap.Offcanvas(offcanvasId);
            bsOffcanvas.show();
        }

        async function getPresenceData(id) {
            try {
                const response = await $.ajax({
                    type: "GET",
                    url: `/cfp/projets/${id}/getDataPresence`,
                    dataType: "json"
                });
                return response;
            } catch (error) {
                console.error("Erreur AJAX :", error);
                return null;
            }
        }

    // Utilisation de la fonction (exemple d'appel)
        async function getAllApprPresence(idProjet) {
        const res = await getPresenceData(idProjet);
        if (!res) return;

        const sessions = res.seances;
        const countDate = res.general_data?.countDate ?? 0;

        console.log("Sessions :", sessions);
        console.log("Nombre de dates :", countDate);

            var all_appr_presence = $('.getAllApprPresence');
            all_appr_presence.html('');


            var data_idAppr = [];

            $.ajax({
                type: "get",
                url: "/cfp/projet/apprenants/getApprenantAdded/" + idProjet,
                dataType: "json",
                beforeSend: function() {
                    all_appr_presence.append(`<span class="initialLoading">Chargement ...</span>`);
                },
                complete: function() {
                    $('.initialLoading').remove();
                },
                success: function(res) {


                    if (sessions == null) {
                        all_appr_presence.append(`<p>Pas de session pour ce projet</p>`);
                    } else {
                        drawer_presence(res, idProjet);
                    }
                }

            });
        }

        // AJAX PRESENCE INTER
        async function getAllApprPresenceInter(idProjet) {
            const res = await getPresenceData(idProjet);
            if (!res) return;

            const sessions = res.seances;
            const countDate = res.general_data?.countDate ?? 0;

            console.log("Sessions :", sessions);
            console.log("Nombre de dates :", countDate);
            var all_appr_presence = $('.getAllApprPresence');
            all_appr_presence.html('');

            $.ajax({
                type: "get",
                url: "/cfp/projet/apprenants/getApprAddedInter/" + idProjet,
                dataType: "json",
                beforeSend: function() {
                    all_appr_presence.append(`<span class="initialLoading">Chargement ...</span>`);
                },
                complete: function() {
                    $('.initialLoading').remove();
                },
                success: function(res) {
                    if (sessions == null) {
                        all_appr_presence.append(`<p>Pas de session pour ce projet</p>`);
                    } else {
                        drawer_presence(res, idProjet);
                    }
                }
            });
        }

        function drawer_presence(res, idProjet) {
            let all_appr_presence = $('.getAllApprPresence');
            all_appr_presence.empty();

            // Construction du tableau initial
            let tableHTML = `
                <thead>
                    <tr class="headPresence"><td class="text-left">Jour</td></tr>
                </thead>
                <tbody class="bodyPresence">
                    <tr class="text-center heureDebPresence"><td class="text-left">Heure début</td></tr>
                    <tr class="text-center heureFinPresence"><td class="text-left">Heure fin</td></tr>
                    <tbody class="apprPresence"></tbody>
                </tbody>`;
            all_appr_presence.append(tableHTML);

            let head_presence = $('.headPresence');
            let heure_deb_presence = $('.heureDebPresence');
            let heure_fin_presence = $('.heureFinPresence');
            let apprenant_list = $('.apprPresence');

            // Ajout des apprenants
            let apprenantHTML = res.apprs.map(data => `
                <tr class="text-center list_button_${data.idEmploye}">
                    <td class="text-left">
                        <div class="inline-flex items-center gap-2 w-max">
                            <input type="hidden" class="inputEmp" value="${data.idEmploye}">
                            <div class="flex items-center justify-center w-12 h-12 text-xl font-medium rounded-full bg-slate-200 text-slate-600">
                                ${data.emp_photo ? `<img src="{{ $endpoint }}/{{ $bucket }}/img/employes/${data.emp_photo}" alt="" class="object-cover w-12 rounded-full">` : `<i class="fa-solid fa-user"></i>`}
                            </div>
                            <p class="text-gray-500">${data.emp_name} ${data.emp_firstname}</p>
                        </div>
                    </td>
                </tr>`).join('');
            apprenant_list.append(apprenantHTML);

            // Ajout des colonnes de dates
            let datesHTML = res.countDate.map(val => `
                <x-thh se="${val.idSeance}" onclick="checkOneSe('${val.idSeance}', 'checkSe', 'checkbox_appr')" colspan="${val.count}" date="${formatDate(val.dateSeance, `dddd DD MMM YYYY`)}" />`).join('');
            head_presence.append(datesHTML);

            // Ajout des horaires
            let debHTML = res.getSeance.map(val => `<td class="text-start"><span class="inline-flex items-center gap-2">${val.heureDebut}</span></td>`).join('');
            let finHTML = res.getSeance.map(val => `<x-tdd class="text-start">${val.heureFin}</x-tdd>`).join('');
            heure_deb_presence.append(debHTML);
            heure_fin_presence.append(finHTML);

            // Ajout des cases de présence
            res.apprs.forEach(data => {
                let presenceHTML = res.getSeance.map(v_se => `
                    <td class="td_emar td_emargement_${v_se.idSeance}_${data.idEmploye}" td-se="${v_se.idSeance}" td-ep="${data.idEmploye}"></td>`).join('');
                $(`.list_button_${data.idEmploye}`).append(presenceHTML);
            });

            // Remplissage des états de présence
            res.getPresence.forEach(v_gp => {
                let td_emargement = $(`.td_emargement_${v_gp.idSeance}_${v_gp.idEmploye}`);
                if (td_emargement.length) {
                    let color_select = {
                        3: 'bg-green-500',
                        2: 'bg-amber-500',
                        1: 'bg-red-500',
                        0: 'bg-gray-500'
                    }[v_gp.isPresent] || 'bg-gray-500';

                    let checkboxHTML = `
                        <label for="td_${v_gp.idSeance}_${v_gp.idEmploye}" onclick="checkOneAppr('checkbox_appr', 'checkall')" class="flex items-center justify-center w-full h-full p-2 cursor-pointer">
                            <input type="checkbox" class="hidden checkbox_appr" name="emargement" data-idProj="${idProjet}" data-idAppr="${v_gp.idEmploye}" data-idSe="${v_gp.idSeance}" id="td_${v_gp.idSeance}_${v_gp.idEmploye}">
                            <div class="w-6 h-6 border-[1px] border-gray-200 ${color_select} rounded-md hover:border-gray-500 cursor-pointer duration-200 flex items-center justify-center text-white">
                                <i class="fa-solid fa-check hidden icon_check icon_se_${v_gp.idSeance} icon_${v_gp.idSeance}_${v_gp.idEmploye}"></i>
                            </div>
                        </label>`;
                    td_emargement.append(checkboxHTML);
                }
            });

            // Mise à jour des statistiques
            $('#present-global, .taux_presence').text(res.percentPresent);
            $('#partiel-global').text(res.percentPartiel);
            $('#absent-global').text(res.percentAbsent);
        }


        function checkOneAppr(appr_id, allcheck) {
            var data = new Array();
            var checkbox_appr = $(`.${appr_id}`);
            var checkall = $(`#${allcheck}`);

            var allChecked = $(`.${appr_id}:checked`).length === checkbox_appr.length;

            // Si toutes les cases sont cochées, cochez 'checkall', sinon décochez-le
            checkall.prop("checked", allChecked);

            $.each(checkbox_appr, function() {
                var iconCheck = $(`.icon_${$(this).attr('data-idSe')}_${$(this).attr('data-idAppr')}`);

                if ($(this).is(':checked')) {
                    iconCheck.removeClass(`hidden`);

                    data.push({
                        idEmploye: $(this).attr('data-idAppr'),
                        idSeance: $(this).attr('data-idSe'),
                        idProjet: $(this).attr('data-idProj')
                    })

                } else {
                    iconCheck.addClass(`hidden`);
                }

            });

            data_check_appr = data;
        }

        function checkOneSe(appr_id, checkSe, apprClass) {
            var data = [];
            var checkbox_appr = $(`.${apprClass}`);
            var checkSe = $(`.${checkSe}`);

            checkbox_appr.each(function() {
                if ($(this).attr('data-idSe') == appr_id) {
                    // Cocher le checkbox spécifique
                    $(this).prop('checked', true);

                    var iconCheck = $(`.icon_se_${appr_id}`);

                    if ($(this).is(':checked')) {
                        iconCheck.removeClass('hidden');
                        data.push({
                            idEmploye: $(this).attr('data-idAppr'),
                            idSeance: $(this).attr('data-idSe'),
                            idProjet: $(this).attr('data-idProj')
                        });
                    } else {
                        iconCheck.addClass('hidden');
                        // Enlever l'élément correspondant de data
                        data = data.filter(item => item.idEmploye !== $(this).attr('data-idAppr'));
                    }
                }
            });

            data_check_appr = data;
        }


        function checkallAppr(appr_id, allcheck, icon_check) {
            var data = new Array();
            var checkbox_appr = $(`.${appr_id}`);
            var checkall = $(`#${allcheck}`);
            var icon_check = $(`.${icon_check}`);

            checkbox_appr.prop("checked", checkall.is(
                ':checked'));

            if (checkall.is(':checked')) {
                icon_check.removeClass(`hidden`);
            } else {
                icon_check.addClass(`hidden`);
            }

            $.each(checkbox_appr, function() {
                if ($(this).is(':checked')) {
                    data.push({
                        idEmploye: $(this).attr('data-idAppr'),
                        idSeance: $(this).attr('data-idSe'),
                        idProjet: $(this).attr('data-idProj')
                    })
                } else {
                    data.pop({
                        idEmploye: $(this).attr('data-idAppr'),
                        idSeance: $(this).attr('data-idSe'),
                        idProjet: $(this).attr('data-idProj')
                    })
                }
            });

            data_check_appr = data;
        }

        function confirmChecking(isPresent, idProjet) {

            var lable_button = "";
            var color = "[#A462A4]";

            switch (isPresent) {
                case 1:
                    lable_button = "absent";
                    color = "red-500";
                    break;
                case 2:
                    lable_button =
                        "partiellement présent";
                    color = "amber-500";
                    break;
                case 3:
                    lable_button =
                        "présent";
                    color = "green-500";
                    break;
                default:
                    break;
            }

            var texte =
                `Toutes les cases cochées seront marquées comme ${lable_button}. Veuillez confirmer s'il vous plaît !`;

            var modal_confirmation = $('#modal_content_master');
            modal_confirmation.html('');

            modal_confirmation.append(`<div class="modal fade" id="modal" tabindex="-1" data-bs-backdrop="static">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                    <div class="font-medium text-gray-600 bg-gray-200 modal-header">
                                        <h5 class="text-lg modal-title">Confirmation</h5>
                                        <button type="button" class="text-gray-600 btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="flex flex-col items-center modal-body">
                                        <p class="text-lg text-gray-500">${texte}</p>
                                        <input type="hidden" name="isPresent" value="${isPresent}">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="px-4 py-2 text-base text-gray-600 transition duration-200 scale-95 bg-gray-200 rounded-md hover:scale-100 hover:bg-gray-200/90 text-medium" data-bs-dismiss="modal">Non, annuler</button>
                                        <button type="submit" id="submitButton" data-bs-dismiss="modal" data-id="${idProjet}" data-is_present="${isPresent}" class="px-4 py-2 text-base text-white transition duration-200 scale-95 bg-${color} rounded-md hover:scale-100 hover:bg-${color}/90 text-medium">Oui, marquer comme ${lable_button}</button>
                                    </div>
                                    </div>
                                </div>
                            </div>`);
            $('#submitButton').on('click', function(event) {
                event.preventDefault();
                var idProjet = $(this).data("id");
                var isPresent = $(this).data("is_present");

                $.ajax({
                    type: "PATCH",
                    url: `/cfp/emargement/update/${idProjet}/${isPresent}`,
                    data: JSON.stringify(data_check_appr),
                    contentType: "application/json",
                    success: function(response) {
                        toastr.success(response.success, 'Succès', {
                            timeOut: 2000
                        });
                        var projet = response.projet;
                        // Apprenant
                        if (projet.idCfp_inter == null) {
                            getAllApprPresence(idProjet);
                        } else {
                            getAllApprPresenceInter(idProjet);
                        }
                    },
                    error: function(error) {
                        toastr.error(response.error, 'Erreur', {
                            timeOut: 2000
                        });
                    }
                });
            });


            var myModalEl = $(`#modal`);
            var modal = new bootstrap.Modal(myModalEl);
            modal.show();
        }

    </script>
@endsection
