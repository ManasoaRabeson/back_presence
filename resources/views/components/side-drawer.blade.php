@php
  $projects ??= null;
  $count ??= null;
@endphp
<div
  class="__drawer z-30 w-0 h-full fixed left-[50px] border-[1px] border-gray-100 inline-flex items-start transition-all duration-300 truncate">
  <div
    class="content-drawer w-full hidden h-full transition-all duration-150 transform bg-white border-r-[1px] border-gray-100 shadow-sm">
    <section class="section hidden bg-white" id="projets">
      <div class="flex flex-col w-full">
        <div class="w-full inline-flex items-center justify-between p-2">
          <label class="text-xl text-gray-500 font-semibold">Projets {{ now()->format('Y') }}</label>
          <div
            class="w-9 h-9 close rounded-md flex items-center justify-center cursor-pointer group/hide duration-150 bg-gray-100">
            <i
              class="fa-solid fa-xmark text-md text-gray-300 group-hover/hide:text-gray-500 cursor-pointer duration-150"></i>
          </div>
        </div>
        <ul class="flex flex-col w-full p-1 duration-700 transition-all">
          @foreach (['in_progress', 'planed', 'reserved', 'trashed', 'in_preparation', 'finished', 'reported', 'closed'] as $status)
            <li class="w-full py-1">
              <details class="group/detail1 w-full flex flex-col">
                <summary class="hover:bg-gray-100 duration-300 cursor-pointer inline-flex items-center gap-0 w-full">
                  <div class="w-6 h-6 flex items-center justify-center">
                    <i
                      class="fa-solid fa-chevron-right group-open/detail1:rotate-90 transition-all duration-200 text-gray-300"></i>
                  </div>
                  <div class="w-6 h-6 flex items-center justify-center mx-1">
                    @if ($status == 'in_progress')
                      <i class="fa-solid fa-folder text-[#1E90FF]"></i>
                    @elseif ($status == 'reserved')
                      <i class="fa-solid fa-folder text-[#33303D]"></i>
                    @elseif ($status == 'trashed')
                      <i class="fa-solid fa-folder text-[#FF6347]"></i>
                    @elseif ($status == 'in_preparation')
                      <i class="fa-solid fa-folder text-[#66CDAA]"></i>
                    @elseif ($status == 'finished')
                      <i class="fa-solid fa-folder text-[#32CD32]"></i>
                    @elseif ($status == 'reported')
                      <i class="fa-solid fa-folder text-[#2E705A]"></i>
                    @elseif ($status == 'planed')
                      <i class="fa-solid fa-folder text-[#2552BA]"></i>
                    @elseif ($status == 'closed')
                      <i class="fa-solid fa-folder text-[#828282]"></i>
                    @endif

                  </div>
                  <div class="h-6 text-md font-normal flex flex-row text-gray-500">
                    @if ($status == 'in_progress')
                      En cours
                    @elseif ($status == 'reserved')
                      Réservé
                    @elseif ($status == 'trashed')
                      Annulé
                    @elseif ($status == 'in_preparation')
                      En préparation
                    @elseif ($status == 'finished')
                      Récemment terminé
                    @elseif ($status == 'reported')
                      Reporté
                    @elseif ($status == 'planed')
                      Planifié
                    @elseif ($status == 'closed')
                      Cloturé
                    @endif
                    <span class="ml-4">{{ isset($count[$status]) ? count($count[$status]) : 0 }}</span>
                  </div>
                </summary>
                @isset($projects[$status])
                  @foreach ($projects[$status] as $etp_name => $modules)
                    <details class="w-full flex flex-col group/detail2">
                      <summary
                        class="hover:bg-gray-100 duration-300 cursor-pointer inline-flex items-center gap-0 w-full pl-3">
                        <div class="w-6 h-6 flex items-center justify-center">
                          <i
                            class="fa-solid fa-chevron-right group-open/detail2:rotate-90 transition-all duration-200 text-gray-300"></i>
                        </div>
                        <div class="w-6 h-6 flex items-center justify-center mx-1">
                          <i class="fa-regular fa-folder text-gray-300"></i>
                        </div>
                        <div class="h-6 text-md font-normal flex flex-row text-gray-500">
                          {{ ($etp_name !== '') ? $etp_name : 'Interne' }}
                          <span class="ml-4">{{ count($modules) }}</span>
                        </div>
                      </summary>
                      @foreach ($modules as $module_name => $ps)
                        <details class="w-full flex flex-col group/detail3">
                          <summary
                            class="hover:bg-gray-100 duration-300 cursor-pointer inline-flex items-center gap-0 w-full pl-6">
                            <div class="w-6 h-6 flex items-center justify-center">
                              <i
                                class="fa-solid fa-chevron-right group-open/detail3:rotate-90 transition-all duration-200 text-gray-300"></i>
                            </div>
                            <div class="w-6 h-6 flex items-center justify-center mx-1">
                              <i class="fa-regular fa-folder text-gray-300"></i>
                            </div>
                            <div class="h-6 text-md font-normal text-gray-500">{{ $module_name }}</div>
                          </summary>
                          @foreach ($ps as $p)
                              <x-sidebar-project-link :id="$p->idProjet" :reference="$p->project_reference" :date="$p->dateDebut" :role="$role"
                            :students="DashboardFormat::getProjectStudents($p->idProjet)" :formateurs="DashboardFormat::getProjectFormateurs($p->idProjet)" />
                          @endforeach
                        </details>
                      @endforeach
                    </details>
                  @endforeach
                @endisset
              </details>
            </li>
          @endforeach
        </ul>
      </div>
    </section>

    <section class="section hidden" id="sessions">
      <div class="flex flex-col w-full items-center">
        <div class="w-full inline-flex items-center justify-between p-2">
          <label class="text-xl text-gray-500 font-semibold">Sessions</label>
          <div class="inline-flex items-center gap-0">
            <x-tooltip content="Agenda" class="top-0">
              <a href="/agendaCfps"
                class="w-9 h-9 rounded-md flex items-center group/hover justify-center cursor-pointer duration-150">
                <i
                  class="fa-solid fa-expand text-md text-gray-300 group-hover/hover:text-gray-500 duration-150 cursor-pointer"></i>
              </a>
            </x-tooltip>
            <div
              class="w-9 h-9 close rounded-md flex items-center justify-center cursor-pointer group/hide duration-150 bg-gray-100">
              <i
                class="fa-solid fa-xmark text-md text-gray-300 group-hover/hide:text-gray-500 duration-150 cursor-pointer"></i>
            </div>
          </div>
        </div>
        <div class="flex flex-col w-full p-1">
          <div class="w-full flex flex-col group/detail1 gap-1 py-1">
            <details open class="flex flex-col gap-1">
              <summary class="list-none inline-flex items-center justify-start">
                <label class="text-md text-gray-500 font-semibold">Aujourd'hui</label>
              </summary>
              {{-- Data card-session => client, debut, fin, localisation, ville --}}
              <x-card-session statut="En cours" id="session1" client="AWS Fiaro" debut="14h" fin="16h"
                localisation="Fiaro" couleur="purple" />
            </details>
          </div>
        </div>
        <ul class="flex flex-col w-full p-1">
          <li class="w-full flex flex-col group/detail1 gap-1 py-1">
            <details open class="flex flex-col gap-1">
              <summary class="list-none inline-flex items-center justify-start">
                <label class="text-md text-gray-500 font-semibold">Lun, 23 oct. 2023</label>
              </summary>
              {{-- Data card-session => client, debut, fin, localisation, ville --}}
              <x-card-session statut="Rattrapage" id="session2" client="AWS Fiaro" debut="14h" fin="16h"
                localisation="Fiaro" />
            </details>
          </li>
          <li class="w-full flex flex-col group/detail1 gap-1 py-1">
            <details open class="flex flex-col gap-1">
              <summary class="list-none inline-flex items-center justify-start">
                <label class="text-md text-gray-500 font-semibold">Mar, 24 oct. 2023</label>
              </summary>
              {{-- Data card-session => client, debut, fin, localisation, ville --}}
              <x-card-session statut="Annuler" id="session3" client="TekFutura Zital Ankorondrano" debut="16h"
                fin="18h30" localisation="Ankorondrano" />
            </details>
          </li>
          <li class="w-full flex flex-col group/detail1 gap-1 py-1">
            <details open class="flex flex-col gap-1">
              <summary class="list-none inline-flex items-center justify-start">
                <label class="text-md text-gray-500 font-semibold">Mer, 25 oct. 2023</label>
              </summary>
              {{-- Data card-session => client, debut, fin, localisation, ville --}}
              <x-card-session statut="En préparation" id="session4" client="AWS Fiaro" debut="14h" fin="16h"
                localisation="Fiaro" />
              <x-card-session statut="En préparation" id="session5" client="TekFutura Zital Ankorondrano"
                debut="16h" fin="18h30" localisation="Ankorondrano" />
            </details>
          </li>
          <li class="w-full flex flex-col group/detail1 gap-1 py-1">
            <details open class="flex flex-col gap-1">
              <summary class="list-none inline-flex items-center justify-start">
                <label class="text-md text-gray-500 font-semibold">Mer, 25 oct. 2023</label>
              </summary>
              {{-- Data card-session => client, debut, fin, localisation, ville --}}
              <x-card-session statut="Reporté" id="session6" client="AWS Fiaro" debut="14h" fin="16h"
                localisation="Fiaro" />
              <x-card-session statut="En préparation" id="session7" client="TekFutura Zital Ankorondrano"
                debut="16h" fin="18h30" localisation="Ankorondrano" />
            </details>
          </li>
        </ul>
      </div>
    </section>
    <section class="section hidden" id="guides">
      <div class="flex flex-col w-full">
        <div class="w-full inline-flex items-center justify-between p-2">
          <label class="text-xl text-gray-500 font-semibold">Guides d'utilisation</label>
          <div class="w-9 h-9 rounded-md flex items-center justify-center cursor-pointer hover:bg-red-50 duration-300">
            <i class="fa-solid fa-xmark text-xl text-red-400 cursor-pointer"></i>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>
