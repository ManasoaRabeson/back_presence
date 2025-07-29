<li class="w-full col-span-1 bg-white shadow-xl card">
    <figure class="h-[170px] bg-slate-100">

        @if (!empty($m['module_image']))
            <img src="{{ $endpoint }}/{{ $bucket }}/img/modules/{{ $m['module_image'] }}" alt="image"
                class="w-full h-full object-fit">
        @else
            <img src="{{ asset('img/logo/Logo_mark.svg') }}" alt="logo"
                class="w-16 h-full opacity-50 object-fit grayscale">
        @endif

    </figure>
    <div class="card-body !pb-0">
        <h2 class="card-title">{{ $m['module_name'] ?? '' }}
            @if ($m['module_is_complete'] == 0)
                <div class="badge badge-warning badge-lg">Nouveau</div>
            @endif
        </h2>
        <p class="text-slate-500"><i class="text-sm fa-regular fa-clock"></i>
            {{ $m['dureeJ'] != 'null' ? $m['dureeJ'] : '' }} jours
            ({{ $m['dureeH'] != 'null' ? $m['dureeH'] : '' }}heures)</p>
        <p class="cursor-pointer text-slate-500 w-max" data-bs-toggle="tooltip" title="Niveau du cours">
            @if ($m['module_level_name'] == "Fondamentaux")
                <i class="fa-solid fa-signal-bars-weak"></i>
            @elseif ($m['module_level_name'] == 'Intermédiaire')
                <i class="fa-solid fa-signal-bars-fair"></i>
            @elseif ($m['module_level_name'] == 'Avancée')
                <i class="fa-solid fa-signal-bars-good"></i>
            @else
                <i class="fa-solid fa-signal-bars"></i>
            @endif {{ $m['module_level_name'] }}</p>
        <p class="text-slate-500">
            Ar
            {{ $m['prix'] != 'null' ? number_format($m['prix'], 2, ',', ' ') : '' }}

            @if ($m['moduleStatut'] == 0 or $m['moduleStatut'] == 2)
                <div class="flex items-center justify-end border-t-[1px] mt-2 gap-4">
                    <p class="mt-2 text-slate-500">Quality
                    </p>

                    <div class="w-full">
                        @if ($m['testSumQuality'] >= 0 && $m['testSumQuality'] <= 1)
                            <div class="w-full mt-2 ">
                                <div class="py-1 text-xs leading-none text-center text-white bg-red-500 rounded-md"
                                    data-bs-toggle="tooltip" title="15% pas assez pour mettre en public!"
                                    style="width: 15%">
                                    15%
                                </div>
                            </div>
                        @endif
                        @if ($m['testSumQuality'] == 2)
                            <div class="w-full mt-2 ">
                                <div class="py-1 text-xs leading-none text-center text-white bg-red-500 rounded-md"
                                    data-bs-toggle="tooltip" title="25% pas suffisant pour mettre en public!"
                                    style="width: 25%">
                                    25%
                                </div>
                            </div>
                        @endif
                        @if ($m['testSumQuality'] == 3)
                            <div class="w-full mt-2 ">
                                <div class="py-1 text-xs leading-none text-center text-white bg-red-500 rounded-md"
                                    data-bs-toggle="tooltip" title="45% pas assez suffisant pour mettre en public!"
                                    style="width: 45%">
                                    45%
                                </div>
                            </div>
                        @endif

                        @if ($m['testSumQuality'] == 4)
                            <div class="w-full mt-2 ">
                                <div class="py-1 text-xs leading-none text-center text-white bg-orange-500 rounded-md"
                                    data-bs-toggle="tooltip" title="65% suffisant pour mettre en public!"
                                    style="width: 65%">
                                    65%
                                </div>
                            </div>
                        @endif

                        @if ($m['testSumQuality'] == 5)
                            <div class="w-full mt-2 ">
                                <div class="py-1 text-xs leading-none text-center text-white bg-blue-500 rounded-md"
                                    data-bs-toggle="tooltip" title="plus de 85% peut mettre en public!"
                                    style="width: 85%">
                                    85%
                                </div>
                            </div>
                        @endif

                        @if ($m['testSumQuality'] == 6)
                            <div class="w-full mt-2 ">
                                <div class="py-1 text-xs leading-none text-center text-white bg-purple-500 rounded-md"
                                    data-bs-toggle="tooltip" title="peut mettre en public!" style="width: 100%">
                                    100%
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

            @endif

        <p class="text-slate-500"> <i class="fa-sharp fa-solid fa-users"></i> {{ $m['totalFormed'] }}  formés</p>

        <div class="flex items-center justify-end border-t-[1px]">
            <a href="{{ route('cfp.modules.show', $m['idModule']) }}" class="btn btn-ghost" data-bs-toggle="tooltip"
                title="Voir les détails">
                <i class="fa-solid fa-eye"></i>
            </a>
            @if ($m['moduleStatut'] == 1)
                <button class="btn btn-ghost" data-bs-toggle="tooltip" title="Mettre en préparation"
                    onclick="openMenuDialog('private', {{ $m['idModule'] }})">
                    <i class="fa-solid fa-gear"></i>
                </button>
                <button class="btn btn-ghost" data-bs-toggle="tooltip" title="Mettre en corbeille"
                    onclick="openMenuDialog('trash', {{ $m['idModule'] }})">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            @endif
            @if ($m['moduleStatut'] == 0)
                <button class="btn btn-ghost" data-bs-toggle="tooltip" title="Mettre en public"
                    onclick="openMenuDialog('public', {{ $m['idModule'] }})">
                    <i class="fa-solid fa-earth-americas"></i>
                </button>
                <button class="btn btn-ghost" data-bs-toggle="tooltip" title="Mettre en corbeille"
                    onclick="openMenuDialog('trash', {{ $m['idModule'] }})">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
                {{-- <div class="dropdown">
                    <button tabindex="0" class="btn">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                    </button>

                    <ul tabindex="0" class="menu dropdown-content rounded-box bg-white !w-max p-2 shadow">
                        <li onclick="openMenuDialog('public', {{ $m['idModule'] }})"><a>Rendre public</a></li>
                        <li onclick="openMenuDialog('trash', {{ $m['idModule'] }})"><a>Supprimer</a></li>
                    </ul>
                </div> --}}
            @endif
            @if ($m['moduleStatut'] == 2)
                <button class="btn btn-ghost" data-bs-toggle="tooltip" title="Restaurer"
                    onclick="openMenuDialog('restore', {{ $m['idModule'] }})">
                    <i class="fa-solid fa-arrows-rotate-reverse"></i>
                </button>
                <button class="btn btn-ghost" data-bs-toggle="tooltip" title="Supprimer définitivement"
                    onclick="openMenuDialog('delete', {{ $m['idModule'] }})">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
                {{-- <div class="dropdown">
                    <button tabindex="0" class="btn">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                    </button>

                    <ul tabindex="0" class="menu dropdown-content rounded-box bg-white !w-max p-2 shadow">
                        <li onclick="openMenuDialog('restore', {{ $m['idModule'] }})"><a>Restaurer</a></li>
                        <li onclick="openMenuDialog('delete', {{ $m['idModule'] }})"><a>Supprimer</a></li>
                    </ul>
                </div> --}}
            @endif
        </div>
    </div>
</li>
