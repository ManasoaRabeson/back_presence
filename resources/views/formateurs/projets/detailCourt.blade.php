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
                                <p class="px-2">Début :
                                    <span class="capitalize">
                                        {{ \Carbon\Carbon::parse($projet->dateDebut)->translatedFormat('d M Y') }}
                                    </span>
                                </p>
                                <p class="px-2">Echéance :
                                    <span class="capitalize">
                                        {{ \Carbon\Carbon::parse($projet->dateFin)->translatedFormat('d M Y') }}
                                    </span>
                                </p>
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
                                                bg-[#828282]
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
                                        <div onclick="__global_drawer('offcanvasApprenant')" style="cursor: pointer"
                                            class="flex justify-end w-full mr-4">
                                            <a>
                                                <i class="fa-solid fa-plus"></i>
                                                Ajouter des apprenants
                                            </a>
                                        </div>
                                    </span>
                                </h2>
                                <div id="participant" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                    data-bs-parent="#accordionExampleParticipant">
                                    <div class="accordion-body">
                                        <div id="appr_table" class="mt-4 overflow-x-auto">
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
                                            <i class="mr-3 fa-solid fa-door-open"></i>
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

    <span id="__global_drawer"></span>
    <span id="drawer_eval"></span>
    <span id="drawer_cv">
    </span>
@endsection
