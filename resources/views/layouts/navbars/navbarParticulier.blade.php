<div class="du-navbar bg-white fixed z-[1] top-0 shadow-xl">
    <div class="du-navbar-start">
        <div class="dropdown">
            <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
                </svg>
            </div>
            <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
                <li><a href="{{ url('/formation') }}">Trouver des formations</a></li>
                <li><a href="{{ url('/organisme') }}">Nos organismes</a></li>
                <li><a href="{{ route('contact.formafusion') }}">Nous contacter</a></li>
                <li><a href="{{ url('/particulier/projet') }}">Mes projets</a></li>
                <li><a href="{{ url('/particulier/agendaForms') }}">Mon agenda</a></li>
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
        <a href="{{ url('/') }}" class="text-xl btn btn-ghost"><img
                src="{{ asset('img/logo/Logo_horizontal.svg') }}" alt="Logo" class="h-12"></a>
    </div>
    <div class="hidden du-navbar-center lg:flex">
        <ul class="px-1 menu menu-horizontal">
            <li><a class="text-slate-600 hover:text-slate-500" href="{{ url('/formation') }}">Trouver des formations</a></li>
            <li><a class="text-slate-600 hover:text-slate-500" href="{{ url('/organisme') }}">Nos organismes</a></li>
            <li><a class="text-slate-600 hover:text-slate-500" href="{{ route('contact.formafusion') }}">Nous contacter</a></li>
            <li><a class="text-slate-600 hover:text-slate-500" href="{{ url('/particulier/projet') }}">Mes projets</a></li>
            <li><a class="text-slate-600 hover:text-slate-500" href="{{ url('/particulier/agendaForms') }}">Mon agenda</a></li>
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

    <div class="du-navbar-end">
        <div class="inline-flex items-center gap-3">
            <p class="text-[#893a8d] font-normal">Bonjour
                {{ \Illuminate\Support\Str::limit(Auth::user()->firstName, 20, '') }}
                !
            </p>

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
                        class="nav-link w-7 h-7 rounded-full text-center flex justify-center items-center text-white text-xl font-semibold bg-[#b963a3] hover:bg-[#893a8d] hover:text-gray-600 transition-all duration-300"
                        href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                        v-pre>
                        {{ \Illuminate\Support\Str::limit(Auth::user()->firstName, 1, '') }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-end p-3 pb-0 items-center border-[1px] border-gray-300 bg-white w-[320px] shadow-lg rounded-xl"
                        aria-labelledby="navbarDropdown">
                        <div class="flex flex-col gap-2">
                            <div class="flex flex-col gap-2 ">
                                <div class="inline-flex items-center justify-start gap-2">
                                    @if (isset(Auth::user()->photo) && Auth::user()->photo != '')
                                        <img src="{{ asset('img/formateurs/' . Auth::user()->photo) }}"
                                            alt="Photo de profil" class="rounded-full w-14 h-14">
                                    @else
                                        <div
                                            class="w-14 h-14 rounded-full text-center cursor-pointer flex justify-center items-center text-white text-xl font-medium bg-[#b963a3] hover:bg-[#893a8d] hover:text-gray-600 transition-all duration-300 shadow-md">
                                            {{ \Illuminate\Support\Str::limit(Auth::user()->firstName, 1, '') }}
                                        </div>
                                    @endif
                                    <div class="flex flex-col gap-1">
                                        <h1 class="text-lg font-medium text-gray-700 ">
                                            Bonjour {{ \Illuminate\Support\Str::limit(Auth::user()->firstName, 20, '') }} !
                                        </h1>
                                        <label class="text-sm text-gray-400">
                                            {{ \Illuminate\Support\Str::limit(Auth::user()->email, 50, '') }}
                                        </label>
                                        <label class="text-sm text-gray-500">
                                            Votre statut : <span class="text-sm text-blue-500">Particulier</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="flex flex-col">
                                    <button
                                        class="inline-flex items-center w-full gap-2 px-4 py-1 text-sm text-gray-500 duration-100 rounded-md hover:bg-gray-100 hover:text-gray-700">
                                        <i class="text-gray-500 bi bi-shield-exclamation"></i>
                                        Donner votre avis
                                    </button>
                                    <button
                                        class="inline-flex items-center w-full gap-2 px-4 py-1 text-sm text-gray-500 duration-100 rounded-md hover:bg-gray-100 hover:text-gray-700">
                                        <i class="text-gray-500 fa-regular fa-circle-question"></i>
                                        Aide et assistance
                                    </button>
                                    <button
                                        class="inline-flex items-center w-full gap-2 px-4 py-1 text-sm text-gray-500 duration-100 rounded-md hover:bg-gray-100 hover:text-gray-700"
                                        type="button" data-bs-toggle="modal" data-bs-target="#logout">
                                        <i class="text-gray-500 fa-solid fa-arrow-right-from-bracket"></i>
                                        Se déconnecter
                                    </button>
                                </div>
                            </div>

                            <div class="inline-flex items-center justify-center gap-3 p-2 text-gray-400 footer">
                                <p class="text-sm">Règle de confidentialité</p>
                                <p class="text-sm">Condition d'utilisation</p>
                            </div>
                        </div>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</div>
