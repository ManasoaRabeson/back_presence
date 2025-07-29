{{-- <div class="offcanvas offcanvas-end !w-[45em]" tabindex="-1" id="offcanvasImage" aria-labelledby="offcanvas">
    <div class="flex flex-col w-full">
        <div class="w-full px-4 py-2 inline-flex items-center justify-between bg-gray-100">
            <p class="text-lg text-gray-500 font-medium">Menu</p>
            <a data-bs-toggle="offcanvas" href="#offcanvasImage" role="button" aria-controls="offcanvas"
                class="w-10 h-10 rounded-md hover:text-inherit hover:bg-gray-200 duration-200 cursor-pointer flex items-center justify-center">
                <i class="fa-solid fa-xmark text-gray-500"></i>
            </a>
        </div>
        <div class="w-full overflow-x-auto p-4 overflow-y-auto h-[100vh] pb-3">
            <div class="flex flex-col gap-y-3">
                <form id="upload-form" method="POST" action="{{ route('photo.update.form', Auth::user()->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="photo" class="form-label">Modifier votre photo</label>
                        <input type="file" id="photo" name="photo" accept="image/*" class="form-control"
                            required>
                        <div id="cropper-container" class="mt-2">
                            <img id="image" src="" alt="Recadrez votre image"
                                class="w-[200px] h-[200px] flex justify-center items-center mt-2">
                        </div>
                        <div class="flex flex-col justify-center items-center">
                            <div class="flex flex-row gap-2 justify-center items-center">
                            </div>
                            <span class="w-full flex flex-row justify-between items-center m-2">
                                <button type="button" id="preview-button"
                                    class="flex justify-center items-center text-white bg-[#790953] py-1 px-4 rounded-md">Prévisualiser
                                </button>
                                <button type="button" id="save-cropped-image"
                                    class="flex justify-center items-center text-white bg-[#790953] py-1 px-4 rounded-md">Enregistrer
                                </button>
                            </span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> --}}

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
<div class="offcanvas offcanvas-end !w-[45em]" tabindex="-1" id="offcanvasImage" aria-labelledby="offcanvas">
    <div class="flex flex-col w-full">
        <div class="w-full px-4 py-2 inline-flex items-center justify-between bg-gray-100">
            <p class="text-lg text-gray-500 font-medium">Menu</p>
            <a data-bs-toggle="offcanvas" href="#offcanvasImage" role="button" aria-controls="offcanvas"
                class="w-10 h-10 rounded-md hover:text-inherit hover:bg-gray-200 duration-200 cursor-pointer flex items-center justify-center">
                <i class="fa-solid fa-xmark text-gray-500"></i>
            </a>
        </div>
        <div class="w-full overflow-x-auto p-4 overflow-y-auto h-[100vh] pb-3">
            <div class="flex flex-col gap-y-3">
                {{-- <form id="upload-form" method="POST" action="{{ route('photo.update.form', Auth::user()->id) }}"
                    enctype="multipart/form-data">
                    @csrf --}}
                <div class="mb-3">
                    <label for="photo" class="form-label">Modifier votre photo</label>
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
                {{-- </form> --}}
            </div>
        </div>
    </div>
</div>


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
            <div class="modal-body h-full">
                <div class="img-container h-full">
                    <div class="row h-full">
                        <div class="grid grid-cols-4 h-full">
                            <div class="grid col-span-3 h-full">
                                <img id="imageform" src="">
                            </div>
                            <div class="grid col-span-1">
                                <div class="previewForm"></div>
                            </div>
                            {{-- <div class="preview"></div> --}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <x-btn-ghost>Annuler</x-btn-ghost>
                <x-btn-primary id="croped" onclick="croped({{ Auth::user()->id }}) ">Recadrer
                    l'image</x-btn-primary>
            </div>
        </div>
    </div>
</div>
