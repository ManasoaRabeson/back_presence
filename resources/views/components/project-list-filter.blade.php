<span class="hidden lg:block">
    <ul id="domaines" class="menu">
        <li class="menu-title font-semibold text-slate-600">Domaines</li>
        @foreach ($domaine as $dom)
            <li class="domaine-item">
                <div class="flex items-center space-x-2">
                    <input type="checkbox" id="domaine_{{ $dom->idDomaine }}" class="domaine-checkbox"
                        value="{{ $dom->nomDomaine }}">
                    <label class="cursor-pointer" for="domaine_{{ $dom->idDomaine }}">{{ $dom->nomDomaine }} <span
                            class="text-gray-500 cursor-pointer">({{ $dom->nb_module }})</span> </label>
                </div>
            </li>
        @endforeach
        <a href="#" id="toggleDomaine" class="hidden mx-3 text-blue-500">Voir plus</a>
    </ul>

    <ul id="villes" class="menu">
        <li class="menu-title font-semibold text-slate-600">Villes</li>
        @foreach ($villes as $ville)
            <li class="ville-item">
                <div class="flex items-center space-x-2">
                    <input type="checkbox" id="ville_{{ $ville->id }}" class="ville-checkbox"
                        value="{{ $ville->ville_name }}">
                    <label class="cursor-pointer" for="ville_{{ $ville->id }}">{{ $ville->ville_name }} ({{$ville->vi_code_postal}}) <span
                            class="text-gray-500 cursor-pointer">({{ $ville->nb_module }})</span></label>
                </div>
            </li>
        @endforeach
        <a href="#" id="toggleVille" class="hidden mx-3 text-blue-500">Voir plus</a>
    </ul>
    

    <ul id="cfps" class="menu">
        <li class="menu-title font-semibold text-slate-600">Centre de formation</li>
        @foreach ($cfp as $c)
            <li class="cfp-item">
                <div class="flex items-center space-x-2">
                    <input type="checkbox" id="cfp_{{ $c->idCustomer }}" class="cfp-checkbox"
                        value="{{ $c->cfpName }}">
                    <label class="cursor-pointer" for="cfp_{{ $c->idCustomer }}">{{ $c->cfpName }} <span
                            class="text-gray-500 cursor-pointer">({{ $c->nb_module }})</span></label>
                </div>
            </li>
        @endforeach
        <a href="#" id="toggleCfp" class="hidden mx-3 text-blue-500">Voir plus</a>
    </ul>

    <ul id="levels" class="menu">
        <li class="menu-title font-semibold text-slate-600">Niveau</li>
        @foreach ($levels as $level)
            <li class="level-item">
                <div class="flex items-center space-x-2">
                    <input type="checkbox" id="level_{{ $level->idLevel }}" class="level-checkbox"
                        value="{{ $level->level_name }}">
                    <label class="cursor-pointer" for="level_{{ $level->idLevel }}">{{ $level->level_name }} <span
                            class="text-gray-500 cursor-pointer">({{ $level->nb_module }})</span></label>
                </div>
            </li>
        @endforeach
    </ul>

    <ul class="menu">
        <li class="menu-title font-semibold text-slate-600">Durée</li>
        <li class="">
            <div class="flex items-center space-x-2">
                <input type="checkbox" class="during-checkbox" id="during_1" value="Durée de 1 jour">
                <label class="cursor-pointer" for="during_1">1 jour</label>
            </div>
        </li>
        <li class="">
            <div class="flex items-center space-x-2">
                <input type="checkbox" class="during-checkbox" id="during_2" value="Durée de 2 jours">
                <label class="cursor-pointer" for="during_2">2 jours</label>
            </div>
        </li>
        <li class="">
            <div class="flex items-center space-x-2">
                <input type="checkbox" class="during-checkbox" id="during_3" value="Durée de 3 jour">
                <label class="cursor-pointer" for="during_3">3 jours</label>
            </div>
        </li>
        <li class="">
            <div class="flex items-center space-x-2">
                <input type="checkbox" class="during-checkbox" id="during_4" value="Durée plus de 4 jours">
                <label class="cursor-pointer" for="during_4">Plus de 4 jours</label>
            </div>
        </li>
    </ul>
</span>
