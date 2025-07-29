@php
  $id ??= '';
  $initial ??= '';
  $nom ??= '';
  $exemple ??= '';
  $check ??= false;
  $onclick ??= '';
@endphp
<li
  class="grid grid-cols-5 w-full gap-2 justify-between px-3 py-2 border-[1px] border-gray-100 cursor-pointer hover:bg-gray-50 duration-200 rounded-md bg-white">
  <div class="col-span-4">
    <div class="inline-flex items-center gap-2">

      <div class="flex flex-col gap-0">
        <p class="font-normal text-base text-gray-700">{{ $nom }}</p>
        <p class="text-sm text-gray-400 lowercase">{{ $exemple }},...</p>
      </div>
    </div>
  </div>
  <div class="grid col-span-1 items-center justify-center w-full">
    <div onclick="{{ $onclick }}"
      class="icon w-10 h-10 rounded-full flex items-center justify-center bg-green-100 cursor-pointer hover:bg-green-50 group/icon duration-150">
      <i class="fa-solid fa-plus text-green-500 text-sm group-hover/icon:text-green-600 duration-150"></i>
    </div>
  </div>
</li>
