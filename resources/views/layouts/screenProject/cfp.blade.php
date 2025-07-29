<div id="screenClient" class="">
    <div id="modalClient"
        class="w-[36em] hidden overflow-hidden rounded-xl bg-white shadow-2xl z-50 border-[1px] border-gray-100">
        <div class="w-full">
            <div class="flex items-center justify-between w-full p-3 bg-gray-100">
                <x-titre-modal class="text-xl font-semibold text-gray-700">Ajouter un client</x-titre-modal>
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
                @include('CFP.collaborations.create')
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
                @include('CFP.modules.addCours')
            </div>
        </div>
    </div>
</div>

<div id="screenFormateur" class="">
    <div id="modalFormateur"
        class="w-[36em] h-max hidden overflow-hidden rounded-xl bg-white shadow-2xl z-50 border-[1px] border-gray-100">
        <div class="w-full h-full">
            <div class="flex items-center justify-between w-full p-3 bg-gray-100">
                <x-titre-modal class="text-xl font-semibold text-gray-700">Ajouter un formateur</x-titre-modal>
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
                @include('CFP.formateurs.addFormateur')
            </div>
        </div>
    </div>
</div>

<div id="screenApprenant" class="">
    <div id="modalApprenant"
        class="w-[36em] h-max hidden overflow-hidden rounded-xl bg-white shadow-2xl z-50 border-[1px] border-gray-100">
        <div class="w-full h-full">
            <div class="flex items-center justify-between w-full p-3 bg-gray-100">
                <x-titre-modal class="text-xl font-semibold text-gray-700">Ajouter un apprenant</x-titre-modal>
                <div class="inline-flex items-center gap-2">
                    <button
                        class="flex items-center justify-center w-8 h-8 duration-150 rounded-md cursor-pointer hover:bg-gray-100"
                        id="minimizeBtnApprenant">
                        <i class="text-gray-400 reduireApprenant text-md"></i>
                    </button>
                    <div
                        class="z-50 flex items-center justify-center w-8 h-8 duration-150 rounded-md cursor-pointer closeApprenant hover:bg-gray-100">
                        <i class="text-gray-400 fa-solid fa-xmark text-md"></i>
                    </div>
                </div>
            </div>
            <div class="h-full p-3 mt-2 modal-body">
                @include('CFP.apprenants.addApprenant')
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
            <div class="p-3 mt-2 modal-body">
                @include('CFP.salles.addSalle')
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
                @include('CFP.employeCfps.addReferent')
            </div>
        </div>
    </div>
</div>

<div id="screenBankAccount" class="">
    <div id="modalBankAccount"
        class="w-[36em] h-max hidden overflow-hidden rounded-xl bg-white shadow-2xl z-50 border-[1px] border-gray-100">
        <div class="w-full h-full">
            <div class="flex items-center justify-between w-full p-3 bg-gray-100">
                <x-titre-modal class="text-xl font-semibold text-gray-700">Ajouter un compte
                    bancaire</x-titre-modal>
                <div class="inline-flex items-center gap-2">
                    <button
                        class="flex items-center justify-center w-8 h-8 duration-150 rounded-md cursor-pointer hover:bg-gray-100"
                        id="minimizeBtnBankAccount">
                        <i class="text-gray-400 reduireBankAccount text-md"></i>
                    </button>
                    <div
                        class="z-50 flex items-center justify-center w-8 h-8 duration-150 rounded-md cursor-pointer closeBankAccount hover:bg-gray-100">
                        <i class="text-gray-400 fa-solid fa-xmark text-md"></i>
                    </div>
                </div>
            </div>
            <div class="h-full p-3 mt-2 modal-body">
                @include('CFP.bankCard.addBankAccount')
            </div>
        </div>
    </div>
</div>

<div id="screenParticulier" class="">
    <div id="modalParticulier"
        class="w-[36em] h-max hidden overflow-hidden rounded-xl bg-white shadow-2xl z-50 border-[1px] border-gray-100">
        <div class="w-full h-full">
            <div class="flex items-center justify-between w-full p-3 bg-gray-100">
                <x-titre-modal class="text-xl font-semibold text-gray-700">Ajouter un particulier</x-titre-modal>
                <div class="inline-flex items-center gap-2">
                    <button
                        class="flex items-center justify-center w-8 h-8 duration-150 rounded-md cursor-pointer hover:bg-gray-100"
                        id="minimizeBtnParticulier">
                        <i class="text-gray-400 reduireParticulier text-md"></i>
                    </button>
                    <div
                        class="z-50 flex items-center justify-center w-8 h-8 duration-150 rounded-md cursor-pointer closeParticulier hover:bg-gray-100">
                        <i class="text-gray-400 fa-solid fa-xmark text-md"></i>
                    </div>
                </div>
            </div>
            <div class="h-full p-3 mt-2 modal-body">
                @include('Particuliers.create')
            </div>
        </div>
    </div>
</div>

<div id="screenSubContractor" class="">
    <div id="modalSubContractor"
        class="w-[36em] hidden overflow-hidden rounded-xl bg-white shadow-2xl z-50 border-[1px] border-gray-100">
        <div class="w-full">
            <div class="flex items-center justify-between w-full p-3 bg-gray-100">
                <x-titre-modal class="text-xl font-semibold text-gray-700">Ajouter un sous-traitant</x-titre-modal>
                <div class="inline-flex items-center gap-2">
                    <button
                        class="flex items-center justify-center w-8 h-8 duration-150 rounded-md cursor-pointer hover:bg-gray-100"
                        id="minimizeBtnSubContractor">
                        <i class="text-gray-400 reduireSubContractor text-md"></i>
                    </button>
                    <div
                        class="z-50 flex items-center justify-center w-8 h-8 duration-150 rounded-md cursor-pointer closeSubContractor hover:bg-gray-100">
                        <i class="text-gray-400 fa-solid fa-xmark text-md"></i>
                    </div>
                </div>
            </div>
            <div class="p-3 mt-2 modal-body">
                @include('CFP.subContractor.create')
            </div>
        </div>
    </div>
</div>
