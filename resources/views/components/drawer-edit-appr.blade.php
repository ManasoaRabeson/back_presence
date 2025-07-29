<!-- component -->
@php
    $idAppr ??= '';
    $nameAppr ??= '';
    $firstnameAppr ??= '';
    $emailAppr ??= '';
    $etpName ??= '';
    $photoAppr ??= '';
    $phoneAppr ??= '';
    $matriculeAppr ??= '';
@endphp
<div class="offcanvas offcanvas-end !w-[45em]" tabindex="-1" id="offcanvasApprContent"
    aria-labelledby="offcanvasApprContent">
    <div class="flex flex-col w-full">
        <div class="inline-flex items-center justify-between w-full px-4 py-2 bg-gray-100">
            <p class="text-lg font-medium text-gray-500">Modifier un apprenant</p>
            <a data-bs-toggle="offcanvas" href="#offcanvasApprContent" role="button" aria-controls="offcanvasApprContent"
                class="flex items-center justify-center w-10 h-10 duration-200 rounded-md cursor-pointer hover:text-inherit hover:bg-gray-200">
                <i class="text-gray-500 fa-solid fa-xmark"></i>
            </a>
        </div>

        <div class="w-full overflow-x-auto p-4 overflow-y-auto h-[100vh] pb-3">
            <div class="flex flex-col gap-y-3">
                <div class="grid w-full grid-cols-9">
                    <div class="grid items-center justify-center w-full col-span-3">
                        <div class="flex flex-col items-center w-full gap-2">
                            <span class="emp_photo_detail"></span>
                            <input type="text" id="drawer-toggle-crops" class="relative hidden sr-only peer">

                            <label for="logofile-{{ $idAppr }}"
                                class="rounded-md flex items-center gap-1 bg-white border justify-center px-2 py-1 hover:!bg-gray-100 duration-200 cursor-pointer">
                                <i class="text-sm fa-solid fa-pen"></i>
                                Changer de profil
                            </label>
                        </div>
                    </div>
                    <div class="grid col-span-6 grid-cols-subgrid">
                        <div class="flex flex-col gap-2">
                            <x-input name="emp_name_edit" label="Nom" />
                            <x-input name="emp_firstname_edit" label="Prénom" />
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <input type="hidden" id="id_apprenant_hidden">
                    <x-input name="emp_matricule_edit" type="text" label="Matricule" />
                    <div class="grid grid-cols-2 gap-3">
                        <x-input name="emp_email_edit" type="email" label="Mail" />
                        <x-input name="emp_phone_edit" type="tel" label="Téléphone" />
                    </div>
                    <div class="flex flex-col w-full gap-1">
                        <label for="idEntrepise_edit"
                            class="text-gray-500 text-base after:content-['*'] after:ml-0.5 after:text-red-500">Entreprise</label>
                        <select id="idEntrepise_edit_{{ $idAppr }}"
                            class="outline-none w-full text-gray-400 bg-transparent pl-2 h-10 border-[1px] border-gray-200 rounded-md hover:border-purple-300 focus:border-purple-300 focus:ring-2 focus:ring-purple-100 duration-200">
                        </select>
                    </div>
                    {{-- <x-input name="emp_fonction_edit" label="Fonction" /> --}}

                    <div class="grid grid-cols-2 gap-3">
                        <x-input name="emp_lot_edit" label="Lot" />
                        <x-input name="emp_qrt_edit" label="Quartier" />
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="flex flex-col w-full gap-1">
                            <label for="ville" class="text-gray-600">Ville</label>
                            <p for="" class="text-sm text-gray-400"></p>
                            <select name="ville" id="idVille_edit_{{ $idAppr }}"
                                class="bg-white password-toggle outline-none w-full bg-transparent pl-2 h-10 border-[1px] border-gray-200 rounded-md hover:border-purple-300 focus:border-purple-300 focus:ring-2 focus:ring-purple-100 duration-200 text-gray-400">
                            </select>
                        </div>
                        <x-input name="emp_cp_edit" type="number" label="Code postal" />
                    </div>
                </div>
            </div>

            <div class="inline-flex items-end justify-end w-full pt-3 mb-10 gap-2">
                <a data-bs-toggle="offcanvas" href="#offcanvasApprContent" class="hover:text-inherit btn">
                    Annuler
                </a>
                <x-btn-primary onclick="updateApprenant()">Sauvegarder les modifications</x-btn-primary>
            </div>
        </div>
    </div>
</div>
