<div class="offcanvas offcanvas-end !w-[80em]" tabindex="-1" id="offcanvasParticulier"
    aria-labelledby="offcanvasParticulier">
    <div class="flex flex-col w-full">
        <div class="w-full px-4 py-2 inline-flex items-center justify-between bg-gray-100">
            <p class="text-lg text-gray-500 font-medium">Ajouter un particulier</p>
            <a data-bs-toggle="offcanvas" href="#offcanvasParticulier"
                class="w-10 h-10 rounded-md hover:bg-gray-200 duration-200 cursor-pointer flex items-center justify-center">
                <i class="fa-solid fa-xmark text-gray-500"></i>
            </a>
        </div>
        <div class="flex h-full">
            <div class="flex h-full flex-col items-start mr-4 w-1/2 border-r-[1px] border-gray-200">
                <div class="flex flex-row gap-2 px-3 py-1 bg-gray-50 ">
                    <div class="w-1 h-6 bg-red-400"></div>
                    <label class="text-gray-500 text-xl font-normal">Liste de tous les particuliers</label>
                </div>
                <div class="h-full mt-2 bg-gray-50 w-full overflow-y-auto p-2">
                    <input id="main_search_part_projet" placeholder="Chercher un apprenant"
                        onkeyup="mainSearch('main_search_part_projet', 'all_part')"
                        class="input input-bordered w-full bg-white" />
                    <span id="select_appr_project"></span>
                    <div class="mt-2">
                        <table class="table">
                            <tbody id="all_part"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Liste des apprenants sélectionnés -->
            <div class="flex flex-col items-start w-1/2 h-full">
                <div class="inline-flex items-center w-max gap-2">
                    <div class="flex flex-row gap-2 px-3 py-1 bg-gray-50 ">
                        <div class="w-1 h-6 bg-green-400"></div>
                        <label class="text-gray-500 text-xl font-normal"><span
                                class="text-bold text-2xl countApprDrawer"></span>
                            particuliers
                            selectionnés pour ce projet</label>
                    </div>
                </div>
                <div class="h-full mt-2 w-full overflow-y-auto">
                    <span id="all_part_selected" class="">
                    </span>
                </div>
            </div>
        </div>

    </div>
</div>
