<div class="offcanvas offcanvas-end !w-[45em]" tabindex="-1" id="offcanvas4" aria-labelledby="offcanvas4">
    <div class="flex flex-col w-full">
        <div class="w-full px-4 py-2 inline-flex items-center justify-between bg-gray-100">
            <p class="text-lg text-gray-500 font-medium">Modifier vos compétences</p>
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
                <!--Title-->
                <div class="flex justify-between items-center">
                    <h1 class="text-xl font-semibold text-[#a462a4]">Ajouter mes compétences</h1>
                </div>
                <!--Body-->
                <form action="{{ route('miniCv.index.store') }}" method="post">
                    @csrf
                    <input type="hidden" name="type" value="cpc">
                    <div class="flex flex-col w-full gap-3">
                        <div class="flex flex-col w-full gap-1">
                            <x-input name="Competence" type="text" label="Insérer vos compétences"
                                value="{{ old('Competence') }}" />
                        </div>
                    </div>
                    <div class="flex justify-end pt-2">
                        <button class="btn btn-primary">Confirmer</button>
                    </div>
                </form>
                <!--Footer-->
            </div>
        </div>
    </div>
</div>
