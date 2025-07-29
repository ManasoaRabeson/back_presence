<div class="flex flex-col w-[calc(100%-50px)] fixed z-20">
  <div class="relative z-50 flex flex-col">
    <div class="relative inline-flex justify-between w-full h-10 bg-[#a462a4] px-2">
      <div class="inline-flex items-center gap-2">
        {{-- LISTE DES MENUS --}}
        <nav class="flex flex-row flex-1 items-center h-full ">
          <ul class="flex flex-row flex-1 items-center h-full" id="navigation">
            <x-nav-sub route="{{ route('projetFormInternes.indexFormInterne') }}">Projets</x-nav-sub>
            <x-nav-sub route="{{ route('agenda.formInterne') }}">Agenda</x-nav-sub>
            <x-nav-sub route="{{ route('etpFormInt.index') }}">Mon entreprise</x-nav-sub>
            {{-- <x-nav-sub route="{{ route('miniCv.index') }}">Mon mini CV</x-nav-sub> --}}
          </ul>
        </nav>
      </div>
      <div class="inline-flex gap-3 items-center">
        <p class="text-white font-normal">Bonjour {{ \Illuminate\Support\Str::limit(Auth::user()->firstName, 20, '') }}
          !
        </p>

        <div class="flex flex-row gap-3 justify-center items-center">
          @guest
            @if (Route::has('login'))
              <a class="nav-link" href="{{ route('login') }}">{{ __('Se connecter') }}</a>
            @endif

            @if (Route::has('register'))
              <a class="nav-link" href="{{ route('register') }}">{{ __('Créer un compte') }}</a>
            @endif
          @else
            <a id="navbarDropdown"
              class="nav-link w-7 h-7 rounded-full text-center flex justify-center items-center text-[#81338a] text-xl font-semibold bg-gray-50 hover:bg-gray-200 hover:text-gray-600 transition-all duration-300"
              href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
              {{-- <img class="w-8 h-8 rounded-full" src="" alt="Rounded avatar"> --}}
              {{ \Illuminate\Support\Str::limit(Auth::user()->firstName, 1, '') }}
            </a>

            <div
              class="dropdown-menu dropdown-menu-end p-3 pb-0 items-center border-[1px] border-gray-300 bg-white w-[320px] shadow-lg rounded-xl"
              aria-labelledby="navbarDropdown">
              <div class="flex flex-col gap-2">
                <div class="flex flex-col gap-2 ">
                  <div class="inline-flex items-center justify-start gap-2">
                    @if (isset(Auth::user()->photo) && Auth::user()->photo != '')
                      <img src="{{ asset('img/formateurs/' . Auth::user()->photo) }}" alt="Photo de profil"
                        class="w-14 h-14 rounded-full">
                    @else
                      <div
                        class="w-14 h-14 rounded-full text-center cursor-pointer flex justify-center items-center text-white text-xl font-medium bg-gray-400 hover:bg-gray-500 hover:text-gray-600 transition-all duration-300 shadow-md">
                        {{ \Illuminate\Support\Str::limit(Auth::user()->firstName, 1, '') }}
                      </div>
                    @endif
                    <div class="flex flex-col gap-1">
                      <h1 class="text-gray-700 text-lg font-medium ">
                        Bonjour {{ \Illuminate\Support\Str::limit(Auth::user()->firstName, 20, '') }} !
                      </h1>
                      <label class="text-sm text-gray-400">
                        {{ \Illuminate\Support\Str::limit(Auth::user()->email, 50, '') }}
                      </label>
                      <label class="text-sm text-gray-500">
                        Votre statut : <span class="text-blue-500 text-sm">Formateur Interne</span>
                      </label>
                      {{-- <label>Votre Licence : Invité valide jusqu'au 31/01/2024</label> --}}
                    </div>
                  </div>

                  <div class="flex flex-col">
                    {{-- <a href="{{ route('profile.edit') }}"
                        class="inline-flex items-center gap-2 text-gray-500 text-sm w-full px-4 py-1 rounded-md duration-100 hover:bg-gray-100 hover:text-gray-700">
                        <i class="fa-regular text-gray-500 fa-user"></i>
                        Gérer mon profil
                      </a> --}}
                    <button
                      class="inline-flex items-center gap-2 text-gray-500 text-sm w-full px-4 py-1 rounded-md duration-100 hover:bg-gray-100 hover:text-gray-700">
                      <i class="bi bi-shield-exclamation text-gray-500"></i>
                      Donner votre avis
                    </button>
                    <button
                      class="inline-flex items-center gap-2 text-gray-500 text-sm w-full px-4 py-1 rounded-md duration-100 hover:bg-gray-100 hover:text-gray-700">
                      <i class="fa-regular text-gray-500 fa-circle-question"></i>
                      Aide et assistance
                    </button>
                    <button
                      class="inline-flex items-center gap-2 text-gray-500 text-sm w-full px-4 py-1 rounded-md duration-100 hover:bg-gray-100 hover:text-gray-700"
                      type="button" data-bs-toggle="modal" data-bs-target="#logout">
                      <i class="fa-solid text-gray-500 fa-arrow-right-from-bracket"></i>
                      Se déconnecter
                    </button>
                  </div>
                </div>

                <div class="footer inline-flex items-center justify-center text-gray-400 gap-3 p-2">
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
</div>

