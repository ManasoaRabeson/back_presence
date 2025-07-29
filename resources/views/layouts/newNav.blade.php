@php
  $menus = [
        ['label' => __('launcher.analytics'), 'icon' => '📊', 'link' => 'https://analytics.forma-fusion.com/home'],
        ['label' => __('launcher.factures'), 'icon' => '💰', 'link' => 'https://factures.forma-fusion.com/cfp/factures/id/1'],
        ['label' => __('launcher.apprenants'), 'icon' => '👥', 'link' => 'https://apprenants.forma-fusion.com/cfp/apprenants'],
        ['label' => __('launcher.client'), 'icon' => '🏬', 'link' => 'https://clients.forma-fusion.com/cfp/invites/etp/list/1'],
        ['label' => __('launcher.formateurs'), 'icon' => '🧑‍🏫', 'link' => 'https://formateurs.forma-fusion.com/cfp/forms'],
        ['label' => __('launcher.referents'), 'icon' => '👨‍💼', 'link' => 'https://referents.forma-fusion.com/cfp/referents'],
        ['label' => __('launcher.test'), 'icon' => '✍️', 'link' => 'https://test.forma-fusion.com/home/accueil'],
        ['label' => __('launcher.projets'), 'icon' => '📋', 'link' => 'https://projets.forma-fusion.com/cfp/projets'],
        ['label' => __('launcher.licence'), 'icon' => '📜', 'link' => 'https://licence.forma-fusion.com/cfp/abonnement'],
        ['label' => __('launcher.admin'), 'icon' => '⚙️', 'link' => 'https://admin.forma-fusion.com/'],
        ['label' => __('launcher.catalogues'), 'icon' => '📚', 'link' => 'https://catalogue.forma-fusion.com/cfp/modules'],
        ['label' => __('launcher.agenda'), 'icon' => '📅', 'link' => 'https://agenda.forma-fusion.com/agendaCfps'],
        ['label' => __('launcher.photos'), 'icon' => '📸', 'link' => 'https://photos.forma-fusion.com/cfp/gallery'],
        ['label' => __('launcher.marketplace'), 'icon' => '🏪', 'link' => 'https://marketplace.forma-fusion.com/'],
        ['label' => __('launcher.badge'), 'icon' => '🏅', 'link' => 'https://badge.forma-fusion.com/'],
        ['label' => __('launcher.opportunite'), 'icon' => '🎯', 'link' => 'https://opportunites.forma-fusion.com/cfp/prospection'],
        ['label' => __('launcher.evaluation'), 'icon' => '📝', 'link' => 'https://evaluations.forma-fusion.com/cfp/projets'],
        ['label' => __('launcher.presence'), 'icon' => '✅', 'link' => 'https://presence.forma-fusion.com/cfp/projets'],
        ['label' => __('launcher.inscription'), 'icon' => '📝', 'link' => 'https://inscription.forma-fusion.com/'],
        ['label' => __('launcher.dossiers'), 'icon' => '📚', 'link' => 'https://dossiers.forma-fusion.com/cfp/dossier'],
        ['label' => __('launcher.reporting'), 'icon' => '📩', 'link' => 'https://reporting.forma-fusion.com/reporting/formation'],
        ['label' => __('launcher.reservation'), 'icon' => '📍', 'link' => 'https://reservation.forma-fusion.com/cfp/rsv/5'],
    ];
@endphp


<div class="bg-base-100 shadow-sm px-4">
  <div class="w-full navbar mx-auto">
    <div class="inline-flex items-center gap-4">
        <a href="{{route('cfp.projets.index')}}" class="flex items-center gap-2">
          <span class="text-2xl">✅</span>
          <h1 class="text-2xl font-semibold text-gray-700">{{__('launcher.presence')}}</h1>
        </a>
    </div>

    @auth
    @if ($index == true)
      <div class="">
          <span class="">
            <span class="inline-flex flex-wrap items-center gap-x-4 gap-y-4">
                <div class="indicator">
                    <span
                        class="indicator-item indicator-middle badge !-translate-x-2 !h-[1.30rem] text-white bg-[#369ACC]">{{ $encours->projet_nb ?? 0 }}</span>
                    <input type="radio" name="statut" aria-label="{{__('statut.enCours')}}"
                        class="btn btn-outline statut_item_checkbox pr-14 !border-slate-200 btn-sm !rounded-md"
                        {{ $encours->projet_nb ?? 'disabled' }}
                        value="{{ $encours->project_status ?? '' }}" />
                </div>

                <div class="indicator">
                    <span
                        class="indicator-item indicator-middle badge !-translate-x-2 !h-[1.30rem] text-white bg-[#95CF92]">{{ $terminer->projet_nb ?? 0 }}</span>
                    <input type="radio" name="statut" aria-label="{{__('statut.termine')}}"
                        class="btn btn-outline statut_item_checkbox pr-14 !border-slate-200 btn-sm !rounded-md"
                        {{ $terminer->projet_nb ?? 'disabled' }}
                        value="{{ $terminer->project_status ?? '' }}" />
                </div>

                <div class="indicator">
                    <span
                        class="indicator-item indicator-middle badge !-translate-x-2 !h-[1.30rem] text-white bg-[#6F1926]">{{ $cloturer->projet_nb ?? 0 }}</span>
                    <input type="radio" name="statut" aria-label="{{__('statut.cloture')}}"
                        class="btn btn-outline statut_item_checkbox pr-14 !border-slate-200 btn-sm !rounded-md"
                        {{ $cloturer->projet_nb ?? 'disabled' }}
                        value="{{ $cloturer->project_status ?? '' }}" />
                </div>
            </span>
        </span>
      </div>
    @endif
    @endauth 
    
    <div class="flex gap-2">
    @auth
    <div class="dropdown">
      <div tabindex="0" role="button" class="btn btn-ghost">
        <i class="fa-solid fa-earth text-2xl"></i>
        {{__('launcher.lang')}}
      </div>
      <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-20 w-52 p-2 shadow-sm">
        <li><a href="/locale/fr">Français</a></li>
        <li><a href="/locale/en">English</a></li>
      </ul>
    </div>
    @if ($index == true)
      <span data-head="filter" class="flex flex-col items-center gap-2 lg:flex-row">
        <label class="swap swap-flip" onclick="view_click()">
            <!-- this hidden checkbox controls the state -->
            <input name="view_check" type="checkbox" />

            <!-- hamburger icon -->
            <span id="swap_1" class="hidden btn btn-ghost btn-square" title="Tableau">
                <i class="fa-solid fa-list"></i>
            </span>

            <!-- close icon -->
            <span id="swap_2" class="hidden btn btn-ghost btn-square" title="Carte">
                <i class="fa-solid fa-square"></i>
            </span>
        </label>
        <button class="btn btn-square btn-ghost" id="filterButton" title="Filtrer le projet"><i class="fa-solid fa-filter"></i></button>
      </span>

      <span class="inline-flex items-center gap-2">
        {{-- <input type="radio" role="tab" data-tab="projets" name="project_view" aria-label="🗃️"
            class="btn btn-ghost btn-square text-2xl" title="Projets" checked="checked" /> --}}
        {{-- <input type="radio" role="tab" data-tab="archives" name="project_view" aria-label="📦️"
            class="btn btn-ghost btn-square text-2xl" title="Archives" />
        <input type="radio" role="tab" data-tab="corbeilles" name="project_view" aria-label="🗑️"
            class="btn btn-ghost btn-square text-2xl" title="Corbeille" /> --}}
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
                <a target="_blank" href="https://profils.forma-fusion.com/cfp/profils" class="flex flex-col items-center p-3 cursor-pointer rounded-lg hover:bg-slate-100 transition-colors">
                    @if (isset(Auth::user()->photo))
                        <div tabindex="0" role="button" class="mt-1 avatar mb-1">
                            <div class="w-7 rounded-full">
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
      @else
        <div class="dropdown dropdown-end">
          <div tabindex="0" role="button">
            <div class="avatar">
              @if (isset(Auth::user()->photo))
                  <div tabindex="0" role="button" class="mt-1 avatar">
                      <div class="w-10 rounded-full">
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
            </div>
          </div>
          <ul tabindex="0" class="menu dropdown-content bg-base-100 rounded-box z-30 mt-3 w-max p-2 shadow">
            <div class="px-4 py-2 border-b border-gray-100">
              <p class="font-medium text-gray-900"> {{ \Illuminate\Support\Str::limit(Auth::user()->name, 20, '') }}
                {{ \Illuminate\Support\Str::limit(Auth::user()->firstName, 50, '') }}</p>
              <p class="text-sm text-gray-500">{{ \Illuminate\Support\Str::limit(Auth::user()->email, 50, '') }}</p>
            </div>
            <li>
              <a target="_blank" href="https://profils.forma-fusion.com/cfp/profils" class="hover:text-inherit">
                <i class="fa-solid fa-user"></i>
                {{__('launcher.manageAccount')}}
              </a>
            </li>
            <li class="cursor-pointer">
              <span>
                <i class="fa-solid fa-gear"></i>
                {{__('launcher.setting')}}
              </span>
            </li>
            <li class="cursor-pointer" onclick="logoutButton()">
              <span>
                <i class="fa-solid fa-power-off"></i>
                {{__('launcher.logout')}}
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