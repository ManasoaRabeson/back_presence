@php
    $id ??= '';
    $badge ??= '';
    $nom ??= '';
    $prenom ??= '';
    $mail ??= '';
    $telephone ??= '';
    $fonction ??= '';
    $img ??= null;
    $etpName ??= '';
@endphp
<div class="grid w-full col-span-1 p-4 bg-white shadow-xl h-72 rounded-xl">
    <div class="flex flex-col w-full h-full gap-2">
        <div class="grid w-full grid-cols-6 h-1/2">
            <div class="grid w-full col-span-5 grid-cols-subgrid">
                <div class="grid grid-cols-6">
                    <div class="grid items-start justify-center col-span-2 grid-cols-subgrid">
                        <div
                            class="flex items-center justify-center w-24 h-24 text-3xl font-medium text-gray-500 uppercase bg-gray-100 rounded-full">
                            @if ($img != null)
                                <img src="{{ $endpoint }}/{{ $bucket }}/img/formateurs/{{ $img }}"
                                    alt="photo" class="object-cover w-full h-full rounded-full">
                            @else
                                <i class="text-4xl text-slate-600 fa-solid fa-user-graduate"></i>
                            @endif
                        </div>
                    </div>
                    <div class="grid col-span-4 grid-cols-subgrid">
                        <div class="flex flex-col">
                            <p class="flex-wrap text-xl text-slate-600">{{ $nom }}</p>
                            <p class="flex-wrap text-xl text-slate-600">{!! $prenom ?? '' !!}</p>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="grid justify-end col-span-1">
                <div class="dropdown">
                    <button type="button" title="Cliquer pour afficher le menu"
                        class="w-8 h-8 bg-[#A462A4] rounded-md hover:bg-[#A462A4]/90 duration-150 cursor-pointer shadow-sm shadow-purple-500"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <span class=""><i class="text-white fa-solid fa-bars-staggered"></i></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li>
                            <a class="inline-flex items-center w-full h-full gap-2 p-2 duration-200 cursor-pointer hover:bg-gray-100 hover:text-inherit"
                                data-bs-toggle="offcanvas" href="#offcanvas" role="button" aria-controls="offcanvas"
                                onclick="editFormateur({{ $id }}); sessionStorage.setItem('ID_CROP_IMG_FORM', {{ $id }} )">
                                <i class="text-sm text-gray-700 fa-solid fa-pen"></i>
                                <span>Editer</span>
                            </a>
                            <input type="file" class="hidden inputFile" name="logofile-{{ $id }}"
                                id="logoFileForm">
                        </li>
                        <li>
                            <a class="inline-flex items-center w-full h-full gap-2 p-2 duration-200 cursor-pointer hover:bg-gray-100 hover:text-inherit"
                                onclick="viewMiniCV({{ $id }})">
                                <i class="text-sm text-gray-700 fa-solid fa-file"></i>
                                <span>Voir le CV</span>
                            </a>
                        </li>
                        <li>
                            <form action="{{ route('cfp.forms.hardDelete', $id) }}" method="post">
                                @csrf
                                @method('delete')

                                <button type="submit"
                                    class="inline-flex items-center w-full h-full gap-2 p-2 duration-200 cursor-pointer hover:bg-gray-100 hover:text-inherit">
                                    <i class="text-sm text-gray-700 fa-solid fa-trash-can"></i>
                                    <span>Supprimer</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                    <x-drawer-edit-form></x-drawer-edit-form>
                </div>
            </div> --}}
        </div>

        <div class="w-full bg-slate-50 h-1/2 rounded-xl">
            <div class="flex flex-col h-full gap-2 p-3">
                <div class="w-full">
                    <div class="grid grid-cols-5">
                        {{-- <div class="grid col-span-3 grid-cols-subgrid">
                        </div> --}}
                        <div class="grid justify-start col-span-2 grid-cols-subgrid">
                            <div class="flex flex-col">
                                {{-- @if ($badge == 0)
                                    <div class="flex justify-end w-full">
                                        <label class="px-2 py-1 text-base rounded-md text-amber-600 bg-amber-100">En
                                            attente</label>
                                    </div>
                                @else
                                    <div class="flex justify-end w-full">
                                        <label
                                            class="px-2 py-1 text-base text-green-600 bg-green-100 rounded-md">Membre</label>
                                    </div>
                                @endif --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col w-full gap-1">
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

                </div>
            </div>
        </div>

        <div class="flex items-center justify-end border-t-[1px]">
            <a class="btn btn-ghost" data-bs-toggle="tooltip" title="Voir le CV"
                onclick="viewMiniCV({{ $id }})">
                <i class="fa-solid fa-eye"></i>

            </a>
            <a class="btn btn-ghost" data-bs-toggle="offcanvas" href="#offcanvas" role="button" title="Editer"
                aria-controls="offcanvas"
                onclick="editFormateur({{ $id }}); sessionStorage.setItem('ID_CROP_IMG_FORM', {{ $id }} )">
                <i class="text-sm text-gray-700 fa-solid fa-pen"></i>

            </a>

            <form action="{{ route('cfp.forms.hardDelete', $id) }}" method="post">
                @csrf
                @method('delete')

                <button type="submit" class="btn btn-ghost" data-bs-toggle="tooltip" title="Supprimer définitivement">
                    <i class="text-sm text-gray-700 fa-solid fa-trash-can"></i>

                </button>
            </form>

            <x-drawer-edit-form></x-drawer-edit-form>

        </div>
    </div>
</div>
