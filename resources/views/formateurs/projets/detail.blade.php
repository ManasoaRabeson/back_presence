@extends('layouts.masterForm')

@section('content')
    <div class="flex flex-col w-full h-full">
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
                                    / Dossier : {{ $nomDossier ?? 'Non classé' }}
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
                                    class="taux_presence px-3 py-1 text-sm border-[1px] rounded-xl border-slate-200"></span>
                            </div>

                            @if ($projet->project_type == 'Inter')
                                <div>
                                    <p class="px-3 py-1 text-sm border-[1px] rounded-xl w-max border-slate-200">
                                        {{ $place_reserved }}/{{ $nbPlace }} Places réservées</p>
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
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-4 max-w-screen-xl mx-auto w-full h-[calc(100vh-30px)] mt-[41px] p-4 ">

            {{-- Participant --}}
            <div class="accordion accordion-flush rounded-box" id="accordionExampleParticipant">
                <div class="accordion-item card bg-base-100 !w-full shadow-xl !rounded-xl bg-white">
                    <h2 class="accordion-header" id="headingOne">
                        <span class="collapsed w-full inline-flex !rounded-xl items-center justify-between">
                            <button type="button" class="w-full p-4 text-left group hover:underline underline-offset-4"
                                data-bs-toggle="collapse" data-bs-target="#participant" aria-expanded="true"
                                aria-controls="participant">
                                <i class="mr-3 fa-solid fa-users"></i>
                                <span class="countApprDrawer"></span>
                                Participants
                            </button>

                            <div class="flex justify-end flex-1 pr-4 mt-2">
                                <div class="dropdown dropdown-bottom dropdown-end">
                                    <div onclick="__global_drawer('offcanvasPresence')"
                                        class="m-1 w-max btn btn-sm btn-outline">Emargement</div>
                                </div>
                            </div>
                        </span>
                    </h2>
                    <div id="participant" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                        data-bs-parent="#accordionExampleParticipant">
                        <div class="accordion-body h-[48vh] overflow-y-scroll">
                            <div id="appr_table" class="mt-4 overflow-x-auto">
                            </div>
                            <div id="part_table" class="mt-4 overflow-x-auto">
                            </div>
                        </div>
                    </div>
                </div>
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
                            <span class="text-[#A462A4] font-medium lowercase">{{ $mat->prestation_name }}-</span>
                        @endif
                    @endforeach.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-4">
                {{-- Agenda --}}
                <div class="accordion accordion-flush rounded-box" id="accordionExampleAgenda">
                    <div class="accordion-item card bg-base-100 !w-full shadow-xl !rounded-xl bg-white">
                        <h2 class="accordion-header" id="headingOne">
                            <span class="collapsed w-full inline-flex !rounded-xl items-center justify-between">
                                <button class="w-full p-4 text-left group hover:underline underline-offset-4" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#angenda" aria-expanded="true"
                                    aria-controls="angenda">
                                    <i class="mr-3 fa-solid fa-calendar-day"></i>
                                    Agenda
                                </button>
                            </span>
                        </h2>
                        <div id="angenda" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                            data-bs-parent="#accordionExampleAgenda">
                            <div class="accordion-body">
                                <p>
                                    @if (count($seances) > 0)
                                        Vous avez <span class="font-semibold text-blue-500">{{ count($seances) }}</span>
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
                                <h3 class="text-xl font-semibold text-gray-700">Ressources téléchargeables (Support
                                    de
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


            <div class="flex flex-col w-full p-3 bg-white border-[1px] shadow-sm border-gray-200 rounded-lg">
                <div class="inline-flex items-center gap-3 mb-3">
                    <i class="text-lg fa-solid fa-image"></i>
                    <h3 class="text-xl font-semibold text-gray-700">Momentum ( Galerie photo )</h3>
                </div>

                @if ($imagesMomentums->isEmpty())
                    <div class="mb-4">
                        <form id="uploadPhotoForm"
                            action="{{ route('uploadphoto.momentum', ['idProjet' => $projet->idProjet]) }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            <label for="dropzone-file"
                                class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 dropzone">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6 upload-instructions">
                                    <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                    </svg>
                                    <h3 class="text-gray-600">Cliquer ou glisser pour télécharger</h3>
                                    <p class="text-gray-500">SVG, PNG, JPG, GIF ou WEBP <strong>(MAX. 2
                                            Mo)</strong></p>
                                </div>
                                <div class="flex flex-row flex-wrap justify-center mt-4 img-area" data-img="">
                                    <!-- Les images sélectionnées seront ajoutées ici -->
                                </div>
                                <input id="dropzone-file" type="file" name="myFile[]" multiple class="hidden" />
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
                            method="POST" enctype="multipart/form-data" class="flex items-center gap-4">
                            @csrf
                            <label class="flex-grow block">
                                <input type="file" name="myFile[]" multiple
                                    class="block w-full text-sm text-gray-500 cursor-pointer md:w-3/4 lg:w-1/2 file:cursor-pointer file:w-48 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-400 file:text-white hover:file:bg-purple-500 file:disabled:opacity-50 file:disabled:pointer-events-none dark:text-neutral-500 dark:file:bg-purple-300 dark:hover:file:bg-purple-400">
                            </label>
                            <button type="submit"
                                class="text-white bg-gradient-to-r from-purple-500 via-purple-600 to-purple-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-purple-300 dark:focus:ring-purple-800 font-bold rounded-lg text-sm px-5 py-2.5 text-center">
                                Importer
                            </button>
                        </form>
                    </div>

                    <div class="w-full overflow-x-auto"
                        onclick="window.location.href='{{ route('cfp.projets.showmomentum', ['idProjet' => $projet->idProjet]) }}';">
                        <div class="w-full h-full owl-carousel" id="momentum">
                            @foreach ($imagesMomentums as $imagesMomentum)
                                <div class="relative w-full text-white truncate rounded-md bg-cyan-500 h-72">
                                    <img src="{{ $imagesMomentum->url }}" alt="photo"
                                        class="object-cover w-full h-full">
                                    <div
                                        class="absolute inset-0 flex items-center justify-center transition-opacity duration-300 bg-black bg-opacity-50 opacity-0 hover:opacity-100">
                                        <button class="px-4 py-2 text-black bg-white rounded">Lire</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <span id="__global_drawer"></span>
    <span id="drawer_eval"></span>
    <span id="drawer_cv">
    </span>
@endsection

@push('custom_style')
    <link rel="stylesheet" href="{{ asset('css/bootstrap5-toggle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/calendar-evo/evo-calendar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}">
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
    <script src="{{ asset('js/planningForm.js') }}"></script>
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('js/heat-rating.js') }}"></script>
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
                    if (file.size < 2000000) { // Limite de taille de 2 Mo
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
                        toastr.error("La taille de l'image dépasse 2 Mo");
                    }
                });
            }

            // Afficher les instructions si aucune image valide n'est ajoutée
            if (validImagesCount === 0) {
                uploadInstructions.style.display = 'flex';
            }
        }

        // Gestion des événements de glissement
        dropzone.addEventListener('dragover', (event) => {
            event.preventDefault();
            dropzone.classList.add('dragover');
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('dragover');
        });

        dropzone.addEventListener('drop', (event) => {
            event.preventDefault();
            dropzone.classList.remove('dragover');
            const files = event.dataTransfer.files;
            handleFiles(files);
        });

        // Gestion des événements de sélection de fichiers via l'input
        inputFile.addEventListener('change', function() {
            const files = this.files;
            handleFiles(files);
        });
    </script>
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
        getIdCustomer({{ $projet->idProjet }});

        var projet = @json($projet);
        if (projet.idCfp_inter == null) {
            getApprenantAdded({{ $projet->idProjet }});
            getApprenantProjets({{ $projet->idEtp }});
            getAllApprPresence({{ $projet->idProjet }});

        } else {
            getApprenantProjetInter({{ $projet->idProjet }});
            getAllApprPresenceInter({{ $projet->idProjet }});

            // Au chargement de la page, cacher tous les éléments <li>
            $('.list').hide();

            getApprenantAddedInter({{ $projet->idProjet }});

        }

        getAllEtps({{ $projet->idCfp_inter }});
        edit({{ $projet->idProjet }});
        getSeanceByIdStorage({{ $projet->idProjet }})

        const check = $('.formateur-li').attr('check');



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

        function getApprenantProjetInter(idProjet) {
            let idEtpParent = 18;
            $.ajax({
                type: "get",
                url: "/projetsForm/getApprenantProjetInter/" + idProjet,
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

        function __global_drawer(__offcanvas) {
            let __global_drawer = $('#__global_drawer');
            __global_drawer.html('');
            var projet = @json($projet);
            var sumHourSession = @json($totalSession ? $totalSession->sumHourSession : null)

            switch (__offcanvas) {
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
                            getApprenantAdded({{ $projet->idProjet }});
                            getApprenantProjets({{ $projet->idEtp }});
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

                case 'offcanvasFrais':
                    __global_drawer.append(
                        `<x-drawer-frais onClick="fermeturefrais('{{ $projet->idProjet }}')"></x-drawer-frais>`);

                    __global_drawer.ready(function() {
                        getfraisAssign({{ $projet->idProjet }}, 0);
                        getAllFrais();
                    });
                    break;

                default:
                    break;
            }

            let offcanvasId = $('#' + __offcanvas)
            var bsOffcanvas = new bootstrap.Offcanvas(offcanvasId);
            bsOffcanvas.show();
        }

        function getAllEtps(idCfp_inter) {
            $.ajax({
                type: "get",
                url: "/projetsForm/etp/getAllEtps",
                dataType: "json",
                success: function(res) {
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
                                    .etp_name +
                                    `" mail="` +
                                    val
                                    .etp_email + `" />`);
                            } else if (idCfp_inter != null) {
                                get_all_etps.append(`<x-etp-li id="` + val.idEtp +
                                    `" onclick="etpAssignInter({{ $projet->idProjet }}, ` + val
                                    .idEtp +
                                    `)" initial="` + val.etp_initial_name + `" nom="` + val
                                    .etp_name +
                                    `" mail="` +
                                    val
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


        function edit(idProjet) {
            $.ajax({
                type: "GET",
                url: "/projetsForm/emargement/" + idProjet,
                dataType: "json",
                success: function(res) {}
            });
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
                    url: `/projetsForm/emargement/update/${idProjet}/${isPresent}`,
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

        // Fonction pour remplir la liste des apprenants
        function fillApprenantList(apprs) {

            console.log(apprs);
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
                    $('.matricule_' + val.idEmploye).append(`<p class="text-sm text-gray-400">Matricule : ` +
                        val
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



        function getApprenantAdded(idProjet) {
            var all_appr_project = $('.getApprProject');
            all_appr_project.html('');

            $.ajax({
                type: "get",
                url: "/projetsForm/getApprenantAdded/" + idProjet,
                dataType: "json",
                beforeSend: function() {
                    all_appr_project.append(`<span class="initialLoading">Chargement ...</span>`);
                },
                complete: function() {
                    $('.initialLoading').remove();
                },
                success: function(res) {
                    appr_table(res);
                    appr_drawer_intra(res);
                    tauxPresenceGlobal(res.percentPresent);
                }
            });
        }

        function appr_table(data) {
            var appr_table = $('#appr_table')
            appr_table.html('');

            var countApprDrawer = $('.countApprDrawer');
            countApprDrawer.html('');

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

                        countApprDrawer.append(data.apprs.length);
                        var idEmploye = '';

                        $.each(data.apprs, function(i, appr) {
                            if (etp.etp_name == appr.etp_name) {
                                data_participant.append(
                                    `
                                <tr>
                                    <td class="capitalize">
                                        <span class="inline-flex items-center">
                                            <div class="mr-3 avatar">
                                                <div class="w-12 rounded-full">
                                                    ${ appr.emp_photo ? ` <img src="{{ $endpoint }}/{{ $bucket }}/img/employes/${appr.emp_photo}" />` : `<span class="flex items-center justify-center w-full h-full text-xl uppercase text-slate-600 bg-slate-200">${appr.emp_name.charAt(0)}</span>`
                                                    }
                                                </div>
                                            </div>
                                            <span class="flex flex-col">
                                                <span class="mr-1 uppercase">${appr.emp_name}</span> ${appr.emp_firstname ?? '' }
                                            </span>
                                        </span>
                                    </td>
                                    <td><div data-bs-toggle="tooltip" class="w-5 h-5 rounded-md uniquePresence_${appr.idEmploye}"></div></td>
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
                                    showEvalTable({{ $projet->idProjet }}, appr
                                        .idEmploye);
                                    loadBsTooltip();
                                });
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


        function getApprenantAddedInter(idProjet) {
            var eval_content = @json($eval_content);
            var eval_type = @json($eval_type);
            var all_appr_project = $('.getApprProject');
            all_appr_project.html('');

            $.ajax({
                type: "get",
                url: "/projetsForm/getApprenantAddedInter/" + idProjet,
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
                        all_appr_project.append(`
                <table class="w-full table-auto">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th class="hidden md:table-cell">Matricule</th>
                            <th class="hidden">Entreprise</th>
                            <th class="text-center">Fonction</th>
                            <th class="text-center">Présence</th>
                            <th class="text-center"><i class="fa-solid fa-star text-amber-500"></i></th>
                            <th class="text-center"></th>
                        </tr>
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

                            $('.get_all_appr_project').append(`
                                               <tr class="border-b">
                        <td class="!p-1">
                            <div class="inline-flex items-center gap-2">
                                <span class="appr_photo_${val.idEmploye}"></span>
                                <div class="flex flex-col gap-1">
                                    <label class="text-base font-normal text-gray-600 cursor-pointer">${val.emp_name} ${firstname}</label>
                                    <label class="text-sm text-gray-400">${val.etp_name}</label>
                                </div>
                            </div>
                        </td>
                        <td class="!p-1 hidden md:table-cell">${(val.emp_matricule || '--')}</td>
                        <td class="!p-1 hidden">
                            <label class="text-base text-gray-400">${val.etp_name}</label>
                        </td>
                        <td class="!p-1 text-center">
                            <label class="text-base text-gray-400">${(val.emp_fonction || '--')}</label>
                        </td>
                        <td class="!p-1 text-center">
                            <div class="flex items-center justify-center">
                                <div class="uniquePresence_${val.idEmploye} w-3 h-3 rounded-full"></div>
                            </div>
                        </td>
                        <td class="!p-1 text-center">
                            <label class="text-base text-gray-400 appreciation_${val.idEmploye}"></label>
                        </td>
                        <td class="!p-1 text-center">
                            <button onclick="__global_drawer('offcanvasPresence')" class="text-purple-500 underline cursor-pointer">
                                <span><i class="text-sm fa-solid fa-list-check"></i></span> Emargement
                            </button>
                        </td>
                    </tr>`);

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
                                                      <p class="text-sm text-gray-400 lowercase">` + mail + `</p>
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
        }

        // Fonction pour ajuster la mise en page selon la taille de la fenêtre
        function adjustLayout() {
            if ($(window).width() < 768) {
                $('.get_all_appr_project').find('td').css('padding', '8px 4px'); // Ajuste les espacements
            } else {
                $('.get_all_appr_project').find('td').css('padding', '12px 8px'); // Réinitialise les espacements
            }
        }

        // Exécute la fonction lors du chargement de la page
        adjustLayout();

        // Exécute la fonction lorsque la fenêtre est redimensionnée
        $(window).resize(function() {
            adjustLayout();
        });

        function showEvalTable(idProjet, idEmploye) {
            $.ajax({
                type: "get",
                url: "/projetsForm/checkEval/" + idProjet + "/" + idEmploye,
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
                url: "/projetsForm/checkEval/" + idProjet + "/" + idEmploye,
                dataType: "json",
                success: function(res) {

                    console.log(res);
                    var form_method = $('#form_method_' + idEmploye)
                    var _method = $('._method_' + idEmploye)
                    form_method.attr('action', '/projetsForm/editEval');

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



        function getEvaluation(idProjet, idEmploye) {

            let drawer_eval = $('#drawer_eval');
            drawer_eval.html('');

            drawer_eval.append(
                `<x-drawer-evaluation idProjet="{{ $projet->idProjet }}" id="${idEmploye}"></x-drawer-evaluation>`);

            let offcanvasEvaluation = $('#offcanvasEvaluation_' + idEmploye);

            var bsOffcanvas = new bootstrap.Offcanvas(offcanvasEvaluation);

            $.ajax({
                type: "get",
                url: "/projetsForm/checkEval/" + idProjet + "/" + idEmploye,
                dataType: "json",
                success: function(res) {

                    var form_method = $('#form_method_' + idEmploye)
                    form_method.attr('action', '/projetsForm/chaud');

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

        function getEtpAdded(idProjet) {
            $.ajax({
                type: "get",
                url: "/projetsForm/getEtpAdded/" + idProjet,
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
                                  <img onclick="showCustomer(${v.idEtp}, '/projetsForm/etp-drawer/')" src="/img/entreprises/${v.etp_logo}" alt="logo"
                                    class="object-cover w-full h-full rounded-xl">
                                </div>`);
                        } else {
                            $('.photo_etp_' + v.idEtp).append(
                                `<span onclick="showCustomer(${v.idEtp}, '/projetsForm/etp-drawer/')" class="flex items-center justify-center object-cover w-20 h-10 mr-4 text-gray-600 uppercase bg-gray-200 rounded-lg cursor-pointer">${v.etp_name[0]}</span>`
                            );

                            dash_etp.append(
                                `<span onclick="showCustomer(${v.idEtp}, '/projetsForm/etp-drawer/')" class="flex items-center justify-center object-cover w-20 h-10 font-semibold text-gray-600 uppercase bg-gray-200 rounded-lg cursor-pointer">${v.etp_name[0]}</span>`
                            );
                        }
                    });

                    listEtpAdded = $etpAdded;
                }
            });
        }

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

        function getEtpAssigned(idProjet) {
            $.ajax({
                type: "get",
                url: "/projetsForm/" + idProjet + "/etp/assign",
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
                                `<div  class="flex items-center justify-center w-20 h-10 text-gray-500 uppercase bg-gray-200 rounded-full">` +
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


        function getApprenantProjets(idEtp) {
            $.ajax({
                type: "get",
                url: "/projetsForm/getApprenantProjets/" + idEtp,
                dataType: "json",
                success: function(res) {
                    var all_apprenant = $('#all_apprenant');
                    all_apprenant.html('');

                    if (res.apprs.length <= 0) {
                        all_apprenant.append(`<x-no-data texte="Aucun résultat"></x-no-data>`);
                    } else {
                        $.each(res.apprs, function(key, val) {
                            all_apprenant.append(`<li
                                        class="list grid grid-cols-5 w-full px-3 py-2 border-[1px] border-gray-100 cursor-pointer hover:bg-gray-50 duration-200 rounded-md !bg-white">
                                        <div class="col-span-4">
                                          <div class="inline-flex items-center gap-2">
                                            <span id="photo_appr_` + val.idEmploye + `"></span>
                                            <div class="flex flex-col gap-0">
                                              <p class="text-base font-normal text-gray-700">` + val.emp_name + ` ` +
                                val
                                .emp_firstname + `</p>
                                              <p class="text-sm text-gray-400 lowercase">` + val.emp_email + `</p>
                                              <div class="flex flex-col">
                                                <p class="text-sm text-gray-400 normal-case">Matricule : ` + val
                                .emp_matricule + `</p>
                                                <p class="text-sm text-gray-400 normal-case">Fonction : ` + val
                                .emp_fonction + `</p>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="grid items-center justify-center w-full col-span-1">
                                          <div
                                            onclick="manageApprenant('post', {{ $projet->idProjet }}, ` + val
                                .idEmploye + `)"
                                            class="flex items-center justify-center w-10 h-10 duration-150 bg-green-100 rounded-full cursor-pointer icon hover:bg-green-50 group/icon">
                                            <i class="text-sm text-green-500 duration-150 fa-solid fa-plus group-hover/icon:text-green-600"></i>
                                          </div>
                                        </div>
                                      </li>`);

                            var photo_appr = $('#photo_appr_' + val.idEmploye);
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
                    url: 'homeForm/get-id-customer', // Assurez-vous que cette route retourne l'ID utilisateur
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
            const url = `/projetsForm/${idProjet}/getAllSeances`;
            await fetch(url, {
                    method: "GET"
                })
                .then(response => response.json())
                .then(data => {
                    dataEvnts = data.seances;
                    for (let obj of dataEvnts) {
                        let date = new Date(obj.end);
                        const monthName = date.toLocaleString(
                            'en-US', {
                                month: 'long'
                            });
                        objEvents.push({
                            id: obj.idSeance,
                            name: obj.module,
                            date: monthName + "/" + date
                                .getDate() + "/" + date
                                .getFullYear(),
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
                url: "/projetsForm/getApprenantAdded/" + idProjet,
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

        // =========== PRESENCE =============
        // Taux de présence
        function tauxPresenceGlobal(data) {
            var taux_presence = $('.taux_presence');
            taux_presence.html('');
            taux_presence.append(data ?? 'Inconnu');
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
                url: "/projetsForm/getApprenantAddedInter/" + idProjet,
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

        function getPresenceUnique(idProjet, idEmploye) {
            $.ajax({
                type: "get",
                url: "/projetsForm/checkPresence/" + idProjet + "/" + idEmploye,
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
                        url: "{{ route('evaluation.apprenant.form') }}",
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
                url: `/projetsForm/evaluation/aprrenant/${idEmploye}/${idProjet}`,
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
    </script>
@endsection
