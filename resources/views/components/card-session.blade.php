@php
  $id ??= '';
  $client ??= '';
  $statut ??= '';
  $debut ??= '';
  $fin ??= '';
  $localisation ??= '';
  $ville ??= 'Tanà';
  $couleur ??= 'amber';
  $statutCouleur ??= 'gray';
@endphp

<div
  class="relative w-full h-24 bg-gray-50 cursor-pointer hover:shadow-lg rounded-md flex flex-row justify-between duration-200 group/action items-center m-1 p-3">
  <div class="inline-flex items-center gap-2">
    <div class="w-1 rounded-md bg-{{ $couleur }}-400 h-9"></div>
    <div class="flex flex-col items-start p-2">
      <label class="text-sm font-semibold text-gray-500">{{ $client }}</label>
      <div class="inline-flex items-center gap-2">
        <p class="text-sm text-gray-400">{{ $debut }} à {{ $fin }}</p>
        <p class="text-sm text-gray-400">{{ $localisation }}, {{ $ville }}.</p>
      </div>
      <label
        class="text-sm px-2 py-1 @if ($statut === 'En préparation') text-white bg-[#66CDAA]  
      @elseif ($statut === 'Annuler') text-white bg-[#FF6347] @elseif ($statut === 'Reporté') text-white bg-cyan-400 @elseif ($statut === 'En cours') bg-[#1E90FF] text-white @else text-white bg-amber-400 @endif">{{ $statut }}</label>
    </div>
  </div>
  <div class="dropdown">
    <button type="button" data-bs-toggle="dropdown" aria-expanded="false">
      <div class="w-6 h-6 rounded-full hover:bg-gray-200 duration-150 cursor-pointer flex items-center justify-center">
        <i class="fa-solid fa-ellipsis-vertical text-gray-400 text-md"></i>
      </div>
    </button>
    <ul class="dropdown-menu">
      <li class="text-gray-400 px-3 py-1 hover:bg-gray-100 rounded-md duration-150 cursor-pointer">
        <button
          class="text-gray-400 px-3 py-1 hover:bg-gray-100 w-full text-left rounded-md duration-150 cursor-pointer"
          data-bs-toggle="modal" id="myButton" data-bs-target="#reporter">
          Reporter
        </button>
      </li>
      <li class="text-gray-400 px-3 py-1 hover:bg-gray-100 rounded-md duration-150 cursor-pointer">
        <button
          class="text-gray-400 px-3 py-1 hover:bg-gray-100 w-full text-left rounded-md duration-150 cursor-pointer"
          data-bs-toggle="modal" id="myButton" data-bs-target="#annuler">
          Annuler
        </button>
      </li>
      <li class="text-gray-400 px-3 py-1 hover:bg-gray-100 rounded-md duration-150 cursor-pointer">
        <button
          class="text-gray-400 px-3 py-1 hover:bg-gray-100 w-full text-left rounded-md duration-150 cursor-pointer"
          data-bs-toggle="modal" id="myButton" data-bs-target="#supprimer">
          Supprimer
        </button>
      </li>
    </ul>
  </div>
</div>
