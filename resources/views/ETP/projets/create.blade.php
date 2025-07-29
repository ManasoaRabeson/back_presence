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
    <div id="tab2Inter" class="w-full mt-3 mb-10 ">
        <div id="smartwizard">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" href="#step-1">
                        <div class="num">1</div>
                        <div class="flex flex-col items-start">
                            <label class="text-base font-medium">Nouveau</label>
                            <p class="text-sm font-normal">Titre & Description</p>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#step-2">
                        <span class="num">2</span>
                        <div class="flex flex-col items-start">
                            <label class="text-base font-medium">Cours</label>
                            <p class="text-sm font-normal">Choisir une formation</p>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#step-3">
                        <span class="num">3</span>
                        <div class="flex flex-col items-start">
                            <label class="text-base font-medium">Date</label>
                            <p class="text-sm font-normal">Programmer la date</p>
                        </div>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#step-4">
                        <span class="num">4</span>
                        <div class="flex flex-col items-start">
                            <label class="text-base font-medium">Lieu de formation</label>
                            <p class="text-sm font-normal">Choisir une ville</p>
                        </div>
                    </a>
                </li>
            </ul>

            <div class="tab-content">

                <input type="hidden" id="main_etp_get_id">
                <input type="hidden" id="main_project_get_id">
                <input type="hidden" id="main_module_get_id">


                <div id="step-1" class="tab-pane" role="tabpanel" aria-labelledby="step-1">
                    <div class="flex flex-col items-center w-full h-full gap-4 mt-4">
                        <div class="flex flex-col w-full gap-1">
                            <x-input type="text" name="main_project_rerefence_inter" label="Référence du projet" />
                            <div id="" class="text-sm text-red-500"></div>
                        </div>
                        <div class="flex flex-col w-full gap-1">
                            <x-input type="text" name="main_project_title_inter" label="Titre du projet"
                                required="true" />


                            <div id="error_project_title" class="text-sm text-red-500"></div>
                        </div>
                        <div class="inline-flex items-center w-full gap-8">
                            <div class="inline-flex items-center gap-2 py-2">
                                <label class="text-base font-normal text-blue-600">Présentielle</label>
                                <x-tooltip content="Modalité" class="top-0">
                                    <div class="flex flex-row justify-between toggle">
                                        <label for="modalite_project_inter" class="flex items-center cursor-pointer">
                                            <div class="relative">
                                                <input type="checkbox" name="activation" id="modalite_project_inter"
                                                    class="hidden checkbox peer">
                                                <div
                                                    class="block border-[1px] border-blue-600 bg-blue-100 peer-checked:border-purple-900 peer-checked:bg-purple-400 w-9 h-4 rounded-full">
                                                </div>
                                                <div
                                                    class="absolute top-0 w-4 h-4 transition bg-blue-600 rounded-full dot peer-checked:translate-x-5 peer-checked:bg-purple-900">
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </x-tooltip>
                                <label class="text-base font-normal text-purple-900">En ligne</label>

                            </div>
                        </div>

                        <div class="flex flex-col w-full gap-1">
                            <x-input type="textarea" name="main_project_description_inter" label="Description" />
                            <div id="error_description_inter" class="text-sm text-red-500"></div>
                        </div>

                        <div class="inline-flex items-center justify-end w-full gap-2 mt-3">
                            <button type="button"
                                class="focus:outline-none px-3 bg-[#A462A4] py-2 rounded-md text-white hover:bg-[#A462A4]/90 transition duration-200 text-sm"
                                id="main_next_btn_project_inter">Choisir une formation</button>
                        </div>
                    </div>
                </div>
                <div id="step-2" class="tab-pane" role="tabpanel" aria-labelledby="step-2">
                    <div class="flex flex-col w-full gap-4">
                        <div class="flex h-[25em] min-w-[500px] overflow-x-auto w-full">
                            <div class="flex flex-col items-start mr-4 w-1/2 border-r-[1px] border-gray-200">
                                <div class="flex flex-row gap-2 px-3 py-1 bg-gray-50 ">
                                    <div class="w-1 h-6 bg-red-400"></div>
                                    <label class="text-xl font-normal text-gray-500">Liste de tous les cours
                                        internes</label>
                                </div>
                                <input id="main_search_cours" placeholder="Chercher un cours"
                                    onkeyup="mainSearchCours('main_search_cours', 'main_get_all_module_projects_inter')"
                                    class="!bg-white password-toggle outline-none w-full bg-transparent pl-2 h-10 border-[1px] border-gray-200 rounded-md hover:border-purple-300 focus:border-purple-300 focus:ring-2 focus:ring-purple-100 duration-200 text-gray-400" />
                                <div class="w-full h-full mt-2 bg-gray-50">
                                    <ul id="main_get_all_module_projects_inter"
                                        class="select-list list-none p-2 relative rounded w-full flex flex-row flex-wrap gap-2 justify-start items-start overflow-y-auto max-h-[25em]">
                                    </ul>
                                </div>
                            </div>

                            <div class="flex flex-col items-start w-1/2 h-full">
                                <div class="inline-flex items-center gap-2 w-max">
                                    <div class="flex flex-row gap-2 px-3 py-1 bg-gray-50 ">
                                        <div class="w-1 h-6 bg-green-400"></div>
                                        <label class="text-xl font-normal text-gray-500">Le cours sélectionné pour ce
                                            projet </label>
                                    </div>
                                </div>
                                <div class="w-full h-full mt-2">
                                    <ul id="main_get_all_module_projects_selected_inter"
                                        class="flex flex-row flex-wrap w-full gap-2 p-2 list-none select-list">
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="inline-flex items-center justify-end w-full gap-2 mt-3">
                            <button type="button"
                                class="px-3 py-2 text-sm text-gray-600 transition duration-200 bg-gray-300 rounded-md btn sw-btn-prev sw-btn prevBtn focus:outline-none hover:bg-gray-300/80"
                                id="prevBtn_inter">Revenir</button>
                            <button type="button"
                                class="btn sw-btn-next sw-btn focus:outline-none px-3 bg-[#A462A4] py-2 rounded-md text-white hover:bg-[#A462A4]/90 transition duration-200 text-sm"
                                id="main_next_btn_cours_inter">Selectionner la date du projet</button>
                        </div>
                    </div>
                </div>
                <div id="step-3" class="tab-pane" role="tabpanel" aria-labelledby="step-3">
                    <div class="flex flex-col items-center w-full gap-4 mt-4">
                        <div class="flex flex-col items-start w-full">
                            <p class="text-lg text-left text-gray-400">Veuillez ajouter les sessions pour ce projet de
                                formation.</p>
                        </div>
                        <div class="flex flex-col justify-start w-full gap-3">
                            <div class="inline-flex items-center gap-2">
                                <div class="flex flex-col w-full gap-1">

                                    <x-input type="date" name="main_date_debut_project" label="Début du projet"
                                        required="true" />
                                    <div id="error_ref" class="text-sm text-red-500"></div>

                                </div>
                                <div class="flex flex-col w-full gap-1">

                                    <x-input type="date" name="main_date_fin_project" label="Fin du projet" />
                                    <div id="error_tag" class="text-sm text-red-500"></div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="inline-flex items-center justify-end w-full gap-2 mt-3">
                        <button type="button"
                            class="px-3 py-2 text-sm text-gray-600 transition duration-200 bg-gray-300 rounded-md prevBtn btn sw-btn-prev sw-btn focus:outline-none hover:bg-gray-300/80"
                            id="prevBtn_inter">Revenir</button>
                        <button type="button"
                            class="nextBtn btn sw-btn-next sw-btn focus:outline-none px-3 bg-[#A462A4] py-2 rounded-md text-white hover:bg-[#A462A4]/90 transition duration-200 text-sm"
                            id="">Choisir lieu de formation </button>
                    </div>
                </div>

                <div id="step-4" class="tab-pane" role="tabpanel" aria-labelledby="step-4">
                    <div class="flex flex-col items-center w-full gap-4 mt-4">
                        <div class="flex flex-col items-start w-full">
                            <p class="text-lg text-left text-gray-400">Où se fera ce projet de formation
                                inter-entreprise ?</p>
                        </div>
                        <div class="flex flex-col justify-start w-full gap-3">
                            <div class="flex flex-col w-full gap-1">
                                <label for="ville" class="text-gray-600">Lieu de formation</label>
                                <p for="" class="text-sm text-gray-400"></p>
                                <select name="" id=""
                                    class="outline-none w-full bg-transparent p-2 h-10 border-[1px] border-gray-200 rounded-md hover:border-purple-300 focus:border-purple-300 focus:ring-2 focus:ring-purple-100 duration-200 text-gray-400">
                                    <option>Antananarivo</option>
                                    <option>Antsirabe</option>
                                    <option>Fianarantsoa</option>
                                    <option>Mahajanga</option>
                                    <option>Toamasina</option>
                                    <option>Antsiranana (Diego Suarez)</option>
                                    <option>Toliara</option>
                                    <option>Morondava</option>
                                    <option>Ambositra</option>
                                    <option>Ambanja</option>
                                    <option>Ambatondrazaka</option>
                                    <option>Ambalavao</option>
                                    <option>Antsohihy</option>
                                    <option>Ambovombe</option>
                                    <option>Mananjary</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="inline-flex items-center justify-end w-full gap-2 mt-3">
                        <button type="button"
                            class="px-3 py-2 text-sm text-gray-600 transition duration-200 bg-gray-300 rounded-md prevBtn btn sw-btn-prev sw-btn focus:outline-none hover:bg-gray-300/80"
                            id="prevBtn_inter">Revenir</button>
                        <button onclick="mainUpdateDateProject($('#main_project_get_id').val())" type="button"
                            class=" focus:outline-none px-3 bg-[#A462A4] py-2 rounded-md text-white hover:bg-[#A462A4]/90 transition duration-200 text-sm"
                            id="nextBtn_inter">Sauvegarder le projet</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
