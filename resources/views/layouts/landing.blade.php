<div class="relative py-24 lg:py-32 overflow-hidden bg-cover"
    style="background-image: url('{{ asset('img/hero/hero.webp') }}'); background-position: top;">

    <div class="absolute inset-0 bg-[#212529] opacity-60 "></div>

    <div class="container mx-auto relative flex flex-col lg:flex-row items-center justify-center px-6 lg:px-0">

        <div class="w-full lg:w-1/2 text-gray-800 lg:text-left text-center lg:pr-16 mt-14 lg:mt-40">
            <h1 class="text-4xl lg:text-6xl font-extrabold mb-6 leading-tight text-slate-300">
                Explorez une multitude de formations sur FormaFusion
            </h1>
            <p class="text-lg lg:text-2xl text-slate-200 mb-8">
                Trouvez la formation qu’il vous faut parmi 4 000 organismes de confiance
            </p>
        </div>

        <!-- Mahatonga an'ilay texte 1 ery ambony ery mba ho eo @ sisiny gauche tsara -->
        <div class="hidden lg:block lg:w-1/2 lg:mt-20">
        </div>
    </div>

    <form action="{{ route('search.formation') }}" method="get">
        <div class="container flex flex-col items-center lg:flex-row py-10 px-6 rounded-lg shadow-lg lg:mx-auto mx-auto w-full"
            style="background-color: rgba(255, 255, 255, 0.281); backdrop-filter: blur(10px); z-index: 30;">
            <div class="w-full lg:w-1/3 px-2 lg:mb-0">
                <input type="text" id="simple-search" name="course" class="input input-bordered w-full"
                    placeholder="Rechercher...">
            </div>

            <div class="w-full lg:w-1/3 px-2 lg:mb-0">
                <select id="category" name="category" class="select select-bordered w-full">
                    <option value="all">Toutes les domaines</option>
                    @foreach ($domaines as $dom)
                        <option value="{{ $dom['idDomaine'] }}">{{ $dom['nomDomaine'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-full lg:w-1/3 px-2 lg:mb-0">
                <select id="lieu-search" name="place" class="select select-bordered w-full">
                    <option value="all">Partout en Madagascar</option>
                    @foreach ($places as $place)
                        <option value="{{ $place->id }}">{{ $place->ville }} ({{ $place->vi_code_postal}})</option>
                    @endforeach
                </select>
            </div>

            <div class="w-full lg:w-auto px-2">
                <button type="submit"
                    class="w-full lg:w-auto flex items-center justify-center bg-[#4c3c90] text-white font-semibold h-12 px-6 rounded-lg shadow hover:bg-[#3b2c6b] transition duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-300">
                    <svg class="w-5 h-5 mr-2 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 19a8 8 0 100-16 8 8 0 000 16zm7-7l4 4"></path>
                    </svg>
                    Rechercher
                </button>
            </div>
        </div>
    </form>

</div>


<div class="bg-[#f3f4f6] py-14">

    <p class="font-extrabold text-3xl text-slate-700 text-center sm:px-6 lg:px-8 py-8 mx-auto max-w-7xl">Découvrez les
        formations du moment</p>

    <div class="container mx-auto p-6 bg-[#ffffff] shadow-lg rounded-lg">

        <div class="flex flex-col lg:flex-row items-center lg:items-start lg:justify-between">

            <div class="text-center lg:text-left mb-8 lg:mb-0 lg:w-1/3 bg-[#e8eef7] rounded-xl p-8">
                <p class="text-2xl font-semibold text-[#0b763e]">MICROSOFT EXCEL</p>
                <p class="text-base px-10 lg:px-0 text-slate-600 whitespace-pre-line">
                    Apprenez à maîtriser les bases d'Excel, de la gestion des données aux fonctions essentielles. Cette
                    formation vous permettra d'améliorer votre efficacité avec des outils pratiques et des techniques
                    d'analyse.
                </p>
                <a href="/formation_by_numerika/excel">
                    <button class="btn btn-outline btn-success mt-6">
                        Voir tous <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </a>
            </div>

            <div class="flex flex-col lg:flex-row w-full lg:w-2/3 lg:ml-10">

                <div class="js-carousel relative w-full overflow-hidden p-3">

                    <div class="js-carousel-inner-1 flex flex-1 transition-transform duration-300"
                        id="js-carousel-inner">

                        @isset($modules_excel)
                            @foreach ($modules_excel as $excel)
                                <div class="js-carousel-item px-2">
                                    <div
                                        class="bg-white w-full rounded-lg max-w-lg mx-auto border border-[#e8eef7] hover:scale-105 transform transition-transform duration-300 overflow-hidden shadow-md">
                                        <img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/modules/{{ $excel->module_image }}"
                                            alt="Excel Niveau 1" class="w-full h-40 object-cover">
                                        <img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/entreprises/{{ $excel->logo }}"
                                            alt="Logo numerika" class="absolute top-2 left-1 p-1" style="width: 120px;">
                                        <div class="p-6">
                                            <h3
                                                class="text-md font-bold mb-2 text-slate-700 h-16 overflow-hidden text-ellipsis">
                                                {{ $excel->moduleName }}
                                            </h3>
                                            <p class="text-base mb-1 font-bold overflow-hidden text-slate-600">
                                                {{ $excel->customerName }}</p>
                                            @if (isset($excel->customer_slogan))
                                                <p
                                                    class="text-sm mb-1 text-slate-600 h-10 overflow-hidden text-ellipsis line-clamp-2">
                                                    {{ $excel->customer_slogan }}
                                                </p>
                                            @endif
                                            <p class="text-sm mb-1 text-slate-600"><i
                                                    class="fa-solid fa-clock mr-1"></i>{{ $excel->dureeJ }} jours
                                                ({{ $excel->dureeH }} heures)
                                            </p>
                                            <p class="text-sm font-semibold mb-3 text-slate-600"><i
                                                    class="fa-solid fa-money-bill-wave mr-1"></i>
                                                {{ number_format($excel->prix, 2, ',', ' ') }} Ar HT</p>
                                            <a href="/formation/detail/{{ $excel->idModule }}">
                                                <button
                                                    class="bg-[#0b763e] text-white py-2 px-4 rounded hover:text-[#0b763e] border border-[#0b763e] text-sm w-full">
                                                    Voir la formation
                                                </button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endisset

                    </div>

                    <button
                        class="absolute top-1/2 left-0 transform -translate-y-1/2 js-carousel-arrow js-carousel-prev"
                        id="prev1" aria-label="Previous Slide">‹</button>
                    <button
                        class="absolute top-1/2 right-0 transform -translate-y-1/2 js-carousel-arrow js-carousel-next"
                        id="next1" aria-label="Next Slide">›</button>
                </div>

            </div>

        </div>
    </div>

    <div class="container mx-auto p-6 bg-[#ffffff] shadow-lg rounded-lg mt-8">

        <div class="flex flex-col lg:flex-row items-center lg:items-start lg:justify-between">

            <div class="text-center lg:text-left mb-8 lg:mb-0 lg:w-1/3 bg-[#e8eef7] rounded-xl p-8">
                <p class="text-2xl font-semibold text-[#ef9e08]">MICROSOFT POWER BI</p>
                <p class="text-base px-10 lg:px-0 text-slate-600 whitespace-pre-line">
                    Apprendre Microsoft Power BI permet de transformer les données en visualisations claires, facilitant
                    la prise de décisions. Son intégration avec d'autres outils Microsoft en fait un atout pour
                    optimiser l'analyse des données.
                </p>
                <a href="/formation_by_numerika/power_bi">
                    <button class="btn btn-outline btn-warning mt-6">
                        Voir tous <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </a>
            </div>

            <div class="flex flex-col lg:flex-row w-full lg:w-2/3 lg:ml-10">

                <div class="js-carousel relative overflow-hidden p-3">

                    <div class="js-carousel-inner-2 flex transition-transform duration-300" id="js-carousel-inner-2">
                        @isset($modules_power_bi)
                            @foreach ($modules_power_bi as $power_bi)
                                <div class="js-carousel-item px-2">
                                    <div
                                        class="bg-white w-full rounded-lg max-w-lg mx-auto border border-[#e8eef7] hover:scale-105 transform transition-transform duration-300 overflow-hidden shadow-md">
                                        <img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/modules/{{ $power_bi->module_image }}"
                                            alt="Excel Niveau 1" class="w-full h-40 object-cover">
                                        <img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/entreprises/{{ $power_bi->logo }}"
                                            alt="Logo numerika" class="absolute top-2 left-1 p-1" style="width: 120px;">
                                        <div class="p-6">
                                            <h3
                                                class="text-md font-semibold mb-2 text-slate-700 h-16 overflow-hidden text-ellipsis">
                                                {{ $power_bi->moduleName }}
                                            </h3>
                                            <p class="text-base mb-1 font-bold overflow-hidden text-slate-600">
                                                {{ $power_bi->customerName }}</p>
                                            @if (isset($power_bi->customer_slogan))
                                                <p
                                                    class="text-sm mb-1 text-slate-600 h-10 overflow-hidden text-ellipsis line-clamp-2">
                                                    {{ $power_bi->customer_slogan }}
                                                </p>
                                            @endif
                                            <p class="text-sm mb-1 text-slate-600"><i
                                                    class="fa-solid fa-clock mr-1"></i>{{ $power_bi->dureeJ }} jours
                                                ({{ $power_bi->dureeH }} heures)
                                            </p>
                                            <p class="text-sm font-semibold mb-3 text-slate-600"><i
                                                    class="fa-solid fa-money-bill-wave mr-1"></i>
                                                {{ number_format($power_bi->prix, 2, ',', ' ') }} Ar HT</p>
                                            <a href="/formation/detail/{{ $power_bi->idModule }}">
                                                <button
                                                    class="bg-[#0b763e] text-white py-2 px-4 rounded hover:text-[#0b763e] border border-[#0b763e] text-sm w-full">
                                                    Voir la formation
                                                </button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endisset
                    </div>

                    <button
                        class="absolute top-1/2 left-0 transform -translate-y-1/2 js-carousel-arrow js-carousel-prev"
                        id="prev2" aria-label="Previous Slide">‹</button>
                    <button
                        class="absolute top-1/2 right-0 transform -translate-y-1/2 js-carousel-arrow js-carousel-next"
                        id="next2" aria-label="Next Slide">›</button>

                </div>

            </div>
        </div>
    </div>


</div>

<div class="container mx-auto mt-16 mb-16">
    <div class="flex flex-col lg:flex-row items-center p-10 bg-[#f3f4f6] shadow-lg rounded-lg">
        <div class="w-full lg:w-4/6 mb-6 lg:mb-0 text-center lg:text-left">
            <h1 class="text-3xl font-bold text-gray-900 mb-6 mt-4">
                Des Organismes de formation de confiance !
            </h1>
            <p class="text-lg text-gray-700">
                Nous recensons aujourd’hui des formations dans plus d’une 50ène de catégories et sous-catégories afin
                d’être le plus représentatif par rapport au besoin en compétences des entreprises.
            </p>
            <div class="flex justify-center space-x-4 mt-8">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRskC5oEOdl4V1dbFqELlselFhuB4AdjLobuA&s"
                    alt="Logo Numerika" class="w-28 h-12">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSTjLnc1CK6E7byx0uV_c42XW45HPcfecYC7w&s"
                    alt="Logo TekFutura" class="w-28 h-12">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSYIEa99fPNili1cL4Yhm-2up7A8XExqB3MpA&s"
                    alt="Logo Inscae" class="w-28 h-12">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRJsHnor0MkZxyqvPRMK5mO-oDZ1Z9SyaSsQA&s"
                    alt="Logo Kentia Formation" class="w-28 h-12">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQmLBIbI-WPgmfHq6BJL5KHYY_afYFSDavbWA&s"
                    alt="Logo Teknet Group" class="w-28 h-12">
            </div>
        </div>
        <div class="flex justify-center lg:justify-end w-full lg:w-2/6">
            <a href="/liste_organisme"
                class="rounded-lg bg-gradient-to-r from-[#a462a4] to-[#834b83] px-8 py-4 text-white text-lg font-semibold flex items-center transition-transform duration-300 hover:scale-105">
                Découvrir nos organismes
                <i class="fa-solid fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</div>
