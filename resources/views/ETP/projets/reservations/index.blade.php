@extends('layouts.masterEtp')

@section('content')
    <div class="w-full flex flex-col max-h-[calc(100vh- 100px)] h-full">
        <x-sub label="Projet" icon="tarp">
        </x-sub>

        <div class="w-full h-full max-w-screen-xl mx-auto">
            <section id="filterSection" class="p-2 my-4">
                <div class="flex flex-col">
                    {{-- Text Result Search --}}
                    <h1 class="text-3xl font-semibold text-gray-500">Vous avez <span
                            class="text-[#A462A4] font-semibold text-3xl">{{ $projetCount }}</span> Projets
                    </h1>

                    {{-- Filtre --}}
                    {{-- <div class="flex flex-col gap-4 mt-12">
                        <div class="flex justify-between">
                            <div class="grid gap-4 my-2 space-x-20 2xl:grid-cols-2 md:grid-cols-2">
                                <div class="grid col-span-1 w-80">
                                    <x-drop-filter id="statut" titre="Statut" item="Statut(s)" onClick="refresh('statut')"
                                        item="Projets">
                                        <span id="filterStatut"></span>
                                    </x-drop-filter>
                                </div>
                                <div class="grid col-span-1 w-80">
                                    <x-drop-filter id="cours" titre="Cours" item="Cours" onClick="refresh('cours')"
                                        item="Projets">
                                        <span id="filterModule"></span>
                                    </x-drop-filter>
                                </div>
                            </div>
                            <span class="inline-flex items-center justify-between w-full">
                                <h3 class="text-2xl font-semibold text-gray-700 count_card_filter"></h3>
    
                                <button onclick="location.reload()" class="inline-flex items-center gap-2 text-purple-500">
                                    <i class="fa-solid fa-rotate-right"></i>
                                    Réinitialiser le filtre
                                </button>
                            </span>

                        </div>
                    </div> --}}
                </div>
            </section>

            <section id="content" class="p-2 mt-4">
                <div class="flex flex-col w-full gap-2 mt-2 mb-4 showResult">
                    @if ($projetCount <= 0)
                        <x-no-data texte="Vous n'avez pas encore de projet"></x-no-data>
                    @else
                        @if (count($projectDates) > 0)
                            @foreach ($projectDates as $projectDate)
                                <ul class="menu w-full p-0 [&_li>*]:rounded-none">
                                    <li class="menu-title !text-2xl p-3 bg-white text-slate-700 capitalize">
                                        <a>
                                            @if (isset($projectDate->headDate))
                                                {{ $projectDate->headDate }}
                                            @else
                                                --
                                            @endif
                                        </a>
                                    </li>
                                    <section
                                        class="grid grid-cols-1 gap-4 p-4 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-3 content">
                                        @foreach ($projets as $p)
                                            @if ($projectDate->headDate == $p['headDate'])
                                                <span class="flex flex-col w-full gap-2">
                                                    {{-- @include('ETP.projets.reservations.list') --}}
                                                    <x-reservation-list :p="$p" />
                                                </span>
                                            @endif
                                        @endforeach
                                    </section>
                                </ul>
                            @endforeach
                        @endif
                    @endif
                </div>
            </section>
        </div>
    </div>

    <span id="modal_confirmation"></span>
    <style>
        .applyBtn {
            background: #5B5966;
            border: none;
        }

        .applyBtn:hover {
            background: #4b4a53;
            border: none;
        }
    </style>
@endsection

@section('script')
    {{-- <script src="{{ asset('js/filter/filter_reservation_etp.js') }}"></script> --}}
    <script type="text/javascript" src="{{ asset('js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('js/filter/newFilter.js') }}"></script>
    <script type="text/javascript">
        var projets = @json($projets);
        $(document).ready(function() {
            $.each(projets, function(p, projet) {
                userAdded(projet.idProjet);
            });
            // getDropdownItemReservation();
        })

        function filter() {

        }


        function __global_drawer(__offcanvas, element) {
            let __global_drawer = $('#drawer_content_detail');
            __global_drawer.html('');

            switch (__offcanvas) {
                case 'offcanvasApprenant':

                    __global_drawer.append(`<x-drawer-apprenant></x-drawer-apprenant>`);
                    __global_drawer.ready(function() {

                        var select_appr_project = $('#select_appr_project');
                        var all_apprenant_selected = $('#all_apprenant_selected');

                        let id = $(element).data("id");
                        let nbPlace = $(element).data("nb_place");

                        userAdded(id);
                        getApprenant(id);
                        getApprenantAdded(id);

                        $('.list').hide();
                    });
                    break;

                default:
                    break;
            }

            let offcanvasId = $('#' + __offcanvas)
            var bsOffcanvas = new bootstrap.Offcanvas(offcanvasId);
            bsOffcanvas.show();
        }

        function getApprenant(id) {
            $.ajax({
                type: "get",
                url: "{{ route('listEmployes.etp', '') }}/" + id,
                success: function(response) {
                    var select_appr_project = $('#select_appr_project');
                    select_appr_project.html('')
                    $.each(response.employes, function(index, employe) {
                        select_appr_project.append(`<li class="list list_` + employe.idCustomer + ` grid grid-cols-5 w-full px-3 py-2 border-[1px] border-gray-100 cursor-pointer hover:bg-gray-50 duration-200 rounded-md !bg-white">
                            <div class="col-span-4">
                                <div class="inline-flex items-center gap-2">
                                    <span id="photo_appr_` + employe.idEmploye + `"></span>
                                    <div class="flex flex-col gap-0">
                                        <p class="text-base font-normal text-gray-700">` + employe.name + ` ` + employe
                            .firstName + `</p>
                                        <div class="flex flex-col">
                                            <span class="matricule_` + employe.matricule + `">` + employe.matricule + `</span>
                                            <span class="fonction_` + employe.fonction + `">` + employe.fonction + `</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="grid items-center justify-center w-full col-span-1">
                                <div onclick="manageApprenantInterAdd('post', ` + id + `, ` + employe.idEmploye +
                            `, ` + employe.idCustomer + `)" class="flex items-center justify-center w-10 h-10 duration-150 bg-green-100 rounded-full cursor-pointer icon hover:bg-green-50 group/icon">
                                    <i class="text-sm text-green-500 duration-150 fa-solid fa-plus group-hover/icon:text-green-600"></i>
                                </div>
                            </div>
                        </li>`);

                        var photo_appr = $('#photo_appr_' + employe.idEmploye);
                        photo_appr.html('');

                        if (employe.photo == "" || employe.photo == null) {
                            if (employe.firstName != null) {
                                photo_appr.append(
                                    `<div class="flex items-center justify-center w-10 h-10 mr-4 text-gray-500 uppercase bg-gray-200 rounded-full">` +
                                    employe.firstName[0] + `</div>`);
                            } else {
                                photo_appr.append(
                                    `<div class="flex items-center justify-center w-10 h-10 mr-4 text-gray-500 uppercase bg-gray-200 rounded-full">` +
                                    employe.name[0] + `</div>`);
                            }
                        } else {
                            photo_appr.append(
                                `<img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/employes/` +
                                employe.photo +
                                `" alt="Avatar" class="object-cover w-10 h-10 mr-4 rounded-full">`);
                        }
                    });
                },
                error: function(xhr) {
                    console.log("ERReur");
                }
            });
        }

        function getApprenantAdded(id) {
            $.ajax({
                type: "get",
                url: "{{ route('etp.employes.project', '') }}/" + id,
                success: function(res) {
                    var all_apprenant_selected = $('#all_apprenant_selected');
                    all_apprenant_selected.html('');
                    $.each(res.list_employes, function(index, emp) {
                        all_apprenant_selected.append(`<li class="list list_` + emp.idCustomer + ` grid grid-cols-5 w-full px-3 py-2 border-[1px] border-gray-100 cursor-pointer hover:bg-gray-50 duration-200 rounded-md !bg-white">
                            <div class="col-span-4">
                                <div class="inline-flex items-center gap-2">
                                    <span id="photo_appr_` + emp.idEmploye + `"></span>
                                    <div class="flex flex-col gap-0">
                                        <p class="text-base font-normal text-gray-700">` + emp.name + ` ` + emp
                            .firstName + `</p>
                                        <div class="flex flex-col">
                                            <span class="matricule_` + emp.matricule + `">` + emp.matricule + `</span>
                                            <span class="fonction_` + emp.fonction + `">` + emp.fonction + `</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="grid items-center justify-center w-full col-span-1">
                                <div onclick="manageApprenantInter('delete',` + id + `, ` +
                            emp.idEmploye + `)" class="flex items-center justify-center w-10 h-10 duration-150 bg-red-100 rounded-full cursor-pointer icon hover:bg-red-50 group/icon">
                                    <i class="text-sm text-red-500 duration-150 fa-solid fa-minus group-hover/icon:text-red-600"></i>
                                </div>
                            </div>
                        </li>`);

                        var photo_appr = $('#photo_appr_' + emp.idEmploye);
                        photo_appr.html('');

                        if (emp.photo == "" || emp.photo == null) {
                            if (emp.firstName != null) {
                                photo_appr.append(
                                    `<div class="flex items-center justify-center w-10 h-10 mr-4 text-gray-500 uppercase bg-gray-200 rounded-full">` +
                                    emp.firstName[0] + `</div>`);
                            } else {
                                photo_appr.append(
                                    `<div class="flex items-center justify-center w-10 h-10 mr-4 text-gray-500 uppercase bg-gray-200 rounded-full">` +
                                    emp.name[0] + `</div>`);
                            }
                        } else {
                            photo_appr.append(
                                `<img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/employes/` +
                                emp.photo +
                                `" alt="Avatar" class="object-cover w-10 h-10 mr-4 rounded-full">`);
                        }
                    });
                },
                error: function(xhr) {
                    console.log("ERReur");
                }
            });
        }

        function userAdded(id) {
            $.ajax({
                type: "get",
                url: "{{ route('etp.employes.project', '') }}/" + id,
                success: function(res) {
                    var user_added = $(`#user_added_${id}`);
                    user_added.html('');
                    if ((res.list_employes != null && res.list_employes.length > 0)) {
                        $.each(res.list_employes, function(index, emp) {
                            if (emp.photo == "" || emp.photo == null) {
                                if (emp.firstName != null) {
                                    user_added.append(
                                        `<div class="flex items-center justify-center w-10 h-10 text-gray-500 uppercase bg-gray-200 rounded-full cursor-pointer" title="${emp.name} ${emp.firstName}">` +
                                        emp.firstName[0] + `</div>`);
                                } else {
                                    user_added.append(
                                        `<div class="flex items-center justify-center w-10 h-10 text-gray-500 uppercase bg-gray-200 rounded-full cursor-pointer" title="${emp.name}">` +
                                        emp.name[0] + `</div>`);
                                }
                            } else {
                                user_added.append(
                                    `<img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/employes/` +
                                    emp.photo +
                                    `" alt="Avatar" class="object-cover w-10 h-10 rounded-full">`);
                            }
                        });
                    } else {
                        // user_added.append(`<p>L'ajout de vos apprenants est maintenant disponible.</p>`);
                    }

                },
                error: function(xhr) {
                    console.log("ERReur");
                }
            });
        }

        function manageApprenantInter(type, idProjet, idApprenant, idEtp) {
            $.ajax({
                type: type,
                url: "/etp/employes/" + idProjet + "/" + idApprenant + "/" +
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
                        getApprenant(idProjet);
                        getApprenantAdded(idProjet);
                        userAdded(idProjet);
                    } else if (res.error) {
                        toastr.error(res.error, 'Erreur', {
                            timeOut: 1500
                        });
                    }
                }
            });
        }

        function manageApprenantInterAdd(type, idProjet, idApprenant, idEtp) {
            $.ajax({
                type: type,
                url: "/etp/employes/" + idProjet + "/" + idApprenant + "/" + idEtp,
                data: {
                    _token: '{!! csrf_token() !!}'
                },
                dataType: "json",
                success: function(res) {
                    if (res.status == 200) {
                        toastr.success(res.success, 'Succès', {
                            timeOut: 1500
                        });
                        getApprenant(idProjet);
                        getApprenantAdded(idProjet);
                        userAdded(idProjet);
                    } else if (res.status == 403) {
                        toastr.error('Vous atteint le nombre maximum de l\'apprenant', 'Erreur', {
                            timeOut: 1500
                        });
                    }
                },
                error: function(r) {
                    toastr.error('Erreur', r, {
                        timeOut: 1500
                    });
                }

            });
        }
    </script>
@endsection
