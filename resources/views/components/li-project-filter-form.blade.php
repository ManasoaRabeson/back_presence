@php
    $id ??= '';
    $route ??= '#';
@endphp

<div class="profile_card grid w-full min-w-[550px] grid-cols-12 gap-x-6 p-2 border-[1px] border-gray-200 rounded-xl">
    {{-- Bloc 1 --}}
    <div class="grid w-full min-[350px]:col-span-12 md:col-span-2 grid-cols-subgrid">
        <div class="min-[350px]:hidden md:block">
            <div
                class="p_statut_{{ $id }} w-full h-full bg-gradient-to-br relative rounded-md overflow-hidden text-white p-3">
                <div
                    class="p_type_{{ $id }} px-2 py-1 text-white text-sm text-center w-36 absolute -left-10 top-3 -rotate-45 shadow-sm">
                </div>
                <div class="flex flex-col items-center justify-center h-full ml-12">
                    <span class="flex flex-col justify-center w-full">
                        <h5 class="p_date_year_{{ $id }} text-xl font-medium text-white">
                        </h5>
                        <div class="inline-flex items-end gap-2">
                            <h5 class="p_date_jour_debut_{{ $id }} text-4xl font-semibold text-white">

                            </h5>
                            <h5 class="p_date_jour_fin_{{ $id }} text-xl text-white">
                            </h5>
                        </div>
                        <div class="inline-flex items-end gap-4">
                            <h5 class="p_date_mois_debut_{{ $id }} text-xl font-semibold text-white">
                            </h5>
                            <h5 class="p_date_mois_fin_{{ $id }} text-lg text-white">
                            </h5>
                        </div>
                    </span>
                </div>
            </div>
        </div>

        <div class="grid w-full grid-cols-12 md:hidden gap-x-4">
            {{-- Date + Type --}}
            <div class="grid w-full h-full min-[350px]:col-span-4 grid-cols-subgrid">
                <div class="relative w-full h-full p-3 overflow-hidden text-white rounded-md bg-gradient-to-br">
                    <div
                        class="absolute px-2 py-1 text-sm text-center text-white -rotate-45 shadow-sm w-36 -left-10 top-3">
                        <p class="text-sm text-white"></p>
                    </div>
                    <div class="flex flex-col items-center justify-center h-full ml-12">
                        <span class="flex flex-col justify-center w-full">
                            <h5 class="text-xl font-medium text-white">
                            </h5>
                            <div class="inline-flex items-end gap-2">
                                <h5 class="text-4xl font-semibold text-white">
                                </h5>
                                <h5 class="text-xl text-white">
                                </h5>
                            </div>
                            <div class="inline-flex items-end gap-4">
                                <h5 class="text-xl font-semibold text-white">
                                </h5>
                                <h5 class="text-lg text-white">
                                </h5>
                            </div>
                        </span>
                    </div>
                </div>
            </div>

            {{-- Module + ETP + Sessions + Heures + Apprenants --}}
            <div class="grid w-full p-2 h-full min-[350px]:col-span-6 grid-cols-subgrid">
                <div class="grid w-full grid-cols-6">
                    <div class="p_module_{{ $id }} grid col-span-6 grid-cols-subgrid">
                        <h1 class="text-xl font-medium text-gray-600">
                        </h1>
                    </div>
                    <div class="grid col-span-6 grid-cols-subgrid">
                        <span class="p_etp_initial_{{ $id }} inline-flex items-center gap-x-3">
                        </span>
                    </div>
                    <div class="grid col-span-6 grid-cols-subgrid">
                        <div class="inline-flex items-center gap-4">
                            <div class="seance_count_{{ $id }}">
                                <p class="text-gray-600">
                                </p>
                                <span class="text-gray-400">Sessions</span>
                            </div>
                            <div class="">
                                <p class="text-gray-600 heure_count_{{ $id }}">
                                </p>
                            </div>
                            {{-- @isset($id)
                                <div class="inline-flex items-center gap-x-1">
                                    <p class="text-gray-600 part_count_{{ $id }}">
                                    </p>
                                    <span class="text-gray-400">Particuliers</span>
                                </div>
                            @endisset --}}
                            <div class="appr_count_{{ $id }}">
                                <p class="text-gray-600">
                                </p>
                                <span class="text-gray-400">Apprenants</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Menu + Rating --}}
            <div class="grid w-full p-2 h-full min-[350px]:col-span-2 grid-cols-subgrid">
                <div class="grid items-start justify-end h-full col-span-1">
                    <div class="btn-group h-max">
                        <button type="button" title="Cliquer pour afficher le menu"
                            class="w-8 h-8 bg-[#A462A4] rounded-md hover:bg-[#A462A4]/90 duration-150 cursor-pointer shadow-sm shadow-purple-500"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <span class=""><i class="text-white fa-solid fa-bars-staggered"></i></span>
                        </button>
                        <ul class="dropdown-menu">
                            <x-dropdown-item icontype="solid" icon="eye" label="Aperçu"
                                route="/projetsForm/{{ $id }}/detailForm" />
                        </ul>
                    </div>
                </div>
                <div class="grid items-end justify-end col-span-1">
                    <div class="inline-flex items-center justify-end gap-1">
                        <i class="text-sm text-gray-600 fa-solid fa-star"></i>
                        <p class="font-medium text-gray-500 p_note_{{ $id }}"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bloc 2 --}}
    <div class="grid w-full h-full min-[350px]:col-span-12 md:col-span-10 grid-cols-subgrid">
        <div class="min-[350px]:grid w-full grid-cols-12 md:hidden">
            <div class="grid col-span-12 grid-cols-subgrid">
                <hr class="border-[1px] w-full border-gray-200 my-2">
                {{-- Description --}}
                <div class="grid w-full grid-cols-6 pr-4">
                    <div class="grid col-span-5 grid-cols-subgrid">
                        <span class="text-gray-500 p_description_{{ $id }}">
                        </span>
                    </div>
                    <div class="grid justify-end col-span-1 p_restauration_{{ $id }}">
                    </div>
                </div>
            </div>
            <div class="grid w-full col-span-12 grid-cols-subgrid">
                <hr class="border-[1px] w-full border-gray-200 my-2">
                <div class="grid w-full grid-cols-3">
                    <div class="grid col-span-1">
                        <span data-bs-toggle="tooltip"
                            class="p_statut_string_{{ $id }} inline-flex items-center gap-2 px-2 py-1 text-sm text-white w-[90px] justify-center">
                        </span>
                    </div>
                    <div class="grid col-span-1">
                        <span
                            class="p_modalite_{{ $id }} inline-flex items-center gap-2 px-2 py-1 w-[90px] justify-center">
                        </span>
                    </div>

                    <div class="grid col-span-1">
                        <div class="inline-flex items-center">
                            <div class="w-[24px] flex justify-center items-center">
                                <i class="text-gray-400 fa-solid fa-user-graduate"></i>
                            </div>
                            <p class="p_formateur_{{ $id }} flex flex-row items-center gap-2 text-gray-600">
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid w-full col-span-12 grid-cols-subgrid">
                <hr class="border-[1px] w-full border-gray-200 my-2">
                <div class="inline-flex items-center w-full">
                    <div class="w-[24px] flex justify-center items-center">
                        <i class="text-gray-400 fa-solid fa-location-dot"></i>
                    </div>
                    <p class="p_lieu_{{ $id }} text-gray-600" title="">
                        <span class="p_salle_name_{{ $id }}"></span>
                        <span class="p_salle_quartier_{{ $id }}"></span> -
                        <span class="p_salle_ville_{{ $id }}"></span> -
                        <span class="p_salle_code_postal_{{ $id }}"></span>
                    </p>
                </div>
            </div>
            <div class="grid w-full col-span-12 grid-cols-subgrid">
                <hr class="border-[1px] w-full border-gray-200 my-2">
                <div class="grid w-full grid-cols-3">
                    <div class="inline-flex items-center w-full">
                        <div class="w-[24px] flex justify-center items-center">
                            <i class="text-gray-400 fa-solid fa-folder-tree"></i>
                        </div>
                        <p class="font-medium text-gray-500 p_doc_{{ $id }}">7 Documents</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="min-[350px]:hidden md:grid grid-cols-10 w-full">
            <div class="grid w-full md:col-span-4 lg:col-span-2 grid-cols-subgrid">
                <div class="grid w-full col-span-4 grid-cols-subgrid">
                    <h1 class="p_module_{{ $id }} text-xl font-medium text-gray-600" title="">
                    </h1>
                </div>

                <div class="grid col-span-6 grid-cols-subgrid">
                    <span class="p_etp_initial_{{ $id }} inline-flex items-center gap-x-3">
                    </span>
                </div>

                <div class="w-full col-span-4 md:grid lg:hidden grid-cols-subgrid">
                    <div class="inline-flex items-center w-full gap-4">
                        <div class="inline-flex items-center gap-x-1 seance_count_{{ $id }}">
                            <p class="text-gray-600">
                            </p>
                            <span class="text-gray-400">Sessions</span>
                        </div>
                        <div class="inline-flex items-center gap-x-1">
                            <p class="text-gray-600 heure_count_{{ $id }}">
                            </p>
                            <span class="text-gray-400">Heures</span>
                        </div>
                        @isset($id)
                            <div class="inline-flex items-center gap-x-1">
                                <p class="text-gray-600 part_count_{{ $id }}">
                                </p>
                                <span class="text-gray-400">Particuliers</span>
                            </div>
                        @endisset
                        <div class="inline-flex items-center gap-x-1 appr_count_{{ $id }}">
                            <p class="text-gray-600">
                            </p>
                            <span class="text-gray-400">Apprenants</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full lg:grid min-[350px]:hidden lg:col-span-3 gap-y-0 grid-cols-subgrid">
                <div class="grid w-full col-span-3 grid-cols-subgrid">
                    <div class="flex flex-col items-start w-full">
                        <div class="w-[24px] flex justify-center items-center">
                            <p class="text-gray-400">Lieu</p>
                        </div>
                        <p class="p_lieu_{{ $id }} text-gray-600"
                            title="Salle 05 - Hotel Radison Blue Andraharo">
                            <span class="p_salle_name_{{ $id }}"></span>
                            <span class="p_salle_quartier_{{ $id }}"></span> -
                            <span class="p_salle_ville_{{ $id }}"></span> -
                            <span class="p_salle_code_postal_{{ $id }}"></span>
                        </p>
                    </div>
                </div>

                <div class="grid w-full col-span-3 grid-cols-subgrid">
                    <div class="inline-flex items-center w-full gap-4">
                        <div class="inline-flex items-center gap-x-1 seance_count_{{ $id }}">
                            <p class="text-gray-600">
                            </p>
                            <span class="text-gray-400">Sessions</span>
                        </div>
                        <div class="inline-flex items-center gap-x-1">
                            <p class="text-gray-600 heure_count_{{ $id }}">
                            </p>
                            <span class="text-gray-400">Heures</span>
                        </div>
                        {{-- @isset($id)
                            <div class="inline-flex items-center gap-x-1">
                                <p class="text-gray-600 part_count_{{ $id }}">
                                </p>
                                <span class="text-gray-400">Particuliers</span>
                            </div>
                        @endisset --}}
                        <div class="inline-flex items-center gap-x-1 appr_count_{{ $id }}">
                            <p class="text-gray-600">
                            </p>
                            <span class="text-gray-400">Apprenants</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid w-full md:col-span-4 lg:col-span-3 gap-y-0 grid-cols-subgrid">
                <div class="grid w-full md:col-span-4 lg:col-span-3">
                    <div class="grid w-full grid-cols-2">
                        <div class="grid w-full gap-2 md:col-span-2 lg:col-span-1 grid-cols-subgrid">
                            <div class="flex flex-col w-full">
                                <div class="flex w-full lg:flex-col md:flex-row lg:items-start md:items-center">
                                    <div class="md:hidden lg:block w-[24px] flex flex-col">
                                        <p class="text-gray-400">Statut</p>
                                    </div>
                                    <span
                                        class="p_statut_string_{{ $id }} inline-flex items-center gap-2 px-2 py-1 text-sm text-white w-[90px] justify-center">
                                    </span>
                                </div>
                            </div>

                            <div class="w-full md:block lg:hidden">
                                <div class="inline-flex items-center w-full">
                                    <div class="w-[24px] flex justify-center items-center">
                                        <i class="text-gray-400 fa-solid fa-location-dot"></i>
                                    </div>
                                    <p class="p_lieu_{{ $id }} text-gray-600" title="">
                                    <p class="text-gray-600" title="">
                                        <span class="p_salle_name_{{ $id }}"></span>
                                        <span class="p_salle_quartier_{{ $id }}"></span> -
                                        <span class="p_salle_ville_{{ $id }}"></span> -
                                        <span class="p_salle_code_postal_{{ $id }}"></span>
                                    </p>
                                </div>
                            </div>

                            <div class="grid col-span-1">
                                <div class="flex w-full lg:flex-col md:flex-row lg:items-start md:items-center">
                                    <div class="md:flex lg:hidden w-[24px] flex-col items-center justify-center">
                                        <i class="text-gray-400 fa-solid fa-user-graduate"></i>
                                    </div>
                                    <div class="md:hidden lg:block w-[24px] flex flex-col">
                                        <p class="text-gray-400">Formateur(s)</p>
                                    </div>
                                    <p
                                        class="p_formateur_{{ $id }} flex flex-row items-center gap-2 text-gray-600">
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="inline-flex items-center w-full">
                            <div class="w-[24px] flex justify-center items-center">
                                <i class="text-gray-400 fa-solid fa-folder-tree"></i>
                            </div>
                            <p class="font-medium text-gray-500 p_doc_{{ $id }}">7 Documents</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="grid w-full p-2 md:col-span-2 lg:col-span-2 grid-cols-subgrid">
            <div class="flex items-center justify-end w-full h-full gap-1">
                <span
                    class="p_modalite_{{ $id }} inline-flex items-center gap-2 px-2 py-1 w-[90px] justify-center">
                </span>
                <div class="btn-group h-max">
                    <button type="button" title="Cliquer pour afficher le menu"
                        class="w-8 h-8 bg-[#A462A4] rounded-md hover:bg-[#A462A4]/90 duration-150 cursor-pointer shadow-sm shadow-purple-500"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <span class=""><i class="text-white fa-solid fa-bars-staggered"></i></span>
                    </button>
                    <ul class="dropdown-menu">
                        <x-dropdown-item icontype="solid" icon="eye" label="Aperçu"
                            route="/projetsForm/{{ $id }}/detailForm" />
                        {{-- @if ($p['project_status'] != 'Terminé') --}}
                    </ul>
                </div>
            </div>

            <div class="grid items-end justify-end w-full col-span-1 p_note_lg_{{ $id }}">
                <div class="inline-flex items-center justify-end gap-1">
                    <i class="text-sm text-gray-600 fa-solid fa-star"></i>
                    <p class="font-medium text-gray-500"></p>
                </div>
            </div>
        </div>
        <div class="grid w-full col-span-10 grid-cols-subgrid">
            <hr class="border-[1px] w-full border-gray-200 my-3">
            {{-- Description --}}
            <div class="grid w-full grid-cols-6 pr-4">
                <div class="grid col-span-5 grid-cols-subgrid">
                    <span class="text-gray-500 p_description_{{ $id }}">
                    </span>
                </div>
                <div class="grid justify-end col-span-1 p_restauration_{{ $id }}">
                </div>
            </div>
        </div>
    </div>
</div>
</div>
