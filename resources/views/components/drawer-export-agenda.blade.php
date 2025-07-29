<!-- component -->
<div class="offcanvas offcanvas-end !w-[80em]" data-bs-backdrop="static" tabindex="-1" id="offcanvasExport"
    aria-labelledby="offcanvasExport">
    <div class="flex flex-col w-full gap-2">
        <div class="inline-flex items-center justify-between w-full px-4 py-2 bg-gray-100">
            <p class="text-lg font-medium text-gray-500" id="head_session">DATA EXPORT</p>
            <a data-bs-toggle="offcanvas" href="#offcanvasApprenant"
                class="flex items-center justify-center w-10 h-10 duration-200 rounded-md cursor-pointer hover:bg-gray-200">
                <i class="text-gray-500 fa-solid fa-xmark"></i>
            </a>
        </div>
        <div class="w-full p-3 flex flex-col overflow-y-auto gap-2 h-[100vh] pb-6">
            {{-- Navigation --}}
            <div class="inline-flex min-w-[900px] overflow-x-scroll items-center gap-2 justify-between w-full">
                <div class="inline-flex items-center gap-2">



                </div>
                <div class="inline-flex items-center gap-2">
                    <div class="inline-flex items-center justify-start w-full">
                        <x-btn-ghost>
                            <a data-bs-toggle="offcanvas" href="#offcanvasExport" class="hover:text-inherit">
                                Annuler
                            </a>
                        </x-btn-ghost>
                        <x-btn-primary onclick="location.reload()">Sauvegarder les modifications</x-btn-primary>
                    </div>
                </div>
            </div>
            <div class="w-full relative min-w-[900px] overflow-x-scroll">
                <div class="w-14 h-8 bg-gray-100 absolute top-[1px] left-[1px] z-10"></div>
                <div id="export"></div>
            </div>
        </div>
    </div>
</div>
</div>
