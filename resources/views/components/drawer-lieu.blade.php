<div class="offcanvas offcanvas-end !w-[80em]" tabindex="-1" id="offcanvasSalle" aria-labelledby="offcanvasSalle">
  <div class="flex flex-col w-full">
    <div class="w-full px-4 py-2 inline-flex items-center justify-between bg-gray-100">
      <p class="text-lg text-gray-500 font-medium">Ajouter le lieu et salle</p>
      <a data-bs-toggle="offcanvas" href="#offcanvasSalle"
        class="w-10 h-10 rounded-md hover:bg-gray-200 duration-200 cursor-pointer flex items-center justify-center">
        <i class="fa-solid fa-xmark text-gray-500"></i>
      </a>
    </div>

    <div class="w-full overflow-x-auto p-4 h-[100vh] pb-3">
      <div class="flex flex-col gap-4 w-full h-full min-w-[650px] overflow-x-scroll">
        <div class="flex h-full overflow-y-auto">
          <div class="flex flex-col h-full items-start mr-4 w-2/3 border-r-[1px] border-gray-200">
            <div class="flex flex-row gap-2 px-3 py-1 bg-gray-50 ">
              <div class="w-1 h-6 bg-red-400"></div>
              <label class="text-gray-500 text-xl font-normal">Liste des lieux et salles à disposition</label>
            </div>
            <div class="w-full h-full mt-2 bg-gray-50 overflow-y-auto">
              <div class="grid grid-cols-3 gap-2">
                <div class="grid col-span-2 p-2 items-start h-full overflow-y-auto">
                  <h1 class="text-xl text-gray-600 font-medium h-max">Lieu et adresse</h1>
                  <span>
                    <input id="main_search_client_project" placeholder="Chercher un lieu et adresse"
                        onkeyup="salleSearch('main_search_client_project', 'get_all_salle_detail')"
                        class="input input-bordered w-full bg-white" />
                  </span>
                  <ul class="get_all_salle_detail flex flex-col gap-2 p-2"></ul>
                </div>
              </div>
            </div>
          </div>
          <!-- Liste des salles sélectionnés -->
          <div class="flex flex-col items-start w-1/3 h-full">
            <div class="inline-flex items-center w-max gap-2">
              <div class="flex flex-row gap-2 px-3 py-1 bg-gray-50 ">
                <div class="w-1 h-6 bg-green-400"></div>
                <label class="text-gray-500 text-xl font-normal">Le lieu sélectionné pour ce projet</label>
              </div>
            </div>
            <div class="w-full h-full overflow-y-auto mt-2">
              <ul class="get_salle_selected flex flex-col gap-2 p-2 justify-start items-start"></ul>
            </div>
          </div>
        </div>
        <div class="w-full inline-flex items-end justify-end pt-3 mb-14">
          <x-btn-ghost>
            <a data-bs-toggle="offcanvas" href="#offcanvasSalle" class="hover:text-inherit">
              Annuler
            </a>
          </x-btn-ghost>
          <x-btn-primary>
            <a data-bs-toggle="offcanvas" href="#offcanvasSalle" class="hover:text-inherit">
              Fermer
            </a>
          </x-btn-primary>
        </div>
      </div>
    </div>
  </div>
</div>
