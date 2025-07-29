<div class="space-y-6">
    <div class="">
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
        @if (count($projects)>0)
            <div class="mx-10">
                <div class="flex flex-col md:grid md:grid-cols-2 xl:grid-cols-4 gap-10 2xl:mx-40">
                    @foreach ($projects as $project)
                        <div class="border border-lg w-full rounded-lg space-y-8">
                            <a href="/formation/detail/{{$project->idProjet}}">
                                <div class="flex flex-col">
                                    <div class="w-full relative  sm:block">
                                        <img src="/img/modules/{{$project->module_image}}" alt="" class=" inset-0 w-full h-[11.05rem] rounded-t-lg object-fill" loading="lazy" />
                                        <div
                                            class="absolute top-0 left-0 px-4 py-2 text-white mt-2 mr-1 w-28 transition duration-500 ease-in-out">
                                            <img src="/img/entreprises/{{$project->etp_logo}}" alt="" class=" inset-0 w-full h-full rounded-md object-fill" loading="lazy" />
                                        </div>
                                    </div>
                                    <div class="mx-1">
                                        <h1 class="my-4 h-12 line-clamp-2">{{$project->project_title}}</h1>
                                        <div class="space-y-2">
                                            <p class="line-clamp-3">
                                                @if (!isset($project->project_description))
                                                    <span>Aucune description</span>
                                                    <p><i class="fa-solid fa-location-dot mt-14"></i> {{$project->ville}}</p>
                                                    <p><i class="fa-regular fa-clock"></i> {{$project->dureeJ}} jours | {{$project->dureeH}} heures</p>
                                                    <p><i class="fa-regular fa-money-bill-1"></i> A partir de {{$project->prix}} Ar</p>
                                                @else
                                                    {{$project->project_description}}
                                                    <p><i class="fa-solid fa-location-dot"></i> {{$project->ville}}</p>
                                                    <p><i class="fa-regular fa-clock"></i> {{$project->dureeJ}} jours | {{$project->dureeH}} heures</p>
                                                    <p><i class="fa-regular fa-money-bill-1"></i> A partir de {{$project->prix}} Ar</p>
                                                @endif
                                            </p>
                                        </div>
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
            </div>
        @else
            <h1 class="text-2xl mx-10">Aucun resultat correspondant</h1>
        @endif
</div>