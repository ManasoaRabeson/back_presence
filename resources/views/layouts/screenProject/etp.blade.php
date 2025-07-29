<div id="screenProject" class="">
    <div id="modal"
        class="w-[65em] h-[42em] hidden overflow-hidden rounded-xl bg-white shadow-2xl z-50 border-[1px] border-gray-100">
        <div class="w-full h-full">
            <div class="flex items-center justify-between w-full p-3 bg-gray-100">
                <x-titre-modal class="text-xl font-semibold text-gray-700">Créer un nouveau projet
                    interne</x-titre-modal>
                <div class="inline-flex items-center gap-2">
                    <button
                        class="flex items-center justify-center w-8 h-8 duration-150 rounded-md cursor-pointer hover:bg-gray-100"
                        id="minimizeBtn">
                        <i class="text-gray-400 reduire text-md"></i>
                    </button>
                    <div
                        class="z-50 flex items-center justify-center w-8 h-8 duration-150 rounded-md cursor-pointer close hover:bg-gray-100">
                        <i class="text-gray-400 fa-solid fa-xmark text-md"></i>
                    </div>
                </div>
            </div>
            <div class="h-full p-3 mt-2 overflow-y-scroll modal-body">
                @include('ETP.projets.create')
            </div>
        </div>
    </div>
</div>

<div id="screenClient" class="">
    <div id="modalClient"
        class="w-[36em] hidden overflow-hidden rounded-xl bg-white shadow-2xl z-50 border-[1px] border-gray-100">
        <div class="w-full">
            <div class="flex items-center justify-between w-full p-3 bg-gray-100">
                <x-titre-modal class="text-xl font-semibold text-gray-700">Ajouter un centre de formation
                </x-titre-modal>
                <div class="inline-flex items-center gap-2">
                    <button
                        class="flex items-center justify-center w-8 h-8 duration-150 rounded-md cursor-pointer hover:bg-gray-100"
                        id="minimizeBtnClient">
                        <i class="text-gray-400 reduireClient text-md"></i>
                    </button>
                    <div
                        class="z-50 flex items-center justify-center w-8 h-8 duration-150 rounded-md cursor-pointer closeClient hover:bg-gray-100">
                        <i class="text-gray-400 fa-solid fa-xmark text-md"></i>
                    </div>
                </div>
            </div>
            <div class="p-3 mt-2 modal-body">
                @include('ETP.collaborations.create')
            </div>
        </div>
    </div>
</div>

<div id="screenCours" class="">
    <div id="modalCours"
        class="w-[40em] h-max hidden overflow-hidden rounded-xl bg-white shadow-2xl z-50 border-[1px] border-gray-100">
        <div class="w-full h-full">
            <div class="flex items-center justify-between w-full p-3 bg-gray-100">
                <x-titre-modal class="text-xl font-semibold text-gray-700">Ajouter un cours</x-titre-modal>
                <div class="inline-flex items-center gap-2">
                    <button
                        class="flex items-center justify-center w-8 h-8 duration-150 rounded-md cursor-pointer hover:bg-gray-100"
                        id="minimizeBtnCours">
                        <i class="text-gray-400 reduireCours text-md"></i>
                    </button>
                    <div
                        class="z-50 flex items-center justify-center w-8 h-8 duration-150 rounded-md cursor-pointer closeCours hover:bg-gray-100">
                        <i class="text-gray-400 fa-solid fa-xmark text-md"></i>
                    </div>
                </div>
            </div>
            <div class="h-full p-3 mt-2 modal-body">
                @include('ETP.moduleInternes.addCours')
            </div>
        </div>
    </div>
</div>

<div id="screenFormateur" class="">
    <div id="modalFormateurInterne"
        class="w-[36em] h-max hidden overflow-hidden rounded-xl bg-white shadow-2xl z-50 border-[1px] border-gray-100">
        <div class="w-full h-full">
            <div class="flex items-center justify-between w-full p-3 bg-gray-100">
                <x-titre-modal class="text-xl font-semibold text-gray-700">Ajouter un formateur
                    interne</x-titre-modal>
                <div class="inline-flex items-center gap-2">
                    <button
                        class="flex items-center justify-center w-8 h-8 duration-150 rounded-md cursor-pointer hover:bg-gray-100"
                        id="minimizeBtnFormateur">
                        <i class="text-gray-400 reduireFormateur text-md"></i>
                    </button>
                    <div
                        class="z-50 flex items-center justify-center w-8 h-8 duration-150 rounded-md cursor-pointer closeFormateur hover:bg-gray-100">
                        <i class="text-gray-400 fa-solid fa-xmark text-md"></i>
                    </div>
                </div>
            </div>
            <div class="h-full p-3 mt-2 modal-body">
                @include('ETP.formateurInternes.addFormateurInterne')
            </div>
        </div>
    </div>
</div>

<div id="screenEmploye" class="">
    <div id="modalEmploye"
        class="w-[36em] h-max hidden overflow-hidden rounded-xl bg-white shadow-2xl z-50 border-[1px] border-gray-100">
        <div class="w-full h-full">
            <div class="flex items-center justify-between w-full p-3 bg-gray-100">
                <x-titre-modal class="text-xl font-semibold text-gray-700">Ajouter un employé</x-titre-modal>
                <div class="inline-flex items-center gap-2">
                    <button
                        class="flex items-center justify-center w-8 h-8 duration-150 rounded-md cursor-pointer hover:bg-gray-100"
                        id="minimizeBtnEmploye">
                        <i class="text-gray-400 reduireEmploye text-md"></i>
                    </button>
                    <div
                        class="z-50 flex items-center justify-center w-8 h-8 duration-150 rounded-md cursor-pointer closeEmploye hover:bg-gray-100">
                        <i class="text-gray-400 fa-solid fa-xmark text-md"></i>
                    </div>
                </div>
            </div>
            <div class="h-full p-3 mt-2 modal-body">
                @include('ETP.employes.addEmploye')
            </div>
        </div>
    </div>
</div>

<div id="screenSalle" class="">
    <div id="modalSalle"
        class="w-[36em] h-max hidden overflow-hidden rounded-xl bg-white shadow-2xl z-50 border-[1px] border-gray-100">
        <div class="w-full h-full">
            <div class="flex items-center justify-between w-full p-3 bg-gray-100">
                <x-titre-modal class="text-xl font-semibold text-gray-700">Ajouter une salle</x-titre-modal>
                <div class="inline-flex items-center gap-2">
                    <button
                        class="flex items-center justify-center w-8 h-8 duration-150 rounded-md cursor-pointer hover:bg-gray-100"
                        id="minimizeBtnSalle">
                        <i class="text-gray-400 reduireSalle text-md"></i>
                    </button>
                    <div
                        class="z-50 flex items-center justify-center w-8 h-8 duration-150 rounded-md cursor-pointer closeSalle hover:bg-gray-100">
                        <i class="text-gray-400 fa-solid fa-xmark text-md"></i>
                    </div>
                </div>
            </div>
            <div class="h-full p-3 mt-2 modal-body">
                @include('ETP.salles.addSalle')
            </div>
        </div>
    </div>
</div>

<div id="screenReferent" class="">
    <div id="modalReferent"
        class="w-[36em] h-max hidden overflow-hidden rounded-xl bg-white shadow-2xl z-50 border-[1px] border-gray-100">
        <div class="w-full h-full">
            <div class="flex items-center justify-between w-full p-3 bg-gray-100">
                <x-titre-modal class="text-xl font-semibold text-gray-700">Ajouter un référent</x-titre-modal>
                <div class="inline-flex items-center gap-2">
                    <button
                        class="flex items-center justify-center w-8 h-8 duration-150 rounded-md cursor-pointer hover:bg-gray-100"
                        id="minimizeBtnReferent">
                        <i class="text-gray-400 reduireReferent text-md"></i>
                    </button>
                    <div
                        class="z-50 flex items-center justify-center w-8 h-8 duration-150 rounded-md cursor-pointer closeReferent hover:bg-gray-100">
                        <i class="text-gray-400 fa-solid fa-xmark text-md"></i>
                    </div>
                </div>
            </div>
            <div class="h-full p-3 mt-2 modal-body">
                @include('ETP.employeEtps.addReferent')
            </div>
        </div>
    </div>
</div>
