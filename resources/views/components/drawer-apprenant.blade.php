<div class="offcanvas offcanvas-end !w-[80em]" tabindex="-1" id="offcanvasApprenant" aria-labelledby="offcanvasApprenant">
  <div class="flex flex-col w-full">
    <div class="w-full px-4 py-2 inline-flex items-center justify-between bg-gray-100">
      <p class="text-lg text-gray-500 font-medium">Ajouter des apprenants</p>
      <a data-bs-toggle="offcanvas" href="#offcanvasApprenant"
        class="w-10 h-10 rounded-md hover:bg-gray-200 duration-200 cursor-pointer flex items-center justify-center">
        <i class="fa-solid fa-xmark text-gray-500"></i>
      </a>
    </div>

    <div class="w-full overflow-x-auto p-4 h-[100vh] pb-3">
      <div class="flex flex-col gap-4 h-full w-full min-w-[650px] overflow-x-scroll overflow-y-auto">
        <x-apprenant-list />
        <div class="w-full inline-flex items-end justify-end pt-3 mb-14">
          <x-btn-ghost>
            <a data-bs-toggle="offcanvas" href="#offcanvasApprenant" class="hover:text-inherit">
              Annuler
            </a>
          </x-btn-ghost>
          <x-btn-primary>
            <a data-bs-toggle="offcanvas" href="#offcanvasApprenant" class="hover:text-inherit">
              Fermer
            </a>
          </x-btn-primary>
        </div>
      </div>
    </div>
  </div>
</div>
