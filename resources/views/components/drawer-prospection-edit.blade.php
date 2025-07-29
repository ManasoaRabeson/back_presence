@php
    $new ??= false;
@endphp
<div class="offcanvas offcanvas-end !w-[40em]" tabindex="-1" id="offcanvasProspectionEdit"
    aria-labelledby="offcanvasProspectionEdit">
    <div class="flex flex-col w-full">
        <div class="inline-flex items-center justify-between w-full px-4 py-2 bg-slate-50">
            <p class="text-lg font-medium text-slate-500">
                @if ($new == true)
                    Nouvelle Opportunité
                @else
                    Modifier l'Opportunité
                @endif
            </p>
            <a data-bs-toggle="offcanvas" href="#offcanvasProspectionEdit" class="btn btn-sm btn-square btn-ghost">
                <i class="text-slate-500 fa-solid fa-xmark"></i>
            </a>
        </div>
    </div>

    <div class="flex flex-col gap-3 w-full overflow-y-auto p-4">
        <span>
            <p class="menu-title text-lg text-slate-700 !font-normal"><i class="fa-solid fa-building text-sm"></i>
                Information sur l'entreprise</p>

            <div class="grid grid-cols-1">
                <div id="choose_etp_type" class="grid grid-cols-2 mt-2 hidden">
                    <div class="grid col-span-1">
                        <label class="cursor-pointer inline-flex items-center gap-2">
                            <input onclick="etp_radio()" type="radio" name="etp_radio" value="1"
                                class="radio checked:bg-[#E42548]" data-val="{{ $opportunite->etp_name ?? null }}"
                                {{ old('etp_radio', isset($opportunite->idEtp) ? 'checked' : '') }} />
                            <span class="label-text">Contact existant ?</span>
                        </label>
                    </div>
                    <div class="grid col-span-1">
                        <label class="cursor-pointer inline-flex items-center gap-2">
                            <input onclick="etp_radio()" type="radio" name="etp_radio" value="2"
                                class="radio checked:bg-[#4056F4]" data-val="{{ $opportunite->prospect_name ?? null }}"
                                {{ old('etp_radio', isset($opportunite->id_prospect) ? 'checked' : '') }} />
                            <span class="label-text">Nouveau prospect ?</span>
                        </label>
                    </div>
                </div>

                <span id="etp_radio_result" class="mb-2"></span>

                <div class="grid grid-cols-2 gap-4">
                    <div class="grid col-span-1">
                        <label class="w-full">
                            <div class="label">
                                <span class="label-text after:content-['*'] after:ml-0.5 after:text-red-500">Nom du
                                    responsable</span>
                            </div>
                            <input type="text" name="opportunite_ref_name"
                                class="input input-bordered input-sm w-full"
                                value="{{ old('opportunite_ref_name', $opportunite->ref_name ?? '') }}" required />
                            <span id="opportunite_ref_name_error" class="text-red-500 text-sm"></span>
                        </label>
                    </div>
                    <div class="grid col-span-1">
                        <label class="w-full">
                            <div class="label">
                                <span class="label-text">Prénom du responsable</span>
                            </div>
                            <input type="text" name="opportunite_ref_firstname"
                                class="input input-bordered input-sm w-full"
                                value="{{ old('opportunite_ref_firstname', $opportunite->ref_firstname ?? '') }}" />
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="grid col-span-1">
                        <label class="w-full">
                            <div class="label">
                                <span
                                    class="label-text after:content-['*'] after:ml-0.5 after:text-red-500">Email</span>
                            </div>
                            <input type="email" name="opportunite_ref_email"
                                class="input input-bordered input-sm w-full" required
                                value="{{ old('opportunite_ref_email', $opportunite->etp_email ?? '') }}" />
                            <span id="opportunite_ref_email_error" class="text-red-500 text-sm"></span>
                        </label>
                    </div>
                    <div class="grid col-span-1">
                        <label class="w-full">
                            <div class="label">
                                <span class="label-text">Téléphone</span>
                            </div>
                            <input type="text" name="opportunite_ref_phone"
                                class="input input-bordered input-sm w-full"
                                value="{{ old('opportunite_ref_phone', $opportunite->etp_phone ?? '') }}" />
                        </label>
                    </div>
                </div>
            </div>
        </span>
        </span>

        <span>
            <p class="menu-title text-lg text-slate-700 !font-normal"><i class="fa-solid fa-puzzle-piece text-sm"></i>
                Cours</p>

            <div class="grid grid-cols-1">
                <label class="w-full">
                    <div class="label">
                        <span class="label-text">Nom de la formation</span>
                    </div>
                    <select id="select_cours" name="opportunite_cours"
                        data-val="{{ old('opportunite_cours', $opportunite->idModule ?? '') }}"
                        class="select select-bordered select-sm w-full">
                    </select>
                </label>

                <div class="grid grid-cols-2 gap-4">
                    <div class="grid col-span-1">
                        <label class="w-full">
                            <div class="label">
                                <span class="label-text">Coût</span>
                            </div>
                            <input type="number" value="{{ old('opportunite_prix', $opportunite->prix ?? '') }}"
                                name="opportunite_prix" class="input input-bordered input-sm w-full" />
                        </label>
                    </div>
                    <div class="grid col-span-1">
                        <label class="w-full">
                            <div class="label">
                                <span class="label-text">Nombre de personne</span>
                            </div>
                            <input type="number"
                                value="{{ old('opportunite_nb_personne', $opportunite->nbPersonne ?? '') }}"
                                name="opportunite_nb_personne" class="input input-bordered input-sm w-full" />
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="grid col-span-1">
                        <label class="w-full">
                            <div class="label">
                                <span class="label-text after:content-['*'] after:ml-0.5 after:text-red-500">Date de
                                    début prévue</span>
                            </div>
                            <input type="date" required
                                value="{{ old('opportunite_date_deb', $opportunite->dateDeb ?? '') }}"
                                name="opportunite_date_deb" class="input input-bordered input-sm w-full" />
                            <span id="opportunite_date_deb_error" class="text-red-500 text-sm"></span>
                        </label>
                    </div>
                    <div class="grid col-span-1">
                        <label class="w-full">
                            <div class="label">
                                <span class="label-text after:content-['*'] after:ml-0.5 after:text-red-500">Date
                                    de fin prévue</span>
                            </div>
                            <input type="date" required
                                value="{{ old('opportunite_date_fin', $opportunite->dateFin ?? '') }}"
                                name="opportunite_date_fin" class="input input-bordered input-sm w-full" />
                            <span id="opportunite_date_fin_error" class="text-red-500 text-sm"></span>
                        </label>
                    </div>
                </div>

                <label class="w-full">
                    <div class="label">
                        <span class="label-text">Lieu de formation</span>
                    </div>
                    <select id="select_lieu" name="opportunite_lieu"
                        data-val="{{ old('opportunite_lieu', $opportunite->idVille ?? '') }}"
                        class="select select-bordered select-sm w-full">
                    </select>
                </label>
            </div>
        </span>

        <span>

            <p class="menu-title text-lg text-slate-700 !font-normal"><i class="fa-solid fa-info-circle text-sm"></i>
                Autres informations</p>

            <label class="w-full mb-2">
                <div class="label">
                    <span class="label-text">Source</span>
                </div>
                <input list="suggestions" value="{{ old('source', $opportunite->source ?? '') }}" name="source"
                    id="source" class="input input-bordered input-sm w-full"
                    placeholder="Source de l'opportunité">
                <datalist id="suggestions">
                    <option value="Mail">
                    <option value="Page">
                    <option value="Site web">
                    <option value="Appel">
                    <option value="Recommandation">
                    <option value="Bouche à oreille">
                </datalist>
            </label>

            <div class="grid grid-cols-4 mt-2">
                <div class="grid col-span-1">
                    <label class="cursor-pointer inline-flex items-center gap-2">
                        <input type="radio" name="opportunite_statut" value="1"
                            {{ old('opportunite_statut', isset($opportunite) && $opportunite->statut == 1 ? 'checked' : '') }}
                            class="radio checked:bg-[#4056F4]" checked="checked" />
                        <span class="label-text">Identification</span>
                    </label>
                </div>
                <div class="grid col-span-1">
                    <label class="cursor-pointer inline-flex items-center gap-2">
                        <input type="radio" name="opportunite_statut" value="2"
                            {{ old('opportunite_statut', isset($opportunite) && $opportunite->statut == 2 ? 'checked' : '') }}
                            class="radio checked:bg-[#E42548]" />
                        <span class="label-text">Offre</span>
                    </label>
                </div>
                <div class="grid col-span-1">
                    <label class="cursor-pointer inline-flex items-center gap-2">
                        <input type="radio" name="opportunite_statut" value="3"
                            {{ old('opportunite_statut', isset($opportunite) && $opportunite->statut == 3 ? 'checked' : '') }}
                            class="radio checked:bg-[#CB9801]" />
                        <span class="label-text">Rendez-vous</span>
                    </label>
                </div>
                <div class="grid col-span-1">
                    <label class="cursor-pointer inline-flex items-center gap-2">
                        <input type="radio" name="opportunite_statut" value="4"
                            {{ old('opportunite_statut', isset($opportunite) && $opportunite->statut == 4 ? 'checked' : '') }}
                            class="radio checked:bg-[#126936]" />
                        <span class="label-text">Négociation</span>
                    </label>
                </div>
                <div class="grid col-span-1">
                    <label class="cursor-pointer inline-flex items-center gap-2">
                        <input type="radio" name="opportunite_statut" value="5"
                            {{ old('opportunite_statut', isset($opportunite) && $opportunite->statut == 5 ? 'checked' : '') }}
                            class="radio checked:bg-[#041925]" />
                        <span class="label-text">Pré-réservation</span>
                    </label>
                </div>
            </div>

            <label class="w-full">
                <div class="label">
                    <span class="label-text">Note</span>
                </div>
                <textarea name="opportunite_note" class="textarea textarea-bordered w-full h-20"
                    placeholder="Ajouter ici vos remarques">{{ old('opportunite_note', $opportunite->remarque ?? '') }}</textarea>
            </label>
        </span>

        <span class="w-full inline-flex items-center justify-end gap-3">
            <button class="btn" data-bs-toggle="offcanvas" href="#offcanvasProspectionEdit">Annuler</button>
            @if ($new == true)
                <button onclick="createOpportunite()" class="btn btn-primary hover:text-white">Ajouter
                    l'opportunité</button>
            @else
                <button onclick="updateOpportunite({{ old('id_opportunite', $opportunite->id_opportunite ?? '') }})"
                    class="btn btn-primary hover:text-white">Sauvegarder les
                    modifications</button>
            @endif
        </span>
    </div>
</div>
