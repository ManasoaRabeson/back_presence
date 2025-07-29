@php
  $id ??= '';
  $titre ??= '';
  $drop ??= [];
@endphp
<div class="relative my-3">
  <div class="inline-flex items-center min-w-[200px] unselectedFilter_{{ $id }}">
    <span id="{{ $id }}" class="formulaire_input btnDrop flex" onclick="dropShow('{{ $id }}')">
      <p>{{ $titre }}</p>
      <input type="hidden" name="{{ $id }}" id="{{ $id }}" value="all"
        class="h-full cursor-pointer px-2 capitalize">
      <i class="fa-solid fa-chevron-down iconDrop duration-200 iconDrop-{{ $id }}"></i>
    </span>
  </div>

  <div id="drop-{{ $id }}"
    class="drop-filter hidden w-full py-3 shadow-sm bg-white border-[1px] border-gray-100 mt-2 absolute top-11 z-50">
    <span class="inline-flex items-center gap-3 my-2">
      @if ($titre != 'Période de formation')
        <p
          class="resetClick_{{ $id }} text-gray-400 hover:text-purple-500 duration-100 cursor-pointer hover:underline underline-offset-2">
          Réinitialiser</p>
        <x-v-separator />
        <div id="search-{{ $id }}" onclick="dropSearch('{{ $id }}')" class="group cursor-pointer">
          <i class="fa-solid fa-search text-gray-500 group-hover:text-purple-500 duration-200 "></i>
        </div>
      @endif
    </span>
    <span id="span-search-{{ $id }}" class="overflow-hidden span-search hidden">
      <input class="inputSearch" id="input-{{ $id }}" onkeyup="searchInput('{{ $id }}')">
    </span>

    <ul id="list-{{ $id }}" class="w-full flex flex-col max-h-[20em] overflow-y-scroll my-2">
    <span id="inputEntreprise">
        <li id="{{$id}}" class="input_{{$id}} option pointer cursor-pointer hover:bg-slate-100 text-start p-2" data-value="all">
            Tous les modules
        </li>
    </span> 
      @foreach ($drop as $item)
          <span id="inputEntreprise">
            <li id="{{$id}}" class="input_{{ $id }} option pointer cursor-pointer hover:bg-slate-100 text-start p-2" data-value="{{ $item->idModule }}">
              {{ $item->module_name }}
            </li>
          </span>
      @endforeach
    </ul>
  </div>
</div>