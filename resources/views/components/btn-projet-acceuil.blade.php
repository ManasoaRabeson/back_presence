@php
  $label ??= '';
  $statut ??= '0';
  $url ??= '';
@endphp

@if ($statut == '0')
  <div class="flex flex-col cursor-pointer">
    <a href="{{ $url }}" class="inline-flex items-center gap-2 group/link px-3 py-1 rounded-md bg-gray-200 hover:bg-gray-300 duration-150">
      {{-- <i class="bi bi-person-check text-gray-700 text-base"></i> --}}
      <label class="text-base text-gray-700 duration-200 cursor-pointer">{{ $label }} </label>
    </a>
  </div>
@elseif ($statut == '1')
  <div class="flex flex-row items-center gap-2">
    <a href="{{ $url }}" class="inline-flex items-center gap-2 group/link px-3 py-1 rounded-md bg-gray-50">
      <i class="fa-solid fa-check text-green-500 text-base"></i>
      <label class="text-base text-gray-400 duration-200">{{ $label }} </label>
    </a>
  </div>
@elseif ($statut == '2')
  <div class="flex flex-col cursor-pointer">
    <a href="{{ $url }}" class="inline-flex items-center gap-2 group/link px-3 py-1 rounded-md bg-gray-100 hover:bg-gray-200 duration-150">
      <i class="fa-solid fa-exclamation text-amber-400 text-base"></i>
      <label class="text-base text-gray-700 duration-200 cursor-pointer">{{ $label }} </label>
    </a>
  </div>
@elseif ($statut == '3')
  <div class="flex flex-row items-center gap-2">
    <a href="{{ $url }}" class="inline-flex items-center gap-2 group/link px-3 py-1 rounded-md bg-gray-50">
      <i class="fa-solid fa-check text-green-500 text-base"></i>
      <label class="text-base text-gray-400 duration-200">{{ $label }} </label>
    </a>
    <a href="{{ $url }}" class="group/link px-2 py-1 rounded-md bg-gray-200 hover:bg-gray-300 duration-150 cursor-pointer">
      <i class="fa-solid fa-pen text-gray-500 text-base"></i>
      {{-- <label class="text-base text-gray-700 duration-200 cursor-pointer">Modifier</label> --}}
    </a>
  </div>
@elseif ($statut == 'disabled')
  <div class="flex flex-col">
    <a href="{{ $url }}" class="inline-flex items-center gap-2 group/link px-3 py-1 rounded-md bg-gray-50">
      {{-- <i class="fa-solid fa-exclamation text-amber-500 text-base"></i> --}}
      <label class="text-base text-gray-300 duration-200">{{ $label }} </label>
    </a>
  </div>
@endif
