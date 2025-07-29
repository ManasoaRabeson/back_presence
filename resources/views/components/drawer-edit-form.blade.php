<!-- component -->
<div class="offcanvas offcanvas-end !w-[45em]" tabindex="-1" id="offcanvas" aria-labelledby="offcanvas">
    <div class="flex flex-col w-full">
        <div class="w-full px-4 py-2 inline-flex items-center justify-between bg-gray-100">
            <p class="text-lg text-gray-500 font-medium">Modifier un formateur</p>
            <a data-bs-toggle="offcanvas" href="#offcanvas" role="button" aria-controls="offcanvas"
                class="w-10 h-10 rounded-md hover:text-inherit hover:bg-gray-200 duration-200 cursor-pointer flex items-center justify-center">
                <i class="fa-solid fa-xmark text-gray-500"></i>
            </a>
        </div>

        <div class="w-full overflow-x-auto p-4 overflow-y-auto h-[100vh] pb-3">
            <div class="flex flex-col gap-y-3">
                <div class="w-full grid grid-cols-9 gap-2">
                    <div class="grid col-span-3 w-full items-center justify-center">
                        <div class="w-full flex flex-col items-center gap-2">
                            <span class="form_photo_detail"></span>
                            <label for="logoFileForm"
                                class="rounded-md flex items-center gap-1 bg-white border justify-center px-2 py-1 hover:!bg-gray-100 duration-200 cursor-pointer"
                                title="Modifier la photo de profil">
                                <input type="file" name="logoFileForm" id="logoFileForm" class="hidden">
                                <i class="fa-solid fa-pen text-sm"></i>
                                Changer de profil
                            </label>
                        </div>
                    </div>
                    <div class="grid grid-cols-subgrid col-span-6">
                        <div class="flex flex-col gap-2">
                            <x-input name="form_name_edit" label="Nom" />
                            <x-input name="form_firstname_edit" label="Prénom" />
                        </div>
                    </div>
                </div>

                <hr class="border-[1px] border-gray-400 mt-2">

                <div class="flex flex-col gap-2">
                    <input type="hidden" id="id_formateur_hidden">

                    <div class="grid grid-cols-2 gap-3">
                        <x-input name="form_email_edit" type="email" label="Mail" />
                        <x-input name="form_phone_edit" type="tel" label="Téléphone" />
                    </div>

                    {{-- Adresse --}}
                    {{-- <div class="grid grid-cols-2 gap-3">
            <x-input name="form_lot_edit" label="Lot" />
            <x-input name="form_qrt_edit" label="Quartier" />
          </div> --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="flex flex-col gap-1 w-full">
                            <label for="ville" class="text-gray-600">Ville</label>
                            <p for="" class="text-sm text-gray-400"></p>
                            <select name="ville" id="ville"
                                class="bg-white password-toggle outline-none w-full bg-transparent pl-2 h-10 border-[1px] border-gray-200 rounded-md hover:border-purple-300 focus:border-purple-300 focus:ring-2 focus:ring-purple-100 duration-200 text-gray-400">
                                <option value="">Antananarivo</option>
                                <option value="">Toamasina</option>
                                <option value="">Mahajanga</option>
                                <option value="">Antsiranana</option>
                                <option value="">Fianarantsoa</option>
                                <option value="">Antsirabe</option>
                            </select>
                        </div>
                        <x-input name="form_cp_edit" label="Code postal" />
                    </div>
                </div>
            </div>
            <div class="w-full inline-flex items-end justify-end pt-3 mb-10 gap-2">
                <a data-bs-toggle="offcanvas" href="#offcanvas" class="hover:text-inherit btn">
                    Annuler
                </a>
                <x-btn-primary onclick="updateForm()">Sauvegarder les modifications</x-btn-primary>
            </div>
        </div>
    </div>
</div>
{{-- MODAL --}}

<div class="modal fade" id="cropform" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content bg-white border-none justify-center gap-2 rounded-md h-[80vh]">
            <div class="p-3 inline-flex gap-3 justify-center">
                <h2 class="modal-title" id="modalLabel">Séléctionnez l'image à découper...</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <input type="text" class="idApprVal">
            <div class="modal-body">
                <div class="img-container">
                    <div class="row">
                        <div class="grid grid-cols-4">
                            <div class="grid col-span-3">
                                <img id="imageform" src="" class="!h-full !w-auto">
                            </div>
                            <div class="grid col-span-1 justify-end">
                                <div class="previewForm"></div>
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
                <x-btn-primary id="croped" onclick="croped(sessionStorage.getItem('ID_CROP_IMG_FORM')) ">Recadrer
                    l'image</x-btn-primary>
            </div>
        </div>
    </div>
</div>
