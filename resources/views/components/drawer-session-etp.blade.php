
<!-- component -->
<div class="offcanvas offcanvas-end !w-[80em]" data-bs-backdrop="static" tabindex="-1" id="offcanvasSession"
  aria-labelledby="offcanvasSession">
  <div class="w-full flex flex-col gap-2">
    <div class="w-full px-4 py-2 inline-flex items-center justify-between bg-gray-100">
      <p class="text-lg text-gray-500 font-medium" id="head_session">Ajouter des sessions</p>
      <a data-bs-toggle="offcanvas" href="#offcanvasSession"
        class="w-10 h-10 rounded-md hover:bg-gray-200 duration-200 cursor-pointer flex items-center justify-center">
        <i class="fa-solid fa-xmark text-gray-500"></i>
      </a>
    </div>
    <div class="w-full p-3 flex flex-col overflow-y-auto gap-2 h-[100vh] pb-6">
      {{-- Navigation --}}
      <div class="inline-flex min-w-[900px] overflow-x-scroll items-center gap-2 justify-between w-full">
        <div class="inline-flex items-center gap-2">
          {{-- Week: --}}
          <div class="w-max py-2 px-3 group/nav cursor-pointer bg-gray-200 flex justify-center rounded-md items-center">
            <a id="dp_today" onclick="">
              {{-- <i class="fa-solid fa-chevron-left"></i> --}}
              Aujourd'hui
            </a>
          </div>
          <div class="w-8 h-8 rounded-full group/nav cursor-pointer bg-gray-200 flex justify-center items-center">
            <a id="dp_yesterday" onclick="">
              <i class="fa-solid fa-chevron-left"></i>
            </a>
          </div>
          <div class="w-8 h-8 rounded-full group/nav cursor-pointer bg-gray-200 flex justify-center items-center">
            <a id="dp_tomorrow" onclick="">
              <i class="fa-solid fa-chevron-right"></i>
            </a>
          </div>
        </div>
        <div class="inline-flex items-center gap-2">
          <div class="w-full inline-flex items-center justify-start">
            <x-btn-ghost>
              <a data-bs-toggle="offcanvas" href="#offcanvasSession" class="hover:text-inherit">
                Annuler
              </a>
            </x-btn-ghost>
            <x-btn-primary onclick="location.reload()">Sauvegarder les modifications</x-btn-primary>
          </div>
        </div>
      </div>
      <div class="w-full relative min-w-[900px] overflow-x-scroll">
        <div class="w-14 h-8 bg-gray-100 absolute top-[1px] left-[1px] z-10"></div>
        <div id="dp_session_etp">
        </div>
      </div>
    </div>
  </div>
</div>
