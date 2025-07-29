<div class="flex h-full">
    <div class="flex h-full flex-col items-start mr-4 w-1/2 border-r-[1px] border-gray-200">
        <div class="flex flex-row gap-2 px-3 py-1 bg-gray-50 ">
            <div class="w-1 h-6 bg-red-400"></div>
            <label class="text-gray-500 text-xl font-normal">Liste de tous les employés</label>
        </div>
        <div class="h-full mt-2 bg-gray-50 w-full overflow-y-auto p-2">
            <span class="inline-flex items-center justify-between">
                <input id="main_search_appr_projet" placeholder="Chercher un apprenant"
                    onkeyup="mainSearch('main_search_appr_projet', 'all_apprenant')"
                    class="input input-bordered w-full bg-white" />
            </span>
            <span id="select_appr_project"></span>
            <div class="mt-2">
                <table class="table">
                    <tbody id="all_apprenant"></tbody>
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
                    employés
                    selectionnés pour ce projet</label>
            </div>
        </div>
        <div class="h-full mt-2 w-full overflow-y-auto">
            <span id="all_apprenant_selected" class="">
            </span>
        </div>
    </div>
</div>
