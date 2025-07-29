@php
    $entreprise ??= 'LECOFRUIT MADA';
    $statut ??= '';
    $color ??= '[#A462A4]';
@endphp

<div class="inline-flex items-center justify-between py-1 pl-8 border-l-4 border-{{ $color }}">
  <div class="flex flex-col">
    <div class="flex gap-2 w-full items-center">
      <p class="text-lg font-semibold text-gray-600">{{ $entreprise }}</p>
      <p class="underline font-semibold text-base rounded-md text-red-500">{{ $statut }}</p>
    </div>
    <p class="text-lg text-gray-500">14h - 16h à Carlton Antananarivo</p>
  </div>
  <div class="relative">
    <i id="menuIcon" class="fa-solid fa-ellipsis-vertical text-2xl text-gray-500 border rounded-full w-10 h-10 flex items-center justify-center"></i>
    <div id="dropdownMenu" class="absolute z-50 hidden right-0 mt-2 bg-white border border-gray-300 p-2 rounded flex flex-col gap-2">
      <p class="text-lg text-gray-600 px-4 hover:bg-gray-100" >Supprimer</p>
      <p class="text-lg text-gray-600 px-4 hover:bg-gray-100" >Annuler</p>
      <p class="text-lg text-gray-600 px-4 hover:bg-gray-100" >Reporter</p>
    </div>
  </div>
</div>