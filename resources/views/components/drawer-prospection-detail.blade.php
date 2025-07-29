@php
    $item ??= null;
    $endpoint = 'https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg';
@endphp
<div class="offcanvas offcanvas-end !w-[40em]" tabindex="-1" id="offcanvasProspectionDetail"
    aria-labelledby="offcanvasProspectionDetail">
    <div class="flex flex-col w-full">
        <div class="inline-flex items-center justify-between w-full px-4 py-2 bg-slate-50">
            <p class="text-lg font-medium text-slate-500">
                Détails
            </p>
            <a data-bs-toggle="offcanvas" href="#offcanvasProspectionDetail" class="btn btn-sm btn-square btn-ghost">
                <i class="text-slate-500 fa-solid fa-xmark"></i>
            </a>
        </div>
    </div>

    <div class="flex flex-col gap-3 w-full p-4">
        <div class="grid grid-cols-1 gap-2">
            <p class="menu-title text-lg text-slate-700 !font-normal"><i class="fa-solid fa-building text-sm"></i>
                A propos de l'entreprise</p>

            <div class="inline-flex items-start gap-2">
                <div class="avatar">
                    <div class="w-20 h-16 rounded-xl flex items-center justify-center">
                        @if (isset($item['etp_logo']))
                            <img src="{{ $endpoint }}/img/entreprises/{{ $item['etp_logo'] }}" />
                        @else
                            <span class="flex items-center justify-center bg-slate-200 h-full w-full">
                                <i class="fa-solid fa-building text-slate-700 text-lg"></i>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="flex flex-col">
                    <h2 class="card-title line-clamp-1">{{ $item['etp_name'] ?? '' }}</h2>
                    <p class="text-slate-500">{{ $item['ref_name'] ?? '' }} {{ $item['ref_firstName'] ?? '' }}</p>
                    <p class="text-slate-500">{{ $item['etp_email'] ?? '' }}</p>
                    <p class="text-slate-500">{{ $item['etp_phone'] ?? '' }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-2">
            <p class="menu-title text-lg text-slate-700 !font-normal"><i class="fa-solid fa-puzzle-piece text-sm"></i>
                Formation demandée</p>

            <div class="inline-flex items-start gap-2">
                <div class="avatar">
                    <div class="w-20 h-16 rounded-xl">
                        <img src="{{ $endpoint }}/img/modules/{{ $item['cours_img'] ?? '' }}" />
                    </div>
                </div>
                <div class="flex flex-col">
                    <h2 class="card-title line-clamp-2">{{ $item['cours_name'] ?? '' }}</h2>
                    <p class="text-slate-500">{{ $item['nbPersonne'] ?? 0 }}</p>
                    <p class="text-slate-500">à {{ $item['ville'] ?? '' }} prévue le {{ $item['dateDeb'] ?? '' }} -
                        {{ $item['dateFin'] ?? '' }}</p>
                    <p class="text-lg font-medium"><span class="text-slate-500 font-normal">Prix :</span>
                        {{ $item['prix'] ?? 'Pas de prix' }}
                    </p>
                </div>
            </div>
        </div>

        <span>

            <p class="menu-title text-lg text-slate-700 !font-normal"><i class="fa-solid fa-info-circle text-sm"></i>
                Autres informations</p>

            <label class="w-full">
                <div class="label">
                    <p class="text-slate-500">Statut</p>
                </div>
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
            </label>

            <label class="w-full">
                <div class="label">
                    <p class="text-slate-500">Source</p>
                </div>
                <p>{{ $item['source'] ?? 'Source inconnue' }}</p>
            </label>

            <label class="w-full">
                <div class="label">
                    <p class="text-slate-500">Remarque</p>
                </div>
                <p>{{ $item['remarque'] ?? 'Acune remarque' }}</p>
            </label>
        </span>
    </div>
</div>
