@php
    $id ??= null;
    $matricule ??= '';
    $nom ??= '';
    $prenom ??= '';
    $mail ??= '';
    $telephone ??= '';
    $fonction ??= '';
    $img ??= '';
    $etpName ??= '';
    $empInitialName ??= '';
    $ville ??= '';
    $idEtp ??= '';
    $idCustomer ??= '';
    $userId ??= '';
@endphp
<div class="grid w-full col-span-1 p-3 my-auto bg-white shadow-xl h-55 rounded-xl">
    <div class="flex flex-col justify-center w-full h-full gap-2">
        <div class="grid w-full grid-cols-6">
            <div class="grid w-full col-span-5 grid-cols-subgrid">
                <div class="grid grid-cols-6">
                    <div class="grid items-center justify-center col-span-2 grid-cols-subgrid">
                        <div id="emp_photo_{{ $id }}"
                            class="flex items-center justify-center w-24 h-24 text-3xl font-medium text-gray-500 uppercase bg-gray-100 rounded-full">
                            @if ($img == null || $img == '')
                                <i class="text-4xl text-gray-600 fa-solid fa-user"></i>
                            @else
                                <img src="{{ $endpoint }}/{{ $bucket }}/img/employes/{{ $img }}"
                                    alt="photo" class="object-cover w-full h-full rounded-full">
                            @endif
                        </div>
                    </div>
                    <div class="grid col-span-4 grid-cols-subgrid">
                        <div class="flex flex-col">
                            <p onclick="showEmployeUnique({{ $id }}, '/cfp/etp-drawers/apprenant/')"
                                title="{{ $nom }} {!! $prenom !!}"
                                class="text-lg uppercase cursor-pointer text-slate-600 line-clamp-2 hover:underline">
                                {{ $nom }}</p>
                            <p onclick="showEmployeUnique({{ $id }}, '/cfp/etp-drawers/apprenant/')"
                                class="text-lg capitalize cursor-pointer text-slate-600 line-clamp-1 hover:underline">
                                {!! $prenom !!}</p>
                            <div class="mt-2">
                                <p onclick="showCustomer({{ $idEtp }},'/cfp/etp-drawer/')"
                                    class="cursor-pointer text-slate-400 hover:underline line-clamp-2 etp_name_{{ $id }}">
                                    {!! $etpName !!}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="flex items-center justify-end border-t-[1px]">
            <a class="btn btn-ghost" role="button" title="Editer"
                onclick="editApprenant({{ $id }}); sessionStorage.setItem('ID_CROP_IMG_APPR', {{ $id }} )">
                <i class="text-sm text-gray-700 fa-solid fa-pen"></i>

            </a>
            <input type="file" class="hidden inputFile" name="logofile-{{ $id }}"
                id="logofile-{{ $id }}">

            @if ($idCustomer != null && $userId === $idCustomer)
                {{-- <form action="{{ url('cfp/apprenants', $id) }}" method="post">
                        @csrf
                        @method('delete') --}}

                <button onclick="deleteAppr({{ $id }})" type="button" class="btn btn-ghost"
                    title="Supprimer définitivement">
                    <i class="text-sm text-gray-700 fa-solid fa-trash-can"></i>

                </button>
                {{-- </form> --}}
            @endif
        </div>
    </div>
</div>
