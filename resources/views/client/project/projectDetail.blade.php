@extends($extends_containt)

@push('custom_style')
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
@endpush

@section('content')
    <div class="w-full h-full overflow-y-scroll bg-gray-50">
        <div class="w-full h-full max-w-screen-xl mx-auto">
            <div class="pt-3 pb-10 text-base">
                <div class="my-4 lg:mx-4 ">
                    {{-- <div>
                        <img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/modules/{{ $module->module_image }}"
                            class="object-fill w-full px-2 lg:px-0 h-80 rounded-xl">
                        @if (session('error'))
                            <div id="errorAlert"
                                class="absolute top-16 left-1/2 transform -translate-x-1/2 bg-[#F8D7DA] w-[50rem] border border-[#DC3445] text-[#842029] rounded px-3 py-2 flex justify-between items-center z-50"
                                role="alert">
                                <p class="text-lg">{{ session('error') }}</p>
                                <strong class="text-xl cursor-pointer close-alert">&times;</strong>
                            </div>
                        @endif
                    </div>

                    <div class="relative flex flex-col justify-center pb-40 mx-10 space-y-2 xl:mx-40">
                        <div class="absolute w-full h-auto p-4 text-center bg-[#f9fafb] rounded-lg shadow-sm -top-16">
                            <p class="mt-3 text-2xl font-bold text-center text-gray-800">{{ $module->moduleName }}</p>
                            <p class="mt-2 text-base font-normal text-gray-600">
                                {{ $module->description ? $module->description : 'Aucune description' }}
                            </p>
                            <div class="flex flex-col items-center mt-3 space-y-1">
                                @php
                                    $averageScore = $note['average'] ?? 0;
                                    if (!function_exists('generalRound')) {
                                        function generalRound($score)
                                        {
                                            return round($score * 2) / 2;
                                        }
                                    }
                                    $adjustedAverage = generalRound($averageScore);
                                @endphp
                                <div class="flex average" data-average="{{ $note['average'] }}"></div>
                                <p class="text-gray-600">
                                    {{ $adjustedAverage }}
                                    <span class="text-gray-400">({{ $note['totalEmployees'] }} avis)</span>
                                </p>
                            </div>
                        </div>
                    </div> --}}

                    <div
                        class="hero bg-[#f1f5f9] mt-10 p-8 flex flex-col lg:flex-row rounded-xl shadow-lg border border-dashed border-gray-200 mb-10 transition-all duration-300 hover:shadow-xl">
                        <div
                            class="lg:w-1/2 relative overflow-hidden rounded-l-xl transition-transform duration-300 hover:scale-105">
                            <img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/modules/{{ $module->module_image }}"
                                alt="Produit" class="w-full h-96 rounded-l-xl">
                        </div>
                        <div class="lg:w-1/2 lg:pl-8 flex flex-col justify-center p-6">
                            <p
                                class="mt-3 text-4xl font-bold text-center text-gray-800 transition-colors duration-300 hover:text-gray-900">
                                {{ $module->moduleName }}</p>
                            <p class="mt-2 text-lg font-normal text-center text-gray-600 leading-relaxed">
                                {{ $module->description ? $module->description : 'Aucune description' }}
                            </p>
                            <div class="flex flex-col items-center mt-4 space-y-2">
                                @php
                                    $averageScore = $note['average'] ?? 0;
                                    if (!function_exists('generalRound')) {
                                        function generalRound($score)
                                        {
                                            return round($score * 2) / 2;
                                        }
                                    }
                                    $adjustedAverage = generalRound($averageScore);
                                @endphp
                                <div class="flex average" data-average="{{ $note['average'] }}"></div>
                                <p class="text-gray-600 text-lg font-medium">
                                    {{ $adjustedAverage }}
                                    <span class="text-gray-400 text-sm">({{ $note['totalEmployees'] }} avis)</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col-reverse h-full p-4 bg-[#f9fafb] lg:flex-row lg:space-x-6 rounded-2xl">

                        <div class="w-full h-full space-y-10 lg:w-9/12">

                            <div class="flex flex-col gap-y-1" id="session">
                                <h3 class="text-xl font-bold text-gray-700">A propos de cette formation</h3>
                                <div
                                    class="grid h-full grid-cols-2 py-2 mt-3 text-center bg-white border border-gray-200 divide-x divide-gray-200 rounded-lg md:grid-cols-4 gap-y-3">
                                    <div class="grid col-span-1 pt-3">
                                        <span><i class="text-gray-600 fa-solid fa-medal fa-2xl"></i></span>
                                        <div class="px-2 py-3 pt-3 text-lg text-gray-600">
                                            {{ $module->module_level_name }}
                                        </div>
                                    </div>
                                    <div class="grid col-span-1 pt-3">
                                        <span><i class="text-gray-600 fa-solid fa-money-bill fa-2xl"></i></span>
                                        <div class="px-2 py-3 pt-3 text-lg text-gray-600">
                                            A partir de {{ number_format($module->prix, 2, ',', ' ') }} Ar HT
                                        </div>
                                    </div>
                                    <div class="grid col-span-1 pt-3">
                                        <span><i class="text-gray-600 fa-solid fa-clock fa-2xl"></i></span>
                                        <div class="px-2 py-3 pt-3 text-lg text-gray-600">
                                            {{ $module->dureeJ }} jours | {{ $module->dureeH }} heures
                                        </div>
                                    </div>
                                    <div class="grid col-span-1 pt-3">
                                        <span>
                                            <i class="text-gray-600 fa-solid fa-person fa-2xl"></i>
                                        </span>
                                        <div class="px-2 py-3 pt-3 text-lg text-gray-600">
                                            {{ $module->minApprenant }}
                                            @if ($module->maxApprenant > 0)
                                                à {{ $module->maxApprenant }}
                                            @endif personnes
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col gap-y-1">
                                <h1 class="text-xl font-bold text-gray-700">Sessions</h1>

                                <div class="space-y-4 bg-white">
                                    @foreach ($projects_with_sessions as $projectId => $projectData)
                                        <div class="p-6 border border-gray-200 rounded-lg">
                                            <div
                                                class="flex flex-col items-center justify-between space-y-3 text-center lg:space-y-0 lg:flex-row ">
                                                <div>
                                                    <a href="#"
                                                        class="text-blue-500 more-info hover:underline underline-offset-4"
                                                        data-id="{{ $projectId }}"><i
                                                            class="mr-1 fa-regular fa-calendar-days"></i> Du
                                                        {{ $projectData['projectStartDate'] }} -
                                                        {{ $projectData['projectEndDate'] }}</a>
                                                </div>
                                                <span class="text-gray-600">
                                                    <i class="mr-1 fa-solid fa-location-dot"></i>
                                                    {{ $projectData['ville'] }}
                                                </span>
                                                <span class="text-gray-600">
                                                    <i class="mr-1 fa-solid fa-money-bill"></i>
                                                    {{ number_format($module->prix, 2, ',', ' ') }} AR
                                                </span>
                                                @if ($projectData['availability'] == 1)
                                                    <span class="px-4 py-1 text-sm text-green-500 bg-green-100 rounded-xl">
                                                        Places disponiples
                                                    </span>
                                                    <div>
                                                        @guest
                                                            <a href="#"
                                                                class="text-white btn btn-sm btn-primary openModalButton"
                                                                data-id="{{ $projectId }}">S'inscrire</a>
                                                        @else
                                                            @if ($projectData['nbPlace'] === 0)
                                                                <a href="/formation/reservation/{{ $projectId }}"
                                                                    class="text-white btn btn-sm btn-primary">S'inscrire</a>
                                                            @else
                                                                <p>Vous avez déjà reservé <span
                                                                        class="font-semibold">{{ $projectData['nbPlace'] }}</span>
                                                                    place(s)</p>
                                                            @endif
                                                        @endguest
                                                    </div>
                                                @else
                                                    <span class="px-4 py-1 text-sm text-red-500 bg-red-100 rounded-xl">
                                                        Places indisponiples
                                                    </span>
                                                    <div>
                                                        <p class="w-4"></p>
                                                    </div>
                                                @endif
                                            </div>

                                            <div id="content-{{ $projectId }}" class="hidden">
                                                <table class="table table-hover caption-top">
                                                    <caption>Liste des sessions de ce projet</caption>
                                                    <thead class="table-light">
                                                        <tr>
                                                            {{-- <th scope="col">#</th> --}}
                                                            <th scope="col">Sessions</th>
                                                            <th scope="col">Matin</th>
                                                            <th scope="col">Après-midi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($projectData['sessionsGroupedByDate'] as $session)
                                                            <tr>
                                                                {{-- <th scope="row">1</th> --}}
                                                                <td>{{ $session['dateSeance'] }}</td>
                                                                <td>
                                                                    @if (count($session['morningSessions']) > 0)
                                                                        @foreach ($session['morningSessions'] as $morningSession)
                                                                            <p>{{ $morningSession['heureDebut'] }} -
                                                                                {{ $morningSession['heureFin'] }}</p>
                                                                        @endforeach
                                                                    @else
                                                                        <p class="text-base text-gray-600">Pas de session
                                                                        </p>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if (count($session['afternoonSessions']) > 0)
                                                                        @foreach ($session['afternoonSessions'] as $afternoonSession)
                                                                            <p>{{ $afternoonSession['heureDebut'] }} -
                                                                                {{ $afternoonSession['heureFin'] }}</p>
                                                                        @endforeach
                                                                    @else
                                                                        <p class="text-base text-gray-600">Pas de session
                                                                        </p>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>

                                                @if (count($projectData['forms']) > 0)
                                                    <div class="p-6 space-y-3 border-gray-200 shadow-lg rounded-xl border-x-1 border-b-1 shadow-gray-200"
                                                        id="info">
                                                        <h1 class="font-bold">Vos formateurs</h1>
                                                        <ul
                                                            class="grid items-center px-3 space-y-2 lg:grid-cols-2 xl:grid-cols-3 md:flex-row">
                                                            @foreach ($projectData['forms'] as $form)
                                                                <li>
                                                                    <div class="flex items-center space-x-3">
                                                                        <img src="/img/formateurs/{{ $form->form_photo }}"
                                                                            alt="" class="w-12 rounded-full">
                                                                        <div>
                                                                            <p class="font-semibold">
                                                                                {{ $form->form_firstname }}
                                                                                {{ $form->form_name }}</p>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                            <div class="flex flex-col gap-y-1">
                                <h3 class="text-xl font-bold text-gray-700">Objectifs de cette formation</h3>
                                <div class="h-full line-clamp-5" id="objectives">
                                    <ul class="h-full ml-6 text-gray-600 list-disc">
                                        @if ($objectifs != null)
                                            @foreach ($objectifs as $objectif)
                                                <li>{{ $objectif->objectif }}</li>
                                            @endforeach
                                        @else
                                            <li>Objectif non renseigné</li>
                                        @endif
                                    </ul>
                                </div>

                                <a href="#" id="toggleObjectives" class="hidden text-blue-500">Voir plus</a>
                            </div>
                            <div class="flex flex-col h-full gap-y-1">
                                <h3 class="text-xl font-bold text-gray-700">Programmes de cette formation</h3>
                                <ul class="text-gray-600 line-clamp-5" id="programs">
                                    @foreach ($prog as $pro)
                                        <li>
                                            <p class="font-semibold"> - {{ $pro->program_title }}</p>
                                            <div class="ml-6"> {!! $pro->program_description !!}  </div>
                                            {{-- <ul class="ml-6 list-disc">
                                                @foreach ($pro['program_descriptions'] as $descriptions)
                                                    <li>
                                                        {{ $descriptions }}
                                                    </li>
                                                @endforeach
                                            </ul> --}}
                                        </li>
                                    @endforeach
                                </ul>
                                <a href="#" id="togglePrograms" class="hidden text-blue-500">Voir plus</a>
                            </div>
                        </div>

                        <div class="w-full space-y-6 lg:w-3/12">

                            <div
                                class="p-4 space-y-3 bg-[#f1f5f9] rounded-xl border-[1px] border-dashed border-[#dee2e6] shadow-sm mt-12">
                                <a href="/formation/exportPdf/{{ $module->idModule }}">
                                    <p class="text-center text-xl font-bold underline underline-offset-8 text-[#475569]"> <i
                                            class="mr-2 text-xl fa-solid fa-download"></i> Télécharger en PDF</p>
                                </a>
                            </div>

                            <div class="p-4 space-y-3 bg-white rounded-xl border-[1px] border-dashed border-gray-200">
                                <h1 class="text-xl font-bold text-gray-700 text-wrap">Proposé par</h1>
                                <div class="flex flex-wrap items-start w-full gap-2">
                                    <div>
                                        @if (isset($module->etp_logo))
                                            <img class="object-cover w-full h-28"
                                                src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/entreprises/{{ $module->etp_logo }}"
                                                alt="Logo entreprise" />
                                        @else
                                            <span class="flex items-center justify-center w-full h-full">
                                                <i class="text-5xl fa-solid fa-image text-slate-400"></i>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex flex-col">
                                        <p class="font-bold">{{ $module->nameCfp }}</p>
                                        <p class="text-blue-500 cursor-pointer"><a
                                                href="/organisme_formation/{{ $module->idCustomer }}">Découvrir
                                                l'organisme de formation <i class="ml-1 fa-solid fa-arrow-right"></i></a>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="py-4 space-y-3 bg-white rounded-xl border-[1px] border-dashed border-gray-200 px-4">
                                <h1 class="text-xl font-bold text-gray-700 text-wrap">Villes</h1>
                                <ul class="ml-4 text-gray-500 list-disc">
                                    {{-- {{ $module->ville ? $module->ville : 'Ville non renseignée' }} --}}
                                    <li>Ville non renseignée</li>
                                </ul>
                                <div class="flex flex-wrap items-start w-full gap-2 pt-4">
                                    <a class="text-white btn btn-primary btn-sm"
                                        href="{{ url('/demande_devis/1') }}">Demander un
                                        devis</a>
                                    <a class="btn btn-outline btn-sm"
                                        href="{{ url('/demande_devis/individual/1') }}">S'inscrire</a>
                                </div>
                            </div>

                            <div class="p-4 space-y-3 bg-white rounded-xl border-[1px] border-dashed border-gray-200">
                                <div class="flex flex-col gap-1">
                                    <h1 class="text-xl font-bold text-gray-700">Public concerné</h1>
                                    <ul class="ml-4 text-gray-500 list-disc">
                                        @foreach ($cibles as $cible)
                                            <li>{{ $cible->cible }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <h1 class="text-xl font-bold text-gray-700">Prérequis</h1>
                                    <ul class="ml-4 text-gray-500 list-disc">
                                        @if (count($prerequis) > 0)
                                            @foreach ($prerequis as $requis)
                                                <li>{{ $requis->prerequis_name }}</li>
                                            @endforeach
                                        @else
                                            <li>Aucun pré-requis</li>
                                        @endif
                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div
                        class="h-full p-4 bg-white lg:space-x-6 rounded-2xl mt-4 border-[1px] border-dashed border-gray-200">
                        <h1 class="text-2xl font-semibold text-slate-700 mb-4 ml-4">Ces formations peuvent aussi vous
                            intéresser</h1>
                        <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                            @if (isset($onlineModules))
                                @php
                                    $startDisplaying = false;
                                    $moduleCount = 0;
                                    $allModules = [];
                                @endphp

                                @foreach ($onlineModules as $domaine)
                                    @foreach ($domaine['modules'] as $module)
                                        @php
                                            $allModules[] = $module;
                                        @endphp
                                    @endforeach
                                @endforeach

                                @for ($i = 0; $moduleCount < 4; $i++)
                                    @php
                                        $index =
                                            ($i + array_search($id, array_column($allModules, 'idModule')) + 1) %
                                            count($allModules);
                                        $module = $allModules[$index];
                                    @endphp

                                    <div class="card bg-base-100 w-full h-[28rem] overflow-hidden shadow-md">
                                        <a href="/formation/detail/{{ $module['idModule'] }}"
                                            class="hover:text-inherit h-full">
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
                                                    <p><i
                                                            class="mr-1 fa-regular fa-clock"></i>{{ $module['dureeJ'] ?? 0 }}
                                                        jours |
                                                        {{ $module['dureeH'] ?? 0 }} heures</p>
                                                    <p><i class="text-gray-600 fa-solid fa-medal"></i>
                                                        {{ $module['module_level_name'] }}</p>
                                                    @php $prix = floatval($module['prixGroupe'] ?? 0); @endphp
                                                    <p><i class="mr-1 fa-regular fa-money-bill-1"></i>A partir de
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

                                    @php $moduleCount++; @endphp
                                @endfor
                            @else
                                <p class="text-slate-500 w-max">Cet organisme n'a pas encore de formation à proposer.</p>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="modal" class="fixed inset-0 flex items-center justify-center hidden bg-gray-800 bg-opacity-50">
        <div id="modalContent" class="relative w-1/3 w-full max-w-2xl max-h-full p-4 m-4 bg-white rounded-lg shadow-lg">

            <div class="flex justify-between">
                <button id="loginTab" class="w-5/12 py-2 text-base text-center border-b-2 lg:text-lg">Connexion</button>
                <button id="registerTab"
                    class="w-5/12 py-2 text-base text-center border-b-2 border-transparent lg:text-lg">Creer un
                    compte</button>
                <button class="w-2/12 py-2 text-gray-500" id="closeModalButton"><i
                        class="fa-solid fa-xmark"></i></button>
            </div>

            <div id="loginContent" class="px-4">
                <form method="POST" id="loginForm" action="{{ route('login.client') }}">
                    @csrf
                    <input type="hidden" id="loginDataId" name="project_id">
                    <div id="loginError" class="mb-4 text-red-500"></div>
                    <div class="mb-4">
                        <label for="email" class="block text-sm text-gray-700 lg:text-base">Email:</label>
                        <x-input type="email" id="email" name="email"
                            class="w-full px-3 py-2 border rounded-md" />
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-sm text-gray-700 lg:text-base">Password:</label>
                        <x-input type="password" id="password" name="password"
                            class="w-full px-3 py-2 border rounded-md" />
                    </div>
                    <button type="submit" class="w-full py-2 text-base text-white bg-blue-500 rounded-md lg:text-base">Se
                        connecter</button>
                </form>
            </div>

            <div id="registerContent" class="hidden px-2">
                <form method="POST" id="registerForm" action="{{ route('register.client') }}">
                    @csrf
                    <input type="hidden" id="registerDataId" name="project_id">
                    <input type="hidden" name="account_type" value="2">
                    <div id="registerError" class="mb-1 text-red-500 lg:mb-4"></div>
                    <div class="mb-1 lg:mb-2">
                        <label for="customer_email" class="block text-sm text-gray-700 lg:text-base">Email:</label>
                        <x-input type="email" id="customer_email" name="customer_email"
                            class="w-full px-3 py-2 border rounded-md" />
                    </div>
                    <div class="mb-1 lg:mb-2">
                        <label for="customer_name" class="block text-sm text-gray-700 lg:text-base">Raison
                            social:</label>
                        <x-input type="text" id="customer_name" name="customer_name"
                            class="w-full px-3 py-2 border rounded-md" />
                    </div>
                    <div class="mb-1 lg:mb-2">
                        <label for="customer_nif" class="block text-sm text-gray-700 lg:text-base">Numéro
                            d'identification fiscal (NIF):</label>
                        <x-input type="number" id="customer_nif" name="customer_nif"
                            class="w-full px-3 py-2 border rounded-md" />
                    </div>
                    <div class="flex mb-1 space-x-4 lg:mb-2 ">
                        <div class="w-1/2 ">
                            <label for="referent_name" class="block text-sm text-gray-700 lg:text-base">Nom du
                                responsable:</label>
                            <x-input type="text" id="referent_name" name="referent_name"
                                class="w-full px-3 py-2 border rounded-md" />
                        </div>
                        <div class="w-1/2">
                            <label for="referent_firstName" class="block text-sm text-gray-700 lg:text-base">Prénom du
                                responsable:</label>
                            <x-input type="text" id="referent_firstName" name="referent_firstName"
                                class="w-full px-3 py-2 border rounded-md" />
                        </div>
                    </div>
                    <div class="mb-1 lg:mb-2">
                        <label for="password" class="block text-sm text-gray-700 lg:text-base">Password:</label>
                        <x-input type="password" id="password" name="password"
                            class="w-full px-3 py-2 border rounded-md" />
                    </div>
                    <div class="mb-1 lg:mb-2">
                        <label for="password_confirmation" class="block text-sm text-gray-700 lg:text-base">Confirm
                            Password:</label>
                        <x-input type="password" id="password_confirmation" name="password_confirmation"
                            class="w-full px-3 py-2 border rounded-md" />
                    </div>
                    <button type="submit"
                        class="w-full py-2 text-base text-white bg-blue-500 rounded-md lg:text-base">Creer
                        un
                        compte</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Alert -->
    <div id="alertMessage" class="relative hidden px-4 py-3 text-red-700 bg-red-100 border border-red-400 rounded"
        role="alert">
        <strong class="font-bold">Holy smokes!</strong>
        <span class="block sm:inline">Something seriously bad happened.</span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="w-6 h-6 text-red-500 fill-current" role="button" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20">
                <title>Close</title>
                <path
                    d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
            </svg>
        </span>
    </div>
@endsection

@section('script')
    <script src="{{ asset('js/jquery.raty.js') }}"></script>
    <script src="{{ asset('js/global_js.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.more-info').click(function(event) {
                event.preventDefault();

                var projectId = $(this).data('id');
                var content = $('#content-' + projectId);

                if (content.css('display') === 'none') {
                    content.css('display', 'block');
                } else {
                    content.css('display', 'none');
                }
            });

            function toggleVisibility(list, toggleButton) {
                var listItems = list.children('li');

                if (listItems.length > 0) {
                    var lineHeight = parseFloat(getComputedStyle(listItems[0]).lineHeight);
                    var maxVisibleHeight = lineHeight * 5;

                    var totalHeight = 0;
                    listItems.each(function() {
                        totalHeight += $(this).outerHeight(true);
                    });

                    if (totalHeight > maxVisibleHeight) {
                        toggleButton.removeClass('hidden');
                    }

                    toggleButton.click(function(event) {
                        event.preventDefault();

                        var isClamped = list.hasClass('line-clamp-5');
                        list.toggleClass('line-clamp-5', !isClamped);

                        $(this).text(isClamped ? 'Voir moins' : 'Voir plus');
                    });
                }
            }

            var objectivesList = $('#objectives');
            var programsList = $('#programs');
            var toggleObjectivesButton = $('#toggleObjectives');
            var toggleProgramsButton = $('#togglePrograms');

            toggleVisibility(objectivesList, toggleObjectivesButton);
            toggleVisibility(programsList, toggleProgramsButton);
        });


        $(document).ready(function() {
            $('.openModalButton').on('click', function(event) {
                event.preventDefault();

                var dataId = $(this).data('id');

                $('#loginDataId').val(dataId);
                $('#registerDataId').val(dataId);

                $('#modal').removeClass('hidden');
            });

            $('#closeModalButton').on('click', function() {
                $('#modal').addClass('hidden');
            });

            $('#modal').on('click', function(event) {
                if (event.target === this) {
                    $('#modal').addClass('hidden');
                }
            });

            showTab('login');

            $('#loginTab').on('click', function() {
                showTab('login');
            });

            $('#registerTab').on('click', function() {
                showTab('register');
            });

            function showTab(tab) {
                if (tab === 'login') {
                    $('#loginContent').removeClass('hidden');
                    $('#registerContent').addClass('hidden');
                    $('#loginTab').addClass('border-blue-500');
                    $('#registerTab').removeClass('border-blue-500');
                    $('#registerTab').addClass('border-transparent');
                } else {
                    $('#loginContent').addClass('hidden');
                    $('#registerContent').removeClass('hidden');
                    $('#loginTab').removeClass('border-blue-500');
                    $('#loginTab').addClass('border-transparent');
                    $('#registerTab').addClass('border-blue-500');
                    $('#registerTab').removeClass('border-transparent');
                }
            }

            $('#loginForm').on('submit', function(event) {
                event.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            window.location.href = response.redirect;
                        }
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        var errorHtml = '';

                        $.each(errors, function(key, value) {
                            errorHtml += '<p>' + value + '</p>';
                        });

                        $('#loginError').html(errorHtml);
                    }
                });
            });

            $('#registerForm').on('submit', function(event) {
                event.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            window.location.href = response.redirect;
                        }
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        var errorHtml = '';

                        $.each(errors, function(key, value) {
                            errorHtml += '<p>' + value + '</p>';
                        });

                        $('#registerError').html(errorHtml);
                    }
                });
            });
        });

        $(document).ready(function() {
            setTimeout(function() {
                $('#errorAlert').fadeOut('slow', function() {
                    $(this).remove();
                });
            }, 3000);
            $('.close-alert').click(function() {
                $('#errorAlert').fadeOut('slow', function() {
                    $(this).remove();
                });
            });
        });

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

        $('#menu-button').on('click', function() {
            const dropdownMenu = $('#dropdown-menu');
            dropdownMenu.toggleClass('hidden');
            dropdownMenu.toggleClass('block');
        });

        window.addEventListener('click', function(event) {
            if (!event.target.closest('#menu-button') && !event.target.closest('#dropdown-menu')) {
                $('#dropdown-menu').addClass('hidden')
                $('#dropdown-menu').removeClass('block')
            }
        });
    </script>
@endsection
