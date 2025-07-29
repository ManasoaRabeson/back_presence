@php
  $menus = [
        ['label' => 'Analytics', 'icon' => '📊', 'link' => 'https://analytics.forma-fusion.com/home-etp'],
        ['label' => 'Employés', 'icon' => '👥', 'link' => 'https://apprenants.forma-fusion.com/etp/employes'],
        ['label' => 'Centre de Formation', 'icon' => '🏬', 'link' => 'https://clients.forma-fusion.com/etp/invites/cfp'],
        // ['label' => 'Formateurs', 'icon' => '🧑‍🏫', 'link' => 'https://formateurs.forma-fusion.com/cfp/forms'],
        ['label' => 'Référents', 'icon' => '👨‍💼', 'link' => 'https://referents.forma-fusion.com/etp/referents'],
        ['label' => 'Test', 'icon' => '✍️', 'link' => 'https://test.forma-fusion.com/qcm/index'],
        ['label' => 'Projets', 'icon' => '📋', 'link' => 'https://projets.forma-fusion.com/etp/projets'],
        ['label' => 'Licence', 'icon' => '📜', 'link' => 'https://licence.forma-fusion.com/etp/abonnement'],
        ['label' => 'Catalogues', 'icon' => '📚', 'link' => 'https://catalogue.forma-fusion.com/etp/modules'],
        ['label' => 'Agenda', 'icon' => '📅', 'link' => 'https://agenda.forma-fusion.com/agendaEtps'],
        ['label' => 'Marketplace', 'icon' => '🏪', 'link' => 'https://marketplace.forma-fusion.com/'],
        // ['label' => 'Badge', 'icon' => '🏅', 'link' => 'https://badge.forma-fusion.com/'],
        ['label' => 'Evaluation', 'icon' => '📝', 'link' => 'https://evaluations.forma-fusion.com/etp/projets'],
        // ['label' => 'Présence', 'icon' => '✅', 'link' => 'https://presence.forma-fusion.com/'],
        ['label' => 'Inscription', 'icon' => '📝', 'link' => 'https://inscription.forma-fusion.com/'],
        ['label' => 'Dossiers', 'icon' => '📚', 'link' => 'https://dossiers.forma-fusion.com/etp/dossier'],
        ['label' => 'Reporting', 'icon' => '📩', 'link' => 'https://reporting.forma-fusion.com/etp/reporting/formation'],
        ['label' => 'Réservations', 'icon' => '📍', 'link' => 'https://reservation.forma-fusion.com/etp/reservations/projet_inter'],
    ];
@endphp


<div class="bg-base-100 shadow-sm px-4">
  <div class="w-full navbar mx-auto">
    <div class="inline-flex items-center gap-4">
        <a href="{{route('etp.projets.index')}}" class="flex items-center gap-2">
          <span class="text-2xl">📋</span>
          <h1 class="text-2xl font-semibold text-gray-700">Projets</h1>
        </a>
    </div>

    @auth
    @if ($index == true)
      <div class="">
          <span class="">
            <span class="inline-flex items-center gap-8">
                <div class="indicator">
                    <span
                        class="indicator-item !-translate-x-2 !rounded-md !h-[1.30rem] indicator-middle badge text-white bg-[#369ACC]">{{ $encours[0]->projet_nb ?? 0 }}</span>
                    <input type="radio" name="statut" aria-label="En cours"
                        class="btn btn-outline statut_item_checkbox pr-14 !border-slate-200 btn-sm !rounded-md"
                        {{ $encours[0]->projet_nb ?? 'disabled' }}
                        value="{{ $encours[0]->project_status ?? '' }}" />
                </div>

                <div class="indicator">
                    <span
                        class="indicator-item !-translate-x-2 !rounded-md !h-[1.30rem] indicator-middle badge text-white bg-[#F8E16F] ">{{ $preparation[0]->projet_nb ?? 0 }}</span>
                    <input type="radio" name="statut" aria-label="En préparation"
                        class="btn btn-outline statut_item_checkbox pr-14 !border-slate-200 btn-sm !rounded-md enpreparation"
                        {{ $preparation[0]->projet_nb ?? 'disabled' }}
                        value="{{ $preparation[0]->project_status ?? '' }}" />
                </div>

                <div class="indicator">
                    <span
                        class="indicator-item !-translate-x-2 !rounded-md !h-[1.30rem] indicator-middle badge text-white bg-[#CBABD1]">{{ $planifier[0]->projet_nb ?? 0 }}</span>
                    <input type="radio" name="statut" aria-label="Planifié"
                        class="btn btn-outline statut_item_checkbox pr-14 !border-slate-200 btn-sm !rounded-md"
                        {{ $planifier[0]->projet_nb ?? 'disabled' }}
                        value="{{ $planifier[0]->project_status ?? '' }}" />
                </div>

                <div class="indicator">
                    <span
                        class="indicator-item !-translate-x-2 !rounded-md !h-[1.30rem] indicator-middle badge text-white bg-[#95CF92]">{{ $terminer[0]->projet_nb ?? 0 }}</span>
                    <input type="radio" name="statut" aria-label="Terminé"
                        class="btn btn-outline statut_item_checkbox pr-14 !border-slate-200 btn-sm !rounded-md"
                        {{ $terminer[0]->projet_nb ?? 'disabled' }}
                        value="{{ $terminer[0]->project_status ?? '' }}" />
                </div>

                <div class="indicator">
                    <span
                        class="indicator-item !-translate-x-2 !rounded-md !h-[1.30rem] indicator-middle badge text-white bg-[#6F1926]">{{ $cloturer[0]->projet_nb ?? 0 }}</span>
                    <input type="radio" name="statut" aria-label="Clôturé"
                        class="btn btn-outline statut_item_checkbox pr-14 !border-slate-200 btn-sm !rounded-md"
                        {{ $cloturer[0]->projet_nb ?? 'disabled' }}
                        value="{{ $cloturer[0]->project_status ?? '' }}" />
                </div>

                <div class="indicator">
                    <span
                        class="indicator-item !-translate-x-2 !rounded-md !h-[1.30rem] indicator-middle badge text-white bg-[#DE324C]">{{ $annuler[0]->projet_nb ?? 0 }}</span>
                    <input type="radio" name="statut" aria-label="Annulé"
                        class="btn btn-outline statut_item_checkbox pr-14 !border-slate-200 btn-sm !rounded-md"
                        {{ $annuler[0]->projet_nb ?? 'disabled' }}
                        value="{{ $annuler[0]->project_status ?? '' }}" />
                </div>

                <div class="indicator">
                    <span
                        class="indicator-item !-translate-x-2 !rounded-md !h-[1.30rem] indicator-middle badge text-white bg-[#2E705A]">{{ $reporter[0]->projet_nb ?? 0 }}</span>
                    <input type="radio" name="statut" aria-label="Reporté"
                        class="btn btn-outline statut_item_checkbox pr-14 !border-slate-200 btn-sm !rounded-md"
                        {{ $reporter[0]->projet_nb ?? 'disabled' }}
                        value="{{ $reporter[0]->project_status ?? '' }}" />
                </div>
            </span>
        </span>
      </div>
    @endif
    @endauth 
    
    <div class="flex gap-2">
    @auth
    @if ($index == true)
      <span data-head="filter" class="flex flex-col items-center gap-2 lg:flex-row">
        <button class="btn btn-square btn-ghost" id="filterButton" title="Filtrer le projet"><i class="fa-solid fa-filter"></i></button>
      </span>
    @endif

      <x-btn-nouveau onclick="__addDrawer()" label="Nouveau" />
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
                <a target="_blank" href="https://profils.forma-fusion.com/etp/profils" class="flex flex-col items-center p-3 cursor-pointer rounded-lg hover:bg-slate-100 transition-colors">
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
          <ul
            tabindex="0"
            class="menu dropdown-content bg-base-100 rounded-box z-30 mt-3 w-max p-2 shadow">
            <div class="px-4 py-2 border-b border-gray-100">
              <p class="font-medium text-gray-900"> {{ \Illuminate\Support\Str::limit(Auth::user()->name, 20, '') }}
                {{ \Illuminate\Support\Str::limit(Auth::user()->firstName, 50, '') }}</p>
              <p class="text-sm text-gray-500">{{ \Illuminate\Support\Str::limit(Auth::user()->email, 50, '') }}</p>
            </div>
            <li>
              <a target="_blank" href="https://profils.forma-fusion.com/etp/profils" class="hover:text-inherit">
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