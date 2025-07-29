@extends($extends_containt)

@section('content')
    <div class="bg-white">

        <div class="">
            <div class="flex flex-col lg:flex-row items-center justify-between mb-12 container mx-auto py-20 px-6 lg:px-48 bg-[#f0f6ff] rounded-2xl shadow-xl">
                <div class="flex-1">
                    <h1 class="text-4xl font-bold text-navy-700 capitalize italic">{{ $customer->customerName }}</h1>
                    <p class="text-lg text-gray-600 mt-2">Secteur : <strong
                            class="text-navy-600">{{ $customer->secteur }}</strong>
                    </p>
                    <p class="text-lg text-gray-600">Membre depuis: <strong
                            class="text-navy-600">{{ \Carbon\Carbon::parse($customer->created_at)->format('Y') }}</strong>
                    </p>
                </div>
                <figure class="image-container hidden w-full h-60 lg:block flex-1 rounded-xl shadow-xl overflow-hidden transition-all duration-300 transform hover:scale-105 hover:shadow-2xl">
                    @if (isset($customer->logo))
                        <img class="w-full h-60 transition-all duration-300 ease-in-out transform hover:scale-110 hover:brightness-90"
                            src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/entreprises/{{ $customer->logo }}"
                            alt="Logo entreprise" />
                    @else
                        <span class="h-full w-full flex items-center justify-center transition-all duration-300 transform hover:scale-105">
                            <i class="fa-solid fa-image text-5xl text-slate-400"></i>
                        </span>
                    @endif
                </figure>                
            </div>
        </div>

        <div class="container mx-auto py-10 px-6 lg:px-48">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-10">

                <div class="bg-white border-[1px] border-gray-200 rounded-2xl overflow-hidden">
                    <h2 class="text-base font-semibold text-slate-700 bg-[#f0f6ff] p-3 capitalize"><i
                            class="fa-solid fa-address-book mr-3"></i>Contact</h2>
                    <div class="py-4 px-6 flex flex-col gap-1">
                        <p class="text-slate-500">Email : <span
                                class="text-slate-600">{{ $customer->customerEmail ?? 'Non renseigné' }}</span>
                        </p>
                        <p class="text-slate-500">Téléphone : <span
                                class="text-slate-600">{{ $customer->customerPhone ?? 'Non renseigné' }}</span>
                        </p>
                        <p class="text-slate-500">Adresse : <span
                                class="text-slate-600">{{ $customer->customer_addr_lot ?? 'Non renseigné' }}</span>
                        </p>
                    </div>
                </div>

                <div class="bg-white border-[1px] border-gray-200 rounded-2xl overflow-hidden">
                    <h2 class="text-base font-semibold text-slate-700 bg-[#f0f6ff] p-3 capitalize"><i
                            class="fa-solid fa-users mr-3"></i>équipe pédagogique
                    </h2>
                    <span class=" py-4 px-6 flex flex-col gap-2">
                        <p class="text-slate-500">Nom du référent principal :</p>
                        <p class="text-slate-600">{{ $user->name }} {{ $user->firstName }}</p>
                    </span>
                </div>

                <div class="bg-white border-[1px] border-gray-200 rounded-2xl overflow-hidden">
                    <h2 class="text-base font-semibold text-slate-700 bg-[#f0f6ff] p-3 capitalize"><i
                            class="fa-solid fa-quote-left mr-3"></i>slogan</h2>
                    <p class="text-gray-600 italic py-4 px-6">{{ $customer->customer_slogan ?? 'Pas de slogan' }}</p>
                </div>


            </div>

            <div class="p-6">
                <h2 class="text-2xl font-semibold text-slate-700">Description</h2>
                <p class="mt-2 text-gray-600">{{ $customer->description ?? 'Aucune description' }}</p>
            </div>
        </div>

        <div class="container mx-auto py-10 px-6 lg:px-48">
            <h1 class="text-2xl font-semibold text-slate-700 mb-4">Formations proposées par cette organisation</h1>
            <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @if (count($onlineModules) > 0)
                    @foreach ($onlineModules as $domaine)
                        <div class="col-span-4 bg-[#f2f6fd] text-[#334155] font-bold text-lg px-6 py-3 rounded-lg shadow-md">
                            <p>{{ $domaine['nomDomaine'] }}</p>
                        </div>
                        @foreach ($domaine['modules'] as $module)
                            <div class="card bg-base-100 w-full h-[28rem] overflow-hidden shadow-xl">
                                <a href="/formation/detail/{{ $module['idModule'] }}" class="hover:text-inherit h-full">
                                    <figure class="h-1/2 relative bg-slate-50">
                                        @if (isset($module['module_image']))
                                            <img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/modules/{{ $module['module_image'] }}"
                                                alt="Formation" class="h-full w-full object-fit" />
                                        @else
                                            <i class="fa-solid fa-image text-3xl text-slate-400"></i>
                                        @endif
                                        <div class="absolute top-2 left-2 w-20 h-10 overflow-hidden rounded-lg">
                                            <img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/entreprises/{{ $module['logo_cfp'] }}"
                                                alt="" class="w-full h-full object-fit">
                                        </div>
                                    </figure>
                                    <div class="card-body p-4 h-1/2">
                                        <h2 data-bs-toggle="tooltip" title="{{ $module['module_name'] }}"
                                            class="card-title line-clamp-1 text-slate-800 !text-lg">
                                            {{ $module['module_name'] }}</h2>
                                        <span class="flex flex-col gap-1 text-slate-600">
                                            <p class="line-clamp-2">
                                                {{ $module['module_description'] ?? 'Aucune description' }}</p>
                                            <p class=""><i
                                                    class="mr-1 fa-regular fa-clock"></i>{{ $module['dureeJ'] ?? 0 }}
                                                jours | {{ $module['dureeH'] ?? 0 }} heures</p>
                                            <p><i class="text-gray-600 fa-solid fa-medal"></i> {{ $module['module_level_name'] }}</p>
                                            @php
                                                $prix = floatval($module['prix'] ?? 0);
                                            @endphp
                                            <p class=""><i class="mr-1 fa-regular fa-money-bill-1"></i>A partir de
                                                <span class="font-bold">{{ number_format($prix, 2, ',', ' ') }}
                                                    Ar</span>
                                            </p>
                                            <div class="flex items-center space-x-2">
                                                @php
                                                    $averageScore = $module['note']['average'] ?? 0;
                                                    if (!function_exists('generalRound')) {
                                                        function generalRound($score)
                                                        {
                                                            return round($score * 2) / 2;
                                                        }
                                                    }
                                                    $adjustedAverage = generalRound($averageScore);
                                                @endphp
                                                <div class="flex average"
                                                    data-average="{{ $module['note']['average'] ?? 0 }}"></div>
                                                <p class="text-gray-600">{{ $adjustedAverage ?? 'N/A' }}
                                                    <span
                                                        class="text-gray-400">({{ $module['note']['totalEmployees'] ?? 0 }}
                                                        avis)</span>
                                                </p>
                                            </div>
                                        </span>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    @endforeach
                @else
                    <p class="text-slate-500 w-max">Cet organisme n'a pas encore de formation à proposer.</p>
                @endif
            </div>
        </div>

        @if ($allCollabs->count() != 0)
            <div class="container mx-auto py-10 px-6 lg:px-48">
                <h1 class="text-2xl font-semibold text-slate-700 mb-4">Liste des Formateurs</h1>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-4">
                    @foreach ($allCollabs as $collab)
                        <div class="card w-full h-96 shadow-xl">
                            <figure class="h-2/4 bg-slate-100 overflow-hidden">
                                @if ($collab->photoForm)
                                    <img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/formateurs/{{ $collab->photoForm }}"
                                        alt="Formateur" class="w-full h-full object-cover" />
                                @else
                                    <i class="fa-solid fa-user-graduate text-3xl text-slate-400"></i>
                                @endif
                            </figure>
                            <div class="card-body h-2/4">
                                <h2 class="card-title"> {{ $collab->name }} {{ $collab->firstName }}</h2>
                                <p class="text-gray-600 text-lg italic"><i class="fa-solid fa-briefcase mr-1"></i> {{ !isset($collab->form_speciality) ? "--" : $collab->form_speciality }} </p>
                                <p class="text-gray-600 text-lg italic"><i class="fa-solid fa-building mr-1"></i> {{ !isset($collab->form_titre) ? "Formateur" : $collab->form_titre }} </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>    
        @endif
        @include('layouts.homeFooter')    
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/jquery.raty.js') }}"></script>
    <script src="{{ asset('js/global_js.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.average').each(function() {
                var average = $(this).data('average');
                ratyNotationFormation($(this), average);
            });
        });

        function ratyNotationFormation(element, average) {
            $(element).raty({
                score: average,
                readOnly: true
            });

            $(`.average img`).addClass(`w-5 h-5`);
        }
    </script>
@endsection
