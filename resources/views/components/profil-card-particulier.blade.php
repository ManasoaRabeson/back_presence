@php
    $id ??= '';
    $nom ??= '';
    $prenom ??= '';
    $mail ??= '';
    $phone ??= '';
    $fonction ??= '';
    $img ??= null;
    $etpName ??= '';
    $routeD ??= '';
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
                                <img src="{{ $endpoint }}/{{ $bucket }}/img/particuliers/{{ $img }}"
                                    alt="photo" class="object-cover w-full h-full rounded-full">
                            @else
                                <i class="text-4xl text-slate-600 fa-solid fa-user"></i>
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
                    <button tabindex="0" role="button" title="Cliquer pour afficher le menu"
                        class="w-8 h-8 bg-[#A462A4] rounded-md hover:bg-[#A462A4]/90 duration-150 cursor-pointer shadow-sm shadow-purple-500"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <span class=""><i class="text-white fa-solid fa-bars-staggered"></i></span>
                    </button>
                    <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
                        <li><a> <i class="fa-solid fa-pen"></i>Editer</a></li>
                        <li><a> <i class="fa-solid fa-trash-can"></i>Supprimer</a></li>
                    </ul>
                </div>
            </div> --}}
        </div>
        <div class="w-full bg-slate-50 rounded-xl">
            <div class="flex flex-col gap-2 p-3">
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
                        <p class="text-gray-500">{{ $phone }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-end border-t-[1px]">
            {{-- <a href="/" class="btn btn-ghost" data-bs-toggle="tooltip" title="Editer">
                <i class="fa-solid fa-pen"></i>
            </a> --}}
            <a class="btn btn-ghost" role="button" data-bs-toggle="tooltip" title="Editer"
                onclick="editParticulier({{ $id }}); sessionStorage.setItem('ID_CROP_IMG_APPR', {{ $id }} )">
                <i class="text-sm text-gray-700 fa-solid fa-pen"></i>

            </a>
            <form action="{{ $routeD }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="btn btn-ghost" data-bs-toggle="tooltip" title="Supprimer">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </form>
        </div>
    </div>
</div>
