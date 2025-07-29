@php
    $idDossier ??= '';
    $nomDossier ??= '';
@endphp
<div class="mb-[30px] flex flex-col gap-4 max-w-screen-xl mx-auto w-full h-[calc(100vh-30px)]">
    <style>
        .accordion-content {
            transition: max-height 0.3s ease-out;
            overflow: hidden;
            max-height: 0;
        }

        #accordion-content {
            max-height: none;
        }
    </style>

    <div id="confirm-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-gray-900 bg-opacity-50">
        <div class="w-1/3 max-w-sm p-6 bg-white rounded-lg shadow-lg">
            <h2 class="mb-4 text-lg font-semibold">
                <i
                    class="text-transparent fa-solid fa-triangle-exclamation bg-clip-text bg-gradient-to-r from-red-500 via-red-600 to-red-700"></i>
                Confirmation
            </h2>
            <p class="mb-4">Êtes-vous sûr de vouloir supprimer ce document définitivement ?</p>
            <div class="flex justify-end gap-4">
                <button id="confirm-delete"
                    class="px-4 py-2 text-white rounded-md bg-gradient-to-r from-red-500 via-red-600 to-red-700 hover:bg-gradient-to-br hover:bg-red-600">Supprimer</button>
                <button id="cancel-delete"
                    class="px-4 py-2 text-gray-700 bg-gray-300 rounded-md hover:bg-gray-400">Annuler</button>
            </div>
        </div>
    </div>

    <div id="ajoutDocument" class="p-2 bg-white rounded-lg shadow-md">
        <div id="accordion-content" class="overflow-hidden transition-max-height duration-500 ease-in-out max-h-0">
            <form id="document-form" method="POST" enctype="multipart/form-data"
                class="p-6 flex flex-col gap-6 bg-white rounded-lg shadow-md">
                @csrf
                <div id="section-documents" class="relative flex flex-col gap-2">
                    <label for="section-radio" class="text-lg font-semibold text-gray-700">Section du Document</label>
                    <div id="section-radio-group" class="grid grid-cols-3 gap-4">
                    </div>
                </div>
                <div id="type-documents" class="relative flex flex-col gap-2 p-2 bg-purple-100" style="display: none;">
                    <label for="type-radio" class="text-lg font-semibold text-gray-700 mb-2">Type de Document</label>
                    <div id="type-radio-group" class="grid grid-cols-3 gap-4">
                    </div>
                </div>
                <div class="relative flex flex-col gap-2 p-2">
                    <label for="file-input" class="text-lg text-base text-gray-700">Fichier <span
                            class="text-gray-500">(pdf, txt, xls, xlsx, csv, ppt, pptx)</span> </label>
                    <input type="file" name="myFile" id="file-input"
                        accept=".pdf, .txt, .ppt, .pptx, .xls, .xlsx, .csv"
                        class="w-full p-3 border border-gray-300 rounded-md form-control">
                </div>
                <div class="relative flex flex-col gap-2 p-2">
                    <label for="title-input" class="text-lg font-semibold text-gray-700">Titre du Document</label>
                    <div class="relative">
                        <input type="text" name="title" id="title-input" placeholder="Titre du document"
                            class="w-full p-3 pr-10 border border-gray-300 rounded-md form-control">
                    </div>
                </div>

                <div>
                    <input type="submit" value="Ajouter le Document"
                        class="w-full px-5 py-3 font-semibold text-white transition rounded-md bg-gradient-to-r from-[#924792] to-[#b36fb3] 
              hover:from-[#b36fb3] hover:to-[#924792]">
                </div>
            </form>
        </div>


    </div>


    <div class="h-4 text-white">.</div>

    <div id="edit-modal-document"
        class="fixed inset-0 flex items-center justify-center hidden bg-gray-900 bg-opacity-50">
        <div class="p-6 bg-white rounded-lg w-1/2">
            <h2 class="mb-4 text-xl font-semibold">Modifier le document</h2>

            <div id="edit-section-documents" class="relative flex flex-col gap-2 mb-2">
                <label for="edit-section-radio" class="text-lg font-semibold text-gray-700">Section du
                    Document</label>
                <div id="edit-section-radio-group" class="grid grid-cols-3 gap-4"></div>
            </div>

            <div id="edit-type-documents" class="relative flex flex-col gap-2 mb-2" style="display: none;">
                <label for="edit-type-radio" class="text-lg font-semibold text-gray-700">Type de Document</label>
                <div id="edit-type-radio-group" class="grid grid-cols-3 gap-4"></div>
            </div>

            <input type="text" id="edit-file-title" class="w-full p-2 mb-4 mt-4 border rounded-lg"
                placeholder="Titre du fichier">
            <div class="flex justify-end space-x-2">
                <button id="save-edit"
                    class="px-5 py-2.5 text-white font-bold rounded-md bg-gradient-to-r from-purple-500 via-purple-600 to-purple-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-purple-300 text-sm">Sauvegarder</button>
                <button id="cancel-edit"
                    class="px-4 py-2 text-white bg-gray-500 rounded-lg hover:bg-gray-700">Annuler</button>
            </div>
        </div>
    </div>
</div>
