@php
  $menus = [
        ['label' => 'Analytics', 'icon' => '📊', 'link' => 'https://analytics.forma-fusion.com/formateur/tableau-de-bord'],
        ['label' => 'Apprenants', 'icon' => '👥', 'link' => 'https://apprenants.forma-fusion.com/form/apprenants'],
        ['label' => 'Centre de Formation', 'icon' => '🏬', 'link' => 'https://clients.forma-fusion.com/centreDeFormation'],
        // ['label' => 'Test', 'icon' => '✍️', 'link' => 'https://test.forma-fusion.com/qcm/index'],
        ['label' => 'Projets', 'icon' => '📋', 'link' => 'https://projets.forma-fusion.com/projetsForm'],
        ['label' => 'Agenda', 'icon' => '📅', 'link' => 'https://agenda.forma-fusion.com/agendaForms'],
        ['label' => 'Evaluation', 'icon' => '📝', 'link' => 'https://evaluations.forma-fusion.com/projetsForm'],
        ['label' => 'Présence', 'icon' => '✅', 'link' => 'https://presence.forma-fusion.com/projetsForm'],
    ];
@endphp


<div class="bg-base-100 shadow-sm px-4">
  <div class="w-full navbar mx-auto">
    <div class="inline-flex items-center gap-4">
        <a href="{{route('projetForms.indexForm')}}" class="flex items-center gap-2">
          <span class="text-2xl">📋</span>
          <h1 class="text-2xl font-semibold text-gray-700">Projets</h1>
        </a>
    </div>    
    <div class="flex gap-2">
    @auth
        @if ($index == true)
        <span data-head="filter" class="flex flex-col items-center gap-2 lg:flex-row">
            <button class="btn btn-square btn-ghost" id="filterButton" title="Filtrer le projet"><i class="fa-solid fa-filter"></i></button>
        </span>
        @endif
    @endauth

      <div class="dropdown dropdown-end">
        <div tabindex="0" role="button" class="btn btn-ghost btn-square">
          <button aria-label="Applications">
            <i class="fa-solid fa-grid text-2xl"></i>
          </button>
        </div>
        <div
          tabindex="0"
          class="dropdown-content z-30 mt-3 w-96 bg-white rounded-xl shadow-lg p-4">
          <div class="">
            <div class="grid grid-cols-3 gap-2">
              @if (Auth::user() !== null)
                <a target="_blank" href="https://profils.forma-fusion.com/miniCv" class="flex flex-col items-center p-3 cursor-pointer rounded-lg hover:bg-slate-100 transition-colors">
                    @if (isset(Auth::user()->photo))
                        <div tabindex="0" role="button" class="mt-1 avatar mb-1">
                            <div class="w-7 rounded-full">
                                <img alt="Profile"
                                    src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/formateurs/{{ Auth::user()->photo }}" />
                            </div>
                        </div>
                    @elseif (isset(Auth::user()->name))
                        <button tabindex="0" id="navbarDropdown"
                            class="text-xl font-medium btn bg-slate-200 hover:bg-slate-200/90 text-slate-600 hover:text-slate-600"
                            role="button">
                            {{ \Illuminate\Support\Str::limit(Auth::user()->name, 1, '') }}
                        </button>
                    @endif
                    <span class="text-sm text-gray-600 text-center">Compte</span>
                </a>
              @endif
              @foreach ($menus as $item)
                <a href="{{$item['link']}}" target="_blank" class="flex flex-col items-center p-3 rounded-lg hover:bg-slate-100 transition-colors">
                  <span class="text-2xl mb-1">{{$item['icon']}}</span>
                  <span class="text-sm text-gray-600 text-center">{{$item['label']}}</span>
                </a>
              @endforeach
            </div>
          </div>
        </div>
      </div>

      @guest
      <a class="text-white btn btn-primary" href="{{ route('user.login') }}"><i class="fa fa-user"
        aria-hidden="true"></i> Se connecter</a>
      @else
        <div class="dropdown dropdown-end">
          <div tabindex="0" role="button">
            <div class="avatar">
              @if (isset(Auth::user()->photo))
                  <div tabindex="0" role="button" class="mt-1 avatar">
                      <div class="w-10 rounded-full">
                          <img alt="Profile"
                              src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/formateurs/{{ Auth::user()->photo }}" />
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
            </div>
          </div>
          <ul
            tabindex="0"
            class="menu dropdown-content bg-base-100 rounded-box z-30 mt-3 w-max p-2 shadow">
            <div class="px-4 py-2 border-b border-gray-100">
              <p class="font-medium text-gray-900"> {{ \Illuminate\Support\Str::limit(Auth::user()->name, 20, '') }}
                {{ \Illuminate\Support\Str::limit(Auth::user()->firstName, 50, '') }}</p>
              <p class="text-sm text-gray-500">{{ \Illuminate\Support\Str::limit(Auth::user()->email, 50, '') }}</p>
            </div>
            <li>
              <a target="_blank" href="https://profils.forma-fusion.com/miniCv" class="hover:text-inherit">
                <i class="fa-solid fa-user"></i>
                Gérer mon compte
              </a>
            </li>
            <li class="cursor-pointer">
              <span>
                <i class="fa-solid fa-gear"></i>
                Paramètres
              </span>
            </li>
            <li class="cursor-pointer" onclick="logoutButton()">
              <span>
                <i class="fa-solid fa-power-off"></i>
                Deconnexion
              </span>
            </li>
          </ul>

          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
          </form>
        </div>
      @endguest
    </div>
  </div>
</div>