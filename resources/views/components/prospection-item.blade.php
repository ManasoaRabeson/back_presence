@php
    $endpoint = 'https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg';
@endphp
<li class="cursor-pointer prospection-item" data-id="{{ $item['id_opportunite'] }}">
    <div class="w-full p-4 rounded-lg border-[1px] border-slate-100 hover:shadow-md bg-white duration-300">
        <div class="grid grid-cols-4 w-full">
            <div class="grid col-span-3 grid-cols-subgrid">
                <div class="grid grid-cols-4 items-start">
                    <div class="grid col-span-1">
                        <div class="avatar">
                            <div class="w-14 h-10 rounded-md">
                                @if (isset($item['etp_logo']))
                                    <img alt="{{ $item['etp_name'] }}"
                                        src="{{ $endpoint }}/img/entreprises/{{ $item['etp_logo'] }}" />
                                @else
                                    <span class="flex items-center justify-center bg-slate-200 h-full w-full">
                                        <i class="fa-solid fa-building text-slate-700 text-lg"></i>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="grid col-span-3 grid-cols-subgrid">
                        <div class="flex flex-col">
                            <p class="text-lg text-slate-700 capitalize line-clamp-2">{{ $item['etp_name'] }}</p>
                            <p class="text-sm text-slate-700 capitalize line-clamp-2">{{ $item['ref_name'] }}
                                {!! $item['ref_firstName'] !!}
                            </p>
                            <p class="text-lg text-slate-700 capitalize line-clamp-2">
                                @if (isset($item['etp_phone']))
                                    <span class="text-slate-500">({{ $item['etp_phone'] }})</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid col-span-1 justify-end">
            </div>
        </div>
        <div class="flex flex-col gap-1 mt-2">
            <div class="grid grid-cols-12 items-center">
                <div class="grid col-span-1 text-slate-500"><i class="fa-solid text-sm fa-puzzle-piece"></i></div>
                <div class="grid col-span-11">
                    <p class="text-slate-700 grid-cols-subgrid font-medium line-clamp-2">
                        {{ $item['cours_name'] }}
                    </p>
                </div>
            </div>
            <div class="grid grid-cols-12 items-center">
                <div class="grid col-span-1 text-slate-500"><i class="fa-solid text-sm fa-calendar-days"></i></div>
                <div class="grid col-span-11 text-slate-700 grid-cols-subgrid">{{ $item['dateDeb'] }} -
                    {{ $item['dateFin'] }}</div>
            </div>
            <div class="grid grid-cols-2">
                <div class="grid col-span-1">
                    <div class="grid grid-cols-6 items-center">
                        <div class="grid col-span-1 text-slate-500"><i class="fa-solid text-sm fa-users"></i></div>
                        <div class="grid col-span-5 text-slate-700 grid-cols-subgrid">{{ $item['nbPersonne'] }}</div>
                    </div>
                </div>
                <div class="grid col-span-1 justify-end">
                    <div class="grid grid-cols-6 items-center">
                        <div class="grid col-span-1 text-slate-500"><i class="fa-solid text-sm fa-dollar"></i></div>
                        <div class="grid col-span-5 text-slate-700 grid-cols-subgrid text-lg font-medium">
                            {{ $item['prix'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <hr class="border-[1px] border-slate-400 w-full my-2">
        <div class="inline-flex items-center w-full justify-between">
            <ul class="menu menu-sm menu-horizontal rounded-box">
                <li data-bs-toggle="tooltip" title="Gagnée">
                    <a onclick="manageOpportunitiesWin({{ $item['id_opportunite'] }})">
                        <i class="fa-solid text-sm fa-thumbs-up text-green-500"></i>
                    </a>
                </li>
                <li data-bs-toggle="tooltip" title="Perdue">
                    <a onclick="manageOpportunities({{ $item['id_opportunite'] }}, 'lost')">
                        <i class="fa-solid text-sm fa-thumbs-down text-red-500"></i>
                    </a>
                </li>
                <li data-bs-toggle="tooltip" title="En veille">
                    <a onclick="manageOpportunities({{ $item['id_opportunite'] }}, 'standBy')">
                        <i class="fa-solid text-sm fa-hand text-blue-500"></i>
                    </a>
                </li>
            </ul>
            <ul class="menu menu-sm menu-horizontal rounded-box">
                <li data-bs-toggle="tooltip" title="Supprimer">
                    <a onclick="removeOpportunitie({{ $item['id_opportunite'] }})">
                        <i class="fa-solid text-sm text-slate-600 fa-trash-can"></i>
                    </a>
                </li>
                <li data-bs-toggle="tooltip" title="Editer">
                    <a onclick="drawer('offcanvasProspectionEdit', {{ $item['id_opportunite'] }})">
                        <i class="fa-solid text-sm text-slate-600 fa-pen"></i>
                    </a>
                </li>
                <li data-bs-toggle="tooltip" title="Aperçu">
                    <a onclick="show({{ $item['id_opportunite'] }})">
                        <i class="fa-solid text-sm text-slate-600 fa-eye"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</li>
