<!-- component -->
@php
    $idAppr ??= '';
@endphp
<div class="offcanvas offcanvas-end !w-[45em]" tabindex="-1" id="offcanvasParticulierContent"
    aria-labelledby="offcanvasParticulierContent">
    <div class="flex flex-col w-full">
        <div class="inline-flex items-center justify-between w-full px-4 py-2 bg-gray-100">
            <p class="text-lg font-medium text-gray-500">Modifier un particulier</p>
            <a data-bs-toggle="offcanvas" href="#offcanvasParticulierContent" role="button"
                aria-controls="offcanvasParticulierContent"
                class="flex items-center justify-center w-10 h-10 duration-200 rounded-md cursor-pointer hover:text-inherit hover:bg-gray-200">
                <i class="text-gray-500 fa-solid fa-xmark"></i>
            </a>
        </div>

        <div class="w-full overflow-x-auto p-4 overflow-y-auto h-[100vh] pb-3">
            <div class="my-3">
                <span class="main_loading_part"></span>
                <form class="">
                    <div class="flex flex-col gap-3">
                        <div class="flex flex-col justify-start w-full gap-3">
                            <input type="hidden" id="id_particulier_hidden">
                            <div class="w-full grid grid-cols-9 gap-2">
                                <div class="grid col-span-3 w-full items-center justify-center">
                                    <div class="w-full flex flex-col items-center gap-2">
                                        <span class="main_particulier_photo_detail"></span>

                                        <label for="logoFilePart" id="{{ $idAppr }}"
                                            class="rounded-md flex items-center gap-1 bg-white border justify-center px-2 py-1 hover:!bg-gray-100 duration-200 cursor-pointer"
                                            title="Modifier la photo de profil">
                                            <input type="file" name="logoFilePart" id="logoFilePart" class="hidden">
                                            <i class="fa-solid fa-pen text-sm"></i>
                                            Changer de profil
                                        </label>
                                    </div>
                                </div>
                                <div class="grid grid-cols-subgrid col-span-6">
                                    <div class="flex flex-col gap-2">
                                        <x-input required="true" type="text" name="part_name" label="Nom" />
                                        <div id="error_main_part_name" class="text-sm text-red-500"></div>
                                        <x-input type="text" name="part_firstname" label="Prénom(s)" />
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col w-full gap-1">
                                <x-input type="number" name="part_cin" label="CIN" />
                                <div id="error_main_part_cin" class="text-sm text-red-500"></div>
                            </div>

                            <div class="flex flex-col w-full gap-1">
                                <x-input type="email" name="part_email" disabled="true" label="E-mail" />
                                <div id="error_main_part_email" class="text-sm text-red-500"></div>
                            </div>
                            <div class="flex flex-col w-full gap-1">
                                <x-input type="text" name="part_phone" label="Phone" />
                                <div id="error_main_part_phone" class="text-sm text-red-500"></div>
                            </div>
                        </div>
                    </div>
                    <div class="inline-flex items-end justify-end w-full pt-3 mb-10 gap-2">
                        <a data-bs-toggle="offcanvas" href="#offcanvasParticulierContent"
                            class="hover:text-inherit btn">
                            Annuler
                        </a>
                        <x-btn-primary onclick="updateParticulier()">Sauvegarder les modifications</x-btn-primary>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

{{-- MODAL --}}

<div class="modal fade" id="cropAppr" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="modalLabel" aria-hidden="true">
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
                                <img id="imageappr" src="">
                            </div>
                            <div class="grid col-span-1">
                                <div class="previewAppr"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <x-btn-ghost>Annuler</x-btn-ghost>
                <x-btn-primary id="croped" onclick="croped({{ $idAppr }}) ">Recadrer
                    l'image</x-btn-primary>
            </div>
        </div>
    </div>
</div>
{{-- sessionStorage.getItem('ID_CROP_IMG_REF') --}}
