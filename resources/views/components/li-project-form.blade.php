<div class="grid w-full min-w-[550px] grid-cols-12 gap-x-6 p-2 border-[1px] border-gray-200 rounded-xl">
    {{-- Bloc 1 --}}
    <div class="grid w-full min-[350px]:col-span-12 md:col-span-2 grid-cols-subgrid">
        <div class="min-[350px]:hidden md:block">
            <div
                class="p_statut_{{ $p['idProjet'] }} w-full h-full bg-gradient-to-br relative rounded-md overflow-hidden text-white p-3
                              @switch($p['project_status'])
                                @case('En préparation')
                                  from-[#66CDAA] to-[#66CDAA]/50
                                  @break
                                @case('Réservé')
                                  from-[#33303D] to-[#33303D]/50
                                  @break
                                @case('En cours')
                                  from-[#1E90FF] to-[#1E90FF]/50
                                  @break
                                @case('Terminé')
                                  from-[#32CD32] to-[#32CD32]/50
                                  @break
                                @case('Annulé')
                                  from-[#FF6347] to-[#FF6347]/50
                                  @break
                                @case('Reporté')
                                  from-[#2E705A] to-[#2E705A]/50
                                  @break
                                @case('Planifié')
                                  from-[#2552BA] to-[#2552BA]/50
                                  @break
                                @case('Cloturé')
                                  from-[#828282] to-[#828282]/50
                                  @break
                                @default
                                 @case('Cloturé')
                                  from-[#828282] to-[#828282]/50
                                  @break
                              @endswitch">
                <div
                    class="p_type_{{ $p['idProjet'] }} px-2 py-1 text-white text-sm text-center w-36 absolute -left-10 top-3 -rotate-45 shadow-sm
                                    @switch($p['project_type'])
                                      @case('Intra')
                                      bg-[#1565c0]
                                      @break
                                      @case('Inter')
                                      bg-[#7209b7]
                                      @break
                                      @default
                                      @break
                                    @endswitch">
                    <p class="text-sm text-white">{{ $p['project_type'] }}</p>
                </div>
                <div class="flex flex-col items-center justify-center h-full ml-12">
                    <span class="flex flex-col justify-center w-full">
                        <h5 class="p_date_year_{{ $p['idProjet'] }} text-xl font-medium text-white">
                            @if ($p['dateDebut'] != null)
                                {{ $p['dateDebut'] ? Carbon\Carbon::parse($p['dateDebut'])->format('Y') : '' }}
                            @else
                                --
                            @endif
                        </h5>
                        <div class="inline-flex items-end gap-2">
                            <h5 class="p_date_jour_debut_{{ $p['idProjet'] }} text-4xl font-semibold text-white">
                                @if ($p['dateDebut'] != null)
                                    {{ $p['dateDebut'] ? Carbon\Carbon::parse($p['dateDebut'])->format('d') : '' }}
                                @else
                                    --
                                @endif -
                            </h5>
                            <h5 class="p_date_jour_fin_[] text-xl text-white">
                                @if ($p['dateFin'] != null)
                                    {{ $p['dateFin'] ? Carbon\Carbon::parse($p['dateFin'])->format('d') : '' }}
                                @else
                                    --
                                @endif
                            </h5>
                        </div>
                        <div class="inline-flex items-end gap-4">
                            <h5 class="p_date_mois_debut_[] text-xl font-semibold text-white">
                                @if ($p['dateDebut'] != null)
                                    {{ $p['dateDebut'] ? Carbon\Carbon::parse($p['dateDebut'])->format('M') : '' }}
                                @else
                                    --
                                @endif
                            </h5>
                            <h5 class="p_date_mois_fin_[] text-lg text-white">
                                @if ($p['dateFin'] != null)
                                    {{ $p['dateFin'] ? Carbon\Carbon::parse($p['dateFin'])->format('M') : '' }}
                                @else
                                    --
                                @endif
                            </h5>
                        </div>
                    </span>
                </div>
            </div>
        </div>

        <div class="grid w-full grid-cols-12 md:hidden gap-x-4">
            {{-- Date + Type --}}
            <div class="grid w-full h-full min-[350px]:col-span-4 grid-cols-subgrid">
                <div
                    class="w-full h-full bg-gradient-to-br relative rounded-md overflow-hidden text-white p-3
                                @switch($p['project_status'])
                                  @case('En préparation')
                                    from-[#66CDAA] to-[#66CDAA]/50
                                    @break
                                  @case('Réservé')
                                    from-[#33303D] to-[#33303D]/50
                                    @break
                                  @case('En cours')
                                    from-[#1E90FF] to-[#1E90FF]/50
                                    @break
                                  @case('Terminé')
                                    from-[#32CD32] to-[#32CD32]/50
                                    @break
                                  @case('Annulé')
                                    from-[#FF6347] to-[#FF6347]/50
                                    @break
                                  @case('Reporté')
                                    from-[#2E705A] to-[#2E705A]/50
                                    @break
                                  @case('Planifié')
                                    from-[#2552BA] to-[#2552BA]/50
                                    @break
                                  @case('Cloturé')
                                    from-[#828282] to-[#828282]/50
                                    @break
                                  @default
                                @endswitch">
                    <div
                        class="px-2 py-1 text-white text-sm text-center w-36 absolute -left-10 top-3 -rotate-45 shadow-sm
                                    @switch($p['project_type'])
                                      @case('Intra')
                                      bg-[#1565c0]
                                      @break
                                      @case('Inter')
                                      bg-[#7209b7]
                                      @break
                                      @default
                                      @break
                                    @endswitch">
                        <p class="text-sm text-white">{{ $p['project_type'] }}</p>
                    </div>
                    <div class="flex flex-col items-center justify-center h-full ml-12">
                        <span class="flex flex-col justify-center w-full">
                            <h5 class="text-xl font-medium text-white">
                                @if ($p['dateDebut'] != null)
                                    {{ $p['dateDebut'] ? Carbon\Carbon::parse($p['dateDebut'])->format('Y') : '' }}
                                @else
                                    --
                                @endif
                            </h5>
                            <div class="inline-flex items-end gap-2">
                                <h5 class="text-4xl font-semibold text-white">
                                    @if ($p['dateDebut'] != null)
                                        {{ $p['dateDebut'] ? Carbon\Carbon::parse($p['dateDebut'])->format('d') : '' }}
                                    @else
                                        --
                                    @endif -
                                </h5>
                                <h5 class="text-xl text-white">
                                    @if ($p['dateFin'] != null)
                                        {{ $p['dateFin'] ? Carbon\Carbon::parse($p['dateFin'])->format('d') : '' }}
                                    @else
                                        --
                                    @endif
                                </h5>
                            </div>
                            <div class="inline-flex items-end gap-4">
                                <h5 class="text-xl font-semibold text-white">
                                    @if ($p['dateDebut'] != null)
                                        {{ $p['dateDebut'] ? Carbon\Carbon::parse($p['dateDebut'])->format('M') : '' }}
                                    @else
                                        --
                                    @endif
                                </h5>
                                <h5 class="text-lg text-white">
                                    @if ($p['dateFin'] != null)
                                        {{ $p['dateFin'] ? Carbon\Carbon::parse($p['dateFin'])->format('M') : '' }}
                                    @else
                                        --
                                    @endif
                                </h5>
                            </div>
                        </span>
                    </div>
                </div>
            </div>

            {{-- Module + ETP + Sessions + Heures + Apprenants --}}
            <div class="grid w-full p-2 h-full min-[350px]:col-span-6 grid-cols-subgrid">
                <div class="grid w-full grid-cols-6">
                    <div class="grid col-span-6 grid-cols-subgrid">
                        <h1 onclick="showFormation({{ $p['idModule'] }})"
                            class="text-xl font-medium text-gray-600 cursor-pointer" title="{{ $p['module_name'] }}">
                            @if (isset($p['module_name']) && $p['module_name'] != 'Default module')
                                {{ $p['module_name'] }}
                            @else
                                <span class="text-gray-600">--</span>
                            @endif
                        </h1>
                    </div>
                    <div class="grid col-span-6 grid-cols-subgrid">
                        <span class="inline-flex items-center gap-x-3">
                            @if (count($p['etp_name']) > 0 && count($p['etp_name']) <= 3)
                                @foreach ($p['etp_name'] as $etp)
                                    @if (isset($etp->etp_logo))
                                        <div onclick="showCustomer({{ $etp->idEtp }}, '/projetsForm/etp-drawer/')"
                                            class="relative w-20 h-10 capitalize bg-gray-200 cursor-pointer rounded-xl"
                                            title="{{ $etp->etp_name }}">
                                            <x-icon-badge />
                                            <img src="/img/entreprises/{{ $etp->etp_logo }}" alt="logo"
                                                class="object-cover w-full h-full rounded-xl">
                                        </div>
                                    @elseif (!isset($etp->etp_logo) && isset($etp->etp_name))
                                        <span onclick="showCustomer({{ $etp->idEtp }}, '/projetsForm/etp-drawer/')"
                                            title="{{ $etp->etp_name }}"
                                            class="relative flex items-center justify-center w-20 h-10 uppercase bg-gray-200 cursor-pointer rounded-xl">{{ $etp->etp_name[0] }}</span>
                                    @else
                                        <span class="text-gray-400">--</span>
                                    @endif
                                @endforeach
                            @endif

                            @if (count($p['etp_name']) > 0 && count($p['etp_name']) > 3)
                                @foreach ($p['etp_name'] as $etp)
                                    @if (isset($etp->etp_name))
                                        <span onclick="showCustomer({{ $etp->idEtp }}, '/projetsForm/etp-drawer/')"
                                            class="text-gray-400 capitalize">{{ $etp->etp_name }}
                                            -</span>
                                    @else
                                        <span class="text-gray-400">--</span>
                                    @endif
                                @endforeach
                            @endif
                        </span>
                    </div>
                    <div class="grid col-span-6 grid-cols-subgrid">
                        <div class="inline-flex items-center gap-4">
                            <div class="">
                                <p class="text-gray-600">{{ $p['seanceCount'] }} <span
                                        onclick="showSessions('/projetsForm/session-drawer/{{ $p['idProjet'] }}')"
                                        class="text-gray-400 underline cursor-pointer">Sessions</span></p>
                            </div>
                            <div class="">
                                <p class="text-gray-600">
                                    {{ $p['totalSessionHour'] != null ? $p['totalSessionHour'] : '0' }}
                                    <span class="text-gray-400">Heures</span>
                                </p>
                            </div>
                            @if ($p['partCount'] != null || $p['partCount'] != 0)
                                <div class="">
                                    <p class="text-gray-600">{{ $p['partCount'] }} <span
                                            class="text-gray-400">Particuliers</span></p>
                                </div>
                            @else
                                <div class="">
                                    <p class="text-gray-600">{{ $p['apprCount'] }} <a href="#"><span
                                                onclick="showApprenants('/projetsForms/apprenant-drawer/{{ $p['idProjet'] }}')"
                                                class="text-gray-400 underline cursor-pointer">Apprenants</span></a></p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Menu + Rating --}}
            <div class="grid w-full p-2 h-full min-[350px]:col-span-2 grid-cols-subgrid">
                <div class="grid items-start justify-end h-full col-span-1">
                    <div class="btn-group h-max">
                        <button type="button" title="Cliquer pour afficher le menu"
                            class="w-8 h-8 bg-[#A462A4] rounded-md hover:bg-[#A462A4]/90 duration-150 cursor-pointer shadow-sm shadow-purple-500"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <span class=""><i class="text-white fa-solid fa-bars-staggered"></i></span>
                        </button>
                        <ul class="dropdown-menu">
                            <x-dropdown-item icontype="solid" icon="eye"
                                route="{{ route('cfp.projets.show', $p['idProjet']) }}" label="Aperçu" />
                        </ul>
                    </div>
                </div>
                <div class="grid items-end justify-end col-span-1">
                    <div class="inline-flex items-center justify-end gap-1">
                        {{-- <i class="text-sm text-gray-600 fa-solid fa-star"></i> --}}
                        <p class="font-medium text-gray-500 p_note_{{ $p['idProjet'] }}">
                            {{ $p['general_note'] ? number_format($p['general_note'][0], 1, ',', ' ') : '0' }}</p>
                        <span class="text-gray-400">
                            ({{ $p['general_note'] ? $p['general_note'][1] : '0' }} avis)
                        </span>
                        <div id="raty_notation_lg_{{ $p['idProjet'] }}"
                            data-val="{{ $p['general_note'] ? $p['general_note'][0] : '0' }}"
                            class="inline-flex items-center gap-1 raty_notation_id">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bloc 2 --}}
    <div class="grid w-full h-full min-[350px]:col-span-12 md:col-span-10 grid-cols-subgrid">
        <div class="min-[350px]:grid w-full grid-cols-12 md:hidden">
            <div class="grid col-span-12 grid-cols-subgrid">
                <hr class="border-[1px] w-full border-gray-200 my-2">
                <div class="grid w-full grid-cols-6 pr-4">
                    <div class="grid col-span-5 grid-cols-subgrid">
                        @if ($p['project_description'] != null)
                            <span class="text-gray-500">
                                {{ $p['project_description'] }}
                            </span>
                        @else
                            <span class="text-gray-500">--</span>
                        @endif
                    </div>
                    <div class="grid justify-end col-span-1">
                        <div class="inline-flex items-center gap-x-2">
                            <i title="Petit déjeuner" data-bs-toggle="tooltip"
                                class="text-lg text-gray-{{ in_array(1, $p['restaurations']) ? '600' : '200' }} cursor-pointer fa-solid fa-bread-slice"></i>
                            <i title="Pause café" data-bs-toggle="tooltip"
                                class="text-lg text-gray-{{ in_array(2, $p['restaurations']) ? '600' : '200' }} cursor-pointer fa-solid fa-mug-hot"></i>
                            <i title="Déjeuner" data-bs-toggle="tooltip"
                                class="text-lg text-gray-{{ in_array(3, $p['restaurations']) ? '600' : '200' }} cursor-pointer fa-solid fa-bowl-rice"></i>
                            <i title="Pause café" data-bs-toggle="tooltip"
                                class="text-lg text-gray-{{ in_array(4, $p['restaurations']) ? '600' : '200' }} cursor-pointer fa-solid fa-mug-hot"></i>
                            <i title="Dîner" data-bs-toggle="tooltip"
                                class="text-lg text-gray-{{ in_array(5, $p['restaurations']) ? '600' : '200' }} cursor-pointer fa-solid fa-utensils"></i>
                            <i title="Bouteille d'eau" data-bs-toggle="tooltip"
                                class="text-lg text-gray-{{ in_array(6, $p['restaurations']) ? '600' : '200' }} cursor-pointer fa-solid fa-bottle-water"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid w-full col-span-12 grid-cols-subgrid">
                <hr class="border-[1px] w-full border-gray-200 my-2">
                <div class="grid w-full grid-cols-3">
                    <div class="grid col-span-1">
                        <span data-bs-toggle="tooltip"
                            class="inline-flex items-center gap-2 px-2 py-1 text-sm text-white w-[90px] justify-center 
                                    @switch($p['project_status'])
                                    @case('En préparation')
                                        bg-[#66CDAA]
                                        @break
                                    @case('Réservé')
                                        bg-[#33303D]
                                        @break
                                    @case('En cours')
                                        bg-[#1E90FF]
                                        @break
                                    @case('Terminé')
                                        bg-[#32CD32]
                                        @break
                                    @case('Annulé')
                                        bg-[#FF6347]
                                        @break
                                    @case('Reporté')
                                        bg-[#2E705A]
                                        @break
                                    @case('Planifié')
                                        bg-[#2552BA]
                                        @break
                                    @case('Cloturé')
                                        from-[#828282] to-[#828282]/50
                                        @break
                                    @default
                                    @endswitch"
                            title=" @switch($p['project_status'])
                                            @case('En préparation')
                                            Le projet de formation est en cours de préparation, avec des détails tels que le programme, les supports de formation, et la logistique en cours de finalisation.
                                            @break
                                            @case('Réservé')
                                            Réservé.
                                            @break
                                            @case('En cours')
                                            La formation a débuté et les sessions sont en train de se dérouler selon le calendrier prévu.
                                            @break
                                            @case('Terminé')
                                            Toutes les sessions de formation prévues ont été effectuées et la formation est officiellement terminée.
                                            @break
                                            @case('Annulé')
                                            Le projet de formation a été annulé avant d'avoir pu être mené à terme. Cela peut être dû à un manque de participants, des contraintes logistiques, ou d'autres raisons.
                                            @break
                                            @case('Reporté')
                                            La formation a été initialement planifiée mais a été reportée à une date ultérieure. Cela peut être dû à des contraintes de calendrier, des imprévus, ou d'autres raisons.
                                            @break
                                            @case('Planifié')
                                            Le projet de formation a été créé et les dates, lieux, et formateurs ont été déterminés, mais la formation n'a pas encore commencé.
                                            @break
                                            @case('Cloturé')
                                            Le projet de formation a été cloturé
                                            @break
                                        @endswitch">
                            {{ $p['project_status'] }}
                        </span>
                    </div>
                    <div class="grid col-span-1">
                        <span
                            class="inline-flex items-center gap-2 px-2 py-1 w-[90px] justify-center
                                      @switch($p['modalite'])
                                        @case('Présentielle')
                                          text-[#00b4d8]
                                          @break
                                        @case('En ligne')
                                          text-[#fca311]
                                          @break
                                        @case('Blended')
                                          text-[#005f73]
                                          @break
                                      
                                        @default
                                        text-[#00b4d8]
                                      @endswitch">
                            {{ $p['modalite'] }}
                        </span>
                    </div>

                    <div class="grid col-span-1">
                        <div class="inline-flex items-center">
                            <div class="w-[24px] flex justify-center items-center">
                                <i class="text-gray-400 fa-solid fa-user-graduate"></i>
                            </div>
                            <p class="flex flex-row items-center gap-2 text-gray-600">
                                @if (count($p['formateurs']) > 0)
                                    @foreach ($p['formateurs'] as $pf)
                                        @if (isset($pf->form_photo))
                                            <img class="object-cover w-10 h-10 rounded-full cursor-pointer"
                                                src="/img/formateurs/{{ $pf->form_photo }}" alt="photo"
                                                title="{{ $pf->form_name }} {{ $pf->form_firstname }}">
                                        @else
                                            <span title="{{ $pf->form_name }} {{ $pf->form_firstname }}"
                                                class="flex items-center justify-center w-10 h-10 bg-gray-200 rounded-full cursor-pointer">{{ $pf->form_initial_name }}</span>
                                        @endif
                                    @endforeach
                                @else
                                    <span class="text-gray-400">--</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid w-full col-span-12 grid-cols-subgrid">
                <hr class="border-[1px] w-full border-gray-200 my-2">
                <div class="inline-flex items-center w-full">
                    <div class="w-[24px] flex justify-center items-center">
                        <i class="text-gray-400 fa-solid fa-location-dot"></i>
                    </div>
                    <p class="text-gray-600" title="Salle">
                        @if ($p['project_type'] == 'Intra')
                            <p class="text-gray-600" title="Salle">
                                @if (isset($p['salle_name']))
                                    {{ $p['salle_name'] }}
                                @else
                                    --
                                @endif

                                @if (isset($p['salle_name']) && $p['ville'] != 'Default')
                                    @if (isset($p['salle_quartier']))
                                        {{ $p['salle_quartier'] }} -
                                    @else
                                        --
                                    @endif
                                    @if (isset($p['ville']) && $p['ville'] != 'Default')
                                        {{ $p['ville'] }} -
                                    @else
                                        --
                                    @endif
                                    @if (isset($p['salle_code_postal']))
                                        {{ $p['salle_code_postal'] }}
                                    @else
                                        --
                                    @endif
                                @else
                                    <p></p>
                                @endif
                            </p>
                        @else
                            Non renseigné
                        @endif
                    </p>
                </div>
            </div>
            <div class="grid w-full col-span-12 grid-cols-subgrid">
                <hr class="border-[1px] w-full border-gray-200 my-2">
                <div class="grid w-full grid-cols-3">
                    <div class="inline-flex items-center w-full">
                        <div class="w-[24px] flex justify-center items-center">
                            <i class="text-gray-400 fa-solid fa-folder-tree"></i>
                        </div>
                        <p class="font-medium text-gray-500">7 Documents</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="min-[350px]:hidden md:grid grid-cols-10 w-full">
            <div class="grid w-full md:col-span-4 lg:col-span-2 grid-cols-subgrid">
                <div class="grid w-full col-span-4 grid-cols-subgrid">
                    <h1 onclick="showFormation({{ $p['idModule'] }})"
                        class="text-xl font-medium text-gray-600 cursor-pointer" title="{{ $p['module_name'] }}">
                        @if (isset($p['module_name']) && $p['module_name'] != 'Default module')
                            {{ $p['module_name'] }}
                        @else
                            <span class="text-gray-600">--</span>
                        @endif
                    </h1>
                </div>

                <div class="grid w-full col-span-4 grid-cols-subgrid">
                    <span class="inline-flex items-center gap-x-3">
                        @if (count($p['etp_name']) > 0 && count($p['etp_name']) <= 3)
                            @foreach ($p['etp_name'] as $etp)
                                @if (isset($etp->etp_logo))
                                    <div onclick="showCustomer({{ $etp->idEtp }}, '/projetsForm/etp-drawer/')"
                                        class="relative w-20 h-10 capitalize bg-gray-200 cursor-pointer rounded-xl"
                                        title="{{ $etp->etp_name }}">
                                        <x-icon-badge />
                                        <img src="/img/entreprises/{{ $etp->etp_logo }}" alt="logo"
                                            class="object-cover w-full h-full rounded-xl">
                                    </div>
                                @elseif (!isset($etp->etp_logo) && isset($etp->etp_name))
                                    <span onclick="showCustomer({{ $etp->idEtp }}, '/projetsForm/etp-drawer/')"
                                        title="{{ $etp->etp_name }}"
                                        class="relative flex items-center justify-center w-20 h-10 uppercase bg-gray-200 cursor-pointer rounded-xl">{{ $etp->etp_name[0] }}</span>
                                @else
                                    <span class="text-gray-400">--</span>
                                @endif
                            @endforeach
                        @endif

                        @if (count($p['etp_name']) > 0 && count($p['etp_name']) > 3)
                            @foreach ($p['etp_name'] as $etp)
                                @if (isset($etp->etp_name))
                                    <span onclick="showCustomer({{ $etp->idEtp }}, '/projetsForm/etp-drawer/')"
                                        class="text-gray-400 capitalize">{{ $etp->etp_name }}
                                        -</span>
                                @else
                                    <span class="text-gray-400">--</span>
                                @endif
                            @endforeach
                        @endif
                    </span>
                </div>

                <div class="w-full col-span-4 md:grid lg:hidden grid-cols-subgrid">
                    <div class="inline-flex items-center w-full gap-4">
                        <div class="">
                            <p class="text-gray-600">{{ $p['seanceCount'] }} <span
                                    onclick="showSessions('/projetsForm/session-drawer/{{ $p['idProjet'] }}')"
                                    class="text-gray-400 underline cursor-pointer">Sessions</span></p>
                        </div>
                        <div class="">
                            <p class="text-gray-600">
                                {{ $p['totalSessionHour'] != null ? $p['totalSessionHour'] : '0' }}
                                <span class="text-gray-400">Heures</span>
                            </p>
                        </div>
                        @if ($p['partCount'] != null || $p['partCount'] != 0)
                            <div class="">
                                <p class="text-gray-600">{{ $p['partCount'] }} <span
                                        class="text-gray-400">Particuliers</span></p>
                            </div>
                        @else
                            <div class="">
                                <p class="text-gray-600">{{ $p['apprCount'] }} <span
                                        onclick="showApprenants('/projetsForm/apprenants-drawer/{{ $p['idProjet'] }}')"
                                        class="text-gray-400 underline cursor-pointer">Apprenants</span></p>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

            <div class="w-full lg:grid min-[350px]:hidden lg:col-span-3 gap-y-0 grid-cols-subgrid">
                <div class="grid w-full col-span-3 grid-cols-subgrid">
                    <div class="flex flex-col items-start w-full">
                        <div class="w-[24px] flex justify-center items-center">
                            <p class="text-gray-400">Lieu</p>
                        </div>
                        <p class="text-gray-600" title="Salle 05 - Hotel Radison Blue Andraharo">
                            @if ($p['project_type'] == 'Intra')
                                <p class="text-gray-600" title="Salle 05 - Hotel Radison Blue Andraharo">
                                    @if (isset($p['salle_name']))
                                        {{ $p['salle_name'] }}
                                    @else
                                        --
                                    @endif

                                    @if (isset($p['salle_name']) && $p['ville'] != 'Default')
                                        @if (isset($p['salle_quartier']))
                                            {{ $p['salle_quartier'] }} -
                                        @else
                                            --
                                        @endif
                                        @if (isset($p['ville']) && $p['ville'] != 'Default')
                                            {{ $p['ville'] }} -
                                        @else
                                            --
                                        @endif
                                        @if (isset($p['salle_code_postal']))
                                            {{ $p['salle_code_postal'] }}
                                        @else
                                            --
                                        @endif
                                    @else
                                        <p></p>
                                    @endif

                                </p>
                            @else
                                --
                            @endif
                        </p>
                    </div>
                </div>

                <div class="grid w-full col-span-3 grid-cols-subgrid">
                    <div class="inline-flex items-center w-full gap-4">
                        <div class="">
                            <p class="text-gray-600">{{ $p['seanceCount'] }} <span
                                    onclick="showSessions('/projetsForm/session-drawer/{{ $p['idProjet'] }}')"
                                    class="text-gray-400 underline cursor-pointer">Sessions</span></p>
                        </div>
                        <div class="">
                            <p class="text-gray-600">
                                {{ $p['totalSessionHour'] != null ? $p['totalSessionHour'] : '0' }}
                                <span class="text-gray-400">Heures</span>
                            </p>
                        </div>
                        @if ($p['partCount'] != null || $p['partCount'] != 0)
                            <div class="">
                                <p class="text-gray-600">{{ $p['partCount'] }} <span
                                        class="text-gray-400">Particuliers</span></p>
                            </div>
                        @else
                            <div class="">
                                <p class="text-gray-600">{{ $p['apprCount'] }} <span
                                        onclick="showApprenants('/projetsForm/apprenants-drawer/{{ $p['idProjet'] }}')"
                                        class="text-gray-400 underline cursor-pointer">Apprenants</span></p>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

            <div class="grid w-full md:col-span-4 lg:col-span-3 gap-y-0 grid-cols-subgrid">
                <div class="grid w-full md:col-span-4 lg:col-span-3">
                    <div class="grid w-full grid-cols-2">
                        <div class="grid w-full gap-2 md:col-span-2 lg:col-span-1 grid-cols-subgrid">
                            <div class="flex flex-col w-full">
                                <div class="flex w-full lg:flex-col md:flex-row lg:items-start md:items-center">
                                    <div class="md:hidden lg:block w-[24px] flex flex-col">
                                        <p class="text-gray-400">Statut</p>
                                    </div>
                                    <span data-bs-toggle="tooltip"
                                        class="inline-flex items-center gap-2 px-2 py-1 text-sm text-white w-[90px] justify-center 
                                          @switch($p['project_status'])
                                            @case('En préparation')
                                              bg-[#66CDAA]
                                              @break
                                            @case('Réservé')
                                              bg-[#33303D]
                                              @break
                                            @case('En cours')
                                              bg-[#1E90FF]
                                              @break
                                            @case('Terminé')
                                              bg-[#32CD32]
                                              @break
                                            @case('Annulé')
                                              bg-[#FF6347]
                                              @break
                                            @case('Reporté')
                                              bg-[#2E705A]
                                              @break
                                            @case('Planifié')
                                              bg-[#2552BA]
                                              @break  
                                            @case('Cloturé')
                                              bg-[#828282]
                                              @break  
                                            @default
                                              @break
                                          @endswitch"
                                        title=" @switch($p['project_status'])
                                          @case('En préparation')
                                              Le projet de formation est en cours de préparation, avec des détails tels que le programme, les supports de formation, et la logistique en cours de finalisation.
                                            @break
                                          @case('Réservé')
                                              Réservé.
                                            @break
                                          @case('En cours')
                                              La formation a débuté et les sessions sont en train de se dérouler selon le calendrier prévu.
                                            @break
                                          @case('Terminé')
                                              Toutes les sessions de formation prévues ont été effectuées et la formation est officiellement terminée.
                                          @break
                                          @case('Annulé')
                                              Le projet de formation a été annulé avant d'avoir pu être mené à terme. Cela peut être dû à un manque de participants, des contraintes logistiques, ou d'autres raisons.
                                              @break
                                          @case('Reporté')
                                              La formation a été initialement planifiée mais a été reportée à une date ultérieure. Cela peut être dû à des contraintes de calendrier, des imprévus, ou d'autres raisons.
                                              @break
                                          @case('Planifié')
                                              Le projet de formation a été créé et les dates, lieux, et formateurs ont été déterminés, mais la formation n'a pas encore commencé.
                                              @break
                                          @case('Cloturé')
                                              Le projet de formation a été cloturé.
                                              @break
                                          @endswitch ">
                                        {{ $p['project_status'] }}
                                    </span>
                                </div>
                            </div>

                            <div class="w-full md:block lg:hidden">
                                <div class="inline-flex items-center w-full">
                                    <div class="w-[24px] flex justify-center items-center">
                                        <i class="text-gray-400 fa-solid fa-location-dot"></i>
                                    </div>
                                    <p class="text-gray-600" title="Salle 05 - Hotel Radison Blue Andraharo">
                                        @if ($p['project_type'] == 'Intra')
                                            <p class="text-gray-600" title="Salle 05 - Hotel Radison Blue Andraharo">
                                                @if (isset($p['salle_name']))
                                                    {{ $p['salle_name'] }}
                                                @else
                                                    --
                                                @endif

                                                @if (isset($p['salle_name']) && $p['ville'] != 'Default')
                                                    @if (isset($p['salle_quartier']))
                                                        {{ $p['salle_quartier'] }} -
                                                    @else
                                                        --
                                                    @endif
                                                    @if (isset($p['ville']) && $p['ville'] != 'Default')
                                                        {{ $p['ville'] }} -
                                                    @else
                                                        --
                                                    @endif
                                                    @if (isset($p['salle_code_postal']))
                                                        {{ $p['salle_code_postal'] }}
                                                    @else
                                                        --
                                                    @endif
                                                @else
                                                    <p></p>
                                                @endif

                                            </p>
                                        @else
                                            --
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="w-full">
                                <div class="flex w-full lg:flex-col md:flex-row lg:items-start md:items-center">
                                    <div class="md:flex lg:hidden w-[24px] flex-col items-center justify-center">
                                        <i class="text-gray-400 fa-solid fa-user-graduate"></i>
                                    </div>
                                    <div class="md:hidden lg:block w-[24px] flex flex-col">
                                        <p class="text-gray-400">Formateur(s)</p>
                                    </div>
                                    <p class="flex flex-row items-center gap-2 text-gray-600">
                                        @if (count($p['formateurs']) > 0)
                                            @foreach ($p['formateurs'] as $pf)
                                                @if (isset($pf->form_photo))
                                                    <img onclick="viewMiniCV({{ $pf->idFormateur }})"
                                                        class="object-cover w-10 h-10 rounded-full cursor-pointer"
                                                        src="/img/formateurs/{{ $pf->form_photo }}" alt="photo"
                                                        title="{{ $pf->form_name }} {{ $pf->form_firstname }}">
                                                @else
                                                    <span onclick="viewMiniCV({{ $pf->idFormateur }})"
                                                        class="flex items-center justify-center w-10 h-10 bg-gray-200 rounded-full cursor-pointer"
                                                        title="{{ $pf->form_name }} {{ $pf->form_firstname }}">{{ $pf->form_initial_name }}</span>
                                                @endif
                                            @endforeach
                                        @else
                                            <span class="text-gray-400">--</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="grid w-full gap-2 md:col-span-2 lg:col-span-1 grid-cols-subgrid">
                            <div class="inline-flex items-center w-full">
                                <div class="w-[24px] flex justify-center items-center">
                                    <i class="text-gray-400 fa-solid fa-folder-tree"></i>
                                </div>
                                <p class="font-medium text-gray-500">7 Documents</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid w-full p-2 md:col-span-2 lg:col-span-2 grid-cols-subgrid">
                <div class="flex items-center justify-end w-full h-full gap-1">
                    <span
                        class="inline-flex items-center gap-2 px-2 py-1 w-[90px] justify-center
                                        @switch($p['modalite'])
                                          @case('Présentielle')
                                            text-[#00b4d8]
                                            @break
                                          @case('En ligne')
                                            text-[#fca311]
                                            @break
                                          @case('Blended')
                                            text-[#005f73]
                                            @break
                                        
                                          @default
                                          text-[#00b4d8]
                                        @endswitch">
                        {{ $p['modalite'] }}
                    </span>
                    <div class="btn-group h-max">
                        <button type="button" title="Cliquer pour afficher le menu"
                            class="w-8 h-8 bg-[#A462A4] rounded-md hover:bg-[#A462A4]/90 duration-150 cursor-pointer shadow-sm shadow-purple-500"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <span class=""><i class="text-white fa-solid fa-bars-staggered"></i></span>
                        </button>
                        <ul class="dropdown-menu">
                            <x-dropdown-item icontype="solid" icon="eye"
                                route="{{ route('projetForms.detailForm', $p['idProjet']) }}" label="Aperçu" />
                            {{-- @if ($p['project_status'] != 'Terminé') --}}
                        </ul>
                    </div>
                </div>

                <div class="grid items-end justify-end w-full col-span-1">
                    <div class="inline-flex items-center justify-end gap-1">
                        {{-- <i class="text-sm text-gray-600 fa-solid fa-star"></i> --}}
                        <p class="font-medium text-gray-500 p_note_{{ $p['idProjet'] }}">
                            {{ $p['general_note'] ? number_format($p['general_note'][0], 1, ',', ' ') : '0' }}</p>
                        <span class="text-gray-400">
                            ({{ $p['general_note'] ? $p['general_note'][1] : '0' }} avis)
                        </span>
                        <div id="raty_notation_{{ $p['idProjet'] }}"
                            data-val="{{ $p['general_note'] ? $p['general_note'][0] : '0' }}"
                            class="inline-flex items-center gap-1 raty_notation_id">
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid w-full col-span-10 grid-cols-subgrid">
                <hr class="border-[1px] w-full border-gray-200 my-3">
                <div class="grid w-full grid-cols-6 pr-4">
                    <div class="grid col-span-5 grid-cols-subgrid">
                        @if ($p['project_description'] != null)
                            <span class="text-gray-500">
                                {{ $p['project_description'] }}
                            </span>
                        @else
                            <span class="text-gray-500">--</span>
                        @endif
                    </div>
                    <div class="grid justify-end col-span-1">
                        <div class="inline-flex items-center gap-x-2">
                            <i title="Petit déjeuner" data-bs-toggle="tooltip"
                                class="text-lg {{ in_array(1, $p['restaurations']) ? 'text-[#0d6efd]' : 'text-gray-200' }} cursor-pointer fa-solid fa-bread-slice"></i>
                            <i title="Pause café" data-bs-toggle="tooltip"
                                class="text-lg {{ in_array(2, $p['restaurations']) ? 'text-[#000]' : 'text-gray-200' }} cursor-pointer fa-solid fa-mug-hot"></i>
                            <i title="Déjeuner" data-bs-toggle="tooltip"
                                class="text-lg {{ in_array(3, $p['restaurations']) ? 'text-[#ffc107]' : 'text-gray-200' }} cursor-pointer fa-solid fa-bowl-rice"></i>
                            <i title="Pause café" data-bs-toggle="tooltip"
                                class="text-lg {{ in_array(4, $p['restaurations']) ? 'text-[#000]' : 'text-gray-200' }} cursor-pointer fa-solid fa-mug-hot"></i>
                            <i title="Dîner" data-bs-toggle="tooltip"
                                class="text-lg {{ in_array(5, $p['restaurations']) ? 'text-[#dc3545]' : 'text-gray-200' }} cursor-pointer fa-solid fa-utensils"></i>
                            <i title="Bouteille d'eau" data-bs-toggle="tooltip"
                                class="text-lg {{ in_array(6, $p['restaurations']) ? 'text-[#0dcaf0]' : 'text-gray-200' }} cursor-pointer fa-solid fa-bottle-water"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
