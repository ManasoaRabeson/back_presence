<li class="w-full col-span-1 bg-white shadow-xl card">
    <figure class="h-[170px] bg-slate-100">
        @if (!empty($m->module_image))
            <img src="{{ $endpoint }}/{{ $bucket }}/img/modules/{{ $m->module_image }}" alt="image"
                class="w-full h-full object-fit">
        @else
            <img src="{{ asset('img/logo/Logo_mark.svg') }}" alt="logo"
                class="w-16 h-full opacity-50 object-fit grayscale">
        @endif
    </figure>
    <div class="card-body !pb-0">
        <h2 class="card-title">{{ $m->moduleName ?? '' }}
            @if ($m->module_is_complete == 0)
                <div class="badge badge-warning badge-lg">Nouveau</div>
            @endif
        </h2>
        <p class="text-slate-500"><i class="text-sm fa-regular fa-clock"></i>
            {{ $m->dureeJ != 'null' ? $m->dureeJ : '' }} jours
            ({{ $m->dureeH != 'null' ? $m->dureeH : '' }} heures)
        </p>
        <p class="cursor-pointer text-slate-500 w-max" data-bs-toggle="tooltip" title="Niveau du cours"><i
                class="text-sm fa-solid fa-medal"></i> {{ $m->module_level_name }}</p>
        <p class="text-slate-500"><i class="text-sm fa-regular fa-dollar"></i>
            Ar {{ $m->prix != 'null' ? number_format($m->prix, 2, ',', ' ') : '' }}
        </p>

        @if ($m->moduleStatut == 0)
            <div class="flex items-center justify-end border-t-[1px] mt-2 gap-4">
                <p class="mt-2 text-slate-500">Quality</p>
                <div class="w-full">
                    @if ($m->testSum <= 2)
                        <div class="w-full mt-2">
                            <div class="py-1 text-xs leading-none text-center text-white bg-red-500 rounded-md"
                                style="width: 45%">
                                45%
                            </div>
                        </div>
                    @elseif ($m->testSum == 3)
                        <div class="w-full mt-2">
                            <div class="py-1 text-xs leading-none text-center text-white bg-orange-500 rounded-md"
                                style="width: 65%">
                                65%
                            </div>
                        </div>
                    @elseif ($m->testSum == 4)
                        <div class="w-full mt-2">
                            <div class="py-1 text-xs leading-none text-center text-white bg-blue-500 rounded-md"
                                style="width: 85%">
                                85%
                            </div>
                        </div>
                    @elseif ($m->testSum == 5)
                        <div class="w-full mt-2">
                            <div class="py-1 text-xs leading-none text-center text-white bg-purple-500 rounded-md"
                                style="width: 100%">
                                100%
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <div class="flex items-center justify-end border-t-[1px]">
            <a href="{{ route('cfp.modules.show', $m->idModule) }}" class="btn btn-ghost" data-bs-toggle="tooltip"
                title="Voir les détails">
                <i class="fa-solid fa-eye"></i>
            </a>
            @if ($m->moduleStatut == 1)
                <button class="btn btn-ghost" data-bs-toggle="tooltip" title="Mettre en préparation"
                    onclick="openMenuDialog('private', {{ $m->idModule }})">
                    <i class="fa-solid fa-gear"></i>
                </button>
                <button class="btn btn-ghost" data-bs-toggle="tooltip" title="Mettre en corbeille"
                    onclick="openMenuDialog('trash', {{ $m->idModule }})">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            @elseif ($m->moduleStatut == 0)
                <button class="btn btn-ghost" data-bs-toggle="tooltip" title="Mettre en public"
                    onclick="openMenuDialog('public', {{ $m->idModule }})">
                    <i class="fa-solid fa-earth-americas"></i>
                </button>
                <button class="btn btn-ghost" data-bs-toggle="tooltip" title="Mettre en corbeille"
                    onclick="openMenuDialog('trash', {{ $m->idModule }})">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            @elseif ($m->moduleStatut == 2)
                <button class="btn btn-ghost" data-bs-toggle="tooltip" title="Restaurer"
                    onclick="openMenuDialog('restore', {{ $m->idModule }})">
                    <i class="fa-solid fa-arrows-rotate-reverse"></i>
                </button>
                <button class="btn btn-ghost" data-bs-toggle="tooltip" title="Supprimer définitivement"
                    onclick="openMenuDialog('delete', {{ $m->idModule }})">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            @endif
        </div>
    </div>
</li>
