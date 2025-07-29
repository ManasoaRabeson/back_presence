@extends($extends_containt)

@push('custom_style')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
@endpush

@section('content')
    <div class="w-full mx-auto">
        @if (session('success'))
            <x-alert-success message="{{ session('success') }}" />
        @elseif (session('error'))
            <x-alert-error message="{{ session('error ') }}" />
        @endif


        <div class="flex items-center justify-center w-full">

            <div class="grid w-full grid-cols-1 gap-8 lg:grid-cols-3 max-w-7xl">

                <div class="p-6 lg:h-auto border border-[#e8eef7] rounded-lg shadow-xl">

                    <div class="bg-[#f1f3f5] rounded-lg p-8 mb-8">
                        <p class="text-xl font-semibold"> Par téléphone</p>
                        <p class="mt-4">Du lundi au vendredi de 8h00 à 17h</p>
                        <p class="mt-2 text-lg"><i class="fa-solid fa-phone mr-2 text-base text-[#ffb93e]"></i> +261 32 03
                            231 44</p>
                    </div>


                    <div class="bg-[#f1f3f5] rounded-lg p-8 mb-8 relative">
                        <p class="text-xl font-semibold">Adresse</p>
                        <p class="mt-2 text-base"><i class="fa-solid fa-location-dot mr-2 text-sm text-[#ffb93e]"></i> II N
                            59 CI Analamahitsy 101 Antananarivo Madagascar</p>

                        <div id="map" class="mt-4 h-[300px] w-full z-0"></div>
                    </div>

                </div>

                <div class="p-6 rounded-lg shadow-xl col-span-2 w-full lg:w-auto border border-[#e8eef7]">

                    <p class="mb-4 ml-2 text-2xl font-semibold">Prenez contact avec Upskill</p>

                    <form action="{{ route('send.email') }}" method="POST">
                        @csrf

                        <div class="p-8 mb-4 rounded-lg bg-slate-50">
                            <p class="mb-4 text-sm font-semibold">* Tous les champs ci-dessous sont obligatoires</p>

                            <div class="mb-4">
                                <label for="nom" class="block mb-2 text-slate-700">Nom et prénom :</label>
                                <input type="text" id="nom" name="nom" class="w-full input input-bordered"
                                    placeholder="Votre nom et prénom">
                            </div>

                            <div class="mb-4">
                                <label for="fonction" class="block mb-2 text-slate-700">Fonction :</label>
                                <input type="text" id="fonction" name="fonction" class="w-full input input-bordered"
                                    placeholder="Votre fonction">
                            </div>

                            <div class="mb-4">
                                <label for="email" class="block mb-2 text-slate-700">Email :</label>
                                <input type="email" id="email" name="email" class="w-full input input-bordered"
                                    placeholder="Votre adresse email">
                            </div>

                            <div class="mb-4">
                                <label for="telephone" class="block mb-2 text-slate-700">Téléphone :</label>
                                <input type="tel" id="telephone" name="telephone" class="w-full input input-bordered"
                                    placeholder="+261 34 00 000 00">
                            </div>

                            <div class="mb-4">
                                <label for="entreprise" class="block mb-2 text-slate-700">Entreprise / Organisation
                                    :</label>
                                <input type="text" id="entreprise" name="entreprise" class="w-full input input-bordered"
                                    placeholder="Le nom de votre entreprise.">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="demandeFormation" class="block mb-2 font-semibold text-slate-700">Votre demande
                                concerne la formation :</label>
                            <textarea id="demandeFormation" name="demandeFormation" rows="4" class="w-full h-48 input input-bordered"
                                placeholder="Décrivez la formation souhaitée..."></textarea>
                        </div>

                        <div class="mb-4">
                            <strong>Recaptcha:</strong>
                            <div class="g-recaptcha" data-sitekey="{{ env('GOOGLE_RECAPTCHA_SITE_KEY') }}"></div>
                            @if ($errors->has('g-recaptcha-response'))
                                <span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
                            @endif
                        </div>

                        <div>
                            <button type="submit"
                                class="bg-[#a462a4] text-[#ffffff] p-4 rounded-lg text-sm transition-transform duration-300 ease-in-out transform hover:scale-105 hover:shadow-xl">
                                Envoyer la demande
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        $(document).ready(function() {
            timeout()
        });

        function timeout() {
            setTimeout(function() {
                $('#success-message').fadeOut('slow');
            }, 3000);
        }

        var map = L.map('map').setView([-18.876092768405154, 47.55020208369932], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        L.marker([-18.876092768405154, 47.55020208369932]).addTo(map);
    </script>
@endsection
