<div class="du-navbar bg-white fixed z-[1] top-0 shadow-xl">
    <div class="du-navbar-start">
        <div class="dropdown">
            <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
                </svg>
            </div>
            <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
                <li><a href="{{ url('/formation') }}">Trouver des formations</a></li>
                <li><a href="{{ url('/organisme') }}">Nos organismes</a></li>
                <li><a href="/vous_etes">Vous êtes</a></li>
                <li><a href="{{ route('contact.formafusion') }}">Nous contacter</a></li>
                <li>
                    @if (isset($infoProfilCfp->idTypeCustomer))
                        @if ($infoProfilCfp->idTypeCustomer == 1)
                            <a href="{{ route('home') }}">
                                Gérer mes formations
                            </a>
                        @elseif ($infoProfilCfp->idTypeCustomer == 2)
                            <a href="{{ route('home.entreprise') }}">
                                Gérer mes formations
                            </a>
                        @else
                        @endif
                    @else
                        <a href="{{ route('user.login') }}">
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
        <a href="{{ url('/') }}" class="btn btn-ghost text-xl"><img
                src="{{ asset('img/logo/Logo_horizontal.svg') }}" alt="Logo" class="h-12"></a>
    </div>
    <div class="du-navbar-center hidden lg:flex">
        <ul class="menu menu-horizontal px-1">
            <li><a href="{{ url('/formation') }}">Trouver des formations</a></li>
            <li><a href="{{ url('/organisme') }}">Nos organismes</a></li>
            @guest
                <li><a href="/vous_etes">Vous êtes</a></li>
            @endguest
            <li><a href="{{ route('contact.formafusion') }}">Nous contacter</a></li>
            <li>
                @if (isset($infoProfilCfp->idTypeCustomer))
                    @if ($infoProfilCfp->idTypeCustomer == 1)
                        <a href="{{ route('home') }}">
                            Gérer mes formations
                        </a>
                    @elseif ($infoProfilCfp->idTypeCustomer == 2)
                        <a href="{{ route('home.entreprise') }}">
                            Gérer mes formations
                        </a>
                    @else
                    @endif
                @else
                    <a href="{{ route('user.login') }}">
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

    <div class="du-navbar-end">
        @guest
            <a class="btn btn-primary text-white btn-sm" href="{{ route('user.login') }}"><i class="fa-solid fa-user"
                    aria-hidden="true"></i> Se connecter</a>
        @else
            <button class="btn btn-ghost btn-sm btn-circle mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                    <div class="w-10 rounded-full">
                        <img alt="Tailwind CSS du-Navbar component"
                            src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
                    </div>
                </div>
                <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
                    <li><a>Mon profil</a></li>
                    <li><a>Paramètres</a></li>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <li><button type="submit">Deconnexion</button></li>
                    </form>
                </ul>
            </div>
        @endguest
    </div>
</div>
