@extends('layouts.master')

@push('custom_style')
    <style>
        /* HTML: <div class="loader"></div> */
        .loader {
            width: 10px;
            aspect-ratio: 1;
            border-radius: 50%;
            animation: l5 1s infinite linear alternate;
        }

        @keyframes l5 {
            0% {
                box-shadow: 20px 0 #000, -20px 0 #0002;
                background: #000
            }

            33% {
                box-shadow: 20px 0 #000, -20px 0 #0002;
                background: #0002
            }

            66% {
                box-shadow: 20px 0 #0002, -20px 0 #000;
                background: #0002
            }

            100% {
                box-shadow: 20px 0 #0002, -20px 0 #000;
                background: #000
            }
        }
    </style>
@endpush

@section('content')
    <div class="w-full h-full">
        <input type="hidden" id="idProjet" value="{{ $projet->idProjet }}">
        <input type="hidden" value="{{ $projet->idProjet }}" id="main_project_get_id">
        <div class="flex flex-row items-center justify-start w-full h-8 gap-1">
            <button onclick="__global_drawer('offcanvasDossier')" class="btn btn-sm btn-ghost opacity-70"><i
                    class="fa-solid fa-folder"></i> {{__('menu.dossier')}}</button>

            @if ($projet->project_type == 'Inter')
                @if ($projet->project_inter_privacy == 0)
                    <button class="btn btn-sm btn-ghost opacity-70"
                        onclick="showModalConfirmation({{ $projet->idProjet }}, 'RendrePublic')"><i
                            class="fa-solid fa-store"></i> {{__('menu.mettreSurLeMarche')}}r</button>
                @else
                    <button class="btn btn-sm btn-ghost opacity-70"
                        onclick="showModalConfirmation({{ $projet->idProjet }}, 'RendrePrivee')"><i
                            class="fa-solid fa-store-slash"></i> {{__('menu.retirerSurLeMarche')}}
                    </button>
                @endif
            @endif
            <button class="btn btn-sm btn-ghost opacity-70" onclick="__global_drawer('offcanvasGeneral')">
                <i class="fa-solid fa-pen"></i>
                {{__('menu.editInfoBase')}}
            </button>
            <button onclick="__global_drawer('offcanvasSubContractor')" class="btn btn-sm btn-ghost opacity-70"><i
                    class="fa-solid fa-handshake"></i> {{__('launcher.subContractor')}}</button>
            <button onclick="openRestauration({{ $projet->idProjet }})" class="btn btn-sm btn-ghost opacity-70"><i
                    class="fa-solid fa-utensils"></i> Restauration</button>
            <a href="/cfp/projets/detailProjetCfpPdf/{{ $projet->idProjet }}" class="hover:text-inherit"><button
                    class="btn btn-sm btn-ghost opacity-70">
                    <i class="fa-solid fa-download"></i>
                    {{__('menu.downloadPdf')}}
                </button></a>
        </div>
        <div class="grid w-full grid-cols-1 p-6 bg-white">
            <div class="grid grid-cols-1 md:grid-cols-4">
                <div class="grid col-span-1 md:col-span-3 grid-cols-subgrid">
                    <div class="inline-flex items-center">
                        <div class="w-24">
                            <figure>
                                @if (isset($projet->module_image))
                                    <img src="{{ $endpoint }}/{{ $bucket }}/img/modules/{{ $projet->module_image }}"
                                        class="object-cover w-24 h-auto" alt="">
                                @else
                                    <img src="{{ asset('img/logo/Logo_mark.svg') }}"
                                        class="object-cover w-12 h-auto grayscale" alt="">
                                @endif
                            </figure>
                        </div>
                        <div class="flex flex-col justify-center flex-1 gap-2 ml-4">
                            <input type="hidden" id="project_id_hidden" value="{{ $projet->idProjet }}">

                            <span class="inline-flex items-center gap-2">
                                <h2 class="text-3xl font-medium text-slate-800">
                                    @if (isset($projet->module_name) && $projet->module_name != 'Default module')
                                        {{ $projet->module_name }}
                                    @else
                                        N/A
                                    @endif
                                </h2>

                                <span class="text-lg font-medium text-slate-600">
                                    Ref : {{ $projet->project_reference ?? 'Non renseigné' }}
                                </span>
                                <span class="text-lg font-medium text-slate-600">
                                    / {{__('menu.dossier')}} : {{ $dossier->nomDossier ?? __('statut.nonClasse') }}
                                </span>
                            </span>
                            <span class="inline-flex items-center gap-2">
                                <div id="raty_notation" class="inline-flex items-center gap-1 rating">
                                </div>
                                {{ number_format($evaluations->noteGeneral, 1, ',', ' ') }}
                                <span class="text-gray-600"> ({{ $evaluations->countNotationProjet }} {{__('statut.avis')}})
                                </span>
                            </span>

                            <div class="inline-flex items-center divide-x gap-x-4 gap-y-1 divide-slate-200">
                                {{-- <p class="px-2"><i class="fa-solid fa-building"></i> COLAS</p> --}}
                                <p class="px-2">{{__('debut')}} : <span class="capitalize">{{ $deb }}</span></p>
                                <p class="px-2">{{__('fin')}} : <span class="capitalize">{{ $fin }}</span></p>
                            </div>

                            <div class="inline-flex flex-wrap items-center gap-x-4 gap-y-3">
                                <span
                                    class="px-3 py-1 rounded-xl w-max text-base
                                        @switch($projet->project_type)
                                            @case('Intra')
                                                border-[1px] border-[#1565c0] text-[#1565c0]
                                                @break
                                             @case('Inter')
                                                border-[1px] border-[#7209b7] text-[#7209b7]
                                                @break    
                                        
                                            @default
                                                
                                        @endswitch
                                    ">
                                    {{ $projet->project_type }}
                                </span>

                                <span
                                    class="px-3 py-1 rounded-xl w-max text-base
                                    @switch($projet->modalite)
                                            @case('Présentielle')
                                                border-[1px] border-[#00a5c5] text-[#00a5c5]
                                                @break
                                             @case('En ligne')
                                                border-[1px] border-[#ba7300] text-[#ba7300]
                                                @break
                                                
                                            @case('Blended')
                                                border-[1px] border-[#005f73] text-[#005f73]
                                                @break   
                                        
                                            @default
                                                
                                        @endswitch
                                ">
                                    @switch($projet->modalite)
                                        @case('Présentielle')
                                            {{__('statut.presentielle')}}
                                            @break
                                        @case('En ligne')
                                            {{__('statut.enligne')}}
                                            @break
                                        @case('Blended')
                                            {{__('statut.blended')}}
                                            @break
                                        @default
                                            {{__('statut.nonRenseigne')}}
                                            @break
                                            
                                    @endswitch
                                </span>

                                <span
                                    class="taux_presence px-3 py-1 text-base border-[1px] rounded-xl border-slate-200"></span>
                            </div>

                            @if ($projet->project_type == 'Inter')
                                <div>
                                    <p class="px-3 py-1 text-base border-[1px] rounded-xl w-max border-slate-200">
                                        {{ $place_reserved }}/{{ $nbPlace }} {{__('statut.placeReserve')}}</p>
                                </div>
                            @endif

                            <p class="line-clamp-6">
                                @if ($projet->project_description != null)
                                    {!! $projet->project_description !!}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                <div class="grid col-span-1 mt-4 md:mt-0">
                    <div class="inline-flex items-center gap-2">
                        <span
                            class="px-3 py-2 text-xl h-max text-white w-max rounded-xl
                                    @switch($projet->project_status)
                                        @case('En préparation')
                                        bg-[#9c850f]
                                        @break
                                        @case('Réservé')
                                        bg-[#33303D]
                                        @break
                                        @case('En cours')
                                        bg-[#318bb9]
                                        @break
                                        @case('Terminé')
                                        bg-[#4d9149]
                                        @break
                                        @case('Annulé')
                                        bg-[#DE324C]
                                        @break
                                        @case('Reporté')
                                        bg-[#2E705A]
                                        @break
                                        @case('Planifié')
                                        bg-[#805d86]
                                        @break
                                        @case('Cloturé')
                                        bg-[#6F1926]
                                        @break
                    
                                        @default
                                             bg-slate-50
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
                                        @endswitch">
                                        @switch($projet->project_status)
                                        @case('En préparation')
                                        {{__('statut.enPreparation')}}
                                        @break
                                        @case('Terminé')
                                        {{__('statut.termine')}}
                                        @break
                                        @case('Annulé')
                                        {{__('drawer.annuler')}}
                                        @break
                                        @case('Planifié')
                                        {{__('statut.planifie')}}
                                        @break
                                        @case('Cloturé')
                                        {{__('statut.cloture')}}
                                        @break
                    
                                        @default
                                    @endswitch
                        </span>

                        <div class="inline-flex items-center">
                            @if (
                                $projet->project_status != 'Planifié' &&
                                    $projet->project_status != 'Cloturé' &&
                                    $projet->project_status != 'Terminé' &&
                                    $projet->project_status != 'En cours')
                                <button onclick="showModalConfirmation({{ $projet->idProjet }}, 'Valider')"
                                    class="btn btn-ghost btn-sm opacity-70 !rounded-r-none btn-outline">{{__('button.valider')}}</button>
                            @endif

                            @if ($projet->project_status != 'Annulé' && $projet->project_status != 'En préparation')
                                <button onclick="showModalConfirmation({{ $projet->idProjet }}, 'Annuler')"
                                    class="btn btn-ghost btn-sm opacity-70 !rounded-r-none btn-outline">{{__('drawer.annuler')}}</button>
                            @endif

                            <div class="dropdown">
                                <div tabindex="0" role="button" aria-label="Action"
                                    class="btn btn-sm btn-ghost !rounded-l-none btn-outline opacity-70">
                                    <i class="fa-solid fa-gear"></i>
                                </div>
                                <ul tabindex="0"
                                    class="dropdown-content menu bg-base-100 rounded-box z-[1] w-72 p-2 shadow">
                                    <li><span onclick="showModalConfirmation({{ $projet->idProjet }}, 'Supprimer')"><i
                                                class="fa-solid fa-trash-can"></i> {{__('button.supprimer')}}</span></li>
                                    <li><span onclick="showModalConfirmation({{ $projet->idProjet }}, 'Dupliquer')"><i
                                                class="fa-solid fa-copy"></i> {{__('button.dupliquer')}}</span></li>

                                    <li class="menu-title">{{__('button.statut')}}</li>
                                    @if ($projet->project_status != 'Reporté')
                                        <li><span onclick="showModalConfirmation({{ $projet->idProjet }}, 'Reporter')"><i
                                                    class="fa-solid fa-calendar-days"></i> {{__('button.reporter')}}</span></li>
                                    @endif
                                    @if ($projet->project_status != 'Cloturé')
                                        <li><span onclick="showModalConfirmation({{ $projet->idProjet }}, 'Cloturer')"><i
                                                    class="fa-solid fa-circle-xmark"></i> {{__('button.cloturer')}}</span></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div role="tablist" class="w-full p-6 tabs tabs-bordered lg:tabs-lg">
            <input type="radio" name="my_tabs_2" role="tab" class="tab !w-max" aria-label="{{__('menu.vueEnsemble')}}"
                checked="checked" />
            <div role="tabpanel" class="tab-content">
                <div class="grid w-full grid-cols-1 gap-4 m-4 lg:grid-cols-3">
                    <div class="grid w-full col-span-1 gap-4 lg:col-span-2 grid-cols-subgrid h-max">
                        {{-- Participant --}}
                        <div class="accordion accordion-flush rounded-box" id="accordionExampleParticipant">
                            <div class="accordion-item card bg-base-100 !w-full shadow-xl !rounded-xl bg-white">
                                <h2 class="accordion-header" id="headingOne">
                                    <span class="collapsed w-full inline-flex !rounded-xl items-center justify-between">
                                        <button type="button"
                                            class="w-full p-4 text-left group hover:underline underline-offset-4"
                                            data-bs-toggle="collapse" data-bs-target="#participant" aria-expanded="true"
                                            aria-controls="participant">
                                            <i class="mr-3 fa-solid fa-users"></i>
                                            <span class="countApprDrawer"></span>
                                            {{__('launcher.apprenants')}}
                                        </button>

                                        <div class="flex justify-end flex-1 pr-4 mt-2">
                                            @if (
                                                (isset($projet->idSubContractor) && $projet->idSubContractor == $idCfp) ||
                                                    ((!isset($projet->idSubContractor) && $projet->idCfp == $idCfp) || $projet->idCfp_inter == $idCfp))
                                                <div class="dropdown dropdown-bottom dropdown-end">
                                                    <div tabindex="1" role="button"
                                                        class="m-1 w-max btn btn-sm btn-outline"><i
                                                            class="fa-solid fa-pen"></i>
                                                        {{__('button.editer')}}</div>
                                                    <ul tabindex="1"
                                                        class="dropdown-content menu bg-white rounded-box z-[1] w-max p-2 shadow">

                                                        @if ($projet->idCfp_inter == null)
                                                            @isset($projet->etp_name)
                                                                <li onclick="__global_drawer('offcanvasApprenant')">
                                                                    <span>
                                                                        <i class="fa-solid fa-users"></i>
                                                                        Ajouter des apprenants
                                                                    </span>
                                                                </li>
                                                            @endisset
                                                        @else
                                                            @if ($apprenantInter != null)
                                                                <li onclick="__global_drawer('offcanvasApprenant')">
                                                                    <span>
                                                                        <i class="fa-solid fa-users"></i>
                                                                        Ajouter des apprenants
                                                                    </span>
                                                                </li>
                                                            @endif
                                                        @endif

                                                        @if ($projet->project_type == 'Inter')
                                                            <li onclick="__global_drawer('offcanvasParticulier')">
                                                                <span>
                                                                    <i class="fa-solid fa-user-group"></i>
                                                                    Ajouter des particuliers
                                                                </span>
                                                            </li>
                                                        @endif
                                                        <span id="presence_canvas">
                                                        </span>
                                                        <li class="disabled">
                                                            <span>
                                                                <i class="fa-solid fa-chart-simple"></i>
                                                                Evaluation
                                                            </span>
                                                        </li>
                                                        <li class="disabled">
                                                            <span>
                                                                <i class="fa-solid fa-compass-drafting"></i>
                                                                Skill matrix
                                                            </span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    </span>
                                </h2>
                                <div id="participant" class="accordion-collapse collapse show"
                                    aria-labelledby="headingOne" data-bs-parent="#accordionExampleParticipant">
                                    <div class="accordion-body">
                                        <div id="appr_table" class="mt-4 overflow-x-auto">
                                        </div>
                                        <div id="part_table" class="mt-4 overflow-x-auto">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- document nécéssaire debut --}}
                        <div class="accordion accordion-flush rounded-box" id="accordionDocument">
                            <div class="accordion-item card bg-base-100 !w-full shadow-xl !rounded-xl bg-white">
                                <h2 class="accordion-header" id="headingOne">
                                    <span class="collapsed w-full inline-flex !rounded-xl items-center justify-between">
                                        <button type="button"
                                            class="w-full p-4 text-left group hover:underline underline-offset-4"
                                            data-bs-toggle="collapse" data-bs-target="#document" aria-expanded="true"
                                            aria-controls="document">
                                            <i class="mr-3 fa-solid fa-folder"></i>
                                            {{__('menu.documentNecessaire')}}
                                        </button>

                                        <div class="flex justify-end w-full pr-4 mt-2">
                                            @if (isset($dossier->idDossier))
                                                <button
                                                    onclick="openDrawerDocument('{{ $dossier->idDossier }}', '{{ $dossier->nomDossier }}')"
                                                    class="m-1 btn btn-sm btn-outline btn-primary">
                                                    <i class="fa-solid fa-plus"></i>
                                                    {{__('menu.ajouter')}}
                                                </button>

                                                <form action="/cfp/dossier/" methode="GET">
                                                    <input type="hidden" name="dossierSearch"
                                                        value="{{ $dossier->idDossier }}">
                                                    <button class="m-1 w-max btn btn-sm btn-outline">
                                                        <i class="fa-solid fa-arrow-right"></i>
                                                        {{__('allerDossier')}}
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </span>
                                </h2>
                                <div id="document" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                    data-bs-parent="#accordionDocument">
                                    <div class="accordion-body">
                                        <div id="doc_table" class="mt-4 overflow-x-auto">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- document nécéssaire fin --}}

                        <div class="grid grid-cols-1 gap-4">
                            {{-- Agenda --}}
                            <div class="accordion accordion-flush rounded-box" id="accordionExampleAgenda">
                                <div class="accordion-item card bg-base-100 !w-full shadow-xl !rounded-xl bg-white">
                                    <h2 class="accordion-header" id="headingOne">
                                        <span
                                            class="collapsed w-full inline-flex !rounded-xl items-center justify-between">
                                            <button class="w-full p-4 text-left group hover:underline underline-offset-4"
                                                type="button" data-bs-toggle="collapse" data-bs-target="#angenda"
                                                aria-expanded="true" aria-controls="angenda">
                                                <i class="mr-3 fa-solid fa-calendar-day"></i>
                                                Agenda
                                            </button>
                                            <div class="flex justify-end w-full pr-4 mt-2">
                                                @if ($projet->idCfp == $idCfp || $projet->idCfp_inter == $idCfp)
                                                    <button
                                                        onclick="__global_drawer('offcanvasSession');  getTotalSeance({{ $projet->idProjet }}) "
                                                        class="m-1 btn btn-sm btn-outline"><i
                                                            class="fa-solid fa-pen"></i>
                                                        {{__('button.editer')}}</button>
                                                @endif
                                            </div>
                                        </span>
                                    </h2>
                                    <div id="angenda" class="accordion-collapse collapse show"
                                        aria-labelledby="headingOne" data-bs-parent="#accordionExampleAgenda">
                                        <div class="accordion-body">
                                            <p>
                                                @if (count($seances) > 0)
                                                    Vous avez <span
                                                        class="font-semibold text-blue-500">{{ count($seances) }}</span>
                                                    sessions d'une
                                                    durée total
                                                    de
                                                    <span class="font-semibold text-blue-500">{{ $totalSession }}</span>
                                                @endif
                                            </p>
                                            <div class="mt-4 overflow-x-auto">
                                                @if (count($seances) <= 0)
                                                    <x-no-data class="!h-14" texte="Pas de session" />
                                                @else
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Date</th>
                                                                <th>{{__('menu.debut')}}</th>
                                                                <th>{{__('menu.fin ')}}</th>
                                                                <th class="text-right">Durée</th>
                                                                <th class="text-right"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($seances as $seance)
                                                                <tr>
                                                                    <td class="capitalize">
                                                                        {{ \Carbon\Carbon::parse($seance->dateSeance)->translatedFormat('l jS F Y') }}
                                                                    </td>
                                                                    <td>{{ \Carbon\Carbon::parse($seance->heureDebut)->format('H\h i') }}
                                                                    </td>
                                                                    <td>{{ \Carbon\Carbon::parse($seance->heureFin)->format('H\h i') }}
                                                                    </td>
                                                                    <td class="text-right">{{ $seance->intervalle_raw }}
                                                                    </td>
                                                                    <td class="text-right">
                                                                        <button aria-label="Supprimer"
                                                                            onclick="deleteSeance({{ $seance->idSeance }})"
                                                                            class="btn btn-ghost btn-sm">
                                                                            <i class="fa-solid fa-xmark"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{--  finance --}}
                        {{-- Financier --}}
                        <div class="accordion accordion-flush rounded-box" id="accordionExampleFinance">
                            <div class="accordion-item card bg-base-100 !w-full shadow-xl !rounded-xl bg-white">
                                <h2 class="accordion-header" id="headingOne">
                                    <span class="collapsed w-full inline-flex !rounded-xl items-center justify-between">
                                        <button class="w-full p-4 text-left group hover:underline underline-offset-4"
                                            type="button" data-bs-toggle="collapse" data-bs-target="#finance"
                                            aria-expanded="true" aria-controls="finance">
                                            <i class="mr-3 fa-solid fa-landmark"></i>
                                            {{__('menu.aspectFinancier')}}
                                        </button>
                                        <div class="flex justify-end pr-4">
                                            @if ((isset($projet->idSubContractor) && $projet->idSubContractor != $idCfp) || !isset($projet->idSubContractor))
                                                @if ($projet->idCfp == $idCfp || $projet->idCfp_inter == $idCfp)
                                                    <button onclick="__global_drawer('offcanvasFrais')"
                                                        class="m-1 w-max btn btn-sm btn-outline"><i
                                                            class="fa-solid fa-pen"></i>
                                                        {{__('button.editer')}}</button>
                                                @endif
                                            @elseif (isset($projet->idSubContractor) && $projet->idSubContractor == $idCfp)
                                                <div onclick="__global_drawer('offcanvasFrais', true)" role="button"
                                                    class="m-1 w-max btn btn-sm btn-outline"><i
                                                        class="fa-solid fa-pen"></i>
                                                    {{__('button.editer')}}</div>
                                            @endif
                                        </div>
                                    </span>
                                </h2>
                                <div id="finance" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                    data-bs-parent="#accordionExampleFinance">
                                    <div class="accordion-body">
                                        <div class="mt-4 overflow-x-auto">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <th>Financement</th>
                                                        <td class="text-right typeFinancement">
                                                            {{ $projet->paiement ?? 'Non renseigné' }}
                                                        </td>
                                                    </tr>
                                                    @if (isset($projet->idSubContractor) && $projet->idSubContractor != $idCfp)
                                                        <tr>
                                                            <th>Coût de la prestation du sous-traitant (Ariary)</th>
                                                            <td class="text-right project_price_total_ht_sub_contractor">
                                                                {{ number_format($projet->total_ht_sub_contractor, 2, ',', ' ') }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    <tr>
                                                        <th>Prix total HT (Ariary)</th>
                                                        <td class="text-right project_price_total_ht"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Prix total TTC (Ariary)</th>
                                                        <td class="text-right project_price_total_ttc"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Momentum --}}
                        <div class="accordion accordion-flush rounded-box" id="accordionExampleMomentum">
                            <div class="accordion-item card bg-base-100 !w-full shadow-xl !rounded-xl bg-white">
                                <h2 class="accordion-header" id="headingOne">
                                    <span class="collapsed w-full inline-flex !rounded-xl items-center justify-between">
                                        <button class="w-full p-4 text-left group hover:underline underline-offset-4"
                                            type="button" data-bs-toggle="collapse" data-bs-target="#momentumAccordion"
                                            aria-expanded="true" aria-controls="momentumAccordion">
                                            <i class="mr-3 fa-solid fa-image"></i>
                                            Momentum (Galérie Photo)
                                        </button>
                                    </span>
                                </h2>
                                <div id="momentumAccordion" class="accordion-collapse collapse show"
                                    aria-labelledby="headingOne" data-bs-parent="#accordionExampleMomentum">
                                    <div class="accordion-body">
                                        @if ($imagesMomentums->isEmpty())
                                            <div class="mb-4">
                                                <form
                                                    action="{{ route('uploadphoto.momentum', ['idProjet' => $projet->idProjet]) }}"
                                                    method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <label for="dropzone-file"
                                                        class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 dropzone">
                                                        <div
                                                            class="flex flex-col items-center justify-center pt-5 pb-6 upload-instructions">
                                                            <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true"
                                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 20 16">
                                                                <path stroke="currentColor" stroke-linecap="round"
                                                                    stroke-linejoin="round" stroke-width="2"
                                                                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                                            </svg>
                                                            <h3 class="text-gray-600">Cliquer ou glisser pour télécharger 1
                                                                à 10 photos simultanément
                                                            </h3>
                                                            <p class="text-gray-500">SVG, PNG, JPG, GIF ou WEBP
                                                                <strong>(MAX. 5 Mo par photo)</strong>
                                                            </p>
                                                        </div>
                                                        <div class="flex flex-row flex-wrap justify-center mt-4 img-area"
                                                            data-img="">
                                                            <!-- Les images sélectionnées seront ajoutées ici -->
                                                        </div>
                                                        <input id="dropzone-file" type="file"
                                                            accept=".svg , .jpeg, .jpg, .gif, .webp, .png" name="myFile[]"
                                                            multiple class="hidden" />
                                                    </label>
                                                    <button id="submit"
                                                        class="mt-3 w-full text-white bg-gradient-to-r from-purple-500 via-purple-600 to-purple-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-purple-300 font-bold rounded-lg text-sm px-5 py-2.5 text-center">
                                                        Importer
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <div class="mb-4">
                                                <form
                                                    action="{{ route('uploadphoto.momentum', ['idProjet' => $projet->idProjet]) }}"
                                                    method="POST" enctype="multipart/form-data"
                                                    class="flex items-center gap-4">
                                                    @csrf
                                                    <label class="flex-grow block">
                                                        <input type="file" name="myFile[]" multiple
                                                            accept=".svg , .jpeg, .jpg, .gif, .webp, .png"
                                                            class="block w-full text-sm text-gray-500 cursor-pointer md:w-3/4 lg:w-1/2 file:cursor-pointer file:w-48 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:btn file:disabled:opacity-50 file:disabled:pointer-events-none dark:text-neutral-500 dark:file:bg-purple-300 dark:hover:file:bg-purple-400">
                                                    </label>
                                                    <button id="submit" class="btn btn-outline btn-primary btn-sm">
                                                        <i class="fa-solid fa-upload"></i>
                                                        Importer
                                                    </button>
                                                </form>
                                            </div>

                                            <div class="w-full overflow-x-auto">
                                                <div class="w-full h-full du-carousel" id="momentum">
                                                    @foreach ($imagesMomentums as $key => $imagesMomentum)
                                                        <div id="slide{{ $key }}"
                                                            class="relative w-full du-carousel-item h-72">
                                                            <img src="{{ $digitalOcean }}/img/momentum/{{ $projet->idProjet }}/{{ $imagesMomentum->nomImage }}"
                                                                alt="photo"
                                                                class="object-cover w-full h-full cursor-pointer"
                                                                onclick="window.location.href='{{ route('cfp.projets.showmomentum', ['idProjet' => $projet->idProjet]) }}';">
                                                            <div
                                                                class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">
                                                                <a href="#slide{{ $key - 1 }}"
                                                                    class="shadow-xl btn btn-circle">❮</a>
                                                                <a href="#slide{{ $key + 1 }}"
                                                                    class="shadow-xl btn btn-circle">❯</a>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid w-full col-span-1 gap-4 h-max">

                        @if ($projet->modalite == 'En ligne' || $projet->modalite == 'Blended')
                            <div class="accordion accordion-flush rounded-box" id="accordionExampleInvitation">
                                <div class="accordion-item card bg-base-100 !w-full shadow-xl !rounded-xl bg-white">
                                    <h2 class="accordion-header" id="headingOne">
                                        <span
                                            class="collapsed w-full inline-flex !rounded-xl items-center justify-between">
                                            <button class="w-full p-4 text-left group hover:underline underline-offset-4"
                                                type="button" data-bs-toggle="collapse" data-bs-target="#salle"
                                                aria-expanded="true" aria-controls="salle">
                                                <i class="mr-3 fa-solid fa-door-open"></i>
                                                Invitation en ligne
                                            </button>
                                            <div class="flex justify-end w-full pr-4 mt-2">
                                                <button onclick="linkInvitation()"
                                                    class="m-1 btn btn-sm btn-outline"><i
                                                        class="fa-solid fa-pen"></i>
                                                    {{__('button.editer')}}</button>
                                            </div>
                                        </span>
                                    </h2>
                                    <div id="salle" class="accordion-collapse collapse show"
                                        aria-labelledby="headingOne" data-bs-parent="#accordionExampleInvitation">
                                        <div class="accordion-body">
                                            <div class="mt-4 overflow-x-auto">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <th>Lien d'invitation</th>
                                                            <td class="text-right">
                                                                @if (isset($projet->link))
                                                                    <a href="{{ !str_starts_with($projet->link, 'http') ? 'https://' . $projet->link : $projet->link }}"
                                                                        target="_blank" data-bs-toggle="tooltip"
                                                                        title="{{ $projet->link }}"
                                                                        class="text-blue-500 underline link">Cliquer
                                                                        ici</a>
                                                                @else
                                                                    Lien non disponible
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Mot de passe</th>
                                                            <td class="text-right secret_code">{{ $projet->secret_code }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif ($projet->modalite == 'Présentielle' || $projet->modalite == 'Blended')
                            <div class="accordion accordion-flush rounded-box" id="accordionExampleSalle">
                                <div class="accordion-item card bg-base-100 !w-full shadow-xl !rounded-xl bg-white">
                                    <h2 class="accordion-header" id="headingOne">
                                        <span
                                            class="collapsed w-full inline-flex !rounded-xl items-center justify-between">
                                            <button class="w-full p-4 text-left group hover:underline underline-offset-4"
                                                type="button" data-bs-toggle="collapse" data-bs-target="#salle"
                                                aria-expanded="true" aria-controls="salle">
                                                <i class="mr-3 fa-solid fa-door-open"></i>
                                                Lieu et salle
                                            </button>
                                            <div class="flex justify-end w-full pr-4 mt-2">
                                                @if ($projet->idCfp == $idCfp || $projet->idCfp_inter == $idCfp)
                                                    <button onclick="__global_drawer('offcanvasSalle')"
                                                        class="m-1 btn btn-sm btn-outline"><i
                                                            class="fa-solid fa-pen"></i>
                                                        {{__('button.editer')}}</button>
                                                @endif
                                            </div>
                                        </span>
                                    </h2>
                                    <div id="salle" class="accordion-collapse collapse show"
                                        aria-labelledby="headingOne" data-bs-parent="#accordionExampleSalle">
                                        <div class="accordion-body">
                                            <p>
                                                La formation aura lieu à <span class="text-gray-600">
                                                    {{-- <span class="font-semibold text-blue-500 salle_qrt"></span> --}}
                                                    <span class="salle_ville_name_coded"></span>
                                                    <span class="salle_ville"></span>
                                                    <span class="salle_cp"></span>
                                                    <span class="text-blue-500 salle_re"></span>
                                                    <span class="salle_nm"></span>
                                                </span>
                                            </p>

                                            <div class="w-full mt-3 overflow-x-auto">
                                                <div class="items-center justify-center du-carousel du-carousel-vertical rounded-box"
                                                    id="salle_img">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="accordion accordion-flush rounded-box" id="accordionExampleEntreprise">
                            <div class="accordion-item card bg-base-100 !w-full shadow-xl !rounded-xl bg-white">
                                <h2 class="accordion-header" id="headingOne">
                                    <span class="collapsed w-full inline-flex !rounded-xl items-center justify-between">
                                        <button class="w-full p-4 text-left group hover:underline underline-offset-4"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#entreprise_accordion" aria-expanded="true"
                                            aria-controls="entreprise_accordion">
                                            <i class="mr-3 fa-solid fa-building"></i>
                                            Entreprise(s)
                                        </button>
                                        @if ($projet->idCfp == $idCfp || $projet->idCfp_inter == $idCfp)
                                            <div class="flex justify-end pr-4">
                                                <button onclick="__global_drawer('offcanvasClient')"
                                                    class="btn btn-outline btn-primary btn-sm"><i
                                                        class="fa-solid fa-pen"></i>
                                                    {{__('button.editer')}}</button>
                                            </div>
                                        @endif
                                    </span>
                                </h2>
                                <div id="entreprise_accordion" class="accordion-collapse collapse show"
                                    aria-labelledby="headingOne" data-bs-parent="#accordionExampleEntreprise">
                                    <div class="accordion-body">
                                        <div class="overflow-x-auto">
                                            <table class="table">
                                                <tbody id="etp_table">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion accordion-flush" id="accordionExempleFormateur">
                            <div class="accordion-item card bg-base-100 !w-full shadow-xl !rounded-xl bg-white">
                                <h2 class="accordion-header" id="headingTwo">
                                    <span class="collapsed w-full inline-flex !rounded-xl items-center justify-between">
                                        <button class="w-full p-4 text-left group hover:underline underline-offset-4"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#formateur_accordion" aria-expanded="true"
                                            aria-controls="formateur_accordion">
                                            <i class="mr-3 fa-solid fa-user-graduate"></i>
                                            Faites connaissance avec les formateurs
                                        </button>
                                        @if (
                                            (isset($projet->idSubContractor) && $projet->idSubContractor == $idCfp) ||
                                                ((!isset($projet->idSubContractor) && $projet->idCfp == $idCfp) || $projet->idCfp_inter == $idCfp))
                                            <div class="flex justify-end w-full pr-4">
                                                <button onclick="__global_drawer('offcanvasFormateur')"
                                                    class="btn btn-outline btn-sm"><i
                                                        class="fa-solid fa-pen"></i>
                                                    {{__('button.editer')}}</button>
                                            </div>
                                        @endif
                                    </span>
                                </h2>
                                <div id="formateur_accordion" class="accordion-collapse collapse show"
                                    aria-labelledby="headingTwo" data-bs-parent="#accordionExempleFormateur">
                                    <div class="accordion-body">
                                        <div class="overflow-x-auto">
                                            <table class="table">
                                                <tbody id="form_table">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        @if (isset($projet->idSubContractor))
                            <div class="accordion accordion-flush rounded-box" id="accordionExampleSubContractor">
                                <div class="accordion-item card bg-base-100 !w-full shadow-xl !rounded-xl bg-white">
                                    <h2 class="accordion-header" id="headingOne">
                                        <span
                                            class="collapsed w-full inline-flex !rounded-xl items-center justify-between">
                                            <button class="w-full p-4 text-left group hover:underline underline-offset-4"
                                                type="button" data-bs-toggle="collapse"
                                                data-bs-target="#subcontractor_accordion" aria-expanded="true"
                                                aria-controls="subcontractor_accordion">
                                                <i class="mr-3 fa-solid fa-handshake-simple"></i>
                                                @if ($projet->idSubContractor == $idCfp)
                                                    Commanditaire
                                                @else
                                                    Sous-traitant
                                                @endif
                                            </button>
                                            <div class="flex justify-end pr-4">
                                                @if ($projet->idCfp == $idCfp || $projet->idCfp_inter == $idCfp)
                                                    <button onclick="__global_drawer('offcanvasSubContractor')"
                                                        class="btn btn-outline btn-sm"><i
                                                            class="fa-solid fa-pen"></i>
                                                        {{__('button.editer')}}</button>
                                                @endif
                                            </div>
                                        </span>
                                    </h2>
                                    <div id="subcontractor_accordion" class="accordion-collapse collapse show"
                                        aria-labelledby="headingOne" data-bs-parent="#accordionExampleSubContractor">
                                        <div class="accordion-body">
                                            <div class="overflow-x-auto">
                                                <table class="table">
                                                    <tbody id="subcontractor_table">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Restauration --}}
                        @if ($restaurations)
                            <div class="accordion accordion-flush rounded-box" id="accordionExample">
                                <div class="accordion-item card bg-base-100 !w-full shadow-xl !rounded-xl bg-white">
                                    <h2 class="accordion-header" id="headingOne">
                                        <span
                                            class="collapsed w-full inline-flex !rounded-xl items-center justify-between">
                                            <button class="w-full p-4 text-left group hover:underline underline-offset-4"
                                                type="button" data-bs-toggle="collapse"
                                                data-bs-target="#restauration_accordion" aria-expanded="true"
                                                aria-controls="restauration_accordion">
                                                <i class="mr-3 fa-solid fa-utensils"></i>
                                                Restaurations
                                            </button>
                                            <div class="flex justify-end pr-4">
                                                <button onclick="openRestauration()"
                                                    class="btn btn-outline btn-sm"><i
                                                        class="fa-solid fa-pen"></i>
                                                    {{__('button.editer')}}</button>
                                            </div>
                                        </span>
                                    </h2>
                                    <div id="restauration_accordion" class="accordion-collapse collapse show"
                                        aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="overflow-x-auto">
                                                <table class="table">
                                                    <tbody id="content_restauration_list"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <input type="radio" name="my_tabs_2" role="tab" class="tab !w-max"
                aria-label="Détail de la formation" />
            <div role="tabpanel" class="tab-content">
                <div class="grid w-full grid-cols-1 gap-4 m-4 lg:grid-cols-3">
                    <div class="grid w-full col-span-1 gap-4 lg:col-span-2 grid-cols-subgrid h-max">
                        {{-- Programme de formation --}}
                        <div class="accordion accordion-flush rounded-box" id="accordionExampleProgramme">
                            <div class="accordion-item card bg-base-100 !w-full shadow-xl !rounded-xl bg-white">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#programmes" aria-expanded="true" aria-controls="programmes">
                                        <i class="mr-3 fa-solid fa-chalkboard"></i>
                                        Programme de formation
                                    </button>
                                </h2>
                                <div id="programmes" class="accordion-collapse collapse show"
                                    aria-labelledby="headingOne" data-bs-parent="#accordionExampleProgramme">
                                    <div class="accordion-body">
                                        <div id="get_all_programme_project"
                                            class="grid grid-cols-1 gap-3 mt-2 overflow-x-auto md:grid-cols-2 2xl:">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid w-full col-span-1 gap-4 h-max">
                        {{-- Objectif --}}
                        <div class="accordion accordion-flush rounded-box" id="accordionExampleInfoObjectif">
                            <div class="accordion-item card bg-base-100 !w-full shadow-xl !rounded-xl bg-white">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#objectif" aria-expanded="true" aria-controls="objectif">
                                        <i class="mr-3 fa-solid fa-bullseye"></i>
                                        Objectifs de la formation
                                    </button>
                                </h2>
                                <div id="objectif" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                    data-bs-parent="#accordionExampleInfoObjectif">
                                    <div class="accordion-body">
                                        <ul class="flex flex-col ml-12">
                                            @foreach ($objectifs as $objectif)
                                                @if ($objectif->idModule == $projet->idModule)
                                                    <li class="text-gray-500 list-disc">{{ $objectif->objectif }}.
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion accordion-flush rounded-box" id="accordionExampleInfoMateriel">
                            <div class="accordion-item card bg-base-100 !w-full shadow-xl !rounded-xl bg-white">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#materiel" aria-expanded="true" aria-controls="materiel">
                                        <i class="mr-3 fa-solid fa-box-open"></i>
                                        Matériels utiles à la formation
                                    </button>
                                </h2>
                                <div id="materiel" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                    data-bs-parent="#accordionExampleInfoMateriel">
                                    <div class="accordion-body">
                                        <ul class="flex flex-col ml-12">
                                            @foreach ($materiels as $mat)
                                                @if ($mat->idModule == $projet->idModule)
                                                    <li class="text-gray-500 list-disc">
                                                        {{ $mat->prestation_name }}</li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- modal de visualisation de fichier --}}
    <div id="fichier-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
        <div id="fichier-modal-content"
            class="relative bg-white p-6 w-[90%] max-w-5xl max-h-[90vh] overflow-auto rounded-lg shadow-lg">
            <span id="close-fichier-modal"
                class="absolute text-xl text-gray-600 cursor-pointer top-2 right-2 hover:text-gray-800">
                &times;
            </span>
            <div id="file-content" class="overflow-auto"></div>
            <iframe id="fichier-viewer" class="w-full h-[600px] border-none hidden"></iframe>
        </div>
    </div>
@endsection

@push('custom_style')
    <link rel="stylesheet" href="{{ asset('css/heat-rating.css') }}">
@endpush

@section('script')
    <script src="{{asset('js/lang/lang.js')}}"></script>
    <!-- MANIPULATION POUR L'AUTHENTIFICATION OAUTH2 DE GOOGLE -->
    <script src="{{ asset('js/gapi_loading.js') }}"></script>
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script src="{{ asset('js/moment.fr.min.js') }}"></script>

    {{-- Sessions --}}
    <script src="{{ asset('js/daypilot-pro-javascript/daypilot-javascript.min.js') }}"></script>
    <script src="{{ asset('js/agendas/CFP/planning.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>

    {{-- Evaluation rating --}}
    <script src="{{ asset('js/heat-rating.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script>
        function formatAmount(nombre) {
            // const nombre = 3100000;
            const formattedNumber = nombre.toLocaleString('en-US', {
                minimumFractionDigits: 1,
                maximumFractionDigits: 1
            });
            return formattedNumber;
        }

        $(document).ready(function () {
            // Convertir les données PHP en objets JS
            const paiements = @json($paiements);
            const projet = @json($projet);
            const idProjet = $('#idProjet').val();
            const idSubContractor = projet.idSubContractor;
            const idCfp = @json($idCfp);
            const evaluations = @json($evaluations);
            
            // 🎯 Mapping des paiements
            const paiementMapping = {
                "Autres": 3,
                "Fonds Propres": 1,
                "FMFP": 2
            };
            const idPmt = paiementMapping[projet.paiement] || null;

            // ✅ Affichage des notifications Toastr
            const messages = {
                error: "{{ Session('error') }}",
                success: "{{ Session('success') }}"
            };
            if (messages.error) toastr.error(messages.error, 'Erreur', { timeOut: 3000 });
            if (messages.success) toastr.success(messages.success, 'Succès', { timeOut: 1500 });

            // ✅ Récupération des documents
            getDocument(projet.idProjet);

            // ✅ Gestion des apprenants et entreprises
            if (!projet.idCfp_inter) {
                getApprenantAdded(projet.idProjet);
                getEtpAssigned(projet.idProjet);
            } else {
                getApprenantAddedInter(projet.idProjet);
                getEtpAdded(projet.idProjet);
                getPartAdded(projet.idProjet);
            }

            // ✅ Appels généraux
            getFormAdded(projet.idProjet);
            getAllSalle(projet.idEtp);
            getSubcontractorSelected(projet.idProjet);
            getProgramProject(projet.idModule);
            ratyNotation('raty_notation', evaluations?.noteGeneral ?? 0);
            
            // ✅ Gestion des paiements actifs
            $('.activePaiement').toggleClass('hidden', !paiements.some(p => p.idPaiement == idPmt));

            // ✅ Calcul des frais et mises à jour
            calculateTotalFrais();
            canva_presence();
            RestaurationList();
            updatefraistotal(projet.idProjet);

            // ✅ Gestion des frais en fonction du sous-traitant
            if (!idSubContractor || idSubContractor != idCfp) {
                getfraisAssign(projet.idProjet, 0);
            } else {
                getfraisAssign(projet.idProjet, 2);
            }

            // ✅ Affichage des dates
            $('.date_deb').html(formatDate(projet.dateDebut ?? 0, 'dddd DD MMM YYYY'));
            $('.date_fin').html(formatDate(projet.dateFin ?? 0, 'dddd DD MMM YYYY'));
        });


        //  =========== PROJET =============
        function updateProjet(idProjet) {
            $.ajax({
                type: "PATCH",
                url: "/cfp/projets/" + idProjet + "/updateProjet",
                data: {
                    project_reference: $('#project_reference_edit').val(),
                    project_title: $('#project_title_edit').val(),
                    project_description: $('#project_description_edit').val(),
                    nbPlace: $('#project_nbplace_edit').val(),
                    project_type: $('#project_type').val()
                },
                dataType: "json",
                success: function(res) {
                    if (res.success) {
                        toastr.success(res.success, 'Projet modifié avec succès', {
                            timeOut: 1500
                        });
                        location.reload();
                    } else {
                        toastr.error(res.error, "Une erreur s'est produite", {
                            timeOut: 1500
                        });
                    }
                }
            });

            submitInfoBase();
        }

        // ======== FRAIS ===========
        // tous les différents frais
        function getAllFrais(isEtp) {
            $.ajax({
                type: "get",
                url: "/cfp/invites/etp/getAllFrais",
                dataType: "json",
                success: function(res) {
                    var get_all_frais = $('#get_all_frais');
                    get_all_frais.html('');

                    if (res.frais.length <= 0) {
                        get_all_frais.append(`<x-no-data texte="Pas de frais pour cet projet"></x-no-data>`);
                    } else {
                        $.each(res.frais, function(key, val) {
                            get_all_frais.append(
                                `<x-frais-li nom="${val.Frais}" exemple="${val
                                .exemple}" onclick="fraisAssign({{ $projet->idProjet }}, ${val.idFrais}, ${isEtp})"/>`
                            );

                        });
                    }
                }
            });
        }

        function removeEtpFraisProjet(idProjet, idEtp) {
            $.ajax({
                type: "delete",
                url: "/cfp/projets/" + idProjet + "/" + idEtp + "/removeEtpFraisProjet",
                data: {
                    _token: '{!! csrf_token() !!}'
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {}
                    if (response.info) {}
                }
            });
        }

        // Fonction pour mettre à jour la taxe dans la base de données
        function updateTaxe(idProjet, newTaxe) {
            $.ajax({
                type: "post",
                url: "/cfp/projets/" + idProjet + "/update-taxe",
                data: {
                    taxe: newTaxe,
                    _token: $('meta[name="csrf-token"]').attr('content') // Token CSRF pour la sécurité
                },
                success: function(response) {
                    toastr.success('La taxe a été mise à jour avec succès', 'Succès', {
                        timeOut: 1500
                    });
                },
                error: function(xhr, status, error) {
                    toastr.error('Erreur lors de la mise à jour de la taxe', 'Erreur', {
                        timeOut: 1500
                    });
                    console.log("Erreur AJAX:", error);
                }
            });
        }

        // Fonction qui selectionne un type de frais
        function fraisAssign(idProjet, idFrais, isEtp, idSubContractor, idCfp) {
            $.ajax({
                type: "POST",
                url: `/cfp/projets/${idProjet}/${idFrais}/${isEtp}/fraisprojet/assign`,
                data: {
                    idProjet,
                    idFrais,
                    isEtp,
                    _token: '{!! csrf_token() !!}'
                },
                success: function () {
                    toastr.info("Remplir le coût.", "Information", { timeOut: 3000 });

                    // ✅ Détermine quel frais assigner selon les paramètres
                    let fraisType = (!idSubContractor || idSubContractor != idCfp) ? 0 : 2;
                    getfraisAssign(idProjet, fraisType);

                    calculateTotalFrais();
                },
                error: function (xhr, status, error) {
                    console.error("Erreur :", error);
                    console.error("Statut :", status);
                    console.error("Réponse :", xhr.responseText);
                    toastr.error("Erreur inattendue.", "Erreur", { timeOut: 1500 });
                }
            });
        }


        // récupérer les frais déjà séléctionnés
        function getfraisAssign(idProjet, isEtp) {
            $.ajax({
                type: "GET",
                url: `/cfp/projets/${idProjet}/${isEtp}/frais`,
                dataType: "json",
                success: function(res) {
                    const get_frais_selected = $('#get_frais_selected').html('');
                    const project_price_total_ht = $('.project_price_total_ht');
                    const project_price_total_ttc = $('.project_price_total_ttc');

                    // ✅ Vérification des frais
                    if (!res.fraisdetails?.length) {
                        get_frais_selected.append(`<x-no-data texte="Pas de frais sélectionné pour ce projet"></x-no-data>`);
                        return;
                    }

                    // ✅ Calcul du total HT et taxe
                    let totalFrais = res.fraisdetails.reduce((sum, val) => sum + (parseFloat(val.montant) || 0), 0);
                    let taxe = parseFloat(res.fraisdetails[0].taxe) || 0;

                    // ✅ Mise à jour des radios TVA
                    $('#tvaRadio20').prop('checked', taxe === 20);
                    $('#tvaRadio0').prop('checked', taxe !== 20);

                    let totalFraisTTC = totalFrais * (1 + taxe / 100);

                    // ✅ Formatage des montants
                    const formatNumber = (num) => num.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    const formattedTotalFrais = formatNumber(totalFrais);
                    const formattedTotalFraisTTC = formatNumber(totalFraisTTC);

                    // ✅ Mise à jour des totaux affichés
                    project_price_total_ht.text(` ${formattedTotalFrais}`);
                    project_price_total_ttc.text(` ${formattedTotalFraisTTC}`);

                    // ✅ Génération dynamique du HTML
                    let fraisHTML = `
                        <div class="grid items-center grid-cols-3 px-2">
                            <span class="text-gray-400">Total HT :</span>
                            <div class="inline-flex items-center justify-end gap-1">
                                <span class="font-medium text-right text-gray-600 total-frais"> Ar ${formattedTotalFrais}</span>
                            </div>
                        </div>
                        <div class="grid items-center grid-cols-3 px-2">
                            <span class="text-gray-400">Total TTC :</span>
                            <div class="inline-flex items-center justify-end gap-1">
                                <span class="font-medium text-right text-gray-600 total-frais-ttc"> Ar ${formattedTotalFraisTTC}</span>
                            </div>
                        </div>
                        <ul id="fraisList">
                    `;

                    // ✅ Boucle optimisée pour afficher les frais
                    fraisHTML += res.fraisdetails.map(val => `
                        <li data-id-frais-projet="${val.idFraisProjet}" class="grid grid-cols-5 gap-2 justify-between px-3 py-2 border border-gray-100 cursor-pointer hover:bg-gray-50 duration-200 rounded-md bg-white">
                            <div class="col-span-4">
                                <div class="inline-flex items-center gap-2">
                                    <div class="grid-cols-2 gap-0">
                                        <div class="inline-flex items-center gap-3">
                                            <h3 class="text-base font-semibold text-gray-700">${val.frais}</h3>
                                        </div>
                                        <div class="grid col-span-1">
                                            <div class="grid items-center grid-cols-2 px-2">
                                                <span class="text-gray-400">Coût:</span>
                                                <div class="inline-flex items-center justify-end gap-1">
                                                    <span class="font-medium text-right text-gray-600">Ar</span>
                                                    <input type="number" value="${val.montant}" class="coutFrais outline-none text-gray-600 font-medium text-right flex bg-transparent pl-2 h-10 border-b border-gray-50 appearance-none hover:border-purple-300 focus:border-purple-300 focus:ring-2 focus:ring-purple-100 duration-200">
                                                </div>
                                            </div>
                                            <div class="grid items-center grid-cols-2 px-2">
                                                <span class="text-gray-400">Description:</span>
                                                <div class="inline-flex items-center justify-end gap-1">
                                                    <input type="text" value="${val.description || ''}" placeholder="Description" class="descriptionFrais outline-none text-gray-600 font-medium text-right flex bg-transparent pl-2 h-10 border-b border-gray-50 appearance-none hover:border-purple-300 focus:border-purple-300 focus:ring-2 focus:ring-purple-100 duration-200">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div onclick="fraisRemove(${idProjet}, ${val.idFraisProjet})" class="grid items-center justify-center col-span-1">
                                <div class="flex items-center justify-center w-10 h-10 duration-150 bg-red-100 rounded-full cursor-pointer icon hover:bg-red-50 group/icon">
                                    <i class="text-sm text-red-500 duration-150 fa-solid fa-minus group-hover/icon:text-red-600"></i>
                                </div>
                            </div>
                        </li>
                    `).join('');

                    fraisHTML += `</ul>`;
                    get_frais_selected.append(fraisHTML);

                    // ✅ Event Delegation pour optimiser les événements (évite de ré-attacher plusieurs fois)
                    $('#get_frais_selected').off('change', '.coutFrais, .descriptionFrais').on('change', '.coutFrais, .descriptionFrais', function() {
                        updateFrais($(this));
                    });
                },
                error: function(xhr, status, error) {
                    toastr.error("Une erreur est survenue lors du chargement des frais.", 'Erreur', { timeOut: 1500 });
                    console.error("Erreur AJAX:", error, "Statut:", status, "Réponse:", xhr.responseText);
                }
            });
        }


        function updateFrais(element) {
            let listItem = element.closest('li');
            let idFraisProjet = listItem.data('id-frais-projet');
            let montant = listItem.find('.coutFrais').val();
            let description = listItem.find('.descriptionFrais').val();

            // Vérification rapide avant requête
            if (!idFraisProjet || montant === "") {
                toastr.error("Veuillez remplir tous les champs.", "Erreur", { timeOut: 1500 });
                return;
            }

            $.ajax({
                type: "POST",
                url: "/cfp/projets/update-frais",
                data: {
                    idFraisProjet,
                    montant,
                    description,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    let projetId = listItem.closest('#fraisList').data('projet-id'); // Récupérer l'ID projet proprement

                    // ✅ Vérification et recalcul proprement
                    let idSubContractor = JSON.parse('@json($projet->idSubContractor)');
                    let idCfp = JSON.parse('@json($idCfp)');

                    if (!idSubContractor || idSubContractor !== idCfp) {
                        getfraisAssign(projetId, 0);
                    } else {
                        getfraisAssign(projetId, 2);
                    }

                    calculateTotalFrais();
                    toastr.success("Frais mis à jour avec succès.", "Succès", { timeOut: 1500 });
                },
                error: function(xhr) {
                    console.error("Erreur lors de la mise à jour :", xhr.responseText);
                    toastr.error("Échec de la mise à jour. Veuillez réessayer.", "Erreur", { timeOut: 1500 });
                }
            });
        }


        // modifier le total des frais dans la base de données
        function updatefraistotal(idProjet) {
            $.ajax({
                type: "post",
                url: "/cfp/projets/" + idProjet + "/total-frais",
                dataType: "json",
                success: function(response) {
                    if (response.error) {

                    }
                }
            });
        }

        function calculateTotalFrais() {
            // Récupérer la taxe sélectionnée (0 ou 20)
            var newTaxe = parseFloat($('input[name="tvaRadio"]:checked').val()) || 0;

            // Calcul du total HT
            var totalFrais = $('.coutFrais').toArray().reduce((sum, el) => sum + (parseFloat(el.value) || 0), 0);
            totalFrais = Number(totalFrais.toFixed(2));

            // Calcul du total TTC
            var totalFraisTTC = totalFrais * (1 + (newTaxe / 100));
            totalFraisTTC = Number(totalFraisTTC.toFixed(2));

            // Affichage des valeurs formatées
            $('.total-frais').text(' Ar ' + totalFrais.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            $('.total-frais-ttc').text(' Ar ' + totalFraisTTC.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

            $('.project_price_total_ht').text(' ' + formatAmount(totalFrais));
            $('.project_price_total_ttc').text(' ' + formatAmount(totalFraisTTC));
        }

        // Déclencher le recalcul quand la TVA change
        $(document).on('change', 'input[name="tvaRadio"], .coutFrais', calculateTotalFrais);


        function calculateTotalPrice() {
            var priceP = $('.project_price_p_detail').val();
            var priceA = $('.project_price_a_detail').val();

            if (priceP != null && priceA != null) {
                var total = Number(priceP) + Number(priceA);
            } else if (priceP != null && priceA == null) {
                var total = Number(priceP);
            } else if (priceP == null && priceA != null) {
                var total = Number(priceA);
            } else {
                var total = "--";
            }

            var total_price = $('.project_price_total');
            total_price.html('');
            $(total_price).text(new Intl.NumberFormat('en-DE').format(
                total));
        }

        // methode pour avoir l'idProjet dans la table fraisProjet
        function getIdProjetByIdFraisProjet(idFraisProjet) {
            return $.ajax({
                type: "GET",
                url: "/cfp/projets/" + idFraisProjet + "/idProjet",
                dataType: "json",
                success: function(response) {
                    if (response.error) {
                        console.error(response.error);
                        if (response.details) {}
                        return null;
                    } else {
                        return response.idProjet;
                    }
                },
                error: function(xhr, status, error) {
                    console.log("Une erreur est survenue : " + error);
                    return null;
                }
            });
        }

        function fermeturefrais(idProjet) {
            $.ajax({
                type: "GET",
                url: "/cfp/projets/fermeturefrais",
                dataType: "json",
                success: function(response) {
                    let idSubContractor = JSON.parse('@json($projet->idSubContractor)');
                    let idCfp = JSON.parse('@json($idCfp)');

                    let isSubContractorDifferent = idSubContractor !== null && idSubContractor !== idCfp;
                    getfraisAssign(idProjet, isSubContractorDifferent ? 0 : 2);

                    calculateTotalFrais();
                    updatefraistotal(idProjet);
                },
                error: function(xhr) {
                    console.error("Erreur lors de la fermeture des frais :", xhr.responseText);
                    toastr.error("Impossible de fermer les frais. Veuillez réessayer.", "Erreur", { timeOut: 1500 });
                }
            });
        }


        // methode qui supprime/déséléctionne un frais à l'aide du bouton minus
        function fraisRemove(idProjet, idFraisProjet) {
            $.ajax({
                type: "POST",
                url: `/cfp/projets/${idProjet}/${idFraisProjet}/delete-frais`,
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content') // Utilisation sécurisée du token CSRF
                },
                dataType: "json",
                success: function(response) {
                    toastr.success(response.success, 'Succès', { timeOut: 1500 });

                    updatefraistotal(idProjet);

                    let idSubContractor = JSON.parse('@json($projet->idSubContractor)');
                    let idCfp = JSON.parse('@json($idCfp)');

                    let isSubContractorDifferent = idSubContractor !== null && idSubContractor !== idCfp;
                    getfraisAssign(idProjet, isSubContractorDifferent ? 0 : 2);

                    calculateTotalFrais();
                },
                error: function(xhr) {
                    console.error("Erreur lors de la suppression du frais :", xhr.responseText);
                    toastr.error("Impossible de supprimer le frais. Veuillez réessayer.", "Erreur", { timeOut: 1500 });
                }
            });
        }


        // ======== SEANCE ===========
        function deleteSeance(idSeance) {
            $.ajax({
                type: "DELETE",
                url: "/cfp/seances/" + idSeance + "/delete",
                success: function(res) {
                    if (res.success) {
                        toastr.success(res.success, 'Session supprimé avec succès', {
                            timeOut: 1500
                        });

                        let idCustomer;
                        if (sessionStorage.getItem('ID_CUSTOMER') !== null) {
                            idCustomer = sessionStorage.getItem('ID_CUSTOMER');
                        }

                        sessionStorage.setItem('UPDATED_STORAGE', true);

                        var dataInStoreEventsDetails = getStore('ACCESS_EVENTS_DETAILS_' + idCustomer);
                        var dataInStoreEventsGroupBy = getStore('ACCESS_EVENTS_GROUP_BY_' + idCustomer);

                        var newDataInStoreEventsDetails = dataInStoreEventsDetails.filter(data => data
                            .idSeance !== idSeance
                        ); //<===== Si utilisateur GOOGLE j'utilise idCalendar comme réference...


                        var newDataInStoreEventsGroupBy = dataInStoreEventsGroupBy.filter(data => data
                            .idSeance !== idSeance
                        );

                        sessionStorage.setItem('ACCESS_EVENTS_DETAILS_' + idCustomer, JSON.stringify(
                            newDataInStoreEventsDetails));


                        sessionStorage.setItem('ACCESS_EVENTS_GROUP_BY_' + idCustomer, JSON.stringify(
                            newDataInStoreEventsGroupBy));

                        location.reload();
                    } else {
                        toastr.error(res.error, "Une erreur s'est produite", {
                            timeOut: 1500
                        });
                    }
                }
            });
        }

        function openDrawerDocument(id, nomDossier) {
            idDossier = id;
            let __global_drawer = $('#drawer_content_detail');
            __global_drawer.html('');

            __global_drawer.append(`<div class="offcanvas offcanvas-end !w-[100em]" tabindex="-1" id="dossier_${id}" aria-labelledby="dossier_${id}">
                                        <div class="flex flex-col w-full">
                                            <div class="inline-flex items-center justify-between w-full px-4 py-2 bg-gray-100">
                                            <p class="text-lg font-medium text-gray-500">Ajouter un document dans le dossier : <span class="text-2xl text-purple-500">${nomDossier}</span></p>
                                            <a data-bs-toggle="offcanvas" href="#dossier_${id}"
                                                class="flex items-center justify-center w-10 h-10 duration-200 rounded-md cursor-pointer hover:bg-gray-200">
                                                <i class="text-gray-500 fa-solid fa-xmark"></i>
                                            </a>
                                            </div>

                                            <div class="w-full overflow-x-auto p-4 h-[100vh] pb-3">
                                            <div class="flex flex-col gap-4 h-full w-full min-w-[650px] overflow-x-scroll overflow-y-auto">
                                                <x-drawer-dossier-document idDossier="${id}" nomDossier=${nomDossier}/>
                                                
                                            </div> 
                                            </div>
                                        </div>
                                    </div>
                `);

            let offcanvasId = $('#dossier_' + id)
            var bsOffcanvas = new bootstrap.Offcanvas(offcanvasId);
            bsOffcanvas.show();



            //fonction fichier 

            loadSections();


            // formulaire d'insertion des documents
            $('#document-form').submit(function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    url: "/cfp/dossier/document/upload/" + id,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.success, 'Succès', {
                                timeOut: 1500
                            });
                            $('#document-form')[0].reset();

                            window.location.reload();
                        }
                        if (response.error) {
                            toastr.error(response.error, 'Erreur', {
                                timeOut: 3000
                            });
                        }
                    }
                });
            });
        }

        function loadSections() {
            fetch('/cfp/dossier/document/section')
                .then(response => response.json())
                .then(data => {
                    let sectionRadioGroup = document.getElementById('section-radio-group');
                    sectionRadioGroup.innerHTML = '';
                    data.sectionDocument.forEach(section => {
                        // Conteneur principal pour chaque section
                        let sectionDiv = document.createElement('div');
                        sectionDiv.classList.add('mb-4');

                        // Bouton radio pour la section
                        let radio = document.createElement('input');
                        radio.type = 'radio';
                        radio.name = 'section_document';
                        radio.value = section.idSectionDocument;
                        radio.id = `section_${section.idSectionDocument}`;
                        radio.classList.add('form-radio', 'accent-purple-600', 'cursor-pointer',
                            'h-5', 'w-5',
                            'text-purple-600', 'focus:ring-purple-500', 'focus:outline-none');

                        let label = document.createElement('label');
                        label.htmlFor = radio.id;
                        label.textContent = section.section_document;
                        label.classList.add('font-semibold');

                        let div = document.createElement('div');
                        div.classList.add('flex', 'items-center', 'gap-2');
                        div.appendChild(radio);
                        div.appendChild(label);
                        sectionDiv.appendChild(div);

                        let typeContainer = document.createElement('div');
                        typeContainer.classList.add('ml-7',
                            'text-gray-600');
                        fetch(`/cfp/dossier/document/type/${section.idSectionDocument}`)
                            .then(response => response.json())
                            .then(typeData => {
                                let typeNames = typeData.typeDocuments.map(typeDoc => typeDoc
                                        .type_document)
                                    .join(', ');
                                typeContainer.textContent = `(${typeNames})`;
                            });
                        sectionDiv.appendChild(typeContainer);
                        sectionRadioGroup.appendChild(sectionDiv);
                        radio.addEventListener('change', function() {
                            loadTypeDocuments(section.idSectionDocument);
                        });
                    });
                });
        }



        function loadTypeDocuments(idSectionDocument) {
            fetch(`/cfp/dossier/document/type/${idSectionDocument}`)
                .then(response => response.json())
                .then(data => {
                    let typeRadioGroup = document.getElementById('type-radio-group');
                    typeRadioGroup.innerHTML = ''; // Clear existing radio buttons
                    let typeDocumentsDiv = document.getElementById('type-documents');
                    let accordionContent = document.getElementById('accordion-content');

                    // Afficher la section des types de documents
                    typeDocumentsDiv.style.display = 'block';

                    data.typeDocuments.forEach(type => {
                        let radio = document.createElement('input');
                        radio.type = 'radio';
                        radio.name = 'type_document';
                        radio.value = type.idTypeDocument;
                        radio.id = `type_${type.idTypeDocument}`;
                        radio.classList.add('form-radio', 'accent-purple-600', 'cursor-pointer',
                            'h-5', 'w-5',
                            'text-purple-600', 'focus:ring-purple-500', 'focus:outline-none');

                        let label = document.createElement('label');
                        label.htmlFor = radio.id;
                        label.textContent = type.type_document;

                        let div = document.createElement('div');
                        div.classList.add('flex', 'items-center', 'gap-2');

                        div.appendChild(radio);
                        div.appendChild(label);

                        typeRadioGroup.appendChild(div);
                    });

                    // Ajuster la hauteur du div accordéon
                    accordionContent.style.maxHeight = accordionContent.scrollHeight +
                        "px"; // Ajuster en fonction du contenu
                });
        }

        // ======== Salle ===========
        function getAllSalle(idEtp) {
            $.ajax({
                type: "GET",
                url: `/cfp/salles/getAllSalle/${idEtp}`,
                dataType: "json",
                success: function(res) {
                    const salles = $('.get_all_salle_detail');
                    salles.html('');

                    if (res.salles.length === 0) {
                        salles.append(`<x-no-data texte="Pas de données"></x-no-data>`);
                        return;
                    }

                    let sallesHTML = res.salles.map(val => {
                        let customerText = val.customerName ?? val.cfpName ?? "Publique";
                        let quartierText = val.salle_quartier ?? "N/A";
                        let codePostalText = val.salle_code_postal ?? "N/A";
                        let rueText = val.salle_rue ?? "N/A";

                        return `
                        <li class="w-full p-2 border-[1px] bg-white rounded-md">
                            <div class="grid grid-cols-4">
                                <div class="grid col-span-3">
                                    <div class="flex flex-col">
                                        <span>
                                            <span class="text-lg font-semibold text-gray-600">${val.salle_name}</span> de
                                            <span class="text-lg text-gray-600">${val.lieux_name}</span>
                                        </span>
                                        <span>
                                            <span class="text-gray-500">${customerText}</span> - 
                                            <span class="text-gray-500">${quartierText}</span>
                                        </span>
                                        <span>
                                            <span class="text-gray-500">${val.ville}</span> 
                                            <span class="text-gray-500">${codePostalText}</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="grid items-center justify-end col-span-1">
                                    <div onclick="assignSalle(${val.idSalle})" 
                                        class="flex items-center justify-center w-10 h-10 duration-150 bg-green-100 rounded-full cursor-pointer icon hover:bg-green-50 group/icon">
                                        <i class="text-sm text-green-500 duration-150 fa-solid fa-plus group-hover/icon:text-green-600"></i>
                                    </div>
                                </div>
                            </div>
                        </li>`;
                    }).join('');

                    salles.append(sallesHTML);
                },
                error: function(xhr) {
                    console.error("Erreur lors de la récupération des salles :", xhr.responseText);
                    toastr.error("Impossible de récupérer les salles.", "Erreur", { timeOut: 1500 });
                }
            });

            getSalleAdded({{ $projet->idProjet }});
        }


        function assignSalle(idSalle) {
            $.ajax({
                type: "patch",
                url: "/cfp/projets/" +
                    {{ $projet->idProjet }} + "/" + idSalle +
                    "/salle/assign",
                data: {
                    _token: '{!! csrf_token() !!}'
                },
                dataType: "json",
                success: function(res) {
                    if (res.success) {
                        toastr.success(res.success,
                            'Succès', {
                                timeOut: 1500
                            });
                        getSalleAdded(
                            {{ $projet->idProjet }});
                    } else if (res.error) {
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

        function getSalleAdded(idProjet) {
            $.ajax({
                type: "GET",
                url: `/cfp/projets/${idProjet}/getSalleAdded`,
                dataType: "json",
                success: function(res) {
                    const salle = $('.get_salle_selected');
                    const salle_img = $('#salle_img');

                    // Réinitialisation des éléments
                    $('.salle_re, .salle_qrt, .salle_ville_name_coded, .salle_ville, .salle_cp, .salle_nm, .get_salle_selected, #salle_img').html('');

                    if (!res.salle) {
                        salle.append(`<x-no-data texte="Veuillez sélectionner un lieu"></x-no-data>`);
                        return;
                    }

                    const val = res.salle;
                    const quartier = val.salle_quartier ?? "N/A";
                    const villeNameCoded = val.ville_name_coded ?? "N/A";
                    const ville = val.ville ?? "N/A";
                    const codePostal = val.salle_code_postal ?? "N/A";
                    const lieu = val.lieu_name ?? "N/A";
                    const salleName = val.salle_name ?? "N/A";
                    const salleRue = val.salle_rue ?? "N/A";

                    $('.salle_qrt').text(`${quartier} - `);
                    $('.salle_ville_name_coded').text(`${villeNameCoded} - `);
                    $('.salle_ville').text(`${ville} - `);
                    $('.salle_cp').text(`${codePostal}, `);
                    $('.salle_re').text(`${lieu} `);
                    $('.salle_nm').text(salleName);

                    salle.append(`
                        <li class="w-full p-2 border-[1px] bg-white rounded-md">
                            <div class="grid grid-cols-4">
                                <div class="grid col-span-3">
                                    <div class="flex flex-col">
                                        <span>
                                            <span class="text-lg font-semibold text-gray-600">${salleName}</span> de
                                            <span class="text-lg text-gray-600">${lieu}</span>
                                        </span>
                                        <span>
                                            <span class="text-gray-500">${salleRue}</span> - <span class="text-gray-500">${quartier}</span>
                                        </span>
                                        <span>
                                            <span class="text-gray-500">${villeNameCoded}</span> <span class="text-gray-500">${codePostal}</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </li>
                    `);

                    if (val.salle_image) {
                        salle_img.append(`
                            <div class="h-full du-carousel-item h-96">
                                <img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/salles/${val.salle_image}" />
                            </div>
                        `);
                    }
                },
                error: function(error) {
                    console.error("Erreur lors de la récupération de la salle :", error);
                }
            });
        }


        // ======== APPRENANTS ===========
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
                    appr_table(res);
                    appr_drawer_intra(res);
                    tauxPresenceGlobal(res.percentPresent);
                }
            });
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
                    appr_table(res);
                    appr_drawer(res);
                    tauxPresenceGlobal(res.percentPresent);
                }
            });
        }

        function appr_table(data) {
            let appr_table = $('#appr_table');
            let countApprDrawer = $('.countApprDrawer');
            let apprLength = (data && data.apprs) ? data.apprs.length : [];

            // Réinitialisation du contenu
            appr_table.html('');

            countApprDrawer.html(apprLength || 0);

            if (data.length === 0 || !Array.isArray(data.getEtps) || data.getEtps.length === 0) {
                appr_table.append(`<x-no-data texte="Aucun apprenant"/>`);
                return;
            } 

            let htmlContent = data.getEtps.map(etp => {
                let participants = (data.apprs || [])
                    .filter(appr => appr.etp_name === etp.etp_name)
                    .map(appr => createParticipantRow(appr))
                    .join('');

                return `
                    <div class="etp_apprenant">
                        <label class="mb-2 text-xl text-slate-700">${etp.etp_name}</label>
                        <table class="table">
                            <thead>
                                <tr class="text-slate-800">
                                    <th class="w-[40%]">Nom</th>
                                    <th>Présence</th>
                                    <th class="text-left">Notation</th>
                                    <th class="text-center">Skills</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="data_participant_${etp.idEtp}">
                                ${participants}
                            </tbody>
                        </table>
                    </div>`;
            }).join('');
            appr_table.append(htmlContent);



            loadBsTooltip();
        }

        // Fonction pour générer une ligne d'un participant
        function createParticipantRow(appr) {
            let photo = appr.emp_photo
                ? `<img src="{{ $endpoint }}/{{ $bucket }}/img/employes/${appr.emp_photo}" alt="${appr.emp_name}" class="w-12 h-12 rounded-full"/>`
                : `<span class="flex items-center justify-center w-full h-full text-xl uppercase text-slate-600 bg-slate-200">${appr.emp_name.charAt(0)}</span>`;

            let note = appr.evaluation ? appr.evaluation.generalApreciate : 0; // Note entre 0 et 5

            // Génération des étoiles
            let stars = '';
            for (let i = 1; i <= 5; i++) {
                let starColor = i <= note ? "text-amber-500" : "text-slate-200"; // Or si actif, gris sinon
                stars += `<i class="fa-solid fa-star ${starColor}"></i>`;
            }

            return `
                <tr>
                    <td class="capitalize">
                        <span class="inline-flex items-center">
                            <div class="mr-3 avatar">
                                <div class="w-12 rounded-full">${photo}</div>
                            </div>
                            <span class="flex flex-col">
                                <span class="mr-1 uppercase">${appr.emp_name}</span> ${appr.emp_firstname ?? ''}
                            </span>
                        </span>
                    </td>
                    <td>
                        <div data-bs-toggle="tooltip" class="w-5 h-5 rounded-md uniquePresence uniquePresence_${appr.idEmploye} ${appr.color}"></div>
                    </td>
                    <td class="text-left">
                        <span class="appreciation_${appr.idEmploye} inline-flex items-center">${stars}</span>
                    </td>
                    <td class="text-center cursor-pointer" data-bs-toggle="tooltip" title="Compétence Avant/Après Formation">
                        <span class='text-slate-500'> ${appr.avant ?? '--'} |  ${appr.apres ?? '--'}</span>
                    </td>
                    <td class="text-right">
                        <button onclick="getSkills({{ $projet->idProjet }}, ${appr.idEmploye})" data-bs-toggle="tooltip" title="Skill matrix" class="btn btn-xs md:btn-sm btn-ghost">
                            <i class="fa-solid fa-compass-drafting"></i>
                        </button>
                        <button onclick="getEvaluation({{ $projet->idProjet }}, ${appr.idEmploye})" data-bs-toggle="tooltip" title="Evaluation" class="btn btn-xs md:btn-sm btn-ghost">
                            <i class="fa-solid fa-chart-simple"></i>
                        </button>
                    </td>
                </tr>`;
        }




        function getDocument(idProjet) {
            var doc_table_div = $('#doc_table');

            $.ajax({
                type: "get",
                url: `/cfp/dossier/document/projets/` + idProjet,
                dataType: "json",
                beforeSend: function() {
                    doc_table_div.append(`<span class="flex flex-col items-center w-full gap-2 p-4">
                        <div class="loader"></div>
                        <p class="text-lg text-slate-700">Veuillez patientez, nous chargeons vos données !</p>
                        </span>`)
                },
                success: function(res) {
                    doc_table(res);
                }
            });
        }

        function downloadFile(url, filename) {
            fetch(url)
                .then(response => response.blob())
                .then(blob => {
                    saveAs(blob, filename);
                });
        }

        function doc_table(data) {

            var doc_table = $('#doc_table')
            doc_table.html('');
            if (!data || !Array.isArray(data.documents)) {
                doc_table.append(`<x-no-data texte="Aucun document"/>`)
            } else {
                if (data.documents.length <= 0) {
                    doc_table.append(`<x-no-data texte="Aucun document"/>`)
                } else {

                    doc_table.append(`
                        <div class="cfp_doc">
                            <label class="mb-2 text-xl text-slate-700">Nom de dossier : ${data.nomDossier.nomDossier}</label>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="w-[40%]">Titre</th>
                                        <th>Section</th>
                                        <th class="text-left">Type</th>
                                        <th class="text-left">Date</th>
                                        <th class="text-right">Taille</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="data_doc"></tbody>
                            </table>
                        </div>`)

                    var data_doc = $(`#data_doc`);

                    $.each(data.documents, function(i, doc) {

                        var idDocument = '';
                        data_doc.append(
                            `
                                    <tr>
                                        
                                        <td class="text-left">${doc.titre}</td>
                                        <td class="text-left">${doc.section_document}</td>
                                        <td class="text-left">${doc.type_document}</td>
                                        <td class="text-left">${doc.updated_at}</td>
                                        <td class="text-right">${doc.taille} Mo</td>
                                        <td class="text-gray-400 text-center cursor-pointer hover:text-[#A462A4]" title="Télécharger ce document" onclick="downloadFile('{{ $endpoint }}/{{ $bucket }}/${doc.path}', '${doc.titre}.pdf'); this.blur();">        
                                                <i class="fa-solid fa-download"></i>
                                        </td>
                                        <td class="text-gray-400 text-center cursor-pointer hover:text-[#A462A4]" title="Visualiser ce document" onclick="openFileModal('{{ $endpoint }}/{{ $bucket }}/${doc.path}')">
                                            <i class="fa-solid fa-eye"></i>
                                        </td>
                                        
                                    </tr>`);

                        idEmploye = doc.idDocument;

                        data_doc.ready(function() {
                            // getPresenceUnique({{ $projet->idProjet }}, idEmploye);
                            // showEvalTable({{ $projet->idProjet }}, appr.idEmploye);
                            // loadBsTooltip();
                        });
                    });
                }
            }
        }

        const openFileModal = (fileUrl) => {
            let fileExtension = fileUrl.split('.').pop().toLowerCase();
            let fileContentHtml = '';
            $('#file-content').empty();
            $('#fichier-viewer').hide();

            switch (fileExtension) {
                case 'pdf':
                    $('#fichier-viewer').show().attr('src', fileUrl);
                    break;
                case 'txt':
                case 'csv':
                    $.get(fileUrl, function(data) {
                        fileContentHtml =
                            `<pre class="font-mono whitespace-pre-wrap">${data}</pre>`;
                        $('#file-content').html(fileContentHtml);
                    });
                    break;
                case 'xls':
                case 'xlsx':
                    fileContentHtml =
                        `<iframe src="https://docs.google.com/gview?url=${fileUrl}&embedded=true" class="w-full h-[600px]"></iframe>`;
                    $('#file-content').html(fileContentHtml);
                    break;
                case 'ppt':
                case 'pptx':
                    fileContentHtml =
                        `<iframe src="https://docs.google.com/viewer?url=${fileUrl}&embedded=true" class="w-full h-[600px]"></iframe>`;
                    $('#file-content').html(fileContentHtml);
                    break;
                default:
                    $('#file-content').html('Format de fichier non pris en charge.');
            }

            $('#fichier-modal').removeClass('hidden');
        };

        const closeModalApercu = () => {
            $('#fichier-modal').addClass('hidden');
            $('#fichier-viewer').attr('src', '');
            $('#file-content').empty();
        };

        $('#close-fichier-modal').click(closeModalApercu);

        $('#fichier-modal').click(function(e) {
            if (e.target === this) {
                closeModalApercu();
            }
        });

        $(document).keyup(function(e) {
            if (e.key === "Escape" && $('#fichier-modal').hasClass('flex')) {
                closeModalApercu();
            }
        });

        function appr_drawer(data) {
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
                                        <button onclick="manageApprenantInter('delete', {{ $projet->idProjet }}, ${appr.idEmploye})" class="rounded-full btn btn-error btn-outline"><i class="text-xl fa-solid fa-minus"></i></button>    
                                    </td>
                                </tr>`
                                );
                            }
                        });

                    });
                });
            }
        }

        function appr_drawer_intra(data) {
            var all_apprenant_selected = $('#all_apprenant_selected')
            all_apprenant_selected.html('');

            if (data.length === 0 || !Array.isArray(data.getEtps) || data.getEtps.length === 0) {
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
                                        <button onclick="manageApprenant('delete', {{ $projet->idProjet }}, ${appr.idEmploye})" class="rounded-full btn btn-error btn-outline"><i class="text-xl fa-solid fa-minus"></i></button>    
                                    </td>
                                </tr>`
                                );
                            }
                        });

                    });
                });
            }
        }

        function getApprenantProjetInter(idProjet) {
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
                all_apprenant.append(`
                                <tr class="list_${val.idEtp}">
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
                                        <button onclick="manageApprenantInter('post', {{ $projet->idProjet }}, ${val.idEmploye}, ${val.idEtp})" class="rounded-full btn btn-success btn-outline"><i class="text-xl fa-solid fa-plus"></i></button>    
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

        function getApprenantProjets(idEtp) {
            $.ajax({
                type: "get",
                url: "/cfp/projet/apprenants/getApprenantProjets/" + idEtp,
                dataType: "json",
                success: function(res) {
                    console.log(res, "all apprs");

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

                all_apprenant.append(`
                                <tr class="list_${val.idEtp}">
                                    <td class="capitalize">
                                        <span class="inline-flex items-center">
                                            <div class="mr-3 avatar">
                                                <div class="w-12 rounded-full">
                                                    ${ val.emp_photo ? ` <img src="{{ $endpoint }}/{{ $bucket }}/img/employes/${val.emp_photo}" />` : `<span class="flex items-center justify-center w-full h-full text-xl uppercase text-slate-600 bg-slate-200">${val.emp_name.charAt(0)}</span>`
                                                    }
                                                </div>
                                            </div>
                                            <span class="flex flex-col">
                                                <span class="mr-1 uppercase">${val.emp_name}</span> ${val.emp_firstname ?? '' }
                                            </span>
                                        </span>
                                    </td>
                                    <td class='text-right'>
                                        <button onclick="manageApprenant('post', {{ $projet->idProjet }}, ${val.idEmploye})" class="rounded-full btn btn-success btn-outline"><i class="text-xl fa-solid fa-plus"></i></button>    
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
                        getApprenantAddedInter({{ $projet->idProjet }});
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
                        getApprenantAdded({{ $projet->idProjet }});
                    } else if (res.error) {
                        toastr.error(res.error, 'Erreur', {
                            timeOut: 1500
                        });
                    }
                }
            });
        }
        // =========== PRESENCE =============
        // Taux de présence
        function tauxPresenceGlobal(data) {
            var taux_presence = $('.taux_presence');
            taux_presence.html('');
            taux_presence.append(data ?? `{{__('statut.nonRenseigne')}}`);
        }

        // =========== FORMATEUR =============
        // Formateur AJAX
        function getFormAdded(idProjet) {
            $.ajax({
                type: "get",
                url: "/cfp/projets/" + idProjet + "/getFormAdded",
                dataType: "json",
                success: function(res) {
                    form_table(res.forms);
                    form_drawer_added(res.forms);
                }
            });
        }

        // All Formateur AJAX
        function getAllForms() {
            $.ajax({
                type: "get",
                url: "/cfp/forms/getAllForms",
                dataType: "json",
                success: function(res) {
                    all_form_drawer(res.forms)
                }
            });
        }

        function form_table(data) {
            var form_table = $('#form_table');
            form_table.html('');

            if (data.length <= 0) {
                form_table.append(`<x-no-data texte="Pas de formateur"/>`);
            } else {
                $.each(data, function(i, form) {
                    form_table.append(`
                                <tr>
                                    <td class="capitalize">
                                        <span class="inline-flex items-center">
                                            <div class="mr-3 avatar">
                                                <div class="w-12 rounded-full">
                                                    ${ form.form_photo ? ` <img alt="${form.form_name}" src="{{ $endpoint }}/{{ $bucket }}/img/formateurs/${form.form_photo}" />` : `<span class="flex items-center justify-center w-full h-full text-xl uppercase text-slate-600 bg-slate-200">${form.form_name.charAt(0)}</span>`
                                                    }
                                                </div>
                                            </div>
                                            <span class="mr-1 uppercase">${form.form_name}</span> ${form.form_firstname ?? '' }
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <button aria-label="ViewMiniCV" onclick="viewMiniCV(${form.idFormateur})" class="opacity-50 btn btn-sm btn-ghost"><i class="fa-solid fa-eye"></i></button>
                                    </td>
                                </tr>
                    `);
                });
            }
        }

        function all_form_drawer(data) {
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
                                    <button onclick="manageForm('post', {{ $projet->idProjet }}, ${form.idFormateur})" class="rounded-full btn btn-success btn-outline"><i class="text-xl fa-solid fa-plus"></i></button>    
                                </td>
                            </tr>
                `);
                });
            }
        }

        function form_drawer_added(data) {
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
                                    <button onclick="manageForm('delete', {{ $projet->idProjet }}, ${form.idFormateur})" class="rounded-full btn btn-error btn-outline"><i class="text-xl fa-solid fa-minus"></i></button>    
                                </td>
                            </tr>
                `);
                });
            }
        }

        function manageForm(type, idProjet, idFormateur) {
            $.ajax({
                type: type,
                url: `/cfp/projets/${idProjet}/${idFormateur}/form/assign`,
                data: {
                    _token: '{!! csrf_token() !!}'
                },
                dataType: "json",
                success: function(res) {
                    if (res.success) {
                        toastr.success(res.success, 'Succès', { timeOut: 1500 });

                        /************* Ajout/Suppression d'un Formateur côté FRONT *************/
                        let idCustomer = sessionStorage.getItem('ID_CUSTOMER');
                        let prenom_form = $('#prenom_form_' + idFormateur).val();

                        // Fonction de mise à jour de sessionStorage
                        function updateSessionStorage(key) {
                            let data = JSON.parse(sessionStorage.getItem(key)) || [];
                            data.forEach(item => {
                                if (item.idProjet === idProjet) {
                                    let formateurs = item.prenom_form || [];
                                    const index = formateurs.findIndex(obj => obj.idFormateur == idFormateur);
                                    
                                    if (index === -1) {
                                        formateurs.push({ idFormateur, prenom: prenom_form });
                                    } else {
                                        formateurs.splice(index, 1);
                                    }
                                    item.prenom_form = formateurs;
                                }
                            });
                            sessionStorage.setItem(key, JSON.stringify(data));
                        }

                        updateSessionStorage('ACCESS_EVENTS_RESOURCE_' + idCustomer);
                        updateSessionStorage('ACCESS_EVENTS_DETAILS_' + idCustomer);
                        /************************************************************************/

                        getFormAdded(idProjet);
                    } else if (res.error) {
                        toastr.error(res.error, 'Erreur', { timeOut: 1500 });
                    }
                }
            });
        }


        //  =========== ENTREPRISE ==============
        function getAllEtps(idCfp_inter) {
            $.ajax({
                type: "get",
                url: "/cfp/invites/etp/getAllEtps",
                dataType: "json",
                success: function(res) {
                    var all_etp_drawer = $('#all_etp_drawer');
                    all_etp_drawer.html('');

                    if (res.etps.length <= 0) {
                        all_etp_drawer.append(`<x-no-data texte="Pas d'apprenant pour cette entreprise"></x-no-data>`);
                        return;
                    }

                    $.each(res.etps, function(key, etp) {
                        let etp_logo = etp.etp_logo
                            ? `<img src="{{ $endpoint }}/{{ $bucket }}/img/entreprises/${etp.etp_logo}" class="object-cover w-20 h-auto" alt="${etp.etp_name ?? "Entreprise"}" />`
                            : `<span class="flex items-center justify-center w-full h-full text-xl uppercase text-slate-600 bg-slate-200">${etp.etp_initial_name}</span>`;

                        let etp_email = idCfp_inter ? (etp.mail ?? '') : (etp.etp_email ?? '');

                        let assignFunction = idCfp_inter ? `etpAssignInter` : `etpAssign`;

                        all_etp_drawer.append(`
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar">
                                            <div class="w-24 h-16 rounded-xl">${etp_logo}</div>
                                        </div>
                                        <div>
                                            <div class="font-bold uppercase">${etp.etp_name ?? ''}</div>
                                            <div class="text-sm text-slate-500">${etp_email}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <button onclick="${assignFunction}({{ $projet->idProjet }}, ${etp.idEtp})" class="btn btn-outline btn-success">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </td>
                            </tr>
                        `);
                    });
                }
            });
        }


        function etpAssignInter(idProjet, idEtp) {
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
                        getApprenantProjetInter({{ $projet->idProjet }});
                        getEtpAdded({{ $projet->idProjet }});
                        getAllEtps({{ $projet->idCfp_inter }});
                    } else {
                        toastr.error("Erreur", res.error, {
                            timeOut: 1500
                        });
                    }
                }
            });
        }

        function etpAssign(idProjet, idEtp) {
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
                        getApprenantAdded({{ $projet->idProjet }});
                        getEtpAssigned({{ $projet->idProjet }});
                        getAllEtps({{ $projet->idCfp_inter }});
                    }
                }
            });
        }

        function getEtpAdded(idProjet) {
            $.ajax({
                type: "get",
                url: "/cfp/projet/etpInter/getEtpAdded/" + idProjet,
                dataType: "json",
                success: function(res) {
                    etp_table(res.etps);
                    etp_drawer(res.etps);
                }
            });
        }

        function getEtpAssigned(idProjet) {
            $.ajax({
                type: "get",
                url: "/cfp/projets/" + idProjet + "/etp/assign",
                dataType: "json",
                success: function(res) {
                    etp_table_intra(res.etp);
                    etp_drawer_intra(res.etp);
                }
            });
        }

        function removeEtpInter(type, idProjet, idEtp) {
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
                        getApprenantAddedInter({{ $projet->idProjet }});
                        getApprenantProjetInter({{ $projet->idProjet }});
                        getEtpAdded({{ $projet->idProjet }});
                        getAllEtps({{ $projet->idCfp_inter }});
                        removeEtpFraisProjet(idProjet, idEtp);
                    } else if (res.error) {
                        toastr.error(res.error, 'Erreur', {
                            timeOut: 1500
                        });
                    }
                }
            });
        }

        function etp_table(data) {
            var etp_table = $('#etp_table');
            etp_table.html('');

            if (data.length <= 0) {
                etp_table.append(`<x-no-data texte="Pas d'entreprise"/>`);
            } else {
                $.each(data, function(i, etp) {
                    etp_table.append(`
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="avatar">
                                                <div class="w-24 h-16 rounded-xl">
                                                    ${etp.etp_logo ? `<img src="{{ $endpoint }}/{{ $bucket }}/img/entreprises/${etp.etp_logo}" class="object-cover w-20 h-auto" alt="${etp.etp_name ?? "Entreprise"}" />` : 
                                                    `<span class="flex items-center justify-center w-full h-full text-xl uppercase text-slate-600 bg-slate-200">${etp.etp_initial_name ?? 'I'}</span>`}
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-bold uppercase">${etp.etp_name ?? ''}</div>
                                                <div class="text-sm opacity-50">${etp.mail ?? ''}</div>
                                            </div>
                                        </div>
                                    </td>
                                       <td class="text-right">
                                        <button aria-label="ShowCustomer" onclick="showCustomer(${etp.idEtp}, '/cfp/etp-drawer/')" class="opacity-50 btn btn-sm btn-ghost"><i class="fa-solid fa-eye"></i></button>
                                    </td>
                                </tr>
                    `);
                });
            }
        }

        function etp_table_intra(data) {

            var etp_table = $('#etp_table');
            etp_table.html('');

            if (data == null) {
                etp_table.append(`<x-no-data texte="Pas d'entreprise"/>`);
            } else {
                etp_table.append(`
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="avatar">
                                                <div class="w-24 h-16 rounded-xl">
                                                    ${data.etp_logo ? `<img src="{{ $endpoint }}/{{ $bucket }}/img/entreprises/${data.etp_logo}" class="object-cover w-20 h-auto" alt="${data.etp_name ?? "Entreprise"}" />` : 
                                                    `<span class="flex items-center justify-center w-full h-full text-xl uppercase text-slate-600 bg-slate-200">?? I ??</span>`}
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-bold uppercase">${data.etp_name ?? ''}</div>
                                                <div class="text-sm text-slate-500">${data.etp_email ?? ''}</div>
                                            </div>
                                        </div>
                                    </td>
                                        <td class="text-right">
                                        <button aria-label="showCustomer" onclick="showCustomer(${data.idEtp}, '/cfp/etp-drawer/')" class="opacity-50 btn btn-sm btn-ghost"><i class="fa-solid fa-eye"></i></button>
                                    </td>
                                </tr>
                    `);
            }
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

        function etp_drawer(data) {

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
                                    <button onclick="removeEtpInter('delete', {{ $projet->idProjet }}, ${etp.idEtp})" class="btn btn-outline btn-error"><i class="fa-solid fa-minus"></i></button>
                                </td>
                        </tr>
                        `);
                });
            }
        }

        // ============= SUB-CONTRACTOR ============
        // SubContractor
        function getSubContractors() {
            var all_cfps = $('#all_sub_contractor');
            $.ajax({
                type: "get",
                url: "/cfp/projects/subContractor/getAll",
                dataType: "json",
                beforeSend: function() {

                },
                complete: function() {

                },
                success: function(res) {
                    all_cfps.empty();
                    if (res.subContractors.length > 0) {
                        $.each(res.subContractors, function(key, data) {
                            all_cfps.append(`<tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="avatar">
                                                <div class="w-24 h-16 rounded-xl">
                                                    ${data.sub_logo ? `<img src="{{ $endpoint }}/{{ $bucket }}/img/entreprises/${data.sub_logo}" class="object-cover w-20 h-auto" alt="${data.sub_name ?? "Entreprise"}" />` : 
                                                    `<span class="flex items-center justify-center w-full h-full text-xl uppercase text-slate-600 bg-slate-200">${data.sub_initial_name ?? 'I'}</span>`}
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-bold uppercase">${data.sub_name ?? ''}</div>
                                                <div class="text-sm opacity-50">${data.sub_email ?? ''}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class='text-right'>
                                        <button onclick="assignSubcontractor({{ $projet->idProjet }}, ${data.idSubContractor})" class="btn btn-success btn-outline"><i class="text-xl fa-solid fa-plus"></i></button>    
                                    </td>
                                </tr>`);
                        });
                    } else {
                        all_cfps.append(
                            `<x-no-data texte="Vous n'avez pas encore de centre de formation !"></x-no-data>`
                        );
                    }
                }
            });
        }

        function assignSubcontractor(idProjet, idSubContractor) {
            $.ajax({
                type: "post",
                url: "/cfp/projects/subContractor/" + idProjet + "/" + idSubContractor + "/assign",
                dataType: "json",
                success: function(res) {
                    if (res.success) {
                        toastr.success(res.success, 'Opération effectuée avec succès', {
                            timeOut: 1500
                        });
                        getSubcontractorSelected({{ $projet->idProjet }});
                    } else if (res.error) {
                        toastr.error(res.error, 'Erreur', {
                            timeOut: 1500
                        });
                    }
                }
            });
        }

        function getSubcontractorSelected(idProjet) {
            let cfp_selected = $('#all_sub_contractor_selected');
            $.ajax({
                type: "get",
                url: "/cfp/projects/subContractor/" + idProjet + "/getAssign",
                dataType: "json",
                beforeSend: function() {

                },
                complete: function() {

                },
                success: function(res) {
                    subcontractor_table(res.cfp);
                    let data = res.cfp;

                    cfp_selected.empty();

                    if (data != null) {
                        cfp_selected.append(`<tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="avatar">
                                                <div class="w-24 h-16 rounded-xl">
                                                    ${data.cfp_logo ? `<img src="{{ $endpoint }}/{{ $bucket }}/img/entreprises/${data.cfp_logo}" class="object-cover w-20 h-auto" alt="${data.cfp_name ?? "Entreprise"}" />` : 
                                                    `<span class="flex items-center justify-center w-full h-full text-xl uppercase text-slate-600 bg-slate-200">${data.cfp_initial_name ?? 'I'}</span>`}
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-bold uppercase">${data.cfp_name ?? ''}</div>
                                                <div class="text-sm opacity-50">${data.cfp_email ?? ''}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class='text-right'>
                                        <button onclick="removeSubcontractor(${data.idSubContractor})" class="btn btn-error btn-outline"><i class="text-xl fa-solid fa-minus"></i></button>    
                                    </td>
                                </tr>`);
                    } else {
                        cfp_selected.append(`<x-no-data texte="Pas de données"></x-no-data>`);
                    }
                }
            });
        }

        function removeSubcontractor(idSubContractor) {
            $.ajax({
                type: "delete",
                url: "/cfp/projects/subContractor/" + idSubContractor + "/removeAssign",
                dataType: "json",
                success: function(res) {
                    if (res.success) {
                        toastr.success(res.success, 'Opération effectuée avec succès', {
                            timeOut: 1500
                        });
                        getSubcontractorSelected({{ $projet->idProjet }});
                    } else if (res.error) {
                        toastr.error(res.error, 'Erreur', {
                            timeOut: 1500
                        });
                    }
                }
            });
        }

        function subcontractor_table(data) {
            var subcontractor_table = $('#subcontractor_table');
            subcontractor_table.html('');

            if (data == null) {
                subcontractor_table.append(`<x-no-data texte="Pas de sous-traitant"/>`);
            } else {
                subcontractor_table.append(`
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="avatar">
                                                <div class="w-24 h-16 rounded-xl">
                                                    ${data.cfp_logo ? `<img src="{{ $endpoint }}/{{ $bucket }}/img/entreprises/${data.cfp_logo}" class="object-cover w-20 h-auto" alt="${data.cfp_name ?? "Entreprise"}" />` : 
                                                    `<span class="flex items-center justify-center w-full h-full text-xl uppercase text-slate-600 bg-slate-200">${data.cfp_initial_name ?? 'I'}</span>`}
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-bold uppercase">${data.cfp_name ?? ''}</div>
                                                <div class="text-sm opacity-50">${data.cfp_email ?? ''}</div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                    `);
            }
        }

        //  ============ PRESENCE ==================
        // AJAX PRESENCE INTRA
        function getAllApprPresence(idProjet) {
            var sessions = @json($seances);
            var countDate = @json($generalData->countDate ?? 0);
            var all_appr_presence = $('.getAllApprPresence');
            all_appr_presence.html('');
            let idProj = idProjet;


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
                        drawer_presence(res);
                    }
                }

            });
        }

        // AJAX PRESENCE INTER
        function getAllApprPresenceInter(idProjet) {
            var sessions = @json($seances);
            var countDate = @json($generalData->countDate ?? 0);
            var all_appr_presence = $('.getAllApprPresence');
            all_appr_presence.html('');
            let idProj = idProjet;

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
                        drawer_presence(res);
                    }
                }
            });
        }

        function drawer_presence(res) {
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
                            <input type="checkbox" class="hidden checkbox_appr" name="emargement" data-idProj="{{ $projet->idProjet }}" data-idAppr="${v_gp.idEmploye}" data-idSe="${v_gp.idSeance}" id="td_${v_gp.idSeance}_${v_gp.idEmploye}">
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

        function confirmChecking(isPresent) {

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
                                        <button type="submit" id="submitButton" data-bs-dismiss="modal" data-id="{{ $projet->idProjet }}" data-is_present="${isPresent}" class="px-4 py-2 text-base text-white transition duration-200 scale-95 bg-${color} rounded-md hover:scale-100 hover:bg-${color}/90 text-medium">Oui, marquer comme ${lable_button}</button>
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
                        var projet = @json($projet);
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

        // ============= EVALUATION ================
        function getEvaluation(idProjet, idEmploye) {
            let drawer_eval = $('#drawer_content_detail');
            drawer_eval.html(`
                <x-drawer-evaluation idProjet="${idProjet}" id="${idEmploye}"></x-drawer-evaluation>
            `);

            let offcanvasEvaluation = $('#offcanvasEvaluation_' + idEmploye);
            let bsOffcanvas = new bootstrap.Offcanvas(offcanvasEvaluation);

            fetchEvaluationData(idProjet, idEmploye, function(res) {
                if (res.checkEval <= 0) {
                    populateQuestions(res, idEmploye, idProjet);
                    addRatingHandlers();
                } else {
                    populateExistingResponses(res, idEmploye, idProjet);
                }
            });

            bsOffcanvas.show();
        }

        function fetchEvaluationData(idProjet, idEmploye, callback) {
            $.ajax({
                type: "get",
                url: `/cfp/projet/evaluation/checkEval/${idProjet}/${idEmploye}`,
                dataType: "json",
                success: callback
            });
        }

        function populateQuestions(res, idEmploye, idProjet) {
            let form_method = $(`#form_method_${idEmploye}`).attr('action', '/cfp/projet/evaluation/chaud');
            let contentEval = $('#content_eval_' + idEmploye);
            let general = $('.general_' + idEmploye);
            let val_comment = $('.val_comment_' + idEmploye);
            let com1 = $('.com1_' + idEmploye);
            let com2 = $('.com2_' + idEmploye);
            let btn_eval = $('.btn_submit_eval_' + idEmploye);

            contentEval.html('');
            general.html('');
            val_comment.html('');
            com1.html('');
            com2.html('');
            btn_eval.html('');

            res.typeQuestions.forEach(v_type => {
                if (v_type.idTypeQuestion != 5) {
                    let sectionHtml = `
                <div class="p-4 rounded-md border border-gray-200 border-dashed flex flex-col gap-2">
                    <label class="text-xl font-semibold text-gray-700">${v_type.typeQuestion}</label>
                    <div id="eval_type_${v_type.idTypeQuestion}_${idEmploye}" class="flex flex-col gap-1"></div>
                </div>`;
                    contentEval.append(sectionHtml);
                }
            });

            res.questions.forEach(v_eval => {
                let evalType = $(`#eval_type_${v_eval.idTypeQuestion}_${idEmploye}`);
                let questionHtml = `
                <div class="inline-flex items-center gap-1 w-full">
                    <div class="w-[60%]">
                        <input type="hidden" name="idProjet" value="${idProjet}">
                        <input type="hidden" name="idEmploye" value="${idEmploye}">
                        <input type="hidden" name="idQuestion[]" value="${v_eval.idQuestion}">
                        <p class="text-base text-gray-700">${v_eval.question}</p>
                    </div>
                    <div class="inline-flex items-center gap-2 heat-rating" data-question-id="${v_eval.idQuestion}">
                        ${[1,2,3,4,5].map(i => `<div class="rating-block flex items-center justify-center w-12 cursor-pointer ${["one", "two", "three", "four", "five"][i - 1]}" data-value="${i}">${i}</div>`).join('')}
                        <input type="hidden" name="eval_note[]" value="5">
                    </div>
                </div>`;
                evalType.append(questionHtml);
            });

            general.append(createGeneralRating());
            val_comment.append(`<x-input type="textarea" name="idValComment" />`);
            com1.append(`<x-input type="textarea" name="com1" />`);
            com2.append(`<x-input type="textarea" name="com2" />`);
            btn_eval.append(`<button class="btn btn-primary bg-[#A462A4]" type="submit">Valider mes réponses</button>`);
        }

        function addRatingHandlers() {
            $('.heat-rating').each(function() {
                let ratingBlocks = $(this).find('.rating-block');
                ratingBlocks.click(function() {
                    let rating = $(this).data('value');
                    ratingBlocks.css('opacity', '0.2');
                    $(this).css('opacity', 1);
                    $(this).closest('.heat-rating').find('input').val(rating);
                });
            });
        }

        function populateExistingResponses(res, idEmploye, idProjet) {
            let contentEval = $('#content_eval_' + idEmploye);
            let general = $('.general_' + idEmploye);
            let val_comment = $('.val_comment_' + idEmploye);
            let com1 = $('.com1_' + idEmploye);
            let com2 = $('.com2_' + idEmploye);
            let check_examiner = $('.examiner_eval_check_' + idEmploye);
            let modif_eval = $('#modif_eval_' + idEmploye);

            contentEval.html('');
            general.html('');
            val_comment.html('');
            com1.html('');
            com2.html('');
            check_examiner.html('');
            modif_eval.html('');

            res.questions.forEach(v_eval => {
                let noteText = {
                    0: 'Non définie',
                    1: 'Insatisfaisant',
                    2: 'Faible',
                    3: 'Moyen',
                    4: 'Bien',
                    5: 'Excellent'
                };
                let note = res.notes.find(n => n.idQuestion === v_eval.idQuestion)?.note || 0;
                contentEval.append(`
                    <div class="grid grid-cols-6 gap-1 w-full">
                        <div class="grid col-span-4 grid-cols-subgrid">
                            <p class="text-base text-gray-700">${v_eval.question}</p>
                        </div>
                        <div class="grid col-span-1 note_val_${v_eval.idQuestion} w-12 flex items-center ${['bg-green-500', 'bg-green-300', 'bg-amber-500', 'bg-red-300', 'bg-red-500'][v_eval -1]}">${note}</div>
                        <div class="grid col-span-1 note_${v_eval.idQuestion}">${noteText[note]}</div>
                    </div>`);
            });

            general.append(`<div id="raty_notation_${idEmploye}">${res.one.generalApreciate}</div>`);
            val_comment.append(`<p>${res.one.idValComment}</p>`);
            com1.append(`<p>${res.one.com1}</p>`);
            com2.append(`<p>${res.one.com2}</p>`);

            res.examiner.forEach(v_ex => {
                check_examiner.text("Fiche d'évaluation remplie par ");
                check_examiner.append(`${v_ex.name_examiner} ${v_ex.firstname_examiner || ''}`);
            });

            modif_eval.append(
                `<button type="button" class="btn btn-primary !bg-[#A462A4] !text-white" onclick="editEval(${idProjet}, ${idEmploye})">Modifier la fiche</button>`
            );
        }

        function createGeneralRating() {
            return `
                <div class="inline-flex items-center gap-2 heat-rating">
                    ${[1,2,3,4,5].map(i => `<div class="rating-block flex items-center justify-center w-12 cursor-pointer ${["one", "two", "three", "four", "five"][i - 1]}" data-value="${i}">${i}</div> `).join('')}
                    <input type="hidden" name="generalApreciate" value="5">
                </div>
            `;
        }


        function showEvalTable(idProjet, idEmploye) {
            $.ajax({
                type: "get",
                url: "/cfp/projet/evaluation/checkEval/" + idProjet + "/" + idEmploye,
                dataType: "json",
                success: function(res) {

                    let appriciation = $('.appreciation_' + idEmploye);
                    appriciation.html('');

                    if (res.checkEval <= 0) {
                        appriciation.text('');
                    } else {
                        appriciation.raty({
                            score: res.one.generalApreciate,
                            space: false,
                            readOnly: true
                        });

                        appriciation.addClass(`w-4 md:w-5`);
                    }
                }
            });
        }

        function editEval(idProjet, idEmploye, e) {

            $.ajax({
                type: "GET",
                url: `/cfp/projet/evaluation/checkEval/${idProjet}/${idEmploye}`,
                dataType: "json",
                success: function(res) {
                    // Sélections des éléments pour éviter des requêtes répétitives
                    let form_method = $(`#form_method_${idEmploye}`).attr('action',
                        '/cfp/projet/evaluation/editEval');

                    let _method = $(`._method_${idEmploye}`).append(`@method('PATCH')`);

                    let content_eval = $(`#content_eval_${idEmploye}`).empty();
                    let general = $(`.general_${idEmploye}`).empty();
                    let val_comment = $(`.val_comment_${idEmploye}`).empty();
                    let com1 = $(`.com1_${idEmploye}`).empty();
                    let com2 = $(`.com2_${idEmploye}`).empty();
                    let btn_eval = $(`.btn_submit_eval_${idEmploye}`).empty();

                    // Générer les blocs de type questions
                    res.typeQuestions.forEach(v_type => {
                        if (v_type.idTypeQuestion !== 5) {
                            content_eval.append(`
                        <div class="p-4 rounded-md border-[1px] border-gray-200 border-dashed flex flex-col gap-2">
                            <div class="inline-flex items-center w-full gap-4">
                                <label class="text-xl font-semibold text-gray-700 type_${v_type.idTypeQuestion}">${v_type.typeQuestion}</label>
                                <div class="inline-flex items-center gap-3">
                                    <label id="total_1" class="text-base font-bold text-gray-500"></label>
                                </div>
                            </div>
                            <div id="eval_type_${v_type.idTypeQuestion}_${idEmploye}" class="flex flex-col gap-1"></div>
                        </div>
                    `);
                        }

                        let eval_type = $(`#eval_type_${v_type.idTypeQuestion}_${idEmploye}`).empty();

                        // Générer les questions et les évaluations
                        res.questions.forEach(v_eval => {
                            if (v_eval.idTypeQuestion === v_type.idTypeQuestion) {
                                eval_type.append(createEvaluationRow(v_eval, idEmploye));
                                setRating(res.notes, v_eval.idQuestion);
                            }
                        });
                    });

                    // Ajouter l'évaluation générale
                    general.append(createGeneralEvaluation());

                    setRating(res.one, idEmploye, true);

                    // Ajouter les champs de commentaire
                    val_comment.append(`<x-input class="val_comment" type="textarea" name="idValComment" />`);
                    com1.append(`<x-input class="com1" type="textarea" name="com1" />`);
                    com2.append(`<x-input type="textarea" name="com2" class="com2" />`);

                    let inputCom1 = $('.com1');
                    let inputCom2 = $('.com2');
                    let inputcomment = $('.val_comment');

                    inputCom1.val(res.one.com1);
                    inputCom2.val(res.one.com2);
                    inputcomment.val(res.one.idValComment);

                    // Bouton de validation
                    btn_eval.append(`<div class="inline-flex items-center justify-end w-full pt-2">
                            <x-btn-primary type="submit">Valider mes réponses</x-btn-primary>
                        </div>`);
                }
            });
        }

        // Fonction pour générer une ligne d'évaluation
        function createEvaluationRow(v_eval, idEmploye) {
            return `
            <div class="inline-flex items-center gap-1 w-full formQuestion_${v_eval.idQuestion}">
                <div class="w-[60%]">
                    <input type="hidden" name="idProjet" value="{{ $projet->idProjet }}">
                    <input type="hidden" name="idEmploye" value="${idEmploye}">
                    <input type="hidden" name="idQuestion[]" value="${v_eval.idQuestion}">
                    <p class="text-base text-gray-700 pQuestion" data-val="1">${v_eval.question}</p>
                </div>
                <div class="inline-flex items-center gap-2 heat-rating">
                    ${[1, 2, 3, 4, 5].map(value => `<div class="rating-block_${v_eval.idQuestion} rating-block w-20 ${["one", "two", "three", "four", "five"][value - 1]} flex items-center cursor-pointer justify-center rating" data-value="${value}">${value}</div>`).join('')}
                    <div class="ratings_${v_eval.idQuestion} text-transparent">0</div>
                    <input type="hidden" value="" name="eval_note[]" id="ratings-input_${v_eval.idQuestion}">
                </div>
            </div>`;
        }

        // Fonction pour générer l'évaluation générale
        function createGeneralEvaluation() {
            return `
            <div class="inline-flex items-center gap-2 heat-rating">
                ${[1, 2, 3, 4, 5].map(value => ` <div class="flex items-center justify-center w-20 cursor-pointer rating-block-note ${["one", "two", "three", "four", "five"][value - 1]}" data-value="${value}">${value}</div>`).join('')}
                <div class="text-transparent ratings">0</div>
                <input type="hidden" value="5" name="generalApreciate" id="ratings-input-note">
            </div>`;
        }

        // Fonction pour gérer les étoiles de notation
        function setRating(notes, idQuestion, isGeneral = false) {

            let ratingBlocks = isGeneral ? $('.rating-block-note') : $(`.rating-block_${idQuestion}`);
            let ratings = isGeneral ? $('.ratings') : $(`.ratings_${idQuestion}`);
            let ratingInput = isGeneral ? $('#ratings-input-note') : $(`#ratings-input_${idQuestion}`);

            let rating = null;
            if (isGeneral) {
                console.log("one", notes);
                rating = notes.generalApreciate;
            } else {
                console.log("tafa tsika eh", notes);
                notes.forEach(v_nt => {
                    if (v_nt.idQuestion == idQuestion) {
                        rating = v_nt.note;
                    }
                });
            }

            ratingBlocks.css('opacity', '0.2');
            ratings.html(rating);
            ratingInput.val(rating);

            ratingBlocks.each(function() {
                if (parseFloat($(this).attr('data-value')) == rating) {
                    $(this).css('opacity', 1);
                }
            });

            ratingBlocks.click(function() {
                let newRating = parseFloat($(this).attr('data-value'));
                console.log("newRating :", newRating);
                ratingBlocks.css('opacity', '0.2');
                ratings.html(newRating);
                ratingInput.val(newRating);

                ratingBlocks.each(function() {
                    if (parseFloat($(this).attr('data-value')) == newRating) {
                        $(this).css('opacity', 1);
                    }
                });
            });
        }


        // ================ SKILLS MATRIX ===============
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
                                        <input type="hidden" name="idProjet" value="{{ $projet->idProjet }}" data-id_projet="{{ $projet->idProjet }}">
                                        <input type="hidden" name="idEmploye" value="${idEmploye}" data-id_employe="${idEmploye}">
                                        <input type="hidden" name="idQuestion[]" value="">
                                        <p class="text-base text-gray-700 pQuestion" data-val="1"></p>
                                        </div>
                                        <div class="inline-flex items-center gap-2 heat-rating">
                                        <div class="flex items-center justify-center w-12 cursor-pointer rating-block-before_ one" data-value_before="1">1</div>
                                        <div class="flex items-center justify-center w-12 cursor-pointer rating-block-before_ two" data-value_before="2">2</div>
                                        <div class="flex items-center justify-center w-12 cursor-pointer rating-block-before_ three" data-value_before="3">3</div>
                                        <div class="flex items-center justify-center w-12 cursor-pointer rating-block-before_ four" data-value_before="4">4</div>
                                        <div class="flex items-center justify-center w-12 cursor-pointer rating-block-before_ five" data-value_before="5">5</div>
                                        <div class="text-transparent ratings-before_">0</div>
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
                                        <input type="hidden" name="idQuestion[]" value="">
                                        <p class="text-base text-gray-700 pQuestion" data-val="1"></p>
                                        </div>
                                        <div class="inline-flex items-center gap-2 heat-rating">
                                        <div class="flex items-center justify-center w-12 cursor-pointer rating-block-after_ one" data-value_after="1">1</div>
                                        <div class="flex items-center justify-center w-12 cursor-pointer rating-block-after_ two" data-value_after="2">2</div>
                                        <div class="flex items-center justify-center w-12 cursor-pointer rating-block-after_ three" data-value_after="3">3</div>
                                        <div class="flex items-center justify-center w-12 cursor-pointer rating-block-after_ four" data-value_after="4">4</div>
                                        <div class="flex items-center justify-center w-12 cursor-pointer rating-block-after_ five" data-value_after="5">5</div>
                                        <div class="text-transparent ratings-after_">0</div>
                                        <input type="hidden" value="5" name="eval_note[]" id="after-input_">
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
                            <button data-bs-dismiss="modal"
                                class="save_submit_button focus:outline-none px-3 bg-[#A462A4] py-2 ml-3 rounded-md text-white hover:bg-[#A462A4]/90 transition duration-200 text-base">Confirmer</button>
                        </div>
                        </div>
                    </div>
                </div>`);



            (function() {
                var ratingBlocksBefore = $('.rating-block-before_');
                var ratingBlocksAfter = $('.rating-block-after_');
                var ratingsBefore = $('.ratings-before_');
                var ratingsAfter = $('.ratings_');

                var ratingBeforeValue = 0;
                var ratingAfterValue = 0;

                ratingBlocksBefore.click(function() {
                    var ratingBefore = parseFloat($(this).attr('data-value_before'));
                    ratingBeforeValue = ratingBefore;
                    ratingBlocksBefore.css('opacity', '0.2');
                    $(this).css('opacity', '1');
                    ratingsBefore.html(ratingBefore);
                    $('#ratings-input_').val(ratingBefore);
                });

                ratingBlocksAfter.click(function() {
                    var ratingAfter = parseFloat($(this).attr('data-value_after'));
                    ratingAfterValue = ratingAfter;
                    ratingBlocksAfter.css('opacity', '0.2');
                    $(this).css('opacity', '1');
                    ratingsAfter.html(ratingAfter);
                    $('#after-input_').val(ratingAfter);
                });

                $('.save_submit_button').on('click', function(event) {
                    event.preventDefault();

                    var idEmploye = $('input[name="idEmploye"]').val();
                    var idProjet = $('input[name="idProjet"]').val();
                    var beforeValue = $('#ratings-input_').val();
                    var afterValue = $('#after-input_').val();

                    $.ajax({
                        type: "POST",
                        url: "{{ route('evaluation.apprenant') }}",
                        data: JSON.stringify({
                            idEmploye: idEmploye,
                            idProjet: idProjet,
                            before: beforeValue,
                            after: afterValue
                        }),
                        contentType: "application/json",
                        success: function(response) {
                            toastr.success(response.success, 'Succès', {
                                timeOut: 2000
                            });
                            if (`{{ $projet->project_type }}` == 'Intra') {
                                getApprenantAdded(idProjet);
                            } else {
                                getApprenantAddedInter(idProjet);
                            }
                        },
                        error: function(error) {
                            toastr.error('Erreur lors de la soumission', 'Erreur', {
                                timeOut: 2000
                            });
                        }
                    });
                });

                getRatings(idEmploye, idProjet);

                var myModalEl = $('#myModal');
                var modal = new bootstrap.Modal(myModalEl);
                modal.show();
            })();

        }

        function getRatings(idEmploye, idProjet) {
            $.ajax({
                url: `/evaluation/aprrenant/${idEmploye}/${idProjet}`,
                type: 'GET',
                success: function(data) {
                    if (data) {
                        $('#ratings-before_').text(data.avant);
                        $('#ratings-after_').text(data.apres);

                        $('#ratings-input_').val(data.avant);
                        $('#after-input_').val(data.apres);

                        highlightRatingBlocks(data.avant, data.apres);
                    }
                },
                error: function(error) {
                    console.error('Error fetching ratings:', error);
                }
            });
        }


        function highlightRatingBlocks(before, after) {
            $('.rating-block-before_').each(function() {
                if ($(this).data('value_before') == before) {
                    $(this).css('opacity', 1);
                } else {
                    $(this).css('opacity', 0.5);
                }
            });

            $('.rating-block-after_').each(function() {
                if ($(this).data('value_after') == after) {
                    $(this).css('opacity', 1);
                } else {
                    $(this).css('opacity', 0.5);
                }
            });
        }

        // PRESENCE
        function canva_presence() {
            var presence_canvas = $('#presence_canvas');

            presence_canvas.html('');

            var idProjet = {{ $projet->idProjet }};
            $.ajax({
                type: "get",
                url: `/cfp/projets/${idProjet}/details`,
                dataType: "json",
                success: function(res) {
                    if (res.seanceCount > 0 && (res.apprsCount > 0 || res.apprenantInterCount > 0)) {
                        presence_canvas.append(`<li onclick="__global_drawer('offcanvasPresence')">
                                                    <span>
                                                        <i class="fa-solid fa-list-check"></i>
                                                        Emargement
                                                    </span>
                                                </li>`)
                    } else {
                        presence_canvas.append(`<li onclick="__modal_alert('offcanvasPresence')">
                                                    <span>
                                                        <i class="fa-solid fa-list-check"></i>
                                                        Emargement
                                                    </span>
                                                </li>`)
                    }
                }
            });
        }

        function __modal_alert(id) {
            var modal_confirmation = $('#modal_content_master');
            modal_confirmation.html('');

            modal_confirmation.append(`<div class="modal fade" id="modal_${id}" tabindex="-1" data-bs-backdrop="static">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                <div class="font-medium text-gray-500 bg-gray-200 modal-header">
                                                    <h5 class="text-lg modal-title">Information</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="flex flex-col items-center modal-body">
                                                    <p class="text-lg text-gray-500">Emargement non disponible ! Veuillez ajouter au moins une session et un apprenant à ce projet.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="px-4 py-2 text-base text-white transition duration-200 scale-95 bg-[#A462A4] rounded-md hover:scale-100 hover:bg-[#A462A4]/90 text-medium"
                                                    data-bs-dismiss="modal">D'accord</button>
                                                </div>
                                                </div>
                                            </div>
                                        </div>`);

            var myModalEl = $(`#modal_${id}`);
            var modal = new bootstrap.Modal(myModalEl);
            modal.show();
        }


        // récupérer les présences en batch (ensemble)
        function getPresenceUnique(idProjet, employes) {
            $.ajax({
                type: "GET",
                url: "/cfp/projet/apprenants/checkPresences/" + idProjet, // Nouvelle route
                data: {
                    employes: employes
                }, // Envoyer tous les ID des employés
                dataType: "json",
                success: function(res) {
                    res.forEach(item => {
                        var unique = $('.uniquePresence_' + item.idEmploye);
                        unique.removeClass('bg-green-500 bg-amber-500 bg-red-500 bg-gray-700');

                        switch (item.checking) {
                            case 3:
                                unique.addClass('bg-green-500').attr('title', 'Toujours présent');
                                break;
                            case 2:
                                unique.addClass('bg-amber-500').attr('title',
                                    'Absent ou partiellement présent au moins une fois');
                                break;
                            case 1:
                                unique.addClass('bg-red-500').attr('title', 'Toujours absent');
                                break;
                            default:
                                unique.addClass('bg-gray-700').attr('title', 'Présence non définie');
                                break;
                        }
                    });

                    loadBsTooltip();
                }
            });
        }


        const list_resto = [{
                id: 1,
                label: "Petit déjeuner",
                couleur: "A97C75",
                icon: "fa-bread-slice",
                pin: "primary"
            },
            {
                id: 2,
                label: "Pause café matin",
                couleur: "3C1518",
                icon: "fa-mug-hot",
                pin: "dark"
            },
            {
                id: 3,
                label: "Déjeuner",
                couleur: "F5A300",
                icon: "fa-bowl-rice",
                pin: "warning"
            },
            {
                id: 4,
                label: "Pause café après-midi",
                couleur: "3C1518",
                icon: "fa-mug-hot",
                pin: "dark"
            },
            {
                id: 5,
                label: "Dîner",
                couleur: "2725A7",
                icon: "fa-utensils",
                pin: "danger"
            },
            {
                id: 6,
                label: "Bouteille d'eau",
                couleur: "00B4CC",
                icon: "fa-bottle-water",
                pin: "info"
            }
        ];

        const checked_restauration = @json($restaurations);
        const array_checked_restauration = [];
        checked_restauration.forEach(object => {
            array_checked_restauration.push(object.idRestauration);
        });


        function RestaurationList() {
            var content_restauration_list = $('#content_restauration_list');
            content_restauration_list.html('');

            if (checked_restauration != null) {
                $.each(checked_restauration, function(i, v) {
                    content_restauration_list.append(
                        `
                                <tr>
                                    <td><i class='fa-solid rest_${v.idRestauration}'></i></td>
                                    <td>
                                        <label class="inline-flex items-center gap-2 cursor-pointer">
                                            <span class=""><strong>${v.typeRestauration}</strong> offert par ${v.paidBy == 1 ? "le <strong>centre de formation</strong>" : "l'<strong>entreprise</strong>"}</span>
                                        </label>
                                    </td>
                                    <td class="text-right">
                                        <button onclick="deleteRestauration(${v.idRestauration})" class="opacity-50 btn btn-ghost btn-sm"><i class="fa-solid fa-xmark"></i></button>
                                    </td>
                                </tr>
                                `
                    );

                    content_restauration_list.ready(function() {
                        var rest = $(`.rest_${v.idRestauration}`);
                        switch (v.idRestauration) {
                            case 1:
                                rest.addClass(`fa-bread-slice text-[#A97C75]`);
                                break;
                            case 2:
                                rest.addClass(`fa-mug-hot text-[#3C1518]`);
                                break;

                            case 3:
                                rest.addClass(`fa-bowl-rice text-[#F5A300]`);
                                break;

                            case 4:
                                rest.addClass(`fa-mug-hot text-[#3C1518]`);
                                break;

                            case 5:
                                rest.addClass(`fa-utensils text-[#2725A7]`);
                                break;

                            case 6:
                                rest.addClass(`fa-bottle-water text-[#00B4CC]`);
                                break;

                            default:
                                break;
                        }
                    });
                });
            }
        }

        function deleteRestauration(id) {
            console.log(id);

            $.ajax({
                type: "POST",
                url: `/cfp/projets/deleteRestauration/{{ $projet->idProjet }}/${id}`,
                data: {
                    idProjet: {{ $projet->idProjet }},
                    idRestauration: id
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.success, 'Succès', {
                            timeOut: 2000
                        });
                        location.reload();
                    } else {
                        toastr.error(response.error, 'Erreur', {
                            timeOut: 2000
                        });
                    }
                }
            });
        }

        function openRestauration(idProjet) {
            var content = `
            <div class="w-1/3 max-w-5xl du_modal-box">
                <div class="inline-flex items-center justify-between w-full">
                    <h3 class="text-lg font-bold text-slate-800">Restauration</h3>
                    <form method="dialog">
                        <button class="btn btn-sm btn-circle btn-ghost">✕</button>
                    </form>
                </div>

                <div class="mt-3 overflow-x-auto">
                    <div role="alert bg-slate-100" class="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-6 h-6 stroke-info shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <div class="">Merci de sélectionner le donateur qui fournit chaque repas pour ce projet !</div>
                        </div>
                    </div>

                    <table class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Repas</th>
                                <th colspan="2">
                                    <td>CFP</td>
                                    <td>Entreprise</td>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="restaurationTableBody"></tbody>
                    </table>
                </div>

                <div class="inline-flex justify-end w-full mt-4">
                    <div class="modal-action">
                        <form method="dialog">
                            <button class="btn btn-ghost">Annuler</button>
                            <button class="btn btn-primary" onclick="saveRestauration(event)">Enregistrer</button>
                        </form>
                    </div>
                </div>
            </div>
            `;
            openDialog(content);
            loadRestauration(idProjet);
        }

        function toggleRadio(radio) {
            var $radio = $(radio);
            var name = $radio.attr('name');

            if ($radio.data('selected')) {
                $radio.prop('checked', false).data('selected', false);
            } else {
                $(`input[name="${name}"]`).prop('checked', false).data('selected', false);
                $radio.prop('checked', true).data('selected', true);
            }
        }

        function loadRestauration(idProjet) {
            $.ajax({
                url: `/cfp/projets/getRestauration/${idProjet}`,
                method: 'GET',
                success: function(response) {
                    var tableBody = $('#restaurationTableBody');
                    tableBody.empty();

                    var mealNames = [
                        'Petit Déjeuner',
                        'Pause café matin',
                        'Déjeuner',
                        'Pause café après-midi',
                        'Dîner',
                        'Bouteille d\'eau'
                    ];

                    mealNames.forEach(function(meal, index) {
                        var restauration = response.find(r => r.idRestauration === (index + 1));

                        var row = `
                            <tr>
                                <th>${index + 1}</th>
                                <td>${meal}</td>
                                <td colspan=2>
                                    <td><input type="radio" name="restauration${index + 1}" onclick="toggleRadio(this)" class="radio" value="1" ${restauration && restauration.paidBy == 1 ? 'checked' : ''}/></td>
                                    <td><input type="radio" name="restauration${index + 1}" onclick="toggleRadio(this)" class="radio" value="2" ${restauration && restauration.paidBy == 2 ? 'checked' : ''}/></td>
                                </td>
                            </tr>
                        `;
                        tableBody.append(row);
                    });

                    // Initialisation de l'état de sélection pour chaque radio
                    $('.radio').each(function() {
                        $(this).data('selected', $(this).is(':checked'));
                    });
                },
                error: function(xhr, status, error) {
                    alert('Erreur lors de la récupération des données : ' + error);
                }
            });
        }

        function getMealName(idRestauration) {
            switch (idRestauration) {
                case 1:
                    return "Petit Déjeuner";
                case 2:
                    return "Pause café matin";
                case 3:
                    return "Déjeuner";
                case 4:
                    return "Pause café après-midi";
                case 5:
                    return "Dîner";
                case 6:
                    return "Bouteille d'eau";
                default:
                    return "Repas inconnu";
            }
        }

        function manageRestauration(id, checked) {
            if (!checked) {
                const inputs = $(".form-check-input");
                const paidBy = inputs.filter(':checked').attr('id');

                $.ajax({
                    type: "POST",
                    url: "/cfp/projets/addRestauration/{{ $projet->idProjet }}/" + id + "/" + paidBy,
                    dataType: "json",
                    success: function(response) {
                        if (response.error) {

                            toastr.error(response.error, 'Erreur', {
                                timeOut: 2000
                            })
                        } else {
                            toastr.success(response.success, 'Succès', {
                                timeOut: 2000
                            });
                            location.reload();
                        }
                    }
                })
            } else {
                $.ajax({
                    type: 'POST',
                    url: "/cfp/projets/deleteRestauration/{{ $projet->idProjet }}/" + id,
                    dataTpe: "json",
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.success, 'Succès', {
                                timeOut: 2000
                            });
                            location.reload();
                        } else {
                            toastr.error(response.error, 'Erreur', {
                                timeOut: 2000
                            });
                        }
                    }
                });
            }
        }

        function saveRestauration(event) {
            event.preventDefault();

            var data = [];
            var dataDeleted = [];

            for (var i = 1; i <= 6; i++) {
                var selectedValue = $('input[name="restauration' + i + '"]:checked').val();
                if (selectedValue) {
                    data.push({
                        idRestauration: i,
                        paidBy: selectedValue
                    });
                } else {
                    dataDeleted.push({
                        idRestauration: i
                    });
                }
            }

            var projetId = {{ $projet->idProjet }};
            data.forEach(item => item.idProjet = projetId);
            dataDeleted.forEach(item => item.idProjet = projetId);

            var addPromises = data.map(item =>
                $.ajax({
                    url: `/cfp/projets/addRestauration`,
                    method: 'POST',
                    data: {
                        idProjet: item.idProjet,
                        idRestauration: item.idRestauration,
                        paidBy: item.paidBy
                    }
                })
            );

            var deletePromises = dataDeleted.map(item =>
                $.ajax({
                    url: `/cfp/projets/deleteRestauration/${item.idProjet}/${item.idRestauration}`,
                    method: 'POST'
                })
            );

            Promise.all([...addPromises, ...deletePromises])
                .then(() => {
                    toastr.success('Restauration(s) traitée(s) avec succès!',
                        'Succès', {
                            timeOut: 1500
                        });
                    location.reload();
                })
                .catch((error) => {
                    toastr.error('Une erreur est survenue: ' + error, 'Erreur', {
                        timeOut: 2000
                    });
                });
        }

        function submitInfoBase() {
            var idModule = $('#changeModuleSelect').val();
            var dateDebut = $('.date_deb_input').val();
            var dateFin = $('.date_fin_input').val();
            var idModalite = $('.project_idModalite_detail').val();

            try {
                if ({{ $projet->idModule }} != idModule) {
                    updateModuleProjetDetail({{ $projet->idProjet }});
                }

                if ({{ $projet->dateDebut }} != dateDebut) {
                    updateDateDebut({{ $projet->idProjet }});
                }

                if ({{ $projet->dateFin }} != dateFin) {
                    updateDateFin({{ $projet->idProjet }});
                }

                if ({{ $projet->idModalite }} != idModalite) {
                    updateModalite({{ $projet->idProjet }});
                }

                location.reload();
            } catch (error) {
                toastr.error('Erreur', 'Veuillez vérifier vos informations', {
                    timeOut: 1500
                });
            }
        }

        //  ==================== MODULE =======================
        function updateModuleProjetDetail(idProjet) {
            var idModule = $('#changeModuleSelect').val();
            $.ajax({
                type: "patch",
                url: "/cfp/projets/" + idProjet +
                    "/update/module",
                data: {
                    _token: '{!! csrf_token() !!}',
                    idModule: idModule
                },
                dataType: "json",
                success: function(res) {},
                error: function(error) {
                    console.log(error);
                }
            });
        }

        function updateDateDebut(idProjet) {
            var dateDebut = $('.date_deb_input').val();
            $.ajax({
                type: "patch",
                url: "/cfp/projets/" + idProjet +
                    "/update/date",
                data: {
                    _token: '{!! csrf_token() !!}',
                    dateDebut: dateDebut,
                },
                dataType: "json",
                success: function(res) {},
                error: function(error) {
                    console.log(error);
                }
            });
        }

        function updateDateFin(idProjet) {
            var dateFin = $('.date_fin_input').val();
            $.ajax({
                type: "patch",
                url: "/cfp/projets/" + idProjet +
                    "/update/date",
                data: {
                    _token: '{!! csrf_token() !!}',
                    dateFin: dateFin,
                },
                dataType: "json",
                success: function(res) {},
                error: function(error) {
                    console.log(error);
                }
            });
        }

        function updateModalite(idProjet) {
            var idModalite = $('.project_idModalite_detail').val();
            $.ajax({
                type: "patch",
                url: "/cfp/projets/" + idProjet + "/update/modalite",
                data: {
                    _token: '{!! csrf_token() !!}',
                    idModalite: idModalite
                },
                dataType: "json",
                success: function(res) {},
                error: function(error) {
                    console.log(error);
                }
            });
        }

        // =============== PROGRAMME ======================
        function getProgramProject(idModule) {
            var contentToFill = $('#get_all_programme_project');
            $.ajax({
                type: "get",
                url: `/cfp/projets/${idModule}/getProgrammeProject`,
                dataType: "json",
                beforeSend: function() {
                    contentToFill.html('');
                    contentToFill.append(
                        `<p class="loadingProgramProject">Chargement ...</p>`
                    );
                },
                complete: function() {
                    $('.loadingProgramProject').hide();
                },
                success: function(res) {
                    var i = 1;
                    contentToFill.html('');
                    if (res.programmes.length > 0) {
                        $.each(res.programmes, function(key,
                            val) {
                            contentToFill.append(`
                            <div class="grid col-span-1">
                                <div class="flex flex-col gap-1">
                                    <div class="inline-flex items-center justify-between w-full">
                                        <p class="text-lg font-semibold text-gray-700">Module ` + i++ + `</p>
                                    </div>
                                    <p class="text-base font-semibold text-gray-500">` + val.program_title + `</p>
                                    <span>` + val.program_description + `</span>
                                    <hr class="sm:visible md:hidden border-[1px] border-gray-400 my-2">
                                </div>
                            </div>
                            `);
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


        //  ================ TYPE DE FINANCEMENT ====================
        function updateFinancement(idProjet, idPaiement, idCfp_inter = 0) {
            if (idCfp_inter == 0) {
                idCfp_inter = 0;
            }
            $.ajax({
                type: "patch",
                url: "/cfp/projets/update/financement/" + idProjet + "/" + idCfp_inter,
                data: {
                    _token: '{!! csrf_token() !!}',
                    idPaiement: idPaiement
                },
                dataType: "json",
                success: function(res) {

                    if (res.success) {
                        toastr.success(res.success,
                            'Succès', {
                                timeOut: 1500
                            });

                        var typeFinancement = $('.typeFinancement');
                        typeFinancement.html('');

                        var texte = '';
                        switch (idPaiement) {
                            case 1:
                                texte = "Fonds Propres"
                                break;
                            case 2:
                                texte = "FMFP"
                                break;

                            case 3:
                                texte = "Autres"
                                break;

                            default:
                                break;
                        }

                        typeFinancement.text(texte);
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


        // ============== MANAGE PARTICULIER ==============
        function getAllParts() {
            $.ajax({
                type: "get",
                url: "/cfp/projets/parts/getAllParts",
                dataType: "json",
                success: function(res) {
                    var all_part = $('#all_part');
                    all_part.html('');

                    if (res.parts.length <= 0) {
                        all_part.append(`<x-no-data texte="Pas de données"></x-no-data>`);
                    } else {
                        $.each(res.parts, function(key, val) {
                            all_part.append(`
                            <tr class="list list_${val.idEtp}">
                                    <td class="capitalize">
                                        <span class="inline-flex items-center">
                                            <div class="mr-3 avatar">
                                                <div class="w-12 rounded-full">
                                                    ${ val.part_photo ? ` <img src="{{ $endpoint }}/{{ $bucket }}/img/employes/${val.part_photo}" />` : `<span class="flex items-center justify-center w-full h-full text-xl uppercase text-slate-600 bg-slate-200">${val.part_name.charAt(0)}</span>`
                                                    }
                                                </div>
                                            </div>
                                            <span class="flex flex-col">
                                                <span class="mr-1 uppercase">${val.part_name}</span> ${val.part_firstname ?? '' }
                                            </span>
                                        </span>
                                    </td>
                                    <td class='text-right'>
                                        <button onclick="managePart('post', {{ $projet->idProjet }}, ${val.idParticulier})" class="rounded-full btn btn-success btn-outline"><i class="text-xl fa-solid fa-plus"></i></button>    
                                    </td>
                                </tr>
                            `);
                        });
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        }

        function managePart(type, idProjet, idParticulier) {
            $.ajax({
                type: type,
                url: `/cfp/projets/${idProjet}/${idParticulier}/part/assign`,
                data: {
                    _token: '{{ csrf_token() }}'
                },
                dataType: "json",
                success: function(res) {
                    if (res.success) {
                        toastr.success(res.success, 'Succès', {
                            timeOut: 1500
                        });

                        getPartAdded(idProjet)
                    } else if (res.error) {
                        toastr.error(res.error, 'Erreur', {
                            timeOut: 1500
                        });
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        }

        function getPartAdded(idProjet) {
            $.ajax({
                type: "get",
                url: "/cfp/projets/" + idProjet + "/getPartAdded",
                dataType: "json",
                success: function(res) {
                    part_table(res.parts);
                    part_drawer(res.parts);
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        }

        function part_drawer(data) {
            var all_part_selected = $('#all_part_selected')
            all_part_selected.html('');

            if (data.length <= 0) {
                all_part_selected.append(`<x-no-data texte="Aucun particulier"/>`)
            } else {
                all_part_selected.append(`
                    <div class="etp_part">
                        <table class="table">
                            <tbody id="drawer_participant"></tbody>
                        </table>
                    </div>`)

                all_part_selected.ready(function() {
                    var data_participant = $(`#drawer_participant`);

                    $.each(data, function(i, val) {
                        data_participant.append(
                            `
                                <tr>
                                    <td class="capitalize">
                                        <span class="inline-flex items-center">
                                            <div class="mr-3 avatar">
                                                <div class="w-12 rounded-full">
                                                    ${ val.part_photo ? ` <img src="{{ $endpoint }}/{{ $bucket }}/img/employes/${val.part_photo}" />` : `<span class="flex items-center justify-center w-full h-full text-xl uppercase text-slate-600 bg-slate-200">${val.part_name.charAt(0) ?? 'I'}</span>`
                                                    }
                                                </div>
                                            </div>
                                            <span class="flex flex-col">
                                                <span class="mr-1 uppercase">${val.part_name}</span> ${val.part_firstname ?? '' }
                                            </span>
                                        </span>
                                    </td>
                                    <td class='text-right'>
                                        <button onclick="managePart('delete', {{ $projet->idProjet }}, ${val.idParticulier})" class="rounded-full btn btn-error btn-outline"><i class="text-xl fa-solid fa-minus"></i></button>    
                                    </td>
                                </tr>`
                        );
                    });

                });
            }
        }

        function part_table(data) {
            var part_table = $('#part_table')
            part_table.html('');

            var countApprDrawer = $('.countApprDrawer');
            countApprDrawer.html('');

            if (data.length <= 0) {
                part_table.html('');
            } else {
                part_table.append(`
                <div class="part_apprenant">
                    <label class="mb-2 text-xl text-slate-700">Particuliers</label>
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="w-[40%]">Nom</th>
                                <th>Présence</th>
                                <th class="text-left">Notation</th>
                                <th class="text-center">Skills</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="data_participant"></tbody>
                    </table>
                </div>`);

                part_table.ready(function() {
                    var data_participant = $(`#data_participant`);
                    countApprDrawer.append(data.length);

                    $.each(data, function(i, val) {
                        data_participant.append(
                            `
                                <tr>
                                    <td class="capitalize">
                                        <span class="inline-flex items-center">
                                            <div class="mr-3 avatar">
                                                <div class="w-12 rounded-full">
                                                    ${ val.part_photo ? ` <img src="{{ $endpoint }}/{{ $bucket }}/img/employes/${val.part_photo}" />` : `<span class="flex items-center justify-center w-full h-full text-xl uppercase text-slate-600 bg-slate-200">${val.part_name.charAt(0)}</span>`
                                                    }
                                                </div>
                                            </div>
                                            <span class="flex flex-col">
                                                <span class="mr-1 uppercase">${val.part_name}</span> ${val.part_firstname ?? '' }
                                            </span>
                                        </span>
                                    </td>
                                    <td><div data-bs-toggle="tooltip" class="w-5 h-5 rounded-md uniquePresence uniquePresence_${val.idParticulier}"></div></td>
                                    <td class="text-left"><span class="appreciation_${val.idParticulier} inline-flex items-center"></span></td>
                                    <td class="text-center cursor-pointer" data-bs-toggle="tooltip" title="Compétence Avant/Après Formation"><span class='text-slate-400'> ${val.avant ?? '--'} |  ${val.apres ?? '--'}</span></td>
                                    <td class="text-right">
                                        <button onclick="getSkills({{ $projet->idProjet }}, ${val.idParticulier})" data-bs-toggle="tooltip" title="Skill matrix" class="btn btn-xs md:btn-sm btn-ghost"><i class="fa-solid fa-compass-drafting"></i></button>
                                        <button onclick="getEvaluation({{ $projet->idProjet }}, ${val.idParticulier})" data-bs-toggle="tooltip" title="Evaluation" class="btn btn-xs md:btn-sm btn-ghost"><i class="fa-solid fa-chart-simple"></i></button>
                                    </td>
                                </tr>`);

                        data_participant.ready(function() {
                            // getPresenceUnique({{ $projet->idProjet }}, val.idParticulier);
                            // showEvalTable({{ $projet->idProjet }}, val.idParticulier);
                            loadBsTooltip();
                        });
                    });
                });
            }
        }


        // ============= GLOBAL DRAWER =============
        function __global_drawer(__offcanvas, sub = false) {
            let __global_drawer = $('#drawer_content_detail');
            __global_drawer.html('');
            var projet = @json($projet);
            var sumHourSession = @json($totalSession ? $totalSession : null)

            switch (__offcanvas) {
                case 'offcanvasGeneral':
                    __global_drawer.append(
                        `<x-drawer-general id="{{ $projet->idProjet }}" ref="{{ $projet->project_reference }}" titre="{{ $projet->project_title }}" description="{{ $projet->project_description }}" projectType="{{ $projet->project_type }}" nbPlace="{{ $nbPlace }}" ></x-drawer-general>`
                    );

                    __global_drawer.ready(function() {

                        var modules = @json($modules);
                        var modalites = @json($modalites);
                        var idModuleProj = {{ $projet->idModule }};
                        var idModaliteProj = {{ $projet->idModalite }};

                        var modal_content_master = $('#modal_content_master');
                        var changeModuleSelect = $('#changeModuleSelect');
                        var date_deb_input = $('.date_deb_input');
                        var date_fin_input = $('.date_fin_input');
                        var project_idModalite_detail = $('.project_idModalite_detail');
                        changeModuleSelect.html('');


                        date_deb_input.val(`{{ $projet->dateDebut ?? '' }}`);
                        date_fin_input.val(`{{ $projet->dateFin ?? '' }}`);

                        var items = `<option disabled selected>Selectionner une formation</option>`;
                        var items_modalite = `<option disabled selected>Selectionner une modalité</option>`

                        $.each(modules, function(i, module) {
                            items += `
                         <option value="${module.idModule }"
                        ${ idModuleProj == module.idModule ? 'selected' : '' }>
                        ${module.module_name}
                    </option>`;
                        });

                        $.each(modalites, function(i, md) {
                            items_modalite += `
                         <option value="${md.idModalite }"
                        ${ idModaliteProj == md.idModalite ? 'selected' : '' }>
                        ${md.modalite}
                    </option>`;
                        });

                        changeModuleSelect.append(items);
                        project_idModalite_detail.append(items_modalite);

                    });
                    break;

                case 'offcanvasSession':
                    var storage = localStorage.getItem('ACCESS_TOKEN');
                    __global_drawer.append(
                        `<x-drawer-session storage="${storage}" ></x-drawer-session>`
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

                    __global_drawer.ready(function() {
                        getAllSalle({{ $projet->idEtp }});
                    });
                    break;

                case 'offcanvasPresence':
                    __global_drawer.append(`<x-drawer-presence></x-drawer-presence>`);

                    if (projet.idCfp_inter == null) {
                        getAllApprPresence({{ $projet->idProjet }});
                    } else {
                        getAllApprPresenceInter({{ $projet->idProjet }});
                    }
                    break;

                case 'offcanvasFrais':
                    if (sub == true) {
                        __global_drawer.append(
                            `<x-drawer-frais onClick="fermeturefrais('{{ $projet->idProjet }}')" :paiements="$paiements" idCfp_inter="{{ $projet->idCfp_inter }}" idProjet="{{ $projet->idProjet }}" idPaiement="{{ $projet->idPaiement }}" sub="true"></x-drawer-frais>`
                        );
                    } else {
                        __global_drawer.append(
                            `<x-drawer-frais onClick="fermeturefrais('{{ $projet->idProjet }}')" :paiements="$paiements" idCfp_inter="{{ $projet->idCfp_inter }}" idProjet="{{ $projet->idProjet }}" idPaiement="{{ $projet->idPaiement }}"></x-drawer-frais>`
                        );
                    }

                    __global_drawer.ready(function() {

                        if ((@json($projet->idSubContractor) != null && @json($projet->idSubContractor) !=
                                {{ $idCfp }}) || @json($projet->idSubContractor) == null) {
                            getfraisAssign({{ $projet->idProjet }}, 0);
                            getAllFrais(0);
                        } else if (@json($projet->idSubContractor) != null && @json($projet->idSubContractor) ==
                            {{ $idCfp }}) {
                            getfraisAssign({{ $projet->idProjet }}, 2);
                            getAllFrais(2);
                        }

                        $.each($('input[name="tvaRadio"]'), function(indexInArray, valueOfElement) {
                            $(this).on('change', function() {
                                var newTaxe = $(this).val();

                                var idProjet = {{ $projet->idProjet }};

                                updateTaxe(idProjet, newTaxe);

                                if ((@json($projet->idSubContractor) != null &&
                                        @json($projet->idSubContractor) !=
                                        {{ $idCfp }}) ||
                                    @json($projet->idSubContractor) == null) {
                                    getfraisAssign({{ $projet->idProjet }}, 0);
                                } else if (@json($projet->idSubContractor) != null &&
                                    @json($projet->idSubContractor) ==
                                    {{ $idCfp }}) {
                                    getfraisAssign({{ $projet->idProjet }}, 2);
                                }

                                updatefraistotal({{ $projet->idProjet }});
                                calculateTotalFrais();
                            });
                        });
                    });
                    break;

                case 'offcanvasSubContractor':
                    __global_drawer.append(`<x-drawer-sub-contractor></x-drawer-sub-contractor>`);
                    getSubContractors();
                    getSubcontractorSelected({{ $projet->idProjet }});
                    break;

                case 'offcanvasParticulier':
                    __global_drawer.append(`<x-drawer-particulier></x-drawer-particulier>`);

                    getAllParts()
                    getPartAdded({{ $projet->idProjet }});
                    break;

                case 'offcanvasDossier':
                    __global_drawer.append(`<x-drawer-folder-project></x-drawer-folder-project>`);

                    getDossierStepper({{ $projet->idProjet }})
                    getDossierSelected({{ $projet->idProjet }});
                    break;

                default:
                    break;
            }

            let offcanvasId = $('#' + __offcanvas)
            var bsOffcanvas = new bootstrap.Offcanvas(offcanvasId);
            bsOffcanvas.show();
        }

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
    </script>


    <script>
        const inputFile = $('#dropzone-file');
        const imgArea = document.querySelector('.img-area');
        const uploadInstructions = document.querySelector('.upload-instructions');
        const dropzone = document.querySelector('.dropzone');

        function handleFiles(files) {
            imgArea.innerHTML = ''; // Effacer le contenu précédent dans img-area

            let validImagesCount = 0; // Compter le nombre d'images valides

            // Mettre à jour le champ input avec les fichiers déposés
            inputFile.files = files;

            if (files.length > 0) {
                // Masquer les instructions uniquement si des images valides sont sélectionnées
                uploadInstructions.style.display = 'none';
            } else {
                uploadInstructions.style.display = 'flex';
            }

            if (files.length > 10) {
                // Créer un conteneur pour le SVG et le message
                const countContainer = document.createElement('div');
                countContainer.classList.add('flex', 'flex-col', 'items-center');

                // Ajouter le message de comptage au conteneur
                const countMessage = document.createElement('p');
                countMessage.textContent = `Nombre de fichiers sélectionnés : ${files.length}`;
                countMessage.classList.add('text-center', 'text-gray-500', 'mt-2');
                countContainer.appendChild(countMessage);

                // Ajouter le conteneur à img-area
                imgArea.appendChild(countContainer);
            } else {
                Array.from(files).forEach(file => {
                    if (file.size <= 5000000) { // Limite de taille de 3 Mo
                        const reader = new FileReader();
                        reader.onload = () => {
                            const imgUrl = reader.result;
                            const img = document.createElement('img');
                            img.src = imgUrl;
                            img.classList.add('h-32', 'w-32', 'object-cover', 'm-2');
                            imgArea.appendChild(img);
                        }
                        reader.readAsDataURL(file);
                        validImagesCount++;
                    } else {
                        toastr.error("La taille de l'image dépasse 5 Mo");
                    }
                });
            }

            // Afficher les instructions si aucune image valide n'est ajoutée
            if (validImagesCount === 0) {
                uploadInstructions.style.display = 'flex';
            }
        }

        // Gestion des événements de sélection de fichiers via l'input
        inputFile.change(function(e) {
            e.preventDefault();
            const files = this.files;
            handleFiles(files);
        });

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

                        if (type == "delete") {
                            window.location.href = '/cfp/projets'
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

        function linkInvitation() {

            var html = `
            <div class="du_modal-box">
                <form method="dialog">
                <button class="absolute btn btn-sm btn-circle btn-ghost right-2 top-2">✕</button>
                </form>
                <h3 class="text-lg font-bold">Invitation!</h3>
                <input type="hidden" name="idProjet" value="{{ $projet->idProjet }}">
                <label class="w-full">
                    <div class="label">
                        <span class="label-text">Lien d'invitation</span>
                    </div>
                    <input type="text" name="link" value="{{ $projet->link }}" class="w-full input input-bordered" />
                </label>
                <label class="w-full">
                    <div class="label">
                        <span class="label-text">Code secret</span>
                    </div>
                    <input type="text" name="secret_code" value="{{ $projet->secret_code }}" class="w-full input input-bordered" />
                </label>
                <div class="du_modal-action">
                    <form method="dialog">
                        <button class="btn">Annuler</button>
                        </form>
                    <button onclick="saveLink({{ $projet->idProjet }})" class="btn btn-primary">Sauvegarder</button>
                </div>
            </div>
            `;

            openDialog(html);
        }

        function saveLink(id) {
            $.ajax({
                type: "PATCH",
                url: `/cfp/projets/${id}/linkInvitation`,
                data: {
                    _token: '{!! csrf_token() !!}',
                    idProjet: $('input[name="idProjet"]').val(),
                    link: $('input[name="link"]').val(),
                    secret_code: $('input[name="secret_code"]').val()
                },
                success: function(res) {
                    if (res.success) {
                        toastr.success(res.success, 'Succès', {
                            timeOut: 1500
                        });
                        location.reload();
                    } else {
                        toastr.error(res.error, "Erreur", {
                            timeOut: 1500
                        });
                    }
                }
            });
        }
    </script>
@endsection
