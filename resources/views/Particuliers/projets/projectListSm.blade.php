<li id="" class="shadow-md border-[1px] min-w-[550px] overflow-x-scroll border-gray-100 rounded-md">
  <div class="grid grid-cols-1">
    <div class="grid col-span-1">
      <div class="grid grid-cols-6">
        <div
          class="grid col-span-2 gap-2 bg-gradient-to-br relative rounded-md overflow-hidden
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
                bg-purple-500
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
        <div class="grid grid-cols-subgrid col-span-3 gap-4 p-3">
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
                {{-- <i class="fa-solid fa-handshake text-gray-400" title="Entreprise"></i> --}}
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
              </div>
            </div>
            <div class="inline-flex items-center gap-4">
              <div class="">
                <p class="text-gray-600">{{ $p['seanceCount'] }} <span class="text-gray-400">Sessions</span></p>
              </div>
              <div class="">
                <p class="text-gray-600">
                  @if ($p['totalSessionHour'] != null)
                    {{ $p['totalSessionHour'] }}
                    <span class="text-gray-400">Heures</span>
                  @endif
                </p>
              </div>
              <div class="">
                {{-- <p class="text-gray-600">{{ $p['apprCount'] }} <span class="text-gray-400">Apprenants</span></p> --}}
              </div>
            </div>
          </div>
        </div>
        <div class="grid col-span-1 p-3">
          <div class="grid col-span-1 items-start h-full justify-end">
            <div class="btn-group h-max">
              <button type="button" title="Cliquer pour afficher le menu"
                class="w-8 h-8 bg-[#A462A4] rounded-md hover:bg-[#A462A4]/90 duration-150 cursor-pointer shadow-sm shadow-purple-500"
                data-bs-toggle="dropdown" aria-expanded="false">
                <span class=""><i class="fa-solid fa-bars-staggered text-white"></i></span>
              </button>
              <ul class="dropdown-menu">
                <x-dropdown-item icontype="solid" icon="eye"
                  route="{{ route('projets.particulier.detail', $p['idProjet']) }}" label="Aperçu" />
              </ul>
            </div>
          </div>
          <div class="grid col-span-1 items-end justify-end">
            <div class="inline-flex items-center justify-end gap-1">
              <i class="fa-solid fa-star text-gray-600 text-sm"></i>
              <p class="text-gray-500 font-medium">4.5</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="grid col-span-1 p-3">
      {{-- <hr class="border-[1px] border-gray-200 my-2"> --}}
      @if ($p['project_description'] != null)
        {{ $p['project_description'] }}
      @else
        <span class="text-gray-600">--</span>
      @endif
      <hr class="border-[1px] border-gray-200 my-2">
      <div class="flex flex-col gap-1">
        <div class="w-full">
          <div class="grid grid-cols-3">
            <div class="grid col-span-1">
              <div class="inline-flex items-center">
                <div class="w-[24px] flex justify-center items-center">
                </div>
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
                        
                    @endswitch
                  ">
                  {{ $p['project_status'] }}
                </span>

              </div>
            </div>

            <div class="inline-flex items-center">
              <div class="w-[24px] flex justify-center items-center">
              </div>
              <span class="inline-flex items-center text-blue-500 justify-center">
                Présentielle
              </span>
            </div>

            <div class="grid col-span-1">
              <div class="inline-flex items-center">
                <div class="w-[24px] flex justify-center items-center">
                  <i class="fa-solid fa-user-graduate text-gray-400"></i>
                </div>
                <p class="text-gray-600 flex flex-row items-center gap-2">
                  @if (count($p['formateurs']) > 0)
                    @foreach ($p['formateurs'] as $pf)
                      @if (isset($pf->form_photo))
                        <img class="w-10 h-10 object-cover rounded-xl cursor-pointer"
                          src="/img/formateurs/{{ $pf->form_photo }}" alt="photo"
                          title="{{ $pf->form_name }} {{ $pf->form_firstname }}">
                      @else
                        <span title="{{ $pf->form_name }} {{ $pf->form_firstname }}" class="rounded-xl cursor-pointer"
                          style="background: #e5e7eb; padding: 2px 8px;">{{ $pf->form_initial_name }}</span>
                      @endif
                    @endforeach
                  @else
                    <span class="text-gray-400">--</span>
                  @endif
                </p>
              </div>
            </div>

          </div>
        </div>
        <hr class="border-[1px] border-gray-200 my-2">

        <div class="w-full">
          <div class="inline-flex items-center">
            <div class="w-[24px] flex justify-center items-center">
              {{-- <h5 class="text-gray-400">Lieu</h5> --}}
              <i class="fa-solid fa-location-dot text-gray-400"></i>
            </div>
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
        <hr class="border-[1px] border-gray-200 my-2">

        <div class="grid grid-cols-3 gap-2">
          <div class="inline-flex items-center">
            <div class="w-[24px] flex justify-center items-center">
              <i class="fa-solid fa-sack-dollar text-gray-400"></i>
            </div>
          </div>

          <div class="inline-flex items-center">
            <div class="w-[24px] flex justify-center items-center">
              <i class="fa-solid fa-dollar text-gray-400"></i>
            </div>
            @if (isset($p['projectTotalPrice']))
              <p class="text-gray-500 font-medium">Ar {{ number_format($p['projectTotalPrice'], 0, '.', ' ') }} HT</p>
            @else
              <p class="text-gray-500 font-medium">Ar -- HT</p>
            @endif
          </div>

          <div class="inline-flex items-center">
            <div class="w-[24px] flex justify-center items-center">
              <i class="fa-solid text-gray-400 fa-file-invoice"></i>
            </div>
            <p class="text-gray-500 font-medium">
              20% - Facture n°: #347
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</li>
