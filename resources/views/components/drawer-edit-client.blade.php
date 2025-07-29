@php
    $idEtp ??= '';
@endphp

<div class="offcanvas offcanvas-end !w-[45em]" tabindex="-1" id="offcanvas" aria-labelledby="offcanvas">
    <div class="flex flex-col w-full">
        <div class="inline-flex items-center justify-between w-full px-4 py-2 bg-gray-100">
            <p class="text-lg font-medium text-gray-500">Modifier un client</p>
            <a data-bs-toggle="offcanvas" href="#offcanvas" role="button" aria-controls="offcanvas"
                class="flex items-center justify-center w-10 h-10 duration-200 rounded-md cursor-pointer hover:text-inherit hover:bg-gray-200">
                <i class="text-gray-500 fa-solid fa-xmark"></i>
            </a>
        </div>

        <div class="w-full overflow-x-auto p-4 overflow-y-auto h-[100vh] pb-3">
            <div class="flex flex-col gap-y-3">
                <div class="grid w-full grid-cols-9 gap-2">
                    <div class="grid items-center justify-center w-full col-span-3">
                        <div class="flex flex-col items-center w-full gap-2">
                            <div
                                class="flex items-center justify-center h-24 text-3xl font-medium text-gray-500 uppercase bg-gray-100 etp_logo_detail w-44 rounded-xl">
                            </div>
                            <label for="logofileEtp" {{-- id={{ $idEtp }} --}}
                                class="rounded-md flex items-center gap-1 bg-white border justify-center px-2 py-1 hover:!bg-gray-100 duration-200 cursor-pointer"
                                title="Modifier le logo de l'entreprise">
                                <i class="text-sm fa-solid fa-pen"></i>
                                Changer de profil
                            </label>

                        </div>
                    </div>

                    <div class="grid col-span-6 gap-1 grid-cols-subgrid">
                        <div class="grid grid-cols-2 gap-3">
                            <x-input name="etp_nif_edit" label="NIF" />
                            <x-input name="etp_stat_edit" label="STAT" />
                        </div>
                        <x-input name="etp_rcs_edit" label="RCS" />
                    </div>
                </div>

                <hr class="border-[1px] border-gray-400 mt-2">

                <div class="flex flex-col gap-2">
                    <input type="hidden" id="id_entreprise_hidden">

                    <label class="text-lg font-medium text-gray-600">Informations de base</label>
                    <div class="grid grid-cols-2 gap-3">
                        <x-input name="etp_ref_name_edit" label="Nom du référent" readonly />
                        <x-input name="etp_ref_firstname_edit" label="Prénom du référent" readonly />
                    </div>
                    <x-input name="etp_ref_fonction_edit" label="Fonction" readonly />
                    <x-input name="etp_name_edit" label="Nom de l'entreprise" />
                    <div class="grid grid-cols-2 gap-3">
                        <x-input name="etp_email_edit" type="email" label="Mail" />
                        <x-input name="etp_phone_edit" type="tel" label="Téléphone" />
                    </div>

                    <hr class="border-[1px] border-gray-400 mt-4">
                    <label class="text-lg font-medium text-gray-600">Localisation</label>
                    <div class="grid grid-cols-2 gap-3">
                        <x-input name="etp_lot_edit" label="Lot" />
                        <x-input name="etp_qrt_edit" label="Quartier" />
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="flex flex-col w-full gap-1">
                            <label for="ville" class="text-gray-600">Code postal et Ville</label>
                            <select name="etp_ville_edit" id="ville"
                                class="bg-white password-toggle outline-none w-full bg-transparent pl-2 h-12 border-[1px] border-slate-200 rounded-md hover:border-purple-300 focus:border-purple-300 focus:ring-2 focus:ring-purple-100 duration-200 text-gray-700">
                                <option value="">Sélectionnez une ville</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3" id="container_select_type_edit">
                        <div class="flex flex-col w-full gap-1">
                            <label for="ville" class="text-gray-600">Type d'entreprise</label>
                            <select name="etp_type_edit" id="etp-type-edit"
                                class="bg-white password-toggle outline-none w-full bg-transparent pl-2 h-12 border-[1px] border-slate-200 rounded-md hover:border-purple-300 focus:border-purple-300 focus:ring-2 focus:ring-purple-100 duration-200 text-gray-700">
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="inline-flex items-end justify-end w-full pt-3 mb-10 gap-2">
                <a data-bs-toggle="offcanvas" href="#offcanvas" class="hover:text-inherit btn">
                    Annuler
                </a>
                <x-btn-primary onclick="updateClient()">Sauvegarder les modifications</x-btn-primary>
            </div>
        </div>
    </div>
</div>
