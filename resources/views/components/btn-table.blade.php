@php
    $type ??= '';
    $route ??= '';
    $titre ??= '';
    $id ??= '';
@endphp

@if ($type === 'simple')
    <div class="flex flex-1 btn-group dropdown w-max">
        <div class="inline-flex items-center justify-end w-full">
            <button
                class="text-base text-gray-600 px-3 py-1 hover:bg-gray-200 bg-gray-100 transition duration-200 outline-none border-[1px] capitalize"
                type="button">
                <a href="{{ $route }}" class="transition duration-300 hover:text-gray-700">
                    {{ $first }}
                </a>
            </button>
            <button type="button"
                class="text-gray-600 px-3 py-1 h-full hover:bg-gray-200 bg-gray-100 transition-all border-none dropdown-toggle focus:bg-gray-200 border-[1px]"
                data-bs-toggle="dropdown" aria-expanded="false">
                <span class="visually-hidden">Toggle Dropdown</span>
            </button>
            <ul class="bg-white border-none shadow-md dropdown-menu">
                {{ $slot }}
            </ul>
        </div>
    </div>
@elseif($type === 'modal')
    <div class="flex justify-end flex-1 btn-group dropdown w-max">
        {{ $slot }}
    </div>
@elseif($type === 'drawer')
    <div class="flex flex-1 btn-group dropdown w-max">
        <div class="inline-flex items-center justify-end w-full">
            <button
                class="text-base text-gray-600 px-3 py-1 hover:bg-gray-200 bg-gray-100 transition duration-200 outline-none border-[1px] capitalize"
                type="button">
                <a class="text-base text-gray-600 transition duration-150 cursor-pointer dropdown-item"
                    data-bs-toggle="offcanvas" href="#{{ $id }}" role="button" aria-controls="offcanvas">
                    {{ $titre }}
                </a>
            </button>

            <button type="button"
                class="text-gray-600 px-3 py-1 h-full hover:bg-gray-200 bg-gray-100 transition-all border-none dropdown-toggle focus:bg-gray-200 border-[1px]"
                data-bs-toggle="dropdown" aria-expanded="false">
                <span class="visually-hidden">Toggle Dropdown</span>
            </button>
            <ul class="bg-white border-none shadow-md dropdown-menu">
                {{ $slot }}
            </ul>
        </div>
    </div>
@endif
