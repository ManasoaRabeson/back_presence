<div class="flex flex-col w-[100%] fixed z-20 top-0">
    <div class="relative inline-flex justify-between px-2 w-full h-10 bg-[#a462a4]">
        <div class="inline-flex items-center gap-2 text-sm text-gray-500">
            <div class="relative inline-flex items-center w-full">
                {{-- LISTE DES MENUS --}}
                <nav class="flex flex-row items-center flex-1 h-full gap-1 ">
                    <div class="inline-flex items-center gap-2">
                        <a href="{{ url('homeAdmin') }}">
                            <img src="{{ asset('img/logo/Logo_mark.svg') }}" alt="logo_lgcfp" class="w-5 h-auto">
                        </a>
                    </div>
                    <div class="flex flex-row items-center flex-1 h-full " id="navigation">
                        <div class="inline-block group">
                            <span class="relative flex flex-row text-white transition-all">
                                <a href="{{ url('homeAdmin') }}"
                                    class="text-sm p-[7px] hover:bg-[#81338a] hover:text-white transition rounded-none ease-in-out">
                                    <i class="fa-solid fa-chart-simple"></i> Tableau de bord
                                </a>
                            </span>
                            {{-- <x-customHelper titre="Tableau de bord">Ici,vous
                                trouverez la liste des menus dans le tableau de bord du super
                                utilisateur</x-customHelper> --}}
                        </div>
                        <div class="inline-block group">
                            <x-nav-sub route="{{ route('superAdmins.about') }}"><i class="fa-solid fa-user-tie"></i>
                                Super Admin</x-nav-sub>
                            {{-- <x-customHelper titre="Super Admin">Accédez à votre profil utilisateur en tant que Super
                                Administrateur.</x-customHelper> --}}
                        </div>
                        <div class="inline-block group">
                            <x-nav-sub route="{{ route('abonnement.superA.index') }}"><i
                                    class="fa-solid fa-credit-card"></i> Gestion d'Abonnement</x-nav-sub>
                            {{-- <x-nav-sub route="{{ route('superAdmins.agenda') }}">Agenda</x-nav-sub> --}}
                            {{-- <x-customHelper titre="Agenda">Programmez ici votre liste des jours fériées de
                                l'année.</x-customHelper> --}}
                        </div>
                        <div class="inline-block group">
                            <x-nav-sub route="{{ route('crudAbn.index') }}"><i class="fa-solid fa-pen"></i> CRUD
                                Abonnement</x-nav-sub>
                            {{-- <x-nav-sub route="{{ route('superAdmins.assiduite') }}">Assiduités</x-nav-sub> --}}
                            {{-- <x-customHelper titre="Assiduités">Suivez depuis cette interface l'assiduité des
                                apprenants.</x-customHelper> --}}
                        </div>
                        <div class="inline-block group">
                            <x-nav-sub route="{{ route('superAdmins.projetlist') }}"><i
                                    class="fa-solid fa-list-check"></i> Projet</x-nav-sub>
                            {{-- <x-customHelper titre="Projet">Suivez depuis cette interface la liste de tous les
                                projets.</x-customHelper> --}}
                        </div>
                        <div class="inline-block group">
                            <x-nav-sub route="{{ route('superAdmins.organismelist') }}"><i
                                    class="fa-solid fa-building"></i> Organisme</x-nav-sub>
                            {{-- <x-customHelper titre="Organisme">Organiser les organismes.</x-customHelper> --}}
                        </div>
                        <div class="inline-block group">
                            <x-nav-sub route="{{ route('superAdmins.domaineList') }}"><i
                                    class="fa-solid fa-book-open"></i> Domaine de formation</x-nav-sub>
                            {{-- <x-customHelper titre="Domaine de formation">Ici, vous pouvez gérer l'ensemble de vos
                                domaine de formation.</x-customHelper> --}}
                        </div>
                        <div class="inline-block group">
                            <x-nav-sub route="{{ route('superAdmins.publicityModule') }}"><i class="fa-solid fa-tv"></i>
                                Gestion des
                                publicités</x-nav-sub>
                        </div>
                        <div class="inline-block group">
                            <x-nav-sub route="{{ route('superAdmin.villes.index') }}"><i
                                    class="fa-solid fa-mountain-city"></i> Villes</x-nav-sub>
                        </div>
                        <div class="inline-block group">
                            <x-nav-sub route="{{ route('qcm.indexCfpListForQcm') }}"><i
                                    class="fa-solid fa-user-graduate"></i> Testing Center</x-nav-sub>
                        </div>
                        <div class="inline-block group">
                            <x-nav-sub route="{{ route('transactions.history.dashboard') }}"><i
                                    class="fa-solid fa-coins"></i> Gestion des crédits</x-nav-sub>
                        </div>
                    </div>
                </nav>
            </div>
        </div>

        <div class="inline-flex items-center gap-3">
            {{-- CLOCHE AVEC NOTIFICATION --}}
            <button class="relative flex flex-row text-gray-500 transition-all outline-none hover:text-gray-700">
                <span class="absolute right-0 w-2 h-2 bg-yellow-400 rounded-full top-1 fa-fade"></span>
                <a id="navbarDropdown" href="#"
                    class="text-sm p-1 px-2 rounded-full hover:bg-[#81338a] hover:text-gray-600 transition ease-in-out flex text-center items-center"
                    role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    <i class="fa-solid text-gray-500 fa-bell fa-shake text-lg hover:text-[#81338a] m-0"></i>
                </a>
                <div class="dropdown-menu divDrop shadow-xl min-w-[400px]" aria-labelledby="navbarDropdown">
                    <ul class="flex flex-col">
                        @include('layouts.listItemNotif')
                    </ul>
                </div>
            </button>

            <div class="flex flex-row items-center justify-center gap-3">
                @guest
                    @if (Route::has('login'))
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Se connecter') }}</a>
                    @endif

                    @if (Route::has('register'))
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Créer un compte') }}</a>
                    @endif
                @else
                    <a id="navbarDropdown"
                        class="nav-link w-7 h-7 rounded-full text-center flex justify-center items-center text-[#81338a] text-lg font-semibold bg-gray-50 hover:bg-gray-200 hover:text-gray-600 transition-all duration-300"
                        href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                        v-pre>
                        {{-- <img class="w-8 h-8 rounded-full" src="" alt="Rounded avatar"> --}}
                        {{ \Illuminate\Support\Str::limit(Auth::user()->name, 1, '') }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-end p-3 pb-0 items-center border-[1px] border-gray-300 bg-white w-[320px] shadow-lg rounded-xl"
                        aria-labelledby="navbarDropdown">
                        <div class="flex flex-col gap-2">
                            <div class="flex flex-col gap-2 ">
                                <div class="inline-flex items-center justify-start gap-2">
                                    <div
                                        class="flex items-center justify-center text-xl font-medium text-center text-white transition-all duration-300 bg-gray-400 rounded-full shadow-md cursor-pointer w-14 h-14 hover:bg-gray-500 hover:text-gray-600">
                                        {{ \Illuminate\Support\Str::limit(Auth::user()->name, 1, '') }}
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <h1 class="text-lg font-medium text-gray-700 ">
                                            Bonjour {{ \Illuminate\Support\Str::limit(Auth::user()->name, 20, '') }} !
                                        </h1>
                                        <label class="text-sm text-gray-400">
                                            {{ \Illuminate\Support\Str::limit(Auth::user()->email, 50, '') }}
                                        </label>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-1">
                                    <a href="{{ route('superAdmins.about') }}"
                                        class="inline-flex items-center w-full gap-2 px-4 py-2 text-sm text-gray-500 duration-100 rounded-md hover:bg-gray-100 hover:text-gray-700">
                                        <i class="text-gray-500 fa-regular fa-user"></i>
                                        Gérer mon profil personnel
                                    </a>
                                    <button
                                        class="inline-flex items-center w-full gap-2 px-4 py-1 text-sm text-gray-500 rounded-md hover:bg-gray-100 hover:text-gray-900"
                                        type="button" data-bs-toggle="modal" data-bs-target="#logout">
                                        <i class="text-gray-500 fa-solid fa-arrow-right-from-bracket"></i>
                                        Se déconnecter
                                    </button>
                                </div>
                            </div>
                            <hr class="border-gray-400" />

                            <div class="inline-flex items-center justify-center gap-3 p-2 text-gray-400 footer">
                                <p class="text-sm">Règle de confidentialité</p>
                                <p class="text-sm">Condition d'utilisation</p>
                            </div>

                        </div>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true" id="logout">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-white border-none h-[300px] w-[430px] justify-center gap-2 rounded-xl"
                id="lottieAnimation">
                <div class="flex flex-col items-center p-3 rounded">
                    <lottie-player src="{{ asset('Animations/Logout.json') }}" background="transparent" speed="1"
                        style="width: 200px; height: 100px;" loop autoplay></lottie-player>
                    <h1 class="flex flex-1 text-3xl font-semibold text-purple-700" id="staticBackdropLabel">Deconnexion
                    </h1>
                </div>
                <p class="px-4 text-xl text-center text-gray-600">Voulez-vous vraiment vous deconnectez ?</p>
                <div class="inline-flex justify-center gap-3 p-3">
                    <button type="button"
                        class="px-4 py-2 text-lg text-purple-600 transition duration-200 scale-95 rounded-full border-custom hover:text-purple-700 hover:scale-100"
                        data-bs-dismiss="modal" data-bs-dismiss="tooltip">Non,
                        annuler</button>
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();"
                        class="px-4 py-2 text-lg text-white transition duration-200 scale-95 bg-purple-600 rounded-full hover:scale-100 hover:bg-purple-700"
                        data-bs-dismiss="modal">Oui, deconnexion</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="text-gray-500 modal fade" id="modal_deconnexion" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="border-none shadow-xl modal-dialog rounded-xl">
            <div class="modal-content">
                {{-- HEADER MODAL --}}
                <div class="inline-flex items-center px-4 pt-3 pb-2">
                    <div class="flex flex-1 gap-2">
                        <i class="items-center text-2xl text-blue-500 bi bi-info-circle-fill"></i>
                        <h1 class="text-[16px] text-blue-500 font-semibold flex items-center" id="exampleModalLabel">
                            Deconnexion
                        </h1>
                    </div>
                    <button type="button" class="flex items-center h-full" data-bs-dismiss="modal" aria-label="Close">
                        <i class="text-xl bi bi-x"></i>
                    </button>
                </div>
                {{-- END HEADER --}}
                {{-- BODY MODAL --}}
                <div class="pt-2 pb-4 modal-bodypx-4">
                    Voulez-vous vraiment vous deconnecter ?
                </div>
                <div class="flex justify-end flex-1 gap-2 px-4 py-3 border-none bg-gray-50 rounded-xl">
                    <button type="button"
                        class="border border-1 border-blue-500 text-blue-500 rounded text-[12px] hover:border-blue-600 uppercase hover:text-blue-600 transition-all px-3 py-[10px]"
                        data-bs-dismiss="modal">Annuler</button>
                    <a class="text-white bg-blue-500 px-3 py-[10px] text-[12px] rounded uppercase hover:bg-blue-600 transition-all"
                        href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                      document.getElementById('logout-form').submit();">Oui,
                        je confirme</a>
                </div>
            </div>
        </div>
    </div>
@endguest
