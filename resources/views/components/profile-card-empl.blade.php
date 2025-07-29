@php
    $id ??= '';
    $matricule ??= '';
    $nom ??= '';
    $prenom ??= '';
    $mail ??= '';
    $telephone ??= '';
    $fonction ??= '';
    $img ??= '';
    $etpName ??= '';
    $empInitialName ??= '';
    $empService ??= '';
    $idEtp ??= '';
    $check ??= '';
@endphp
<div class="h-[28rem] w-full bg-white shadow-xl rounded-xl p-3 border-[1px] border-gray-100">
    <div class="flex flex-col w-full h-full gap-2">
        <div class="w-full h-1/2">
            <div class="grid w-full grid-cols-6">
                <div class="grid w-full col-span-5 grid-cols-subgrid">
                    <div class="flex flex-col items-start w-full gap-1 truncate">
                        <div
                            class="flex items-center justify-center text-3xl font-medium uppercase rounded-full w-28 h-28 bg-slate-100 text-slate-500">
                            @if ($img != null || $img != '')
                                <img src="{{ $digitalOcean }}/img/employes/{{ $img }}" alt="photo"
                                    class="object-cover w-full h-full rounded-full">
                            @else
                                <i class="text-4xl text-gray-600 fa-solid fa-user"></i>
                            @endif
                        </div>
                        <span class="flex flex-col gap-1">
                            <p title="{{ $nom }} {{ $prenom }}"
                                onclick="showEmployeUnique({{ $id }}, '/etp/etp-drawers/apprenant/')"
                                class="text-xl uppercase cursor-pointer line-clamp-2 text-slate-700 hover:underline">
                                {{ $nom }}
                            </p>
                            <p class="text-xl cursor-pointer line-clamp-2 text-slate-700 hover:underline"
                                onclick="showEmployeUnique({{ $id }}, '/etp/etp-drawers/apprenant/')">
                                {!! $prenom !!}</p>
                        </span>
                        <p class="text-slate-400">{{ $fonction == 'default_fonction' ? '' : '' }}</p>
                    </div>
                </div>
                <div class="grid justify-end col-span-1">
                    <div class="dropdown">
                        <button type="button" title="Cliquer pour afficher le menu"
                            class="w-8 h-8 bg-[#A462A4] rounded-md hover:bg-[#A462A4]/90 duration-150 cursor-pointer shadow-sm shadow-purple-500"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <span class=""><i class="text-white fa-solid fa-bars-staggered"></i></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li>
                                <a class="inline-flex items-center w-full h-full gap-2 p-2 duration-200 cursor-pointer hover:bg-slate-100 hover:text-inherit"
                                    data-bs-toggle="offcanvas" href="#offcanvasemp" role="button"
                                    aria-controls="offcanvasemp"
                                    onclick="editEmploye({{ $id }}); sessionStorage.setItem('ID_CROP_IMG_APPR', {{ $id }} )">
                                    <i class="text-sm fa-solid fa-pen text-slate-700"></i>
                                    <span>Editer</span>
                                </a>
                                <input type="file" class="hidden inputFile" name="logofile-{{ $id }}"
                                    id="logofile">
                            </li>
                            <li>
                                <form action="{{ url('/etp/employes/' . $id . '/delete') }}" method="post">
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
                        <x-drawer-edit-empl idAppr='{{ $id }}' nameAppr='{{ $nom }}'
                            firstnameAppr='{{ $prenom }}' emailAppr='{{ $mail }}'
                            etpName='{{ $etpName }}' photoAppr='{{ $img }}'
                            phoneAppr='{{ $telephone }}' matriculeAppr='{{ $matricule }}'>
                        </x-drawer-edit-empl>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full h-1/2 bg-slate-100 rounded-xl">
            <div class="flex flex-col h-full gap-2 p-3">
                <div class="w-full h-1/5">
                    <div class="grid grid-rows-5">
                        <div class="grid col-span-2 grid-cols-subgrid">
                            <div class="inline-flex items-start gap-2">
                                <span class="text-lg text-slate-400">Matricule</span>
                                <span class="text-lg text-slate-500">{{ $matricule }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col w-full gap-1 h-4/5">
                    <div class="inline-flex items-center gap-1">
                        <div class="w-[18px]" title="Entreprise client">
                            <i class="fa-solid fa-building text-slate-500"></i>
                        </div>
                        <p class="text-xl text-slate-600">{!! $etpName ?? 'Non renseigné' !!}</p>
                    </div>
                    <div class="inline-flex items-center gap-1">
                        <div class="w-[18px]" title="Adresse mail">
                            <i class="fa-solid fa-envelope text-slate-500"></i>
                        </div>
                        <p class="text-slate-500">{{ $mail ?? 'Non renseigné' }}</p>
                    </div>
                    <div class="inline-flex items-center gap-1">
                        <div class="w-[18px]" title="Téléphone">
                            <i class="fa-solid fa-phone text-slate-500"></i>
                        </div>
                        <p class="text-slate-500">{{ $telephone ?? 'Pas de téléphone' }}</p>
                    </div>
                    <span class="flex items-center justify-end w-full">
                        <input class="rounded-md emp_service_toggle" type="checkbox" data-id="{{ $id }}"
                            data-toggle="toggle" data-size="sm" data-onlabel="Actif" data-offlabel="Ex-employé"
                            data-onstyle="success" data-offstyle="outline-danger"
                            @if ($empService == 1) checked @endif {{ $check }} />


                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
