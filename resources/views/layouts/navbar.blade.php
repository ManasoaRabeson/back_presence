@php
    $colors = ['#a0fad9', '#dcd4ff', '#f8e58f', '#b9e8e8'];
    $color = isset($sub->plan) ? $colors[($sub->plan->id - 1) % count($colors)] : '#000000';

    $menus = [
        [
            'menu_title' => 'Projets de formation',
            'menu' => 2,
            'label' => 'Catalogues',
            'endpoint' => 'cfp/modules*',
            'route' => route('cfp.modules.index'),
            'icon' => 'fa-sharp fa-solid fa-books',
        ],
        [
            'menu_title' => 'Projets de formation',
            'menu' => 2,
            'label' => 'Projets',
            'endpoint' => 'cfp/projets*',
            'route' => route('cfp.projets.index'),
            'icon' => 'fa-solid fa-diagram-project',
        ],
        [
            'menu_title' => 'Projets de formation',
            'menu' => 2,
            'label' => 'Dossiers',
            'endpoint' => 'cfp/dossier*',
            'route' => route('cfp.dossier'),
            'icon' => 'fa-sharp fa-solid fa-folders',
        ],
        [
            'menu_title' => 'Projets de formation',
            'menu' => 2,
            'label' => 'Réservations',
            'endpoint' => 'cfp/reservation*',
            'route' => route('cfp.reservation'),
            'icon' => 'fa-sharp fa-solid fa-calendar-check',
        ],
        [
            'menu_title' => 'Projets de formation',
            'menu' => 2,
            'label' => 'Opportunités',
            'endpoint' => 'cfp/prospection*',
            'route' => route('cfp.prospection.index'),
            'icon' => 'fa-solid fa-hand-holding-heart',
        ],
        [
            'menu_title' => 'Projets de formation',
            'menu' => 2,
            'label' => 'Lieux et Salles',
            'endpoint' => 'cfp/lieux*',
            'route' => route('cfp.lieux.index'),
            'icon' => 'fa-solid fa-location-dot',
        ],
        [
            'menu_title' => 'Ressources humaines',
            'menu' => 3,
            'label' => 'Administrateur',
            'endpoint' => 'cfp/referent*',
            'route' => route('cfp.referents.index'),
            'icon' => 'fa-solid fa-user-tie-hair',
        ],
        [
            'menu_title' => 'Ressources humaines',
            'menu' => 3,
            'label' => 'Formateurs',
            'endpoint' => 'cfp/forms*',
            'route' => route('cfp.forms.index'),
            'icon' => 'fa-solid fa-chalkboard-user',
        ],
        [
            'menu_title' => 'Ressources humaines',
            'menu' => 3,
            'label' => 'Apprenants',
            'endpoint' => 'cfp/apprenants*',
            'route' => route('cfp.apprenants.index'),
            'icon' => 'fa-sharp fa-solid fa-users',
        ],
        [
            'menu_title' => 'Partenaires',
            'menu' => 4,
            'label' => 'Clients',
            'endpoint' => 'cfp/invites/etp*',
            'route' => route('cfp.invites.etp', 1),
            'icon' => 'fa-sharp fa-solid fa-city',
        ],
        [
            'menu_title' => 'Galerie',
            'menu' => 5,
            'label' => 'Galerie',
            'endpoint' => 'cfp/gallery',
            'route' => route('cfp.gallery.folder'),
            'icon' => 'fa-solid fa-image',
        ],
        // [
        //     'menu_title' => 'Attestion',
        //     'menu' => 6,
        //     'label' => 'Attestation',
        //     'endpoint' => 'cfp/attestation*',
        //     'route' => route('cfp.attestation.index'),
        //     'icon' => 'fa-solid fa-diploma',
        // ],
        [
            'menu_title' => 'Finance',
            'menu' => 7,
            'label' => 'Facture',
            'endpoint' => 'cfp/factures*',
            'route' => route('cfp.factures.index'),
            'icon' => 'fa-solid fa-file-lines',
        ],
        [
            'menu_title' => 'Finance',
            'menu' => 7,
            'label' => 'Proforma',
            'endpoint' => 'cfp/factureProfo*',
            'route' => route('cfp.factureProfo.index'),
            'icon' => 'fa-solid fa-file-lines',
        ],
        // [
        //     'menu_title' => 'Projets de formation',
        //     'menu' => 2,
        //     'label' => 'Evaluation à froids',
        //     'endpoint' => 'cfp/evaluations/froids*',
        //     'route' => route('cfp.evaluations.froids.index'),
        //     'icon' => 'fa-solid fa-gamepad-modern',
        // ],
    ];

    // Fonction pour grouper les menus
    function groupMenusByLabel($menus)
    {
        return collect($menus)->groupBy('menu_title');
    }

    $groupedMenus = groupMenusByLabel($menus);
@endphp

<div class="fixed top-0 z-50 w-full bg-white/90 text-slate-600 backdrop-blur-lg backdrop-saturate-150">
    <div class="w-full du-navbar">
        <div class="du-navbar-start">
            <div class="dropdown">
                <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h8m-8 6h16" />
                    </svg>
                </div>
                <ul tabindex="0"
                    class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
                    @guest
                        <li><a class="text-slate-600 hover:text-slate-500" href="{{ url('/formation') }}">Trouver des
                                formations</a></li>
                        <li><a class="text-slate-600 hover:text-slate-500" href="{{ url('/organisme') }}">Nos
                                organismes</a>
                        </li>
                        <li><a class="text-slate-600 hover:text-slate-500" href="/vous_etes">Vous êtes</a></li>
                        <li><a class="text-slate-600 hover:text-slate-500" href="{{ route('contact.formafusion') }}">
                                Contacter-nous</a></li>
                    @endguest
                    {{-- <li>
                        <a class="text-slate-600 hover:text-slate-500" href="{{ route('contact.formafusion') }}">
                            Nous contacter
                        </a>
                    </li> --}}
                    @if (isset($infoProfilCfp->idTypeCustomer))
                        <li>
                            <a class="text-slate-600 hover:text-slate-500" href="{{ route('cfp.peda.index') }}">
                                Suivi pédagogique
                            </a>
                        </li>
                        <li>
                            <a class="text-slate-600 hover:text-slate-500" href="{{ route('agenda.index') }}">
                                Calendrier
                            </a>
                        </li>
                        <li>
                            <a class="text-slate-600 hover:text-slate-500" href="{{ route('home') }}">
                                Tableau de bord
                            </a>
                        </li>
                        <li>
                            <a class="text-slate-600 hover:text-slate-500" href="{{ route('ReportingFormation') }}">
                                Reporting
                            </a>
                        </li>
                    @endif
                    <li>
                        @if (isset($infoProfilCfp->idTypeCustomer))
                            @if ($infoProfilCfp->idTypeCustomer == 1)
                                <a class="text-slate-600 hover:text-slate-500" href="{{ route('objectif.index') }}">
                                    Objectif
                                </a>
                            @elseif ($infoProfilCfp->idTypeCustomer == 2)
                                <a class="text-slate-600 hover:text-slate-500" href="{{ route('home.entreprise') }}">
                                    Gérer mes formations!!
                                </a>
                            @else
                            @endif
                        @else
                            <a class="text-slate-600 hover:text-slate-500" href="{{ route('user.login') }}">
                                Gérer mes formations!!!
                            </a>
                        @endif
                    </li>
                    @if (auth()->check())
                        <li>
                            <a class="text-slate-600 hover:text-slate-500" href="{{ route('index.qcm') }}">
                                Testing Center
                            </a>
                        </li>
                    @elseif(!auth()->check())
                        <li>
                            <a class="text-slate-600 hover:text-slate-500" href="{{ route('index.qcm.public') }}">
                                Testing Center
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
            <a href="{{ url('/') }}" class="btn btn-ghost text-slate-600 hover:text-slate-500">
                <img src="{{ asset('img/logo/Logo_horizontal.svg') }}" alt="Logo" class="h-10">
            </a>
        </div>
        <div class="hidden du-navbar-center lg:flex">

            <div class="hidden navbar-center lg:flex">
                <ul class="px-1 menu menu-horizontal">
                    @guest
                        <li><a class="text-slate-600 hover:text-slate-500" href="{{ url('/formation') }}">Trouver des
                                formations</a></li>
                        <li><a class="text-slate-600 hover:text-slate-500" href="{{ url('/organisme') }}">Nos
                                organismes</a>
                        </li>
                        <li><a class="text-slate-600 hover:text-slate-500" href="/vous_etes">Vous êtes</a></li>
                        <li><a class="text-slate-600 hover:text-slate-500" href="{{ route('contact.formafusion') }}">
                                Contacter-nous</a></li>
                    @endguest
                    <li>
                        @if (isset($infoProfilCfp->idTypeCustomer))
                            @if ($infoProfilCfp->idTypeCustomer == 1)
                                <a class="text-slate-600 hover:text-slate-500" href="{{ route('accueil') }}">
                                    Accueil
                                </a>
                            @elseif ($infoProfilCfp->idTypeCustomer == 2)
                                <a class="text-slate-600 hover:text-slate-500" href="{{ route('home.entreprise') }}">
                                    Accueil
                                </a>
                            @else
                            @endif
                        @endif
                    </li>
                    @if (isset($infoProfilCfp->idTypeCustomer))
                        <li>
                            <a class="text-slate-600 hover:text-slate-500" href="{{ route('cfp.peda.index') }}">
                                Suivi pédagogique
                            </a>
                        </li>
                        <li>
                            <a class="text-slate-600 hover:text-slate-500" href="{{ route('agenda.index') }}">
                                Calendrier
                            </a>
                        </li>
                        <li>
                            <a class="text-slate-600 hover:text-slate-500" href="{{ route('home') }}">
                                Tableau de bord
                            </a>
                        </li>
                        <li>
                            <a class="text-slate-600 hover:text-slate-500" href="{{ route('ReportingFormation') }}">
                                Reporting
                            </a>
                        </li>
                    @endif
                    <li>
                        @if (isset($infoProfilCfp->idTypeCustomer))
                            @if ($infoProfilCfp->idTypeCustomer == 1)
                                <a class="text-slate-600 hover:text-slate-500" href="{{ route('objectif.index') }}">
                                    Objectif
                                </a>
                            @elseif ($infoProfilCfp->idTypeCustomer == 2)
                                <a class="text-slate-600 hover:text-slate-500" href="{{ route('etp.projets.index') }}">
                                    Gérer mes formations
                                </a>
                            @else
                            @endif
                        @else
                            <a class="text-slate-600 hover:text-slate-500" href="{{ route('user.login') }}">
                                Gérer mes formations
                            </a>
                        @endif
                    </li>
                    @if (auth()->check())
                        <li>
                            <a class="text-slate-600 hover:text-slate-500" href="{{ route('index.qcm') }}">
                                Testing Center
                            </a>
                        </li>
                    @elseif(!auth()->check())
                        <li>
                            <a class="text-slate-600 hover:text-slate-500" href="{{ route('index.qcm.public') }}">
                                Testing Center
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="du-navbar-end">
            <form id="searchForm" action="{{ route('searchGenerality') }}" method="GET"
                class="inline-flex items-center py-1 hover:border-b-[1px] mr-1">
                <label class="flex items-center gap-2 transition-all duration-700 cursor-pointer group">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                        class="h-7 w-7 opacity-70">
                        <path fill-rule="evenodd"
                            d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
                            clip-rule="evenodd" />
                    </svg>
                    <input type="text"
                        class="w-0 transition-all duration-700 outline-none grow group-hover:w-80 focus:w-80"
                        id="key" name="key" placeholder="Rechercher" autocomplete="off" />
                </label>
            </form>

            @guest
                <span class="inline-flex items-center gap-4">
                    <div class="hidden dropdown dropdown-bottom dropdown-end">
                        <div tabindex="0" role="button" class="btn bg-slate-100 hover:bg-slate-200 text-slate-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h8m-8 6h16" />
                            </svg>
                        </div>
                        <ul tabindex="0"
                            class="menu dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-max p-2 shadow">
                            @guest
                                <li><a class="text-slate-600 hover:text-slate-500" href="{{ url('/formation') }}">Trouver des
                                        formations</a></li>
                                <li><a class="text-slate-600 hover:text-slate-500" href="{{ url('/organisme') }}">Nos
                                        organismes</a>
                                </li>
                                <li><a class="text-slate-600 hover:text-slate-500" href="/vous_etes">Vous êtes</a></li>
                            @endguest
                            <li>
                                @if (isset($infoProfilCfp->idTypeCustomer))
                                    @if ($infoProfilCfp->idTypeCustomer == 1)
                                        <a class="text-slate-600 hover:text-slate-500"
                                            href="{{ route('objectif.index') }}">
                                            Objectif
                                        </a>
                                    @elseif ($infoProfilCfp->idTypeCustomer == 2)
                                        <a class="text-slate-600 hover:text-slate-500"
                                            href="{{ route('home.entreprise') }}">
                                            Gérer mes formations
                                        </a>
                                    @else
                                    @endif
                                @else
                                    <a class="text-slate-600 hover:text-slate-500" href="{{ route('user.login') }}">
                                        Gérer mes formations
                                    </a>
                                @endif
                            </li>
                            @if (auth()->check())
                                <li>
                                    <a class="text-slate-600 hover:text-slate-500" href="{{ route('index.qcm') }}">
                                        Testing Center
                                    </a>
                                </li>
                            @elseif(!auth()->check())
                                <li>
                                    <a class="text-slate-600 hover:text-slate-500"
                                        href="{{ route('index.qcm.public') }}">
                                        Testing Center
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>

                    <a class="text-white btn btn-primary" href="{{ route('user.login') }}"><i class="fa fa-user"
                            aria-hidden="true"></i> Se connecter</a>
                </span>
            @else
                <span class="inline-flex items-center">

                    {{-- notification subscription --}}
                    @php
                        $notifications = app()
                            ->make(App\Http\Controllers\AbonnementController::class)
                            ->notificationSubscription();
                    @endphp
                    @if (count($notifications) >= 1)
                        <div class="mx-2 dropdown dropdown-bottom dropdown-end">
                            <button tabindex="0" role="button" title="Notification"
                                class="relative inline-flex items-center px-3 py-2 rounded-lg hover:text-inherit text-slate-500 hover:bg-slate-100">
                                <i class="text-lg fa-solid fa-bell-ring"></i>
                                <span
                                    class="absolute top-0 right-0 px-2 text-white translate-x-2 -translate-y-2 bg-red-600 rounded-md">
                                    <p class="text-sm">{{ count($notifications) }}</p>
                                </span>
                            </button>

                            <ul tabindex="0"
                                class="dropdown-content menu bg-base-100 rounded-box z-[1] w-max p-2 shadow">
                                @foreach ($notifications as $notification)
                                    <li>
                                        <a href="{{ route('notifications.markAsRead', $notification->id) }}"
                                            class="flex items-start gap-3">
                                            <i class="fa-solid fa-gem text-[#864DFF] text-2xl"></i>
                                            <div class="flex flex-col">
                                                <p class="font-semibold text-slate-600 hover:text-slate-500">
                                                    {{ $notification->data['message'] }}
                                                </p>
                                                <small>({{ $notification->created_at->diffForHumans() }})</small>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    {{-- notification subscription --}}

                    @php
                        $totalConflits = app()->make(App\Http\Controllers\ConflitsController::class)->totalconflits();
                    @endphp
                    @if ($totalConflits >= 1)
                        <a href="/cfp/conflits">
                            <button title="Conflits" type="button"
                                class="relative inline-flex items-center text-[#475569] py-[11px] px-3 rounded-lg hover:bg-slate-200">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                <span
                                    class="absolute top-0 right-0 px-2 text-white translate-x-2 -translate-y-2 bg-red-600 rounded-md">
                                    <i class="text-sm fa-solid fa-exclamation"></i>
                                </span>
                            </button>
                        </a>
                    @endif
                    <a href="{{ route('contact.formafusion') }}" aria-label="Contacter nous" title="Contacter nous"
                        class="inline-flex items-center px-3 py-2 mx-2 rounded-lg hover:text-inherit text-slate-500 hover:bg-slate-200">
                        <i class="text-lg fa-solid fa-phone"></i>
                    </a>
                    <div class="inline-flex items-center gap-4">
                        <div class="dropdown dropdown-bottom md:dropdown-end">
                            <button tabindex="0" role="button" class="btn md:w-full md:px-6 btn-outline">
                                <i class="duration-150 cursor-pointer fa-solid fa-plus"></i>
                                <p class="hidden md:block">Nouveau</p>
                            </button>
                            <ul tabindex="0"
                                class="dropdown-content menu bg-base-100 rounded-box z-[1] w-[220px] p-2 shadow">
                                <li onclick="__addDrawer()">
                                    <span class="capitalize text-slate-600 hover:text-slate-600">
                                        <div class="w-[16px]">
                                            <i class="fa-solid fa-diagram-project"></i>
                                        </div>
                                        Projet
                                    </span>
                                </li>
                                <li onclick="__openDrawerClient()">
                                    {{-- RAHA AVERINA MODAL id="openModalBtnClient" --}}
                                    <span class="capitalize text-slate-600 hover:text-slate-600">
                                        <div class="w-[16px]">
                                            <i class="fa-solid fa-handshake"></i>
                                        </div>
                                        client
                                    </span>
                                </li>

                                <li onclick="__openDrawerCours()">
                                    {{-- id="openModalBtnCours" --}}
                                    <span class="capitalize text-slate-600 hover:text-slate-600">
                                        <div class="w-[16px]">
                                            <i class="fa-solid fa-puzzle-piece"></i>
                                        </div>
                                        cours
                                    </span>
                                </li>

                                <li onclick="__openDrawerFormateur()">
                                    {{-- id="openModalBtnFormateur" --}}
                                    <span class="capitalize text-slate-600 hover:text-slate-600">
                                        <div class="w-[16px]">
                                            <i class="fa-solid fa-user-graduate"></i>
                                        </div>
                                        formateur
                                    </span>
                                </li>

                                <li onclick="__openDrawerApprenant()">
                                    {{--  id="openModalBtnApprenant" --}}
                                    <span class="capitalize text-slate-600 hover:text-slate-600">
                                        <div class="w-[16px]">
                                            <i class="fa-solid fa-people-group"></i>
                                        </div>
                                        apprenant
                                    </span>
                                </li>

                                @isset($infoProfilCfp)
                                    <li onclick="__openDrawerReferent()">
                                        {{-- id="openModalBtnReferent" --}}
                                        <span class="capitalize text-slate-600 hover:text-slate-600">
                                            <div class="w-[16px]">
                                                <i class="fa-solid fa-user-tie"></i>
                                            </div>
                                            référent
                                        </span>
                                    </li>
                                @endisset

                                {{-- <li onclick="mainLoadVille()" id="openModalBtnSalle">
                                    <a class="capitalize text-slate-600 hover:text-slate-600">
                                        <div class="w-[16px]">
                                            <i class="fa-solid fa-door-closed"></i>
                                        </div>
                                        salle
                                    </a>
                                </li> --}}

                                <li onclick="openDrawerLieu()" id="toggle-btn">
                                    <span class="capitalize text-slate-600 hover:text-slate-600">
                                        <div class="w-[16px]">
                                            <i class="fa-solid fa-location-dot"></i>
                                        </div>
                                        lieu
                                    </span>
                                </li>

                                <li onclick="openDrawerSalle()" id="toggle-btn">
                                    <span class="capitalize text-slate-600 hover:text-slate-600">
                                        <div class="w-[16px]">
                                            <i class="fa-solid fa-door-closed"></i>
                                        </div>
                                        salle
                                    </span>
                                </li>

                                <li onclick="__openDrawerParticulier()">
                                    {{-- id="openModalBtnParticulier" --}}
                                    <span class="capitalize text-slate-600 hover:text-slate-600">
                                        <div class="w-[16px]">
                                            <i class="fa-solid fa-handshake"></i>
                                        </div>
                                        particulier
                                    </span>
                                </li>

                                <li>
                                    <a href="{{ route('cfp.factures.create') }}"
                                        class="capitalize text-slate-600 hover:text-slate-600">
                                        <div class="w-[16px]">
                                            <i class="fa-solid fa-file-invoice"></i>
                                        </div>
                                        facture
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('cfp.factureProfo.create') }}"
                                        class="capitalize text-slate-600 hover:text-slate-600">
                                        <div class="w-[16px]">
                                            <i class="fa-solid fa-file-invoice"></i>
                                        </div>
                                        facture proforma
                                    </a>
                                </li>

                                {{-- <li onclick="__openDrawerAttestation()">
                                    <span class="capitalize text-slate-600 hover:text-slate-600">
                                        <div class="w-[16px]">
                                            <i class="fa-solid fa-diploma"></i>
                                        </div>
                                        Attestation
                                    </span>
                                </li> --}}
                                {{-- <li id="openModalBtnSubContractor">
                                    <a class="capitalize text-slate-600 hover:text-slate-600">
                                        <div class="w-[16px]">
                                            <i class="fa-solid fa-handshake"></i>
                                        </div>
                                        sous-traitant
                                    </a>
                                </li> --}}
                            </ul>
                        </div>


                        <div class="dropdown dropdown-bottom dropdown-end">

                            @if (isset(Auth::user()->photo))
                                <div tabindex="0" role="button" class="mt-1 avatar">
                                    <div class="w-12 rounded">
                                        <img alt="Profile"
                                            src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/referents/{{ Auth::user()->photo }}" />
                                    </div>
                                </div>
                            @elseif (isset(Auth::user()->name))
                                <button tabindex="0" id="navbarDropdown"
                                    class="text-xl font-medium btn bg-slate-200 hover:bg-slate-200/90 text-slate-600 hover:text-slate-600"
                                    role="button">
                                    {{ \Illuminate\Support\Str::limit(Auth::user()->name, 1, '') }}
                                </button>
                            @else
                                <button tabindex="0" id="navbarDropdown"
                                    class="text-xl font-medium btn bg-slate-200 hover:bg-slate-200/90 text-slate-600 hover:text-slate-600"
                                    role="button">
                                    <i class="text-xl fa-solid fa-user text-slate-700"></i>
                                </button>
                            @endif

                            <ul tabindex="0"
                                class="dropdown-content dropdown menu bg-base-100 rounded-box z-[1] w-[320px] p-3 shadow"
                                aria-labelledby="navbarDropdown">
                                <div class="flex flex-col gap-2">
                                    <div class="flex flex-col gap-2 ">
                                        <div class="inline-flex items-center justify-start gap-3">
                                            <div
                                                class="w-14 h-14 rounded-full text-center cursor-pointer flex justify-center items-center text-[#a462a4] text-xl font-medium bg-[#e1c4e3] transition-all duration-300 shadow-md uppercase">
                                                <i class="bi bi-buildings text-[#a462a4] text-lg"></i>
                                            </div>
                                            @if (isset($infoProfilCfp))
                                                <div class="flex flex-col gap-1">
                                                    <h1 class="text-lg font-medium text-gray-700 capitalize">
                                                        {{ $infoProfilCfp->customerName }}
                                                    </h1>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="inline-flex items-center w-full gap-3 mb-4">
                                        <div class="w-14"></div>
                                        <div class="flex flex-col w-full gap-1">
                                            <a href="{{ route('cfp.profils.index', Auth::user()->id) }}"
                                                class="w-full px-2 py-1 text-base text-gray-500 duration-100 rounded-md hover:bg-gray-100 hover:text-gray-700">
                                                Gérer le profil
                                            </a>
                                            <a class="w-full px-2 py-1 text-base text-gray-500 duration-100 rounded-md hover:bg-gray-100 hover:text-gray-700"
                                                href="{{ route('cfp.abonnement.index') }}">
                                                Acheter une licence
                                            </a>
                                            @if (isset($infoProfilCfp))
                                                @if (Auth::user()->id === $infoProfilCfp->idCustomer)
                                                    <a href="{{ route('cfp.agences.index') }}"
                                                        class="w-full px-2 py-1 text-base text-gray-500 duration-100 rounded-md hover:bg-gray-100 hover:text-gray-700">
                                                        Gérer les agences
                                                    </a>
                                                @endif
                                            @endif
                                            {{-- <a href="{{ route('cfp.subContractor') }}"
                                                class="w-full px-2 py-1 text-base text-gray-500 duration-100 rounded-md hover:bg-gray-100 hover:text-gray-700">
                                                Gérer les sous-traitants
                                            </a> --}}
                                            <a class="w-full px-2 py-1 text-base text-gray-500 duration-100 rounded-md hover:bg-gray-100 hover:text-gray-700"
                                                href="{{ route('cfp.abonnement.forfait') }}">
                                                Mon abonnement actuel
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <hr class="border-gray-400" />
                                <div class="flex flex-col gap-2 mt-2">
                                    <div class="inline-flex items-center justify-start w-full gap-3">
                                        @if (isset(Auth::user()->photo))
                                            <div
                                                class="w-14 h-14 rounded-full overflow-hidden text-center cursor-pointer flex justify-center items-center text-[#a462a4] text-xl font-medium bg-[#e1c4e3] transition-all duration-300 shadow-md uppercase">
                                                <img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/referents/{{ Auth::user()->photo }}"
                                                    class="w-full h-full" alt="">
                                            </div>
                                        @else
                                            @include('layouts.rond', ['color' => $color])
                                        @endif

                                        <div class="flex flex-col gap-1">
                                            <h1 class="text-lg font-medium text-gray-700 ">
                                                {{ \Illuminate\Support\Str::limit(Auth::user()->name, 20, '') }}
                                                {{ \Illuminate\Support\Str::limit(Auth::user()->firstName, 50, '') }}
                                            </h1>
                                            <span
                                                class="text-base text-gray-700">{{ \Illuminate\Support\Str::limit(Auth::user()->email, 50, '') }}</span>
                                        </div>
                                    </div>

                                    <div class="inline-flex items-center w-full gap-3">
                                        <div class="w-14"></div>
                                        <div class="flex flex-col w-full gap-1">
                                            {{-- <a href="{{ route('cfp.profils.index', Auth::user()->id) }}"
                                                class="w-full px-2 py-1 text-base text-gray-500 duration-100 rounded-md hover:bg-gray-100 hover:text-gray-700">
                                                Gérer mon profil personnel
                                            </a> --}}
                                            <span
                                                class="w-full px-2 py-1 text-base text-gray-500 duration-100 rounded-md cursor-pointer hover:bg-gray-100 hover:text-gray-700"
                                                onclick="logoutButton()">
                                                Se déconnecter
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="inline-flex items-center justify-center gap-3 p-2 text-gray-400 footer">
                                    <a href="{{ route('confidentialite') }}" class="text-sm">Règle de confidentialité</a>
                                    <a href="{{ route('condition') }}" class="text-sm">Condition d'utilisation</a>
                                </div>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </ul>
                        </div>
                    </div>

                    <div class="dropdown dropdown-end">
                        <button tabindex="0"
                            class="relative ml-auto h-12 max-h-[48px] w-12 max-w-[48px] select-none rounded-full text-center hover:bg-gray-100 align-middle text-xs font-medium uppercase text-inherit transition-all focus:bg-transparent active:bg-transparent disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none lg:hidden"
                            role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="text-xl fa-solid fa-bars-staggered text-slate-600"></i>
                        </button>

                        <div tabindex="0"
                            class="dropdown-content menu menu-sm items-start p-2 border-[1px] border-gray-300 divide-y space-y-2 divide-gray-200 bg-white w-72 shadow-lg rounded-xl"
                            aria-labelledby="navbarDropdown">
                            @foreach ($groupedMenus as $menu => $menuItems)
                                <ul class="flex flex-col w-full p-1">
                                    <li class="menu-title">
                                        {{ $menu }}
                                    </li>
                                    @foreach ($menuItems as $menuItem)
                                        <li
                                            class="{{ request()->is($menuItem['endpoint']) ? 'text-[#A462A4] font-medium' : 'text-slate-500' }}">
                                            <a href="{{ $menuItem['route'] }}"
                                                class="flex hover:text-[#A462A4] duration-200 pl-4 rounded-lg hover:bg-gray-100">
                                                <span class="inline-flex items-center w-full gap-2">
                                                    <i class="fa-solid fa-{{ $menuItem['icon'] }}"></i>
                                                    {{ $menuItem['label'] }}
                                                </span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endforeach
                        </div>
                    </div>
                </span>
            @endguest
        </div>
    </div>
    @guest
    @else
        <div class="flex-wrap items-center justify-between hidden w-full mx-auto space-x-6 lg:flex bg-slate-50">
            <div
                class="inline-flex items-center justify-center hidden w-full mx-auto space-x-6 divide-x xl:container divide-slate-300 lg:flex">
                @foreach ($groupedMenus as $menu => $menuItems)
                    <ul class="menu menu-horizontal menu-sm dropdown-content">
                        @foreach ($menuItems as $menuItem)
                            <li
                                class="{{ request()->is($menuItem['endpoint']) ? 'border-b-[1px] bg-white rounded-t-lg border-[#a462a4] text-[#A462A4] font-medium' : 'text-slate-700' }}">
                                <a href="{{ $menuItem['route'] }}" class="py-2 hover:text-slate-700">
                                    <i class="text-sm hidden xl:block {{ $menuItem['icon'] }}"></i>
                                    {{ $menuItem['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endforeach
            </div>
        </div>
    @endguest
</div>
