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
    $idConnected ??= '';
@endphp
<div class="w-full p-3 bg-white shadow-xl h-96 rounded-xl">
    <div class="flex flex-col w-full h-full gap-2">
        <div class="w-full h-1/2">
            <div class="inline-flex items-start justify-between w-full">
                <div class="flex flex-col items-start gap-1">
                    <div
                        class="flex items-center justify-center text-3xl font-medium text-gray-500 uppercase bg-gray-100 rounded-full w-28 h-28">
                        @if ($img != null)
                            <img src="{{ $endpoint }}/{{ $bucket }}/img/referents/{{ $img }}"
                                alt="photo" class="object-cover w-full h-full rounded-full">
                        @else
                            <i class="text-4xl text-gray-400 fa-solid fa-user-tie"></i>
                        @endif
                    </div>
                    <p class="flex-wrap text-lg font-medium text-gray-700">{{ $nom }} {!! $prenom !!}
                    </p>
                    {{-- <p class="text-gray-400">{!! $fonction ?? '' !!}</p> --}}
                </div>


            </div>
        </div>
        <div class="w-full bg-slate-100 h-1/2 rounded-xl">
            <div class="flex flex-col h-full gap-2 p-3">
                <div class="w-full h-1/2">
                    <div class="grid grid-cols-5">
                        <div class="grid col-span-3 grid-cols-subgrid">
                        </div>
                        <div class="grid col-span-2 grid-cols-subgrid">
                            <div class="flex flex-col">
                                <span class="text-gray-400">Matricule</span>
                                <span class="text-gray-500">{{ $matricule }}</span>
                            </div>
                        </div>
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
                </div>
            </div>
        </div>

        @if ($idConnected == 3 || $idConnected == 6)
            <div class="flex items-center justify-end border-t-[1px]">

                <a class="btn btn-ghost" data-bs-toggle="offcanvas" href="#offcanvasPassword" role="button"
                    title="Editer son mot de pase" aria-controls="offcanvasPassword"
                    onclick="mainEditReferent({{ $id }})">
                    <i class="text-sm text-gray-700 fa-solid fa-key"></i>
                </a>

                <a class="btn btn-ghost" data-bs-toggle="offcanvas" href="#offcanvas" role="button" title="Editer"
                    aria-controls="offcanvas"
                    onclick="mainEditReferent({{ $id }}); sessionStorage.setItem('ID_CROP_IMG_REF', {{ $id }} )">
                    <i class="text-sm text-gray-700 fa-solid fa-pen"></i>
                </a>

                <form action="{{ route('cfp.referents.destroy', $id) }}" method="post">
                    @csrf
                    @method('delete')

                    <button type="submit" class="btn btn-ghost" data-bs-toggle="tooltip"
                        title="Supprimer définitivement">
                        <i class="text-sm text-gray-700 fa-solid fa-trash-can"></i>
                    </button>
                </form>
                @include('CFP.employeCfps.changePassword', ['idRef' => $id])
                <x-drawer-edit-referent idRef="{{ $id }}"></x-drawer-edit-referent>
            </div>
        @endif

    </div>
</div>
