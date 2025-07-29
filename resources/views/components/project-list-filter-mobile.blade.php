<div class="space-y-4 text-xl lg:hidden">
    <div class="flex justify-between mx-2">
        <p class="">
            Filtrer par
        </p>
        <p class="text-blue-500" id="reset">
            Réinitialiser
        </p>
    </div>
    <ul class="space-y-4">
        <li class="">
            <ul class="space-y-4">
                <li class="w-full">
                    <div class="space-y-2">
                        <p class="font-semibold">Domaines</p>
                        <ul id="domaines">
                            @foreach ($domaine as $dom)
                                <li class="domaine-item">
                                    <div class="flex items-center space-x-2">
                                        <input type="checkbox" id="domaine_{{ $dom->idDomaine }}"
                                            class="domaine-checkbox" value="{{ $dom->nomDomaine }}">
                                        <label for="domaine_{{ $dom->idDomaine }}">{{ $dom->nomDomaine }} <span
                                                class="text-gray-500">({{ $dom->nb_module }})</span> </label>
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
                                        <input type="checkbox" id="ville_{{ $ville->id }}" class="ville-checkbox"
                                            value="{{ $ville->ville_name }}">
                                        <label for="ville_{{ $ville->id }}">{{ $ville->ville_name }} <span
                                                class="text-gray-500">({{ $ville->nb_module }})</span></label>
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
                                        <input type="checkbox" id="cfp_{{ $c->idCustomer }}" class="cfp-checkbox"
                                            value="{{ $c->cfpName }}">
                                        <label for="cfp_{{ $c->idCustomer }}">{{ $c->cfpName }} <span
                                                class="text-gray-500">({{ $c->nb_module }})</span></label>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </li>
                <li class="w-full">
                    <div class="space-y-2">
                        <p class="font-semibold">Durée</p>
                        <ul>
                            <li class="">
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" class="during-checkbox" id="during_1"
                                        value="Moins de 10 heures">
                                    <label for="during_1">Moins de 5 heures</label>
                                </div>
                            </li>
                            <li class="">
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" class="during-checkbox" id="during_2" value="5-10 heures">
                                    <label for="during_2">5-10 heures</label>
                                </div>
                            </li>
                            <li class="">
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" class="during-checkbox" id="during_3" value="10-20 heures">
                                    <label for="during_3">10-20 heures</label>
                                </div>
                            </li>
                            <li class="">
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" class="during-checkbox" id="during_4" value="20-60 heures">
                                    <label for="during_4">20-60 heures</label>
                                </div>
                            </li>
                            <li class="">
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" class="during-checkbox" id="during_5"
                                        value="Plus de 60 heures">
                                    <label for="during_5">Plus de 60 heures</label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </li>
    </ul>
    <div class="du_modal-action">
        <button class="btn" id="reset">Annuler</button>
        <button class="btn btn-primary ml-3">Valider</button>
        </form>
    </div>
</div>
