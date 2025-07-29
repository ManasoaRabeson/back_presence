@php
    $idCustomer ??= '';

@endphp
<div class="offcanvas offcanvas-end !w-[45em]" tabindex="-1" id="offcanvasProfilEtp" aria-labelledby="offcanvas">
    <div class="flex flex-col w-full">
        <div class="w-full px-4 py-2 inline-flex items-center justify-between bg-gray-100">
            <p class="text-lg text-gray-500 font-medium">Modifier le profil de l'Entreprise</p>
            <a data-bs-toggle="offcanvas" href="#offcanvasProfilEtp" role="button" aria-controls="offcanvas"
                class="w-10 h-10 rounded-md hover:text-inherit hover:bg-gray-200 duration-200 cursor-pointer flex items-center justify-center">
                <i class="fa-solid fa-xmark text-gray-500"></i>
            </a>
        </div>

        <div class="w-full overflow-x-auto p-4 overflow-y-auto h-[100vh] pb-3">
            <div class="flex flex-col gap-y-3">
                <div class="w-full grid grid-cols-9 gap-2">
                    <div class="grid col-span-3 w-full items-center justify-center">
                        <div class="w-full flex flex-col items-center gap-2">

                            <span class="customer_admin_logo_detail"> </span>

                            <label for="logoFileProfile" id='{{ $idCustomer }}'
                                class="rounded-md flex items-center gap-1 bg-white border justify-center px-2 py-1 hover:!bg-gray-100 duration-200 cursor-pointer"
                                title="Modifier le logo de l'entreprise">
                                <input type="file" name="logoFileProfile" id="logoFileProfile" class="hidden">

                                <i class="fa-solid fa-pen text-sm"></i>
                                Changer de profil
                            </label>

                        </div>
                    </div>

                    <div class="grid grid-cols-subgrid col-span-6">

                        <div class="flex flex-col gap-2">
                            <input type="hidden" id="idCustomerHiddenAdmin">
                            <div class="grid grid-cols-2 gap-3">
                                <x-input name="customer_admin_nif_edit" label="NIF" />
                                <x-input name="customer_admin_stat_edit" label="STAT" />
                            </div>
                            <x-input name="customer_admin_rcs_edit" label="RCS" />
                        </div>
                    </div>
                </div>

                <hr class="border-[1px] border-gray-400 mt-2">

                <div class="flex flex-col gap-2">
                    <input type="hidden" id="id_entreprise_hidden">

                    <label class="text-lg text-gray-600 font-medium">Informations de base</label>
                    <x-input name="customer_admin_name_edit" label="Raison sociale" />
                    <x-input name="customer_admin_slogan_edit" label="Slogan" />

                    <x-input type="textarea" name="customer_admin_description_edit" label="Descritption" />

                    <hr class="border-[1px] border-gray-400 mt-3">
                    <label class="text-lg text-gray-600 font-medium">Contact</label>
                    <div class="grid grid-cols-3 gap-3">
                        <x-input name="customer_admin_phone_edit" type="tel" label="Téléphone" />
                        {{-- <x-input name="" type="tel" label="Téléphone 2" />
                        <x-input name="" type="tel" label="Téléphone 3" /> --}}
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <x-input name="customer_admin_email_edit" type="email" label="Mail" />
                        <x-input name="customer_admin_site_web_edit" label="Site Web" />
                    </div>

                    <hr class="border-[1px] border-gray-400 mt-4">
                    <label class="text-lg text-gray-600 font-medium">Localisation</label>
                    <div class="grid grid-cols-2 gap-3">
                        <x-input name="customer_admin_lot_edit" label="Lot" />
                        <x-input name="customer_admin_qrt_edit" label="Quartier" />
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        {{-- <div class="flex flex-col w-full gap-1">
                            <label for="customer_admin_cp_edit" class="text-gray-600">Code Postal</label>
                            <input 
                                name="customer_admin_cp_edit" 
                                id="customer_admin_cp_edit" 
                                type="number" 
                                label="Code postal" 
                                oninput="updateCities()"
                                class="border-2 border-[#e2e8f0] rounded-md px-3 py-1 transition-all duration-200 focus:border-[#4c9aff] focus:ring-2 focus:ring-[#4c9aff] focus:outline-none w-full max-w-xs h-11"
                            />
                        </div>
                        <div class="flex flex-col w-full gap-1">
                            <label for="ville" class="text-gray-600">Ville</label>
                            <p for="" class="text-sm text-gray-400"></p>
                            <select name="ville" id="ville" class="bg-white password-toggle outline-none w-full bg-transparent pl-2 h-10 border-[1px] border-gray-200 rounded-md hover:border-purple-300 focus:border-purple-300 focus:ring-2 focus:ring-purple-100 duration-200 text-gray-400">
                                <option value="">Sélectionnez une ville</option>
                            </select>
                        </div> --}}
                        <div class="flex flex-col w-full gap-1">
                            <label for="ville" class="text-gray-600">Code postal et Ville</label>
                            <select name="etp_ville_edit" id="ville"
                                class="bg-white password-toggle outline-none w-full bg-transparent pl-2 h-12 border-[1px] border-slate-200 rounded-md hover:border-purple-300 focus:border-purple-300 focus:ring-2 focus:ring-purple-100 duration-200 text-gray-700">
                                <option value="">Sélectionnez une ville</option>
                            </select>
                        </div>   
                    </div>
                </div>
            </div>

            <div class="w-full inline-flex items-end justify-end pt-3 mb-16">
                <x-btn-ghost>
                    <a data-bs-toggle="offcanvas" href="#offcanvas" class="hover:text-inherit">
                        Annuler
                    </a>
                </x-btn-ghost>
                <x-btn-primary onclick="updateAdminCustomer()">Sauvegarder les modifications</x-btn-primary>
            </div>

        </div>
    </div>
</div>


{{-- MODAL --}}

<div class="modal fade" id="cropProfile" tabindex="-1" role="dialog" data-bs-backdrop="static"
    data-bs-keyboard="false" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content bg-white border-none justify-center gap-2 rounded-md h-[80vh]">
            <div class="p-3 inline-flex gap-3 justify-center">
                <h2 class="modal-title" id="modalLabel">Séléctionnez l'image à découper...</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="img-container">
                    <div class="row">
                        <div class="grid grid-cols-4">
                            <div class="grid col-span-2">
                                <img id="imageprofile" src="">
                            </div>
                            <div class="grid col-span-1">
                                <div class="previewProfile"></div>
                            </div>
                            {{-- <div class="preview"></div> --}}

                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                {{-- <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" id="croped" onclick="croped(sessionStorage.getItem('ID_CROP_IMG_FORM') ) "> Crop </button> --}}
                <x-btn-ghost>Annuler</x-btn-ghost>
                <x-btn-primary id="croped" onclick="croped(sessionStorage.getItem('ID_CROP_LOGO_ETP')) ">Recadrer
                    l'image</x-btn-primary>
            </div>
        </div>
    </div>
</div>
