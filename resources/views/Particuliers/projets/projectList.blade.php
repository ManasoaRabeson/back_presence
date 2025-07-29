<li id="" class="shadow-md border-[1px] border-gray-100 rounded-md">
  <div class="grid grid-cols-6 gap-4">
    <div class="grid grid-cols-3 gap-2 col-span-2">
      <div
        class="grid col-span-1 gap-2 bg-gradient-to-br relative overflow-hidden
              @switch($p['project_status'])
                @case('En préparation')
                  from-[#66CDAA] to-[#66CDAA]/50
                  @break
                @case('Réservé')
                  from-[#33303D] to-[#33303D]/50
                  @break
                @case('En cours')
                  from-[#1E90FF] to-[#1E90FF]/50
                  @break
                @case('Terminé')
                  from-[#32CD32] to-[#32CD32]/50
                  @break
                @case('Annulé')
                  from-[#FF6347] to-[#FF6347]/50
                  @break
                @case('Reporté')
                  from-[#2E705A] to-[#2E705A]/50
                  @break
                @case('Planifié')
                  from-[#2552BA] to-[#2552BA]/50
                  @break
                @default
                  from-[#3A705A] to-[#3A705A]/50
              @endswitch
                text-white p-3 rounded-l-md">
        <div
          class="px-2 py-1 text-white text-sm @switch($p['project_type'])
              @case('Intra')
                bg-[#1565c0]
                @break
              @case('Inter')
                bg-[#7209b7]
                @break
              @case('Interne')
                bg-[#0ABFDB]
                @break 
            
              @default
    
              @endswitch text-center w-36 absolute -left-10 top-3 -rotate-45 shadow-sm">
          <p class="text-white text-sm">{{ $p['project_type'] }}</p>
        </div>
        <div class="flex flex-col justify-center ml-8 mt-2">
          <h5 class="text-white text-xl font-medium">
            @if ($p['dateDebut'] != null)
              {{ $p['dateDebut'] ? Carbon\Carbon::parse($p['dateDebut'])->format('Y') : '' }}
            @else
              --
            @endif
          </h5>
          <div class="inline-flex items-end gap-2">
            <h5 class="text-white text-4xl font-semibold">
              @if ($p['dateDebut'] != null)
                {{ $p['dateDebut'] ? Carbon\Carbon::parse($p['dateDebut'])->format('d') : '' }}
              @else
                --
              @endif -
            </h5>
            <h5 class="text-white text-xl">
              @if ($p['dateFin'] != null)
                {{ $p['dateFin'] ? Carbon\Carbon::parse($p['dateFin'])->format('d') : '' }}
              @else
                --
              @endif
            </h5>
          </div>
          <div class="inline-flex items-end gap-4">
            <h5 class="text-white text-xl font-semibold">
              @if ($p['dateDebut'] != null)
                {{ $p['dateDebut'] ? Carbon\Carbon::parse($p['dateDebut'])->format('M') : '' }}
              @else
                --
              @endif
            </h5>
            <h5 class="text-white text-lg">
              @if ($p['dateFin'] != null)
                {{ $p['dateFin'] ? Carbon\Carbon::parse($p['dateFin'])->format('M') : '' }}
              @else
                --
              @endif
            </h5>
          </div>
        </div>
      </div>
      <div class="grid grid-cols-subgrid col-span-2 gap-4 p-3">
        <div class="grid grid-cols-1 gap-2">
          <div class="w-full">
            <h1 class="text-gray-600 text-xl font-medium" title="{{ $p['module_name'] }}">
              @if (isset($p['module_name']) && $p['module_name'] != 'Default module')
                {{ $p['module_name'] }}
              @else
                <span class="text-gray-600">--</span>
              @endif
            </h1>
          </div>
          <div class="flex flex-col gap-2">
            <div class="grid grid-cols-6 items-center gap-x-6">

              <span class="grid grid-cols-subgrid col-span-6">
                <div class="inline-flex items-center gap-x-3 w-full flex-wrap">
                  @if (isset($p['idCfp']))

                    @if (isset($p['logoCfp']))
                      <div class="w-28 h-16 bg-gray-200 rounded-xl relative">
                        <x-icon-badge />
                        <img src="/img/entreprises/{{ $p['logoCfp'] }}" alt="logo"
                          class="w-full h-full rounded-xl object-cover">
                      </div>
                    @elseif (!isset($p['logoCfp']) && isset($p['initialnameCfp']))
                      <span
                        style="background: #e5e7eb; padding: 2px 8px; border-radius: 50%; cursor: pointer;">{{ $p['initialnameCfp'] }}</span>
                    @else
                      <span class="text-gray-400">--</span>
                    @endif
                  @else
                    @if (isset($p['etp_logo']))
                      <div class="w-28 h-16 bg-gray-200 rounded-xl relative">
                        <x-icon-badge />
                        <img src="/img/entreprises/{{ $p['etp_logo'] }}" alt="logo"
                          class="w-full h-full rounded-xl object-cover">
                      </div>
                    @elseif (!isset($p['etp_logo']) && isset($p['etp_initial_name']))
                      <span
                        style="background: #e5e7eb; padding: 2px 8px; border-radius: 50%; cursor: pointer;">{{ $p['etp_initial_name'] }}</span>
                    @else
                      <span class="text-gray-400">--</span>
                    @endif

                  @endif

                  @if (isset($p['etp_name']))

                    @if (count($p['etp_name']) > 0 && count($p['etp_name']) <= 3)
                      @foreach ($p['etp_name'] as $etp)
                        @if (isset($etp->etp_logo))
                          <div class="w-20 h-10 bg-gray-200 rounded-xl relative capitalize"
                            title="{{ $etp->etp_name }}">
                            <x-icon-badge />
                            <img src="/img/entreprises/{{ $etp->etp_logo }}" alt="logo"
                              class="w-full h-full rounded-xl object-cover">
                          </div>
                        @elseif (!isset($etp->etp_logo) && isset($etp->etp_name))
                          <span title="{{ $etp->etp_name }}"
                            class="w-20 h-10 bg-gray-200 rounded-xl relative uppercase flex items-center justify-center">{{ $etp->etp_name[0] }}</span>
                        @else
                          <span class="text-gray-400">--</span>
                        @endif
                      @endforeach
                    @endif

                    @if (count($p['etp_name']) > 0 && count($p['etp_name']) > 3)
                      @foreach ($p['etp_name'] as $etp)
                        @if (isset($etp->etp_name))
                          <span class="text-gray-400 capitalize">{{ $etp->etp_name }} -</span>
                        @else
                          <span class="text-gray-400">--</span>
                        @endif
                      @endforeach
                    @endif
                  @endif

                </div>
              </span>

              <span class="grid grid-cols-subgrid col-span-4">
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-subgrid gap-4 col-span-4 p-3">
      <div class="grid grid-cols-5 gap-2">
        <div class="grid grid-cols-subgrid gap-4 col-span-2">
          <div class="w-full">
            <div class="flex flex-col">
              <h5 class="text-gray-400">Lieu</h5>
              <p class="text-gray-600" title="Salle 05 - Hotel Radison Blue Andraharo">
                @if (isset($p['salle_name']))
                  {{ $p['salle_name'] }}
                @else
                  --
                @endif
                @if (isset($p['salle_quartier']))
                  {{ $p['salle_quartier'] }} -
                @else
                  --
                @endif
                @if (isset($p['ville']))
                  {{ $p['ville'] }} -
                @else
                  --
                @endif
                @if (isset($p['salle_code_postal']))
                  {{ $p['salle_code_postal'] }}
                @else
                  --
                @endif
              </p>
            </div>
          </div>
          <div class="grid grid-cols-3 gap-3">
            <div class="w-full">
              <div class="flex flex-col">
                <p class="text-gray-600">{{ $p['seanceCount'] }} <span class="text-gray-400">Sessions</span></p>
              </div>
            </div>
            <div class="w-full">
              <p class="text-gray-600">
                @if ($p['totalSessionHour'] != null)
                  {{ $p['totalSessionHour'] }}
                  <span class="text-gray-400">Heures</span>
                @endif
              </p>
            </div>
            <div class="w-full">
              <div class="flex flex-col">
                {{-- <p class="text-gray-600">{{ $p['apprCount'] }} <span class="text-gray-400">Apprenants</span></p> --}}
              </div>
            </div>
          </div>
        </div>
        <div class="grid grid-cols-1 gap-3">
          <div class="w-full">
            <div class="flex flex-col">
              <h5 class="text-gray-400">Statut</h5>
              <span
                class="inline-flex items-center gap-2 px-2 py-1 text-sm text-white w-[90px] justify-center 
                        @switch($p['project_status'])
                          @case('En préparation')
                            bg-[#66CDAA]
                            @break
                          @case('Réservé')
                            bg-[#33303D]
                            @break
                          @case('En cours')
                            bg-[#1E90FF]
                            @break
                          @case('Terminé')
                            bg-[#32CD32]
                            @break
                          @case('Annulé')
                            bg-[#FF6347]
                            @break
                          @case('Reporté')
                            bg-[#2E705A]
                            @break
                          @case('Planifié')
                            bg-[#2552BA]
                            @break
                        
                          @default
                          bg-[#3A705A]
                        @endswitch
                      ">
                {{ $p['project_status'] }}
              </span>
            </div>
          </div>
          <div class="w-full">
            <div class="flex flex-col">
              <h5 class="text-gray-400">Formateur</h5>
              <p class="text-gray-600 flex flex-row items-center gap-2">
                @if (count($p['formateurs']) > 0)
                  @foreach ($p['formateurs'] as $pf)
                    @if (isset($pf->form_photo))
                      <img class="w-8 h-8 object-cover rounded-full cursor-pointer"
                        src="/img/formateurs/{{ $pf->form_photo }}" alt="photo"
                        title="{{ $pf->form_name }} {{ $pf->form_firstname }}">
                    @else
                      <span title="{{ $pf->form_name }} {{ $pf->form_firstname }}"
                        style="background: #e5e7eb; padding: 2px 8px; border-radius: 50%; cursor: pointer;">{{ $pf->form_initial_name }}</span>
                    @endif
                  @endforeach
                @else
                  <span class="text-gray-400">--</span>
                @endif
              </p>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 items-start">

        </div>

        <div class="grid grid-cols-1 gap-3">
          <div class="h-full w-full flex items-center gap-1 justify-end">
            <span
              class="inline-flex items-center gap-2 px-2 py-1 w-[90px] justify-center
                @switch($p['modalite'])
                          @case('Présentielle')
                            text-[#00b4d8]
                            @break
                          @case('En ligne')
                            text-[#fca311]
                            @break
                          @case('Blended')
                            text-[#005f73]
                            @break
                        
                          @default
                          text-[#00b4d8]
                        @endswitch
                ">
              {{ $p['modalite'] }}
              <div class="btn-group h-max">
                <button type="button" title="Cliquer pour afficher le menu"
                  class="w-8 h-8 bg-[#A462A4] rounded-md hover:bg-[#A462A4]/90 duration-150 cursor-pointer shadow-sm shadow-purple-500"
                  data-bs-toggle="dropdown" aria-expanded="false">
                  <span class=""><i class="fa-solid fa-bars-staggered text-white"></i></span>
                </button>
                <ul class="dropdown-menu">
                  <x-dropdown-item icontype="solid" icon="eye"
                    route="{{ route('projets.particulier.detail', $p['idProjet']) }}" label="Aperçu" />
                  @if ($p['project_type'] != 'Intra' && $p['project_type'] != 'Inter')
                    @if ($p['project_status'] != 'Terminé')
                      <hr class="border-[1px] border-gray-400 my-2">
                      <x-dropdown-item icontype="solid" icon="check" type="btn"
                        target="confirmerProjet{{ $p['idProjet'] }}" label="Valider le projet" />
                      <x-dropdown-item icontype="solid" icon="trash" type="btn"
                        target="supprimerProjet{{ $p['idProjet'] }}" label="Supprimer" />
                      <hr class="border-[1px] border-gray-400 my-2">
                      @if ($p['project_status'] != 'Annulé')
                        <x-dropdown-item icontype="solid" icon="xmark" type="btn"
                          target="annulerProjet{{ $p['idProjet'] }}" label="Annuler" />
                      @endif
                      @if ($p['project_status'] != 'Reporté')
                        <x-dropdown-item icontype="solid" icon="right-left" type="btn"
                          target="reporterProjet{{ $p['idProjet'] }}_lg" label="Reporter" />
                      @endif
                    @endif
                  @endif
                </ul>
              </div>
          </div>
          <div class="inline-flex items-center justify-end gap-1">
            <i class="fa-solid fa-star text-gray-600 text-sm"></i>
            <p class="text-gray-500 font-medium">4.5</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="grid grid-cols-8 gap-4 py-2">
    <div class="grid col-span-1"></div>
    <div class="grid grid-cols-subgrid col-span-7">
      @if ($p['project_description'] != null)
        {{ $p['project_description'] }}
      @else
        <span class="text-gray-600">--</span>
      @endif
    </div>
  </div>
</li>
