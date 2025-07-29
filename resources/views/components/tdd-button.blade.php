@php
  $buttonId ??= '';
  $idEp ??= '';
@endphp

<x-tdd>
  <div class="btn_id" id="{{ $buttonId }}">
    <button id="mainButton" data-se="{{ $buttonId }}" data-ep='{{ $idEp }}'
      class="main-button w-4 h-4 rounded-md bg-gray-50 hover:bg-gray-100  border-[1px] border-gray-200"></button>
    <div id="subButtons" class="sub-buttons hidden">
      <div class="relative inline-block text-left group ">
        <button id="not" data-val=null
          class="sub-button w-4 h-4 rounded-md hover:bg-gray-200 flex justify-center items-center"
          data-color="rgb(98 105 117)">
          <i class="fa-solid fa-question text-base text-gray-500"></i>
        </button>
        <div
          class="hidden group-hover:block absolute z-10 top-10 left-1/2 transform -translate-x-1/2 w-28 bg-gray-50 border-[1px] border-gray-300 shadow-lg rounded-md">
          <p class="px-2 py-2 text-center text-gray-400">Non définis</p>
        </div>
      </div>
      <div class="relative inline-block text-left group">
        <button id="present" data-val=3
          class="sub-button w-4 h-4 rounded-md hover:bg-green-200 flex justify-center items-center"
          data-color="rgb(35 182 89)">
          <i class="fa-solid fa-check text-base text-green-500"></i>
        </button>
        <div
          class="hidden group-hover:block absolute z-10 top-10 left-1/2 transform -translate-x-1/2 w-24 bg-gray-50 border-[1px] border-gray-300 shadow-lg rounded-md">
          <p class="px-2 py-2 text-center text-gray-400">Présent</p>
        </div>
      </div>
      <div class="relative inline-block text-left group">
        <button id="partiel" data-val=2
          class="sub-button w-4 h-4 rounded-md hover:bg-yellow-200 flex justify-center items-center"
          data-color="rgb(211 161 7)">
          <i class="fa-solid fa-exclamation text-base text-yellow-500"></i>
        </button>
        <div
          class="hidden group-hover:block absolute z-10 top-10 left-1/2 transform -translate-x-1/2 w-48 bg-gray-50 border-[1px] border-gray-300 shadow-lg rounded-md">
          <p class="px-2 py-2 text-center text-gray-400">Partiellement Présent</p>
        </div>
      </div>

      <div class="relative inline-block text-left group">
        <button id="absent" data-val=1
          class="sub-button w-4 h-4 rounded-md hover:bg-red-200 flex justify-center items-center"
          data-color="rgb(235 105 106)">
          <i class="fa-solid fa-xmark text-base text-red-400"></i>
        </button>
        <div
          class="hidden group-hover:block absolute z-10 top-10 left-1/2 transform -translate-x-1/2 w-24 bg-gray-50 border-[1px] border-gray-300 shadow-lg rounded-md">
          <p class="px-2 py-2 text-center text-gray-400">Absent</p>
        </div>
      </div>
    </div>
  </div>
</x-tdd>
