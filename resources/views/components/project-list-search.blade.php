<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
    @foreach ($projects as $index => $project)
        <div class="card rounded-box relative w-full h-[385px] shadow-xl">
            <figure
                class="w-full h-[185px] bg-slate-50 rounded-box rounded-b-none overflow-hidden flex items-center justify-center relative">
                @if (isset($project['project']->module_image))
                    <img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/modules/{{ $project['project']->module_image }}"
                        alt="" class="w-full object-fill h-full" loading="lazy" />
                @else
                    <i class="fa-solid fa-image text-3xl text-slate-400"></i>
                @endif

                @if (isset($project['project']->logo_cfp))
                    <div
                        class="absolute rounded-md overflow-hidden top-0 left-0 px-4 py-2 text-white mt-2 mr-1 w-40 transition duration-500 ease-in-out">
                        <img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/entreprises/{{ $project['project']->logo_cfp }}"
                            alt="" class="inset-0 w-full h-full rounded-md object-cover" loading="lazy" />
                    </div>
                @endif
            </figure>
            <div class="card-body">
                <span class="flex flex-col gap-1">
                    <a title="{{ $project['project']->moduleName }}"
                        href="/formation/detail/{{ $project['project']->idModule }}"
                        class="card-title text-xl line-clamp-1 hover:underline underline-offset-4">{{ $project['project']->moduleName }}</a>
                    <div class="w-full flex gap-2 items-center" id="note_{{ $index }}">
                        <div id="raty_{{ $index }}" class="raty_notation text-left inline-flex"
                            data-average="{{ $project['note']['average'] }}">
                        </div>
                        <p class="text-base">{{ number_format($project['note']['average'], 2, '.', '') }}
                            <span class="text-gray-500 ">({{ $project['note']['totalEmployees'] }}
                                avis)</span>
                        </p>
                    </div>
                </span>
                <span class="flex flex-col">
                    <p class="text-xs italic"><strong>{{ $project['project']->cfpName }}</strong></p>
                    <p id="description_{{ $index }}" class="line-clamp-2 text-base text-slate-500">
                        {{ $project['project']->description ?? 'Aucune description' }}</p>
                    <span class="mt-2 text-slate-500">
                        <p class="text-base"><i class="fa-regular fa-clock mr-2"></i>{{ $project['project']->dureeJ }}
                            jours
                            |
                            {{ $project['project']->dureeH }}
                            heures</p>
                        <p class="text-base"><i class="fa-solid fa-medal mr-2"></i>
                            {{ $project['project']->level_name }}</p>
                        @if (isset($project['project']->prix))
                            <p class="text-base"><i class="fa-regular fa-money-bill-1 mr-2"></i>A partir de
                                {{ number_format($project['project']->prix, 2, ',', ' ') }} Ar</p>
                        @else
                            <p class="text-base"><i class="fa-regular fa-money-bill-1 mr-2"></i>Pour connaître nos
                                tarifs, contactez-nous.</p>
                        @endif
                    </span>
                </span>
            </div>
            <div class="absolute w-full mx-auto flex justify-center -bottom-3">
                <div class="card-actions justify-center">
                    <span class="flex flex-row items-center gap-2">
                        <a href="/demande_devis/{{ $project['project']->idCustomer }}/{{ $project['project']->idModule }}"
                            class="btn btn-primary btn-sm rounded-full text-white">
                            Demander un devis
                        </a>
                        <a href="/formation/detail/{{ $project['project']->idModule }}#session"
                            class="btn btn-sm rounded-full">S'inscrire</a>
                    </span>
                </div>
            </div>
        </div>
    @endforeach
</div>
