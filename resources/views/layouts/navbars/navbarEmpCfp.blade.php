@php
    $colors = ['#a0fad9', '#dcd4ff', '#f8e58f', '#b9e8e8'];
    $color = isset($sub->plan) ? $colors[($sub->plan->id - 1) % count($colors)] : '#000000';

    $menus = [
        [
            'menu_title' => 'Analytique',
            'menu' => 1,
            'label' => 'Tableau de bord',
            'endpoint' => 'homeEmpCfp*',
            'route' => url('homeEmpCfp'),
            'icon' => 'chart-pie',
        ],
        [
            'menu_title' => 'Projets de formation',
            'menu' => 2,
            'label' => 'Projets',
            'endpoint' => 'cfp_ref/projets*',
            // 'route' => route('cfp_ref.projets.index'),
            'route' => Route::has('cfp_ref.projets.index') ? route('cfp_ref.projets.index') : null,
            'icon' => 'tarp',
        ],
        [
            'menu_title' => 'Ressources humaines',
            'menu' => 3,
            'label' => 'Agenda',
            'endpoint' => 'agendaEmps*',
            'route' => route('agendaEmps.index'),
            'icon' => 'calendar-day',
        ],
        [
            'menu_title' => 'Testing Center',
            'menu' => 4,
            'label' => 'Testing Center',
            'endpoint' => '',
            'route' => route('index.qcm'),
            'icon' => '',
        ],
    ];

    // Fonction pour grouper les menus
    function groupMenusByLabel($menus)
    {
        return collect($menus)->groupBy('menu_title');
    }

    $groupedMenus = groupMenusByLabel($menus);
@endphp

<nav
    class="sticky top-0 z-40 block w-full rounded-md shadow-md bg-white/90 text-slate-600 backdrop-blur-lg backdrop-saturate-150">

    <div class="container flex flex-wrap items-center justify-between py-2 mx-auto space-x-6 text-slate-800">
        <a href="{{ url('/') }}" class="mr-4 block cursor-pointer py-1.5 text-base text-slate-800 font-semibold">
            <img src="{{ asset('img/logo/Logo_horizontal.svg') }}" class="object-cover w-auto h-14" alt="">
        </a>

        <div class="relative w-full max-w-xs">
            <input
                class="bg-white border border-gray-400 rounded-lg pl-10 pr-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                type="text" placeholder="Rechercher">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i class="fa-solid fa-magnifying-glass text-gray-500"></i>
            </div>
        </div>

        <span class="inline-flex items-center space-x-6">

            <div class="dropdown dropdown-bottom dropdown-end">
                <button tabindex="0" role="button" title="Aspects financiers"
                    class="btn bg-slate-100 hover:bg-slate-200 text-slate-600">
                    <i class="fa-solid fa-landmark"></i>
                </button>

                <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-max p-2 shadow">
                    <li>
                        <a href="" class="text-slate-600 hover:text-slate-500">
                            <div class="w-[16px]">
                                <i class="fa-solid fa-file-invoice"></i>
                            </div>
                            Facture
                        </a>
                    </li>
                    <li>
                        <a href="" class="text-slate-600 hover:text-slate-500">
                            <div class="w-[16px]">
                                <i class="fa-solid fa-file-lines"></i>
                            </div>
                            Facture proforma
                        </a>
                    </li>
                </ul>
            </div>

            <div class="dropdown dropdown-bottom md:dropdown-end">
                <button tabindex="0" role="button" class="btn md:w-full md:px-6 btn-outline">
                    <i class="duration-150 cursor-pointer fa-solid fa-plus"></i>
                    <p class="hidden md:block">Nouveau</p>
                </button>
                <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-[220px] p-2 shadow">
                    <li id="openModalBtn">
                        <a class="capitalize text-slate-600 hover:text-slate-600">
                            <div class="w-[16px]">
                                <i class="fa-solid fa-tarp"></i>
                            </div>
                            Projet
                        </a>
                    </li>
                    <li id="openModalBtnClient">
                        <a class="capitalize text-slate-600 hover:text-slate-600">
                            <div class="w-[16px]">
                                <i class="fa-solid fa-handshake"></i>
                            </div>
                            client
                        </a>
                    </li>

                    <li id="openModalBtnCours">
                        <a class="capitalize text-slate-600 hover:text-slate-600">
                            <div class="w-[16px]">
                                <i class="fa-solid fa-puzzle-piece"></i>
                            </div>
                            cours
                        </a>
                    </li>

                    <li id="openModalBtnFormateur">
                        <a class="capitalize text-slate-600 hover:text-slate-600">
                            <div class="w-[16px]">
                                <i class="fa-solid fa-user-graduate"></i>
                            </div>
                            formateur
                        </a>
                    </li>

                    <li onclick="mainGetEtpApprs()" id="openModalBtnApprenant">
                        <a class="capitalize text-slate-600 hover:text-slate-600">
                            <div class="w-[16px]">
                                <i class="fa-solid fa-people-group"></i>
                            </div>
                            apprenant
                        </a>
                    </li>

                    <li id="openModalBtnReferent">
                        <a class="capitalize text-slate-600 hover:text-slate-600">
                            <div class="w-[16px]">
                                <i class="fa-solid fa-user-tie"></i>
                            </div>
                            référent
                        </a>
                    </li>

                    <li onclick="mainLoadVille()" id="openModalBtnSalle">
                        <a class="capitalize text-slate-600 hover:text-slate-600">
                            <div class="w-[16px]">
                                <i class="fa-solid fa-door-closed"></i>
                            </div>
                            salle
                        </a>
                    </li>

                    <li id="openModalBtnBankAccount">
                        <a class="capitalize text-slate-600 hover:text-slate-600">
                            <div class="w-[16px]">
                                <i class="fa-solid fa-money-check-dollar"></i>
                            </div>
                            compte bancaire
                        </a>
                    </li>

                    <li id="openModalBtnParticulier">
                        <a class="capitalize text-slate-600 hover:text-slate-600">
                            <div class="w-[16px]">
                                <i class="fa-solid fa-handshake"></i>
                            </div>
                            particulier
                        </a>
                    </li>

                    <li>
                        <a href="" class="text-slate-600 hover:text-slate-600 capitalize">
                            <div class="w-[16px]">
                                <i class="fa-solid fa-file-invoice"></i>
                            </div>
                            facture
                        </a>
                    </li>
                    <li id="openModalBtnSubContractor">
                        <a class="text-slate-600 hover:text-slate-600 capitalize">
                            <div class="w-[16px]">
                                <i class="fa-solid fa-handshake"></i>
                            </div>
                            sous-traitant
                        </a>
                    </li>
                </ul>
            </div>


            {{-- @guest
                @if (Route::has('login'))
                    <a class="nav-link" href="">{{ __('Se connecter') }}</a>
                @endif

                @if (Route::has('register'))
                    <a class="nav-link" href="">{{ __('Créer un compte') }}</a>
                @endif
            @else
                <div class="dropdown dropdown-bottom dropdown-end">
                    <button tabindex="0" id="navbarDropdown"
                        class="text-xl font-medium btn bg-slate-200 hover:bg-slate-200/90 text-slate-600 hover:text-slate-600"
                        role="button">
                        {{ \Illuminate\Support\Str::limit(Auth::user()->name, 1, '') }}
                    </button>

                    <ul tabindex="0"
                        class="dropdown-content dropdown menu bg-base-100 rounded-box z-[1] w-[320px] p-3 shadow"
                        aria-labelledby="navbarDropdown">
                        <div class="flex flex-col gap-2 ">
                            <div class="inline-flex items-start justify-start gap-3">
                                <div
                                    class="w-14 h-14 rounded-full text-center cursor-pointer flex justify-center items-center text-[#a462a4] text-xl font-medium bg-[#e1c4e3] transition-all duration-300 shadow-md uppercase">
                                    <i class="bi bi-buildings text-[#a462a4] text-lg"></i>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <h1 class="text-lg font-medium text-gray-700 capitalize">
                                        {{ $infoProfilCfp->customerName }}
                                    </h1>
                                    <div class="flex flex-col">
                                        @if ($sub == null)
                                            <span class="text-[#05a3a3] text-sm">{{ $infoProfilCfp->customerEmail }}</span>
                                        @elseif (!$sub->canceled_at == null)
                                            <span class="text-sm text-gray-700">{{ $infoProfilCfp->customerEmail }}</span>
                                        @else
                                            @include('layouts.infoAbn', ['color' => $color])
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="inline-flex items-center w-full gap-3">
                                <div class="w-14"></div>
                                <div class="flex flex-col w-full gap-1">
                                    <a href=""
                                        class="w-full px-2 py-1 text-base text-gray-500 duration-100 rounded-md hover:bg-gray-100 hover:text-gray-700">
                                        Gérer le profil de l'organisation
                                    </a>
                                    <div class="inline-block group">
                                        <div
                                            class="relative z-30 flex items-center justify-between w-full px-2 py-1 text-base text-gray-500 transition-all duration-75 rounded-md hover:bg-gray-100">
                                            <a href="" class="text-base hover:text-gray-700">
                                                Abonnement
                                            </a>
                                            <i class="fa-solid fa-chevron-down"></i>
                                        </div>

                                        <ul
                                            class="z-40 list-group py-2 bg-white border rounded-lg shadow-md transform scale-0 group-hover:scale-100 absolute transition duration-300 ease-in-out origin-top left-[2%] w-[96%] delay-100">
                                            <li class="px-3 py-2 hover:bg-gray-100">
                                                <a class="text-base text-gray-600 transition duration-150 hover:text-gray-600 focus:text-white"
                                                    href="">
                                                    Acheter une licence
                                                </a>
                                            </li>
                                            <li class="px-3 py-2 hover:bg-gray-100">
                                                <a class="text-base text-gray-600 transition duration-150 hover:text-gray-600 focus:text-white"
                                                    href="">
                                                    Forfait et historique d'abonnement
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="border-gray-400" />
                        <div class="flex flex-col gap-2 mt-2">
                            <div class="inline-flex items-center justify-start w-full gap-3">
                                @include('layouts.rond', ['color' => $color])
                                <div class="flex flex-col gap-1">
                                    <h1 class="text-lg font-medium text-gray-700 ">
                                        {{ \Illuminate\Support\Str::limit(Auth::user()->name, 20, '') }}
                                    </h1>
                                    <span
                                        class="text-base text-gray-700">{{ \Illuminate\Support\Str::limit(Auth::user()->email, 50, '') }}</span>
                                </div>
                            </div>

                            <div class="inline-flex items-center w-full gap-3">
                                <div class="w-14"></div>
                                <div class="flex flex-col w-full gap-1">
                                    <a href=""
                                        class="w-full px-2 py-1 text-base text-gray-500 duration-100 rounded-md hover:bg-gray-100 hover:text-gray-700">
                                        Gérer mon profil personnel
                                    </a>
                                    <a class="w-full px-2 py-1 text-base text-gray-500 duration-100 rounded-md cursor-pointer hover:bg-gray-100 hover:text-gray-700"
                                        onclick="logoutButton()">
                                        Se déconnecter
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="inline-flex items-center justify-center gap-3 p-2 text-gray-400 footer">
                            <a href="" class="text-sm">Règle de confidentialité</a>
                            <a href="" class="text-sm">Condition d'utilisation</a>
                        </div>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </ul>
                </div>

            @endguest --}}

            <button class="btn btn-outline" onclick="logoutButton()">
                <i class="fa-solid fa-power-off"></i>
            </button>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>

            <button
                class="relative ml-auto h-12 max-h-[48px] w-12 max-w-[48px] select-none rounded-full text-center hover:bg-gray-100 align-middle text-xs font-medium uppercase text-inherit transition-all focus:bg-transparent active:bg-transparent disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none lg:hidden"
                role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa-solid fa-bars text-2xl text-[#A462A4]"></i>
            </button>

            <div class="dropdown-menu dropdown-menu-end items-center p-2 border-[1px] border-gray-300 divide-y space-y-2 divide-gray-200 bg-white w-[280px] shadow-lg rounded-xl"
                aria-labelledby="navbarDropdown">
                @foreach ($groupedMenus as $menu => $menuItems)
                    <ul class="flex flex-col p-1">
                        <li class="menu-title">
                            {{ $menu }}
                        </li>
                        @foreach ($menuItems as $menuItem)
                            <li
                                class="{{ request()->is($menuItem['endpoint']) ? 'text-[#A462A4] font-medium' : 'text-slate-500' }}">
                                <a href="{{ $menuItem['route'] }}"
                                    class="flex hover:text-[#A462A4] duration-200 py-2.5 pl-4 rounded-lg hover:bg-gray-100">
                                    <span class="inline-flex items-center w-full gap-2">
                                        <i class="text-xl fa-solid fa-{{ $menuItem['icon'] }}"></i>
                                        {{ $menuItem['label'] }}
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endforeach
            </div>
        </span>
    </div>

    <div class="hidden lg:flex flex-wrap items-center justify-between py-2.5 mx-auto space-x-6 bg-slate-50">
        <div
            class="inline-flex items-center hidden w-full mx-auto space-x-6 divide-x xl:container divide-slate-300 lg:flex">
            @foreach ($groupedMenus as $menu => $menuItems)
                <ul class="menu menu-horizontal menu-sm dropdown-content">
                    @foreach ($menuItems as $menuItem)
                        <li
                            class="{{ request()->is($menuItem['endpoint']) ? 'border-b-[1px] border-[#a462a4] text-[#A462A4] font-medium' : 'text-slate-500' }}">
                            <a href="{{ $menuItem['route'] }}" class="py-2 hover:text-slate-500">
                                <i class="text-sm hidden xl:block fa-solid fa-{{ $menuItem['icon'] }}"></i>
                                {{ $menuItem['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endforeach
        </div>
    </div>
</nav>
