@php
    $id ??= '';
    $idCfp ??= '';
    $badge ??= '--';
    $nom ??= '--';
    $prenom ??= '--';
    $etpName ??= '--';
    $mail ??= '--';
    $telephone ??= '--';
    $adresse ??= '--';
    $imgClient ??= null;
    $logo ??= '';
@endphp
<div class="w-full p-3 bg-white shadow-xl h-96 rounded-xl">
    <div class="flex flex-col w-full h-full gap-2">
        <div class="w-full h-2/5">
            <div class="inline-flex items-start justify-between w-full">
                <a href="/organisme_formation/{{ $idCfp }}" class="flex flex-row items-start gap-2">
                    <div id="logo_{{ $id }}"
                        class="flex items-center justify-center h-24 text-3xl font-medium text-gray-500 uppercase bg-gray-100 w-44 rounded-xl">
                        @if ($logo)
                            <img src="{{ $digitalOcean }}/img/entreprises/{{ $logo }}" alt="photo"
                                class="object-cover w-full h-full rounded-xl">
                        @else
                            <img src="{{ asset('img/logo/Logo_mark.svg') }}" alt="forma-fusion"
                                class="object-cover w-auto h-16 grayscale rounded-xl">
                        @endif
                    </div>

                    <p class="flex-wrap text-lg font-medium text-gray-700">{!! $etpName !!}</p>
                    {{-- <p class="text-gray-400">{{ $fonction }}</p> --}}
                </a>

                {{--    
                <div class="dropdown">
                    <button type="button" title="Cliquer pour afficher le menu"
                        class="w-8 h-8 bg-[#A462A4] rounded-md hover:bg-[#A462A4]/90 duration-150 cursor-pointer shadow-sm shadow-purple-500"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <span class=""><i class="text-white fa-solid fa-bars-staggered"></i></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li>
                            <a class="inline-flex items-center w-full h-full gap-2 p-2 duration-200 cursor-pointer hover:bg-gray-100 hover:text-inherit"
                                onclick="editClient({{ $id }}); sessionStorage.setItem('ID_CROP_IMG_ETP', {{ $id }} )">
                                <i class="text-sm text-gray-700 fa-solid fa-pen"></i>
                                <span>Editer</span>
                            </a>
                            <input type="file" class="hidden inputFile" name="logofileEtp-{{ $id }}"
                                id="logofileEtp">
                        </li>

                        <li>
                            <button onclick="deleteClient({{ $id }})" type="button"
                                class="inline-flex items-center w-full h-full gap-2 p-2 duration-200 cursor-pointer hover:bg-gray-100 hover:text-inherit">
                                <i class="text-sm text-gray-700 fa-solid fa-trash-can"></i>
                                <span>Supprimer</span>

                            </button>
                        </li>
                    </ul>
                    {{-- <x-drawer-edit-client></x-drawer-edit-client> 
                </div> --}}



            </div>

        </div>
        <div class="w-full bg-slate-50 h-3/5 rounded-xl">
            <div class="flex flex-col h-[75%] gap-2 p-2">
                <div class="w-full h-1/2">
                    <div class="grid grid-cols-5">
                        <div class="grid col-span-3 grid-cols-subgrid">
                            <div class="flex flex-col">
                                <span class="text-gray-400">Référent</span>
                                <span class="text-gray-500">
                                    {{ $nom }} {!! $prenom !!}</span>
                                <span onclick="showCustomer({{ $id }}, '/cfp/etp-drawer/')"
                                    class="text-purple-700 underline duration-200 cursor-pointer hover:text-purple-500">voir
                                    tous les
                                    référents</span>
                            </div>
                        </div>
                        {{-- <div class="grid col-span-2 grid-cols-subgrid">
                            <div class="flex flex-col">
                                @if ($badge == 0)
                                    <div class="flex justify-end w-full">
                                        <label class="px-2 py-1 text-base text-white rounded-md bg-amber-400">En
                                            attente</label>
                                    </div>
                                @else
                                    <div class="flex justify-end w-full">
                                        <label
                                            class="px-2 py-1 text-base text-white bg-green-400 rounded-md">Membre</label>
                                    </div>
                                @endif
                            </div>
                        </div> --}}
                    </div>
                </div>
                <div class="flex flex-col w-full gap-1 h-1/2">
                    <div class="inline-flex items-center gap-1">
                        <div class="w-[18px]">
                            <i class="text-gray-500 fa-solid fa-envelope"></i>
                        </div>
                        <p class="text-gray-500">{{ $mail }}</p>
                    </div>
                    <div class="inline-flex items-center gap-1">
                        <div class="w-[18px]">
                            <i class="text-gray-500 fa-solid fa-phone"></i>
                        </div>
                        <p class="text-gray-500">{{ $telephone }}</p>
                    </div>
                    <div class="inline-flex items-center gap-1">
                        <div class="w-[18px]">
                            <i class="text-gray-500 fa-solid fa-location-dot"></i>
                        </div>
                        <p class="text-gray-500">{{ $adresse }}</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end border-t-[1px]">
                <a class="btn btn-ghost" title="Editer" aria-controls="offcanvas"
                    onclick="editClient({{ $id }}); sessionStorage.setItem('ID_CROP_IMG_ETP', {{ $id }} )">
                    <i class="text-sm text-gray-700 fa-solid fa-pen"></i>
                </a>
                <input type="file" class="hidden inputFile" name="logofileEtp-{{ $id }}" id="logofileEtp">

                <button onclick="deleteClient({{ $id }})" type="button" class="btn btn-ghost"
                    data-bs-toggle="tooltip" title="Supprimer définitivement">
                    <i class="text-sm text-gray-700 fa-solid fa-trash-can"></i>

                </button>


            </div>

        </div>
    </div>
</div>
