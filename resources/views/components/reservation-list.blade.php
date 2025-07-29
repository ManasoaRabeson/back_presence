<div
    class="grid col-span-1 p-4 h-[380px] rounded-2xl border-[1px] border-slate-200 shadow-md hover:shadow-xl relative duration-300 bg-white overflow-hidden group">
    <div class="grid grid-cols-6">
        <div class="grid col-span-5 grid-cols-subgrid">
            <h3 onclick="showFormation({{ $p['idModule'] }})"
                class="text-xl font-medium cursor-pointer text-slate-600 text-wrap line-clamp-2">
                @if (isset($p['module_name']) && $p['module_name'] != 'Default module')
                    {{ $p['module_name'] }}
                @else
                    <span class="text-gray-600">--</span>
                @endif
            </h3>
            <span class="inline-flex items-center h-full py-2 gap-2 p_note_{{ $p['idProjet'] }}">
            </span>
        </div>
        <div class="grid justify-end col-span-1">
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button"
                    class="flex items-center justify-center w-12 h-12 m-1 duration-200 bg-white cursor-pointer btn rounded-xl hover:bg-slate-100">
                    <i class="text-xl fa-solid fa-ellipsis-vertical text-slate-400"></i>
                </div>
                <ul tabindex="0"
                    class="dropdown-content project_menu_{{ $p['idProjet'] }} menu bg-base-100 rounded-box z-[1] w-72 p-2 shadow text-slate-600">
                    <li class="menu-title">Action</li>
                    <li>
                        <a href="/etp/projets/{{ $p['idProjet'] }}/detail"><i class="fa-solid fa-eye"></i> Aperçu</a>
                    </li>
                    <li>
                        <a href="/etp/reservations/paiement/{{ $p['idProjet'] }}/{{ $p['nbPlace'] ?? 0 }}"><span
                                class="px-1 text-[0.8em] py-0.7 font-semibold text-white bg-gray-500 rounded-full">Ar</span>Paiement
                            par mobile money</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="inline-flex items-center justify-between w-full gap-2 py-2">
        <div class="inline-flex items-center gap-2">
            <span
                class="px-3 py-1 text-sm rounded-xl type_{{ $p['idProjet'] }} text-[#7209b7] bg-[#7209b7]/10
            ">{{ $p['project_type'] }}</span>
            <span
                class="px-3 py-1 text-sm rounded-xl modalite_{{ $p['idProjet'] }} 
                @switch($p['modalite'])
                @case('Présentielle')
                text-[#00b4d8] bg-[#00b4d8]/10
                    @break
                @case('En ligne')
                text-[#fca311] bg-[#fca311]/10
                    @break
                @case('Blended')
                text-[#005f73] bg-[#005f73]/10   
                    @break
                @default
                @endswitch">{{ $p['modalite'] }}
            </span>
            <span class="px-3 py-1 text-sm rounded-xl text-slate-600 bg-slate-50">{{ $p['paiement'] }}</span>
            <span data-bs-toggle="tooltip" class="px-3 py-1 text-sm rounded-xl"
                @switch($p['is_active_inter'])
                @case('1')
                  style="background-color: #ddf6e8 !important; color: #28c76f !important;"
                  @break
                @case('2')
                  style="background-color: #fff0e1 !important; color: #ff9f43 !important;"
                  @break
                @case('3')
                  style="background-color: #ffe2e3 !important; color: #ff4c51 !important;"
                  @break
                @case('0')
                  style="background-color: #d6f4f8 !important; color: #00bad1 !important;"
                  @break
              @endswitch
                title=" @switch($p['is_active_inter'])
              @case('1')
                  Votre demande de {{ $p['nbPlace'] }} place(s) a été acceptée.
                @break
              @case('2')
                  Votre demande de {{ $p['nbPlace'] }} place(s) a été ajoutée à la liste d'attente.
                @break
              @case('3')
                  Votre demande de {{ $p['nbPlace'] }} place(s) a été refusée.
                @break
              @case('0')
                  Votre demande de {{ $p['nbPlace'] }} place(s) n'a pas encore été validée.
              @break
              @endswitch ">
                @if ($p['is_active_inter'] == 1)
                    Validé
                @elseif ($p['is_active_inter'] == 2)
                    En attente
                @elseif ($p['is_active_inter'] == 3)
                    Refusé
                @else
                    Non validé
                @endif
            </span>
        </div>
        <span
            class="px-3 py-1 text-sm text-white rounded-xl statut_{{ $p['idProjet'] }}
        @switch($p['project_status'])
                @case('En préparation')
                bg-[#F8E16F]
                    @break
                @case('Réservé')
                bg-[#33303D]
                    @break
                @case('En cours')
                bg-[#369ACC]
                    @break
                @case('Planifié')
                bg-[#CBABD1] 
                    @break
                @default
            @endswitch">{{ $p['project_status'] }}</span>
    </div>

    <div class="grid col-span-1 py-2">
        <div class="inline-flex items-center gap-2">
            <i class="fa-solid fa-location-dot text-slate-400"></i>
            <p class="text-lg text-slate-500">{{ $p['ville'] }}</p>
        </div>
    </div>


    <div class="flex flex-col w-full gap-1">
        {{-- ALERT --}}
        <div role="alert" class="alert !mb-0 p-2 !rounded-md inline-flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                class="w-6 h-6 stroke-info shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            @if ($p['is_active_inter'] == 1)
                <span class="inline-flex items-center justify-between w-full">
                    <span>Vous pouvez maintenant ajouter vos apprenants.</span>
                    <div>
                        <button onclick="__global_drawer('offcanvasApprenant', this)" data-id="{{ $p['idProjet'] }}"
                            data-nb_place="{{ $p['nbPlace'] }}" data-bs-toggle="tooltip" title="Ajouter des apprenants"
                            class="btn btn-sm btn-outline opacity-70"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </span>
            @else
                <span>Vous ne pouvez pas ajouter vos apprenants tant que votre réservation n'est pas validée.</span>
            @endif
        </div>
        <div class="py-2 w-full h-[47px] text-slate-500 text-wrap line-clamp-2">
            {{ $p['project_description'] ?? 'Pas de description' }}
        </div>
    </div>

    <div class="inline-flex items-center justify-between w-full gap-2 py-2">
        <div onclick="showApprenants('/etp/apprenant-drawer/{{ $p['idProjet'] }}')"
            class="avatar-group -space-x-4 rtl:space-x-reverse apprs_{{ $p['idProjet'] }}" data-bs-toggle="tooltip"
            title="Participants">

            @if ($p['is_active_inter'] == 1)
                <div class="inline-flex items-center w-full space-x-2" id="user_added_{{ $p['idProjet'] }}">
                </div>
            @endif
        </div>

        <div class="flex -space-x-2 overflow-hidden text-slate-400 etp_client_{{ $p['idProjet'] }}">
            @if (isset($p['logoCfp']))
                <img onclick="showCustomer({{ $p['idCfp_inter'] }}, '/etp/etp-drawer/')"
                    class="inline-block w-20 h-10 duration-200 cursor-pointer grayscale hover:grayscale-0 rounded-xl ring-2 ring-white"
                    src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/entreprises/{{ $p['logoCfp'] }}"
                    alt="" />
            @else
                <div onclick="showCustomer({{ $p['idCfp_inter'] }}, '/etp/etp-drawer/')"
                    class="flex items-center justify-center inline-block w-20 h-10 font-bold uppercase cursor-pointer rounded-xl ring-2 ring-white text-slate-600 bg-slate-100">
                    {{ $p['nameCfp'] }}</div>
            @endif

        </div>
    </div>

    <div class="inline-flex items-center justify-between w-full gap-2 py-2">
        <div class="flex -space-x-2 overflow-hidden opacity-60 text-slate-400 form_{{ $p['idProjet'] }}"
            data-bs-toggle="tooltip" title="Formateurs">
            @if (count($p['formateurs']) > 0)
                @foreach ($p['formateurs'] as $pf)
                    @if (isset($pf->form_photo))
                        <img class="object-cover w-10 h-10 rounded-full cursor-pointer"
                            src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/formateurs/{{ $pf->form_photo }}"
                            alt="photo" title="{{ $pf->form_name }} {{ $pf->form_firstname }}">
                    @else
                        <span title="{{ $pf->form_name }} {{ $pf->form_firstname }}"
                            class="flex items-center justify-center w-10 h-10 bg-gray-200 rounded-full cursor-pointer">{{ $pf->form_initial_name }}</span>
                    @endif
                @endforeach
            @else
                <span class="text-gray-400">--</span>
            @endif
        </div>

        <div class="inline-flex items-center gap-4">
            <span class="inline-flex items-center gap-2 cursor-pointer" data-bs-toggle="tooltip" title="Participants">
                <p class="text-lg font-medium text-slate-600">{{ $p['apprCount'] ?? 0 }} <span
                        class="font-normal underline text-slate-400">Par</span>
                </p>
            </span>
            <span class="inline-flex items-center gap-2 cursor-pointer"
                onclick="showSessions('/etp/session-drawer/{{ $p['idProjet'] }}')">
                <p class="text-lg font-medium text-slate-600" data-bs-toggle="tooltip" title="Sessions">
                    {{ $p['seanceCount'] ?? 0 }}
                    <span class="font-normal underline text-slate-400">Ses</span>
                </p>
            </span>
            <span onclick="showDocuments('/etp/document-drawer/{{ $p['idProjet'] }}')"
                class="inline-flex items-center gap-2 cursor-pointer">
                <p class="text-lg font-medium text-slate-600" data-bs-toggle="tooltip" title="Documents">
                    {{ $p['nbDocument'] ?? 0 }} <span class="font-normal underline text-slate-400">Docs</span></p>
            </span>

            <span class="inline-flex items-center gap-2 cursor-pointer">
                <p class="text-lg font-medium text-slate-600" data-bs-toggle="tooltip" title="Places réservées">
                    {{ $p['nbPlace'] ?? 0 }} <span class="font-normal text-slate-400">Plcs</span></p>
            </span>
        </div>
    </div>

    <div class="grid grid-cols-3 py-2 divide-x divide-slate-200">
        <div class="grid grid-cols-2 col-span-2">
            <div class="flex flex-col items-start ml-3">
                <h5 class="text-base text-slate-400">Date de début :</h5>
                <p class="text-xl font-semibold text-slate-600">{{ $p['deb'] }}</p>
            </div>
            <div class="flex flex-col items-start ml-3">
                <h5 class="text-base text-slate-400">Date de Fin :</h5>
                <p class="text-xl font-semibold text-slate-600">{{ $p['fin'] }}</p>
            </div>
        </div>
        <div class="grid col-span-1">
            <div class="flex flex-col items-start ml-3">
                <h5 class="text-base text-slate-400">Prix Hors Taxe :</h5>
                <p class="text-xl font-bold text-slate-600">{{ $p['total_ht'] ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>
