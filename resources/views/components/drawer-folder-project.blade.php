<div class="offcanvas offcanvas-end !w-[80em]" tabindex="-1" id="offcanvasDossier" aria-labelledby="offcanvasDossier">
    <div class="flex flex-col w-full">
        <div class="w-full px-4 py-2 inline-flex items-center justify-between bg-gray-100">
            <p class="text-lg text-gray-500 font-medium">Choisir un dossier</p>
            <a data-bs-toggle="offcanvas" href="#offcanvasDossier"
                class="w-10 h-10 rounded-md hover:bg-gray-200 duration-200 cursor-pointer flex items-center justify-center">
                <i class="fa-solid fa-xmark text-gray-500"></i>
            </a>
        </div>
        <div class="w-full grid grid-cols-2 gap-3 justify-start">
            <div class="grid col-span-2 h-max">
                <button onclick="addRowDossier('fileTableInter')"
                    class="btn btn-sm btn-outline btn-primary w-max btn_fileTableInter">
                    <i class="fa-solid fa-folder-plus"></i>
                    Nouveau dossier
                </button>
            </div>
            <div class="grid col-span-1 h-[37rem] overflow-y-auto">
                <table class="table fileTableInter fileTable bg-white h-max">
                    <thead>
                        <tr class="bg-gray-100">
                            <th>
                                Nom du dossier
                            </th>
                            <th class="text-right">
                                Document
                            </th>
                            <th class="text-right">
                                Projet
                            </th>
                            <th>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

            <div class="grid col-span-1 h-[37rem] overflow-y-auto">
                <table class="table bg-white h-max">
                    <thead>
                        <tr class="bg-gray-100">
                            <th>
                                Nom du dossier
                            </th>
                            <th class="text-right">
                                Document
                            </th>
                            <th class="text-right">
                                Projet
                            </th>
                            <th>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="fileTableSelected">
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
