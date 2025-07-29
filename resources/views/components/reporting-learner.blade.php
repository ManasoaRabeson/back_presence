<div class="relative w-full px-2 py-4">

    <div class="absolute left-12 top-4 bottom-[3.9rem] w-1 bg-gray-300"></div>

    @foreach ($projects as $index => $project)
        <div class="relative w-full flex items-start mb-12">

            <div
                class="bg-[#a462a4] text-white rounded-full border border-4 border-white p-4 text-center w-20 h-20 flex flex-col justify-center items-center shadow-lg mr-6 relative z-[1]">
                <p class="text-lg font-bold">{{ $project['day'] }}</p>
                <p class="text-sm mb-2">{{ $project['mois'] }}</p>
            </div>

            <div class="flex-1 flex flex-col lg:flex-row bg-white rounded-lg shadow-md overflow-hidden p-6">

                <div class="flex justify-center w-full lg:w-1/3 relative">
                    <img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/modules/{{ $project['module_image'] }}"
                        alt="Event Image" class="w-92 h-92 object-cover">
                </div>

                <div class="w-2/3 pl-8">
                    <a title="{{ $project['module_name'] }}"
                        href="/formation_inter/detail/{{ $project['idModule'] }}/{{ $project['idProjet'] }}"
                        class="card-title text-xl line-clamp-1 hover:underline underline-offset-4">{{ $project['module_name'] }}</a>
                    <p class="text-gray-600 text-sm mt-2">
                        <i class="far fa-calendar-alt"></i> {{ $project['date_debut'] }} -
                        {{ $project['date_fin'] }}
                    </p>
                    <p class="text-gray-600 text-sm mt-2">
                        <i class="fas fa-map-marker-alt"></i> {{ $project['ville'] }}
                    </p>
                    <p class="text-gray-600 text-sm mt-2">
                        <i class="fa-regular fa-clock"></i> {{ $project['dureeJ'] }} jours |
                        {{ $project['dureeH'] }} heures
                    </p>
                    <p class="text-gray-600 text-sm mt-2">
                        <i class="fa-solid fa-medal mr-2"></i> {{ $project['level_name'] }}
                    </p>
                    @if (isset($project['prix']))
                        <p class="text-base"><i class="fa-regular fa-money-bill-1 mr-2"></i>A partir de
                            {{ number_format($project['prix'], 2, ',', ' ') }} Ar</p>
                    @else
                        <p class="text-base"><i class="fa-regular fa-money-bill-1 mr-2"></i>Pour connaître nos
                            tarifs, contactez-nous.</p>
                    @endif
                    <div class="text-gray-600 flex text-sm mt-2 space-x-2" id="note_{{ $index }}">
                        <p id="raty_{{ $index }}" class="raty_notation text-left inline-flex"
                            data-average="{{ $project['note']['average'] }}">
                        </p>
                        <p class="text-sm">{{ $project['note']['average'] }}
                            <span class="text-gray-500 ">({{ $project['note']['totalEmployees'] }}
                                avis)</span>
                        </p>
                    </div>
                    <p class="text-gray-700 mt-4 line-clamp-2">
                        {{ $project['module_description'] }}
                    </p>
                </div>
            </div>

        </div>
    @endforeach
</div>
