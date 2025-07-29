@extends('layouts.masterEmp')
@section('content')
    <style>
        .scroll::-webkit-scrollbar {
            display: none;
        }

        .scroll {
            -ms-overflow-style: none;
            overflow: -moz-scrollbars-none;
        }
    </style>
    <div class="w-full flex h-full overflow-x-scroll flex-col bg-gray-100">
        <x-sub-app label="Projet" icon="tarp">
            <x-v-separator />
            <input type="hidden" id="project_id_hidden" value="{{ $projet->idProjet }}">
            <div
                class="px-3 py-1 text-white text-xs  
                @switch($projet->project_type)
                    @case('Intra')
                        bg-[#1565c0]
                    @break
                    @case('Inter')
                        bg-[#7209b7]
                    @break
                    @default 
                @endswitch">
                {{ $projet->project_type }}
            </div>

            <select onchange="updateModuleProjetDetail({{ $projet->idProjet }})"
                class="project_idModule_detail outline-none border-[1px] px-2 hover:bg-gray-100 duration-200 cursor-pointer border-gray-50 bg-gray-50 text-gray-600 font-medium">
                @foreach ($modules as $module)
                    <option value="{{ $module->idModule }}" {{ $projet->idModule == $module->idModule ? 'selected' : '' }}>
                        {{ $module->module_name }}
                    </option>
                @endforeach
            </select>
            @if ($projet->idCfp_inter == null)
                <label class="text-base text-gray-500">pour</label>
            @endif
            <span onclick="showCustomer({{ $projet->idEtp }}, '/employes/etp-drawer/')" id="etpNameSub"
                class="font-medium text-gray-600 cursor-pointer">{{ $projet->etp_name }}</span>
        </x-sub-app>
        <div class="flex flex-col gap-4 max-w-screen-xl mx-auto w-full h-[calc(100vh-30px)] mt-[41px] p-4 ">

            <div class="flex flex-col w-full bg-white p-3 border-[1px] shadow-sm border-gray-200 rounded-lg">
                <div class="inline-flex items-center gap-3">
                    <i class="text-lg fa-solid fa-ranking-star"></i>
                    <h3 class="text-xl font-semibold text-gray-700">Statut et notation</h3>
                </div>
                {{-- <div class="flex flex-row items-center justify-start gap-4"> --}}
                <div
                    class="grid justify-start w-full @if ($projet->project_type == 'Inter') grid-cols-5
                @else
                    grid-cols-4 @endif  gap-3">
                    <div class="flex items-center justify-start">
                        <div class="flex flex-col items-center gap-1">
                            <p class="text-xl font-medium text-gray-600">{{ number_format($noteGeneral, 1, ',', ' ') }}
                                <span class="text-gray-400">
                                    ({{ $countNotationProjet }} avis)
                                </span>
                            </p>
                            <div id="raty_notation" class="inline-flex items-center gap-2">
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-gray-400">Statut:</span>
                        <div class="inline-flex items-center gap-2">
                            <span
                                class="inline-flex items-center gap-2 px-2 py-1 text-sm text-white w-[90px] justify-center 
                  @switch($projet->project_status)
                    @case('En préparation')
                      bg-[#66CDAA]
                      @break
                    @case('Réservé')
                      bg-[#33303D]
                      @break
                    @case('En cours')
                      bg-[#1E90FF]
                      @break
                    @case('Terminé')
                      bg-[#32CD32]
                      @break
                    @case('Annulé')
                      bg-[#FF6347]
                      @break
                    @case('Reporté')
                      bg-[#2E705A]
                      @break
                    @case('Planifié')
                      bg-[#2552BA]
                      @break

                    @default
                         bg-[#828282]
                      @break
                  @endswitch
                "
                                title=" @switch($projet->project_status)
                           @case('En préparation')
                            Le projet de formation est en cours de préparation, avec des détails tels que le programme, les supports de formation, et la logistique en cours de finalisation.
                            @break
                          @case('Réservé')
                            Le projet de formation est réservé.
                            @break
                          @case('En cours')
                            La formation a débuté et les sessions sont en train de se dérouler selon le calendrier prévu.
                            @break
                          @case('Terminé')
                            Toutes les sessions de formation prévues ont été effectuées et la formation est officiellement terminée.
                          @break
                          @case('Annulé')
                            Le projet de formation a été annulé avant d'avoir pu être mené à terme. Cela peut être dû à un manque de participants, des contraintes logistiques, ou d'autres raisons.
                            @break
                          @case('Reporté')
                            La formation a été initialement planifiée mais a été reportée à une date ultérieure. Cela peut être dû à des contraintes de calendrier, des imprévus, ou d'autres raisons.
                            @break
                          @case('Planifié')
                            Le projet de formation a été créé et les dates, lieux, et formateurs ont été déterminés, mais la formation n\'a pas encore commencé.
                            @break
                        @endswitch ">
                                {{ $projet->project_status }}
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-gray-400">Taux de présence:</span>
                        <span class="font-medium text-gray-600 taux_presence"></span>
                    </div>
                    @if ($projet->project_type == 'Inter')
                        <div class="flex flex-col gap-1">
                            <span class="text-gray-400">Disponibilité de place:</span>
                            <span class="font-medium text-gray-600 ">{{ $place_available }} / {{ $nbPlace }} <a
                                    href="{{ route('cfp.reservation.project', $projet->idProjet) }}"
                                    class="text-[#2552BA]"> ({{ $place_reserved }} réservations) </a></span>
                        </div>
                    @endif
                    <div class="flex flex-col gap-1">
                    </div>
                </div>
                {{-- </div> --}}
            </div>

            @if (isset($projet->idCfp_inter))
                <div class="flex flex-col w-full bg-white p-3 border-[1px] shadow-sm border-gray-200 rounded-lg">
                    <div class="inline-flex items-center gap-3">
                        <i class="text-lg fa-solid fa-building"></i>
                        <h3 class="text-xl font-semibold text-gray-700">Les entreprises</h3>
                    </div>
                    <div class="flex flex-col w-full gap-1">
                        <span class="inline-flex flex-wrap items-center w-full gap-4 dash_etp">
                        </span>
                    </div>
                </div>
            @endif

            <span id="__etp_content_detail"></span>

            <div class="flex flex-col w-full bg-white p-3 border-[1px] shadow-sm border-gray-200 rounded-lg">
                <div class="inline-flex items-center gap-3">
                    <i class="text-lg fa-solid fa-info-circle"></i>
                    <h3 class="text-xl font-semibold text-gray-700">Informations générales</h3>
                </div>
                <div class="flex flex-col w-full gap-1">
                    <div class="inline-flex items-center gap-2">
                        <div class="inline-flex items-center">
                            <label class="text-base font-normal text-gray-400">Ref :</label>
                            <span
                                class="px-2 font-normal text-gray-500 rounded-md">{{ $projet->project_reference }},</span>
                        </div>
                        <h2 class="text-lg font-medium text-gray-500">
                            @if (isset($projet->module_name) && $projet->module_name != 'Default module')
                                {{ $projet->module_name }}
                            @else
                                N/A
                            @endif,
                        </h2>
                        <div class="inline-flex items-center gap-2 ml-3">
                            <div class="inline-flex items-center gap-4">
                                <div class="flex items-center justify-start h-10 text-gray-400 duration-150 cursor-pointer"
                                    title="Date de début de la formation">
                                    du
                                </div>
                                @if ($projet->project_status != 'Terminé')
                                    @if ($projet->dateDebut != null)
                                        <input onchange="updateDateDebut({{ $projet->idProjet }})" type="date"
                                            value="{{ $projet->dateDebut }}"
                                            class="project_dd_detail outline-none bg-transparent flex pl-2 h-8 border-[1px] border-gray-50 hover:border-purple-300 focus:border-purple-300 focus:ring-2 focus:ring-purple-100 duration-200 text-gray-500" />
                                    @else
                                        <input onchange="updateDateDebut({{ $projet->idProjet }})" type="date"
                                            class="project_dd_detail outline-none bg-transparent flex pl-2 h-8 border-[1px] border-gray-50 hover:border-purple-300 focus:border-purple-300 focus:ring-2 focus:ring-purple-100 duration-200 text-gray-500" />
                                    @endif
                                @else
                                    <input type="date" value="{{ $projet->dateDebut }}" disabled
                                        class="project_dd_detail outline-none bg-transparent flex pl-2 h-8 border-[1px] border-gray-50 hover:border-purple-300 focus:border-purple-300 focus:ring-2 focus:ring-purple-100 duration-200 text-gray-500" />
                                @endif
                            </div>
                            <div class="inline-flex items-center gap-4">
                                <div class="flex items-center justify-start h-10 text-gray-400 duration-150 cursor-pointer"
                                    title="Date de fin de formation">
                                    au
                                </div>
                                @if ($projet->project_status != 'Terminé')
                                    @if ($projet->dateFin != null)
                                        <input onchange="updateDateFin({{ $projet->idProjet }})" type="date"
                                            value="{{ $projet->dateFin }}"
                                            class="project_df_detail outline-none bg-transparent flex pl-2 h-8 border-[1px] border-gray-50 hover:border-purple-300 focus:border-purple-300 focus:ring-2 focus:ring-purple-100 duration-200 text-gray-500" />
                                    @else
                                        <input onchange="updateDateFin({{ $projet->idProjet }})" type="date"
                                            class="project_df_detail outline-none bg-transparent flex pl-2 h-8 border-[1px] border-gray-50 hover:border-purple-300 focus:border-purple-300 focus:ring-2 focus:ring-purple-100 duration-200 text-gray-500" />
                                    @endif
                                @else
                                    <input type="date" value="{{ $projet->dateFin }}" disabled
                                        class="project_df_detail outline-none bg-transparent flex pl-2 h-8 border-[1px] border-gray-50 hover:border-purple-300 focus:border-purple-300 focus:ring-2 focus:ring-purple-100 duration-200 text-gray-500" />
                                @endif

                            </div>
                        </div>
                    </div>
                    <div class="inline-flex items-center gap-2">
                        <label class="text-base font-normal text-gray-400">Description :</label>
                        <p class="text-gray-500">
                            @if ($projet->project_description != null)
                                {{ $projet->project_description }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col w-full bg-white p-3 border-[1px] shadow-sm border-gray-200 rounded-lg">
                <div class="inline-flex items-center gap-3">
                    <i class="text-lg fa-solid fa-bullseye"></i>
                    <h3 class="text-xl font-semibold text-gray-700">Objectif de la formation</h3>
                </div>
                <ul class="flex flex-col ml-12">
                    @foreach ($objectifs as $objectif)
                        @if ($objectif->idModule == $projet->idModule)
                            <li class="text-gray-500 list-disc">{{ $objectif->objectif }}.</li>
                        @endif
                    @endforeach
                </ul>
            </div>

            <div class="flex flex-col w-full bg-white p-3 border-[1px] shadow-sm border-gray-200 rounded-lg">
                <div class="inline-flex items-center gap-3">
                    <i class="text-lg fa-solid fa-box-open"></i>
                    <h3 class="text-xl font-semibold text-gray-700">Matériels nécessaires</h3>
                </div>
                <p class="mb-2 text-gray-500 indent-8">Pour cette formation, les apprenants ont besoin de :
                    @foreach ($materiels as $mat)
                        @if ($mat->idModule == $projet->idModule)
                            <span class="text-[#A462A4] font-medium lowercase">{{ $mat->prestation_name }} -</span>
                        @endif
                    @endforeach.
                </p>
            </div>


            <div class="flex flex-col w-full bg-white p-3 border-[1px] shadow-sm border-gray-200 rounded-lg">
                <div class="inline-flex items-center gap-3 mb-2">
                    <i class="text-lg fa-solid fa-calendar-day"></i>
                    <h3 class="text-xl font-semibold text-gray-700">Agenda</h3>
                </div>
                <p class="mb-2 text-gray-500 indent-8">
                    La formation aura lieu à <span class="text-gray-600">
                        <span class="salle_re"></span>
                        <span class="salle_qrt font-semibold text-[#A462A4]"></span>
                        <span class="salle_ville font-semibold text-[#A462A4]"></span>
                        <span class="salle_cp"></span>
                        <span class="salle_nm font-semibold text-[#A462A4]"></span>
                    </span>
                </p>

                <p class="mb-2 text-gray-500 indent-8">
                    @if (count($seances) > 0)
                        Vous avez <span class="font-semibold text-[#A462A4]">{{ count($seances) }}</span> sessions d'une
                        durée
                        total
                        de
                        <span class="font-semibold text-[#A462A4]">{{ $totalSession->sumHourSession }}</span>
                    @endif
                </p>
                {{-- <p class="mb-2 text-gray-500 indent-8">
          <span class="italic underline underline-offset-2">Pause café et déjeuner</span> inclus dans la formation
        </p> --}}
                <div class="flex flex-col items-start justify-center w-full h-full gap-2 p-1">
                    @if (count($seances) <= 0)
                        <x-no-data class="!h-14" texte="Pas de session" />
                    @else
                        <ul class="relative flex flex-col gap-2 list-disc">
                            @foreach ($seances as $seance)
                                <li
                                    class="text-gray-500 text-sm font-medium inline-flex items-center gap-2 marker:text-[#A462A4]">
                                    <div class="w-[125px] capitalize font-normal dateJour">
                                        {{ \Carbon\Carbon::parse($seance->dateSeance)->translatedFormat('l jS F Y') }},
                                    </div>
                                    <span class="font-normal">de</span>
                                    <span
                                        class="heureDeb">{{ \Carbon\Carbon::parse($seance->heureDebut)->format('H\h i') }}</span>
                                    <span class="font-normal"> à</span>
                                    <span
                                        class="heureFin">{{ \Carbon\Carbon::parse($seance->heureFin)->format('H\h i') }}</span>.
                                    <span class="font-normal">Durée <span
                                            class="intervalle_raw">{{ $seance->intervalle_raw }}</span></span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <div class="accordion" id="accordionPanelsStayOpenExample">
                <div class="bg-white shadow-sm accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                        <button class="text-xl font-semibold text-gray-700 accordion-button collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true"
                            aria-controls="panelsStayOpen-collapseOne">
                            <div class="inline-flex items-center gap-3">
                                <i class="text-lg fa-solid fa-calendar-days"></i>
                                <h3 class="text-xl font-semibold text-gray-700">Calendrier</h3>
                            </div>
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseOne" class="visible accordion-collapse collapse"
                        aria-labelledby="panelsStayOpen-headingOne">
                        <div class="accordion-body">
                            <div class="grid gap-4 sm:grid-cols-1 lg:grid-cols-2">
                                <div class="h-full sm:shadow-none sm:border-none lg:shadow-sm lg:border-[1px] border-gray-100"
                                    id="evoCalendar"></div>
                                <div class="h-full sm:shadow-none sm:border-none lg:shadow-sm lg:border-[1px] border-gray-100"
                                    id="evoCalendar2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col w-full bg-white p-3 border-[1px] shadow-sm border-gray-200 rounded-lg">
                <div class="flex flex-col w-full gap-2">
                    <div class="inline-flex items-center gap-3">
                        <i class="text-lg fa-solid fa-people-group"></i>
                        <h3 class="text-xl font-semibold text-gray-700">Participants :</h3>
                        <p class="text-lg font-semibold tracking-wide text-gray-700 uppercase"><span
                                class="mr-2 text-lg font-semibold tracking-wide text-gray-700 uppercase getCountApprProject"></span>Apprenants
                        </p>
                    </div>
                    <div class="px-4 mt-4 getApprProject">
                    </div>
                </div>
            </div>

            <div class="accordion" id="accordionPanelsStayOpenExample">
                <div class="bg-white shadow-sm accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                        <button class="text-xl font-semibold text-gray-700 accordion-button collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="true"
                            aria-controls="panelsStayOpen-collapseTwo">
                            <div class="inline-flex items-center gap-3">
                                <i class="text-lg fa-solid fa-chalkboard"></i>
                                <h3 class="text-xl font-semibold text-gray-700">Programme pédagogique</h3>
                            </div>
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseTwo" class="visible accordion-collapse collapse"
                        aria-labelledby="panelsStayOpen-headingOne">
                        <div class="accordion-body">
                            <div class="flex flex-wrap justify-between gap-2 get_all_programme_project"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion" id="accordionPanelsStayOpenExample">
                <div class="bg-white shadow-sm accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                        <button class="text-xl font-semibold text-gray-700 accordion-button collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="true"
                            aria-controls="panelsStayOpen-collapseThree">
                            <div class="inline-flex items-center gap-3">
                                <i class="text-lg fa-solid fa-bookmark"></i>
                                <h3 class="text-xl font-semibold text-gray-700">Ressources téléchargeables (Support de
                                    cours &
                                    Exercices)
                                </h3>
                            </div>
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseThree" class="visible accordion-collapse collapse"
                        aria-labelledby="panelsStayOpen-headingOne">
                        <div class="accordion-body">
                            <span class="get_all_mr_project"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col w-full bg-white p-3 border-[1px] shadow-sm border-gray-200 rounded-lg">
                <div class="flex flex-col w-full gap-2">
                    <div class="inline-flex items-center gap-3">
                        <i class="text-lg fa-solid fa-user-graduate"></i>
                        <h3 class="text-xl font-semibold text-gray-700">Faite connaissance avec les formateurs :</h3>
                    </div>
                    <div
                        class="grid w-full gap-2 get_project_form sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6">
                    </div>
                </div>
            </div>

            <!-- Drawer pour afficher le mini CV -->
            <div id="miniCvDrawer" class="fixed inset-0 z-50 hidden p-4 bg-white shadow-lg">
                <div class="flex items-center justify-between mb-4 header">
                    <h2 class="text-xl font-semibold">Mini CV</h2>
                    <button onclick="hideMiniCV()" class="btn btn-secondary">Fermer</button>
                </div>

                <div id="miniCvContent">
                    <!-- Le contenu du mini CV sera ajouté ici par JavaScript -->
                </div>
            </div>

            <div class="flex flex-col w-full p-3 bg-white border-[1px] shadow-sm border-gray-200 rounded-lg">
                <div class="inline-flex items-center gap-3 mb-3">
                    <i class="text-lg fa-solid fa-image"></i>
                    <h3 class="text-xl font-semibold text-gray-700">Momentum ( Galerie photo )</h3>
                </div>
                <div class="w-full overflow-x-auto">
                    <div class="w-full h-full owl-carousel">
                        <div class="w-full text-white truncate rounded-md bg-cyan-500 h-72">
                            <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8Zm9ybWF0aW9ufGVufDB8fDB8fHww"
                                alt="photo" class="object-cover h-full">
                        </div>
                        <div class="w-full text-white truncate rounded-md bg-cyan-500 h-72">
                            <img src="https://images.unsplash.com/photo-1552581234-26160f608093?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTF8fGZvcm1hdGlvbnxlbnwwfHwwfHx8MA%3D%3D"
                                alt="photo" class="object-cover h-full">
                        </div>
                        <div class="w-full text-white truncate rounded-md bg-cyan-500 h-72">
                            <img src="https://images.unsplash.com/photo-1503428593586-e225b39bddfe?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTh8fGZvcm1hdGlvbnxlbnwwfHwwfHx8MA%3D%3D"
                                alt="photo" class="object-cover h-full">
                        </div>
                        <div class="w-full text-white truncate rounded-md bg-cyan-500 h-72">
                            <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8Zm9ybWF0aW9ufGVufDB8fDB8fHww"
                                alt="photo" class="object-cover h-full">
                        </div>
                        <div class="w-full text-white truncate rounded-md bg-cyan-500 h-72">
                            <img src="https://images.unsplash.com/photo-1529070538774-1843cb3265df?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTl8fGZvcm1hdGlvbnxlbnwwfHwwfHx8MA%3D%3D"
                                alt="photo" class="object-cover h-full">
                        </div>
                        <div class="w-full text-white truncate rounded-md bg-cyan-500 h-72">
                            <img src="https://images.unsplash.com/photo-1556761175-5973dc0f32e7?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MjN8fGZvcm1hdGlvbnxlbnwwfHwwfHx8MA%3D%3D"
                                alt="photo" class="object-cover h-full">
                        </div>
                        <div class="w-full text-white truncate rounded-md bg-cyan-500 h-72">
                            <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mjd8fGZvcm1hdGlvbnxlbnwwfHwwfHx8MA%3D%3D"
                                alt="photo" class="object-cover h-full">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <span id="__global_drawer"></span>
        <span id="drawer_eval"></span>
        <span id="modal_confirmation"></span>
        <x-drawer-lieu></x-drawer-lieu>
        <span id="drawer_cv"></span>
    @endsection
    @section('script')
        <script src="{{ asset('js/daypilot-pro-javascript/daypilot-javascript.min.js') }}"></script>
        <script src="{{ asset('js/calendar-evo/evo-calendar.min.js') }}"></script>
        <script src="{{ asset('js/planningAppr.js') }}"></script>
        <script src="{{ asset('js/heat-rating.js') }}"></script>
        <script src="{{ asset('js/loading-emp.js') }}"></script>
        <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap5-toggle.jquery.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                var owl = $('.owl-carousel');
                owl.owlCarousel({
                    items: 1,
                    loop: true,
                    margin: 10,
                    autoplay: true,
                    autoplayTimeout: 3000,
                    autoplayHoverPause: true,
                });
                $('.play').on('click', function() {
                    owl.trigger('play.owl.autoplay', [1000])
                })
                $('.stop').on('click', function() {
                    owl.trigger('stop.owl.autoplay')
                })
            });


            ratyNotation('raty_notation', {{ $noteGeneral }});
            getAllForms();
            getFormAdded({{ $projet->idProjet }});

            var projet = @json($projet);
            if (projet.idCfp_inter == null) {
                getApprenantAdded({{ $projet->idProjet }});
                getEtpAssigned({{ $projet->idProjet }});
                getApprenantProjets({{ $projet->idEtp }});
                getAllApprPresence({{ $projet->idProjet }});
            } else {
                getApprenantProjetInter({{ $projet->idProjet }});
                getEtpAdded({{ $projet->idProjet }});
                getAllApprPresenceInter({{ $projet->idProjet }});

                // Au chargement de la page, cacher tous les éléments <li>
                $('.list').hide();

                getApprenantAddedInter({{ $projet->idProjet }});
            }

            getAllEtps({{ $projet->idCfp_inter }});

            getProgramProject({{ $projet->idModule }});
            getModuleRessourceProject({{ $projet->idModule }});
            // getAllSalle();
            getSalleAdded({{ $projet->idProjet }});


            $('#ajoutEtp').click(function(e) {
                e.preventDefault();
                $('#formEtp').toggleClass(`h-0`, `h-80`);
            });

            getSeanceByIdStorage({{ $projet->idProjet }})

            const check = $('.formateur-li').attr('check');

            // Agenda
            function getStore(cleAccess) {
                const data = sessionStorage.getItem(cleAccess);
                return JSON.parse(data);
            }

            function setStore(cleAccess, newVal) {
                sessionStorage.setItem(cleAccess, JSON.stringify(newVal));
            }

            const objEvents = [];
            const holidays = [
                // Ajoutez ici d'autres journées fériées
                {
                    id: 1,
                    date: "January/01/2024",
                    name: "Jour de l'an",
                    type: 'holiday'
                },
                {
                    id: 2,
                    date: "March/08/2024",
                    name: "Journée internationale de la femme",
                    type: 'holiday'
                },
                {
                    id: 3,
                    date: "March/11/2024",
                    name: "Ramadan",
                    type: 'holiday'
                },
                {
                    id: 4,
                    date: "March/29/2024",
                    name: "Jour des Martyrs",
                    type: 'holiday'
                },
                {
                    id: 5,
                    date: "March/31/2024",
                    name: "Pâques",
                    type: 'holiday'
                },
                {
                    id: 6,
                    date: "April/01/2024",
                    name: "lundi de Pâques",
                    type: 'holiday'
                },
                {
                    id: 7,
                    date: "April/10/2024",
                    name: "Aïd el-Fitr",
                    type: 'holiday'
                },
                {
                    id: 8,
                    date: "May/01/2024",
                    name: "Fête du Travail",
                    type: 'holiday'
                },
                {
                    id: 9,
                    name: "Ascension",
                    date: 'May/09/2024',
                    type: 'holiday'
                },
                {
                    id: 10,
                    date: "May/19/2024",
                    name: "Pentecôte",
                    type: 'holiday'
                },
                {
                    id: 11,
                    date: "May/20/2024",
                    name: "Lundi de Pentecôte",
                    type: 'holiday'
                },
                {
                    id: 12,
                    date: "June/17/2024",
                    name: "Aïd el-Kebir",
                    type: 'holiday'
                },
                {
                    id: 13,
                    date: "June/26/2024",
                    name: "Fête de l'Indépendance",
                    type: 'holiday'
                },
                {
                    id: 14,
                    date: "August/15/2024",
                    name: "Assomption",
                    type: 'holiday'
                },
                {
                    id: 15,
                    date: "November/01/2024",
                    name: "Toussaint",
                    type: 'holiday'
                },
                {
                    id: 16,
                    date: "December/25/2024",
                    name: "Noël",
                    type: 'holiday'
                },
                {
                    id: 17,
                    date: "December/31/2024",
                    name: "la Saint-Sylvestre",
                    type: 'holiday'
                },
                {
                    id: 18,
                    date: "January/01/2025",
                    name: "Jour de l'an",
                    type: 'holiday'
                },
            ];

            //Fonction pour AM => type:birthday, PM => type:event
            function setStatusType(date) {
                if (date.getHours() >= 12 && date.getMinutes() >= 0) {
                    //alert('Date>=12 ==>PM');
                    return 'event';
                } else if (date.getHours() < 12) {
                    //alert('Date<=12 ==>AM');
                    return 'birthday';
                } else {
                    return 'holiday';
                }
            }

            function getIdCustomer() {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        url: '/projetsEmp/get-id-customer', // Assurez-vous que cette route retourne l'ID utilisateur
                        method: 'GET',
                        success: function(data) {
                            resolve(data.idCustomer);
                        },
                        error: function(error) {
                            reject('Erreur lors de la récupération de l\'ID utilisateur');
                        }
                    });
                });
            }

            async function getSeanceById(idProjet) //<======= liste des séances avec affichage sur le Daypilot
            {
                let dataEvnts;
                const url = `/seancesEmps/${idProjet}/getAllSeances`;
                await fetch(url, {
                        method: "GET"
                    })
                    .then(response => response.json())
                    .then(data => {
                        dataEvnts = data.seances;
                        for (let obj of dataEvnts) {
                            let date = new Date(obj.end);
                            const monthName = date.toLocaleString('en-US', {
                                month: 'long'
                            });
                            objEvents.push({
                                id: obj.idSeance,
                                name: obj.module,
                                date: monthName + "/" + date.getDate() + "/" + date.getFullYear(),
                                type: setStatusType(date),
                                everyYear: true,
                                //color:"#A462A4",
                            })
                        }
                    })
                return objEvents;
            }

            function getSeanceByIdStorage(idProjet) //<======= liste des séances avec affichage sur le Daypilot(optimisé)
            {
                let objEventStorage = [];
                let objEvents = [];
                getIdCustomer().then(idCustomer => {
                    const dataEvnts = this.getStore(
                        'ACCESS_EVENTS_DETAILS_' + idCustomer);

                    if (dataEvnts === null) {
                        console.log("nulllllllll");
                    } else {
                        objEventStorage = dataEvnts.filter(evnt => evnt.idProjet == idProjet);
                        for (obj of objEventStorage) {
                            let date = new Date(obj.start);

                            const monthName = date.toLocaleString('en-US', {
                                month: 'long'
                            });
                            objEvents.push({
                                id: obj.idSeance,
                                name: obj.module,
                                date: `${date.getDate()}/${monthName}/${date.getFullYear()}`,
                                type: setStatusType(date),
                                format: "dd/MM/yyyy",
                                everyYear: true,
                                //color:"#A462A4",
                            })
                        }
                    }

                    $('#evoCalendar').evoCalendar({
                        language: 'fr',
                        sidebarToggler: false,
                        sidebarDisplayDefault: false,
                        eventListToggler: false,
                        eventDisplayDefault: false,
                        firstDayOfWeek: 1, // Lundi
                        todayHighlight: true, // Met en surbrillance la date actuelle
                        format: "dd/MM/yyyy",
                        calendarEvents: objEvents, // Ajoute les événements
                    });

                    $('#evoCalendar2').evoCalendar({
                        language: 'fr',
                        sidebarToggler: false,
                        sidebarDisplayDefault: false,
                        eventListToggler: false,
                        eventDisplayDefault: false,
                        firstDayOfWeek: 1, // Lundi
                        todayHighlight: true, // Met en surbrillance la date actuelle
                        format: "dd/MM/yyyy",
                        calendarEvents: objEvents, // Ajoute les événements

                    });
                    $('#evoCalendar').evoCalendar(
                        'addCalendarEvent', holidays);
                    $('#evoCalendar2').evoCalendar('selectYear',
                        getNextMonthDate().getFullYear());
                    $('#evoCalendar2').evoCalendar('selectMonth',
                        getNextMonthDate().getMonth());

                    $('#evoCalendar').on('selectDate', function() {
                        return window.location.href =
                            '/agendaEmps';
                    });
                    $('#evoCalendar2').on('selectDate', function() {
                        return window.location.href =
                            '/agendaEmps';
                    });

                })
                return objEvents;
            }

            // Fonction pour obtenir la date du mois suivant
            function getNextMonthDate() {
                const today = new Date();
                const nextMonth = new Date(today.getFullYear(), today
                    .getMonth() + 1, 1);
                return nextMonth;
            }


            function getApprenantProjets(idEtp) {
                $.ajax({
                    type: "get",
                    url: "/employes/projets/apprenants/getApprenantProjets/" + idEtp,
                    dataType: "json",
                    success: function(res) {
                        console.log('GET ALL APPRENANTS-->', res);
                        var all_apprenant = $('#all_apprenant');
                        all_apprenant.html('');

                        let etp_option = null;

                        if (res.apprs.length <= 0) {
                            all_apprenant.append(`<x-no-data texte="Aucun résultat"></x-no-data>`);
                        } else {

                            // Remplir la liste des apprenants initiale
                            fillApprenantListIntra(res.apprs);

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
                        }

                    }
                });
            }

            // Fonction pour remplir la liste des apprenants
            function fillApprenantListIntra(apprs) {

                var all_apprenant = $('#all_apprenant');
                all_apprenant.html('');
                $.each(apprs, function(key, val) {
                    let firstName = val.emp_firstname != null ? val.emp_firstname : '';
                    all_apprenant.append(`<li class="list list_` + val.idEtp + ` grid grid-cols-5 w-full px-3 py-2 border-[1px] border-gray-100 cursor-pointer hover:bg-gray-50 duration-200 rounded-md !bg-white">
        <div class="col-span-4">
            <div class="inline-flex items-center gap-2">
                <span id="photo_appr_` + val.idEmploye + `"></span>
                <div class="flex flex-col gap-0">
                    <p class="text-base font-normal text-gray-700">` + val.emp_name + ` ` + firstName + `</p>
                    <div class="flex flex-col">
                        <p class="text-sm text-gray-400">Matricule : ` + val.emp_matricule + `</p>
                        <p class="text-sm text-gray-400">Fonction : ` + val.emp_fonction + `</p>
                        <p class="text-sm text-gray-400 normal-case">Entreprise : ` + val.etp_name + `</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid items-center justify-center w-full col-span-1">
            <div  onclick="manageApprenant('post', {{ $projet->idProjet }}, ${val.idEmploye})" class="flex items-center justify-center w-10 h-10 duration-150 bg-green-100 rounded-full cursor-pointer icon hover:bg-green-50 group/icon">
                <i class="text-sm text-green-500 duration-150 fa-solid fa-plus group-hover/icon:text-green-600"></i>
            </div>
        </div>  
        </li>`);

                    var matricule = $('#matricule_' + val.idEmploye);
                    var fonction = $('#fonction_' + val.idEmploye);

                    if (val.matricule != null) {
                        matricule.append(`<p class="text-sm text-gray-400">Matricule : ` + val.emp_matricule + `</p>`);
                    }
                    if (val.emp_fonction != null && val.emp_fonction != 'Default') {
                        fonction.append(`<p class="text-sm text-gray-400">Fonction : ` + val.emp_fonction + `</p>`);
                    }

                    var photo_appr = $('#photo_appr_' + val.idEmploye);
                    photo_appr.html('');

                    if (val.emp_photo == "" || val.emp_photo == null) {
                        if (val.emp_firstname != null) {
                            photo_appr.append(
                                `<div class="flex items-center justify-center w-10 h-10 mr-4 text-gray-500 uppercase bg-gray-200 rounded-full">` +
                                val.emp_firstname[0] + `</div>`);
                        } else {
                            photo_appr.append(
                                `<div class="flex items-center justify-center w-10 h-10 mr-4 text-gray-500 uppercase bg-gray-200 rounded-full">` +
                                val.emp_name[0] + `</div>`);
                        }
                    } else {
                        photo_appr.append(`<img src="/img/employes/` + val.emp_photo +
                            `" alt="Avatar" class="object-cover w-10 h-10 mr-4 rounded-full">`);
                    }
                });
            }

            function manageApprenant(type, idProjet, idApprenant) {
                $.ajax({
                    type: type,
                    url: "/projetsEmp/etpIntra" + idProjet + "/" + idApprenant,
                    data: {
                        _token: '{!! csrf_token() !!}'
                    },
                    dataType: "json",
                    success: function(res) {
                        if (res.success) {
                            toastr.success(res.success, 'Succès', {
                                timeOut: 1500
                            });
                            getApprenantAdded({{ $projet->idProjet }});
                            getAllApprPresence({{ $projet->idProjet }});
                            ratyNotation('raty_notation', {{ $noteGeneral }});
                        } else if (res.error) {
                            toastr.error(res.error, 'Erreur', {
                                timeOut: 1500
                            });
                        }
                    }
                });
            }

            function manageApprenantInter(type, idProjet, idApprenant, idEtp) {
                $.ajax({
                    type: type,
                    url: "/projetsEmp/etpInter/" + idProjet + "/" + idApprenant + "/" +
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
                            getApprenantAddedInter({{ $projet->idProjet }});
                            getAllApprPresenceInter({{ $projet->idProjet }});
                            ratyNotation('raty_notation', {{ $noteGeneral }});
                        } else if (res.error) {
                            toastr.error(res.error, 'Erreur', {
                                timeOut: 1500
                            });
                        }
                    }
                });
            }


            function getAllApprPresence(idProjet) {
                var sessions = @json($seances);
                var countDate = @json($countDate);
                var all_appr_presence = $('.getAllApprPresence');
                all_appr_presence.html('');
                let idProj = idProjet;

                $.ajax({
                    type: "get",
                    url: "/employes/projets/apprenants/getApprenantAdded/" + idProjet,
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
                            all_appr_presence.append(`<thead class="headPresence">
                                    </thead>
                                    <tbody class="bodyPresence">
                                      <tr class="text-center heureDebPresence">
                                      </tr>
                                      <tr class="text-center heureFinPresence"></tr>
                                      <tbody class="apprPresence"></tbody>
                                    </tbody>
                                    `)

                            var head_presence = $('.headPresence');
                            head_presence.html(`<x-thh date="Jour" />`);
                            var heure_deb_presence = $('.heureDebPresence');
                            heure_deb_presence.html(`<x-tdd>Heure début</x-tdd>`)
                            var heure_fin_presence = $('.heureFinPresence');
                            heure_fin_presence.html(`<x-tdd>Heure fin</x-tdd>`)
                            var apprenant_list = $('.apprPresence');
                            apprenant_list.html('');

                            $.each(res.apprs, function(j, data) {
                                // Créer la structure HTML pour l'employé
                                let html = `<x-tr class="text-center list_button_${data.idEmploye}">
                                  <td class="p-2 text-left border">
                                      <div class="inline-flex items-center gap-2 w-max">
                                          <input type="hidden" class="inputEmp" value="${data.idEmploye}">
                                          <span class="photo_emp_${data.idEmploye}"></span>
                                          <p class="text-gray-500">${data.emp_name} ${data.emp_firstname}</p>
                                      </div>
                                  </td>
                              </x-tr>`;

                                // Ajouter la structure HTML de l'employé à la liste
                                apprenant_list.append(html);

                                // Sélectionner l'élément de la photo de l'employé
                                let photo_emp = $(`.photo_emp_${data.idEmploye}`);
                                photo_emp.html('');

                                // Ajouter la photo de l'employé s'il y en a une, sinon afficher une initialisation
                                if (data.emp_photo == null) {
                                    if (data.firstname != null) {
                                        photo_emp.append(
                                            `<div class="flex items-center justify-center w-10 h-10 text-gray-600 uppercase bg-gray-200 rounded-full">${data.emp_firstname[0]}</div>`
                                        );
                                    } else {
                                        photo_emp.append(
                                            `<div class="flex items-center justify-center w-10 h-10 text-gray-600 uppercase bg-gray-200 rounded-full">${data.emp_name[0]}</div>`
                                        );
                                    }
                                } else {
                                    photo_emp.append(
                                        `<img src="{{ asset('img/employes/${data.emp_photo}') }}" alt="" class="w-8 h-8 rounded-full border-[1px] border-gray-200 object-cover">`
                                    );
                                }

                                // Sélectionner l'élément où vous souhaitez ajouter les boutons de dates bg-gray-50 hover:bg-gray-100
                                let list_button = $(`.list_button_${data.idEmploye}`);
                                $.each(res.getSeance, function(i_se, v_se) {
                                    list_button.append(
                                        `<x-tdd class="td_emargement_` + v_se.idSeance + `_` +
                                        data.idEmploye +
                                        `" td-se="` +
                                        v_se.idSeance +
                                        `" td-ep='` + data.idEmploye + `'>
                    </x-tdd>`
                                    );
                                    var td_emargement = $('.td_emargement_' + v_se.idSeance + '_' +
                                        data.idEmploye);
                                    td_emargement.html('');

                                    $.each(res.getPresence, function(i_gp, v_gp) {
                                        if (v_gp.idSeance == td_emargement.attr('td-se') &&
                                            v_gp.idEmploye == td_emargement
                                            .attr('td-ep')) {
                                            if (v_gp.isPresent == null) {
                                                td_emargement.append(`<select data-se="` +
                                                    v_gp.idSeance + `" data-ep='` + v_gp
                                                    .idEmploye + `' class="appearance-none main-button w-4 h-4 text-transparent px-2 rounded-md  border-[1px] border-gray-200">
                                              <option id="" class="text-gray-500">-- Selectionner un état --</option>
                                              <option id="present" value="3" class="text-gray-500">Présent</option>
                                              <option id="paritel" value="2" class="text-gray-500">Partiellement Présent</option>
                                              <option id="absent" value="1" class="text-gray-500">Absent</option>
                                              <option id="not" value='0' class="text-gray-500">Non définis</option>
                                            </select>`);
                                            } else {
                                                let color_select = '';
                                                switch (v_gp.isPresent) {
                                                    case 3:
                                                        color_select = 'bg-green-500'
                                                        break;
                                                    case 2:
                                                        color_select = 'bg-amber-500'
                                                        break;
                                                    case 1:
                                                        color_select = 'bg-red-500'
                                                        break;
                                                    case 0:
                                                        color_select = 'bg-gray-500'
                                                        break;

                                                    default:
                                                        color_select = 'bg-gray-50'
                                                        break;
                                                }
                                                td_emargement.append(`<select data-se="` +
                                                    v_gp.idSeance + `" data-ep='` + v_gp
                                                    .idEmploye +
                                                    `' class="appearance-none ` +
                                                    color_select + ` main-button-edit w-4 h-4 text-transparent px-2 rounded-md  border-[1px] border-gray-200">
                                              <option id="" class="text-gray-500">-- Selectionner un état --</option>
                                              <option id="present" value="3" class="text-gray-500">Présent</option>
                                              <option id="paritel" value="2" class="text-gray-500">Partiellement Présent</option>
                                              <option id="absent" value="1" class="text-gray-500">Absent</option>
                                              <option id="not" value='0' class="text-gray-500">Non définis</option>
                                            </select>`);
                                            }
                                        }
                                    });
                                });
                            });

                            const mainButton = $('.main-button');
                            const mainButtonEdit = $('.main-button-edit');
                            let idSe = "";
                            let idEp = "";

                            mainButton.on('change', function() {
                                idSe = $(this).attr('data-se');
                                idEp = $(this).attr('data-ep');
                                isPresent = parseInt($(this).val());
                                switch (isPresent) {
                                    case 3:
                                        $(this).addClass('bg-green-500')
                                        break;
                                    case 2:
                                        $(this).addClass('bg-amber-500')
                                        break;
                                    case 1:
                                        $(this).addClass('bg-red-500')
                                        break;
                                    case 0:
                                        $(this).addClass('bg-gray-500')
                                        break;

                                    default:
                                        $(this).addClass('bg-gray-50')
                                        break;
                                }
                                addPresence(idProj, idSe, idEp, isPresent, 'intra');
                            });

                            mainButtonEdit.on('change', function() {
                                idSe = $(this).attr('data-se');
                                idEp = $(this).attr('data-ep');
                                isPresent = parseInt($(this).val());
                                switch (isPresent) {
                                    case 3:
                                        $(this).addClass('bg-green-500')
                                        break;
                                    case 2:
                                        $(this).addClass('bg-amber-500')
                                        break;
                                    case 1:
                                        $(this).addClass('bg-red-500')
                                        break;
                                    case 0:
                                        $(this).addClass('bg-gray-500')
                                        break;

                                    default:
                                        $(this).addClass('bg-gray-50')
                                        break;
                                }
                                updatePresence(idProj, idSe, idEp, isPresent, 'intra');
                            });


                            $.each(res.countDate, function(i, val) {
                                head_presence.append(`<x-thh colspan="` + val.count + `" date="` + val
                                    .dateSeance + `" />`);
                            });

                            $.each(res.getSeance, function(i, val) {
                                heure_deb_presence.append(`<x-tdd class="text-start">` + val.heureDebut +
                                    `</x-tdd>`);
                                heure_fin_presence.append(`<x-tdd class="text-start">` + val.heureFin +
                                    `</x-tdd>`);
                            })

                            var present_global = $('#present-global');
                            var partiel_global = $('#partiel-global');
                            var absent_global = $('#absent-global');
                            var taux_presence = $('.taux_presence');
                            present_global.html('');
                            partiel_global.html('');
                            absent_global.html('');
                            taux_presence.html('');

                            present_global.append(res.percentPresent);
                            taux_presence.append(res.percentPresent);
                            partiel_global.append(res.percentPartiel);
                            absent_global.append(res.percentAbsent);
                        }
                    }
                });
            }

            function getApprenantAdded(idProjet) {
                var all_appr_project = $('.getApprProject');
                all_appr_project.html('');

                var __etp_content_detail = $('#__etp_content_detail');
                __etp_content_detail.html('');

                $.ajax({
                    type: "get",
                    url: "/employes/projets/apprenants/getApprenantAdded/" + idProjet,
                    dataType: "json",
                    beforeSend: function() {
                        all_appr_project.append(`<span class="initialLoading">Chargement ...</span>`);
                    },
                    complete: function() {
                        $('.initialLoading').remove();
                    },
                    success: function(res) {
                        var all_apprenant_selected = $('#all_apprenant_selected');
                        var get_count_appr = $('.getCountApprProject');
                        var countApprDrawer = $('#countApprDrawer');

                        if (res.getEtps != []) {
                            __etp_content_detail.append(`<div class="flex flex-col w-full bg-white p-3 border-[1px] shadow-sm border-gray-200 rounded-lg">
                                                <div class="inline-flex items-center gap-3">
                                                    <i class="text-lg fa-solid fa-building"></i>
                                                    <h3 class="text-xl font-semibold text-gray-700">Les entreprises</h3>
                                                </div>
                                                <div class="flex flex-col w-full gap-1">
                                                    <span class="inline-flex flex-wrap items-center w-full gap-4 dash_etp">
                                                    </span>
                                                </div>
                                            </div>`);

                            var dash_etp = $('.dash_etp');
                            dash_etp.html('');

                            $.each(res.getEtps, function(i, v) {
                                if (v.etp_logo != null) {
                                    dash_etp.append(
                                        `<div class="relative w-20 h-10 capitalize bg-gray-200 cursor-pointer rounded-xl" title="${v.etp_name}">
                                        <x-icon-badge />
                                        <img onclick="showCustomer(${v.idEtp}, '/employes/etp-drawer/')" src="/img/entreprises/${v.etp_logo}" alt="logo"
                                        class="object-cover w-full h-full cursor-pointer rounded-xl">
                                    </div>`);
                                } else {
                                    dash_etp.append(
                                        `<span onclick="showCustomer(${v.idEtp}, '/employes/etp-drawer/')" class="flex items-center justify-center object-cover w-20 h-10 text-xl font-semibold text-gray-600 uppercase bg-gray-200 rounded-lg cursor-pointer">${v.etp_name[0]}</span>`
                                    );
                                }
                            });

                        } else if (res.getEtps == []) {
                            __etp_content_detail.append(
                                `<h3 class="text-xl font-semibold text-gray-700">Veuillez ajouter des apprenants pour ce projet</h3>`
                            );
                        }

                        all_apprenant_selected.html('');
                        get_count_appr.html('');
                        countApprDrawer.html('');

                        get_count_appr.text(res.apprs.length);
                        countApprDrawer.text(res.apprs.length);

                        if (res.apprs.length <= 0) {
                            all_apprenant_selected.append(`<x-no-data texte="Aucun résultat"></x-no-data>`);
                            all_appr_project.append(`<x-no-data texte="Aucun résultat"></x-no-data>`);
                        } else {

                            /********************Modification nb des Apprenants coté FRONT **********************/

                            let idCustomer = sessionStorage.getItem('ID_CUSTOMER');

                            let details = JSON.parse(sessionStorage.getItem('ACCESS_EVENTS_DETAILS_' +
                                idCustomer)) || [];
                            details.map(item => {
                                if (item.idProjet === idProjet) {
                                    item.nb_appr = res.apprs.length;
                                }
                                return item;
                            });
                            sessionStorage.setItem('ACCESS_EVENTS_DETAILS_' + idCustomer, JSON.stringify(
                                details));

                            /****************************************************************************************/
                            all_appr_project.append(`<table>
                                      <thead>
                                        <x-tr>
                                          <x-th>Nom</x-th>
                                          <x-th class="sm:hidden lg:block">Matricule</x-th>
                                          <x-th class="hidden">Entreprise</x-th>
                                          <x-th class="text-center">Fonction</x-th>
                                          <x-th class="text-center">Avant</x-th>
                                          <x-th class="text-center">Après</x-th>
                                          <x-th class="text-center">Présence</x-th>
                                          <x-th class="text-center"><i class="fa-solid fa-star text-amber-500"></i></x-th>
                                          <x-th class="text-center"></x-th>
                                          <x-th class="text-center"></x-th>
                                        </x-tr>
                                      </thead>
                                      <tbody class="get_all_appr_project"></tbody>
                                    </table>`);

                            $('.get_all_appr_project').html('');
                            $.each(res.apprs, function(k, val) {
                                let firstname = '';
                                let mail = '';
                                let matricule = '';
                                let fonction = '';

                                if (val.emp_firstname != null) {
                                    firstname = val.emp_firstname;
                                }
                                if (val.emp_matricule != null) {
                                    matricule = 'Matricule : ' + val.emp_matricule;
                                }
                                if (val.emp_fonction != null && val.emp_fonction != 'Default' && val
                                    .emp_fonction != '') {
                                    fonction = 'Fonction : ' + val.emp_fonction;
                                }

                                $('.get_all_appr_project').append(`<x-tr class="border-b-[1px] border-gray-50">
                                                    <x-td class="!p-1">
                                                      <div class="inline-flex items-center gap-2">
                                                        <span class="appr_photo_${val.idEmploye}"></span>
                                                        <div class="flex flex-col gap-1">
                                                          <label class="text-base font-normal text-gray-600 cursor-pointer">${val.emp_name} ${firstname}</label>
                                                          <label class="text-sm text-gray-400">${val.etp_name}</label>  
                                                        </div>
                                                      </div>
                                                    </x-td>
                                                    <x-td class="!p-1 sm:hidden lg:block">${(val.emp_matricule != null && val.emp_matricule != '') ? val.emp_matricule : '--'}</x-td>
                                                    <x-td class="!p-1 hidden">
                                                      <label class="text-base text-gray-400">${val.etp_name}</label>
                                                    </x-td>
                                                    <x-td class="!p-1 text-center">
                                                      <label class="text-base text-gray-400">${(val.emp_fonction != null && val.emp_fonction != 'Default') ? val.emp_fonction : '--'}</label>
                                                    </x-td>
                                                    <x-td class="!p-1 text-center">
                                                      <label class="text-base text-gray-400">--</label>
                                                    </x-td>
                                                    <x-td class="!p-1 text-center">
                                                      <label class="text-base text-gray-400">--</label>
                                                    </x-td>
                                                    <x-td class="!p-1 text-center">
                                                      <div class="flex items-center justify-center w-full">
                                                        <div
                                                          class="uniquePresence_${val.idEmploye} w-3 h-3 rounded-full">
                                                        </div>
                                                      </div>
                                                    </x-td>
                                                    <x-td class="!p-1 text-center">
                                                      <label class="text-base text-gray-400 appreciation_${val.idEmploye}"></label>
                                                    </x-td>
                                                    <x-td class="!p-1 text-end">
                                                      <button onclick="getEvaluation({{ $projet->idProjet }}, ${val.idEmploye})" class="text-right text-purple-500 underline cursor-pointer">Evaluation</button>
                                                    </x-td>
                                                    <x-td class="!p-1 text-center">
                                                      <button onclick="getSkills({{ $projet->idProjet }}, ${val.idEmploye})" class="text-purple-500 underline cursor-pointer">Skill Matrix</button>
                                                    </x-td>
                                                  </x-tr>`);

                                showEvalTable({{ $projet->idProjet }}, val.idEmploye);
                                getPresenceUnique({{ $projet->idProjet }}, val.idEmploye);

                                let emp_eval = $('#emp_eval_' + val.idEmploye);
                                emp_eval.html('');

                                let content_eval = $('#content_eval_' + val.idEmploye);
                                content_eval.html('');

                                emp_eval.append(`
              <div class="inline-flex items-center gap-2">
                <span class="appr_photo_` + val.idEmploye + `"></span>
                <div class="flex flex-col gap-1">
                  <label class="text-base font-normal text-gray-600 cursor-pointer">` + val.emp_name + ` ` +
                                    firstname + `</label>
                  <label class="text-sm text-gray-400">` + val.etp_name + `</label>  
                </div>
              </div>`);

                                var appr_photo = $('.appr_photo_' + val.idEmploye);
                                appr_photo.html('');

                                if (val.emp_photo == null) {
                                    if (val.emp_firstname != null) {
                                        appr_photo.append(
                                            `<div class="flex items-center justify-center w-10 h-10 text-gray-600 uppercase bg-gray-200 rounded-full">${val.emp_firstname[0]}</div>`
                                        );
                                    } else {
                                        appr_photo.append(
                                            `<div class="flex items-center justify-center w-10 h-10 text-gray-600 uppercase bg-gray-200 rounded-full">${val.emp_name[0]}</div>`
                                        );
                                    }
                                } else {
                                    appr_photo.append(
                                        `<img src="{{ asset('img/employes/${val.emp_photo}') }}" alt="" class="w-10 h-10 rounded-full border-[1px] border-gray-200 object-cover">`
                                    );
                                }

                                all_apprenant_selected.append(`<li
                                                class="grid grid-cols-5 w-full px-3 py-2 border-[1px] border-gray-100 cursor-pointer hover:bg-gray-50 duration-200 rounded-md !bg-white">
                                                <div class="col-span-4">
                                                  <div class="inline-flex items-center gap-2">
                                                    <span id="photo_appr_added_` + val.idEmploye + `"></span>
                                                    <div class="flex flex-col gap-0">
                                                      <p class="text-base font-normal text-gray-700">` + val.emp_name +
                                    ` ` + firstname + `</p>
                                                      <div class="flex flex-col">
                                                        <p class="text-sm text-gray-400 normal-case">` + matricule + `</p>
                                                        <p class="text-sm text-gray-400 normal-case">` + fonction + `</p>
                                                        <p class="text-sm text-gray-400 normal-case">Entreprise : ` +
                                    val
                                    .etp_name + `</p>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>
                                                <div class="grid items-center justify-center w-full col-span-1">
                                                  <div
                                                    onclick="manageApprenant('delete', {{ $projet->idProjet }}, ` +
                                    val.idEmploye + `)"
                                                    class="flex items-center justify-center w-10 h-10 duration-150 bg-red-100 rounded-full cursor-pointer icon hover:bg-red-50 group/icon">
                                                    <i class="text-sm text-red-500 duration-150 fa-solid fa-minus group-hover/icon:text-red-600"></i>
                                                  </div>
                                                </div>
                                              </li>`);

                                var photo_appr = $('#photo_appr_added_' + val.idEmploye);
                                photo_appr.html('');

                                if (val.emp_photo == "" || val.emp_photo == null) {
                                    photo_appr.append(
                                        `<div  class="flex items-center justify-center w-10 h-10 text-gray-500 uppercase bg-gray-200 rounded-full">` +
                                        val.emp_initial_name + `</div>`);
                                } else {
                                    photo_appr.append(`<img
                                    src="/img/employes/` + val.emp_photo + `"
                                    alt="Avatar" class="object-cover w-10 h-10 mr-4 rounded-full">`);
                                }
                            });
                        }
                    }
                });
            }

            // {{-- SCRIPT FORMATEUR --}}
            function getAllForms() {
                $.ajax({
                    type: "get",
                    url: "/employes/forms/getAllForms",
                    dataType: "json",
                    success: function(res) {
                        var all_form = $('#all_form');
                        all_form.html('');

                        if (res.forms.length <= 0) {
                            all_form.append(`<x-no-data texte="Pas de données"></x-no-data>`);
                        } else {
                            $.each(res.forms, function(key, val) {
                                all_form.append(`<li
                                    class="grid grid-cols-5 w-full gap-2 justify-between px-3 py-2 border-[1px] border-gray-100 cursor-pointer hover:bg-gray-50 duration-200 rounded-md !bg-white">
                                    <div class="col-span-4">
                                      <div class="inline-flex items-center gap-2">
                                        <span id="photo_form_` + val.idFormateur + `"></span>
                                        <div class="flex flex-col gap-0">
                                          <p class="text-base font-normal text-gray-700">` + val.form_name + ` ` + val
                                    .form_first_name + `</p>
                                          <p class="text-sm text-gray-400 lowercase">` + val.form_email + `</p>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="grid items-center justify-center w-full col-span-1">
                                      <div
                                        onclick="manageForm('post', {{ $projet->idProjet }}, ` + val.idFormateur + `)"
                                        class="flex items-center justify-center w-10 h-10 duration-150 bg-green-100 rounded-full cursor-pointer icon hover:bg-green-50 group/icon">
                                        <i class="text-sm text-green-500 duration-150 fa-solid fa-plus group-hover/icon:text-green-600"></i>
                                        <input type="hidden" id="prenom_form_` + val.idFormateur + `" value=` + val
                                    .form_first_name + `>
                                      </div>
                                    </div>
                                  </li>`);

                                var photo_form = $('#photo_form_' + val.idFormateur);
                                photo_form.html('');

                                if (val.photo_form == "" || val.form_photo == null) {
                                    photo_form.append(
                                        `<div  class="flex items-center justify-center w-10 h-10 text-gray-500 uppercase bg-gray-200 rounded-full">` +
                                        val.form_initial_name + `</div>`);
                                } else {
                                    photo_form.append(`<img
                                  src="/img/formateurs/` + val.form_photo + `"
                                  alt="Avatar" class="object-cover w-10 h-10 mr-4 rounded-full">`);
                                }
                            });
                        }
                    }
                });
            }


            function getFormAdded(idProjet) {
                $.ajax({
                    type: "get",
                    url: "/employes/projets/" + idProjet + "/getFormAdded",
                    dataType: "json",
                    success: function(res) {
                        var get_project_form = $('.get_project_form');
                        var all_form_selected = $('#all_form_selected');

                        get_project_form.html('');
                        all_form_selected.html('');

                        if (res.forms.length <= 0) {
                            get_project_form.append(
                                `<span class="text-gray-500">Aucun formateur n'est assigné.</span>`);
                            all_form_selected.append(`<x-no-data texte="Pas de données"></x-no-data>`);
                        } else {
                            $.each(res.forms, function(k, val) {
                                get_project_form.append(`
                        <div class="w-full grid grid-cols-3 p-2 border-[1px] border-gray-200 rounded-md bg-white">
                            <div class="grid col-span-1">
                                <span id="photo_formateur_` + val.idFormateur + `"></span>
                            </div>

                            <div class="grid col-span-2 grid-cols-subgrid">
                                <div class="flex flex-col gap-1">
                                    <span class="text-gray-700">` + val.form_name + ` ` + val.form_firstname + `</span>
                                    <button onclick="viewMiniCV(${val.idFormateur})" class="hover:text-inherit focus:outline-none ml-3 inline-flex items-center gap-2 cursor-pointer text-[#A462A4] underline underline-offset-2 transition duration-200">Voir le mini-CV</button>
                                </div>
                            </div>
                        </div>`);

                                all_form_selected.append(`
                        <li class="grid grid-cols-5 w-full gap-2 justify-between px-3 py-2 border-[1px] border-gray-100 cursor-pointer hover:bg-gray-50 duration-200 rounded-md !bg-white">
                            <div class="col-span-4">
                                <div class="inline-flex items-center gap-2">
                                    <span id="photo_form_added_` + val.idFormateur + `"></span>
                                    <div class="flex flex-col gap-0">
                                        <p class="text-base font-normal text-gray-700">` + val.form_name + ` ` + val
                                    .form_firstname + `</p>
                                        <p class="text-sm text-gray-400 lowercase">` + val.form_email + `</p>
                                    </div>
                                </div>
                            </div>
                            <div class="grid items-center justify-center w-full col-span-1">
                                <div onclick="manageForm('delete', {{ $projet->idProjet }}, ` + val.idFormateur + `)" class="flex items-center justify-center w-10 h-10 duration-150 bg-red-100 rounded-full cursor-pointer icon hover:bg-red-50 group/icon">
                                    <i class="text-sm text-red-500 duration-150 fa-solid fa-minus group-hover/icon:text-red-600"></i>
                                </div>
                            </div>
                        </li>`);
                                var photo_form = $('#photo_form_added_' + val.idFormateur);
                                photo_form.html('');

                                if (val.form_photo == "" || val.form_photo == null) {
                                    photo_form.append(
                                        `<div class="flex items-center justify-center w-10 h-10 text-gray-500 uppercase bg-gray-200 rounded-full">` +
                                        val.form_initial_name + `</div>`);
                                } else {
                                    photo_form.append(`<img src="/img/formateurs/` + val.form_photo +
                                        `" alt="Avatar" class="object-cover w-10 h-10 mr-4 rounded-full">`
                                    );
                                }

                                var photo_formateur = $('#photo_formateur_' + val.idFormateur);
                                photo_formateur.html('');

                                if (val.form_photo == "" || val.form_photo == null) {
                                    photo_formateur.append(
                                        `<div class="flex items-center justify-center text-gray-500 uppercase bg-gray-200 rounded-full w-14 h-14">` +
                                        val.form_initial_name + `</div>`);
                                } else {
                                    photo_formateur.append(`<img src="/img/formateurs/` + val.form_photo +
                                        `" alt="Avatar" class="object-cover rounded-full w-14 h-14">`);
                                }
                            });
                        }
                    }
                });
            }

            function showEvalTable(idProjet, idEmploye) {
                $.ajax({
                    type: "get",
                    url: "/employe/projet/evaluation/checkEval/" + idProjet + "/" + idEmploye,
                    dataType: "json",
                    success: function(res) {

                        let appriciation = $('.appreciation_' + idEmploye);
                        appriciation.html('');

                        if (res.checkEval <= 0) {
                            appriciation.text('--');
                        } else {
                            $.each(res.one, function(i, v_o) {
                                appriciation.text(v_o.generalApreciate);
                            });
                        }
                    }
                });
            }

            function getSkills(idProjet, idEmploye) {

                var modal_content_master = $('#modal_content_master');
                modal_content_master.html('');

                modal_content_master.append(`
                <div class="modal fade" tabindex="-1" id="myModal" data-bs-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                        <div class="bg-gray-100 modal-header">
                            <h5 class="text-xl font-semibold text-gray-700 modal-title">Skill matrix</h5>
                            <button type="button" class="text-white" data-bs-dismiss="modal" aria-label="Close">X</button>
                        </div>
                        <div class="modal-body">
                            <div class="inline-flex items-center justify-between w-full">
                                <div class="flex flex-col items-start">
                                    <p class="text-lg font-medium text-gray-500">AVANT</p>
                                    <div class="inline-flex items-center justify-start gap-1 formQuestion_">
                                        <div class="w-[60%]">
                                        <input type="hidden" name="idProjet" value="{{ $projet->idProjet }}">
                                        <input type="hidden" name="idEmploye" value="${idEmploye}">
                                        <input type="hidden" name="idQuestion[]" value="">
                                        <p class="text-base text-gray-700 pQuestion" data-val="1"></p>
                                        </div>
                                        <div class="inline-flex items-center gap-2 heat-rating">
                                        <div class="flex items-center justify-center w-20 cursor-pointer rating-block_ one" data-value="1">1</div>
                                        <div class="flex items-center justify-center w-20 cursor-pointer rating-block_ two" data-value="2">2</div>
                                        <div class="flex items-center justify-center w-20 cursor-pointer rating-block_ three" data-value="3">3</div>
                                        <div class="flex items-center justify-center w-20 cursor-pointer rating-block_ four" data-value="4">4</div>
                                        <div class="flex items-center justify-center w-20 cursor-pointer rating-block_ five" data-value="5">5</div>
                                        <div class="text-transparent ratings_">0</div>
                                        <input type="hidden" value="5" name="eval_note[]" id="ratings-input_">
                                        </div>
                                        <div class="flex flex-col gap-0 w-[40%]">
                                        <div class="inline-flex items-center justify-end w-full gap-2">
                                            <label class="w-full text-base font-semibold text-right text-gray-400 note_"></label>
                                        </div>
                                        </div>
                                    </div> 
                                </div>

                                <div class="flex flex-col items-start">
                                    <p class="text-lg font-medium text-gray-500">APRES</p>
                                    <div class="inline-flex items-center justify-start gap-1 formQuestion_">
                                        <div class="w-[60%]">
                                        <input type="hidden" name="idProjet" value="{{ $projet->idProjet }}">
                                        <input type="hidden" name="idEmploye" value="${idEmploye}">
                                        <input type="hidden" name="idQuestion[]" value="">
                                        <p class="text-base text-gray-700 pQuestion" data-val="1"></p>
                                        </div>
                                        <div class="inline-flex items-center gap-2 heat-rating">
                                        <div class="flex items-center justify-center w-20 cursor-pointer rating-block_ one" data-value="1">1</div>
                                        <div class="flex items-center justify-center w-20 cursor-pointer rating-block_ two" data-value="2">2</div>
                                        <div class="flex items-center justify-center w-20 cursor-pointer rating-block_ three" data-value="3">3</div>
                                        <div class="flex items-center justify-center w-20 cursor-pointer rating-block_ four" data-value="4">4</div>
                                        <div class="flex items-center justify-center w-20 cursor-pointer rating-block_ five" data-value="5">5</div>
                                        <div class="text-transparent ratings_">0</div>
                                        <input type="hidden" value="5" name="eval_note[]" id="ratings-input_">
                                        </div>
                                        <div class="flex flex-col gap-0 w-[40%]">
                                        <div class="inline-flex items-center justify-end w-full gap-2">
                                            <label class="w-full text-base font-semibold text-right text-gray-400 note_"></label>
                                        </div>
                                        </div>
                                    </div> 
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <x-btn-ghost>Annuler</x-btn-ghots>
                            <x-btn-primary>Confirmer</x-btn-primary>
                        </div>
                        </div>
                    </div>
                </div>`);

                (function() {
                    var ratingBlocks = $('.rating-block_');
                    var totalBlocks = ratingBlocks.length;
                    var ratings = $('.ratings_');
                    ratingBlocks.click(function() {
                        var rating = parseFloat($(this).attr(
                            'data-value'));
                        ratingBlocks.css('opacity', '0.2');
                        ratings.html($(this).attr(
                            'data-value'));
                        $('#ratings-input_')
                            .val(rating);
                        for (var i = 0; i < totalBlocks; i++) {
                            var everyEle = ratingBlocks.eq(i);
                            if (parseFloat(everyEle.attr(
                                    'data-value')) == rating) {
                                everyEle.css('opacity', 1);
                            }
                        }
                    });
                })();

                var myModalEl = $('#myModal');
                var modal = new bootstrap.Modal(myModalEl);
                modal.show();
            }

            function getEvaluation(idProjet, idEmploye) {
                let drawer_eval = $('#drawer_eval');
                drawer_eval.html('');

                drawer_eval.append(
                    `<x-drawer-evaluation idProjet="{{ $projet->idProjet }}" id="${idEmploye}"></x-drawer-evaluation>`);

                let offcanvasEvaluation = $('#offcanvasEvaluation_' + idEmploye);

                var bsOffcanvas = new bootstrap.Offcanvas(offcanvasEvaluation);

                $.ajax({
                    type: "get",
                    url: "/employe/projet/evaluation/checkEval/" + idProjet + "/" + idEmploye,
                    dataType: "json",
                    success: function(res) {

                        var form_method = $('#form_method_' + idEmploye)
                        form_method.attr('action', '/employe/projet/evaluation/chaud');

                        let content_eval = $('#content_eval_' + idEmploye);
                        content_eval.html('');


                        let examiner_eval = $('#examiner_eval_' + idEmploye);
                        examiner_eval.html('');

                        let general = $('.general_' + idEmploye);
                        general.html('');

                        let val_comment = $('.val_comment_' + idEmploye);
                        val_comment.html('');

                        let com1 = $('.com1_' + idEmploye);
                        com1.html('');

                        let com2 = $('.com2_' + idEmploye);
                        com2.html('');

                        let btn_eval = $('.btn_submit_eval_' + idEmploye);
                        btn_eval.html('');

                        let appriciation = $('.appreciation_' + idEmploye);
                        appriciation.html('');

                        let check_examiner = $('.examiner_eval_check_' + idEmploye);
                        check_examiner.html('');

                        let modif_eval = $('#modif_eval_' + idEmploye);
                        modif_eval.html('');

                        if (res.checkEval <= 0) {
                            $.each(res.typeQuestions, function(i, v_type) {
                                if (v_type.idTypeQuestion != 5) {

                                    content_eval.append(`<div class="p-4 rounded-md border-[1px] border-gray-200 border-dashed flex flex-col gap-2">
          <div class="inline-flex items-center w-full gap-4">
            <label class="text-xl font-semibold text-gray-700 type_` + v_type.idTypeQuestion + `">` +
                                        v_type
                                        .typeQuestion + `</label>
            <div class="inline-flex items-center gap-3">
              <label id="total_1" class="text-base font-bold text-gray-500"></label>
            </div>
          </div>
          <div id="eval_type_` + v_type.idTypeQuestion + '_' + idEmploye + `" class="flex flex-col gap-1"></div>
          </div>`);
                                }

                                let eval_type = $('#eval_type_' + v_type.idTypeQuestion + '_' +
                                    idEmploye);
                                eval_type.html('');

                                $.each(res.questions, function(i, v_eval) {

                                    if (v_eval.idTypeQuestion == v_type.idTypeQuestion) {
                                        eval_type.append(`<div class="inline-flex items-center gap-1 w-full formQuestion_${v_eval.idQuestion}">
                            <div class="w-[60%]">
                              <input type="hidden" name="idProjet" value="{{ $projet->idProjet }}">
                              <input type="hidden" name="idEmploye" value="${idEmploye}">
                              <input type="hidden" name="idQuestion[]" value="${v_eval.idQuestion}">
                              <p class="text-base text-gray-700 pQuestion" data-val="1">${v_eval.question}</p>
                            </div>
                            <div class="inline-flex items-center gap-2 heat-rating">
                              <div class="rating-block_${v_eval.idQuestion} w-20 flex items-center cursor-pointer justify-center one" data-value="1">1</div>
                              <div class="rating-block_${v_eval.idQuestion} w-20 flex items-center cursor-pointer justify-center two" data-value="2">2</div>
                              <div class="rating-block_${v_eval.idQuestion} w-20 flex items-center cursor-pointer justify-center three" data-value="3">3</div>
                              <div class="rating-block_${v_eval.idQuestion} w-20 flex items-center cursor-pointer justify-center four" data-value="4">4</div>
                              <div class="rating-block_${v_eval.idQuestion} w-20 flex items-center cursor-pointer justify-center five" data-value="5">5</div>
                              <div class="ratings_${v_eval.idQuestion} text-transparent">0</div>
                              <input type="hidden" value="5" name="eval_note[]" id="ratings-input_${v_eval.idQuestion}">
                            </div>
                            <div class="flex flex-col gap-0 w-[40%]">
                              <div class="inline-flex items-center justify-end w-full gap-2">
                                <label class="text-gray-400 text-base font-semibold w-full text-right note_${v_eval.idQuestion}"></label>
                              </div>
                            </div>
                          </div>`);
                                    }

                                    (function() {
                                        var ratingBlocks = $('.rating-block_' + v_eval
                                            .idQuestion);
                                        var totalBlocks = ratingBlocks.length;
                                        var ratings = $('.ratings_' + v_eval.idQuestion);
                                        ratingBlocks.click(function() {
                                            var rating = parseFloat($(this).attr(
                                                'data-value'));
                                            ratingBlocks.css('opacity', '0.2');
                                            ratings.html($(this).attr(
                                                'data-value'));
                                            $('#ratings-input_' + v_eval.idQuestion)
                                                .val(rating);
                                            for (var i = 0; i < totalBlocks; i++) {
                                                var everyEle = ratingBlocks.eq(i);
                                                if (parseFloat(everyEle.attr(
                                                        'data-value')) == rating) {
                                                    everyEle.css('opacity', 1);
                                                }
                                            }
                                        });
                                    })();
                                });
                            });

                            general.append(`<div class="inline-flex items-center gap-2 heat-rating">
                    <div class="flex items-center justify-center w-20 cursor-pointer rating-block-note one" data-value="1">1</div>
                    <div class="flex items-center justify-center w-20 cursor-pointer rating-block-note two" data-value="2">2</div>
                    <div class="flex items-center justify-center w-20 cursor-pointer rating-block-note three" data-value="3">3</div>
                    <div class="flex items-center justify-center w-20 cursor-pointer rating-block-note four" data-value="4">4</div>
                    <div class="flex items-center justify-center w-20 cursor-pointer rating-block-note five" data-value="5">5</div>
                    <div class="text-transparent ratings">0</div>
                    <input type="hidden" value="5" name="generalApreciate" id="ratings-input-note">
                  </div>`);

                            (function() {
                                var ratingBlocks = $('.rating-block-note');
                                var totalBlocks = ratingBlocks.length;
                                var ratings = $('.ratings');
                                ratingBlocks.click(function() {
                                    var rating = parseFloat($(this).attr('data-value'));
                                    ratingBlocks.css('opacity', '0.2');
                                    ratings.html($(this).attr('data-value'));
                                    $('#ratings-input-note').val(rating);
                                    for (var i = 0; i < totalBlocks; i++) {
                                        var everyEle = ratingBlocks.eq(i);
                                        if (parseFloat(everyEle.attr('data-value')) == rating) {
                                            everyEle.css('opacity', 1);
                                        }
                                    }
                                });
                            })();

                            val_comment.append(`<x-input type="textarea" name="idValComment" />`);
                            com1.append(`<x-input type="textarea" name="com1" />`);
                            com2.append(
                                `<x-input type="textarea" name="com2" />`);

                            btn_eval.append(`<div class="inline-flex items-center justify-end w-full pt-2">
                    <x-btn-primary type="submit">Valider mes réponses</x-btn-primary>
                  </div>`);
                        } else {
                            $.each(res.typeQuestions, function(i, v_type) {
                                if (v_type.idTypeQuestion != 5) {

                                    content_eval.append(`<div class="p-4 rounded-md border-[1px] border-gray-200 border-dashed flex flex-col gap-2">
          <div class="inline-flex items-center w-full gap-4">
            <label class="text-xl font-semibold text-gray-700 type_` + v_type.idTypeQuestion + `">` +
                                        v_type
                                        .typeQuestion + `</label>
            <div class="inline-flex items-center gap-3">
              <label id="total_1" class="text-base font-bold text-gray-500"></label>
            </div>
          </div>
          <div id="eval_type_` + v_type.idTypeQuestion + '_' + idEmploye + `" class="flex flex-col gap-1"></div>
          </div>`);
                                }

                                let eval_type = $('#eval_type_' + v_type.idTypeQuestion + '_' +
                                    idEmploye);
                                eval_type.html('');

                                $.each(res.questions, function(i, v_eval) {

                                    if (v_eval.idTypeQuestion == v_type.idTypeQuestion) {
                                        eval_type.append(`<div class="inline-flex items-center gap-1 w-full formQuestion_${v_eval.idQuestion}">
                            <div class="w-[60%]">
                                <input type="hidden" name="idProjet" value="{{ $projet->idProjet }}">
                                <input type="hidden" name="idEmploye" value="` + idEmploye + `">
                                <input type="hidden" name="idQuestion[]" value="` + v_eval.idQuestion + `">
                                <p class="text-base text-gray-700 pQuestion" data-val=1>${v_eval.question}</p>
                            </div>
                            <div class="inline-flex items-center gap-2 heat-rating">
                              <div class="rating-block_${v_eval.idQuestion} w-10 flex items-center cursor-pointer justify-center note_val_${v_eval.idQuestion}"></div>
                            </div>
                            </p>
                            <div class="flex flex-col gap-0 w-[40%]">
                                <div class="inline-flex items-center justify-end w-full gap-2">
                                    <label class="text-gray-400 text-base font-semibold w-full text-right note_${v_eval.idQuestion}"></label>
                                </div>
                            </div>
                        </div>`);
                                    }

                                    let note_val = $('.note_val_' + v_eval.idQuestion);
                                    note_val.html('');
                                    let _note = $('.note_' + v_eval.idQuestion);
                                    _note.html('');

                                    $.each(res.notes, function(i_n, v_n) {
                                        if (v_eval.idQuestion == v_n.idQuestion) {
                                            note_val.text(v_n.note);
                                            switch (v_n.note) {
                                                case 1:
                                                    _note.text('Insatisfaisant')
                                                    note_val.addClass('one')
                                                    break;
                                                case 2:
                                                    _note.text('Faible')
                                                    note_val.addClass('two')
                                                    break;
                                                case 3:
                                                    _note.text('Moyen')
                                                    note_val.addClass('three')
                                                    break;
                                                case 4:
                                                    _note.text('Bien')
                                                    note_val.addClass('four')
                                                    break;
                                                case 5:
                                                    _note.text('Excellent')
                                                    note_val.addClass('five')
                                                    break;

                                                default:
                                                    break;
                                            }
                                        }
                                    });
                                });
                            });

                            $.each(res.one, function(i, v_o) {
                                general.append(`<div class="inline-flex items-center gap-2 heat-rating">
                      <div id="raty_notation_${v_o.idEmploye}" class="inline-flex items-center gap-2"></div>
                    </div>`);

                                val_comment.append(`<p class="text-gray-700">` + v_o.idValComment +
                                    `</p>`);

                                com1.append(`<p class="text-gray-700">` + v_o.com1 + `</p>`);
                                com2.append(`<p class="text-gray-700">` + v_o.com2 + `</p>`);

                                appriciation.text(v_o.generalApreciate);

                                $('#raty_notation_' + v_o.idEmploye).raty({
                                    score: v_o.generalApreciate,
                                    space: false,
                                    readOnly: true
                                });

                                $('#raty_notation_' + v_o.idEmploye + ' img').addClass(`w-5 h-5`);
                            });


                            $.each(res.examiner, function(i, v_ex) {
                                check_examiner.text("Fiche d'évaluation remplie par ");
                                if (v_ex.firstname_examiner != null) {
                                    examiner_eval.text(v_ex.name_examiner + ' ' + v_ex
                                        .firstname_examiner);
                                } else {
                                    examiner_eval.text(v_ex.name_examiner);
                                }
                            });

                            modif_eval.append(
                                `<x-btn-primary onclick="editEval(${idProjet}, ${idEmploye})">Modifier la fiche</x-btn-primary>`
                            )
                        }
                    }
                });

                bsOffcanvas.show();

            }

            function getPresenceUnique(idProjet, idEmploye) {
                $.ajax({
                    type: "get",
                    url: "/employe/projet/evaluation/checkPresence/" + idProjet + "/" + idEmploye,
                    dataType: "json",
                    success: function(res) {
                        var unique = $('.uniquePresence_' + idEmploye);

                        switch (res.checking) {
                            case 3:
                                unique.addClass('bg-green-500')
                                unique.attr('title', 'Toujours présent')
                                break;
                            case 2:
                                unique.addClass('bg-amber-500')
                                unique.attr('title', 'Absent ou paritellement présent au moins une fois')
                                break;
                            case 1:
                                unique.addClass('bg-red-500')
                                unique.attr('title', 'Toujours absent')
                                break;

                            default:
                                unique.addClass('bg-gray-700')
                                unique.attr('title', 'Présence non définie')
                                break;
                        }
                    }
                });
            }

            function editEval(idProjet, idEmploye) {
                $.ajax({
                    type: "get",
                    url: "/employe/projet/evaluation/checkEval/" + idProjet + "/" + idEmploye,
                    dataType: "json",
                    success: function(res) {

                        console.log(res);
                        var form_method = $('#form_method_' + idEmploye)
                        var _method = $('._method_' + idEmploye)
                        form_method.attr('action', '/employe/projet/evaluation/editEval');

                        _method.append(`@method('patch')`);

                        let content_eval = $('#content_eval_' + idEmploye);
                        content_eval.html('');


                        let examiner_eval = $('#examiner_eval_' + idEmploye);
                        examiner_eval.html('');

                        let general = $('.general_' + idEmploye);
                        general.html('');

                        let val_comment = $('.val_comment_' + idEmploye);
                        val_comment.html('');

                        let com1 = $('.com1_' + idEmploye);
                        com1.html('');

                        let com2 = $('.com2_' + idEmploye);
                        com2.html('');

                        let btn_eval = $('.btn_submit_eval_' + idEmploye);
                        btn_eval.html('');

                        let appriciation = $('.appreciation_' + idEmploye);
                        appriciation.html('');

                        let check_examiner = $('.examiner_eval_check_' + idEmploye);
                        check_examiner.html('');

                        let modif_eval = $('#modif_eval_' + idEmploye);
                        modif_eval.html('');

                        $.each(res.typeQuestions, function(i, v_type) {
                            if (v_type.idTypeQuestion != 5) {

                                content_eval.append(`<div class="p-4 rounded-md border-[1px] border-gray-200 border-dashed flex flex-col gap-2">
                    <div class="inline-flex items-center w-full gap-4">
                      <label class="text-xl font-semibold text-gray-700 type_` + v_type.idTypeQuestion + `">` + v_type
                                    .typeQuestion + `</label>
                      <div class="inline-flex items-center gap-3">
                        <label id="total_1" class="text-base font-bold text-gray-500"></label>
                      </div>
                    </div>
                    <div id="eval_type_` + v_type.idTypeQuestion + '_' + idEmploye + `" class="flex flex-col gap-1"></div>
                    </div>`);
                            }

                            let eval_type = $('#eval_type_' + v_type.idTypeQuestion + '_' +
                                idEmploye);
                            eval_type.html('');

                            $.each(res.questions, function(i, v_eval) {
                                if (v_eval.idTypeQuestion == v_type.idTypeQuestion) {
                                    eval_type.append(`<div class="inline-flex items-center gap-1 w-full formQuestion_${v_eval.idQuestion}">
                                      <div class="w-[60%]">
                                        <input type="hidden" name="idProjet" value="{{ $projet->idProjet }}">
                                        <input type="hidden" name="idEmploye" value="${idEmploye}">
                                        <input type="hidden" name="idQuestion[]" value="${v_eval.idQuestion}">
                                        <p class="text-base text-gray-700 pQuestion" data-val="1">${v_eval.question}</p>
                                      </div>
                                      <div class="inline-flex items-center gap-2 heat-rating">
                                        <div class="rating-block_${v_eval.idQuestion} w-20 flex items-center cursor-pointer justify-center one" data-value="1">1</div>
                                        <div class="rating-block_${v_eval.idQuestion} w-20 flex items-center cursor-pointer justify-center two" data-value="2">2</div>
                                        <div class="rating-block_${v_eval.idQuestion} w-20 flex items-center cursor-pointer justify-center three" data-value="3">3</div>
                                        <div class="rating-block_${v_eval.idQuestion} w-20 flex items-center cursor-pointer justify-center four" data-value="4">4</div>
                                        <div class="rating-block_${v_eval.idQuestion} w-20 flex items-center cursor-pointer justify-center five" data-value="5">5</div>
                                        <div class="ratings_${v_eval.idQuestion} text-transparent">0</div>
                                        <input type="hidden" value="" name="eval_note[]" id="ratings-input_${v_eval.idQuestion}">
                                      </div>
                                      <div class="flex flex-col gap-0 w-[40%]">
                                        <div class="inline-flex items-center justify-end w-full gap-2">
                                          <label class="text-gray-400 text-base font-semibold w-full text-right note_${v_eval.idQuestion}"></label>
                                        </div>
                                      </div>
                                    </div>`);
                                }

                                (function() {
                                    var ratingBlocks = $('.rating-block_' + v_eval
                                        .idQuestion);
                                    var totalBlocks = ratingBlocks.length;
                                    var ratings = $('.ratings_' + v_eval.idQuestion);
                                    var rating = null;

                                    $.each(res.notes, function(i_nt, v_nt) {
                                        if (v_nt.idQuestion == v_eval.idQuestion) {
                                            rating = v_nt.note;
                                        }
                                    });

                                    ratingBlocks.css('opacity', '0.2');
                                    ratings.html($(this).attr('data-value'));
                                    $('#ratings-input_' + v_eval.idQuestion).val(rating);
                                    for (var i = 0; i < totalBlocks; i++) {
                                        var everyEle = ratingBlocks.eq(i);
                                        if (parseFloat(everyEle.attr('data-value')) ==
                                            rating) {
                                            everyEle.css('opacity', 1);
                                        }
                                    }

                                    ratingBlocks.click(function() {
                                        var rating = parseFloat($(this).attr(
                                            'data-value'));
                                        ratingBlocks.css('opacity', '0.2');
                                        ratings.html($(this).attr('data-value'));
                                        $('#ratings-input_' + v_eval.idQuestion)
                                            .val(
                                                rating);
                                        for (var i = 0; i < totalBlocks; i++) {
                                            var everyEle = ratingBlocks.eq(i);
                                            if (parseFloat(everyEle.attr(
                                                    'data-value')) == rating) {
                                                everyEle.css('opacity', 1);
                                            }
                                        }
                                    });
                                })();
                            });
                        });

                        general.append(`<div class="inline-flex items-center gap-2 heat-rating">
                              <div class="flex items-center justify-center w-20 cursor-pointer rating-block-note one" data-value="1">1</div>
                              <div class="flex items-center justify-center w-20 cursor-pointer rating-block-note two" data-value="2">2</div>
                              <div class="flex items-center justify-center w-20 cursor-pointer rating-block-note three" data-value="3">3</div>
                              <div class="flex items-center justify-center w-20 cursor-pointer rating-block-note four" data-value="4">4</div>
                              <div class="flex items-center justify-center w-20 cursor-pointer rating-block-note five" data-value="5">5</div>
                              <div class="text-transparent ratings">0</div>
                              <input type="hidden" value="5" name="generalApreciate" id="ratings-input-note">
                            </div>`);

                        (function() {
                            var ratingBlocks = $('.rating-block-note');
                            var totalBlocks = ratingBlocks.length;
                            var ratings = $('.ratings');
                            var rating = null;

                            $.each(res.one, function(i_one, v_one) {
                                if (v_one.idEmploye == idEmploye) {
                                    rating = v_one.generalApreciate;
                                }
                            });

                            ratingBlocks.css('opacity', '0.2');
                            ratings.html($(this).attr('data-value'));
                            $('#ratings-input-note').val(rating);
                            for (var i = 0; i < totalBlocks; i++) {
                                var everyEle = ratingBlocks.eq(i);
                                if (parseFloat(everyEle.attr('data-value')) == rating) {
                                    everyEle.css('opacity', 1);
                                }
                            }

                            ratingBlocks.click(function() {
                                var rating = parseFloat($(this).attr('data-value'));
                                ratingBlocks.css('opacity', '0.2');
                                ratings.html($(this).attr('data-value'));
                                $('#ratings-input-note').val(rating);
                                for (var i = 0; i < totalBlocks; i++) {
                                    var everyEle = ratingBlocks.eq(i);
                                    if (parseFloat(everyEle.attr('data-value')) == rating) {
                                        everyEle.css('opacity', 1);
                                    }
                                }
                            });
                        })();

                        val_comment.append(
                            `<x-input class="val_comment" type="textarea" name="idValComment" />`);

                        com1.append(`<x-input class="com1" type="textarea" name="com1" />`);
                        com2.append(`<x-input type="textarea" name="com2" class="com2" />`);

                        var inputCom1 = $('.com1');
                        var inputCom2 = $('.com2');
                        var inputcomment = $('.val_comment');

                        $.each(res.one, function(i_one, v_one) {
                            if (v_one.idEmploye == idEmploye) {
                                inputCom1.val(v_one.com1);
                                inputCom2.val(v_one.com2);
                                inputcomment.val(v_one.idValComment);
                            }
                        });

                        btn_eval.append(`<div class="inline-flex items-center justify-end w-full pt-2">
                              <x-btn-primary type="submit">Valider mes réponses</x-btn-primary>
                            </div>`);
                    }
                });
            }

            function __global_drawer(__offcanvas) {
                let __global_drawer = $('#__global_drawer');
                __global_drawer.html('');
                var projet = @json($projet);
                var sumHourSession = @json($totalSession ? $totalSession->sumHourSession : null)

                switch (__offcanvas) {
                    case 'offcanvasGeneral':
                        __global_drawer.append(
                            `<x-drawer-general id="{{ $projet->idProjet }}" ref="{{ $projet->project_reference }}" titre="{{ $projet->project_title }}" description="{{ $projet->project_description }}" projectType="{{ $projet->project_type }}" nbPlace="{{ $nbPlace }}" ></x-drawer-general>`
                        );
                        break;

                    case 'offcanvasSession':
                        __global_drawer.append(
                            `<x-drawer-session></x-drawer-session>`
                        );

                        __global_drawer.ready(function() {
                            openSession("dp_session");
                            var head = $('#head_session');
                        });
                        break;

                    case 'offcanvasApprenant':
                        __global_drawer.append(`<x-drawer-apprenant></x-drawer-apprenant>`);
                        __global_drawer.ready(function() {
                            if (projet.idCfp_inter == null) {
                                var select_appr_project = $('#select_appr_project');
                                select_appr_project.html('');

                                select_appr_project.append(`<div class="inline-flex items-center w-full gap-2">
                                <label class="text-gray-600">Entreprise</label>
                                <select name="" id="etp_list"
                                class="mt-2 border-[1px] border-gray-200 rounded-md p-2 outline-none w-full bg-white">
                                </select>
                            </div>`);

                                getApprenantProjets({{ $projet->idEtp }});

                                getApprenantAdded({{ $projet->idProjet }});
                            } else {
                                var select_appr_project = $('#select_appr_project');
                                select_appr_project.html('');

                                select_appr_project.append(`<div class="inline-flex items-center w-full gap-2">
                                <label class="text-gray-600">Entreprise</label>
                                <select name="" id="etp_list_inter"
                                class="mt-2 border-[1px] border-gray-200 rounded-md p-2 outline-none w-full bg-white">
                                </select>
                            </div>`);

                                getApprenantProjetInter({{ $projet->idProjet }});

                                // Au chargement de la page, cacher tous les éléments <li>
                                $('.list').hide();

                                getApprenantAddedInter({{ $projet->idProjet }});
                            }
                        });
                        break;

                    case 'offcanvasFormateur':
                        __global_drawer.append(`<x-drawer-formateur></x-drawer-formateur>`);

                        getAllForms();
                        getFormAdded({{ $projet->idProjet }});
                        break;

                    case 'offcanvasClient':
                        __global_drawer.append(`<x-drawer-client></x-drawer-client>`);

                        if (projet.idCfp_inter == null) {
                            getEtpAssigned({{ $projet->idProjet }});
                            getAllEtps({{ $projet->idCfp_inter }});
                        } else {
                            getEtpAdded({{ $projet->idProjet }});
                            getAllEtps({{ $projet->idCfp_inter }});
                        }
                        break;

                    case 'offcanvasSalle':
                        __global_drawer.append(`<x-drawer-lieu></x-drawer-lieu>`);
                        break;

                    case 'offcanvasPresence':
                        __global_drawer.append(`<x-drawer-presence></x-drawer-presence>`);

                        if (projet.idCfp_inter == null) {
                            getAllApprPresence({{ $projet->idProjet }});
                        } else {
                            getAllApprPresenceInter({{ $projet->idProjet }});
                        }
                        break;


                    default:
                        break;
                }

                let offcanvasId = $('#' + __offcanvas)
                var bsOffcanvas = new bootstrap.Offcanvas(offcanvasId);
                bsOffcanvas.show();
            }

            //ETPs
            function getAllEtps(idCfp_inter) {
                $.ajax({
                    type: "get",
                    url: "/employes/invites/etp/getAllEtps",
                    dataType: "json",
                    success: function(res) {
                        console.log(res.etps);
                        var get_all_etps = $('#get_all_etps');
                        get_all_etps.html('');

                        if (res.etps.length <= 0) {
                            get_all_etps.append(
                                `<x-no-data texte="Pas d'apprenant pour cette entreprise"></x-no-data>`);
                        } else {
                            $.each(res.etps, function(key, val) {
                                if (idCfp_inter == null) {
                                    get_all_etps.append(`<x-etp-li id="` + val.idEtp +
                                        `" onclick="etpAssign({{ $projet->idProjet }}, ` + val.idEtp +
                                        `)" initial="` + val.etp_initial_name + `" nom="` + val
                                        .etp_name + `" mail="` + val
                                        .etp_email + `" />`);
                                } else if (idCfp_inter != null) {
                                    get_all_etps.append(`<x-etp-li id="` + val.idEtp +
                                        `" onclick="etpAssignInter({{ $projet->idProjet }}, ` + val
                                        .idEtp +
                                        `)" initial="` + val.etp_initial_name + `" nom="` + val
                                        .etp_name + `" mail="` + val
                                        .etp_email + `" />`);
                                }

                                var photo_etp = $('#photo_etp_' + val.idEtp);
                                photo_etp.html('');

                                if (val.etp_logo == "" || val.etp_logo == null) {
                                    photo_etp.append(
                                        `<div  class="flex items-center justify-center w-20 h-10 text-gray-500 uppercase bg-gray-200 rounded-lg">` +
                                        val.etp_initial_name + `</div>`);
                                } else {
                                    photo_etp.append(`<img
                                        src="/img/entreprises/` + val.etp_logo + `"
                                        alt="Avatar" class="object-cover w-20 h-10 mr-4 rounded-lg">`);
                                }
                            });
                        }
                    }
                });
            }

            function getEtpAssigned(idProjet) {
                $.ajax({
                    type: "get",
                    url: "/employes/projets/" + idProjet + "/etp/assign",
                    dataType: "json",
                    success: function(res) {
                        var etp_project = $('.etp_project');
                        var get_etp_selected = $(
                            '#get_etp_selected');

                        etp_project.html('');
                        get_etp_selected.html('');

                        if (res.etp.etp_name != null) {
                            get_etp_selected.append(`<div class="flex flex-row hidden gap-2">
                                <span class="photo_etp_` + res.etp.idEtp + `" data-etpid="` + res.etp.idEtp + `"></span>
                                <p class="pl-2 text-lg font-medium text-gray-500">` + res.etp.etp_name + `</p>
                              </div>`);

                            $('.photo_etp_' + res.etp.idEtp)
                                .html('');
                            if (res.etp.etp_photo != null) {
                                $('.photo_etp_' + res.etp.idEtp)
                                    .append(
                                        `<img src="/img/entreprises/` +
                                        res.etp
                                        .etp_logo +
                                        `" alt="logo" class="object-cover h-16 rounded-lg w-28">`
                                    );
                            } else {

                                $('.photo_etp_' + res.etp.idEtp)
                                    .append(
                                        `<span class="flex items-center justify-center object-cover h-16 text-gray-600 uppercase bg-gray-200 rounded-lg w-28">` +
                                        res.etp
                                        .etp_initial_name +
                                        `</span>`);
                            }
                        } else {
                            etp_project.append(
                                `<p class="pl-2 text-lg font-medium text-gray-500">--</p>`
                            );
                        }

                        if ($('.photo_etp_' + res.etp.idEtp)
                            .attr('data-etpid') == res.etp.idEtp
                        ) {
                            get_etp_selected.append(
                                `<x-etp-li-checked id="` +
                                res.etp.idEtp +
                                `" initial="` + res
                                .etp
                                .etp_initial_name +
                                `" nom="` + res.etp
                                .etp_name + `" />`);
                            $('#etpNameSub').text(res.etp
                                .etp_name);

                            var photo_etp = $(
                                '#photo_etp_selected_' + res
                                .etp.idEtp);
                            photo_etp.html('');

                            if (res.etp.etp_logo == "" || res
                                .etp.etp_logo == null) {
                                photo_etp.append(
                                    `<div  class="flex items-center justify-center w-20 h-10 text-gray-500 uppercase bg-gray-200 rounded-lg">` +
                                    res.etp
                                    .etp_initial_name +
                                    `</div>`);
                            } else {
                                photo_etp.append(`<img
                                        src="/img/entreprises/` + res.etp.etp_logo + `"
                                        alt="Avatar" class="object-cover w-20 h-10 rounded-lg">`);
                            }
                        } else {
                            get_etp_selected.append(
                                `<x-no-data texte="Aucun résultat"></x-no-data>`
                            );
                        }
                    }
                });
            }

            function getProgramProject(idModule) {
                var contentToFill = $('.get_all_programme_project');
                $.ajax({
                    type: "get",
                    url: "/projetsEmp/" + idModule + "/getProgrammeProject",
                    dataType: "json",
                    beforeSend: function() {
                        contentToFill.html('');
                        contentToFill.append(`<p class="loadingProgramProject">Chargement ...</p>`);
                    },
                    complete: function() {
                        $('.loadingProgramProject').hide();
                    },
                    success: function(res) {
                        var i = 1;
                        contentToFill.html('');
                        if (res.programmes.length > 0) {
                            $.each(res.programmes, function(key, val) {
                                contentToFill.append(`<div class="flex flex-col gap-1 sm:w-full md:w-[48%] lg:w-[30%]">
                                        <div class="inline-flex items-center justify-between w-full">
                                          <p class="text-lg font-semibold text-gray-700">Module ` + i++ + `</p>
                                        </div>
                                        <p class="text-base font-semibold text-gray-500">` + val.program_title + `</p>
                                        <span>` + val.program_description + `</span>
                                        <hr class="sm:visible md:hidden border-[1px] border-gray-400 my-2">
                                      </div>`);
                            });
                        } else {
                            contentToFill.append(
                                `<p class="text-lg text-gray-500">Pas de programme pour ce cours pour l'instant.</p>`
                            );
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }

            function getModuleRessourceProject(idModule) {
                var contentToFill = $('.get_all_mr_project');
                $.ajax({
                    type: "get",
                    url: "/projetsEmp/" + idModule + "/getModuleRessourceProject",
                    dataType: "json",
                    beforeSend: function() {
                        contentToFill.html('');
                        contentToFill.append(`<p class="loadingMRProject">Chargement ...</p>`);
                    },
                    complete: function() {
                        $('.loadingMRProject').hide();
                    },
                    success: function(res) {
                        contentToFill.html('');
                        if (res.module_ressources.length > 0) {
                            contentToFill.append(`<table class="w-full">
                                    <thead>
                                      <tr>
                                        <x-th>Nom</x-th>
                                        <x-th>Type de fichier</x-th>
                                        <x-th class="hidden">Date de dernière modification</x-th>
                                        <x-th class="text-right">Action</x-th>
                                      </tr>
                                    </thead>
                                    <tbody class="rmToFill"></tbody>
                                  </table>`);
                            $('.rmToFill').html('');
                            $.each(res.module_ressources, function(key, val) {
                                $('.rmToFill').append(`<x-tr>
                                        <x-td>
                                          <div class="inline-flex items-center gap-2">
                                            <i class="text-xl fa-regular fa-file"></i>
                                            <label class="text-base text-gray-500">` + val.module_ressource_name + `</label>
                                          </div>
                                        </x-td>
                                        <x-td class="text-gray-400">` + val.module_ressource_extension + `</x-td>
                                        <x-td class="text-gray-400">
                                          <div class="inline-flex justify-end w-full">
                                            <button
                                              class="text-sm text-gray-600 px-3 py-1 hover:bg-gray-200 bg-gray-100 transition duration-200 outline-none border-[1px] capitalize"
                                              type="button">
                                              <a href="/employe/module/ressources/` + val.idModuleRessource + `/download" class="transition duration-300 hover:text-gray-700">
                                                Télécharger
                                              </a>
                                            </button>
                                          </div>
                                        </x-td>
                                      </x-tr>`);
                            });
                        } else {
                            contentToFill.append(
                                `<p class="text-lg text-gray-500">Pas de ressource disponible pour ce cours pour l'instant.</p>`
                            );
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }


            function getAllSalle() {
                $.ajax({
                    type: "get",
                    url: "{{ route('employes.salles.getAllSalle') }}",
                    dataType: "json",
                    success: function(res) {
                        var salles = $('.get_all_salle_detail');
                        salles.html('');
                        if (res.salles.length > 0) {
                            $.each(res.salles, function(i, val) {
                                salles.append(`<li class="w-full p-2 border-[1px] bg-white rounded-md">
                                <div class="grid grid-cols-4">
                                  <div class="grid col-span-3">
                                    <div class="flex flex-col">
                                      <span class="text-lg font-semibold text-gray-600">` + val.salle_name + `</span>
                                      <span>
                                        <span class="text-gray-500 rue_` + val.idSalle +
                                    `"></span> - <span class="quartier_` + val.idSalle +
                                    ` text-gray-500">` + val.salle_quartier + `</span>
                                      </span>
                                      <span>
                                        <span class="text-gray-500">` + val.ville +
                                    `</span> <span class="code_postal_` + val.idSalle +
                                    ` text-gray-500">` + val
                                    .salle_code_postal + `</span>
                                      </span>
                                    </div>
                                  </div>
                                  <div class="grid items-center justify-end col-span-1">
                                    <div
                                      onclick="assignSalle(` + val.idSalle + `)"
                                      class="flex items-center justify-center w-10 h-10 duration-150 bg-green-100 rounded-full cursor-pointer icon hover:bg-green-50 group/icon">
                                      <i
                                        class="text-sm text-green-500 duration-150 fa-solid fa-plus group-hover/icon:text-green-600"></i>
                                    </div>
                                  </div>
                                </div>
                              </li>`);

                                if (val.salle_rue != null) {
                                    $('.rue_' + val.idSalle).text(val.salle_rue);
                                } else {
                                    $('.rue_' + val.idSalle).text("N/A");
                                }

                                if (val.salle_quartier != null) {
                                    $('.quartier_' + val.idSalle).text(val.salle_quartier);
                                } else {
                                    $('.quartier_' + val.idSalle).text("N/A");
                                }

                                if (val.salle_code_postal != null) {
                                    $('.code_postal_' + val.idSalle).text(val.salle_code_postal);
                                } else {
                                    $('.code_postal_' + val.idSalle).text("N/A");
                                }
                            });
                        }
                        else{{ salles . append(`<x-no-data texte="Pas de données"></x-no-data>`) }}
                    }
                });
                getSalleAdded({{ $projet->idProjet }});
            }


            function getSalleAdded(idProjet) {
                $.ajax({
                    type: "get",
                    url: "/employe/" + idProjet + "/getSalleAdded",
                    dataType: "json",
                    success: function(res) {
                        var salle_re = $('.salle_re');
                        var salle_qrt = $('.salle_qrt');
                        var salle_ville = $('.salle_ville');
                        var salle_cp = $('.salle_cp');
                        var salle_nm = $('.salle_nm');
                        var salle = $('.get_salle_selected');

                        salle_re.html('');
                        salle_qrt.html('');
                        salle_ville.html('');
                        salle_cp.html('');
                        salle_nm.html('');
                        salle.html('');
                        if (res.salle != null) {
                            var val = res.salle;

                            if (val.salle_rue != null) {
                                salle_re.text(val.salle_rue + " - ");
                            }

                            if (val.salle_quartier != null) {
                                salle_qrt.text(val.salle_quartier + " - ");
                            }

                            if (val.ville != null) {
                                salle_ville.text(val.ville + " - ");
                            }

                            if (val.salle_code_postal != null) {
                                salle_cp.text(val.salle_code_postal + " - ");
                            }

                            if (val.salle_name != null) {
                                salle_nm.text(val.salle_name);
                            }

                            salle.append(`<li class="w-full p-2 border-[1px] bg-white rounded-md">
                              <div class="grid grid-cols-4">
                                <div class="grid col-span-3">
                                  <div class="flex flex-col">
                                    <span class="text-lg font-semibold text-gray-600">` + val.salle_name + `</span>
                                    <span>
                                      <span class="text-gray-500 rue_` + val.idSalle +
                                `"></span> - <span class="quartier_` + val.idSalle + ` text-gray-500">` + val
                                .salle_quartier + `</span>
                                    </span>
                                    <span>
                                      <span class="text-gray-500">` + val.ville + `</span> <span class="code_postal_` +
                                val.idSalle + ` text-gray-500">` + val.salle_code_postal + `</span>
                                    </span>
                                  </div>
                                </div>
                              </div>
                            </li>`);

                            if (val.salle_rue != null) {
                                $('.rue_' + val.idSalle).text(val.salle_rue);
                            } else {
                                $('.rue_' + val.idSalle).text("N/A");
                            }

                            if (val.salle_quartier != null) {
                                $('.quartier_' + val.idSalle).text(val.salle_quartier);
                            } else {
                                $('.quartier_' + val.idSalle).text("N/A");
                            }

                            if (val.salle_code_postal != null) {
                                $('.code_postal_' + val.idSalle).text(val.salle_code_postal);
                            } else {
                                $('.code_postal_' + val.idSalle).text("N/A");
                            }
                        } else {
                            salle.append(`<x-no-data texte="Veuillez selectionnez un lieu"></x-no-data>`);
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }


            function manageProject(type, route) {
                $.ajax({
                    type: type,
                    url: route,
                    data: {
                        _token: '{!! csrf_token() !!}'
                    },
                    dataType: "json",
                    success: function(res) {
                        if (res.success) {
                            toastr.success(res.success, 'Opération effectuée avec succès....', {
                                timeOut: 1500
                            });
                            if (type == 'delete') {
                                $(location).attr('href', '{{ route('projets.employe.index') }}');
                            } else {
                                location.reload();
                            }
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
            }

            function getEtpAdded(idProjet) {
                $.ajax({
                    type: "get",
                    url: "/projetEmp/projet/etpInter/getEtpAdded/" + idProjet,
                    dataType: "json",
                    success: function(res) {

                        var etp_project = $('.etp_project');
                        var get_etp_selected = $('#get_etp_selected');
                        var dash_etp = $('.dash_etp');
                        var $etpAdded = [];

                        etp_project.html('');
                        get_etp_selected.html('');
                        dash_etp.html('');

                        $.each(res.etps, function(i, v) {

                            $etpAdded.push(v.etp_name);

                            if (v.etp_name != null) {
                                get_etp_selected.append(` <li
                                          class="grid grid-cols-5 w-full gap-2 justify-between px-3 py-2 border-[1px] border-gray-100 cursor-pointer hover:bg-gray-50 duration-200 rounded-md">
                                          <div class="col-span-4">
                                            <div class="inline-flex items-center gap-2">
                                              <span class="photo_etp_` + v.idEtp + `">
                                              </span>
                                              <div class="flex flex-col gap-0">
                                                <p class="text-base font-normal text-gray-700">` + v.etp_name + `</p>
                                                <p class="text-sm text-gray-400 lowercase">` + v.mail + `</p>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="grid items-center justify-center w-full col-span-1">
                                            <div onclick="removeEtpInter('delete', {{ $projet->idProjet }}, ` + v
                                    .idEtp + `)"
                                              class="flex items-center justify-center w-10 h-10 duration-150 bg-red-100 rounded-full cursor-pointer icon hover:bg-red-50 group/icon">
                                              <i class="text-sm text-red-500 duration-150 fa-solid fa-minus group-hover/icon:text-red-600"></i>
                                            </div>
                                          </div>
                                        </li>`);

                            } else {
                                etp_project.append(
                                    `<p class="pl-2 text-lg font-medium text-gray-500">--</p>`);
                            }

                            $('.photo_etp_' + v.idEtp).html('');

                            if (v.etp_logo != null) {
                                $('.photo_etp_' + v.idEtp).append(
                                    `<img src="/img/entreprises/${v.etp_logo}" alt="logo" class="object-cover w-20 h-10 mr-4 rounded-lg">`
                                );

                                dash_etp.append(
                                    `<div class="relative w-20 h-10 capitalize bg-gray-200 cursor-pointer rounded-xl" title="${v.etp_name}">
                                  <x-icon-badge />
                                  <img onclick="showCustomer(${v.idEtp}, '/employes/etp-drawer/')" src="/img/entreprises/${v.etp_logo}" alt="logo"
                                    class="object-cover w-full h-full rounded-xl">
                                </div>`);
                            } else {
                                $('.photo_etp_' + v.idEtp).append(
                                    `<span onclick="showCustomer(${v.idEtp}, '/employes/etp-drawer/')" class="flex items-center justify-center object-cover w-20 h-10 mr-4 text-gray-600 uppercase bg-gray-200 rounded-lg cursor-pointer">${v.etp_name[0]}</span>`
                                );

                                dash_etp.append(
                                    `<span onclick="showCustomer(${v.idEtp}, '/employes/etp-drawer/')" class="flex items-center justify-center object-cover w-20 h-10 font-semibold text-gray-600 uppercase bg-gray-200 rounded-lg cursor-pointer">${v.etp_name[0]}</span>`
                                );
                            }
                        });

                        listEtpAdded = $etpAdded;
                    }
                });
            }

            function getApprenantProjetInter(idProjet) {
                $.ajax({
                    type: "get",
                    url: "/projetsEmp/projet/etpInter/getApprenantProjetInter/" + idProjet,
                    dataType: "json",
                    success: function(res) {
                        var all_apprenant = $('#all_apprenant');
                        all_apprenant.html('');

                        var etp_list_inter = $('#etp_list_inter');
                        etp_list_inter.html('');

                        let etp_option = null;

                        if (res.apprs.length <= 0) {
                            all_apprenant.append(`<x-no-data texte="Aucun résultat"></x-no-data>`);
                        } else {

                            // Remplir la liste des apprenants initiale
                            fillApprenantList(res.apprs);

                            if (res.etps.length > 0) {
                                $.each(res.etps, function(i, v) {
                                    etp_list_inter.append(`<option id="` + i + `" value="` + v.idEtp +
                                        `">` + v
                                        .etp_name +
                                        `</option>`);

                                    if (i == 0) {
                                        filterApprenantList($('#' + i).val());
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

            // Fonction pour remplir la liste des apprenants
            function fillApprenantList(apprs) {

                var all_apprenant = $('#all_apprenant');
                all_apprenant.html('');

                $.each(apprs, function(key, val) {
                    let firstName = val.emp_firstname != null ? val.emp_firstname : '';

                    all_apprenant.append(`<li class="list list_` + val.idEtp + ` grid grid-cols-5 w-full px-3 py-2 border-[1px] border-gray-100 cursor-pointer hover:bg-gray-50 duration-200 rounded-md !bg-white">
              <div class="col-span-4">
                  <div class="inline-flex items-center gap-2">
                      <span id="photo_appr_` + val.idEmploye + `"></span>
                      <div class="flex flex-col gap-0">
                          <p class="text-base font-normal text-gray-700">` + val.emp_name + ` ` + firstName + `</p>
                          <span class="mail_` + val.idEmploye + `"></span>
                          <div class="flex flex-col">
                              <span class="matricule_` + val.idEmploye + `"></span>
                              <span class="fonction_` + val.idEmploye + `"></span>
                              <p class="text-sm text-gray-400 normal-case">Entreprise : ` + val.etp_name + `</p>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="grid items-center justify-center w-full col-span-1">
                  <div onclick="manageApprenantInter('post', {{ $projet->idProjet }}, ` + val.idEmploye + `, ` +
                        val
                        .idEtp + `)" class="flex items-center justify-center w-10 h-10 duration-150 bg-green-100 rounded-full cursor-pointer icon hover:bg-green-50 group/icon">
                      <i class="text-sm text-green-500 duration-150 fa-solid fa-plus group-hover/icon:text-green-600"></i>
                  </div>
              </div>
          </li>`);

                    $('.mail_' + val.idEmploye).html('');
                    $('.matricule_' + val.idEmploye).html('');
                    $('.fonction_' + val.idEmploye).html('');

                    if (val.emp_email != null) {
                        $('.mail_' + val.idEmploye).append(`<p class="text-sm text-gray-400 lowercase">` + val
                            .emp_email +
                            `</p>`);
                    }
                    if (val.matricule != null) {
                        $('.matricule_' + val.idEmploye).append(`<p class="text-sm text-gray-400">Matricule : ` + val
                            .emp_matricule + `</p>`);
                    }
                    if (val.emp_fonction != null && val.emp_fonction != 'Default') {
                        $('.fonction_' + val.idEmploye).append(`<p class="text-sm text-gray-400">Fonction : ` + val
                            .emp_fonction +
                            `</p>`);
                    }

                    var photo_appr = $('#photo_appr_' + val.idEmploye);
                    photo_appr.html('');

                    if (val.emp_photo == "" || val.emp_photo == null) {
                        if (val.emp_firstname != null) {
                            photo_appr.append(
                                `<div class="flex items-center justify-center w-10 h-10 mr-4 text-gray-500 uppercase bg-gray-200 rounded-full">` +
                                val.emp_firstname[0] + `</div>`);
                        } else {
                            photo_appr.append(
                                `<div class="flex items-center justify-center w-10 h-10 mr-4 text-gray-500 uppercase bg-gray-200 rounded-full">` +
                                val.emp_name[0] + `</div>`);
                        }
                    } else {
                        photo_appr.append(`<img src="/img/employes/` + val.emp_photo +
                            `" alt="Avatar" class="object-cover w-10 h-10 mr-4 rounded-full">`);
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

            function getApprenantAddedInter(idProjet) {
                var eval_content = @json($eval_content);
                var eval_type = @json($eval_type);
                var all_appr_project = $('.getApprProject');
                all_appr_project.html('');

                $.ajax({
                    type: "get",
                    url: "/projetsEmp/projet/etpInter/getApprenantAddedInter/" + idProjet,
                    dataType: "json",
                    beforeSend: function() {
                        all_appr_project.append(`<span class="initialLoading">Chargement ...</span>`);
                    },
                    complete: function() {
                        $('.initialLoading').remove();
                    },
                    success: function(res) {
                        var all_apprenant_selected = $('#all_apprenant_selected');
                        var get_count_appr = $('.getCountApprProject');
                        var countApprDrawer = $('#countApprDrawer');

                        all_apprenant_selected.html('');
                        get_count_appr.html('');
                        countApprDrawer.html('');

                        get_count_appr.text(res.apprs.length);
                        countApprDrawer.text(res.apprs.length);

                        let eval_note = [];
                        let idValComment = null;
                        let com18 = '';
                        let com19 = '';
                        let idEmployeEval = '';

                        if (res.apprs.length <= 0) {
                            all_apprenant_selected.append(`<x-no-data texte="Aucun résultat"></x-no-data>`);
                            all_appr_project.append(`<x-no-data texte="Aucun résultat"></x-no-data>`);
                        } else {

                            /********************Modification nb des Apprenants coté FRONT **********************/

                            let idCustomer = sessionStorage.getItem('ID_CUSTOMER');

                            let details = JSON.parse(sessionStorage.getItem('ACCESS_EVENTS_DETAILS_' +
                                idCustomer)) || [];
                            details.map(item => {
                                if (item.idProjet === idProjet) {
                                    item.nb_appr = res.apprs.length;
                                }
                                return item;
                            });
                            sessionStorage.setItem('ACCESS_EVENTS_DETAILS_' + idCustomer, JSON.stringify(
                                details));


                            /****************************************************************************************/
                            all_appr_project.append(`<table>
                                      <thead>
                                        <x-tr>
                                          <x-th>Nom</x-th>
                                          <x-th class="sm:hidden lg:block">Matricule</x-th>
                                          <x-th class="hidden">Entreprise</x-th>
                                          <x-th class="text-center">Fonction</x-th>
                                          <x-th class="text-center">Avant</x-th>
                                          <x-th class="text-center">Après</x-th>
                                          <x-th class="text-center">Présence</x-th>
                                          <x-th class="text-center"><i class="fa-solid fa-star text-amber-500"></i></x-th>
                                          <x-th class="text-center"></x-th>
                                          <x-th class="text-center"></x-th>
                                        </x-tr>
                                      </thead>
                                      <tbody class="get_all_appr_project"></tbody>
                                    </table>`);

                            $('.get_all_appr_project').html('');
                            $.each(res.apprs, function(k, val) {
                                let firstname = '';
                                let mail = '';
                                let matricule = '';
                                let matriculeTable = '';
                                let fonction = '';

                                if (val.emp_firstname != null) {
                                    firstname = val.emp_firstname;
                                }
                                if (val.emp_email != null) {
                                    mail = val.emp_email;
                                }
                                if (val.emp_matricule != null) {
                                    matricule = 'Matricule : ' + val.emp_matricule;
                                }
                                if (val.emp_fonction != null && val.emp_fonction != 'Default' && val
                                    .emp_fonction != '') {
                                    fonction = 'Fonction : ' + val.emp_fonction;
                                }

                                $('.get_all_appr_project').append(`<x-tr class="border-b-[1px] border-gray-50">
                                                    <x-td class="!p-1">
                                                      <div class="inline-flex items-center gap-2">
                                                        <span class="appr_photo_${val.idEmploye}"></span>
                                                        <div class="flex flex-col gap-1">
                                                          <label class="text-base font-normal text-gray-600 cursor-pointer">${val.emp_name} ${firstname}</label>
                                                          <label class="text-sm text-gray-400">${val.etp_name}</label>  
                                                        </div>
                                                      </div>
                                                    </x-td>
                                                    <x-td class="!p-1 sm:hidden lg:block">${(val.emp_matricule != null && val.emp_matricule != '') ? val.emp_matricule : '--'}</x-td>
                                                    <x-td class="!p-1 hidden">
                                                      <label class="text-base text-gray-400">${val.etp_name}</label>
                                                    </x-td>
                                                    <x-td class="!p-1 text-center">
                                                      <label class="text-base text-gray-400">${(val.emp_fonction != null && val.emp_fonction != 'Default') ? val.emp_fonction : '--'}</label>
                                                    </x-td>
                                                    <x-td class="!p-1 text-center">
                                                      <label class="text-base text-gray-400">--</label>
                                                    </x-td>
                                                    <x-td class="!p-1 text-center">
                                                      <label class="text-base text-gray-400">--</label>
                                                    </x-td>
                                                    <x-td class="!p-1 text-center">
                                                      <div class="flex items-center justify-center w-full">
                                                        <div
                                                          class="uniquePresence_${val.idEmploye} w-3 h-3 rounded-full">
                                                        </div>
                                                      </div>
                                                    </x-td>
                                                    <x-td class="!p-1 text-center">
                                                      <label class="text-base text-gray-400 appreciation_${val.idEmploye}"></label>
                                                    </x-td>
                                                    <x-td class="!p-1 text-end">
                                                      <button onclick="getEvaluation({{ $projet->idProjet }}, ${val.idEmploye})" class="text-right text-purple-500 underline cursor-pointer">Evaluation</button>
                                                    </x-td>
                                                    <x-td class="!p-1 text-center">
                                                      <button onclick="getSkills({{ $projet->idProjet }}, ${val.idEmploye})" class="text-purple-500 underline cursor-pointer">Skill Matrix</button>
                                                    </x-td>
                                                  </x-tr>`);

                                showEvalTable({{ $projet->idProjet }}, val.idEmploye);
                                getPresenceUnique({{ $projet->idProjet }}, val.idEmploye);

                                let emp_eval = $('#emp_eval_' + val.idEmploye);
                                emp_eval.html('');

                                let content_eval = $('#content_eval_' + val.idEmploye);
                                content_eval.html('');

                                emp_eval.append(`<div class="inline-flex items-center gap-2">
                                                <span class="appr_photo_` + val.idEmploye + `"></span>
                                                <div class="flex flex-col gap-1">
                                                <label class="text-base font-normal text-gray-600 cursor-pointer">` +
                                    val.emp_name + ` ` +
                                    firstname + `</label>
                                                <label class="text-sm text-gray-400">` + val.etp_name + `</label>  
                                                </div>
                                            </div>`);

                                var appr_photo = $('.appr_photo_' + val.idEmploye);
                                appr_photo.html('');

                                if (val.emp_photo == null) {
                                    if (val.emp_firstname != null) {
                                        appr_photo.append(
                                            `<div class="flex items-center justify-center w-10 h-10 text-gray-600 uppercase bg-gray-200 rounded-full">${val.emp_firstname[0]}</div>`
                                        );
                                    } else {
                                        appr_photo.append(
                                            `<div class="flex items-center justify-center w-10 h-10 text-gray-600 uppercase bg-gray-200 rounded-full">${val.emp_name[0]}</div>`
                                        );
                                    }
                                } else {
                                    appr_photo.append(
                                        `<img src="{{ asset('img/employes/${val.emp_photo}') }}" alt="" class="w-10 h-10 rounded-full border-[1px] border-gray-200 object-cover">`
                                    );
                                }

                                all_apprenant_selected.append(`<li
                                                class="grid grid-cols-5 w-full px-3 py-2 border-[1px] border-gray-100 cursor-pointer hover:bg-gray-50 duration-200 rounded-md !bg-white">
                                                <div class="col-span-4">
                                                  <div class="inline-flex items-center gap-2">
                                                    <span id="photo_appr_added_` + val.idEmploye + `"></span>
                                                    <div class="flex flex-col gap-0">
                                                      <p class="text-base font-normal text-gray-700">` + val.emp_name +
                                    ` ` + firstname + `</p>
                                                      <div class="flex flex-col">
                                                        <p class="text-sm text-gray-400 normal-case">` + matricule + `</p>
                                                        <p class="text-sm text-gray-400 normal-case">` + fonction + `</p>
                                                        <p class="text-sm text-gray-400 normal-case">Entreprise : ` +
                                    val
                                    .etp_name +
                                    `</p>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>
                                                <div class="grid items-center justify-center w-full col-span-1">
                                                  <div
                                                    onclick="manageApprenantInter('delete', {{ $projet->idProjet }}, ` +
                                    val.idEmploye + `)"
                                                    class="flex items-center justify-center w-10 h-10 duration-150 bg-red-100 rounded-full cursor-pointer icon hover:bg-red-50 group/icon">
                                                    <i class="text-sm text-red-500 duration-150 fa-solid fa-minus group-hover/icon:text-red-600"></i>
                                                  </div>
                                                </div>
                                              </li>`);

                                var photo_appr = $('#photo_appr_added_' + val.idEmploye);
                                photo_appr.html('');

                                if (val.emp_photo == null) {
                                    if (val.emp_firstname != null) {
                                        photo_appr.append(
                                            `<div class="flex items-center justify-center w-10 h-10 text-gray-600 uppercase bg-gray-200 rounded-full">${val.emp_firstname[0]}</div>`
                                        );
                                    } else {
                                        photo_appr.append(
                                            `<div class="flex items-center justify-center w-10 h-10 text-gray-600 uppercase bg-gray-200 rounded-full">${val.emp_name[0]}</div>`
                                        );
                                    }
                                } else {
                                    photo_appr.append(
                                        `<img src="{{ asset('img/employes/${val.emp_photo}') }}" alt="" class="w-10 h-10 rounded-full border-[1px] border-gray-200 object-cover">`
                                    );
                                }
                            });

                            $('.btn_submit_eval').click(function(e) {
                                e.preventDefault();
                                fillEvaluation({{ $projet->idProjet }}, idEmployeEval,
                                    {{ Auth::user()->id }},
                                    idValComment, eval_note, com18, com19)
                            });
                        }
                    }
                });

                function getAllApprPresenceInter(idProjet) {
                    var sessions = @json($seances);
                    var countDate = @json($countDate);
                    var all_appr_presence = $('.getAllApprPresence');
                    all_appr_presence.html('');
                    let idProj = idProjet;

                    $.ajax({
                        type: "get",
                        url: "/projetsEmp/projet/apprenants/getApprAddedInter/" + idProjet,
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
                                all_appr_presence.append(`<thead class="headPresence">
                                    </thead>
                                    <tbody class="bodyPresence">
                                      <tr class="text-center heureDebPresence">
                                      </tr>
                                      <tr class="text-center heureFinPresence"></tr>
                                      <tbody class="apprPresence"></tbody>
                                    </tbody>
                                    `)

                                var head_presence = $('.headPresence');
                                head_presence.html(`<x-thh date="Jour" />`);
                                var heure_deb_presence = $('.heureDebPresence');
                                heure_deb_presence.html(`<x-tdd>Heure début</x-tdd>`)
                                var heure_fin_presence = $('.heureFinPresence');
                                heure_fin_presence.html(`<x-tdd>Heure fin</x-tdd>`)
                                var apprenant_list = $('.apprPresence');
                                apprenant_list.html('');

                                $.each(res.apprs, function(j, data) {
                                    // Créer la structure HTML pour l'employé
                                    let html = `<x-tr class="text-center list_button_${data.idEmploye}">
                                  <td class="p-2 text-left border">
                                      <div class="inline-flex items-center gap-2 w-max">
                                          <input type="hidden" class="inputEmp" value="${data.idEmploye}">
                                          <span class="photo_emp_${data.idEmploye}"></span>
                                          <p class="text-gray-500">${data.emp_name} ${data.emp_firstname}</p>
                                      </div>
                                  </td>
                              </x-tr>`;

                                    // Ajouter la structure HTML de l'employé à la liste
                                    apprenant_list.append(html);

                                    // Sélectionner l'élément de la photo de l'employé
                                    let photo_emp = $(`.photo_emp_${data.idEmploye}`);
                                    photo_emp.html('');

                                    // Ajouter la photo de l'employé s'il y en a une, sinon afficher une initialisation
                                    if (data.emp_photo == null) {
                                        if (data.firstname != null) {
                                            photo_emp.append(
                                                `<div class="flex items-center justify-center w-10 h-10 text-gray-600 uppercase bg-gray-200 rounded-full">${data.emp_firstname[0]}</div>`
                                            );
                                        } else {
                                            photo_emp.append(
                                                `<div class="flex items-center justify-center w-10 h-10 text-gray-600 uppercase bg-gray-200 rounded-full">${data.emp_name[0]}</div>`
                                            );
                                        }
                                    } else {
                                        photo_emp.append(
                                            `<img src="{{ asset('img/employes/${data.emp_photo}') }}" alt="" class="w-10 h-10 rounded-full border-[1px] border-gray-200 object-cover">`
                                        );
                                    }

                                    // Sélectionner l'élément où vous souhaitez ajouter les boutons de dates bg-gray-50 hover:bg-gray-100
                                    let list_button = $(`.list_button_${data.idEmploye}`);
                                    $.each(res.getSeance, function(i_se, v_se) {
                                        list_button.append(
                                            `<x-tdd class="td_emargement_` + v_se.idSeance +
                                            `_` +
                                            data.idEmploye +
                                            `" td-se="` +
                                            v_se.idSeance +
                                            `" td-ep='` + data.idEmploye + `'></x-tdd>`);
                                        var td_emargement = $('.td_emargement_' + v_se.idSeance +
                                            '_' +
                                            data.idEmploye);
                                        td_emargement.html('');

                                        $.each(res.getPresence, function(i_gp, v_gp) {
                                            if (v_gp.idSeance == td_emargement.attr(
                                                    'td-se') &&
                                                v_gp.idEmploye == td_emargement
                                                .attr('td-ep')) {
                                                if (v_gp.isPresent == null) {
                                                    td_emargement.append(
                                                        `<select data-se="` +
                                                        v_gp.idSeance + `" data-ep='` +
                                                        v_gp
                                                        .idEmploye + `' class="appearance-none main-button w-4 h-4 text-transparent px-2 rounded-md  border-[1px] border-gray-200">
                                              <option id="" class="text-gray-500">-- Selectionner un état --</option>
                                              <option id="present" value="3" class="text-gray-500">Présent</option>
                                              <option id="paritel" value="2" class="text-gray-500">Partiellement Présent</option>
                                              <option id="absent" value="1" class="text-gray-500">Absent</option>
                                              <option id="not" value='0' class="text-gray-500">Non définis</option>
                                            </select>`);
                                                } else {
                                                    let color_select = '';
                                                    switch (v_gp.isPresent) {
                                                        case 3:
                                                            color_select = 'bg-green-500'
                                                            break;
                                                        case 2:
                                                            color_select = 'bg-amber-500'
                                                            break;
                                                        case 1:
                                                            color_select = 'bg-red-500'
                                                            break;
                                                        case 0:
                                                            color_select = 'bg-gray-500'
                                                            break;

                                                        default:
                                                            color_select = 'bg-gray-50'
                                                            break;
                                                    }

                                                    td_emargement.append(
                                                        `<select data-se="` +
                                                        v_gp.idSeance + `" data-ep='` +
                                                        v_gp
                                                        .idEmploye +
                                                        `' class="appearance-none ` +
                                                        color_select + ` main-button-edit w-4 h-4 text-transparent px-2 rounded-md  border-[1px] border-gray-200">
                                              <option id="" class="text-gray-500">-- Selectionner un état --</option>
                                              <option id="present" value="3" class="text-gray-500">Présent</option>
                                              <option id="paritel" value="2" class="text-gray-500">Partiellement Présent</option>
                                              <option id="absent" value="1" class="text-gray-500">Absent</option>
                                              <option id="not" value='0' class="text-gray-500">Non définis</option>
                                            </select>`);
                                                }
                                            }
                                        });
                                    });
                                });

                                const mainButton = $('.main-button');
                                const mainButtonEdit = $('.main-button-edit');
                                let idSe = "";
                                let idEp = "";

                                mainButton.on('change', function() {
                                    idSe = $(this).attr('data-se');
                                    idEp = $(this).attr('data-ep');
                                    isPresent = parseInt($(this).val());
                                    switch (isPresent) {
                                        case 3:
                                            $(this).addClass('bg-green-500')
                                            break;
                                        case 2:
                                            $(this).addClass('bg-amber-500')
                                            break;
                                        case 1:
                                            $(this).addClass('bg-red-500')
                                            break;
                                        case 0:
                                            $(this).addClass('bg-gray-500')
                                            break;

                                        default:
                                            $(this).addClass('bg-gray-50')
                                            break;
                                    }
                                    addPresence(idProj, idSe, idEp, isPresent, 'inter');
                                });

                                mainButtonEdit.on('change', function() {
                                    idSe = $(this).attr('data-se');
                                    idEp = $(this).attr('data-ep');
                                    isPresent = parseInt($(this).val());
                                    switch (isPresent) {
                                        case 3:
                                            $(this).addClass('bg-green-500')
                                            break;
                                        case 2:
                                            $(this).addClass('bg-amber-500')
                                            break;
                                        case 1:
                                            $(this).addClass('bg-red-500')
                                            break;
                                        case 0:
                                            $(this).addClass('bg-gray-500')
                                            break;

                                        default:
                                            $(this).addClass('bg-gray-50')
                                            break;
                                    }
                                    updatePresence(idProj, idSe, idEp, isPresent, 'inter');
                                });


                                $.each(res.countDate, function(i, val) {
                                    head_presence.append(`<x-thh colspan="` + val.count + `" date="` + val
                                        .dateSeance + `" />`);
                                });

                                $.each(res.getSeance, function(i, val) {
                                    heure_deb_presence.append(`<x-tdd class="text-start">` + val
                                        .heureDebut +
                                        `</x-tdd>`);
                                    heure_fin_presence.append(`<x-tdd class="text-start">` + val.heureFin +
                                        `</x-tdd>`);
                                })

                                var present_global = $('#present-global');
                                var partiel_global = $('#partiel-global');
                                var absent_global = $('#absent-global');
                                var taux_presence = $('.taux_presence');
                                present_global.html('');
                                partiel_global.html('');
                                absent_global.html('');
                                taux_presence.html('');

                                present_global.append(res.percentPresent);
                                taux_presence.append(res.percentPresent);
                                partiel_global.append(res.percentPartiel);
                                absent_global.append(res.percentAbsent);
                            }
                        }
                    });
                }
            }
        </script>
    @endsection
