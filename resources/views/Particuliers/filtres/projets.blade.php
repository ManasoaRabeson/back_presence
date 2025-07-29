<div class="flex flex-col mt-12 gap-4">
    <span class="inline-flex items-center w-full justify-between">
      <h3 class="text-2xl font-semibold text-gray-700 count_card_filter"></h3>

      <button onclick="location.reload()" class="inline-flex items-center gap-2 text-purple-500">
        <i class="fa-solid fa-rotate-right"></i>
        Réinitialiser le filtre
      </button>
    </span>
    <div class="grid 2xl:grid-cols-5 md:grid-cols-4 gap-4 my-2">
      <div class="grid col-span-1">
        <x-drop-filter id="statut" titre="Statut" item="Statut(s)" onClick="refresh('statut')" item="Projets">
          <span id="filterStatut"></span>
        </x-drop-filter>
      </div>
      <div class="grid col-span-1">
        <x-drop-filter id="periode" titre="Période de formation" item="Période(s)" onClick="refresh('periode')"
          item="Projets">
          <span id="filterPeriode"></span>
        </x-drop-filter>
      </div>
      <div class="grid col-span-1">
        <x-drop-filter id="cours" titre="Cours" item="Cours" onClick="refresh('cours')" item="Projets">
          <span id="filterModule"></span>
        </x-drop-filter>
      </div>
      <div class="grid col-span-1">
        <x-drop-filter id="ville" titre="Ville" item="Ville" onClick="refresh('ville')" item="Projets">
          <span id="filterVille"></span>
        </x-drop-filter>
      </div>
      <div class="grid col-span-1">
        <x-drop-filter id="financement" titre="Type de financement" item="Financement"
          onClick="refresh('financement')" item="Projets">
          <span id="filterFinancement"></span>
        </x-drop-filter>
      </div>
    </div>
</div>