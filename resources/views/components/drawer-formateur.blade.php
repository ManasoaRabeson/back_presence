<div class="offcanvas offcanvas-end !w-[80em]" tabindex="-1" id="offcanvasFormateur" aria-labelledby="offcanvasFormateur">
    <div class="flex flex-col w-full">
        <div class="w-full px-4 py-2 inline-flex items-center justify-between bg-gray-100">
            <p class="text-lg text-gray-500 font-medium">Ajouter un formateur</p>
            <a data-bs-toggle="offcanvas" href="#offcanvasFormateur"
                class="w-10 h-10 rounded-md hover:bg-gray-200 duration-200 cursor-pointer flex items-center justify-center">
                <i class="fa-solid fa-xmark text-gray-500"></i>
            </a>
        </div>

        <div class="w-full overflow-x-auto p-4 h-[100vh] pb-3">
            <div class="flex flex-col h-full gap-4 w-full min-w-[650px] overflow-x-scroll overflow-y-auto">
                <div class="flex h-full">
                    <div class="flex flex-col h-full items-start mr-4 w-1/2 border-r-[1px] border-gray-200">
                        <div class="flex flex-row gap-2 px-3 py-1 bg-gray-50 ">
                            <div class="w-1 h-6 bg-red-400"></div>
                            <label class="text-gray-500 text-xl font-normal">Liste de tous les formateurs</label>
                        </div>
                        <div class="h-full w-full bg-gray-50 mt-2">
                            <input id="main_search_form_projet" placeholder="Chercher un formateur"
                                onkeyup="mainSearch('main_search_form_projet', 'all_form_drawer')"
                                class="input input-bordered w-full bg-white" />

                            <div class="overflow-x-auto w-full mt-2">
                                <table class="table">
                                    <tbody id="all_form_drawer">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Liste des formateurs sélectionnés -->
                    <div class="flex flex-col items-start w-1/2 h-full">
                        <div class="inline-flex items-center w-max gap-2">
                            <div class="flex flex-row gap-2 px-3 py-1 bg-gray-50 ">
                                <div class="w-1 h-6 bg-green-400"></div>
                                <label class="text-gray-500 text-xl font-normal">Liste des formateurs selectionnés pour
                                    ce
                                    projet</label>
                            </div>
                        </div>
                        <div class="overflow-x-auto mt-2 w-full">
                            <table class="table">
                                <tbody id="form_drawer_added">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="w-full inline-flex items-end justify-end pt-3 mb-14">
                    <x-btn-ghost>
                        <a data-bs-toggle="offcanvas" href="#offcanvasFormateur" class="hover:text-inherit">
                            Annuler
                        </a>
                    </x-btn-ghost>
                    <x-btn-primary>
                        <a data-bs-toggle="offcanvas" href="#offcanvasFormateur" class="hover:text-inherit">
                            Fermer
                        </a>
                    </x-btn-primary>
                </div>
            </div>
        </div>
    </div>
</div>
