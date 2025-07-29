@php
    $id ??= '';
    $titre ??= '';
    $item ??= '';
    $onClick ??= '';
@endphp
<div class="dropdown">
    <div tabindex="0" class="inline-flex items-center w-full unselectedFilter_{{ $id }}">
        <span id="{{ $id }}" class="btnDrop">
            {{ $titre }}
            <i class="fa-solid fa-chevron-down iconDrop duration-200 iconDrop-{{ $id }}"></i>
        </span>
    </div>

    <div tabindex="0" class="inline-flex items-center w-full selectedFilter_{{ $id }} hidden">
        <span id="{{ $id }}" class="btnDropSelected w-full px-2">
            {{ $titre }}
        </span>
        <div class="w-10 btnDropSelectedIcon ml-[1px] iconClose-{{ $id }}">
            <i class="fa-solid fa-xmark iconClose duration-200"></i>
        </div>
    </div>
    {{-- countedButton_{{ $id }} --}}
    <div tabindex="0" id="drop-{{ $id }}"
        class="dropdown-content menu menu-sm bg-base-100 rounded-box z-[1] w-full p-3 shadow">
        <div class="w-full inline-flex items-center justify-between">
            <h5 class="text-xl font-semibold text-gray-700">{{ $titre }}</h5>
        </div>
        <span class="inline-flex items-center gap-3 my-2">
            @if ($titre != 'Période de formation' && $titre != 'Type de projet' && $titre != 'Type de financement')
                <label id="search-{{ $id }}"
                    class="input input-bordered w-full input-sm flex items-center gap-2">
                    <input type="search" id="input-{{ $id }}" onkeyup="searchInput('{{ $id }}')"
                        class="grow w-full" placeholder="Search" />
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                        class="h-4 w-4 opacity-70">
                        <path fill-rule="evenodd"
                            d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
                            clip-rule="evenodd" />
                    </svg>
                </label>
            @endif
        </span>

        <ul id="list-{{ $id }}" class="w-full flex flex-col space-y-2 max-h-[20em] overflow-y-scroll my-2">
            {{ $slot }}
        </ul>

        {{-- <div class="w-full mt-2">
            <button class="btn btn-neutral w-full hover:text-white countedButton_{{ $id }}">Afficher <span
                    class="countSelected_{{ $id }}"></span> {{ $item }}</button>
        </div> --}}
    </div>
</div>
