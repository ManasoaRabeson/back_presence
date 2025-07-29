@php
    $id ??= '--';
    $badge ??= '--';
    $nom ??= '--';
    $prenom ??= '--';
    $cfpName ??= '--';
    $mail ??= '--';
    $telephone ??= '--';
    $adresse ??= '--';
    //   $fonction ??= '--';
    $img ??= null;
    $enpoint = 'https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg';
@endphp
<div class="w-full p-3 bg-white border-[1px] border-gray-200 shadow-md h-96 rounded-xl">
    <div class="flex flex-col w-full h-full gap-2">
        <div class="w-full h-2/5">
            <div class="inline-flex items-start justify-between w-full">
                <div class="flex flex-row items-start gap-2">
                    <div
                        class="flex items-center justify-center h-24 text-3xl font-medium text-gray-500 uppercase bg-gray-100 w-44 rounded-xl">
                        @if ($img != null)
                            <img src="{{ $enpoint }}/img/entreprises/{{ $img }}" alt="photo"
                                class="object-cover w-full h-full rounded-xl">
                        @else
                            {{ $cfpName[0] }}
                        @endif
                    </div>
                    <p class="flex-wrap text-lg font-medium text-gray-700">{{ $nom }}</p>
                </div>
            </div>
        </div>
        <div class="w-full bg-slate-50 h-3/5 rounded-xl">
            <div class="flex flex-col h-full gap-2 p-3">
                <div class="w-full h-1/2">
                    <div class="grid grid-cols-5">
                        <div class="grid col-span-3 grid-cols-subgrid">
                            <div class="flex flex-col">
                                <span class="text-gray-400">Référent</span>
                                <span class="text-gray-500">{{ $nom }} {!! $prenom !!}</span>
                                <span
                                    class="text-purple-700 underline duration-200 cursor-pointer hover:text-purple-500">voir
                                    tous les référents</span>
                                {{-- <span onclick="showCustomer({{ $id }}, '/etp/cfp-drawer/')" class="text-purple-700 underline duration-200 cursor-pointer hover:text-purple-500">voir tous les référents</span> --}}
                            </div>
                        </div>
                        <div class="grid col-span-2 grid-cols-subgrid">
                            {{-- <div class="flex flex-col">
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
                            </div> --}}
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
                    <div class="inline-flex items-center gap-1">
                        <div class="w-[18px]">
                            <i class="text-gray-500 fa-solid fa-location-dot"></i>
                        </div>
                        <p class="text-gray-500">{{ $adresse }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
