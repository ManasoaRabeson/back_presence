<section class="">
    <nav class="fixed top-0 z-10 flex items-center justify-between w-full px-4 py-4 bg-white">
        <div>
            <a class="flex text-xl font-bold leading-none 2xl:text-3xl" href="{{ url('/') }}">
                <img src="{{ asset('img/logo/Logo_horizontal.svg') }}" alt="Logo" class="h-12">
            </a>
        </div>
        <div class="ml-auto xl:hidden">
            <button class="flex items-center p-3 text-blue-600 navbar-burger">
                <svg class="block w-4 h-4 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <title>Mobile menu</title>
                    <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"></path>
                </svg>
            </button>
        </div>
        <div class="ml-12">
            <ul class="hidden xl:flex xl:items-center xl:space-x-6">
                <li><a class="text-xs 2xl:text-md text-gray-400 font-bold hover:text-[#a462a4]"
                        href="{{ url('/formation') }}">TROUVER DES FORMATIONS</a></li>
                <li><a class="text-xs 2xl:text-md text-gray-400 font-bold hover:text-[#a462a4]" href="#">VOUS ETES</a></li>
                <li><a class="text-xs 2xl:text-md text-gray-400 font-bold hover:text-[#a462a4]" href="#">NOUS CONTACTER</a></li>
                <li><a class="text-xs 2xl:text-md text-gray-400 font-bold hover:text-[#a462a4]" href="#">GERER MES
                        FORMATIONS</a></li>
            </ul>
        </div>

        @guest
            <a class="hidden xl:inline-block xl:ml-auto xl:mr-3 py-2 px-6 bg-[#a462a4] text-sm text-white font-bold rounded-full transition duration-200"
                href="{{ route('user.login') }}">
                <i class="mr-2 fa fa-user" aria-hidden="true"></i> Se connecter
            </a>
        @else
            <div class="hidden text-left xl:inline-block">
                <div>
                    <button id="menu-button" class="flex items-center space-x-2">
                        @if (isset(auth()->user()->photo))
                            <img src="/img/employes/{{ auth()->user()->photo }}" alt="" class="w-10 rounded-full">
                        @else
                            <i class="fa-solid fa-circle-user fa-2xl"></i>
                        @endif
                        <p class="hidden font-semibold xl:inline-block xl:ml-auto xl:mr-3">{{ Auth::user()->name }}</p>
                    </button>
                </div>
                <div id="dropdown-menu"
                    class="absolute right-0 z-10 hidden w-56 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                    <div class="py-1">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"><i
                                class="fa-regular fa-user"></i> Profile</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"><i
                                class="fa-solid fa-gear"></i> Parametre</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="block w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100">
                                <i class="fa-solid fa-right-from-bracket"></i> Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endguest
    </nav>

    <div class="fixed z-50 hidden w-full navbar-menu">
        <div class="fixed inset-0 bg-gray-800 opacity-25 navbar-backdrop"></div>
        <nav
            class="fixed top-0 bottom-0 left-0 flex flex-col w-5/6 max-w-sm px-6 py-6 overflow-y-auto bg-white border-r">
            <div class="flex items-center mb-8">
                <a class="flex mr-auto text-3xl font-bold leading-none" href="{{ url('/') }}">
                    <img class="h-10" alt="logo" viewBox="0 0 10240 10240"
                        src="http://127.0.0.1:8000/img/logo/Logo_mark.svg">
                    </img> Forma Fusion
                </a>
                <button class="navbar-close">
                    <svg class="w-6 h-6 text-gray-400 cursor-pointer hover:text-gray-500"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <div>
                <ul>
                    <li class="mb-1">
                        <a class="block p-4 text-sm font-semibold text-gray-400 rounded hover:bg-blue-50 hover:text-blue-600"
                            href="{{ url('/formation') }}">TROUVER DES FORMATIONS</a>
                    </li>
                    <li class="mb-1">
                        <a class="block p-4 text-sm font-semibold text-gray-400 rounded hover:bg-blue-50 hover:text-blue-600"
                            href="#">VOUS ETES</a>
                    </li>
                    <li class="mb-1">
                        <a class="block p-4 text-sm font-semibold text-gray-400 rounded hover:bg-blue-50 hover:text-blue-600"
                            href="#">NOUS CONTACTER</a>
                    </li>
                    <li class="mb-1">
                        <a class="block p-4 text-sm font-semibold text-gray-400 rounded hover:bg-blue-50 hover:text-blue-600"
                            href="#">GERER MES FORMATIONS</a>
                    </li>
                </ul>
            </div>
            <div class="mt-auto">
                <div class="pt-6">
                    @guest
                        <a class="block px-4 py-3 mb-3 leading-loose text-xs text-center font-semibold leading-none text-white bg-[#a462a4] rounded-full"
                            href="{{ route('user.login') }}"><i class="mr-2 fa fa-user" aria-hidden="true"></i> Se
                            connecter</a>
                    @else
                        <p class="block mb-3 leading-loose">Bonjour {{ Auth::user()->name }}</p>
                    @endguest
                </div>
            </div>
        </nav>
    </div>
</section>
