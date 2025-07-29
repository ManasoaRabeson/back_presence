@extends('layouts.masterEtp')


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
                            </span>

                            <span class="inline-flex items-center gap-2">
                                <div id="raty_notation" class="inline-flex items-center gap-1 rating">
                                </div>
                                {{ number_format($noteGeneral, 1, ',', ' ') }}
                                <span class="text-gray-400"> ({{ $countNotationProjet }} avis)
                                </span>
                            </span>

                            <div class="inline-flex items-center divide-x gap-x-4 gap-y-1 divide-slate-200">
                                {{-- <p class="px-2"><i class="fa-solid fa-building"></i> COLAS</p> --}}
                                <p class="px-2">Début : <span class="capitalize date_deb"></span></p>
                                <p class="px-2">Echéance : <span class="capitalize date_fin"></span></p>
                            </div>

                            <div class="inline-flex items-center gap-x-4 gap-y-1">
                                <span
                                    class="px-3 py-1 rounded-xl text-sm
                                        @switch($projet->project_type)
                                            @case('Intra')
                                                bg-[#1565c0]/20 text-[#1565c0]
                                                @break
                                             @case('Inter')
                                                bg-[#7209b7]/20 text-[#7209b7]
                                                @break 
                                            @case('Interne')
                                                bg-[#250001]/20 text-[#250001]
                                                
                                                @break 
                                            @default
                                                
                                        @endswitch
                                    ">
                                    {{ $projet->project_type }}
                                </span>

                                <span
                                    class="px-3 py-1 rounded-xl text-sm
                                    @switch($projet->modalite)
                                            @case('Présentielle')
                                                bg-[#00b4d8]/20 text-[#00b4d8]
                                                @break
                                             @case('En ligne')
                                                bg-[#fca311]/20 text-[#fca311]
                                                @break
                                                
                                            @case('Blended')
                                                bg-[#005f73]/20 text-[#005f73]
                                                @break   
                                        
                                            @default
                                                
                                        @endswitch
                                ">
                                    {{ $projet->modalite ?? 'Non renseignée' }}
                                </span>

                                <span
                                    class="px-3 py-1 text-sm text-white rounded-xl
                                    @switch($projet->project_status)
                                        @case('En préparation')
                                        bg-[#F8E16F]
                                        @break
                                        @case('Réservé')
                                        bg-[#33303D]
                                        @break
                                        @case('En cours')
                                        bg-[#369ACC]
                                        @break
                                        @case('Terminé')
                                        bg-[#95CF92]
                                        @break
                                        @case('Annulé')
                                        bg-[#DE324C]
                                        @break
                                        @case('Reporté')
                                        bg-[#2E705A]
                                        @break
                                        @case('Planifié')
                                        bg-[#CBABD1]
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
                                        @endswitch">
                                    {{ $projet->project_status }}
                                </span>

                                @if ($projet->project_type == 'Interne')
                                    <button class="btn btn-outline btn-primary btn-sm" onclick="openModalInfo()">
                                        <i class="fa-solid fa-pen"></i>
                                        Editer les informations de base
                                    </button>
                                @endif

                                <p class="line-clamp-6">
                                    @if ($projet->project_description != null)
                                        {!! $projet->project_description !!}
                                    @endif
                                </p>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid col-span-1 mt-4 md:mt-0">
                </div>
            </div>
        </div>
        <div role="tablist" class="w-full p-6 tabs tabs-bordered lg:tabs-lg">
            <input type="radio" name="my_tabs_2" role="tab" class="tab !w-max" aria-label="Vue d'ensemble du projet"
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
                                            Participants
                                        </button>
                                        <div class="flex justify-end w-full pr-4 mt-2">

                                            {{--
                                       {{ dd($projet) }} 
                                       
                                       --}}


                                            {{-- @if ($projet->idCfp == $idCfp || $projet->idCfp_inter == $idCfp) --}}
                                            @if ($projet->idEtp == $idEtp)
                                                <div class="dropdown dropdown-bottom dropdown-end">
                                                    <div tabindex="1" role="button" class="m-1 btn btn-sm btn-outline"><i
                                                            class="fa-solid fa-pen"></i>
                                                        Editer</div>
                                                    <ul tabindex="1"
                                                        class="dropdown-content menu bg-white rounded-box z-[1] w-max p-2 shadow">

                                                        @if ($projet->idCfp_inter == null)
                                                            @isset($projet->etp_name)
                                                                <li onclick="__global_drawer('offcanvasApprenant')">
                                                                    <a>
                                                                        <i class="fa-solid fa-users"></i>
                                                                        Ajouter des apprenants
                                                                    </a>
                                                                </li>
                                                            @endisset
                                                        @else
                                                            @if ($apprenantInter != null)
                                                                <li onclick="__global_drawer('offcanvasApprenant')">
                                                                    <a>
                                                                        <i class="fa-solid fa-users"></i>
                                                                        Ajouter des apprenants
                                                                    </a>
                                                                </li>
                                                            @endif
                                                        @endif
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    </span>
                                </h2>
                                <div id="participant" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                    data-bs-parent="#accordionExampleParticipant">
                                    <div class="accordion-body">
                                        <div id="appr_table" class="mt-4">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4">
                            {{-- Agenda --}}
                            <div class="accordion accordion-flush rounded-box" id="accordionExampleAgenda">
                                <div class="accordion-item card bg-base-100 !w-full shadow-xl !rounded-xl bg-white">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#angenda" aria-expanded="true" aria-controls="angenda">
                                            <i class="mr-3 fa-solid fa-calendar-day"></i>
                                            Agenda
                                        </button>
                                        <div class="flex justify-end w-full pr-4 mt-2">
                                            {{-- @if ($projet->idCfp == $idCfp || $projet->idCfp_inter == $idCfp) --}}
                                            @if ($projet->project_type == 'Interne')
                                                <button
                                                    onclick="__global_drawer('offcanvasSession');
                                                getTotalSeance({{ $projet->idProjet }}) "
                                                    class="m-1 btn btn-sm btn-outline btn-primary"><i
                                                        class="fa-solid fa-pen"></i>
                                                    Editer</button>
                                            @endif
                                            {{-- @endif --}}
                                        </div>
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
                                                    <span
                                                        class="font-semibold text-blue-500">{{ $totalSession->sumHourSession }}</span>
                                                @endif
                                            </p>
                                            <div class="mt-4">
                                                @if (count($seances) <= 0)
                                                    <x-no-data class="!h-14" texte="Pas de session" />
                                                @else
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Date</th>
                                                                <th>Début</th>
                                                                <th>Fin</th>
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
                                                                        <button
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

                        @if ($projet->project_type !== 'Interne')
                            {{-- <== projet interne ne possede pas d'aspect financier --}}
                            <div class="accordion accordion-flush rounded-box" id="accordionExampleFinance">
                                <div class="accordion-item card bg-base-100 !w-full shadow-xl !rounded-xl bg-white">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#finance" aria-expanded="true"
                                            aria-controls="finance">
                                            <i class="mr-3 fa-solid fa-landmark"></i>
                                            Aspects financiers
                                        </button>
                                    </h2>
                                    <div id="finance" class="accordion-collapse collapse show"
                                        aria-labelledby="headingOne" data-bs-parent="#accordionExampleFinance">
                                        <div class="accordion-body">
                                            <div class="flex justify-end w-full mb-3">
                                                {{-- @if ($projet->idCfp == $idCfp || $projet->idCfp_inter == $idCfp) --}}
                                                @if ($projet->project_type == 'Interne' || $projet->project_type == 'Inter' || $projet->project_type == 'Intra')
                                                    <button onclick="__global_drawer('offcanvasFrais')"
                                                        class="btn btn-outline btn-primary btn-sm"><i
                                                            class="fa-solid fa-pen"></i>
                                                        Editer</button>
                                                @endif
                                            </div>
                                            <div class="mt-4">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <th>Financement</th>
                                                            <td class="text-right">
                                                                {{ $projet->paiement ?? 'Non renseigné' }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Prix de la prestation du centre de formation (Ariary)</th>
                                                            <td class="text-right">
                                                                {{ number_format($total_ht, 2, ',', ' ') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Coût total HT du projet (Ariary)</th>
                                                            <td class="text-right project_price_total_ht"></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Coût total TTC du projet (Ariary)</th>
                                                            <td class="text-right project_price_total_ttc"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        {{-- Momentum --}}
                        <div class="accordion accordion-flush rounded-box" id="accordionExampleMomentum">
                            <div class="accordion-item card bg-base-100 !w-full shadow-xl !rounded-xl bg-white">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#momentum" aria-expanded="true" aria-controls="momentum">
                                        <i class="mr-3 fa-solid fa-image"></i>
                                        Momentum (Galérie Photo)
                                    </button>
                                </h2>
                                <div id="momentum" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                    data-bs-parent="#accordionExampleMomentum">
                                    <div class="accordion-body">

                                        @if ($imagesMomentums->isEmpty())
                                            <div class="mb-4">
                                                <form id="uploadPhotoForm"
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
                                                            <h3 class="text-gray-600">Cliquer ou glisser pour télécharger
                                                            </h3>
                                                            <p class="text-gray-500">SVG, PNG, JPG, GIF ou WEBP
                                                                <strong>(MAX. 3 Mo)</strong>
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
                                                    <button type="submit"
                                                        class="mt-3 w-full text-white bg-gradient-to-r from-purple-500 via-purple-600 to-purple-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-purple-300 font-bold rounded-lg text-sm px-5 py-2.5 text-center">
                                                        Importer
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <div class="mb-4">
                                                <form id="uploadPhotoForm"
                                                    action="{{ route('uploadphoto.momentum', ['idProjet' => $projet->idProjet]) }}"
                                                    method="POST" enctype="multipart/form-data"
                                                    class="flex items-center gap-4">
                                                    @csrf
                                                    <label class="flex-grow block">
                                                        <input type="file" name="myFile[]" multiple
                                                            accept=".svg , .jpeg, .jpg, .gif, .webp, .png"
                                                            class="block w-full text-sm text-gray-500 cursor-pointer md:w-3/4 lg:w-1/2 file:cursor-pointer file:w-48 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-400 file:text-white hover:file:bg-purple-500 file:disabled:opacity-50 file:disabled:pointer-events-none dark:text-neutral-500 dark:file:bg-purple-300 dark:hover:file:bg-purple-400">
                                                    </label>
                                                    <button type="submit"
                                                        class="text-white bg-gradient-to-r from-purple-500 via-purple-600 to-purple-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-purple-300 dark:focus:ring-purple-800 font-bold rounded-lg text-sm px-5 py-2.5 text-center">
                                                        Importer
                                                    </button>
                                                </form>


                                            </div>

                                            <div class="w-full">
                                                <div class="w-full h-full du-carousel" id="momentum">
                                                    @foreach ($imagesMomentums as $key => $imagesMomentum)
                                                        <div id="slide{{ $key }}"
                                                            class="relative w-full du-carousel-item h-72">
                                                            <img src="{{ $imagesMomentum->url }}" alt="photo"
                                                                class="object-cover w-full h-full cursor-pointer"
                                                                onclick="window.location.href='{{ route('etp.projets.showmomentum', ['idProjet' => $projet->idProjet]) }}';">
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

                        {{-- Info general --}}
                        {{-- <div class="accordion accordion-flush rounded-box" id="accordionExampleInfoGenereal">
                            <div class="accordion-item card bg-base-100 !w-full shadow-xl !rounded-xl bg-white">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#info_generale" aria-expanded="true"
                                        aria-controls="info_generale">
                                        <i class="mr-3 fa-solid fa-circle-info"></i>
                                        Informations Générales
                                    </button>
                                </h2>
                                <div id="info_generale" class="accordion-collapse collapse show"
                                    aria-labelledby="headingOne" data-bs-parent="#accordionExampleInfoGenereal">
                                    <div class="accordion-body">
                                        <div class="flex justify-end w-full mb-3">
                                            {{-- @if ($projet->idCfp == $idCfp || $projet->idCfp_inter == $idCfp) 
                                            <button onclick="__global_drawer('offcanvasGeneral')"
                                                class="btn btn-outline btn-primary btn-sm"><i class="fa-solid fa-pen"></i>
                                                Editer</button>
                                            {{-- @endif 
                                        </div>
                                        <p class="line-clamp-6">
                                            @if ($projet->project_description != null)
                                                {!! $projet->project_description !!}
                                            @endif
                                        </p>

                                        <div class="mt-4 overflow-x-auto">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <th>Référence</th>
                                                        <td class="text-right">
                                                            {{ $projet->project_reference ?? 'Non renseigné' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Date de début</th>
                                                        <td class="text-right capitalize date_deb"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Date d'échéance</th>
                                                        <td class="text-right capitalize date_fin"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Modalité</th>
                                                        <td class="text-right">{{ $projet->modalite ?? 'Non renseignée' }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}


                        {{-- <div class="accordion accordion-flush" id="accordionExampleNotation">
                            <div class="accordion-item card bg-base-100 !w-full shadow-xl !rounded-xl bg-white">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#notation" aria-expanded="true" aria-controls="notation">
                                        <i class="mr-3 fa-solid fa-ranking-star"></i>
                                        Statut et notation
                                    </button>
                                </h2>
                                <div id="notation" class="accordion-collapse collapse show" aria-labelledby="headingTwo"
                                    data-bs-parent="#accordionExampleNotation">
                                    <div class="accordion-body">

                                        <div class="overflow-x-auto">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <th>Type de projet</th>
                                                        <td class="text-right">
                                                            <span
                                                                class="px-3 py-1 rounded-xl
                                                        @switch($projet->project_type)
                                                            @case('Intra')
                                                                bg-[#1565c0]/20 text-[#1565c0]
                                                                @break
                                                             @case('Inter')
                                                                bg-[#7209b7]/20 text-[#7209b7]
                                                                @break    
                                                            @case('Interne')
                                                                bg-[#250001]/20 text-[#250001]
                                                                @break    
                                                        
                                                            @default
                                                                
                                                        @endswitch
                                                    ">
                                                                {{ $projet->project_type }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Statut</th>
                                                        <td class="text-right">
                                                            <span
                                                                class="px-3 py-1 text-sm text-white rounded-xl
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
                                                                @endswitch">
                                                                {{ $projet->project_status }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Taux de présence</th>
                                                        <td class="text-right taux_presence"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                            <div id="raty_notation"
                                                                class="inline-flex items-center gap-1 rating">
                                                            </div>
                                                        </th>
                                                        {{-- <td class="text-right">
                                                            {{ number_format($noteGeneral, 1, ',', ' ') }}
                                                            <span class="text-gray-400"> ({{ $countNotationProjet }} avis)
                                                            </span>
                                                        </td> 
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}



                        <div class="accordion accordion-flush rounded-box" id="accordionExampleSalle">
                            <div class="accordion-item card bg-base-100 !w-full shadow-xl !rounded-xl bg-white">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#salle" aria-expanded="true" aria-controls="salle">
                                        <i class="mr-3 fa-solid fa-door-open"></i>
                                        Lieu et salle
                                    </button>
                                    <div class="flex justify-end w-full pr-4 mt-2">
                                        {{-- @if ($projet->idCfp == $idCfp || $projet->idCfp_inter == $idCfp) --}}
                                        @if ($projet->project_type == 'Interne')
                                            <button onclick="__global_drawer('offcanvasSalle')"
                                                class="m-1 btn btn-sm btn-outline btn-primary"><i
                                                    class="fa-solid fa-pen"></i>
                                                Editer</button>
                                        @endif
                                    </div>
                                </h2>
                                <div id="salle" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                    data-bs-parent="#accordionExampleSalle">
                                    <div class="accordion-body">
                                        <p>
                                            La formation aura lieu à <span class="text-gray-600">
                                                <span class="salle_re"></span>
                                                <span class="font-semibold text-blue-500 salle_qrt"></span>
                                                <span class="font-semibold text-blue-500 salle_ville"></span>
                                                <span class="salle_cp"></span>
                                                <span class="font-semibold text-blue-500 salle_nm"></span>
                                            </span>
                                        </p>
                                        <div class="mt-3">
                                            <figure>
                                                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS3iTHR5PBcG5LPp5OgSXy4mrtTcaKEInWmUg&s"
                                                    alt="Carlton" class="object-cover w-full h-auto rounded-xl" />
                                            </figure>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        @if ($projet->project_type != 'Interne')
                            <div class="accordion accordion-flush rounded-box" id="accordionExampleEntreprise">
                                <div class="accordion-item card bg-base-100 !w-full shadow-xl !rounded-xl bg-white">
                                    <h2 class="accordion-header" id="headingOne">
                                        <span
                                            class="collapsed w-full inline-flex !rounded-xl items-center justify-between">
                                            <button class="w-full p-4 text-left group hover:underline underline-offset-4"
                                                type="button" data-bs-toggle="collapse"
                                                data-bs-target="#entreprise_accordion" aria-expanded="true"
                                                aria-controls="entreprise_accordion">
                                                <i class="mr-3 fa-solid fa-building"></i>
                                                Centres(s) de formation
                                            </button>

                                        </span>
                                    </h2>
                                    <div id="entreprise_accordion" class="accordion-collapse collapse show"
                                        aria-labelledby="headingOne" data-bs-parent="#accordionExampleEntreprise">
                                        <div class="accordion-body">
                                            <div class="overflow-x-auto">
                                                <table class="table">
                                                    <tbody id="">
                                                        <tr>
                                                            <td>
                                                                <div class="flex items-center gap-3">
                                                                    <div class="avatar">
                                                                        <div class="w-24 h-16 rounded-xl">
                                                                            @if (isset($cfp->cfp_logo))
                                                                                <img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/entreprises/{{ $cfp->cfp_logo }}"
                                                                                    class="object-cover w-20 h-auto"
                                                                                    alt="{{ $cfp->cfp_name ?? 'Entreprise' }}" />
                                                                            @else
                                                                                <span
                                                                                    class="flex items-center justify-center w-full h-full text-xl uppercase text-slate-600 bg-slate-200">{{ $cfp->etp_initial_name ?? 'I' }}</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div>
                                                                        <div class="font-bold uppercase">
                                                                            {{ $cfp->cfp_name ?? '' }}</div>
                                                                        <div class="text-sm opacity-50">
                                                                            {{ $cfp->cfp_mail ?? '' }}</div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="text-right">
                                                                <button
                                                                    onclick="showCustomer({{ $projet->idProjet }}, '/etp/etp-drawer/')"
                                                                    class="opacity-50 btn btn-sm btn-ghost"><i
                                                                        class="fa-solid fa-eye"></i></button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="accordion accordion-flush" id="accordionExempleFormateur">
                            <div class="accordion-item card bg-base-100 !w-full shadow-xl !rounded-xl bg-white">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#formateur_accordion" aria-expanded="true"
                                        aria-controls="formateur_accordion">
                                        <i class="mr-3 fa-solid fa-user-graduate"></i>
                                        Faites connaissance avec les formateurs
                                    </button>
                                </h2>
                                <div id="formateur_accordion" class="accordion-collapse collapse show"
                                    aria-labelledby="headingTwo" data-bs-parent="#accordionExempleFormateur">
                                    <div class="accordion-body">
                                        {{-- @if ($projet->idCfp == $idCfp || $projet->idCfp_inter == $idCfp) --}}
                                        @if ($projet->project_type == 'Interne')
                                            <div class="flex justify-end w-full mb-3">
                                                <button onclick="__global_drawer('offcanvasFormateur')"
                                                    class="btn btn-outline btn-primary btn-sm"><i
                                                        class="fa-solid fa-pen"></i>
                                                    Editer</button>
                                            </div>
                                        @endif
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

                        {{-- Restauration --}}
                        <div class="accordion accordion-flush rounded-box" id="accordionExample">
                            <div class="accordion-item card bg-base-100 !w-full shadow-xl !rounded-xl bg-white">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#restauration_accordion" aria-expanded="true"
                                        aria-controls="restauration_accordion">
                                        <i class="mr-3 fa-solid fa-utensils"></i>
                                        Restauration
                                    </button>
                                </h2>
                                <div id="restauration_accordion" class="accordion-collapse collapse show"
                                    aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="flex justify-end w-full mb-3">
                                            <button onclick="openRestauration()"
                                                class="btn btn-outline btn-primary btn-sm"><i class="fa-solid fa-pen"></i>
                                                Editer</button>
                                        </div>
                                        <div class="overflow-x-auto">
                                            <table class="table">
                                                <!-- head -->
                                                {{-- <thead>
                                            <tr>
                                                <th></th>
                                                <th>Name</th>
                                                <th>Job</th>
                                                <th>Favorite Color</th>
                                            </tr>
                                        </thead> --}}
                                                <tbody id="content_restauration_list"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <input type="radio" name="my_tabs_2" role="tab" class="tab !w-max"
                aria-label="Programme de formation" />
            <div role="tabpanel" class="p-3 tab-content">
                <div class="grid grid-cols-1 gap-4 m-4 lg:grid-cols-3">
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
                                                    <li class="text-gray-500 list-disc">{{ $objectif->objectif }}.</li>
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
    @endsection





    @push('custom_style')
        <link rel="stylesheet" href="{{ asset('css/heat-rating.css') }}">
        {{-- <style>
        .dropzone {
            position: relative;
        }

        .dropzone.dragover {
            border-color: #4A90E2;
            /* Change the border color when dragging */
            background-color: #E0F7FF;
            /* Change background color when dragging */
        }
    </style> --}}
    @endpush

    @section('script')
        <script src="{{ asset('js/moment.min.js') }}"></script>
        <script src="{{ asset('js/moment.fr.min.js') }}"></script>

        {{-- Sessions --}}
        <script src="{{ asset('js/daypilot-pro-javascript/daypilot-javascript.min.js') }}"></script>
        <script src="{{ asset('js/agendas/ETP/planningEtp.js') }}"></script>

        <script src="{{ asset('js/axios.min.js') }}"></script>

        {{-- Evaluation rating --}}
        <script src="{{ asset('js/heat-rating.js') }}"></script>
        <script>
            function formatAmount(nombre) {
                // const nombre = 3100000;
                const formattedNumber = nombre.toLocaleString('en-US', {
                    minimumFractionDigits: 1,
                    maximumFractionDigits: 1
                });
                return formattedNumber;
            }

            $(document).ready(function() {

                var id_check = [];
                var project_type = @json($projet->project_type);
                var idEtp = @json($projet->idEtp);
                RestaurationList();
                getProgramProject({{ $projet->idModule }});
                canva_presence();

                console.log('project_type ==>', project_type);
                if (project_type != 'Interne') { // <== projet intra ou inter
                    calculateTotalFrais();
                    getfraisAssign({{ $projet->idProjet }}, 0);
                    calculateTotalPrice();
                    updatefraistotal({{ $projet->idProjet }});

                }

                var projet = @json($projet);

                // Apprenant ou Employé
                if (project_type != 'Interne') // <== projet intra ou inter
                {


                    if (projet.idCfp_inter == null) {
                        getApprenantAdded({{ $projet->idProjet }});
                        getEtpAssigned({{ $projet->idProjet }})
                    } else {
                        getApprenantAddedInter({{ $projet->idProjet }});
                        // Entreprise
                        getEtpAdded({{ $projet->idProjet }});
                    }
                    //Ajout formateur intra inter...
                    getFormAdded({{ $projet->idProjet }});
                    // getAllSalle();
                }

                if (project_type == 'Interne') {
                    getAllFormInternes(); //<== Appel formateurs internes... 
                    getApprenantAdded({{ $projet->idProjet }});
                    getApprenantProjets({{ $projet->idEtp }});
                    //Ajout formateur interne...
                    getFormInterneAdded({{ $projet->idProjet }});
                    // getAllSalle();
                }


                if (project_type == 'Inter') {
                    getApprenantAddedInter({{ $projet->idProjet }});
                    //getAllApprenantInter({{ $projet->idProjet }});

                    getApprenantProjetInter({{ $projet->idProjet }});

                    getApprenantProjets(sessionStorage.getItem('IDCUSTOMER_ETP'));
                    // getApprenantAdded({{ $projet->idProjet }});
                }


                // Rating
                ratyNotation('raty_notation', {{ $noteGeneral }});

                var date_deb = $('.date_deb');
                var date_fin = $('.date_fin');
                date_deb.html('');
                date_fin.html('');

                date_deb.append(formatDate(`{{ $projet->dateDebut ?? 0 }}`, 'dddd DD MMM YYYY'))
                date_fin.append(formatDate(`{{ $projet->dateFin ?? 0 }}`, 'dddd DD MMM YYYY'))
            });

            //  =========== PROJET =============
            function updateProjet(idProjet) {
                $.ajax({
                    type: "PATCH",
                    url: "/etp/projets/" + idProjet + "/updateProjet",
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
            }

            // ======== FRAIS ===========
            // tous les différents frais
            function getAllFrais() {
                $.ajax({
                    type: "get",
                    url: "/etp/invites/cfp/getAllFrais",
                    dataType: "json",
                    success: function(res) {
                        var get_all_frais = $('#get_all_frais');
                        get_all_frais.html('');

                        if (res.frais.length <= 0) {
                            get_all_frais.append(`<x-no-data texte="Pas de frais pour cet projet"></x-no-data>`);
                        } else {
                            $.each(res.frais, function(key, val) {
                                get_all_frais.append(`<x-frais-li nom="` + val.Frais + `" exemple="` + val
                                    .exemple +
                                    `" onclick="fraisAssign({{ $projet->idProjet }},` + val.idFrais +
                                    `)"/>`);

                            });
                        }
                    }
                });
            }


            function removeEtpFraisProjet(idProjet, idEtp) {
                $.ajax({
                    type: "delete",
                    url: "/etp/projets/" + idProjet + "/" + idEtp + "/removeEtpFraisProjet",
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
                    url: "/etp/projets/" + idProjet + "/update-taxe",
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
            function fraisAssign(idProjet, idFrais, isEtp = 1) {
                $.ajax({
                    type: "post",
                    url: "/etp/projets/" + idProjet + "/" + idFrais + "/" + isEtp + "/fraisprojet/assign",
                    data: {
                        idProjet: idProjet,
                        idFrais: idFrais,
                        isEtp: isEtp,
                        _token: '{!! csrf_token() !!}'
                    },
                    success: function(response) {
                        toastr.info("Remplir le coût.", "Information", {
                            timeOut: 3000 // Durée en millisecondes
                        });
                        getfraisAssign(idProjet, 0);
                        calculateTotalFrais();
                    },
                    error: function(xhr, status, error) {
                        console.log("Erreur :", error);
                        console.log("Statut :", status);
                        console.log("Réponse :", xhr.responseText);
                        // toastr.erreur("Erreur inattendue.", "Erreur", {
                        //   timeOut: 1500 // Durée en millisecondes
                        // });
                    }
                });
            }

            // récupérer les frais déjà séléctionnés
            function getfraisAssign(idProjet, isEtp) {
                $.ajax({
                    type: "get",
                    url: "/etp/projets/" + idProjet + "/" + isEtp + "/frais",
                    dataType: "json",
                    success: function(res) {
                        var get_frais_selected = $('#get_frais_selected');
                        get_frais_selected.html('');
                        var totalFrais = 0;

                        let project_price_total_ht = $('.project_price_total_ht');
                        let project_price_total_ttc = $('.project_price_total_ttc');

                        // Check if there are any frais details returned
                        if (res.fraisdetails.length > 0) {
                            // Get the taxe value from the first item in fraisdetails
                            var taxe = parseFloat(res.fraisdetails[0].taxe);

                            // Set the radio button based on the tax value
                            if (taxe === 20) {
                                $('#tvaRadio20').prop('checked', true);
                            } else {
                                $('#tvaRadio0').prop('checked', true);
                            }

                            // Calculate the total frais
                            $.each(res.fraisdetails, function(key, val) {
                                totalFrais += parseFloat(val.montant) || 0;
                            });

                            totalFrais = Number(totalFrais.toFixed(2));
                            var formattedTotalFrais = totalFrais.toLocaleString('fr-FR', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });

                            var totalFraisTTC = taxe === 20 ? totalFrais * 1.20 : totalFrais;
                            totalFraisTTC = Number(totalFraisTTC.toFixed(2));
                            var formattedTotalFraisTTC = totalFraisTTC.toLocaleString('fr-FR', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });

                            project_price_total_ht.text(' ' + formattedTotalFrais);
                            project_price_total_ttc.text(' ' + formattedTotalFraisTTC);

                            // Display the total at the top
                            get_frais_selected.append(
                                `<div class="grid items-center grid-cols-3 px-2">
                                    <span class="text-gray-400">Total HT : </span>
                                    <div class="inline-flex items-center justify-end gap-1">
                                        <span class="font-medium text-right text-gray-600 total-frais" id="totalFrais"> Ar ${formattedTotalFrais}</span>
                                    </div>
                                </div>
                                <div class="grid items-center grid-cols-3 px-2">
                                    <span class="text-gray-400">Total TTC : </span>
                                    <div class="inline-flex items-center justify-end gap-1">
                                        <span class="font-medium text-right text-gray-600 total-frais-ttc" id="totalFraisTTC"> Ar ${formattedTotalFraisTTC}</span>
                                    </div>
                                </div>`
                            );

                            // Append la liste des frais selectionnés
                            $.each(res.fraisdetails, function(key, val) {
                                var description = val.description == null ? "" : val.description;
                                get_frais_selected.append(`<li data-id-frais-projet="` + val.idFraisProjet + `" class="grid grid-cols-5 w-full gap-2 justify-between px-3 py-2 border-[1px] border-gray-100 cursor-pointer hover:bg-gray-50 duration-200 rounded-md bg-white">
                        <div class="col-span-4">
                            <div class="inline-flex items-center gap-2">
                                <div class="grid-cols-2 gap-0">
                                    <div class="inline-flex items-center gap-3">
                                        <h3 class="text-base font-semibold text-gray-700">` + val.frais + `</h3>
                                    </div>
                                    <div class="grid col-span-1">
                                        <div class="grid items-center grid-cols-2 px-2">
                                            <span class="text-gray-400">Coût:</span>
                                            <div class="inline-flex items-center justify-end gap-1">
                                                <span class="font-medium text-right text-gray-600">Ar</span>
                                                <input type="number" value="` + val.montant + `" class="coutFrais outline-none text-gray-600 font-medium text-right flex bg-transparent pl-2 h-10 border-b-[1px] border-gray-50 appearance-none hover:border-purple-300 focus:border-purple-300 focus:ring-2 focus:ring-purple-100 duration-200">
                                            </div>
                                        </div>
                                        <div class="grid items-center grid-cols-2 px-2">
                                            <span class="text-gray-400">Description:</span>
                                            <div class="inline-flex items-center justify-end gap-1">
                                                <input type="text" value="` + description + `" placeholder="Description" class="descriptionFrais outline-none text-gray-600 font-medium text-right flex bg-transparent pl-2 h-10 border-b-[1px] border-gray-50 appearance-none hover:border-purple-300 focus:border-purple-300 focus:ring-2 focus:ring-purple-100 duration-200">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div onclick="fraisRemove({{ $projet->idProjet }},` + val.idFraisProjet + `)" class="grid items-center justify-center w-full col-span-1">
                            <div class="flex items-center justify-center w-10 h-10 duration-150 bg-red-100 rounded-full cursor-pointer icon hover:bg-red-50 group/icon">
                                <i class="text-sm text-red-500 duration-150 fa-solid fa-minus group-hover/icon:text-red-600"></i>
                            </div>
                        </div>
                        </li>
                    `);
                            });

                            // Modifier le total de frais
                            $('.coutFrais').on('change', function() {
                                updateFrais($(this));
                            });

                            $('.descriptionFrais').on('change', function() {
                                updateFrais($(this));
                            });
                        } else {
                            get_frais_selected.append(
                                `<x-no-data texte="Pas de frais selectionné pour cet projet"></x-no-data>`);
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error(response.error, 'Erreur', {
                            timeOut: 1500
                        });
                        console.log("Erreur AJAX:", error);
                    }
                });
            }

            function updateFrais(element) {
                var idFraisProjet = element.closest('li').data('id-frais-projet');
                var montant = element.closest('li').find('.coutFrais').val();
                var description = element.closest('li').find('.descriptionFrais').val();

                // Mettre à jour la base de données via AJAX
                $.ajax({
                    type: "post",
                    url: "/etp/projets/update-frais",
                    data: {
                        idFraisProjet: idFraisProjet,
                        montant: montant,
                        description: description,
                        _token: $('meta[name="csrf-token"]').attr('content') // Assurez-vous d'avoir le jeton CSRF
                    },
                    success: function(response) {
                        var idProjet = getIdProjetByIdFraisProjet(idFraisProjet);
                        // Recalculer le total des frais
                        getfraisAssign({{ $projet->idProjet }}, 0);
                        calculateTotalFrais();
                    },
                    error: function(xhr, status, error) {
                        toastr.error(response.error, 'Erreur', {
                            timeOut: 1500
                        });
                        console.log("Erreur lors de la mise à jour:", error);
                    }
                });
            }

            // modifier le total des frais dans la base de données
            function updatefraistotal(idProjet) {
                $.ajax({
                    type: "post",
                    url: "/etp/projets/" + idProjet + "/total-frais",
                    dataType: "json",
                    success: function(response) {
                        if (response.error) {}
                    }
                });
            }


            function calculateTotalFrais() {

                // récuperer la taxe
                var newTaxe = 0;
                $.each($('input[name="tvaRadio"]'), function() {
                    $(this).on('change', function() {
                        newTaxe = $(this)
                            .val(); // Récupère la nouvelle valeur de la taxe (0 ou 20)
                    });
                });


                var totalFrais = 0;
                $('.coutFrais').each(function() {
                    totalFrais += parseFloat($(this).val()) || 0;
                });
                totalFrais = Number(totalFrais.toFixed(2));
                var formattedTotalFrais = totalFrais.toLocaleString('fr-FR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                $('.total-frais').text(' Ar ' + formattedTotalFrais);
                var totalFraisTTC = totalFrais * (1 + (newTaxe / 100));
                totalFraisTTC = Number(totalFraisTTC.toFixed(2));
                var formattedTotalFraisTTC = totalFraisTTC.toLocaleString('fr-FR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                $('.total-frais-ttc').text(' Ar ' + formattedTotalFraisTTC);

                $('.project_price_total_ht').text(' ' + formatAmount(formattedTotalFrais ?? 0));
                $('.project_price_total_ttc').text(' ' + formatAmount(formattedTotalFraisTTC ?? 0));
            }


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
                    url: "/etp/projets/" + idFraisProjet + "/idProjet",
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
                    type: "get",
                    url: "/etp/projets/fermeturefrais",
                    dataType: "json",
                    success: function(response) {
                        getfraisAssign(idProjet, 0);
                        calculateTotalFrais();
                        updatefraistotal(idProjet);
                    }
                });
            }

            // methode qui supprime/déséléctionne un frais à l'aide du bouton minus
            function fraisRemove(idProjet, idFraisProjet) {
                $.ajax({
                    type: "post",
                    url: "/etp/projets/" + idProjet + "/" + idFraisProjet + "/delete-frais",
                    data: {
                        _token: '{!! csrf_token() !!}'
                    },
                    dataType: "json",
                    success: function(response) {
                        toastr.success(response.success, 'Succès', {
                            timeOut: 1500
                        });
                        updatefraistotal(idProjet);
                        getfraisAssign(idProjet, 0);
                        calculateTotalFrais();
                    }
                });
            }

            // ======== SEANCE ===========
            function deleteSeance(idSeance) {
                $.ajax({
                    type: "DELETE",
                    url: "/etp/seanceInternes/" + idSeance + "/delete",
                    success: function(res) {
                        if (res.success) {
                            toastr.success(res.success, 'Session supprimé avec succès', {
                                timeOut: 1500
                            });
                            let idCustomer;
                            if (sessionStorage.getItem('IDCUSTOMER_ETP') !== null) {
                                idCustomer = sessionStorage.getItem('IDCUSTOMER_ETP');
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

            // ======== Salle ===========
            function getAllSalle() {
                $.ajax({
                    type: "get",
                    url: "{{ route('etp.salles.getAllSalle') }}",
                    dataType: "json",
                    success: function(res) {
                        var salles = $('.get_all_salle_detail');
                        salles.html('');
                        if (res.salles.length > 0) {
                            $.each(res.salles, function(i,
                                val) {
                                salles.append(`<li class="w-full p-2 border-[1px] bg-white rounded-md">
                                <div class="grid grid-cols-4">
                                  <div class="grid col-span-3">
                                    <div class="flex flex-col">
                                      <span class="text-lg font-semibold text-gray-600">` + val.salle_name + `</span>
                                      <span>
                                        <span class="text-gray-500 rue_` + val.idSalle +
                                    `"></span> - <span class="quartier_` +
                                    val.idSalle +
                                    ` text-gray-500">` +
                                    val
                                    .salle_quartier + `</span>
                                      </span>
                                      <span>
                                        <span class="text-gray-500">` + val.ville +
                                    `</span> <span class="code_postal_` +
                                    val.idSalle +
                                    ` text-gray-500">` +
                                    val
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

                                if (val.salle_rue !=
                                    null) {
                                    $('.rue_' + val
                                            .idSalle)
                                        .text(val
                                            .salle_rue);
                                } else {
                                    $('.rue_' + val
                                            .idSalle)
                                        .text("N/A");
                                }

                                if (val
                                    .salle_quartier !=
                                    null) {
                                    $('.quartier_' + val
                                            .idSalle)
                                        .text(val
                                            .salle_quartier
                                        );
                                } else {
                                    $('.quartier_' + val
                                            .idSalle)
                                        .text("N/A");
                                }

                                if (val
                                    .salle_code_postal !=
                                    null) {
                                    $('.code_postal_' +
                                            val.idSalle)
                                        .text(val
                                            .salle_code_postal
                                        );
                                } else {
                                    $('.code_postal_' +
                                            val.idSalle)
                                        .text("N/A");
                                }
                            });
                        }
                        else{{ salles . append(`<x-no-data texte="Pas de données"></x-no-data>`) }}
                    }
                });
                getSalleAdded({{ $projet->idProjet }});
            }

            function assignSalle(idSalle) {
                $.ajax({
                    type: "patch",
                    url: "/etp/projets/" +
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
                    type: "get",
                    url: "/etp/projets/" + idProjet + "/getSalleAdded",
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
                                salle_re.text(val.salle_rue +
                                    " - ");
                            }

                            if (val.salle_quartier != null) {
                                salle_qrt.text(val
                                    .salle_quartier + " - ");
                            }

                            if (val.ville != null) {
                                salle_ville.text(val.ville +
                                    " - ");
                            }

                            if (val.salle_code_postal != null) {
                                salle_cp.text(val
                                    .salle_code_postal +
                                    " - ");
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
                                `"></span> - <span class="quartier_` +
                                val.idSalle +
                                ` text-gray-500">` + val
                                .salle_quartier + `</span>
                                    </span>
                                    <span>
                                      <span class="text-gray-500">` + val.ville + `</span> <span class="code_postal_` +
                                val.idSalle +
                                ` text-gray-500">` + val
                                .salle_code_postal + `</span>
                                    </span>
                                  </div>
                                </div>
                              </div>
                            </li>`);

                            if (val.salle_rue != null) {
                                $('.rue_' + val.idSalle).text(
                                    val.salle_rue);
                            } else {
                                $('.rue_' + val.idSalle).text(
                                    "N/A");
                            }

                            if (val.salle_quartier != null) {
                                $('.quartier_' + val.idSalle)
                                    .text(val.salle_quartier);
                            } else {
                                $('.quartier_' + val.idSalle)
                                    .text("N/A");
                            }

                            if (val.salle_code_postal != null) {
                                $('.code_postal_' + val.idSalle)
                                    .text(val
                                        .salle_code_postal);
                            } else {
                                $('.code_postal_' + val.idSalle)
                                    .text("N/A");
                            }
                        } else {
                            salle.append(
                                `<x-no-data texte="Veuillez selectionnez un lieu"></x-no-data>`
                            );
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }


            // ======== APPRENANTS ===========
            //Apprenant AJAX
            function getApprenantAdded(idProjet) {
                var appr_table_div = $('#appr_table');

                $.ajax({
                    type: "get",
                    url: "/etp/projet/apprenants/getApprenantAdded/" + idProjet,
                    dataType: "json",
                    beforeSend: function() {
                        // all_appr_project.append(`<span class="initialLoading">Chargement ...</span>`);
                        appr_table_div.append(`<span class="flex flex-col items-center w-full gap-2 p-4">
                        <div class="loader"></div>
                        <p class="text-lg text-slate-700">Veuillez patientez, nous chargeons vos données !</p>
                        </span>`)
                    },
                    complete: function() {
                        $('.initialLoading').remove();
                    },
                    success: function(res) {
                        console.log('RESULTAT APPR INTRA-->', res);
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
                    url: "/etp/projet/etpInter/getApprenantAddedInter/" + idProjet,
                    dataType: "json",
                    beforeSend: function() {
                        // all_appr_project.append(`<span class="initialLoading">Chargement ...</span>`);
                        appr_table_div.append(`<span class="flex flex-col items-center w-full gap-2 p-4">
                        <div class="loader"></div>
                        <p class="text-lg text-slate-700">Veuillez patientez, nous chargeons vos données !</p>
                        </span>`)
                    },
                    complete: function() {
                        $('.initialLoading').remove();
                    },
                    success: function(res) {
                        console.log('RESULTAT ETP APPR INTER-->', res);
                        appr_table(res);
                        appr_drawer(res);
                        tauxPresenceGlobal(res.percentPresent);
                    }
                });
            }

            function appr_table(data) {
                var appr_table = $('#appr_table')
                appr_table.html('');
                console.log('APPR TABLE-->', data);
                if (data.getEtps.length <= 0) {
                    appr_table.append(`<x-no-data texte="Aucun apprenant"/>`)
                } else {
                    $.each(data.getEtps, function(i, etp) {
                        appr_table.append(`
                    <div class="etp_apprenant">
                        <label class="mb-2 text-xl text-slate-700">${etp.etp_name}</label>
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
                            <tbody id="data_participant_${etp.idEtp}"></tbody>
                        </table>
                    </div>`)

                        appr_table.ready(function() {
                            var data_participant = $(`#data_participant_${etp.idEtp}`);

                            $.each(data.apprs, function(i, appr) {
                                var idEmploye = '';
                                if (etp.etp_name == appr.etp_name) {
                                    data_participant.append(
                                        `
                                <tr>
                                    <td class="capitalize">
                                        <span class="inline-flex items-center">
                                            <div class="mr-3 avatar">
                                                <div class="w-12 rounded-full">
                                                    ${ appr.emp_photo ? ` <img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/employes/${appr.emp_photo}" />` : `<span class="flex items-center justify-center w-full h-full text-xl uppercase text-slate-600 bg-slate-200">${appr.emp_firstname[0] ?? appr.emp_name[0]}</span>`
                                                    }
                                                </div>
                                            </div>
                                            <span class="flex flex-col">
                                                <span class="mr-1 uppercase">${appr.emp_name}</span> ${appr.emp_firstname ?? '' }
                                            </span>
                                        </span>
                                    </td>
                                    <td><span class="px-2 py-1 rounded-full text-sm uniquePresence_${appr.idEmploye}">100%</span></td>
                                    <td class="text-left"><span class="appreciation_${appr.idEmploye} inline-flex items-center"></span></td>
                                    <td class="text-center cursor-pointer" data-bs-toggle="tooltip" title="Compétence Avant/Après Formation"><span class='text-slate-400'> ${appr.avant ?? '--'} |  ${appr.apres ?? '--'}</span></td>
                                    <td class="text-right">
                                        <button onclick="getSkills({{ $projet->idProjet }}, ${appr.idEmploye})" data-bs-toggle="tooltip" title="Skill matrix" class="btn btn-xs md:btn-sm btn-ghost"><i class="fa-solid fa-compass-drafting"></i></button>
                                        <button onclick="getEvaluation({{ $projet->idProjet }}, ${appr.idEmploye})" data-bs-toggle="tooltip" title="Evaluation" class="btn btn-xs md:btn-sm btn-ghost"><i class="fa-solid fa-chart-simple"></i></button>
                                    </td>
                                </tr>`);

                                    idEmploye = appr.idEmploye;

                                    data_participant.ready(function() {
                                        getPresenceUnique({{ $projet->idProjet }}, idEmploye);
                                        showEvalTable({{ $projet->idProjet }}, appr.idEmploye);
                                        loadBsTooltip();
                                    });
                                }
                            });

                        });
                    });
                }
            }

            function appr_drawer(data) {
                console.log(data);
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
                                                    ${ appr.emp_photo ? ` <img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/employes/${appr.emp_photo}" />` : `<span class="flex items-center justify-center w-full h-full text-xl uppercase text-slate-600 bg-slate-200">${appr.emp_initial_name ?? 'I'}</span>`
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
                                                    ${ appr.emp_photo ? ` <img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/employes/${appr.emp_photo}" />` : `<span class="flex items-center justify-center w-full h-full text-xl uppercase text-slate-600 bg-slate-200">${appr.emp_initial_name ?? 'I'}</span>`
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
                    url: "/etp/projet/etpInter/getApprenantProjetInter/" + idProjet,
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

                            /*if (res.etps.length > 0) {
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
                            }*/

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
                                <tr class="list list_${val.idEtp}">
                                    <td class="capitalize">
                                        <span class="inline-flex items-center">
                                            <div class="mr-3 avatar">
                                                <div class="w-12 rounded-full">
                                                    ${ val.emp_photo ? ` <img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/employes/${val.emp_photo}" />` : `<span class="flex items-center justify-center w-full h-full text-xl uppercase text-slate-600 bg-slate-200">${val.emp_initial_name ?? 'I'}</span>`
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
                    url: "/etp/projet/apprenants/getApprenantProjets/" + idEtp,
                    dataType: "json",
                    success: function(res) {
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
                                <tr class="list list_${val.idEtp}">
                                    <td class="capitalize">
                                        <span class="inline-flex items-center">
                                            <div class="mr-3 avatar">
                                                <div class="w-12 rounded-full">
                                                    ${ val.emp_photo ? ` <img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/employes/${val.emp_photo}" />` : `<span class="flex items-center justify-center w-full h-full text-xl uppercase text-slate-600 bg-slate-200">${val.emp_firstname[0] ?? val.emp_name[0]}</span>`
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
                    url: "/etp/projet/etpInter/" + idProjet + "/" + idApprenant + "/" +
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
                    url: "/etp/projet/apprenants/" + idProjet + "/" + idApprenant,
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
                taux_presence.append(data ?? 'Inconnu');
            }


            // =========== FORMATEUR =============
            // Formateur AJAX
            function getFormInterneAdded(idProjet) {
                $.ajax({
                    type: "get",
                    url: "/etp/projets/" + idProjet + "/getFormInterneAdded",
                    dataType: "json",
                    success: function(res) {
                        form_table(res.forms);
                        form_drawer_added(res.forms);
                    }
                });
            }

            function getFormAdded(idProjet) {
                $.ajax({
                    type: "get",
                    url: "/etp/projets/" + idProjet + "/getFormAdded",
                    dataType: "json",
                    success: function(res) {
                        form_table(res.forms);
                        form_drawer_added(res.forms);
                    }
                });
            }

            // All Formateur AJAX
            // function getAllForms() {
            //     $.ajax({
            //         type: "get",
            //         //url: "/etp/forms/getAllForms",
            //         url: "/etp/formInternes/getAllForms",
            //         dataType: "json",
            //         success: function(res) {
            //             all_form_drawer(res.forms)
            //         }
            //     });
            // }


            // {{-- SCRIPT FORMATEUR --}}
            //Liste des formateurs internes de l' Entreprise...
            function getAllFormInternes() {
                $.ajax({
                    type: "get",
                    url: "/etp/formInternes/getAllForms",
                    dataType: "json",
                    success: function(res) {
                        console.log('FORMATEURS INTERNES-->', res);
                        all_form_drawer(res.forms);
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
                                                    ${ form.form_photo ? ` <img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/formateurs/${form.form_photo}" />` : `<span class="flex items-center justify-center w-full h-full text-xl uppercase text-slate-600 bg-slate-200">${form.form_firstname[0] ?? form.form_name[0]}</span>`
                                                    }
                                                </div>
                                            </div>
                                            <span class="mr-1 uppercase">${form.form_name}</span> ${form.form_firstname ?? '' }
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <button onclick="viewMiniCV(${form.idEmploye})" class="opacity-50 btn btn-sm btn-ghost"><i class="fa-solid fa-eye"></i></button>
                                    </td>
                                </tr>
                    `);
                    });
                }
            }

            function all_form_drawer(data) {
                var form_drawer = $('#all_form_drawer');
                form_drawer.html('');
                console.log('ALL DATA FORM-->', data);


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
                                                ${ form.form_photo ? ` <img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/formateurs/${form.form_photo}" />` : `<span class="flex items-center justify-center w-full h-full text-xl uppercase text-slate-600 bg-slate-200">${form.form_initial_name ?? 'I'}</span>`
                                                }
                                            </div>
                                        </div>
                                        <span class="flex flex-col">
                                            <span class="mr-1 uppercase">${form.form_name}</span> ${form.form_first_name ?? '' }
                                        </span>
                                    </span>
                                </td>
                                <td class='text-right'>
                                    <button onclick="manageForm('post', {{ $projet->idProjet }}, ${form.idEmploye})" class="rounded-full btn btn-success btn-outline"><i class="text-xl fa-solid fa-plus"></i></button>    
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
                        console.log('forms-->', form)
                        form_drawer.append(`
                            <tr>
                                <td class="capitalize">
                                    <span class="inline-flex items-center">
                                        <div class="mr-3 avatar">
                                            <div class="w-12 rounded-full">
                                                ${ form.form_photo ? ` <img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/formateurs/${form.form_photo}" />` : `<span class="flex items-center justify-center w-full h-full text-xl uppercase text-slate-600 bg-slate-200">${form.form_initial_name ?? 'I'}</span>`
                                                }
                                            </div>
                                        </div>
                                        <span class="flex flex-col">
                                            <span class="mr-1 uppercase">${form.form_name}</span> ${form.form_firstname ?? '' }
                                        </span>
                                    </span>
                                </td>
                                <td class='text-right'>
                                    <button onclick="manageForm('delete', {{ $projet->idProjet }}, ${form.idEmploye})" class="rounded-full btn btn-error btn-outline"><i class="text-xl fa-solid fa-minus"></i></button>    
                                </td>
                            </tr>
                `);
                    });
                }
            }

            function manageForm(type, idProjet, idFormateur) {
                $.ajax({
                    type: type,
                    url: "/etp/projets/" + idProjet + "/" + idFormateur + "/form/assign",
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

                            getFormInterneAdded(idProjet); //<== Ajout (-) drawer à droite Formateur

                        } else if (res.error) {
                            toastr.error(res.error, 'Erreur', {
                                timeOut: 1500
                            });
                        }
                    }
                });
            }

            //  =========== EnTREPRISE ==============
            function getAllEtps(idCfp_inter) {
                $.ajax({
                    type: "get",
                    url: "/etp/invites/etp/getAllEtps",
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
                                                        ${etp.etp_logo ? `<img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/entreprises/${etp.etp_logo}" class="object-cover w-20 h-auto" alt="${etp.etp_name ?? "Entreprise"}" />` : 
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
                                            <button onclick="etpAssign({{ $projet->idProjet }}, ${etp.idEtp})" class="btn btn-outline btn-success"><i class="fa-solid fa-plus"></i></button>
                                        </td>
                                    </tr>
                                    `);
                                } else if (idCfp_inter != null) {
                                    all_etp_drawer.append(`<tr>
                                        <td>
                                            <div class="flex items-center gap-3">
                                                <div class="avatar">
                                                    <div class="w-24 h-16 rounded-xl">
                                                        ${etp.etp_logo ? `<img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/entreprises/${etp.etp_logo}" class="object-cover w-20 h-auto" alt="${etp.etp_name ?? "Entreprise"}" />` : 
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
                                            <button onclick="etpAssignInter({{ $projet->idProjet }}, ${etp.idEtp})" class="btn btn-outline btn-success"><i class="fa-solid fa-plus"></i></button>
                                        </td>
                                    </tr>
                                    `);
                                }
                            });
                        }
                    }
                });
            }

            function etpAssignInter(idProjet, idEtp) {
                $.ajax({
                    type: "post",
                    url: "/etp/projets/" + idProjet + '/' + idEtp,
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
                    url: "/etp/projets/" + idProjet + "/" + idEtp + "/etp/assign",
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
                    url: "/etp/projet/etpInter/getEtpAdded/" + idProjet,
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
                    url: "/etp/projets/" + idProjet + "/etp/assign",
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
                    url: "/etp/projet/etpInter/" + idProjet + "/" + idEtp,
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
                    etp_table.append(`<x-no-data texte="Pas de centre"/>`);
                } else {
                    $.each(data, function(i, etp) {
                        etp_table.append(`
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="avatar">
                                                <div class="w-24 h-16 rounded-xl">
                                                    ${etp.cfp_logo ? `<img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/entreprises/${etp.cfp_logo}" class="object-cover w-20 h-auto" alt="${etp.cfp_name ?? "Entreprise"}" />` : 
                                                    `<span class="flex items-center justify-center w-full h-full text-xl uppercase text-slate-600 bg-slate-200">${etp.etp_initial_name ?? 'I'}</span>`}
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-bold uppercase">${etp.cfp_name ?? ''}</div>
                                                <div class="text-sm opacity-50">${etp.cfp_mail ?? ''}</div>
                                            </div>
                                        </div>
                                    </td>
                                       <td class="text-right">
                                        <button onclick="showCustomer(${etp.idProjet}, '/etp/etp-drawer/')" class="opacity-50 btn btn-sm btn-ghost"><i class="fa-solid fa-eye"></i></button>
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
                                                    ${data.etp_logo ? `<img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/entreprises/${data.etp_logo}" class="object-cover w-20 h-auto" alt="${data.etp_name ?? "Entreprise"}" />` : 
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
                                        <button onclick="showCustomer(${data.idProjet}, '/etp/etp-drawer/')" class="opacity-50 btn btn-sm btn-ghost"><i class="fa-solid fa-eye"></i></button>
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
                                                        ${data.etp_logo ? `<img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/entreprises/${data.etp_logo}" class="object-cover w-20 h-auto" alt="${data.etp_name ?? "Entreprise"}" />` : 
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
                                            ${etp.etp_logo ? `<img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/entreprises/${etp.etp_logo}" class="object-cover w-20 h-auto" alt="${etp.etp_name ?? "Entreprise"}" />` : 
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


            //  ============ PRESENCE ==================
            // AJAX PRESENCE INTRA
            function getAllApprPresence(idProjet) {
                var sessions = @json($seances);
                var countDate = @json($countDate);
                var all_appr_presence = $('.getAllApprPresence');
                all_appr_presence.html('');
                let idProj = idProjet;


                var data_idAppr = [];

                $.ajax({
                    type: "get",
                    url: "/etp/projet/apprenants/getApprenantAdded/" + idProjet,
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
                var countDate = @json($countDate);
                var all_appr_presence = $('.getAllApprPresence');
                all_appr_presence.html('');
                let idProj = idProjet;

                $.ajax({
                    type: "get",
                    url: "/etp/projet/apprenants/getApprAddedInter/" + idProjet,
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
                var all_appr_presence = $('.getAllApprPresence');
                all_appr_presence.html('');

                all_appr_presence.append(`
                                    <thead>
                                        <tr class="headPresence"></tr>
                                    </thead>
                                    <tbody class="bodyPresence">
                                      <tr class="text-center heureDebPresence"></tr>
                                      <tr class="text-center heureFinPresence"></tr>
                                      <tbody class="apprPresence"></tbody>
                                    </tbody>
                                    `);

                var head_presence = $('.headPresence');
                head_presence.html(`<td class="text-left">Jour</td>`);
                var heure_deb_presence = $('.heureDebPresence');
                heure_deb_presence.html(`<td class="text-left">Heure début</td>`)
                var heure_fin_presence = $('.heureFinPresence');
                heure_fin_presence.html(`<td class="text-left">Heure fin</td>`)
                var apprenant_list = $('.apprPresence');
                apprenant_list.html('');

                $.each(res.apprs, function(j, data) {
                    // Créer la structure HTML pour l'employé
                    let html = `<tr class="text-center list_button_${data.idEmploye}">
                                  <td class="text-left">
                                      <div class="inline-flex items-center gap-2 w-max">
                                          <input type="hidden" class="inputEmp" value="${data.idEmploye}">
                                            <div class="avatar">
                                                <div class="flex items-center justify-center w-12 text-xl font-medium rounded text-slate-600">
                                                   ${data.emp_photo ? `<img src="{{ asset('img/employes/${data.emp_photo}') }}" alt="" class="object-cover w-12 rounded-full">` : `<i class="fa-solid fa-user"></i>`}
                                                </div>
                                            </div>
                                          <p class="text-gray-500">${data.emp_name} ${data.emp_firstname}</p>
                                      </div>
                                  </td>
                              </tr>`;

                    // Ajouter la structure HTML de l'employé à la liste
                    apprenant_list.append(html);

                    // Sélectionner l'élément où vous souhaitez ajouter les boutons de dates bg-gray-50 hover:bg-gray-100
                    let list_button = $(`.list_button_${data.idEmploye}`);
                    $.each(res.getSeance, function(i_se, v_se) {
                        list_button.append(
                            `<td class="td_emar td_emargement_${v_se.idSeance}_${data.idEmploye}" td-se="${v_se.idSeance}" td-ep='${data.idEmploye}'></td>`
                        );
                        var td_emargement = $(
                            `.td_emargement_${v_se.idSeance}_${data.idEmploye}`);
                        td_emargement.html('');

                        $.each(res.getPresence, function(i_gp, v_gp) {
                            if (v_gp.idSeance == td_emargement.attr('td-se') &&
                                v_gp.idEmploye == td_emargement
                                .attr('td-ep')) {
                                if (v_gp.isPresent == null) {
                                    td_emargement.append(`
                                            <label for="td_${v_gp.idSeance}_${v_gp.idEmploye}" onchange="checkOneAppr('checkbox_appr', 'checkall')" class="flex items-center justify-center w-full h-full p-2 cursor-pointer">
                                                <input type="checkbox" class="hidden checkbox_appr" name="emargement" data-idProj="{{ $projet->idProjet }}" data-idAppr="${v_gp.idEmploye}" data-idSe="${v_gp.idSeance}" id="td_${v_gp.idSeance}_${v_gp.idEmploye}">
                                                <div class="w-6 h-6 border-[1px] border-gray-400 rounded-md hover:border-gray-500 cursor-pointer duration-200">
                                                </div>
                                            </label>`);
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
                                    td_emargement.append(`
                                            <label for="td_${v_gp.idSeance}_${v_gp.idEmploye}" onclick="checkOneAppr('checkbox_appr', 'checkall')" class="flex items-center justify-center w-full h-full p-2 cursor-pointer">
                                                <input type="checkbox" class="hidden checkbox_appr" name="emargement" data-idProj="{{ $projet->idProjet }}" data-idAppr="${v_gp.idEmploye}" data-idSe="${v_gp.idSeance}" id="td_${v_gp.idSeance}_${v_gp.idEmploye}">
                                                <div class="w-6 h-6 border-[1px] border-gray-200 ${color_select} rounded-md hover:border-gray-500 cursor-pointer duration-200">
                                                </div>
                                            </label>`);
                                }
                            }
                        });
                    });


                });

                $.each(res.countDate, function(i, val) {
                    head_presence.append(
                        `<x-thh colspan="${val.count}" date="${formatDate(val.dateSeance, `dddd DD MMM YYYY`)}" />`
                    );
                });

                $.each(res.getSeance, function(i, val) {

                    heure_deb_presence.append(
                        `<td class="text-start">
                                    <span class="inline-flex items-center gap-2">${val.heureDebut}</span>    
                                </td>`
                    );
                    heure_fin_presence.append(
                        `<x-tdd class="text-start">${val.heureFin}</x-tdd>`);
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


            function checkOneAppr(appr_id, allcheck) {
                var data = new Array();
                var checkbox_appr = $(`.${appr_id}`);
                var checkall = $(`#${allcheck}`);

                var allChecked = $(`.${appr_id}:checked`).length === checkbox_appr.length;

                // Si toutes les cases sont cochées, cochez 'checkall', sinon décochez-le
                checkall.prop("checked", allChecked);

                $.each(checkbox_appr, function() {
                    if ($(this).is(':checked')) {
                        $(this).parent().addClass(`bg-gray-100`);

                        data.push({
                            idEmploye: $(this).attr('data-idAppr'),
                            idSeance: $(this).attr('data-idSe'),
                            idProjet: $(this).attr('data-idProj')
                        })

                    } else {
                        $(this).parent().removeClass(`bg-gray-100`);
                    }

                });

                data_check_appr = data;
            }

            function checkallAppr(appr_id, allcheck) {
                var data = new Array();
                var checkbox_appr = $(`.${appr_id}`);
                var checkall = $(`#${allcheck}`);

                checkbox_appr.prop("checked", checkall.is(
                    ':checked'));
                if (checkall.is(':checked')) {
                    checkbox_appr.parent().addClass(
                        `bg-gray-100`);
                } else {
                    checkbox_appr.parent().removeClass(
                        `bg-gray-100`);
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
                        url: `/etp/emargement/update/${idProjet}/${isPresent}`,
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
                drawer_eval.html('');

                drawer_eval.append(
                    `<x-drawer-evaluation idProjet="{{ $projet->idProjet }}" id="${idEmploye}"></x-drawer-evaluation>`);

                let offcanvasEvaluation = $('#offcanvasEvaluation_' + idEmploye);

                var bsOffcanvas = new bootstrap.Offcanvas(offcanvasEvaluation);

                $.ajax({
                    type: "get",
                    url: "/etp/projet/evaluation/checkEval/" + idProjet + "/" + idEmploye,
                    dataType: "json",
                    success: function(res) {

                        var form_method = $('#form_method_' + idEmploye)
                        form_method.attr('action', '/etp/projet/evaluation/chaud');

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
                              <div class="rating-block_${v_eval.idQuestion} w-12 flex items-center cursor-pointer justify-center one" data-value="1">1</div>
                              <div class="rating-block_${v_eval.idQuestion} w-12 flex items-center cursor-pointer justify-center two" data-value="2">2</div>
                              <div class="rating-block_${v_eval.idQuestion} w-12 flex items-center cursor-pointer justify-center three" data-value="3">3</div>
                              <div class="rating-block_${v_eval.idQuestion} w-12 flex items-center cursor-pointer justify-center four" data-value="4">4</div>
                              <div class="rating-block_${v_eval.idQuestion} w-12 flex items-center cursor-pointer justify-center five" data-value="5">5</div>
                              <div class="ratings_${v_eval.idQuestion} text-transparent">0</div>
                              <input type="hidden" value="5" name="eval_note[]" id="ratings-input_${v_eval.idQuestion}">
                            </div>
                            <div class="flex flex-col gap-0 w-[40%]">
                                <div class="inline-flex items-center justify-end w-full gap-2">
                                    <label class="w-full text-base font-semibold text-right text-gray-400"></label>
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
                    <div class="flex items-center justify-center w-12 cursor-pointer rating-block-note one" data-value="1">1</div>
                    <div class="flex items-center justify-center w-12 cursor-pointer rating-block-note two" data-value="2">2</div>
                    <div class="flex items-center justify-center w-12 cursor-pointer rating-block-note three" data-value="3">3</div>
                    <div class="flex items-center justify-center w-12 cursor-pointer rating-block-note four" data-value="4">4</div>
                    <div class="flex items-center justify-center w-12 cursor-pointer rating-block-note five" data-value="5">5</div>
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
                    <button class="btn btn-primary bg-[#A462A4]" type="submit">Valider mes réponses</button>
                  </div>`);
                        } else {
                            $.each(res.typeQuestions, function(i, v_type) {
                                if (v_type.idTypeQuestion != 5) {

                                    content_eval.append(`<div class="p-4 rounded-md border-[1px] border-gray-200 border-dashed flex flex-col gap-2">
                                <div class="inline-flex items-center w-full gap-4">
                                    <label class="text-xl font-semibold text-gray-700 type_` + v_type.idTypeQuestion +
                                        `">` +
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

                                showEvalTable(idProjet, idEmploye);

                                $('#raty_notation_' + v_o.idEmploye).raty({
                                    score: v_o.generalApreciate,
                                    space: false,
                                    readOnly: true
                                });

                                $('#raty_notation_' + v_o.idEmploye + ' img').addClass(`w-4 md:w-5`);
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

                            // modif_eval.append(
                            //     `<label class="ml-2 btn btn-primary" onclick="editEval(${idProjet}, ${idEmploye})">Modifier la fiche</label>`
                            // )
                        }
                    }
                });

                bsOffcanvas.show();

            }


            function showEvalTable(idProjet, idEmploye) {
                $.ajax({
                    type: "get",
                    url: "/etp/projet/evaluation/checkEval/" + idProjet + "/" + idEmploye,
                    dataType: "json",
                    success: function(res) {

                        let appriciation = $('.appreciation_' + idEmploye);
                        appriciation.html('');

                        if (res.checkEval <= 0) {
                            appriciation.text('');
                        } else {
                            $.each(res.one, function(i, v_o) {
                                // appriciation.text(v_o.generalApreciate);
                                appriciation.raty({
                                    score: v_o.generalApreciate,
                                    space: false,
                                    readOnly: true
                                });

                                appriciation.addClass(`w-4 md:w-5`);
                            });
                        }
                    }
                });
            }

            function editEval(idProjet, idEmploye) {
                $.ajax({
                    type: "get",
                    url: "/etp/projet/evaluation/checkEval/" + idProjet + "/" + idEmploye,
                    dataType: "json",
                    success: function(res) {

                        console.log(res);
                        var form_method = $('#form_method_' + idEmploye)
                        var _method = $('._method_' + idEmploye)
                        form_method.attr('action', '/etp/projet/evaluation/editEval');

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
                                        if (parseFloat(everyEle.attr('data-value')) == rating) {
                                            everyEle.css('opacity', 1);
                                        }
                                    }

                                    ratingBlocks.click(function() {
                                        var rating = parseFloat($(this).attr(
                                            'data-value'));
                                        ratingBlocks.css('opacity', '0.2');
                                        ratings.html($(this).attr('data-value'));
                                        $('#ratings-input_' + v_eval.idQuestion).val(
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

                        val_comment.append(`<x-input class="val_comment" type="textarea" name="idValComment" />`);

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
                    url: `/etp/projets/${idProjet}/details`,
                    dataType: "json",
                    success: function(res) {
                        if (res.seanceCount > 0 && (res.apprsCount > 0 || res.apprenantInterCount > 0)) {
                            presence_canvas.append(`<li onclick="__global_drawer('offcanvasPresence')">
                                                    <a>
                                                        <i class="fa-solid fa-list-check"></i>
                                                        Emargement
                                                    </a>
                                                </li>`)
                        } else {
                            presence_canvas.append(`<li onclick="__modal_alert('offcanvasPresence')">
                                                    <a>
                                                        <i class="fa-solid fa-list-check"></i>
                                                        Emargement
                                                    </a>
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

            function getPresenceUnique(idProjet, idEmploye) {
                $.ajax({
                    type: "get",
                    url: "/etp/projet/apprenants/checkPresence/" + idProjet + "/" + idEmploye,
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



            function RestaurationList() {
                var content_restauration_list = $('#content_restauration_list');
                content_restauration_list.html('');

                /*    if (checked_restauration != null) {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                $.each(checked_restauration, function(i, v) {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    content_restauration_list.append(
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        `
        <tr>
            <td><i class='fa-solid rest_${v.idRestauration}'></i></td>
            <td onclick="offert(${v.id})">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <span class=""><strong>${v.typeRestauration}</strong> offert par ${v.paidBy = 1 ? "le <strong>centre de formation</strong>" : "l'<strong>entreprise</strong>"}</span>
                </label>
            </td>
            <td class="text-right">
                <button class="opacity-50 btn btn-ghost btn-sm"><i class="fa-solid fa-xmark"></i></button>
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
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                }*/
            }

            function openRestauration() {
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
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        class="w-6 h-6 stroke-info shrink-0">
                        <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <div class="">Merci de sélectionner le donateur qui fournit chaque repas pour ce projet. !</div>
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
                        <tbody>
                            <tr class="">
                                <th>1</th>
                                <td><i class="fa-solid fa-bread-slice"></i> Petit Déjeuner</td>
                                <td colspan=2>
                                        <td><input type="radio" name="restauration1" class="radio" /></td>
                                        <td><input type="radio" name="restauration1" class="radio" /></td>
                                </td>
                            </tr>
                            <tr class="">
                                <th>2</th>
                                <td><i class="fa-solid fa-mug-hot"></i> Pause café matin</td>
                                <td colspan=2>
                                        <td><input type="radio" name="restauration2" class="radio" /></td>
                                        <td><input type="radio" name="restauration2" class="radio" /></td>
                                </td>
                            </tr>
                            <tr class="">
                                <th>3</th>
                                <td><i class="fa-solid fa-bowl-rice"></i> Déjeuner</td>
                                <td colspan=2>
                                        <td><input type="radio" name="restauration3" class="radio" /></td>
                                        <td><input type="radio" name="restauration3" class="radio" /></td>
                                </td>
                            </tr>
                            <tr class="">
                                <th>4</th>
                                <td><i class="fa-solid fa-mug-hot"></i> Pause café après-midi</td>
                                <td colspan=2>
                                        <td><input type="radio" name="restauration4" class="radio" /></td>
                                        <td><input type="radio" name="restauration4" class="radio" /></td>
                                </td>
                            </tr>
                            <tr class="">
                                <th>5</th>
                                <td><i class="fa-solid fa-utensils"></i> Dîner</td>
                                <td colspan=2>
                                        <td><input type="radio" name="restauration5" class="radio" /></td>
                                        <td><input type="radio" name="restauration5" class="radio" /></td>
                                </td>
                            </tr>
                            <tr class="">
                                <th>6</th>
                                <td><i class="fa-solid fa-bottle-water"></i> Bouteille d'eau</td>
                                <td colspan=2>
                                        <td><input type="radio" name="restauration6" class="radio" /></td>
                                        <td><input type="radio" name="restauration6" class="radio" /></td>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="inline-flex justify-end w-full mt-4">
                    <div class="modal-action">
                        <form method="dialog">
                            <button class="btn btn-ghost">Annuler</button>
                            <button class="btn btn-primary">Enregistrer</button>
                        </form>
                    </div>
                </div>
            </div>
            `;

                openDialog(content);
            }


            function offert(id) {
                const input = $('#on-off-switch-' + id);
                const checked = input.is(':checked');
                var modal_content_master = $('#modal_content_master');
                modal_content_master.html('');

                console.log(checked);


                if (!checked) {
                    modal_content_master.append(`
                            <div class="modal fade" tabindex="-1" id="myModal" data-bs-backdrop="static">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                    <div class="bg-gray-100 modal-header">
                                        <h5 class="text-xl font-semibold text-gray-700 modal-title">Fournisseur</h5>
                                        <button type="button" class="text-white" data-bs-dismiss="modal" aria-label="Close">X</button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="text-lg font-medium text-gray-500">Repas offert par</p>
                                        <div class="inline-flex items-center gap-4 mt-2">
                                            <div class="inline-flex items-center gap-2 border-[1px] border-gray-200 rounded-md px-3 py-2 cursor-pointer hover:bg-gray-100 duratoin-200">
                                                <input id="1" class="form-check-input appearance-none checked:bg-[#A462A4] checked:ring-offset-1 checked:ring-[#A462A4]" type="radio" name="restauration" id="restauration1" value="1" checked>
                                                <label class="text-gray-500 cursor-pointer form-check-label" for="restauration1">
                                                    Le centre de formation
                                                </label>
                                            </div>  
                                            <div class="inline-flex items-center gap-2 border-[1px] border-gray-200 rounded-md px-3 py-2 cursor-pointer hover:bg-gray-100 duratoin-200">
                                                <input id="2" class="form-check-input appearance-none checked:bg-[#A462A4] checked:ring-offset-1 checked:ring-[#A462A4]" type="radio" name="restauration" id="restauration2" value="1">
                                                <label class="text-gray-500 cursor-pointer form-check-label" for="restauration2">
                                                    L'entreprise
                                                </label>
                                            </div>    
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <x-btn-ghost>Annuler</x-btn-ghots>
                                        <x-btn-primary onclick="manageRestauration(${id}, ${checked})">Confirmer</x-btn-primary>
                                    </div>
                                    </div>
                                </div>
                            </div>`);
                    var myModalEl = $('#myModal');
                    var modal = new bootstrap.Modal(myModalEl);
                    modal.show();

                } else {
                    modal_content_master.append(`
                            <div class="modal fade" tabindex="-1" id="myModal" data-bs-backdrop="static">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                    <div class="bg-gray-100 modal-header">
                                        <h5 class="text-xl font-semibold text-gray-700 modal-title">Confirmation de suppression</h5>
                                        <button type="button" class="text-white" data-bs-dismiss="modal" aria-label="Close">X</button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="text-lg font-medium text-gray-500">Vous voulez vraiment supprimer cet offre de restauration ?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <x-btn-ghost>Annuler</x-btn-ghots>
                                        <x-btn-primary onclick="manageRestauration(${id}, ${checked})">Confirmer</x-btn-primary>
                                    </div>
                                    </div>
                                </div>
                            </div>`);
                    var myModalEl = $('#myModal');
                    var modal = new bootstrap.Modal(myModalEl);
                    modal.show();
                }
            }

            function manageRestauration(id, checked) {
                if (!checked) {
                    const inputs = $(".form-check-input");
                    const paidBy = inputs.filter(':checked').attr('id');

                    $.ajax({
                        type: "POST",
                        url: "/etp/projets/addRestauration/{{ $projet->idProjet }}/" + id + "/" + paidBy,
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
                        url: "/etp/projets/deleteRestauration/{{ $projet->idProjet }}/" + id,
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

            function openModalInfo() {

                var modules = @json($modules);

                var modalites = @json($modalites);
                var idModuleProj = {{ $projet->idModule }};

                var idModuleProj = {{ $projet->idModule }};

                var content = `
             <div class="du_modal-box">
                <form method="dialog">
                <button class="absolute btn btn-sm btn-circle btn-ghost right-2 top-2">✕</button>
                </form>
                <div class="flex grid flex-col w-full grid-cols-2 gap-4">
                    <div class="grid col-span-2 grid-cols-subgrid">
                        <label class="w-full">
                            <div class="label">
                                <span class="label-text">Choisir la formation</span>
                            </div>
                            <select id="changeModuleSelect" class="w-full select select-bordered">
                            </select>
                        </label>
                    </div>
                    <div class="grid grid-cols-2 col-span-2 gap-2">
                        <div class="grid col-span-1">
                            <label class="w-full">
                                <div class="label">
                                    <span class="label-text">Début</span>
                                </div>
                                <input type="date" class="w-full input date_deb_input input-bordered" />
                            </label>
                        </div>
                        <div class="grid col-span-1">
                            <label class="w-full">
                                <div class="label">
                                    <span class="label-text">Echéance</span>
                                </div>
                                <input type="date" class="w-full input date_fin_input input-bordered" />
                            </label>
                        </div>
                    </div>

                    <div class="grid col-span-2 grid-cols-subgrid">
                        <label class="w-full">
                            <div class="label">
                                <span class="label-text">Modalité</span>
                            </div>
                            <select class="w-full select project_idModalite_detail select-bordered">
                            </select>
                        </label>
                    </div>
                </div>
                <div class="inline-flex justify-end w-full mt-4">
                    <div class="modal-action">
                        <form method="dialog">
                            <button class="btn btn-ghost">Annuler</button>
                            <button onclick="submitInfoBase()" class="btn btn-primary">Enregistrer</button>
                        </form>
                    </div>
                </div>
            </div>
            `;
                openDialog(content);

                $(document).ready(function() {

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


                    toastr.success('Succès', 'Modfication réussie', {
                        timeOut: 1500
                    });

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
                    url: "/etp/projets/" + idProjet +
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
                    url: "/etp/projets/" + idProjet +
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
                    url: "/etp/projets/" + idProjet +
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
                    url: "/etp/projets/" + idProjet + "/update/modalite",
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
                    url: "/etp/projets/" + idModule +
                        "/getProgrammeProject",
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


            // ============= GLOBAL DRAWER =============
            function __global_drawer(__offcanvas) {
                let __global_drawer = $('#drawer_content_detail');
                __global_drawer.html('');
                var projet = @json($projet);
                var sumHourSession = @json($totalSession ? $totalSession->sumHourSession : null)

                switch (__offcanvas) {
                    case 'offcanvasGeneral':
                        __global_drawer.append(
                            `<x-drawer-general id="{{ $projet->idProjet }}" ref="{{ $projet->project_reference }}" titre="{{ $projet->project_title }}" description="{{ $projet->project_description }}" projectType="{{ $projet->project_type }}" nbPlace="" ></x-drawer-general>`
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


                        //getAllForms();


                        if (`{{ $projet->project_type }}` == 'Interne') {

                            __global_drawer.append(`<x-drawer-formateur></x-drawer-formateur>`);

                            getAllFormInternes();
                            getFormInterneAdded({{ $projet->idProjet }});
                            break;
                        }



                    case 'offcanvasSalle':
                        __global_drawer.append(`<x-drawer-lieu></x-drawer-lieu>`);

                        __global_drawer.ready(function() {
                            //getAllSalle();
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
                        __global_drawer.append(
                            `<x-drawer-frais onClick="fermeturefrais('{{ $projet->idProjet }}')"></x-drawer-frais>`);

                        __global_drawer.ready(function() {
                            getfraisAssign({{ $projet->idProjet }}, 0);
                            getAllFrais();

                            $.each($('input[name="tvaRadio"]'), function(indexInArray, valueOfElement) {
                                $(this).on('change', function() {
                                    var newTaxe = $(this).val();

                                    var idProjet = {{ $projet->idProjet }};

                                    updateTaxe(idProjet, newTaxe);

                                    getfraisAssign({{ $projet->idProjet }}, 0);
                                    updatefraistotal({{ $projet->idProjet }});
                                    calculateTotalFrais();
                                });
                            });
                        });
                        break;

                    case 'offcanvasSubContractor':
                        __global_drawer.append(`<x-drawer-sub-contractor></x-drawer-sub-contractor>`);
                        getSubContractors();
                        // getSubcontractorSelected({{ $projet->idProjet }});
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
                    url: "/etp/seanceInternes/" + idProjet + "/getSeanceAndTotalTime",
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
            const inputFile = document.querySelector('#dropzone-file');
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
                        if (file.size <= 3000000) { // Limite de taille de 3 Mo
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
                            toastr.error("La taille de l'image dépasse 3 Mo");
                        }
                    });
                }

                // Afficher les instructions si aucune image valide n'est ajoutée
                if (validImagesCount === 0) {
                    uploadInstructions.style.display = 'flex';
                }
            }

            // Gestion des événements de glissement
            // dropzone.addEventListener('dragover', (event) => {
            //     event.preventDefault();
            //     dropzone.classList.add('dragover');
            // });

            // dropzone.addEventListener('dragleave', () => {
            //     dropzone.classList.remove('dragover');
            // });

            // dropzone.addEventListener('drop', (event) => {
            //     event.preventDefault();
            //     dropzone.classList.remove('dragover');
            //     const files = event.dataTransfer.files;
            //     handleFiles(files);
            // });

            // Gestion des événements de sélection de fichiers via l'input
            /* inputFile.addEventListener('change', function() {
                 const files = this.files;
                 handleFiles(files);
             });*/



            $('#uploadPhotoForm').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.success, 'Succès', {
                                timeOut: 2000
                            });
                            location.reload();
                        }
                        if (response.error) {
                            console.log(response.error);
                            toastr.error(response.error, 'Erreur', {
                                timeOut: 2000
                            });
                        }
                    },
                    error: function(xhr) {
                        alert('Erreur lors du téléchargement des fichiers');
                    }
                });
            });
        </script>
    @endsection
