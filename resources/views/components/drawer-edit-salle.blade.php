<div class="offcanvas offcanvas-end !w-[45em]" tabindex="-1" id="offcanvas" aria-labelledby="offcanvas">
    <div class="flex flex-col w-full">
        <div class="w-full px-4 py-2 inline-flex items-center justify-between bg-gray-100">
            <p class="text-lg text-gray-500 font-medium">Modifier le lieu et salle</p>
            <a data-bs-toggle="offcanvas" href="#offcanvas" role="button" aria-controls="offcanvas"
                class="w-10 h-10 rounded-md hover:text-inherit hover:bg-gray-200 duration-200 cursor-pointer flex items-center justify-center">
                <i class="fa-solid fa-xmark text-gray-500"></i>
            </a>
        </div>

        <form action="">
            <div class="w-full overflow-x-auto p-4 overflow-y-auto h-[100vh] pb-3">
                <div class="flex flex-col gap-y-3">
                    <div class="w-full grid grid-cols-7 gap-2">
                        <div class="grid col-span-3">
                            <div class="flex flex-col">
                                <div class="salle_name_edit text-xl text-gray-700 font-semibold"></div>
                                <div class="salle_text-gray-500">
                                    <span class="salle_quartier_edit"></span> -
                                    <span class="salle_ville_edit"></span>
                                    <span class="salle_code_postal_edit"></span>
                                </div>
                                <div class="salle_image_edit">
                                    {{-- <img src="res.salle.salle_image" alt="Image de la salle" class="w-full h-auto"> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="border-[1px] border-gray-400 my-2">

                    <div class="flex flex-col gap-2">


                        <input type="hidden" id="idSalleHiddenEdit">
                        <x-input name="sl_image_edit" accept="image/*" label="Image" type="file" />
                        <x-input name="sl_name_edit" label="Lieu" />
                        <x-input name="sl_rue_edit" label="Nom de la salle" />
                        <x-input name="sl_quartier_edit" label="Quartier" />
                        <div class="flex flex-col gap-1 w-full">
                            <label for="sl_ville_edit" class="text-sm text-gray-500">Ville</label>
                            <select name="" id="sl_ville_edit"
                                class="bg-white password-toggle outline-none w-full bg-transparent pl-2 h-10 border-[1px] border-gray-200 rounded-md hover:border-purple-300 focus:border-purple-300 focus:ring-2 focus:ring-purple-100 duration-200 text-gray-400">
                            </select>
                        </div>
                        <x-input name="sl_code_postal_edit" type="number" label="Code postal" />
                    </div>
                </div>
                <div class="w-full inline-flex items-end justify-end pt-3 mb-10">
                    <x-btn-ghost>
                        <a data-bs-toggle="offcanvas" href="#offcanvas" role="button" class="hover:text-inherit">
                            Annuler
                        </a>
                    </x-btn-ghost>
                    <x-btn-primary type="button" onclick="updateSalle()">Sauvegarder les modifications</x-btn-primary>
                </div>
            </div>
        </form>
    </div>
</div>
