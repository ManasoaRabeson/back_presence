@php
  $group = '';
  $titre ??= '';
  $nb = '';
@endphp

<div
  class="z-40 list-group px-3 py-2 bg-white border rounded-lg shadow-md transform scale-0 group-hover{{ $group }}:scale-100 absolute transition duration-300 ease-in-out origin-top w-[205px] delay-500 !hover:delay-300">
  <div class="flex flex-col">
    <p class="text-base font-semibold">{{ $titre }} {{ $nb }}</p>
    <p class="text-gray-400">{{ $slot }}</p>
  </div>
</div>
