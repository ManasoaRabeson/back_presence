<div class="offcanvas offcanvas-end !w-[80em]" tabindex="-1" id="offcanvasClient" aria-labelledby="offcanvasClient">
    <div class="flex flex-col w-full">
        <div class="w-full px-4 py-2 inline-flex items-center justify-between bg-gray-100">
            <p class="text-lg text-gray-500 font-medium">Ajouter un client</p>
            <a data-bs-toggle="offcanvas" href="#offcanvasClient"
                class="w-10 h-10 rounded-md hover:bg-gray-200 duration-200 cursor-pointer flex items-center justify-center">
                <i class="fa-solid fa-xmark text-gray-500"></i>
            </a>
        </div>

        <div class="w-full overflow-x-auto p-4 h-[100vh] pb-3">
            <div class="flex flex-col gap-4 w-full h-full min-w-[650px] overflow-x-scroll">
                <div class="flex h-full overflow-y-auto">
                    <div class="flex flex-col h-full items-start mr-4 w-1/2 border-r-[1px] border-gray-200">
                        <div class="flex flex-row gap-2 px-3 py-1 bg-gray-50 ">
                            <div class="w-1 h-6 bg-red-400"></div>
                            <label class="text-gray-500 text-xl font-normal">Liste de tous les entreprises
                                clients</label>
                        </div>
                        <div class="w-full h-full mt-2 bg-gray-50 overflow-y-auto">
                            <span>
                                <input id="main_search_client_project" placeholder="Chercher un client"
                                    onkeyup="mainSearch('main_search_client_project', 'all_etp_drawer')"
                                    class="input input-bordered w-full bg-white" />
                            </span>
                            <div class="overflow-x-auto mt-2 p-4">
                                <table class="table">
                                    <tbody id="all_etp_drawer">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Liste des Clients sélectionnés -->
                    <div class="flex flex-col items-start w-1/2 h-full">
                        <div class="inline-flex items-center w-max gap-2">
                            <div class="flex flex-row gap-2 px-3 py-1 bg-gray-50 ">
                                <div class="w-1 h-6 bg-green-400"></div>
                                <label class="text-gray-500 text-xl font-normal">Le client sélectionné pour ce
                                    projet</label>
                            </div>
                        </div>
                        <div class="overflow-x-auto w-full p-4 mt-2">
                            <table class="table">
                                <tbody id="etp_drawer_added">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="w-full inline-flex items-end justify-end gap-2 pt-3 mb-14">
                    <a data-bs-toggle="offcanvas" href="#offcanvasClient" class="hover:text-inherit btn">
                        Annuler
                    </a>
                    <x-btn-primary>
                        <a data-bs-toggle="offcanvas" href="#offcanvasClient" class="hover:text-inherit">
                            Fermer
                        </a>
                    </x-btn-primary>
                </div>
            </div>
        </div>
    </div>
</div>
