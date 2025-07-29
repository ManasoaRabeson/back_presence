<div class="offcanvas offcanvas-end !w-[45em]" tabindex="-1" id="offcanvas3" aria-labelledby="offcanvas3">
  <div class="flex flex-col w-full">
    <div class="w-full px-4 py-2 inline-flex items-center justify-between bg-gray-100">
      <p class="text-lg text-gray-500 font-medium">Modifier vos formations</p>
      {{-- <a data-bs-toggle="offcanvas" href="#offcanvas" role="button" aria-controls="offcanvas"
        class="w-10 h-10 rounded-md hover:text-inherit hover:bg-gray-200 duration-200 cursor-pointer flex items-center justify-center">
        <i class="fa-solid fa-xmark text-gray-500"></i>
      </a> --}}
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"
        class="w-10 h-10 rounded-md hover:text-inherit hover:bg-gray-200 duration-200 cursor-pointer flex items-center justify-center">
        <i class="fa-solid fa-xmark text-gray-500"></i>
      </button>
    </div>
    <div class="w-full overflow-x-auto p-4 overflow-y-auto h-[100vh] pb-3">
      <div class="flex flex-col gap-y-3">
        <div class="flex justify-between items-center">
          <h1 class="text-xl font-semibold text-[#a462a4]">Ajouter un Formation</h1>
        </div>
        <!--Body-->
        <div class="my-2">
          <form action="{{ route('miniCv.index.store') }}" method="post">
            @csrf
            <input type="hidden" name="type" value="dp">
            <div class="flex flex-col gap-3">
              <div class="flex flex-col gap-4">
                <div class="flex flex-row gap-4">
                  <div class="flex flex-col w-full gap-1">
                    <x-input name="Ecole" type="text" label="Ecole" value="{{ old('Ecole') }}" />
                  </div>

                  <div class="flex flex-col w-full gap-1">
                    <x-input name="Diplome" type="text" label="Diplôme" value="{{ old('Diplome') }}" />
                  </div>

                  <div class="flex flex-col w-full gap-1">
                    <x-input name="Domaine" type="text" label="Domaine d'études" value="{{ old('Domaine') }}" />
                  </div>

                </div>
                <div class="w-full inline-flex flex-1 gap-2">
                  <div class="flex flex-col w-full gap-1">
                    <x-input name="Date_debut" type="date" label="Date debut" value="{{ old('Date_debut') }}" />

                  </div>

                  <div class="flex flex-col w-full gap-1">
                    <x-input name="Date_fin" type="date" label="Date fin" value="{{ old('Date_fin') }}" />
                  </div>
                </div>
              </div>
              <div class="flex justify-end pt-2">
                <input type="submit"
                  class="focus:outline-none px-2 bg-[#a462a4] py-1 ml-3 rounded-lg text-white hover:scale-105 hover:bg-[#a462a4]/80 transition duration-200 text-md"
                  value="Confirmer">
              </div>
          </form>
        </div>
        <!--Footer-->
      </div>
    </div>
  </div>
</div>
</div>
