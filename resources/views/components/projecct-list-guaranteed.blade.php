<div class="h-full">

    <div class="relative px-2 py-6">

        <div onclick="filterBtn()"
            class="bg-white flex justify-between items-center py-2 rounded-md shadow-md w-32 ml-auto"
            style="cursor: pointer">
            <p class="text-gray-800 font-semibold text-sm pl-4">Filtrer</p>
            <button id="filterButton"
                class="flex items-center justify-center w-16 p-2 text-gray-600 hover:text-blue-600 transition duration-200">
                <i class="fa-solid fa-caret-down text-lg"></i>
            </button>
        </div>

        <div id="filterDropdown"
            class="hidden absolute right-0 mt-2 w-96 bg-white border border-gray-300 shadow-lg rounded-lg z-[1] transition-all duration-300">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Filtrer par</h3>
                <div class="mt-2 space-y-2">
                    <label class="flex items-center">
                        <input type="radio" name="filter" value="week" class="mr-2">
                        Cette semaine
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="filter" value="month" class="mr-2">
                        Ce mois
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="filter" value="next_month" class="mr-2">
                        Mois suivant
                    </label>
                </div>
                <div class="flex space-x-4 mt-4">
                    <div class="flex-1">
                        <label class="block">
                            <span class="text-gray-700">Date de début</span>
                            <input type="date"
                                class="mt-1 block w-full border-[#a462a4] rounded-md shadow-sm focus:border-[#a462a4] focus:ring focus:ring-[#a462a4]">
                        </label>
                    </div>
                    <div class="flex-1">
                        <label class="block">
                            <span class="text-gray-700">Date de fin</span>
                            <input type="date"
                                class="mt-1 block w-full border-[#a462a4] rounded-md shadow-sm focus:border-[#a462a4] focus:ring focus:ring-[#a462a4]">
                        </label>
                    </div>
                </div>
                <button type="submit"
                    class="mt-6 w-full bg-[#a462a4] text-white py-2 rounded-md hover:bg-[#a462a4] transition duration-200">
                    Rechercher
                </button>
            </div>
        </div>
    </div>


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
                            alt="Event Image" class="w-full h-full object-cover">
                        <div
                            class="absolute top-0 left-0 text-white mt-2 mr-1 w-32 h-20 transition duration-500 ease-in-out">
                            <img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/entreprises/{{ $project['logo_cfp'] }}"
                                alt="" class="inset-0 w-full h-auto rounded-md object-cover" loading="lazy" />
                        </div>
                    </div>

                    <div class="w-2/3 pl-8">
                        <a title="{{ $project['module_name'] }}"
                            href="/formation_inter/detail/{{ $project['idModule'] }}/{{ $project['idProjet'] }}"
                            class="card-title text-xl line-clamp-1 hover:underline underline-offset-4">{{ $project['module_name'] }}</a>
                        <p class="text-xs italic mt-2"><strong> {{ $project['cfp_name'] }}</strong></p>
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
                        <div class="mt-6 flex space-x-2">
                            <a href="/demande_devis/{{ $project['idCustomer'] }}/{{ $project['idModule'] }}"
                                class="bg-[#a462a4] hover:bg-[#a462a4] text-white px-4 py-2 rounded-md text-sm">
                                Demander un devis
                            </a>
                            <a href="/formation_inter/detail/{{ $project['idModule'] }}/{{ $project['idProjet'] }}#session"
                                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm">
                                S'inscrire
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        @endforeach
    </div>

</div>
