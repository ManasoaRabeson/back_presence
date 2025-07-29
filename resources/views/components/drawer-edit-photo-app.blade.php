<div class="offcanvas offcanvas-end !w-[45em]" tabindex="-1" id="offcanvas" aria-labelledby="offcanvas">
    <div class="flex flex-col w-full">
        <div class="w-full px-4 py-2 inline-flex items-center justify-between bg-gray-100/50">
            <p class="text-lg text-gray-500 font-medium">Modifier votre photo de profil</p>
            <a data-bs-toggle="offcanvas" href="#offcanvas" role="button" aria-controls="offcanvas"
                class="w-10 h-10 rounded-md hover:text-inherit hover:bg-gray-200 duration-200 cursor-pointer flex items-center justify-center">
                <i class="fa-solid fa-xmark text-gray-500"></i>
            </a>
        </div>
        <div class="container">

            <!-- Formulaire de téléversement de photo -->
            <form id="upload-form" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Champ de téléchargement de fichier -->
                <input type="file" id="photo" name="photo" accept="image/*"
                    class="m-2 w-full flex flex-row justify-center items-center" required>
                <!-- Zone de prévisualisation de CropperJS -->
                <div id="cropper-container">
                    <img id="image" src="" alt="Photo à recadrer"
                        class="w-[640px] h-[480px] flex justify-center items-center">
                </div>
                <div class="flex flex-row justify-between items-center m-2">

                    <button type="button" id="preview-button"
                        class="flex justify-center items-center text-white bg-[#a462a4] py-1 px-4 rounded-md">Prévisualiser
                    </button>
                    <button type="button" id="save-cropped-image"
                        class="flex justify-center items-center text-white bg-[#a462a4] py-1 px-4 rounded-md">Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
