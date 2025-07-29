@php
    $endpoint = 'https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg';
@endphp
<tr class="prospection-item">
    <th>{{ $i }}</th>
    <td>
        <div class="inline-flex items-start gap-2">
            <div class="avatar">
                <div class="w-12 h-8 rounded">
                    @if (isset($item['etp_logo']))
                        <img src="{{ $endpoint }}/img/entreprises/{{ $item['etp_logo'] }}" />
                    @else
                        <span class="flex items-center justify-center bg-slate-200 h-full w-full">
                            <i class="fa-solid fa-building text-slate-700 text-sm"></i>
                        </span>
                    @endif
                </div>
            </div>
            <p class="text-slate-500 text-lg line-clamp-2 capitalize">{{ $item['etp_name'] }}</p>
        </div>
    </td>
    <td>
        <div class="inline-flex items-start gap-2">
            <div class="avatar">
                <div class="w-12 h-8 rounded">
                    <img src="{{ $endpoint }}/img/modules/{{ $item['cours_img'] ?? '' }}"
                        alt="Tailwind-CSS-Avatar-component" />
                </div>
            </div>
            <p class="text-slate-500 text-lg line-clamp-1">{{ $item['cours_name'] }}
            </p>
        </div>
    </td>
    <td>{{ $item['nbPersonne'] }}</td>
    <td>{{ $item['dateDeb'] }}</td>
    <td>{{ $item['dateFin'] }}</td>
    <td>
        <span class="inline-flex items-center gap-1">
            @switch($item['opportunite_id_statut'] ?? '')
                @case(1)
                    <div class="w-3 h-3 rounded-full bg-[#4056F4]"></div>
                    <p class="text-lg">Identification</p>
                @break

                @case(2)
                    <div class="w-3 h-3 rounded-full bg-[#E42548]"></div>
                    <p class="text-lg">Offre</p>
                @break

                @case(3)
                    <div class="w-3 h-3 rounded-full bg-[#CB9801]"></div>
                    <p class="text-lg">Rendez-vous</p>
                @break

                @case(4)
                    <div class="w-3 h-3 rounded-full bg-[#126936]"></div>
                    <p class="text-lg">Négociation</p>
                @break

                @case(5)
                    <div class="w-3 h-3 rounded-full bg-[#041925]"></div>
                    <p class="text-lg">Pré-réservation</p>
                @break

                @default
            @endswitch

        </span>
    </td>
    <td class="text-right">{{ $item['prix'] }}</td>
    <td class="text-right">
        <div class="dropdown dropdown-end">
            <button tabindex="0" id="b_menu_table_prospection_{{ $item['id_opportunite'] }}"
                aria-label="menuOpportunite" role="button" class="btn btn-sm btn-square btn-ghost opacity-70"><i
                    class="fa-solid fa-ellipsis"></i></button>
            <ul tabindex="0" class="dropdown-content menu bg-base-100 text-left rounded-box z-[1] w-52 p-2 shadow">
                <li class="menu-title">Action</li>
                <li><a onclick="show({{ $item['id_opportunite'] }})"><i class="fa-solid text-sm fa-eye"></i>
                        Aperçu</a></li>
                <li><a onclick="drawer('offcanvasProspectionEdit', {{ $item['id_opportunite'] }})"><i
                            class="fa-solid text-sm fa-pen"></i>
                        Editer</a></li>
                <li><a onclick="removeOpportunitie({{ $item['id_opportunite'] }})"><i
                            class="fa-solid text-sm fa-trash-can"></i> Supprimer</a></li>
                <li class="menu-title">Opportunité</li>
                @if (!isset($item['opportunitie_is_win']))
                    <li><a onclick="manageOpportunities({{ $item['id_opportunite'] }}, 'win')"><i
                                class="fa-solid text-sm fa-check text-green-500"></i> Gagnée</a></li>
                @endif
                @if (!isset($item['opportunitie_is_lost']))
                    <li><a onclick="manageOpportunities({{ $item['id_opportunite'] }}, 'lost')"><i
                                class="fa-solid text-sm fa-xmark text-red-500"></i> Perdue</a></li>
                @endif
                @if (!isset($item['opportunitie_is_standBy']))
                    <li><a onclick="manageOpportunities({{ $item['id_opportunite'] }}, 'standBy')"><i
                                class="fa-solid text-sm fa-spinner text-blue-500"></i> En veille</a></li>
                @endif
                <li><a onclick="manageOpportunities({{ $item['id_opportunite'] }}, 'restore')"><i
                            class="fa-solid text-sm fa-recycle text-slate-500"></i> Restaurer</a></li>
            </ul>
        </div>
    </td>
</tr>
