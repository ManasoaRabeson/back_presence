@php
    $id ??= '--';
    $badge ??= '--';
    $nom ??= '--';
    $prenom ??= '--';
    $etpName ??= '--';
    $mail ??= '--';
    $telephone ??= '--';
    $adresse ??= '--';
    //   $fonction ??= '--';
    $imgClient ??= null;
@endphp
<div class="w-full p-3 h-96 bg-white shadow-xl rounded-xl">
    <div class="flex flex-col w-full h-full gap-2">
        <div class="w-full h-2/5">
            <div class="inline-flex items-start justify-between w-full">
                <div class="flex flex-row items-start gap-2">
                    <div id="logo_{{ $id }}"
                        class="flex items-center justify-center h-24 text-3xl font-medium text-gray-500 uppercase bg-gray-100 w-44 rounded-xl">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <p class="flex-wrap text-lg font-medium text-gray-700">{!! $etpName !!}</p>
                    {{-- <p class="text-gray-400">{{ $fonction }}</p> --}}
                </div>
            </div>
        </div>
        <div class="w-full bg-slate-100 h-3/5 rounded-xl">
            <div class="flex flex-col h-full gap-2 p-3">
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
        </div>
    </div>
</div>
