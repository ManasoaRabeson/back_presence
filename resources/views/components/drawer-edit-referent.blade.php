@php
    $idRef ??= '';
    //dd($idRef);
@endphp
<div class="offcanvas offcanvas-end !w-[45em]" tabindex="-1" id="offcanvas" aria-labelledby="offcanvas">
    <div class="flex flex-col w-full">
        <div class="inline-flex items-center justify-between w-full px-4 py-2 bg-gray-100">
            <p class="text-lg font-medium text-gray-500">Modifier un référent</p>
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
                            <span class="main_referent_photo_detail"></span>

                            <label for="logoFileRef" id="{{ $idRef }}"
                                class="rounded-md flex items-center gap-1 bg-white border justify-center px-2 py-1 hover:!bg-gray-100 duration-200 cursor-pointer"
                                title="Modifier la photo de profil">
                                <input type="file" name="logoFileRef" id="logoFileRef" class="hidden">
                                <i class="text-sm fa-solid fa-pen"></i>
                                Changer de profil
                            </label>
                        </div>
                    </div>
                    <div class="grid col-span-6 grid-cols-subgrid">
                        <div class="flex flex-col gap-2">
                            <x-input required="true" name="main_ref_name_edit" label="Nom" />
                            <x-input name="main_ref_firstname_edit" label="Prénom" />
                        </div>
                    </div>
                </div>

                <hr class="border-[1px] border-gray-400 my-2">

                <div class="flex flex-col gap-2">
                    <input type="hidden" value="{{ $idRef }}" class="main_ref_id_edit">
                    <x-input name="main_ref_matricule_edit" label="Matricule" />

                    <div class="grid grid-cols-2 gap-3">
                        <x-input required="true" name="main_ref_email_edit" type="mail" label="Mail" />
                        <x-input name="main_ref_phone_edit" type="tel" label="Téléphone" />
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="flex flex-col w-full gap-1">
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
                        <x-input name="main_ref_addrcp_edit" label="Code postal" type="number" />
                    </div>
                </div>
            </div>
            <div class="inline-flex items-end justify-end w-full gap-2 pt-3 mb-10">
                <a data-bs-toggle="offcanvas" href="#offcanvas" class="hover:text-inherit btn">
                    Annuler
                </a>
                <x-btn-primary onclick="mainUpdateReferent()">Sauvegarder les modifications</x-btn-primary>
            </div>
        </div>
    </div>
</div>

{{-- MODAL --}}

<div class="modal fade" id="cropRef" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="modalLabel" aria-hidden="true">
    <div class="w-full max-w-screen-xl modal-dialog du_modal-box" role="document">
        <div class="relative justify-start h-full gap-2 bg-white border-none rounded-md modal-content">
            <div class="inline-flex justify-center gap-3 p-3">
                <h2 class="modal-title" id="modalLabel">Séléctionnez l'image à découper...</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="img-container">
                    <div class="row">
                        <div class="grid grid-cols-4">
                            <div class="grid col-span-3">
                                <img id="imageref" src="">
                            </div>
                            <div class="grid justify-end col-span-1">
                                <div class="previewRef"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <x-btn-ghost>Annuler</x-btn-ghost>
                <x-btn-primary id="croped" onclick="croped(sessionStorage.getItem('ID_CROP_IMG_REF')) ">Recadrer
                    l'image</x-btn-primary>
            </div>
        </div>
    </div>
</div>
{{-- sessionStorage.getItem('ID_CROP_IMG_REF') --}}
