@php
    $id ??= '';
    $screen ??= '';
@endphp

<div class="offcanvas offcanvas-end !w-[45em]" tabindex="-1" id="{{ $id }}"
    aria-labelledby="{{ $id }}">
    <div class="flex flex-col w-full">
        <div class="w-full px-4 py-2 inline-flex items-center justify-between bg-gray-100">
            <p class="text-lg text-gray-500 font-medium">Modifier un cours</p>
            <a data-bs-toggle="offcanvas" href="#{{ $id }}" role="button" aria-controls="{{ $id }}"
                class="w-10 h-10 rounded-md hover:text-inherit hover:bg-gray-200 duration-200 cursor-pointer flex items-center justify-center">
                <i class="fa-solid fa-xmark text-gray-500"></i>
            </a>
        </div>

        <div class="w-full overflow-x-auto px-4 py-2 overflow-y-auto h-[100vh] pb-3">
            <div class="flex flex-col gap-y-3">

                <div class="flex flex-col gap-2">
                    <input type="hidden" id="id_module_hidden" class="id_module_hidden_{{ $screen }}">
                    {{-- <div class="grid grid-cols-2 gap-3"> --}}
                    <x-input screen="{{ $screen }}" name="module_reference_edit" label="Référence" />
                    {{-- </div> --}}
                    <x-input screen="{{ $screen }}" name="module_name_edit" label="Intitulé de la formation" />
                    <x-input screen="{{ $screen }}" name="module_subtitle_edit"
                        label="Sous-intitulé de la formation (moins de 150 caractères)" />
                    <x-input screen="{{ $screen }}" name="module_description_edit" type="textarea"
                        label="Description" value="Description Test" />
                    <div class="flex flex-col gap-1 w-full">
                        <label for="domaine" class="text-sm text-gray-500">Domaine de formation</label>
                        <select name="" id="id_domaine_formation_edit"
                            class="id_domaine_formation_edit id_domaine_formation_edit_{{ $screen }} bg-white password-toggle outline-none w-full bg-transparent pl-2 h-10 border-[1px] border-gray-200 rounded-md hover:border-purple-300 focus:border-purple-300 focus:ring-2 focus:ring-purple-100 duration-200 text-gray-400">
                        </select>
                    </div>
                    <div class="flex flex-col gap-1 w-full">
                        <label for="domaine" class="text-sm text-gray-500">Niveau</label>
                        <select name="" id="id_module_level_edit_{{ $screen }}"
                            class="bg-white password-toggle outline-none w-full bg-transparent pl-2 h-10 border-[1px] border-gray-200 rounded-md hover:border-purple-300 focus:border-purple-300 focus:ring-2 focus:ring-purple-100 duration-200 text-gray-400">
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <x-input screen="{{ $screen }}" name="module_price_edit" type="number"
                            label="Prix Individuel" />
                        <x-input screen="{{ $screen }}" name="module_prix_groupe_edit" type="number"
                            label="Prix Groupe" />
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <x-input screen="{{ $screen }}" name="module_dureeH_edit" type="number"
                            label="Durée en heures" />
                        <x-input screen="{{ $screen }}" name="module_dureeJ_edit" type="number"
                            label="Durée en jours" />
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <x-input screen="{{ $screen }}" name="module_min_appr_edit" type="number"
                            label="Apprenant minimum" />
                        <x-input screen="{{ $screen }}" name="module_max_appr_edit" type="number"
                            label="Apprenant maximum" />
                    </div>
                </div>
            </div>
            <div class="w-full inline-flex items-end justify-end pt-3 mb-10 gap-2">
                <a data-bs-toggle="offcanvas" href="#offcanvas" role="button" class="hover:text-inherit btn">
                    Annuler
                </a>
                <x-btn-primary onclick="updateModule('{{ $screen }}')">Sauvegarder les
                    modifications</x-btn-primary>
            </div>
        </div>
    </div>
</div>
