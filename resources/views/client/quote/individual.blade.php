@extends($extends_containt)
@php
    $required = "after:content-['*'] after:ml-0.5 after:text-red-500";
@endphp

@section('content')
    <div
        class="px-6 md:px-14 lg:px-16 xl:px-20 bg-white w-full max-w-screen-md mx-auto lg:mx-20 xl:mx-60  space-y-6 py-6 mb-10 rounded-lg">
        <div class="flex flex-row items-center">
            <img src="https://www.skills.hr/images/picto/rocket-7f7bbec200661130cddac0dcb0efb107.svg?vsn=d" alt="">
            <p class="text-xl font-semibold ml-2">Quelques mots pour mieux vous connaître</p>
        </div>
        <div class="space-y-6">
            <div class="">
                <div class="lg:grid grid-cols-2 gap-6 space-y-3 lg:space-y-0">
                    <div>
                        <label class="w-full">
                            <div class="label">
                                <span class="label-text {{ $required }}">Nom</span>
                            </div>
                            <input name="name" class="input input-bordered w-full" required>
                            <div class="label">
                                <span class="label-text-alt name_error text-red-500"></span>
                            </div>
                        </label>
                    </div>
                    <div>
                        <label class="w-full">
                            <div class="label">
                                <span class="label-text">Prénom</span>
                            </div>
                            <input name="firstname" class="input input-bordered w-full">
                            <div class="label">
                                <span class="label-text-alt"></span>
                            </div>
                        </label>
                    </div>
                </div>
                <div>
                    <label class="w-full">
                        <div class="label">
                            <span class="label-text">Situation professionnelle</span>
                        </div>
                        <input name="situationPro" class="input input-bordered w-full">
                        <div class="label">
                            <span class="label-text-alt"></span>
                        </div>
                    </label>
                </div>
                <div class="lg:grid grid-cols-2 gap-6 space-y-3 lg:space-y-0">
                    <div>
                        <label class="w-full">
                            <div class="label">
                                <span class="label-text {{ $required }}">Email</span>
                            </div>
                            <input type="email" name="email" class="input input-bordered w-full" required>
                            <div class="label">
                                <span class="label-text-alt email_error text-red-500"></span>
                            </div>
                        </label>
                    </div>
                    <div>
                        <label class="w-full">
                            <div class="label">
                                <span class="label-text">Téléphone</span>
                            </div>
                            <input name="phone" class="input input-bordered w-full">
                            <div class="label">
                                <span class="label-text-alt"></span>
                            </div>
                        </label>
                    </div>
                </div>
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
                            <span class="label-text">Option de financement</span>
                        </div>
                        <select name="financement" class="select select-bordered w-full">
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
                        <input type="date" name="dateDeb" class="input input-bordered w-full" required>
                        <div class="label">
                            <span class="label-text-alt dateDeb_error text-red-500"></span>
                        </div>
                    </label>
                    <label class="w-full">
                        <div class="label">
                            <span class="label-text {{ $required }}">Date de fin de formation</span>
                        </div>
                        <input type="date" name="dateFin" class="input input-bordered w-full" required>
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
            <button onclick="sendDemandIndividual()" class="btn btn-primary w-full hover:text-white">
                <span id="spin" class="hidden loading loading-spinner loading-sm"></span>
                Envoyer ma demande</button>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function sendDemandIndividual() {
            var spin = $('#spin');
            // Envoi de la requête AJAX
            $.ajax({
                type: "POST",
                url: "/demande_devis/individual",
                data: {
                    _token: '{!! csrf_token() !!}',
                    idVille: 1,
                    idModule: {!! json_encode($module) !!},
                    name: $('input[name="name"]').val(),
                    firstname: $('input[name="firstname"]').val(),
                    situationPro: $('input[name="situationPro"]').val(),
                    email: $('input[name="email"]').val(),
                    phone: $('input[name="phone"]').val(),
                    modalite: $('select[name="modalite"]').val(),
                    financement: $('select[name="financement"]').val(),
                    dateDeb: $('input[name="dateDeb"]').val(),
                    dateFin: $('input[name="dateFin"]').val(),
                    lieu_formation: $('input[name="lieu_formation"]').val(),
                    note: $('textarea[name="note"]').val(),
                    idCustomer: {!! json_encode($etpId) !!},
                },
                dataType: "json",
                beforeSend: function() {
                    spin.removeClass('hidden');
                },
                success: function(res) {
                    toastr.success(res.success, 'Opération effectuée avec succès', {
                        timeOut: 1500
                    });
                    location.reload();
                },
                error: function(res) {
                    $('.name_error').text('');
                    $('.email_error').text('');
                    $('.dateDeb_error').text('');
                    $('.dateFin_error').text('');

                    var res = res.responseJSON.error;

                    $('.name_error').text(res.name);
                    $('.email_error').text(res.email);
                    $('.dateDeb_error').text(res.dateDeb);
                    $('.dateFin_error').text(res.dateFin);
                }
            });

        }
    </script>
@endsection
