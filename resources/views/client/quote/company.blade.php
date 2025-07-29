@extends($extends_containt)
@php
    $required = "after:content-['*'] after:ml-0.5 after:text-red-500";
@endphp

@section('content')
    <div
        class="px-6 md:px-14 lg:px-16 xl:px-20 bg-white lg:mx-20 xl:mx-60 w-full max-w-screen-md mx-auto  space-y-6 py-6 rounded-lg">
        <div class="flex flex-row items-center">
            <img src="https://www.skills.hr/images/picto/rocket-7f7bbec200661130cddac0dcb0efb107.svg?vsn=d" alt="">
            <p class="text-xl font-semibold ml-2">Quelques mots pour mieux vous connaître</p>
        </div>

        {{-- <div class="bg-gray-100 rounded-xl p-4">
            Vous être en charge des formations de vos équipes ? Créez votre compte gratuit afin de suivre l'avancé de vos
            demandes et découvrir nos solutions RH
        </div> --}}

        <div class="space-y-6">
            <div class="">
                <div>
                    <label class="w-full">
                        <div class="label">
                            <span class="label-text {{ $required }}">Nom de l'entreprise</span>
                        </div>
                        <input name="etp_name" class="input input-bordered w-full">
                        <div class="label">
                            <span class="label-text-alt etp_name_error text-red-500"></span>
                        </div>
                    </label>
                </div>
                <div class="lg:grid grid-cols-2 gap-6 space-y-3 lg:space-y-0">
                    <div>
                        <label class="w-full">
                            <div class="label">
                                <span class="label-text {{ $required }}">Email de l'entreprise</span>
                            </div>
                            <input type="email" name="etp_email" class="input input-bordered w-full">
                            <div class="label">
                                <span class="label-text-alt etp_email_error text-red-500"></span>
                            </div>
                        </label>
                    </div>
                    <div>
                        <label class="w-full">
                            <div class="label">
                                <span class="label-text">Téléphone de l'entreprise</span>
                            </div>
                            <input name="etp_phone" class="input input-bordered w-full">
                            <div class="label">
                                <span class="label-text-alt"></span>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="lg:grid grid-cols-2 gap-6 space-y-3 lg:space-y-0">
                    <div>
                        <label class="w-full">
                            <div class="label">
                                <span class="label-text {{ $required }}">Nom du responsable</span>
                            </div>
                            <input name="ref_name" class="input input-bordered w-full">
                            <div class="label">
                                <span class="label-text-alt ref_name_error text-red-500"></span>
                            </div>
                        </label>
                    </div>
                    <div>
                        <label class="w-full">
                            <div class="label">
                                <span class="label-text">Prénom du responsable</span>
                            </div>
                            <input name="ref_firstName" class="input input-bordered w-full">
                            <div class="label">
                                <span class="label-text-alt"></span>
                            </div>
                        </label>
                    </div>
                </div>
                <div>
                </div>

                <div>
                    <label class="w-full">
                        <div class="label">
                            <span class="label-text">Type de projet</span>
                        </div>
                        <select name="type_projet" class="select select-bordered w-full">
                            <option value="1">Intra (Projet pour une seule entreprise)</option>
                            <option value="2">Inter (Projet pour plusieurs entreprises et des particuliers)
                            </option>
                        </select>
                        <div class="label">
                            <span class="label-text-alt"></span>
                        </div>
                    </label>
                </div>

                <div class="lg:grid grid-cols-2 gap-6 space-y-3 lg:space-y-0">
                    <div>
                        <label class="w-full">
                            <div class="label">
                                <span class="label-text">Modalité</span>
                            </div>
                            <select name="modalite" class="select select-bordered w-full">
                                <option value="1">Présentielle</option>
                                <option value="2">En ligne</option>
                                <option value="3">Blended</option>
                            </select>
                            <div class="label">
                                <span class="label-text-alt"></span>
                            </div>
                        </label>
                    </div>
                    <div>
                        <label class="w-full">
                            <div class="label">
                                <span class="label-text">Nombre d'apprenants prévus</span>
                            </div>
                            <input type="number" min="0" name="nb_appr" class="input input-bordered w-full">
                            <div class="label">
                                <span class="label-text-alt"></span>
                            </div>
                        </label>
                    </div>
                </div>
                <div>
                    <label class="w-full">
                        <div class="label">
                            <span class="label-text">Option de financement</span>
                        </div>
                        <select name="type_financement" class="select select-bordered w-full">
                            <option value="1">FMFP</option>
                            <option value="2">Fond propre</option>
                            <option value="3">Autres</option>
                        </select>
                        <div class="label">
                            <span class="label-text-alt"></span>
                        </div>
                    </label>
                </div>
                <div class="inline-flex items-center gap-6 w-full">
                    <label class="w-full">
                        <div class="label">
                            <span class="label-text {{ $required }}">Date de début de formation</span>
                        </div>
                        <input type="date" name="dateDeb" class="input input-bordered w-full">
                        <div class="label">
                            <span class="label-text-alt dateDeb_error text-red-500"></span>
                        </div>
                    </label>
                    <label class="w-full">
                        <div class="label">
                            <span class="label-text {{ $required }}">Date de fin de formation</span>
                        </div>
                        <input type="date" name="dateFin" class="input input-bordered w-full">
                        <div class="label">
                            <span class="label-text-alt dateFin_error text-red-500"></span>
                        </div>
                    </label>
                </div>
            </div>

            <div>
                <label class="w-full">
                    <div class="label">
                        <span class="label-text">Lieu de formation</span>
                    </div>
                    <input name="lieu_formation" class="input input-bordered w-full">
                    <div class="label">
                        <span class="label-text-alt"></span>
                    </div>
                </label>
            </div>
            <div class="space-y-1">
                <label class="w-full">
                    <div class="label">
                        <span class="label-text">Dites-nous plus sur vos besoins</span>
                    </div>
                    <textarea name="note" class="textarea textarea-bordered h-24 w-full" placeholder="Ecrivez ici vos remarques"></textarea>
                    <div class="label">
                        <span class="label-text-alt"></span>
                    </div>
                </label>
            </div>
        </div>

        <div class="flex flex-col items-center justify-center space-y-2">
            <button onclick="sendDemandCompany()" class="btn btn-primary w-full hover:text-white">
                <span id="spin" class="hidden loading loading-spinner loading-sm"></span>
                Envoyer ma demande</button>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function sendDemandCompany() {
            // Récupération des données du formulaire
            const data = {
                _token: '{!! csrf_token() !!}',
                idVille: 1,
                idModule: {!! json_encode($module) !!},
                etp_name: $('input[name="etp_name"]').val(),
                etp_email: $('input[name="etp_email"]').val(),
                etp_phone: $('input[name="etp_phone"]').val(),
                ref_name: $('input[name="ref_name"]').val(),
                ref_firstName: $('input[name="ref_firstName"]').val(),
                project_type: $('select[name="type_projet"]').val(),
                modalite: $('select[name="modalite"]').val(),
                nb_appr: $('input[name="nb_appr"]').val(),
                financement: $('select[name="type_financement"]').val(),
                dateDeb: $('input[name="dateDeb"]').val(),
                dateFin: $('input[name="dateFin"]').val(),
                lieu_formation: $('input[name="lieu_formation"]').val(),
                note: $('textarea[name="note"]').val(),
                idCustomer: {!! json_encode($etpId) !!},
            };

            var spin = $('#spin');
            // Envoi de la requête AJAX
            $.ajax({
                type: "POST",
                url: "/demande_devis/company",
                data: data,
                dataType: "json",
                beforeSend: function() {
                    spin.removeClass('hidden');
                },
                success: function(res) {
                    toastr.success(res.message, 'Opération effectuée avec succès', {
                        timeOut: 2000
                    });
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                },
                error: function(res) {
                    $('.etp_name_error').text('');
                    $('.ref_name_error').text('');
                    $('.email_error').text('');
                    $('.dateDeb_error').text('');
                    $('.dateFin_error').text('');

                    var res = res.responseJSON.error;

                    $('.etp_name_error').text(res.etp_name);
                    $('.ref_name_error').text(res.ref_name);
                    $('.etp_email_error').text(res.etp_email);
                    $('.dateDeb_error').text(res.dateDeb);
                    $('.dateFin_error').text(res.dateFin);
                }
            });

        }
    </script>
@endsection
