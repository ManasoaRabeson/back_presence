@php
    $idDossier ??= '';
    $nomDossier ??= '';
@endphp
<div class="mb-[30px] flex flex-col gap-4 max-w-screen-xl mx-auto w-full h-[calc(100vh-30px)] mt-[20px] p-4">

    <style>
        .accordion-content {
            transition: max-height 0.3s ease-out;
            overflow: hidden;
            max-height: 0;
        }

        .accordion-content.open {
            max-height: none;
        }
    </style>
    <div id="projetDocument" class="p-4 mb-4 bg-gray-100 rounded-lg shadow-md">
        {{-- <button id="accordion-header" type="button"
            class="w-full flex items-center justify-between p-4 text-lg font-semibold text-gray-700 bg-gray-100 rounded-md shadow-sm">
            <div class="w-1 h-8 bg-purple-400 rounded"></div>
            <span>Gestion des projets par dossier</span>
            <i id="accordion-icon" class="fa fa-chevron-down transition-transform duration-300"></i>
        </button> --}}

        <div class="p-2 mt-2 rounded accordion-content open bg-gray-50">
            <div class="flex h-full overflow-y-auto">
                <!-- Contenu du premier bloc -->
                <div class="flex flex-col h-full items-start mr-4 w-1/2 border-r-[1px] border-gray-200">
                    <div class="flex items-center gap-3 px-4 py-2 rounded-lg shadow-sm bg-gray-50">
                        <div class="w-1 h-8 bg-red-400 rounded"></div>
                        <label class="text-lg font-semibold text-gray-700">Projets sans dossier</label>
                    </div>

                    <div class="h-full mt-2 bg-gray-50 w-full overflow-y-auto p-2">
                        <input id="document_search" placeholder="Chercher un projet"
                            onkeyup="documentSearch('document_search', 'get_all_projet')"
                            class="input input-bordered w-full bg-white" />
                    </div>

                    <div class="w-full h-full mt-2 overflow-y-auto bg-gray-50">
                        <p id="projectCount" class="px-2 py-1 text-gray-700"></p>

                        <ul id="get_all_projet"
                            class="relative flex flex-row flex-wrap items-start justify-start w-full gap-2 p-2 list-none rounded select-list">
                            <!-- Liste de projets sous forme de <li> -->
                        </ul>
                    </div>
                </div>
                <div class="flex flex-col items-start w-1/2 h-full border-gray-200">
                    <div class="flex items-center gap-3 px-4 py-2 rounded-lg shadow-sm bg-gray-50">
                        <div class="w-1 h-8 bg-green-400 rounded"></div>
                        <label class="text-lg font-semibold text-gray-700">Projets dans ce dossier</label>
                    </div>

                    <div class="h-full mt-2 bg-gray-50 w-full overflow-y-auto p-2">
                        <input id="document_search_dossier" placeholder="Chercher un projet"
                            onkeyup="documentSearch('document_search_dossier', 'get_all_projet_dossier')"
                            class="input input-bordered w-full bg-white" />
                    </div>

                    <div class="w-full h-full mt-2 overflow-y-auto bg-gray-50">
                        <p id="projectCountDossier" class="px-2 py-1 text-gray-700"></p>
                        <ul id="get_all_projet_dossier"
                            class="relative flex flex-row flex-wrap items-start justify-start w-full gap-2 p-2 list-none rounded select-list">
                            <!-- Liste de projets dans ce dossier ici -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="confirm-modal"
        class="fixed inset-0 z-50 flex items-center justify-center hidden bg-gray-900 bg-opacity-50">
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
