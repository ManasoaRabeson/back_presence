@extends('layouts.masterEmp')
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
                                <p class="px-2">Début : <span class="capitalize">{{ $deb }}</span></p>
                                <p class="px-2">Echéance : <span class="capitalize">{{ $fin }}</span></p>
                            </div>

                            <div class="inline-flex flex-wrap items-center gap-x-4 gap-y-3">
                                <span
                                    class="px-3 py-1 rounded-xl w-max text-sm
                                        @switch($projet->project_type)
                                            @case('Intra')
                                                bg-[#1565c0]/20 text-[#1565c0]
                                                @break
                                             @case('Inter')
                                                bg-[#7209b7]/20 text-[#7209b7]
                                                @break    
                                        
                                            @default
                                                
                                        @endswitch
                                    ">
                                    {{ $projet->project_type }}
                                </span>

                                <span
                                    class="px-3 py-1 rounded-xl w-max text-sm
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
                                    class="px-3 py-1 text-sm text-white w-max rounded-xl
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
                                    {{ $projet->project_status }}
                                </span>

                                <span
                                    class="taux_presence px-3 py-1 text-sm border-[1px] rounded-xl border-slate-200"></span>
                            </div>

                            <p class="line-clamp-6">
                                @if ($projet->project_description != null)
                                    {!! $projet->project_description !!}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                <div class="grid col-span-1 mt-4 md:mt-0 items-center justify-center">
                    <button onclick="getEvaluation({{ $projet->idProjet }}, {{$userId}})" class="btn btn-outline btn-sm"><i class="fa-solid fa-chart-simple"></i> Effectuer mon évaluation à chaud</button>
                </div>
            </div>
        </div>
        <div role="tablist" class="w-full p-6 tabs tabs-bordered lg:tabs-lg">
            <input type="radio" name="my_tabs_2" role="tab" class="tab !w-max" aria-label="Vue d'ensemble du projet"
                checked="checked" />
            <div role="tabpanel" class="tab-content">
                <div class="grid w-full grid-cols-1 gap-4 m-4 lg:grid-cols-3">
                    <div class="grid w-full col-span-1 gap-4 lg:col-span-2 grid-cols-subgrid h-max">
                        <div class="grid grid-cols-1 gap-4">
                            {{-- Agenda --}}
                            <div class="accordion accordion-flush rounded-box" id="accordionExampleAgenda">
                                <div class="accordion-item card bg-base-100 !w-full shadow-xl !rounded-xl bg-white">
                                    <h2 class="accordion-header" id="headingOne">
                                        <span class="collapsed w-full inline-flex !rounded-xl items-center justify-between">
                                            <button class="w-full p-4 text-left group hover:underline underline-offset-4"
                                                type="button" data-bs-toggle="collapse" data-bs-target="#angenda"
                                                aria-expanded="true" aria-controls="angenda">
                                                <i class="mr-3 fa-solid fa-calendar-day"></i>
                                                Agenda
                                            </button>
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
                                                    <span
                                                        class="font-semibold text-blue-500">{{ $totalSession->sumHourSession }}</span>
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
                                                                <th>Début</th>
                                                                <th>Fin</th>
                                                                <th class="text-right">Durée</th>
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

                        {{-- support de cours --}}
                        <div class="accordion accordion-flush rounded-box" id="accordionDocument">
                            <div class="accordion-item card bg-base-100 !w-full shadow-xl !rounded-xl bg-white">
                                <h2 class="accordion-header" id="headingOne">
                                    <span class="collapsed w-full inline-flex !rounded-xl items-center justify-between">
                                        <button type="button"
                                            class="w-full p-4 pb-1 text-left group hover:underline underline-offset-4"
                                            data-bs-toggle="collapse" data-bs-target="#document" aria-expanded="true"
                                            aria-controls="document">
                                            <i class="mr-3 fa-solid fa-books"></i>
                                            Support de cours
                                        </button>


                                    </span>
                                </h2>
                                <div id="document" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                    data-bs-parent="#accordionDocument">
                                    <div class="accordion-body">
                                        <div id="support_table" class="mt-1 overflow-x-auto">
                                            @if (count($module_ressources) <= 0)
                                                <x-no-data class="!h-14" texte="Pas de ressource" />
                                            @else
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th class="w-[40%]">Nom</th>
                                                            <th>Type du fichier</th>
                                                            <th class="text-left">Taille</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($module_ressources as $module_ressource)
                                                            <tr>

                                                                <td class="text-left">
                                                                    {{ $module_ressource->module_ressource_name }}</td>
                                                                <td class="text-left">
                                                                    {{ $module_ressource->module_ressource_extension }}
                                                                </td>
                                                                <td class="text-left">{{ $module_ressource->taille }} Mo
                                                                </td>
                                                                <td>
                                                                    <a class="text-gray-400 text-center cursor-pointer hover:text-[#A462A4]"
                                                                        data-bs-toggle="tooltip"
                                                                        aria-label="Télécharger ce document"
                                                                        data-bs-original-title="Télécharger ce document"
                                                                        href="{{ route('projetEmp.download', $module_ressource->idModuleRessource) }}">
                                                                        <i class="fa-solid fa-download"></i>
                                                                    </a>
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
                                                <form id="uploadPhotoForm"
                                                    action="{{ route('emps.uploadphoto.momentum', ['idProjet' => $projet->idProjet]) }}"
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
                                                                <strong>(MAX. 5 Mo)</strong>
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
                                                    action="{{ route('emps.uploadphoto.momentum', ['idProjet' => $projet->idProjet]) }}"
                                                    method="POST" enctype="multipart/form-data"
                                                    class="flex items-center gap-4">
                                                    @csrf
                                                    <label class="flex-grow block">
                                                        <input type="file" name="myFile[]" multiple
                                                            accept=".svg , .jpeg, .jpg, .gif, .webp, .png"
                                                            class="block w-full text-sm text-gray-500 cursor-pointer md:w-3/4 lg:w-1/2 file:cursor-pointer file:w-48 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:btn file:disabled:opacity-50 file:disabled:pointer-events-none dark:text-neutral-500 dark:file:bg-purple-300 dark:hover:file:bg-purple-400">
                                                    </label>
                                                    <button type="submit" class="btn btn-outline btn-primary btn-sm">
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
                                                            <img src="{{ $imagesMomentum->url }}" alt="photo"
                                                                class="object-cover w-full h-full cursor-pointer"
                                                                onclick="window.location.href='{{ route('emps.detailForm.showmomentum', ['idProjet' => $projet->idProjet]) }}';">
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
                        <div class="accordion accordion-flush rounded-box" id="accordionExampleSalle">
                            <div class="accordion-item card bg-base-100 !w-full shadow-xl !rounded-xl bg-white">
                                <h2 class="accordion-header" id="headingOne">
                                    <span class="collapsed w-full inline-flex !rounded-xl items-center justify-between">
                                        <button class="w-full p-4 text-left group hover:underline underline-offset-4"
                                            type="button" data-bs-toggle="collapse" data-bs-target="#salle"
                                            aria-expanded="true" aria-controls="salle">
                                            <i class="mr-3 fa-solid fa-location-dot"></i>
                                            Lieu et salle
                                        </button>
                                    </span>
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

                                        <div class="w-full mt-3 overflow-x-auto">
                                            <div class="du-carousel du-carousel-vertical rounded-box h-96">
                                                @php
                                                    $lieux = [
                                                        'salle1.avif',
                                                        'salle2.avif',
                                                        'salle3.avif',
                                                        'salle4.avif',
                                                        'salle5.avif',
                                                        'salle7.webp',
                                                    ];
                                                @endphp
                                                @foreach ($lieux as $i => $lieu)
                                                    <div class="h-full du-carousel-item">
                                                        <img src="/img/salles/{{ $lieu }}" />
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion accordion-flush rounded-box" id="accordionExampleEntreprise">
                            <div class="accordion-item card bg-base-100 !w-full shadow-xl !rounded-xl bg-white">
                                <h2 class="accordion-header" id="headingOne">
                                    <span class="collapsed w-full inline-flex !rounded-xl items-center justify-between">
                                        <button class="w-full p-4 text-left group hover:underline underline-offset-4"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#entreprise_accordion" aria-expanded="true"
                                            aria-controls="entreprise_accordion">
                                            <i class="mr-3 fa-solid fa-building"></i>
                                            Centre de formation
                                        </button>
                                    </span>
                                </h2>
                                <div id="entreprise_accordion" class="accordion-collapse collapse show"
                                    aria-labelledby="headingOne" data-bs-parent="#accordionExampleEntreprise">
                                    <div class="accordion-body">
                                        <div class="overflow-x-auto">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <div class="flex items-center gap-3">
                                                                <div class="avatar">
                                                                    <div class="w-24 h-16 rounded-xl">
                                                                        @if (isset($cfp))
                                                                            @if ($cfp->logo != null)
                                                                                <img src="{{ $endpoint }}/{{ $bucket }}/img/entreprises/{{ $cfp->logo }}"
                                                                                    class="object-cover w-20 h-auto" />
                                                                            @else
                                                                                <span
                                                                                    class="flex items-center justify-center w-full h-full text-xl uppercase text-slate-600 bg-slate-200"><i
                                                                                        class="fa-solid fa-school"></i></span>
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    <div class="font-bold uppercase">
                                                                        {{ $cfp->customerName ?? '' }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
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

                        {{-- Restauration --}}
                        <div class="accordion accordion-flush rounded-box" id="accordionExample">
                            <div class="accordion-item card bg-base-100 !w-full shadow-xl !rounded-xl bg-white">
                                <h2 class="accordion-header" id="headingOne">
                                    <span class="collapsed w-full inline-flex !rounded-xl items-center justify-between">
                                        <button class="w-full p-4 text-left group hover:underline underline-offset-4"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#restauration_accordion" aria-expanded="true"
                                            aria-controls="restauration_accordion">
                                            <i class="mr-3 fa-solid fa-utensils"></i>
                                            Restauration
                                        </button>
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
@endsection
@push('custom_style')
    <link rel="stylesheet" href="{{ asset('css/bootstrap5-toggle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/calendar-evo/evo-calendar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/heat-rating.css') }}">
    <style>
        .scroll::-webkit-scrollbar {
            display: none;
        }

        .scroll {
            -ms-overflow-style: none;
            overflow: -moz-scrollbars-none;
        }

        .infobulle {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            z-index: 1000;
        }

        .toggle>.toggle-group>.toggle-handle {
            height: 0;
        }

        .dropzone {
            position: relative;
        }

        .dropzone.dragover {
            border-color: #4A90E2;
            background-color: #E0F7FF;
        }
    </style>
@endpush
@section('script')
    <script src="{{ asset('js/bootstrap5-toggle.jquery.min.js') }}"></script>
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

        @if (Session::has('error'))
            var errorMessage = "{{ Session('error') }}";
            toastr.error(errorMessage, 'Erreur', {
                timeOut: 3000
            });
        @elseif (Session::has('success'))
            var SuccessMessage = "{{ Session('success') }}";
            toastr.success(SuccessMessage, 'Succès', {
                timeOut: 1500
            });
        @endif

        // Aperçu de la photo momentum à uploader
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
        inputFile.addEventListener('change', function() {
            const files = this.files;
            handleFiles(files);
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
                    // console.log("nulllllllll");
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
                    // console.log('GET ALL APPRENANTS-->', res);
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
                    // console.log(res.etps);
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
                url: `/employe/projet/evaluation/checkEval/${idProjet}/${idEmploye}`,
                dataType: "json",
                success: callback
            });
        }

        function populateQuestions(res, idEmploye, idProjet) {
            let form_method = $(`#form_method_${idEmploye}`).attr('action', '/employe/projet/evaluation/chaud');
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
    </script>
@endsection
