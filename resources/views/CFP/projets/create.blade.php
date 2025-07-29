<style>
    .rotate-button {
        display: inline-block;
        width: 150px;
        height: 30px;
        text-align: center;
        transform-style: preserve-3d;
        cursor: pointer;
        transition: all .3s ease;
        font-family: 'arial'
    }

    .rotate-button .rotate-button-face,
    .rotate-button .rotate-button-face-back {
        position: absolute;
        display: block;
        border: 1px solid #A462A4;
        transition: all .5s;
        color: #A462A4;
        text-decoration: none;
        width: 150px;
        height: 30px;
        line-height: 30px;
        border-radius: 4px;
    }

    .rotate-button .rotate-button-face {
        /* background-color: #fff; */
        background-color: #f3d1f3;
        transform: translateZ(15px);
    }

    .rotate-button .rotate-button-face-back {
        background-color: #A462A4;
        color: white;
        border: 1px solid white;
        transform: rotateX(-90deg) translateZ(15px);
    }

    .rotate-button:hover {
        transform: rotateX(90deg);
    }
</style>

<div class="w-full">
    <div id="erreurProjet" class="mb-2"></div>

    <div role="tablist" class="tabs tabs-boxed tabs-lg bg-white !w-full !mx-auto">
        <input type="radio" name="type_projet" role="tab" class="tab !rounded-lg !w-[310px]" aria-label="Projet Intra"
            checked="checked" />
        <div role="tabpanel" class="tab-content bg-white rounded-box p-2 !h-[48rem]">
            <div id="smartwizard-intra">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#step-1">
                            <span class="num">1</span>
                            <div class="flex flex-col items-start justify-center">
                                <label class="text-base font-medium text-left">Nouveau</label>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#step-2">
                            <span class="num">2</span>
                            <div class="flex flex-col items-start justify-center">
                                <label class="text-base font-medium text-left">Entreprise</label>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#step-3">
                            <span class="num">3</span>
                            <div class="flex flex-col items-start justify-center">
                                <label class="text-base font-medium text-left">Cours</label>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#step-4">
                            <span class="num">4</span>
                            <div class="flex flex-col items-start justify-center">
                                <label class="text-base font-medium text-left">Dossier</label>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="#step-5">
                            <span class="num">5</span>
                            <div class="flex flex-col items-start justify-center">
                                <label class="text-base font-medium text-left">Date</label>
                            </div>
                        </a>
                    </li>
                </ul>

                <div class="tab-content !h-[47rem]">
                    <input type="hidden" name="main_project_get_id" id="main_project_get_id">
                    <input type="hidden" id="main_etp_get_id">
                    <input type="hidden" id="main_module_get_id">
                    <div id="step-1" class="tab-pane !h-full" role="tabpanel" aria-labelledby="step-1">
                        <div role="alert" class="alert bg-blue-100 mt-2 mt-2">
                            <i class="fa-solid fa-info-circle text-blue-500"></i>
                            <span class="text-lg">Créer un projet pour une seule entreprise.</span>
                        </div>

                        <div class="w-full h-full flex flex-col gap-4 mt-4 items-center">
                            <div class="flex flex-col w-full gap-1">
                                <x-input type="text" name="main_project_rerefence" label="Référence du projet" />
                                <div id="main_rerefence" class="text-sm text-red-500"></div>
                            </div>
                            <div class="flex flex-col w-full gap-1">
                                <x-input type="text" name="main_project_title" label="Titre du projet"
                                    required="true" />
                                <div id="error_main_project_title" class="text-sm text-red-500"></div>
                            </div>


                            <div class="flex flex-col w-full gap-1">
                                <x-input type="textarea" name="main_project_description" label="Description" />
                                <div id="error_description" class="text-sm text-red-500"></div>
                            </div>

                            <div class="mt-2 w-full inline-flex items-center gap-2 justify-end">
                                <a class="rotate-button" id="main_next_btn_project">
                                    <span class="rotate-button-face">Suivant <i
                                            class="fa-solid fa-arrow-right"></i></span>
                                    <span class="rotate-button-face-back">Ajouter une entreprise</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div id="step-2" class="tab-pane !visible" role="tabpanel" aria-labelledby="step-2">
                        <div class="flex flex-col gap-4 w-full">
                            <div class="flex min-w-[500px] overflow-x-auto w-full">
                                <div
                                    class="flex flex-col !h-[41rem] overflow-y-auto items-start mr-4 w-1/2 border-r-[1px] border-gray-200">
                                    <div class="flex flex-row gap-2 px-3 py-1 bg-gray-50 ">
                                        <div class="w-1 h-6 bg-red-400"></div>
                                        <label class="text-gray-500 text-xl font-normal">Liste de tous les entreprises
                                            clients</label>
                                    </div>
                                    <div class="w-full h-full mt-2 bg-gray-50 space-y-2">
                                        <span class="inline-flex items-center w-full gap-4">
                                            <button onclick="showCreateEtp()" class="btn btn-outline btn-primary">
                                                <span class="inline-flex items-center gap-2">
                                                    <i class="fa-solid fa-plus"></i>
                                                    Nouvelle entreprise
                                                </span>
                                            </button>
                                            <input id="main_search_client" placeholder="Chercher un client"
                                                onkeyup="mainSearch('main_search_client', 'main_get_all_etps', 'li')"
                                                class="input input-bordered w-full input-primary"
                                                placeholder="Cherchez un client" />
                                        </span>

                                        <div id="etp_create_form" class="hidden grid grid-cols-5 gap-2">
                                            <div class="grid col-span-4 grid-cols-subgrid">
                                                <div class="flex flex-col w-full gap-1">
                                                    <x-input onkeyup="projectMainRcs()" type="text"
                                                        name="main_etp_rcs_search" label="Chercher un NIF"
                                                        required='true' placeholder="Veuillez entrez un NIF" />
                                                    <div id="error_rcs" class="text-sm text-red-500"></div>
                                                </div>
                                                <span class="main_rcs_to_append my-3"></span>
                                            </div>
                                        </div>
                                        <ul id="main_get_all_etps"
                                            class="select-list list-none p-2 relative rounded w-full flex flex-row flex-wrap gap-2 justify-start items-start">
                                        </ul>
                                    </div>
                                </div>
                                <!-- Liste des apprenants sélectionnés -->
                                <div class="flex flex-col items-start w-1/2 h-full">
                                    <div class="inline-flex items-center w-max gap-2">
                                        <div class="flex flex-row gap-2 px-3 py-1 bg-gray-50 ">
                                            <div class="w-1 h-6 bg-green-400"></div>
                                            <label class="text-gray-500 text-xl font-normal">Le client sélectionné pour
                                                ce
                                                projet</label>
                                        </div>
                                    </div>
                                    <div class="w-full h-full mt-2">
                                        <ul id="main_get_all_etps_selected"
                                            class="select-list list-none p-2 w-full flex flex-row flex-wrap gap-2">
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- Boutons Suivant et Prev -->
                        <div class="mt-3 w-full inline-flex items-center gap-2 justify-end">
                            <button type="button"
                                class="prevBtn focus:outline-none px-3 bg-gray-300 py-2 rounded-md text-gray-600 hover:bg-gray-300/80 transition duration-200 text-sm"
                                id="prevBtn">Revenir</button>
                            <a class="rotate-button" id="main_next_btn_etp">
                                <span class="rotate-button-face">Suivant <i
                                        class="fa-solid fa-arrow-right"></i></span>
                                <span class="rotate-button-face-back">Selectionner un cours</span>
                            </a>
                        </div>
                    </div>
                    <div id="step-3" class="tab-pane !visible" role="tabpanel" aria-labelledby="step-3">
                        <div class="flex flex-col gap-4 w-full">
                            <div class="flex min-w-[500px] overflow-x-auto w-full">
                                <div
                                    class="flex !h-[41rem] overflow-y-auto flex-col items-start mr-4 w-1/2 border-r-[1px] border-gray-200">
                                    <div class="flex flex-row gap-2 px-3 py-1 bg-gray-50 ">
                                        <div class="w-1 h-6 bg-red-400"></div>
                                        <label class="text-gray-500 text-xl font-normal">Liste de tous les
                                            cours</label>
                                    </div>
                                    <input id="main_search_cours" placeholder="Chercher un cours"
                                        onkeyup="mainSearch('main_search_cours', 'main_get_all_module_projects', 'li')"
                                        class="!bg-white password-toggle outline-none w-full bg-transparent pl-2 h-10 border-[1px] border-gray-200 rounded-md hover:border-purple-300 focus:border-purple-300 focus:ring-2 focus:ring-purple-100 duration-200 text-gray-400" />
                                    <div class="w-full h-full mt-2 bg-gray-50">
                                        <ul id="main_get_all_module_projects"
                                            class="select-list list-none p-2 relative rounded w-full flex flex-row flex-wrap gap-2 justify-start items-start">
                                        </ul>
                                    </div>
                                </div>
                                <!-- Liste des apprenants sélectionnés -->
                                <div class="flex flex-col items-start w-1/2 h-full">
                                    <div class="inline-flex items-center w-max gap-2">
                                        <div class="flex flex-row gap-2 px-3 py-1 bg-gray-50 ">
                                            <div class="w-1 h-6 bg-green-400"></div>
                                            <label class="text-gray-500 text-xl font-normal">Le cours sélectionné pour
                                                ce
                                                projet</label>
                                        </div>
                                    </div>
                                    <div class="w-full h-full mt-2">
                                        <ul id="main_get_all_module_projects_selected"
                                            class="select-list list-none p-2 w-full flex flex-row flex-wrap gap-2">
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 w-full inline-flex items-center gap-2 justify-end">
                                <button type="button"
                                    class="prevBtn focus:outline-none px-3 bg-gray-300 py-2 rounded-md text-gray-600 hover:bg-gray-300/80 transition duration-200 text-sm"
                                    id="prevBtn">Revenir</button>

                                <a class="rotate-button" id="main_next_btn_cours">
                                    <span class="rotate-button-face">Suivant <i
                                            class="fa-solid fa-arrow-right"></i></span>
                                    <span class="rotate-button-face-back">Choisir un dossier</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div id="step-4" class="tab-pane !visible" role="tabpanel" aria-labelledby="step-4">
                        <div class="flex flex-col gap-4 w-full">
                            <div role="alert" class="alert bg-blue-100 mt-2">
                                <i class="fa-solid fa-info-circle text-blue-500"></i>
                                <span class="text-lg">Veuillez assigné ce projet à un dossier pour mieux le retrouver à
                                    l'avenir.</span>
                            </div>
                            <div class="flex min-w-[500px] overflow-x-auto w-full">
                                <div class="w-full grid grid-cols-2 gap-3 h-[33rem] justify-start">
                                    <div class="grid col-span-2 h-max">
                                        <button onclick="addRowDossier('fileTableIntra')"
                                            class="btn btn-sm btn-outline btn-primary w-max btn_fileTableIntra">
                                            <i class="fa-solid fa-folder-plus"></i>
                                            Nouveau dossier
                                        </button>
                                    </div>
                                    <div class="grid col-span-1 h-[37rem] overflow-y-auto">
                                        <table class="table fileTableIntra fileTable bg-white h-max">
                                            <thead>
                                                <tr class="bg-gray-100">
                                                    <th>
                                                        Nom du dossier
                                                    </th>
                                                    <th class="text-right">
                                                        Document
                                                    </th>
                                                    <th class="text-right">
                                                        Projet
                                                    </th>
                                                    <th>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="grid col-span-1 h-[37rem] overflow-y-auto">
                                        <table class="table bg-white h-max">
                                            <thead>
                                                <tr class="bg-gray-100">
                                                    <th>
                                                        Nom du dossier
                                                    </th>
                                                    <th class="text-right">
                                                        Document
                                                    </th>
                                                    <th class="text-right">
                                                        Projet
                                                    </th>
                                                    <th>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="fileTableSelected">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 w-full inline-flex items-center gap-2 justify-end">
                                <button type="button"
                                    class="prevBtn focus:outline-none px-3 bg-gray-300 py-2 rounded-md text-gray-600 hover:bg-gray-300/80 transition duration-200 text-sm"
                                    id="prevBtn">Revenir</button>

                                <a class="rotate-button" id="main_next_btn_dossier">
                                    <span class="rotate-button-face">Suivant <i
                                            class="fa-solid fa-arrow-right"></i></span>
                                    <span class="rotate-button-face-back">Choisir une date</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div id="step-5" class="tab-pane !visible" role="tabpanel" aria-labelledby="step-5">
                        <div class="w-full flex flex-col gap-4 mt-4 items-center">
                            <div class="inline-flex items-center w-full gap-8">
                                <div class="form-control w-52">
                                    <label class="label cursor-pointer">
                                        <span class="label-text">Réserver ce projet</span>
                                        <input type="checkbox" name="activation" id="main_project_reservation"
                                            class="toggle toggle-primary" />
                                    </label>
                                </div>
                            </div>

                            <div class="flex flex-col w-full gap-1">
                                <label for="" class="text-gray-600">Modalité</label>
                                <p for="" class="text-sm text-gray-400"></p>
                                <select name="modalite" id="idModalite_intra"
                                    onchange="updateModalite($('#main_project_get_id').val(), 1)"
                                    class="select select-bordered w-full">
                                </select>
                                <div id="error_project_title" class="text-sm text-red-500"></div>
                            </div>

                            <div class="w-full flex flex-col items-start">
                                <p class="text-lg text-left text-gray-400">Entre quel date vous voulez programmer ce
                                    projet
                                    ? Vous pouvez
                                    modifier
                                    la date à l'avenir en fonction de votre disponibilité.</p>
                            </div>
                            <div class="flex flex-col justify-start w-full gap-3">
                                <div class="inline-flex items-center gap-2">
                                    <div class="flex flex-col w-full gap-1">
                                        <x-input type="date" name="main_date_debut_project"
                                            label="Début du projet" required="true" />
                                        <div id="error_ref" class="text-sm text-red-500"></div>
                                    </div>
                                    <div class="flex flex-col w-full gap-1">
                                        <x-input type="date" name="main_date_fin_project" label="Fin du projet" />
                                        <div id="error_tag" class="text-sm text-red-500"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Boutons Suivant et Prev -->
                        <div class="mt-3 w-full inline-flex items-center gap-2 justify-end">
                            <button type="button" class="prevBtn btn btn-ghost" id="prevBtn">Revenir</button>
                            {{-- <a onclick="mainUpdateDateProject($('#main_project_get_id').val(), 1, 1)"
                                class="btn btn-outline btn-primary save_view">
                                Sauvegarder et Voir détails
                            </a> --}}
                            <a class="btn btn-primary save_view hover:text-white"
                                onclick="mainUpdateDateProject($('#main_project_get_id').val(), 1, 1)">
                                <i class="fa-solid fa-bookmark"></i> Terminé
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="radio" name="type_projet" role="tab" class="tab !rounded-lg !w-[310px]"
            aria-label="Projet Inter">
        <div role="tabpanel" class="tab-content bg-white rounded-box p-2 !h-[48rem]">
            <div id="smartwizard" class="!h-full">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#step-1">
                            <div class="num">1</div>
                            <div class="flex flex-col items-start">
                                <label class="text-base font-medium text-left">Nouveau</label>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#step-2">
                            <span class="num">2</span>
                            <div class="flex flex-col items-start">
                                <label class="text-base font-medium text-left">Cours</label>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#step-3">
                            <span class="num">3</span>
                            <div class="flex flex-col items-start">
                                <label class="text-base font-medium text-left">Lieu</label>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#step-4">
                            <span class="num">4</span>
                            <div class="flex flex-col items-start">
                                <label class="text-base font-medium text-left">Dossier</label>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#step-5">
                            <span class="num">5</span>
                            <div class="flex flex-col items-start">
                                <label class="text-base font-medium text-left">Date</label>
                            </div>
                        </a>
                    </li>
                </ul>

                <div class="tab-content !h-[47rem]">
                    <div id="step-1" class="tab-pane h-full" role="tabpanel" aria-labelledby="step-1">
                        <div role="alert" class="alert bg-blue-100 mt-2">
                            <i class="fa-solid fa-info-circle text-blue-500"></i>
                            <span class="text-lg">Créer un projet pour plusieurs entreprises et des
                                particuliers.</span>
                        </div>

                        <div class="w-full h-full flex flex-col gap-4 mt-4 items-center">
                            <div class="flex flex-col w-full gap-1">
                                <x-input type="text" name="main_project_rerefence_inter"
                                    label="Référence du projet" />
                                <div id="" class="text-sm text-red-500"></div>
                            </div>
                            <div class="flex flex-col w-full gap-1">
                                <x-input type="text" name="main_project_title_inter" label="Titre du projet"
                                    required="true" />
                                <div id="error_main_project_title_inter" class="text-sm text-red-500"></div>
                            </div>

                            <div class="flex flex-col w-full gap-1">
                                <x-input type="textarea" name="main_project_description_inter" label="Description" />
                                <div id="error_main_project_description_inter" class="text-sm text-red-500"></div>
                            </div>

                            <div class="mt-2 w-full inline-flex items-center gap-2 justify-end">
                                <a class="rotate-button" id="main_next_btn_project_inter">
                                    <span class="rotate-button-face">Suivant <i
                                            class="fa-solid fa-arrow-right"></i></span>
                                    <span class="rotate-button-face-back">Choisir une formation</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div id="step-2" class="tab-pane h-full" role="tabpanel" aria-labelledby="step-2">
                        <div class="flex flex-col gap-4 w-full">
                            <div class="flex h-full min-w-[500px] overflow-x-auto w-full">
                                <div
                                    class="flex flex-col !h-[40rem] overflow-y-auto items-start mr-4 w-1/2 border-r-[1px] border-gray-200">
                                    <div class="flex flex-row gap-2 px-3 py-1 bg-gray-50 ">
                                        <div class="w-1 h-6 bg-red-400"></div>
                                        <label class="text-gray-500 text-xl font-normal">Liste de tous les
                                            cours</label>
                                    </div>
                                    <div class="w-full h-full mt-2 bg-gray-50">
                                        <ul id="main_get_all_module_projects_inter"
                                            class="select-list list-none p-2 relative rounded w-full flex flex-row flex-wrap gap-2 justify-start items-start">
                                        </ul>
                                    </div>
                                </div>

                                <div class="flex flex-col items-start w-1/2 h-full">
                                    <div class="inline-flex items-center w-max gap-2">
                                        <div class="flex flex-row gap-2 px-3 py-1 bg-gray-50 ">
                                            <div class="w-1 h-6 bg-green-400"></div>
                                            <label class="text-gray-500 text-xl font-normal">Le cours sélectionné pour
                                                ce
                                                projet</label>
                                        </div>
                                    </div>
                                    <div class="w-full h-full mt-2">
                                        <ul id="main_get_all_module_projects_selected_inter"
                                            class="select-list list-none p-2 w-full flex flex-row flex-wrap gap-2">
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 w-full inline-flex items-center gap-2 justify-end">
                                <button type="button"
                                    class="btn sw-btn-prev sw-btn prevBtn focus:outline-none px-3 bg-gray-300 py-2 rounded-md text-gray-600 hover:bg-gray-300/80 transition duration-200 text-sm"
                                    id="prevBtn_inter">Revenir</button>
                                <a class="rotate-button" id="main_next_btn_lieu_inter">
                                    <span class="rotate-button-face">Suivant <i
                                            class="fa-solid fa-arrow-right"></i></span>
                                    <span class="rotate-button-face-back">Choisir le lieu de formation</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div id="step-3" class="tab-pane h-full" role="tabpanel" aria-labelledby="step-3">
                        <div class="w-full flex flex-col gap-4 mt-4 items-center">
                            <div class="w-full flex flex-col items-start">
                                <p class="text-lg text-left text-gray-400">Où se fera ce projet de formation
                                    inter-entreprise ?</p>
                            </div>
                            <div class="flex flex-col justify-start w-full gap-3">
                                <div class="flex flex-col gap-1 w-full">
                                    <label for="ville" class="text-gray-600">Lieu de formation</label>
                                    <p for="" class="text-sm text-gray-400"></p>
                                    <select name="ville" id="idVille"
                                        onchange="updateVille($('#main_project_get_id').val())"
                                        class="outline-none w-full bg-transparent p-2 h-10 border-[1px] border-gray-200 rounded-md hover:border-purple-300 focus:border-purple-300 focus:ring-2 focus:ring-purple-100 duration-200 text-gray-400">
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 w-full inline-flex items-center gap-2 justify-end">
                            <button type="button"
                                class="prevBtn btn sw-btn-prev sw-btn focus:outline-none px-3 bg-gray-300 py-2 rounded-md text-gray-600 hover:bg-gray-300/80 transition duration-200 text-sm"
                                id="prevBtn_inter">Revenir</button>
                            <a class="rotate-button" id="main_next_btn_date_inter">
                                <span class="rotate-button-face">Suivant <i
                                        class="fa-solid fa-arrow-right"></i></span>
                                <span class="rotate-button-face-back">Choisir un dossier</span>
                            </a>
                        </div>
                    </div>

                    <div id="step-4" class="tab-pane !visible" role="tabpanel" aria-labelledby="step-4">
                        <div role="alert" class="alert bg-blue-100 mt-2">
                            <i class="fa-solid fa-info-circle text-blue-500"></i>
                            <span class="text-lg">Veuillez assigné ce projet à un dossier pour mieux le retrouver à
                                l'avenir.</span>
                        </div>
                        <div class="flex flex-col gap-4 w-full">
                            <div class="flex min-w-[500px] overflow-x-auto w-full">
                                <div class="w-full grid grid-cols-2 h-[33rem] gap-3 justify-start">
                                    <div class="grid col-span-2 h-max">
                                        <button onclick="addRowDossier('fileTableInter')"
                                            class="btn btn-sm btn-outline btn-primary w-max btn_fileTableInter">
                                            <i class="fa-solid fa-folder-plus"></i>
                                            Nouveau dossier
                                        </button>
                                    </div>
                                    <div class="grid col-span-1 h-[37rem] overflow-y-auto">
                                        <table class="table fileTableInter fileTable bg-white h-max">
                                            <thead>
                                                <tr class="bg-gray-100">
                                                    <th>
                                                        Nom du dossier
                                                    </th>
                                                    <th class="text-right">
                                                        Document
                                                    </th>
                                                    <th class="text-right">
                                                        Projet
                                                    </th>
                                                    <th>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="grid col-span-1 h-[37rem] overflow-y-auto">
                                        <table class="table bg-white h-max">
                                            <thead>
                                                <tr class="bg-gray-100">
                                                    <th>
                                                        Nom du dossier
                                                    </th>
                                                    <th class="text-right">
                                                        Document
                                                    </th>
                                                    <th class="text-right">
                                                        Projet
                                                    </th>
                                                    <th>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="fileTableSelected">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 w-full inline-flex items-center gap-2 justify-end">
                                <button type="button"
                                    class="prevBtn btn sw-btn-prev sw-btn focus:outline-none px-3 bg-gray-300 py-2 rounded-md text-gray-600 hover:bg-gray-300/80 transition duration-200 text-sm"
                                    id="prevBtn">Revenir</button>

                                <a class="rotate-button" id="main_next_btn_dossier_inter">
                                    <span class="rotate-button-face">Suivant <i
                                            class="fa-solid fa-arrow-right"></i></span>
                                    <span class="rotate-button-face-back">Choisir une date</span>
                                </a>
                            </div>
                        </div>
                    </div>


                    <div id="step-5" class="tab-pane h-full" role="tabpanel" aria-labelledby="step-5">
                        <div class="w-full flex flex-col gap-4 mt-4 items-center">
                            <div class="inline-flex items-center w-full gap-8">
                                <div class="form-control w-52">
                                    <label class="label cursor-pointer">
                                        <span class="label-text">Réserver ce projet</span>
                                        <input type="checkbox" name="activation" id="main_project_reservation_inter"
                                            class="toggle toggle-primary" />
                                    </label>
                                </div>
                            </div>

                            <div class="flex flex-col w-full gap-1">
                                <label for="" class="text-gray-600">Modalité</label>
                                <p for="" class="text-sm text-gray-400"></p>
                                <select name="modalite" id="idModalite_inter"
                                    onchange="updateModalite($('#main_project_get_id').val(), 2)"
                                    class="select select-bordered w-full">
                                </select>
                                <div id="error_project_title" class="text-sm text-red-500"></div>
                            </div>

                            <div class="w-full flex flex-col items-start">
                                <p class="text-lg text-left text-gray-400">Entre quel date vous voulez programmer ce
                                    projet
                                    ? Vous pouvez
                                    modifier
                                    la date à l'avenir en fonction de votre disponibilité.</p>
                            </div>
                            <div class="flex flex-col justify-start w-full gap-3">
                                <div class="inline-flex items-center gap-2">
                                    <div class="flex flex-col w-full gap-1">
                                        <x-input type="date" name="main_date_debut_project_inter"
                                            label="Début du projet" required="true" />
                                        <div id="error_ref" class="text-sm text-red-500"></div>
                                    </div>
                                    <div class="flex flex-col w-full gap-1">
                                        <x-input type="date" name="main_date_fin_project_inter"
                                            label="Fin du projet" />
                                        <div id="error_tag" class="text-sm text-red-500"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 w-full inline-flex items-center gap-2 justify-end">
                            <button type="button" class="prevBtn btn sw-btn-prev sw-btn btn btn-ghost"
                                id="prevBtn_inter">Revenir</button>
                            {{-- <a onclick="mainUpdateDateProject($('#main_project_get_id').val(), 2, 1)"
                                class="btn btn-outline btn-primary save_view">
                                Sauvegarder et Voir détails
                            </a> --}}
                            <a class="btn btn-primary save_view hover:text-white"
                                onclick="mainUpdateDateProject($('#main_project_get_id').val(), 2, 1)">
                                <i class="fa-solid fa-bookmark"></i> Terminé
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
