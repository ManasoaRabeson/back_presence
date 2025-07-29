@php
    $id ??= '';
    $ref ??= '';
    $titre ??= '';
    $description ??= '';
    $nbPlace ??= null;
    $projectType ??= null;
@endphp

<div class="offcanvas offcanvas-end !w-[40em]" tabindex="-1" id="offcanvasGeneral" aria-labelledby="offcanvasGeneral">
    <div class="flex flex-col w-full">
        <div class="inline-flex items-center justify-between w-full px-4 py-2 bg-gray-100">
            <p class="text-lg font-medium text-gray-500">Modifier les informations générales</p>
            <a data-bs-toggle="offcanvas" href="#offcanvasGeneral"
                class="flex items-center justify-center w-10 h-10 duration-200 rounded-md cursor-pointer hover:bg-gray-200">
                <i class="text-gray-500 fa-solid fa-xmark"></i>
            </a>
        </div>

        <div class="w-full overflow-x-auto p-4 h-[100vh] pb-3">
            <div class="flex flex-col gap-4 h-full w-full min-w-[400px] overflow-x-scroll overflow-y-auto">
                <input type="hidden" value="{{ $id }}">
                <input type="hidden" value="{{ $projectType }}" id="project_type" value="{{ $projectType }}">

                <x-input name="project_reference_edit" type="text" label="Référence du projet"
                    value="{{ $ref }}" />
                <x-input name="project_title_edit" type="text" label="Titre du projet" value="{{ $titre }}" />
                @if ($projectType == 'Inter')
                    <x-input name="project_nbplace_edit" type="number" label="Nombre de place"
                        value="{{ $nbPlace }}" />
                @endif
                <x-input name="project_description_edit" type="textarea" label="Description"
                    value="{{ $description }}" />

                <div class="flex grid flex-col w-full grid-cols-2 gap-4">
                    <div class="grid col-span-2 grid-cols-subgrid">
                        <label class="w-full">
                            <div class="label">
                                <span class="label-text">Choisir la formation</span>
                            </div>
                            <select id="changeModuleSelect" class="w-full select select-bordered">
                            </select>
                        </label>
                    </div>
                    <div class="grid grid-cols-2 col-span-2 gap-2">
                        <div class="grid col-span-1">
                            <label class="w-full">
                                <div class="label">
                                    <span class="label-text">Début</span>
                                </div>
                                <input type="date" class="w-full input date_deb_input input-bordered" />
                            </label>
                        </div>
                        <div class="grid col-span-1">
                            <label class="w-full">
                                <div class="label">
                                    <span class="label-text">Echéance</span>
                                </div>
                                <input type="date" class="w-full input date_fin_input input-bordered" />
                            </label>
                        </div>
                    </div>

                    <div class="grid col-span-2 grid-cols-subgrid">
                        <label class="w-full">
                            <div class="label">
                                <span class="label-text">Modalité</span>
                            </div>
                            <select class="w-full select project_idModalite_detail select-bordered">
                            </select>
                        </label>
                    </div>
                </div>
                <div class="inline-flex items-end justify-end w-full pt-3 mb-14">
                    <x-btn-ghost>
                        <a data-bs-toggle="offcanvas" href="#offcanvasGeneral" class="hover:text-inherit">
                            Annuler
                        </a>
                    </x-btn-ghost>
                    <x-btn-primary onclick="updateProjet({{ $id }})">
                        Sauvegarder les modifications
                    </x-btn-primary>
                </div>
            </div>
        </div>
    </div>
</div>
