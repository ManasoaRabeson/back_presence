{{-- 
<div class="space-y-6 2xl:mx-48">
    <div class="2xl:mx-20">
        <ul class="grid grid-cols-2 gap-2 xl:flex xl:flex-row xl:space-x-6 items-center rounded-lg xl:rounded-full border border-gray-200 shadow-lg shadow-gray-200 h-auto bg-white pt-2 px-6 mx-6">
            <li class="w-full xl:w-1/6">
                <label for="small" class="block mb-2 text-sm text-center font-medium text-gray-600 dark:text-white">LIEU</label>
                <select id="small" class="block overflow-y-auto w-full p-2 mb-6 hover:bg-gray-300 hover:text-black border border-gray-200 focus:outline-none text-black text-sm rounded-lg">
                    <option selected>Partout en M/car</option>
                    @foreach ($places as $place)
                        <option value="{{$place->idVille}}">{{$place->ville}}</option>
                    @endforeach
                </select>
            </li>
            <li class="w-full xl:w-1/6">
                <label for="small" class="block mb-2 text-sm text-center font-medium text-gray-600 dark:text-white">ENTREPRISE</label>
                <select id="small" class="block overflow-y-auto w-full p-2 mb-6 hover:bg-gray-300 hover:text-black border border-gray-200 focus:outline-none text-black text-sm rounded-lg">
                    <option selected>Tous les entreprises</option>
                    @foreach ($cfp as $c)
                        <option value="{{$c->idCustomer}}">{{$c->customerName}}</option>
                    @endforeach
                </select>
            </li>
            <li class="w-full xl:w-1/6">
                <label for="small" class="block mb-2 text-sm text-center font-medium text-gray-600 dark:text-white">NIVEAU</label>
                <select id="small" class="block overflow-y-auto w-full p-2 mb-6 hover:bg-gray-300 hover:text-black border border-gray-200 focus:outline-none text-black text-sm rounded-lg">
                    <option selected>Tous niveaux</option>
                    <option value="CA">Debutant</option>
                    <option value="FR">Intermetdiaire</option>
                    <option value="DE">Avance</option>
                </select>
            </li>
            <li class="w-full xl:w-1/6">
                <label for="small" class="block mb-2 text-sm text-center font-medium text-gray-600 dark:text-white">COURS</label>
                <select id="small" class="block overflow-y-auto w-full p-2 mb-6 hover:bg-gray-300 hover:text-black border border-gray-200 focus:outline-none text-black text-sm rounded-lg">
                    <option selected>Tous les cours</option>
                    @foreach ($domaines as $dom)
                        <option value="{{$dom->idDomaine}}">{{$dom->nomDomaine}}</option>
                    @endforeach
                </select>
            </li>
            <li class="w-full xl:w-1/6">
                <label for="small" class="block mb-2 text-sm text-center font-medium text-gray-600 dark:text-white">INTER / INTRA / INTERNE</label>
                <select id="small" class="block overflow-y-auto w-full p-2 mb-6 hover:bg-gray-300 hover:text-black border border-gray-200 focus:outline-none text-black text-sm rounded-lg">
                    <option selected>Inter / Intra / Interne</option>
                    <option value="US">Inter</option>
                    <option value="CA">Intra</option>
                    <option value="FR">Interne</option>
                </select>
            </li>
            <li class="w-full xl:w-1/6">
                <input type="text" name="" id="" class="mt-6 md:mt-1 xl:mt-0 hover:bg-gray-300 w-full hover:text-black border border-gray-200 h-[2.40rem] rounded-lg items-center outline-none" placeholder=" Chercher..."> 
            </li>
        </ul>
    </div>

    <div class="mx-10">
        <div class="flex flex-col md:grid md:grid-cols-2 xl:grid-cols-4 gap-10 2xl:mx-20">
            @if (count($projects)>0)
                @foreach ($projects as $index => $project)
                    <div class="border border-lg w-full rounded-lg space-y-8">
                        <a href="/formation/detail/{{$project['project']->idModule}}">
                            <div class="flex flex-col">
                                <div class="w-full relative  sm:block p-2">
                                    <img src="/img/modules/{{$project['project']->module_image}}" alt="" class=" inset-0 w-full h-[11.05rem] rounded-t-lg object-fill" loading="lazy" />
                                    <div
                                        class="absolute top-0 left-0 px-4 py-2 text-white mt-2 mr-1 w-28 transition duration-500 ease-in-out">
                                        <img src="/img/entreprises/{{$project['project']->etp_logo}}" alt="" class=" inset-0 w-full h-full rounded-md object-fill" loading="lazy" />
                                    </div>
                                </div>
                                <div class="mx-1 px-2">
                                    <h1 class="my-4 h-12 line-clamp-2">{{$project['project']->moduleName}}</h1>
                                    <p class="line-clamp-3">
                                        @if (isset($project['project']->description))
                                            <div class="project-description">
                                                <p id="description_{{$index}}" class="line-clamp-3">{{$project['project']->description}}</p>
                                                <p><i class="fa-regular fa-clock"></i> {{$project['project']->dureeJ}} jours | {{$project['project']->dureeH}} heures</p>
                                                <p><i class="fa-regular  fa-money-bill-1"></i> A partir de {{ number_format($project['project']->prix, 2, ',', ' ') }} Ar</p>
                                                <div class="flex items-center space-x-1" id="note_{{$index}}">
                                                    <p id="raty_{{ $index }}" class="raty_notation  inline-flex" data-average="{{$project['note']['average']}}"></p>
                                                    <p>{{$project['note']['average']}} <span class="text-gray-500">({{$project['note']['totalEmployees']}} avis)</span> </p>
                                                </div>
                                            </div>
                                        @else
                                            <span class="h-12">Aucune description</span>
                                            <p><i class="fa-regular fa-clock"></i> {{$project['project']->dureeJ}} jours | {{$project['project']->dureeH}} heures</p>
                                            <p><i class="fa-regular fa-money-bill-1"></i> A partir de {{ number_format($project['project']->prix, 2, ',', ' ') }} Ar</p>
                                            <div class="flex items-center space-x-1">
                                                <p id="raty_{{ $index }}" class="raty_notation  inline-flex" data-average="{{$project['note']['average']}}"></p>
                                                <p>{{$project['note']['average']}} <span class="text-gray-500">({{$project['note']['totalEmployees']}} avis)</span> </p>
                                            </div>
                                            <br><br>
                                        @endif                        
                                    </p>
                                </div>
                            </div>
                        </a>
                        <div class="relative flex  sm:justify-center">
                            <div class="absolute flex ml-2 sm:ml-0 h-10  gap-5 md:gap-6 sm:gap-20 -top-4">
                                <div>
                                    <a class="bg-[#a462a4] text-xs text-white py-2 px-4 rounded-full" href="{{ url('/demande_devis/1') }}">
                                        Demander un devis
                                    </a>
                                </div>
                                <div>
                                    <a class="bg-white border border-gray-600 text-xs text-gray-600 hover:text-black hover:bg-gray-200 py-2 px-4 rounded-full">
                                        S'inscrire
                                    </a>
                                </div>
                            </div>
                        </div>   
                    </div>
                @endforeach
            @else
                <h1>Aucun resultat correspondant</h1>
            @endif
            
        </div>
    </div>
</div> --}}

<div class="w-full h-full max-w-screen-xl mx-auto" id="search_results">
    <div class="grid grid-cols-12 mx-12">
        <div class="grid col-span-3 w-full grid-cols-subgrid hidden xl:block"> 
            <ul class="space-y-4 overflow-y-scroll max-h-[80vh] h-full">
                <li class="pr-6">
                    <div
                        class="flex items-center p-2 text-gray-400 bg-white rounded-md border-[1px] hover:border-gray-500 focus:border-gray-400 cursor-pointer duration-200">
                        <i class="fa-solid fa-magnifying-glass" class="w-5 h-5 text-gray-400"></i>
                        <input class="block ml-3 text-gray-400 bg-white outline-none" type="text" value="{{$key}}" placeholder="Chercher..." id="search">
                    </div>
                </li>
                <li>
                    <p class="text-xl font-bold">Filtrer par</p>
                </li>
                <li class="w-full">
                    <div class="space-y-2">
                        <p class="font-semibold">Domaines</p>
                        <ul id="domaines">
                            @foreach ($domaines_search as $domaine)
                                <li class="domaine-item">
                                    <div class="flex items-center space-x-2">
                                        <input type="checkbox" id="domaine_{{$dom['idDomaine']}}" class="domaine-checkbox" value="{{$dom['domaine_name']}}">
                                        <label for="domaine_{{$dom['idDomaine']}}">{{$dom['domaine_name']}} <span class="text-gray-500">({{$dom['nb_module']}})</span> </label>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </li>
                <li class="w-full">
                    <div class="space-y-2">
                        <p class="font-semibold">Villes</p>
                        <ul id="villes">
                            @foreach ($villes as $ville)
                                <li class="ville-item">
                                    <div class="flex items-center space-x-2">
                                        <input type="checkbox" id="ville_{{$ville->idVille}}" class="ville-checkbox" value="{{$ville->ville}}">
                                        <label for="ville_{{$ville->idVille}}">{{$ville->ville}} <span class="text-gray-500">({{$ville->nb_module}})</span></label>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </li>
                <li class="w-full">
                    <div class="space-y-2">
                        <p class="font-semibold">Centre de formation</p>
                        <ul id="cfps">
                            @foreach ($cfp as $c)
                                <li class="cfp-item">
                                    <div class="flex items-center space-x-2">
                                        <input type="checkbox" id="cfp_{{$c->id_cfp}}" class="cfp-checkbox" value="{{$c->cfp_name}}">
                                        <label for="cfp_{{$c->id_cfp}}">{{$c->cfp_name}} <span class="text-gray-500">({{$c->nb_module}})</span></label>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </li>
                <li class="w-full">
                    <div class="space-y-2">
                        <p class="font-semibold">Durée</p>
                        <ul >
                            <li class="">
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" id="moins_8">
                                    <label for="moins_8">Moins de 8 heures</label>
                                </div>
                            </li>
                            <li class="">
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" id="moins_40">
                                    <label for="moins_40">Moins de 40 heures</label>
                                </div>
                            </li>
                            <li class="">
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" id="plus_40">
                                    <label for="plus_40">Plus de 40 heures</label>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <a href="#" id="toggleCfp" class="hidden text-blue-500">Voir plus</a>
                </li>
            </ul>
        </div>
        <div class="grid col-span-12 lg:col-span-9 grid-cols-subgrid">
                @if (count($projects)>0)
                    <div class="flex col-span-9 pl-6 mb-4 space-x-6 items-center">
                        <div class="grid grid-cols-12">
                            <div class="col-span-2 grid-cols-subgrid">
                                {{$project_count}} cours trouvé(s)
                            </div>
                            <div class="col-span-10 ml-2 grid-cols-subgrid">
                                <div class="flex flex-wrap w-full gap-2" id="selected-items">
                                    @if (isset($category_search))
                                        <div class="rounded rounded-xl border-2 px-2 py-1">
                                            <span class="mr-2">{{$category_search}}</span>
                                            <span><i class="fa-solid fa-xmark"></i></span>
                                        </div>
                                    @endif
                                    @if (isset($ville_search))
                                        <div class="rounded rounded-xl border-2 px-2 py-1">
                                            <span class="mr-2">{{$ville_search}}</span>
                                            <span><i class="fa-solid fa-xmark"></i></span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="grid col-span-12 grid-cols-1 lg:grid-cols-2 xl:col-span-9 xl:grid-cols-3 h-max gap-10 pl-6 xl:overflow-y-scroll max-h-[80vh] h-full">
                        @foreach ($projects as $index => $project)
                            <div class="border border-lg w-full rounded-lg space-y-6">
                                <a href="/formation/detail/{{$project['project']->idModule}}">
                                    <div class="flex flex-col">
                                        <div class="w-full relative  sm:block p-2">
                                            <img src="/img/modules/{{$project['project']->module_image}}" alt="" class=" inset-0 w-full h-[11.05rem] rounded-t-lg object-fill" loading="lazy" />
                                            <div
                                                class="absolute top-0 left-0 px-4 py-2 text-white mt-2 mr-1 w-28 transition duration-500 ease-in-out">
                                                <img src="/img/entreprises/{{$project['project']->logo_cfp}}" alt="" class=" inset-0 w-full h-full rounded-md object-fill" loading="lazy" />
                                            </div>
                                        </div>
                                        <div class="mx-1 px-2">
                                            <h1 class="my-4 h-12 line-clamp-2">{{$project['project']->module_name}}</h1>
                                            <p class="line-clamp-3">
                                                @if (isset($project['project']->description))
                                                    <div class="project-description">
                                                        <p id="description_{{$index}}" class="line-clamp-3">{{$project['project']->description}}</p>
                                                        <p><i class="fa-regular fa-clock"></i> {{$project['project']->dureeJ}} jours | {{$project['project']->dureeH}} heures</p>
                                                        <p><i class="fa-regular  fa-money-bill-1"></i> A partir de {{ number_format($project['project']->prix, 2, ',', ' ') }} Ar</p>
                                                        <div class="flex items-center space-x-1" id="note_{{$index}}">
                                                            <p id="raty_{{ $index }}" class="raty_notation  inline-flex" data-average="{{$project['note']['average']}}"></p>
                                                            <p>{{$project['note']['average']}} <span class="text-gray-500">({{$project['note']['totalEmployees']}} avis)</span> </p>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="h-12">Aucune description</span>
                                                    <p><i class="fa-regular fa-clock"></i> {{$project['project']->dureeJ}} jours | {{$project['project']->dureeH}} heures</p>
                                                    <p><i class="fa-regular fa-money-bill-1"></i> A partir de {{ number_format($project['project']->prix, 2, ',', ' ') }} Ar</p>
                                                    <div class="flex items-center space-x-1">
                                                        <p id="raty_{{ $index }}" class="raty_notation  inline-flex" data-average="{{$project['note']['average']}}"></p>
                                                        <p>{{$project['note']['average']}} <span class="text-gray-500">({{$project['note']['totalEmployees']}} avis)</span> </p>
                                                    </div>
                                                    <br><br>
                                                @endif                        
                                            </p>
                                        </div>
                                    </div>
                                </a>
                                <div class="relative flex  sm:justify-center">
                                    <div class="absolute flex ml-2 sm:ml-0 h-10  gap-5 md:gap-6 sm:gap-20 -top-4">
                                        <div>
                                            <a class="bg-[#a462a4] text-xs text-white py-2 px-4 rounded-full" href="{{ url('/demande_devis/1') }}">
                                                Demander un devis
                                            </a>
                                        </div>
                                        <div>
                                            <a class="bg-white border border-gray-600 text-xs text-gray-600 hover:text-black hover:bg-gray-200 py-2 px-4 rounded-full">
                                                S'inscrire
                                            </a>
                                        </div>
                                    </div>
                                </div>   
                            </div>
                        @endforeach
                    </div>
                    
                @else
                    <h1>Aucun resultat correspondant</h1>
                @endif
        </div>
    </div>
</div>
