@php
    $opportunite ??= null;
@endphp

{{-- @dump($opportunite) --}}
<ul class="stepper linear">
    <li class="step active">
        <div class="step-title waves-effect">Informations</div>
        <div class="step-content">
            <input type="hidden" name="dateDeb" value="{{ $opportunite['op_dateDeb'] }}">
            <input type="hidden" name="dateFin" value="{{ $opportunite['op_dateFin'] }}">
            <input type="hidden" name="description_projet" value="{{ $opportunite['remarque'] }}">
            <input type="hidden" name="idModule" value="{{ $opportunite['idModule'] }}">
            <input type="hidden" name="idEtp" value="{{ $opportunite['idEtp'] }}">
            <input type="hidden" name="ville" value="{{ $opportunite['ville'] }}">
            <div class="flex flex-col w-full">
                <label class="w-full">
                    <div class="label">
                        <span class="label-text">Type de projet</span>
                    </div>
                    <select name="type_projet" class="select select-bordered select-sm w-full">
                        <option value="1" selected>Intra (Projet pour une seule entreprise)</option>
                        <option value="2">Inter (Projet pour plusieurs entreprises et les particuliers)</option>
                    </select>
                    <div class="label">
                        <span class="label-text-alt"></span>
                    </div>
                </label>
                <label class="w-full">
                    <div class="label">
                        <span class="label-text">Titre du projet</span>
                    </div>
                    <input type="text" name="titre_projet" class="input input-bordered input-sm w-full" />
                    <div class="label">
                        <span class="label-text-alt"></span>
                    </div>
                </label>
                <label class="w-full">
                    <div class="label">
                        <span class="label-text">Modalité</span>
                    </div>
                    <select name="modalite_projet" class="select select-bordered select-sm w-full">
                        <option value="1" selected>Présentielle</option>
                        <option value="2">En ligne</option>
                        <option value="3">Blended</option>
                    </select>
                    <div class="label">
                        <span class="label-text-alt"></span>
                    </div>
                </label>
            </div>
            <div class="step-actions">
                <!-- Here goes your actions buttons -->
                <button class="btn next-step btn-primary">Etape suivante <i class="fa-solid fa-arrow-right"></i> A
                    propos du client</button>
            </div>
        </div>
    </li>
    <li class="step">
        <div class="step-title waves-effect">A propos du client</div>
        <div class="step-content">
            <div class="flex flex-col w-full">
                <label class="w-full">
                    <div class="label">
                        <span class="label-text">Nom de l'entreprise</span>
                    </div>
                    <input type="text" name="nom_etp" value="{{ $opportunite['etp_name'] }}"
                        class="input input-bordered input-sm w-full" />
                    <div class="label">
                        <span class="label-text-alt"></span>
                    </div>
                </label>
                <span class="inline-flex items-center gap-4">
                    @if (!isset($opportunite['idEtp']))
                        <label class="w-full">
                            <div class="label">
                                <span class="label-text">Numéro d'Identification Fiscale (NIF)</span>
                            </div>
                            <input type="text" name="nif_etp" class="input input-bordered input-sm w-full" />
                            <div class="label">
                                <span class="label-text-alt"></span>
                            </div>
                        </label>
                    @endif
                    <label class="w-full">
                        <div class="label">
                            <span class="label-text">Email</span>
                        </div>
                        <input type="email" name="email_etp" value="{{ $opportunite['etp_email'] }}"
                            class="input input-bordered input-sm w-full" />
                        <div class="label">
                            <span class="label-text-alt"></span>
                        </div>
                    </label>
                </span>
                <label class="w-full">
                    <div class="label">
                        <span class="label-text">Nom du responsable</span>
                    </div>
                    <input type="text" name="ref_name_etp" value="{{ $opportunite['ref_name'] }}"
                        class="input input-bordered input-sm w-full" />
                    <div class="label">
                        <span class="label-text-alt"></span>
                    </div>
                </label>
                <label class="w-full">
                    <div class="label">
                        <span class="label-text">Prénom du responsable</span>
                    </div>
                    <input type="text" name="ref_firstname_etp" value="{{ $opportunite['ref_firstName'] }}"
                        class="input input-bordered input-sm w-full" />
                    <div class="label">
                        <span class="label-text-alt"></span>
                    </div>
                </label>
            </div>
            <div class="step-actions">
                <!-- Here goes your actions buttons -->
                <button class="btn previous-step">Revenir</button>
                <button onclick="submitOp({{ $opportunite['id_opportunite'] }})"
                    class="btn next-step btn-primary">Finalisation du projet</button>
            </div>
        </div>
    </li>
    {{-- <li class="step">
        <div class="step-title waves-effect">Classification du projet</div>
        <div class="step-content">
            <div class="flex flex-col gap-4 w-full">
                <div role="alert" class="alert bg-blue-100 mt-2">
                    <i class="fa-solid fa-info-circle text-blue-500"></i>
                    <span class="text-lg">Veuillez assigné ce projet à un dossier pour mieux le retrouver à
                        l'avenir.</span>
                </div>
                <div class="flex min-w-[500px] overflow-x-auto w-full">
                    <div class="w-full grid grid-cols-2 gap-3 h-[22rem] justify-start">
                        <div class="grid col-span-2 h-max">
                            <button class="btn btn-sm btn-outline btn-primary w-max btn_fileTableIntra">
                                <i class="fa-solid fa-folder-plus"></i>
                                Nouveau dossier
                            </button>
                        </div>
                        <div class="grid col-span-1 h-[19rem] overflow-y-auto">
                            <table class="table fileTableIntra fileTable bg-white h-max">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th>
                                            Nom du dossier
                                        </th>
                                        <th class="text-right">
                                            Document
                                        </th>
                                        <th class="text-right">
                                            Projet
                                        </th>
                                        <th>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>

                        <div class="grid col-span-1 h-[19rem] overflow-y-auto">
                            <table class="table bg-white h-max">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th>
                                            Nom du dossier
                                        </th>
                                        <th class="text-right">
                                            Document
                                        </th>
                                        <th class="text-right">
                                            Projet
                                        </th>
                                        <th>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="fileTableSelected">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="step-actions">
                <!-- Here goes your actions buttons -->
                <button class="btn previous-step">Revenir</button>
                <button class="btn next-step btn-primary">Finalisation</button>
            </div>
        </div>
    </li> --}}
</ul>
