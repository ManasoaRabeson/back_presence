@php
    $nif ??= '';
    $stat ??= '';
    $rcs ??= '';
    $idEtp ??= '';
    $name ??= '';
    $email ??= '';
    $phone ??= '';
    $lot ??= '';
    $quartier ??= '';
    $ville_codeds ??= '';
@endphp

<div class="offcanvas offcanvas-end !w-[45em]" tabindex="-1" id="offcanvas" aria-labelledby="offcanvas">
    <div class="flex flex-col w-full">
        <div class="inline-flex items-center justify-between w-full px-4 py-2 bg-gray-100">
            <p class="text-lg font-medium text-gray-500">Modifier un client</p>
            <a data-bs-toggle="offcanvas" href="#offcanvas" role="button" aria-controls="offcanvas"
                class="flex items-center justify-center w-10 h-10 duration-200 rounded-md cursor-pointer hover:text-inherit hover:bg-gray-200">
                <i class="text-gray-500 fa-solid fa-xmark"></i>
            </a>
        </div>

        <div class="w-full overflow-x-auto p-4 overflow-y-auto h-[100vh] pb-3">
            <div class="flex flex-col gap-y-3">
                <div class="">
                    <div class="">
                        <div class="flex items-center gap-3">
                            <x-input name="etp_nif_edit" label="NIF" value="{{ $nif }}" />
                            <x-input name="etp_stat_edit" label="STAT" value="{{ $stat }}" />
                        </div>
                        <x-input name="etp_rcs_edit" label="RCS" value="{{ $rcs }}" />
                    </div>
                </div>

                <hr class="border-[1px] border-gray-400 mt-2">

                <div class="flex flex-col gap-2">
                    <input type="hidden" id="id_entreprise_hidden" value="{{ $idEtp }}">

                    <label class="text-lg font-medium text-gray-600">Informations de base</label>
                    <x-input name="etp_name_edit" label="Nom de l'entreprise" value="{{ $name }}" />
                    <div class="grid grid-cols-2 gap-3">
                        <x-input name="etp_email_edit" type="email" label="Mail" value="{{ $email }}" />
                        <x-input name="etp_phone_edit" type="tel" label="Téléphone" value="{{ $phone }}" />
                    </div>

                    <hr class="border-[1px] border-gray-400 mt-4">
                    <label class="text-lg font-medium text-gray-600">Localisation</label>
                    <div class="grid grid-cols-2 gap-3">
                        <x-input name="etp_lot_edit" label="Lot" value="{{ $lot }}" />
                        <x-input name="etp_qrt_edit" label="Quartier" value="{{ $quartier }}" />
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="flex flex-col w-full gap-1">
                            <label for="ville" class="text-gray-600">Code postal et Ville</label>
                            <select name="etp_ville_edit" id="etp_ville_edit"
                                class="bg-white password-toggle outline-none w-full bg-transparent pl-2 h-12 border-[1px] border-slate-200 rounded-md hover:border-purple-300 focus:border-purple-300 focus:ring-2 focus:ring-purple-100 duration-200 text-gray-700">
                                @foreach ($villeCodeds as $vc)
                                    <option value="{{ $vc->id }}">{{ $vc->vi_code_postal }} -
                                        {{ $vc->ville_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="inline-flex items-end justify-end w-full pt-3 mb-10">
                <x-btn-ghost>
                    <a data-bs-toggle="offcanvas" href="#offcanvas" role="button" class="hover:text-inherit">
                        Annuler
                    </a>
                </x-btn-ghost>
                <div onclick="updateClient()" class="btn btn-primary bg-[#A462A4] cursor-pointer">Sauvegarder les
                    modifications</div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateClient() {
        var idEtp = $('#id_entreprise_hidden').val();

        $.ajax({
            type: "patch",
            url: "/cfp/invites/etp/" + idEtp + "/update",
            data: {
                _token: '{!! csrf_token() !!}',
                etp_nif: $('#etp_nif_edit').val(),
                etp_stat: $('#etp_stat_edit').val(),
                etp_rcs: $('#etp_rcs_edit').val(),
                etp_name: $('#etp_name_edit').val(),
                etp_phone: $('#etp_phone_edit').val(),
                etp_email: $('#etp_email_edit').val(),
                etp_addr_lot: $('#etp_lot_edit').val(),
                etp_addr_quartier: $('#etp_qrt_edit').val(),
                etp_ville_id: $('#etp_ville_edit').val(),
                etp_referent_name: $('#etp_ref_name_edit').val(),
                etp_referent_firstname: $('#etp_ref_firstname_edit').val(),
            },
            dataType: "json",
            success: function(res) {
                if (res.success) {
                    // Mise à jour directe des valeurs affichées
                    $('#edit-nif').text($('#etp_nif_edit').val() || 'Pas de numero NIF');
                    $('#edit-stat').text($('#etp_stat_edit').val() || 'Pas de numero STAT');
                    $('#edit-name').text($('#etp_name_edit').val() || 'pas de nom d\'entreprise');
                    $('#edit-adresse').text(
                        ($('#etp_lot_edit').val() || '') + ' ' + ($('#etp_qrt_edit').val() || '') ||
                        'pas d\'adresse'
                    );
                    $('#edit-codePostal').text(
                        $('#etp_ville_edit option:selected').text() || 'Madagascar'
                    );

                    toastr.success(res.success, 'Succès', {
                        timeOut: 1500
                    });
                } else {
                    console.log(res.error);
                    toastr.error("Erreur inconnue !", 'Erreur', {
                        timeOut: 1500
                    });
                }
            },
            error: function(err) {
                console.error(err);
                toastr.error("Erreur de mise à jour !", 'Erreur', {
                    timeOut: 1500
                });
            }
        });
    }
</script>
