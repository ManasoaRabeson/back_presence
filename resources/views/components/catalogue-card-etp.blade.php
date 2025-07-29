<li class="col-span-1 bg-white shadow-xl card glass w-96">
    <figure class="h-[220px]">
        @if (isset($m->module_image))
            <img src="{{ $endpoint }}/{{ $bucket }}/img/modules/{{ $m->module_image }}" alt="image"
                class="w-full h-full object-fit">
        @else
            <img src="{{ asset('img/logo/Logo_mark.svg') }}" alt="logo"
                class="w-full h-full opacity-50 object-fit grayscale">
        @endif
    </figure>
    <div class="card-body">
        <h2 class="card-title">{{ $m->moduleName ?? '' }}
            @if ($m->module_is_complete == 0)
                <div class="badge badge-warning badge-lg">Nouveau</div>
            @endif
        </h2>
        <p class="text-slate-500">
            {{ $m->nomDomaine ?? 'Domaine non renseigné' }}</p>
        <p class="text-slate-500"><i class="text-sm fa-regular fa-clock"></i>
            {{ $m->dureeJ }} jours ({{ $m->dureeH }} heures)</p>

        <div class="justify-end card-actions">
            <a href="{{ route('etp.modules.show', $m->idModule) }}" class="btn btn-outline btn-primary">Voir les
                détails</a>
            @if ($m->moduleStatut == 1)
                <button class="btn btn-ghost" data-bs-toggle="tooltip" title="Mettre en préparation"
                    onclick="openMenuDialog('private', {{ $m->idModule }})">
                    <i class="fa-solid fa-lock"></i>
                </button>
            @endif
            @if ($m->moduleStatut == 0)
                <div class="dropdown">
                    <button tabindex="0" class="btn">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                    </button>

                    <ul tabindex="0" class="menu dropdown-content rounded-box bg-white !w-max p-2 shadow">
                        <li onclick="openMenuDialog('public', {{ $m->idModule }})"><a>Rendre public</a></li>
                        <li onclick="openMenuDialog('trash', {{ $m->idModule }})"><a>Supprimer</a></li>
                    </ul>
                </div>
            @endif
            @if ($m->moduleStatut == 2)
                <div class="dropdown">
                    <button tabindex="0" class="btn">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                    </button>

                    <ul tabindex="0" class="menu dropdown-content rounded-box bg-white !w-max p-2 shadow">
                        <li onclick="openMenuDialog('restore', {{ $m->idModule }})"><a>Restaurer</a></li>
                        <li onclick="openMenuDialog('delete', {{ $m->idModule }})"><a>Supprimer</a></li>
                    </ul>
                </div>
            @endif
        </div>
    </div>
</li>
